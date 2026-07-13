<?php

namespace App\Controllers;

use App\Models\BukuModel;
use App\Models\PeminjamanModel;
use App\Models\UserShelfModel;
use App\Models\AnggotaModel;

class UserDashboard extends BaseController
{
    public function index()
    {
        // Pastikan yang mengakses adalah anggota
        if (!session()->get('logged_in') || session()->get('role') != 'anggota') {
            return redirect()->to('/auth/login');
        }

        $peminjamanModel = new PeminjamanModel();
        $shelfModel = new UserShelfModel();
        $bukuModel = new BukuModel();
        $anggotaModel = new AnggotaModel();
        $id_anggota = session()->get('id_anggota');

        $anggota = $anggotaModel->find($id_anggota);
        $reading_goal = $anggota['reading_goal'] ?? 0;

        // Ambil riwayat peminjaman khusus user ini
        $riwayat = $peminjamanModel->select('peminjaman.*, buku.judul')
                                   ->join('buku', 'buku.id_buku = peminjaman.id_buku')
                                   ->where('peminjaman.id_anggota', $id_anggota)
                                   ->orderBy('peminjaman.id_pinjam', 'DESC')
                                   ->findAll();

        $shelf = $shelfModel->getUserShelf($id_anggota);
        $reading = array_values(array_filter($shelf, fn($item) => $item['status_baca'] === 'reading'));
        $wishlist = array_values(array_filter($shelf, fn($item) => $item['status_baca'] === 'wishlist'));
        $finished = array_values(array_filter($shelf, fn($item) => $item['status_baca'] === 'finished'));

        $favoriteCategories = [];
        foreach ($shelf as $item) {
            $category = $item['kategori'] ?? null;
            if ($category) {
                $favoriteCategories[$category] = ($favoriteCategories[$category] ?? 0) + 1;
            }
        }

        arsort($favoriteCategories);
        $favoriteCategory = array_key_first($favoriteCategories);

        // ── Smart Recommendations (Contextual) ──
        $recommendationData = $this->getSmartRecommendations($bukuModel, $shelf);
        $recommendations = $recommendationData['books'];
        $recommendationMessage = $recommendationData['message'];

        // ── Gamification: Heatmap & Streak ──
        $heatmapData = $this->getHeatmapData($id_anggota);
        $streakData = $this->getStreakData($id_anggota);

        // ── Bookmarks ──
        $bookmarks = $this->getUserBookmarks($id_anggota);

        // ── Gamification: Badges ──
        $db = \Config\Database::connect();
        $this->checkAndAwardBadges($id_anggota, $streakData, count($finished), $db);
        $badges = $db->table('user_badges')->where('id_anggota', $id_anggota)->get()->getResultArray();

        $data = [
            'riwayat' => $riwayat,
            'shelf' => $shelf,
            'reading' => $reading,
            'wishlist' => $wishlist,
            'finished' => $finished,
            'recommendations' => $recommendations,
            'recommendationMessage' => $recommendationMessage,
            'favoriteCategory' => $favoriteCategory,
            'reading_goal' => $reading_goal,
            'heatmapData' => $heatmapData,
            'streakData' => $streakData,
            'bookmarks' => $bookmarks,
            'badges' => $badges,
        ];

        return view('user/dashboard', $data);
    }

    public function updateReadingGoal()
    {
        if (!session()->get('logged_in') || session()->get('role') != 'anggota') {
            return redirect()->to('/auth/login');
        }

        $id_anggota = session()->get('id_anggota');
        $goal = (int) $this->request->getPost('reading_goal');

        $anggotaModel = new AnggotaModel();
        try {
            $anggotaModel->update($id_anggota, ['reading_goal' => $goal]);
        } catch (\CodeIgniter\Database\Exceptions\DataException $e) {
            // Ignore if there is no data to update (value is the same)
        }

        return redirect()->back()->with('success', 'Target membaca berhasil diperbarui.');
    }

    // ══════════════════════════════════════
    // Smart Recommendations
    // ══════════════════════════════════════
    private function getSmartRecommendations($bukuModel, $shelf)
    {
        $lastRead = null;
        // Find the most recently added to reading or finished
        foreach ($shelf as $item) {
            if (in_array($item['status_baca'], ['reading', 'finished'])) {
                $lastRead = $item;
                break;
            }
        }

        if (!$lastRead) {
            return [
                'books' => $bukuModel->orderBy('rating', 'DESC')->findAll(4),
                'message' => 'Rekomendasi terbaik kami untuk Anda mulai membaca.'
            ];
        }

        // Extract keywords from synopsis
        $sinopsis = strtolower($lastRead['sinopsis'] ?? '');
        $category = $lastRead['kategori'] ?? '';
        
        $stopwords = ['dan', 'di', 'ke', 'dari', 'yang', 'untuk', 'pada', 'adalah', 'ini', 'itu', 'dengan', 'dalam', 'sebuah'];
        $words = str_word_count(preg_replace('/[^a-z]/', ' ', $sinopsis), 1);
        $words = array_diff($words, $stopwords);
        
        // Count word frequency
        $freq = array_count_values($words);
        arsort($freq);
        $topKeywords = array_slice(array_keys($freq), 0, 3);

        $query = $bukuModel->where('id_buku !=', $lastRead['id_buku']);
        
        if (!empty($category)) {
            $query->groupStart()
                  ->where('kategori', $category);
            foreach ($topKeywords as $kw) {
                if (strlen($kw) > 3) $query->orLike('sinopsis', $kw);
            }
            $query->groupEnd();
        }

        $books = $query->orderBy('rating', 'DESC')->findAll(4);

        if (count($books) === 0) {
            $books = $bukuModel->where('id_buku !=', $lastRead['id_buku'])->orderBy('rating', 'DESC')->findAll(4);
        }

        return [
            'books' => $books,
            'message' => "Karena Anda menyukai <b>{$lastRead['judul']}</b> dan tema <b>{$category}</b>..."
        ];
    }

    // ══════════════════════════════════════
    // Bookmark API
    // ══════════════════════════════════════
    public function saveBookmark()
    {
        $id_anggota = session()->get('id_anggota') ?? 1;
        $id_buku = (int) $this->request->getPost('id_buku');
        $halaman = max(1, (int) $this->request->getPost('halaman'));
        $catatan = trim($this->request->getPost('catatan') ?? '');

        $db = \Config\Database::connect();

        $existing = $db->table('user_bookmarks')
            ->where('id_anggota', $id_anggota)
            ->where('id_buku', $id_buku)
            ->where('halaman', $halaman)
            ->get()->getRowArray();

        if ($existing) {
            $db->table('user_bookmarks')
                ->where('id_bookmark', $existing['id_bookmark'])
                ->update(['catatan' => $catatan]);
        } else {
            $db->table('user_bookmarks')->insert([
                'id_anggota' => $id_anggota,
                'id_buku' => $id_buku,
                'halaman' => $halaman,
                'catatan' => $catatan,
            ]);
        }

        return $this->response->setJSON(['status' => 'ok', 'message' => 'Bookmark tersimpan']);
    }

    public function deleteBookmark()
    {
        $id_anggota = session()->get('id_anggota') ?? 1;
        $id_bookmark = (int) $this->request->getPost('id_bookmark');

        $db = \Config\Database::connect();
        $db->table('user_bookmarks')
            ->where('id_bookmark', $id_bookmark)
            ->where('id_anggota', $id_anggota)
            ->delete();

        return $this->response->setJSON(['status' => 'ok', 'message' => 'Bookmark dihapus']);
    }

    public function listBookmarks($id_buku)
    {
        $id_anggota = session()->get('id_anggota') ?? 1;
        $db = \Config\Database::connect();

        $bookmarks = $db->table('user_bookmarks')
            ->where('id_anggota', $id_anggota)
            ->where('id_buku', $id_buku)
            ->orderBy('halaman', 'ASC')
            ->get()->getResultArray();

        return $this->response->setJSON(['bookmarks' => $bookmarks]);
    }

    // ══════════════════════════════════════
    // Annotations (Highlight text)
    // ══════════════════════════════════════
    public function saveAnnotation()
    {
        $id_anggota = session()->get('id_anggota') ?? 1;
        $id_buku = (int) $this->request->getPost('id_buku');
        $halaman = (int) $this->request->getPost('halaman');
        $teks_highlight = $this->request->getPost('teks_highlight');
        $warna = $this->request->getPost('warna') ?? 'yellow';
        $catatan = $this->request->getPost('catatan') ?? '';

        $db = \Config\Database::connect();
        
        $db->table('user_annotations')->insert([
            'id_anggota' => $id_anggota,
            'id_buku' => $id_buku,
            'halaman' => $halaman,
            'teks_highlight' => $teks_highlight,
            'warna' => $warna,
            'catatan' => $catatan
        ]);

        return $this->response->setJSON(['status' => 'ok', 'id_anotasi' => $db->insertID()]);
    }

    public function listAnnotations($id_buku)
    {
        $id_anggota = session()->get('id_anggota') ?? 1;
        $db = \Config\Database::connect();

        $annotations = $db->table('user_annotations')
            ->where('id_anggota', $id_anggota)
            ->where('id_buku', $id_buku)
            ->get()->getResultArray();

        return $this->response->setJSON(['annotations' => $annotations]);
    }

    // ══════════════════════════════════════
    // Reading Session Logging (for Gamification)
    // ══════════════════════════════════════
    public function logReadingSession()
    {
        $id_anggota = session()->get('id_anggota') ?? 1;
        $id_buku = (int) $this->request->getPost('id_buku');

        $db = \Config\Database::connect();
        $today = date('Y-m-d');

        $existing = $db->table('reading_sessions')
            ->where('id_anggota', $id_anggota)
            ->where('id_buku', $id_buku)
            ->where('read_date', $today)
            ->get()->getRowArray();

        if ($existing) {
            $db->table('reading_sessions')
                ->where('id_session', $existing['id_session'])
                ->update(['pages_read' => $existing['pages_read'] + 1]);
        } else {
            $db->table('reading_sessions')->insert([
                'id_anggota' => $id_anggota,
                'id_buku' => $id_buku,
                'read_date' => $today,
                'pages_read' => 1,
            ]);
        }

        return $this->response->setJSON(['status' => 'ok']);
    }

    // ══════════════════════════════════════
    // Private helpers for Gamification
    // ══════════════════════════════════════
    private function getHeatmapData(int $id_anggota): array
    {
        $db = \Config\Database::connect();
        $startDate = date('Y-m-d', strtotime('-365 days'));

        $sessions = $db->table('reading_sessions')
            ->select('read_date, SUM(pages_read) as total_pages')
            ->where('id_anggota', $id_anggota)
            ->where('read_date >=', $startDate)
            ->groupBy('read_date')
            ->orderBy('read_date', 'ASC')
            ->get()->getResultArray();

        $heatmap = [];
        foreach ($sessions as $s) {
            $heatmap[$s['read_date']] = (int) $s['total_pages'];
        }

        return $heatmap;
    }

    private function getStreakData(int $id_anggota): array
    {
        $db = \Config\Database::connect();

        $dates = $db->table('reading_sessions')
            ->distinct()
            ->select('read_date')
            ->where('id_anggota', $id_anggota)
            ->orderBy('read_date', 'DESC')
            ->get()->getResultArray();

        $readDates = array_column($dates, 'read_date');
        $currentStreak = 0;
        $longestStreak = 0;
        $tempStreak = 0;

        $today = date('Y-m-d');
        $yesterday = date('Y-m-d', strtotime('-1 day'));

        if (in_array($today, $readDates) || in_array($yesterday, $readDates)) {
            $checkDate = in_array($today, $readDates) ? $today : $yesterday;
            while (in_array($checkDate, $readDates)) {
                $currentStreak++;
                $checkDate = date('Y-m-d', strtotime($checkDate . ' -1 day'));
            }
        }

        foreach ($readDates as $i => $date) {
            if ($i === 0) {
                $tempStreak = 1;
            } else {
                $prevDate = date('Y-m-d', strtotime($readDates[$i - 1] . ' -1 day'));
                if ($date === $prevDate) {
                    $tempStreak++;
                } else {
                    $tempStreak = 1;
                }
            }
            $longestStreak = max($longestStreak, $tempStreak);
        }

        $totalDays = count($readDates);

        $badges = [];
        if ($currentStreak >= 3) $badges[] = ['icon' => '🔥', 'label' => 'Pembaca Rutin', 'desc' => '3+ hari berturut-turut'];
        if ($currentStreak >= 7) $badges[] = ['icon' => '⚡', 'label' => 'Kutu Buku', 'desc' => '7+ hari berturut-turut'];
        if ($currentStreak >= 30) $badges[] = ['icon' => '👑', 'label' => 'Legenda Literasi', 'desc' => '30+ hari berturut-turut'];
        if ($totalDays >= 10) $badges[] = ['icon' => '📚', 'label' => 'Penjelajah', 'desc' => '10+ hari membaca'];
        if ($totalDays >= 50) $badges[] = ['icon' => '🏆', 'label' => 'Master Baca', 'desc' => '50+ hari membaca'];

        return [
            'current' => $currentStreak,
            'longest' => $longestStreak,
            'totalDays' => $totalDays,
            'badges' => $badges,
        ];
    }

    private function getUserBookmarks(int $id_anggota): array
    {
        $db = \Config\Database::connect();

        return $db->table('user_bookmarks')
            ->select('user_bookmarks.*, buku.judul, buku.cover_url')
            ->join('buku', 'buku.id_buku = user_bookmarks.id_buku')
            ->where('user_bookmarks.id_anggota', $id_anggota)
            ->orderBy('user_bookmarks.created_at', 'DESC')
            ->get()->getResultArray();
    }

    private function checkAndAwardBadges(int $id_anggota, array $streakData, int $finishedCount, $db): void
    {
        $badgeBuilder = $db->table('user_badges');

        // Badge: Bookworm — Selesai membaca 3 buku atau lebih
        if ($finishedCount >= 3) {
            $exists = $badgeBuilder->where('id_anggota', $id_anggota)->where('badge_name', 'bookworm')->countAllResults(false);
            if (!$exists) {
                $badgeBuilder->insert(['id_anggota' => $id_anggota, 'badge_name' => 'bookworm']);
            }
        }

        // Badge: Marathon Reader — Streak membaca 3 hari berturut-turut
        if (($streakData['current'] ?? 0) >= 3 || ($streakData['longest'] ?? 0) >= 3) {
            $exists = $badgeBuilder->where('id_anggota', $id_anggota)->where('badge_name', 'marathon')->countAllResults(false);
            if (!$exists) {
                $badgeBuilder->insert(['id_anggota' => $id_anggota, 'badge_name' => 'marathon']);
            }
        }

        // Badge: Night Owl — Pernah membaca di jam 22:00 - 04:00
        $nightReading = $db->table('reading_sessions')
            ->where('id_anggota', $id_anggota)
            ->groupStart()
                ->where('HOUR(created_at) >=', 22)
                ->orWhere('HOUR(created_at) <', 4)
            ->groupEnd()
            ->countAllResults(false);
        if ($nightReading > 0) {
            $exists = $badgeBuilder->where('id_anggota', $id_anggota)->where('badge_name', 'night_owl')->countAllResults(false);
            if (!$exists) {
                $badgeBuilder->insert(['id_anggota' => $id_anggota, 'badge_name' => 'night_owl']);
            }
        }

        // Badge: First Steps — Menambahkan buku pertama ke rak
        $shelfCount = $db->table('user_shelf')->where('id_anggota', $id_anggota)->countAllResults(false);
        if ($shelfCount >= 1) {
            $exists = $badgeBuilder->where('id_anggota', $id_anggota)->where('badge_name', 'first_steps')->countAllResults(false);
            if (!$exists) {
                $badgeBuilder->insert(['id_anggota' => $id_anggota, 'badge_name' => 'first_steps']);
            }
        }

        // Badge: Scholar — Membaca total 7 hari (tidak harus berturut-turut)
        if (($streakData['totalDays'] ?? 0) >= 7) {
            $exists = $badgeBuilder->where('id_anggota', $id_anggota)->where('badge_name', 'scholar')->countAllResults(false);
            if (!$exists) {
                $badgeBuilder->insert(['id_anggota' => $id_anggota, 'badge_name' => 'scholar']);
            }
        }
    }
}
