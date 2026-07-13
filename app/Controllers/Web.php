<?php

namespace App\Controllers;

use App\Models\BukuModel;

class Web extends BaseController
{
    private const READING_PAGE_CHARS = 1800;

    public function index()
    {
        $bukuModel = new BukuModel();
        $buku = $bukuModel->orderBy('id_buku', 'DESC')->findAll();
        $categories = array_values(array_unique(array_filter(array_column($buku, 'kategori'))));

        // Fetch Editor's Pick books
        $editorsPick = array_filter($buku, fn($b) => ($b['is_editors_pick'] ?? 0) == 1);
        $editorsPick = array_values($editorsPick);
        
        $data = [
            'buku' => $buku,
            'categories' => $categories,
            'editorsPick' => $editorsPick,
        ];
        
        return view('web/index', $data);
    }

    public function search()
    {
        $bukuModel = new BukuModel();
        $query = $this->request->getGet('q');
        $cat = $this->request->getGet('cat');
        $access = $this->request->getGet('access');

        $bukuModel->orderBy('id_buku', 'DESC');

        if (!empty($query)) {
            $bukuModel->groupStart()
                      ->like('judul', $query)
                      ->orLike('pengarang', $query)
                      ->orLike('penerbit', $query)
                      ->orLike('isbn', $query)
                      ->groupEnd();
        }

        if (!empty($cat)) {
            $bukuModel->where('kategori', $cat);
        }

        if ($access === 'readable') {
            $bukuModel->where('read_access', 'public_domain');
        }

        $buku = $bukuModel->findAll();

        return view('web/_catalog_list', ['buku' => $buku]);
    }

    public function bookDetail($id)
    {
        $bukuModel = new \App\Models\BukuModel();
        $buku = $bukuModel->find($id);
        
        if (!$buku) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }
        
        $reviewModel = new \App\Models\ReviewModel();
        $reviews = $reviewModel->getReviewsForBook($id);

        $shelfItem = null;
        if (session()->get('logged_in')) {
            $id_anggota = session()->get('id_anggota') ?? 1;
            $shelfModel = new \App\Models\UserShelfModel();
            $shelfItem = $shelfModel->where('id_anggota', $id_anggota)
                                    ->where('id_buku', $id)
                                    ->first();
        }
        
        return view('web/book_detail', ['buku' => $buku, 'shelfItem' => $shelfItem, 'reviews' => $reviews]);
    }

    public function submitReview()
    {
        if (!session()->get('logged_in') || session()->get('role') != 'anggota') {
            return redirect()->to('/auth/login');
        }

        $id_anggota = session()->get('id_anggota');
        $id_buku = $this->request->getPost('id_buku');
        $rating = (int) $this->request->getPost('rating');
        $komentar = $this->request->getPost('komentar');

        if ($rating < 1 || $rating > 5) {
            return redirect()->back()->with('error', 'Rating tidak valid.');
        }

        $reviewModel = new \App\Models\ReviewModel();
        $existing = $reviewModel->where('id_anggota', $id_anggota)
                                ->where('id_buku', $id_buku)
                                ->first();

        if ($existing) {
            $reviewModel->update($existing['id_review'], [
                'rating' => $rating,
                'komentar' => $komentar,
            ]);
        } else {
            $reviewModel->insert([
                'id_buku' => $id_buku,
                'id_anggota' => $id_anggota,
                'rating' => $rating,
                'komentar' => $komentar,
            ]);
        }

        // Update average rating
        $allReviews = $reviewModel->where('id_buku', $id_buku)->findAll();
        $avgRating = array_sum(array_column($allReviews, 'rating')) / count($allReviews);
        
        $bukuModel = new \App\Models\BukuModel();
        $bukuModel->update($id_buku, ['rating' => round($avgRating, 1)]);

        return redirect()->back()->with('success', 'Ulasan berhasil disimpan.');
    }

    public function myShelf()
    {
        // For prototype, simulate user login if not logged in (e.g., Anggota ID 1)
        $id_anggota = session()->get('id_anggota') ?? 1; 
        
        $shelfModel = new \App\Models\UserShelfModel();
        $status = $this->request->getVar('status');
        
        $shelf = $shelfModel->getUserShelf($id_anggota, $status);
        
        return view('web/my_shelf', ['shelf' => $shelf, 'active_status' => $status]);
    }

    public function addToShelf()
    {
        $id_anggota = session()->get('id_anggota') ?? 1;
        $id_buku = (int) $this->request->getPost('id_buku');
        $status = $this->request->getPost('status_baca') ?: 'wishlist';
        $redirectTo = $this->request->getPost('redirect_to');

        if (!$id_buku || !in_array($status, ['wishlist', 'reading', 'finished'], true)) {
            return redirect()->back()->with('error', 'Pilihan rak tidak valid.');
        }

        $shelfModel = new \App\Models\UserShelfModel();
        $existing = $shelfModel->where('id_anggota', $id_anggota)
                               ->where('id_buku', $id_buku)
                               ->first();

        $payload = [
            'id_anggota' => $id_anggota,
            'id_buku' => $id_buku,
            'status_baca' => $status,
            'progress_persen' => $status === 'finished' ? 100 : ($status === 'reading' ? 1 : 0),
        ];

        if ($existing) {
            $shelfModel->update($existing['id_rak'], $payload);
        } else {
            $shelfModel->insert($payload);
        }

        if ($redirectTo === 'reader') {
            return redirect()->to(base_url('web/read/' . $id_buku))->with('success', 'Buku dibuka di ruang baca.');
        }

        return redirect()->to(base_url('user/dashboard'))->with('success', 'Buku berhasil disimpan ke rak.');
    }

    public function readBook($id)
    {
        $id_anggota = session()->get('id_anggota') ?? 1;
        $bukuModel = new \App\Models\BukuModel();
        $buku = $bukuModel->find($id);

        if (!$buku) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $shelfModel = new \App\Models\UserShelfModel();
        $existing = $shelfModel->where('id_anggota', $id_anggota)
                               ->where('id_buku', $id)
                               ->first();

        if (!$existing) {
            $shelfModel->insert([
                'id_anggota' => $id_anggota,
                'id_buku' => $id,
                'status_baca' => 'reading',
                'progress_persen' => 1,
                'halaman_terakhir' => 1,
            ]);
        } elseif ($existing['status_baca'] === 'wishlist') {
            $shelfModel->update($existing['id_rak'], [
                'status_baca' => 'reading',
                'progress_persen' => max(1, (int) $existing['progress_persen']),
            ]);
        }

        $pages = $this->buildReadingPages($buku, 0, 4);

        return view('web/reader', [
            'buku' => $buku,
            'pages' => $pages,
            'totalPages' => $this->countReadingPages($buku),
            'shelfItem' => $existing,
        ]);
    }

    public function readPages($id)
    {
        $bukuModel = new \App\Models\BukuModel();
        $buku = $bukuModel->find($id);

        if (!$buku) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $start = max(0, (int) $this->request->getGet('start'));
        $limit = min(8, max(1, (int) ($this->request->getGet('limit') ?? 4)));

        return $this->response->setJSON([
            'pages' => $this->buildReadingPages($buku, $start, $limit),
            'totalPages' => $this->countReadingPages($buku),
        ]);
    }

    private function buildReadingPages(array $buku, ?int $start = null, ?int $limit = null): array
    {
        if (($buku['read_access'] ?? 'metadata') === 'public_domain' && !empty($buku['reading_text'])) {
            return $this->paginateReadingText($buku, $start ?? 0, $limit);
        }

        $sinopsis = trim($buku['sinopsis'] ?? 'Belum ada catatan katalog untuk buku ini.');
        $title = $buku['judul'] ?? 'Buku';
        $author = $buku['pengarang'] ?? 'Penulis tidak diketahui';
        $category = $buku['kategori'] ?? 'Umum';
        $publisher = $buku['penerbit'] ?? '-';
        $year = $buku['tahun_terbit'] ?? '-';

        $pages = [
            [
                'kicker' => 'LITERIA READING ROOM',
                'title' => $title,
                'body' => "Oleh {$author}. Edisi katalog LITERIA ini membuka ruang baca dengan metadata, sinopsis, dan catatan kurasi agar pembaca punya konteks sebelum masuk ke buku utuh.",
            ],
            [
                'kicker' => 'SINOPSIS',
                'title' => 'Tentang buku ini',
                'body' => $sinopsis,
            ],
            [
                'kicker' => 'KONTEKS KATALOG',
                'title' => $category,
                'body' => "Diterbitkan oleh {$publisher} pada {$year}. Buku ini ditempatkan di kelompok {$category} agar mudah ditemukan kembali saat pembaca menyusun rak pribadi dan rencana baca.",
            ],
            [
                'kicker' => 'CATATAN PEMBACA',
                'title' => 'Pertanyaan saat membaca',
                'body' => 'Apa gagasan utama yang ingin dibawa penulis? Bagian mana yang paling mengubah cara pandang Anda? Tandai halaman penting, lalu lanjutkan progress dari dashboard pembaca.',
            ],
        ];

        return array_slice($pages, $start ?? 0, $limit);
    }

    private function countReadingPages(array $buku): int
    {
        if (($buku['read_access'] ?? 'metadata') === 'public_domain' && !empty($buku['reading_text'])) {
            $count = count($this->splitReadingText($buku['reading_text']));

            return $count % 2 === 0 ? $count : $count + 1;
        }

        return 4;
    }

    private function paginateReadingText(array $buku, int $start = 0, ?int $limit = null): array
    {
        $chunks = $this->splitReadingText($buku['reading_text']);
        $totalTextPages = count($chunks);
        $totalPages = $totalTextPages % 2 === 0 ? $totalTextPages : $totalTextPages + 1;
        $end = $limit === null ? $totalPages : min($totalPages, $start + $limit);
        $pages = [];

        for ($index = $start; $index < $end; $index++) {
            if ($index >= $totalTextPages) {
                $pages[] = [
                    'kicker' => $buku['source_name'] ?? 'PUBLIC DOMAIN',
                    'title' => 'Catatan sumber',
                    'body' => 'Teks ini berasal dari sumber legal public domain. Buka tautan sumber pada detail buku untuk membaca edisi lengkapnya.',
                ];

                continue;
            }

            $pages[] = [
                'kicker' => ($buku['source_name'] ?? 'PUBLIC DOMAIN') . ' - HALAMAN ' . ($index + 1),
                'title' => $index === 0 ? ($buku['judul'] ?? 'Buku') : 'Halaman ' . ($index + 1),
                'body' => trim($chunks[$index]),
            ];
        }

        return $pages;
    }

    private function splitReadingText(string $readingText): array
    {
        $text = (string) preg_replace('/\[Illustration:.*?\]/i', ' ', $readingText);
        $text = str_replace('_', '', $text);
        $text = trim((string) preg_replace('/\s+/', ' ', $text));
        $wrapped = wordwrap($text, self::READING_PAGE_CHARS, "\n", false);

        return array_values(array_filter(array_map('trim', explode("\n", $wrapped))));
    }

    public function updateShelf($id)
    {
        $id_anggota = session()->get('id_anggota') ?? 1;
        $status = $this->request->getPost('status_baca') ?: 'reading';
        $progress = max(0, min(100, (int) $this->request->getPost('progress_persen')));
        $lastPage = max(0, (int) $this->request->getPost('halaman_terakhir'));

        if (!in_array($status, ['wishlist', 'reading', 'finished'], true)) {
            return redirect()->back()->with('error', 'Status baca tidak valid.');
        }

        if ($status === 'finished') {
            $progress = 100;
        }

        $shelfModel = new \App\Models\UserShelfModel();
        $item = $shelfModel->where('id_rak', $id)
                           ->where('id_anggota', $id_anggota)
                           ->first();

        if (!$item) {
            return redirect()->back()->with('error', 'Item rak tidak ditemukan.');
        }

        $shelfModel->update($id, [
            'status_baca' => $status,
            'progress_persen' => $progress,
            'halaman_terakhir' => $lastPage,
        ]);

        return redirect()->back()->with('success', 'Progress membaca diperbarui.');
    }

    public function migrateKategori()
    {
        $db = \Config\Database::connect();
        try {
            $db->query("ALTER TABLE buku ADD COLUMN kategori VARCHAR(100) DEFAULT 'Umum' AFTER stok");
            echo "Kolom kategori berhasil ditambahkan.";
        } catch (\Exception $e) {
            echo "Error: " . $e->getMessage();
        }
    }

    public function migrateV2()
    {
        $db = \Config\Database::connect();
        
        try {
            // Update buku table
            $db->query("ALTER TABLE buku ADD COLUMN IF NOT EXISTS sinopsis TEXT");
            $db->query("ALTER TABLE buku ADD COLUMN IF NOT EXISTS cover_url VARCHAR(255) DEFAULT ''");
            $db->query("ALTER TABLE buku ADD COLUMN IF NOT EXISTS rating DECIMAL(3,1) DEFAULT 0.0");
            $db->query("ALTER TABLE buku ADD COLUMN IF NOT EXISTS jumlah_halaman INT DEFAULT 0");
            $db->query("ALTER TABLE buku ADD COLUMN IF NOT EXISTS isbn VARCHAR(20) DEFAULT ''");
            $db->query("ALTER TABLE buku ADD COLUMN IF NOT EXISTS read_access ENUM('metadata', 'public_domain') DEFAULT 'metadata'");
            $db->query("ALTER TABLE buku ADD COLUMN IF NOT EXISTS source_name VARCHAR(100) DEFAULT ''");
            $db->query("ALTER TABLE buku ADD COLUMN IF NOT EXISTS source_url VARCHAR(255) DEFAULT ''");
            $db->query("ALTER TABLE buku ADD COLUMN IF NOT EXISTS reading_text LONGTEXT");

            // Update anggota table
            $db->query("ALTER TABLE anggota ADD COLUMN IF NOT EXISTS tier ENUM('free', 'premium') DEFAULT 'free'");
            $db->query("ALTER TABLE anggota ADD COLUMN IF NOT EXISTS avatar_url VARCHAR(255) DEFAULT ''");

            // Create user_shelf table
            $db->query("CREATE TABLE IF NOT EXISTS user_shelf (
                id_rak INT AUTO_INCREMENT PRIMARY KEY,
                id_anggota INT NOT NULL,
                id_buku INT NOT NULL,
                status_baca ENUM('wishlist', 'reading', 'finished') DEFAULT 'wishlist',
                progress_persen INT DEFAULT 0,
                halaman_terakhir INT DEFAULT 0,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                FOREIGN KEY (id_anggota) REFERENCES anggota(id_anggota) ON DELETE CASCADE,
                FOREIGN KEY (id_buku) REFERENCES buku(id_buku) ON DELETE CASCADE
            )");

            return "Database berhasil diupdate untuk versi LITERIA Premium Digital Library!";
        } catch (\Exception $e) {
            return "Error: " . $e->getMessage();
        }
    }

    public function migrateV3()
    {
        $db = \Config\Database::connect();

        try {
            // Editor's Pick column on buku table
            $db->query("ALTER TABLE buku ADD COLUMN IF NOT EXISTS is_editors_pick TINYINT(1) DEFAULT 0");

            // User bookmarks table
            $db->query("CREATE TABLE IF NOT EXISTS user_bookmarks (
                id_bookmark INT AUTO_INCREMENT PRIMARY KEY,
                id_anggota INT NOT NULL,
                id_buku INT NOT NULL,
                halaman INT NOT NULL DEFAULT 1,
                catatan VARCHAR(500) DEFAULT '',
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                FOREIGN KEY (id_anggota) REFERENCES anggota(id_anggota) ON DELETE CASCADE,
                FOREIGN KEY (id_buku) REFERENCES buku(id_buku) ON DELETE CASCADE
            )");

            // Reading sessions table for gamification
            $db->query("CREATE TABLE IF NOT EXISTS reading_sessions (
                id_session INT AUTO_INCREMENT PRIMARY KEY,
                id_anggota INT NOT NULL,
                id_buku INT NOT NULL,
                read_date DATE NOT NULL,
                pages_read INT DEFAULT 1,
                duration_minutes INT DEFAULT 0,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                UNIQUE KEY uniq_user_book_date (id_anggota, id_buku, read_date),
                FOREIGN KEY (id_anggota) REFERENCES anggota(id_anggota) ON DELETE CASCADE,
                FOREIGN KEY (id_buku) REFERENCES buku(id_buku) ON DELETE CASCADE
            )");

            // Set some books as Editor's Pick
            $db->query("UPDATE buku SET is_editors_pick = 1 WHERE id_buku IN (1, 2, 9, 10, 12)");

            return "Migrasi V3 berhasil! Editor's Pick, Bookmarks, dan Reading Sessions siap.";
        } catch (\Exception $e) {
            return "Error: " . $e->getMessage();
        }
    }
    public function migrateV4()
    {
        try {
            $db = \Config\Database::connect();
            
            // 1. Create user_annotations
            $db->query("CREATE TABLE IF NOT EXISTS user_annotations (
                id_anotasi INT AUTO_INCREMENT PRIMARY KEY,
                id_anggota INT NOT NULL,
                id_buku INT NOT NULL,
                halaman INT NOT NULL,
                teks_highlight TEXT NOT NULL,
                catatan TEXT,
                warna VARCHAR(20) DEFAULT 'yellow',
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                FOREIGN KEY (id_anggota) REFERENCES anggota(id_anggota) ON DELETE CASCADE,
                FOREIGN KEY (id_buku) REFERENCES buku(id_buku) ON DELETE CASCADE
            )");

            return "Migrasi V4 (Anotasi) berhasil!";
        } catch (\Exception $e) {
            return "Error: " . $e->getMessage();
        }
    }
    public function seed_gutenberg()
    {
        // This is a powerful automated importer that fetches books from Gutendex API
        $apiUrl = "https://gutendex.com/books/?page=1";
        $response = @file_get_contents($apiUrl);
        if (!$response) return "Failed to fetch from Gutendex.";
        
        $data = json_decode($response, true);
        if (!$data || empty($data['results'])) return "Failed to parse Gutendex JSON.";

        $db = \Config\Database::connect();
        $builder = $db->table('buku');
        $added = 0;

        foreach ($data['results'] as $book) {
            // Only process if we have less than 15 fetched right now to prevent long timeouts
            if ($added >= 15) break;
            
            // Check if already in DB
            $existing = $builder->where('judul', $book['title'])->get()->getRow();
            if ($existing) continue; // Skip existing books

            // Need text/plain URL for full text
            $textUrl = '';
            foreach ($book['formats'] as $mime => $url) {
                if (strpos($mime, 'text/plain') !== false) {
                    $textUrl = $url;
                    break;
                }
            }
            if (!$textUrl) continue; // Skip if no plain text

            // Download cover (image/jpeg)
            $coverUrl = '';
            foreach ($book['formats'] as $mime => $url) {
                if (strpos($mime, 'image/jpeg') !== false) {
                    $coverUrl = $url;
                    break;
                }
            }
            if (!$coverUrl) $coverUrl = 'https://via.placeholder.com/300x450?text=No+Cover';

            // Download text
            // Gutendex text URLs often redirect or need proper context, but file_get_contents usually follows Location headers.
            $opts = [
                "http" => [
                    "method" => "GET",
                    "header" => "User-Agent: LITERIA_AutoImporter/1.0\r\n"
                ]
            ];
            $context = stream_context_create($opts);
            $rawText = @file_get_contents($textUrl, false, $context);
            if (!$rawText || strlen($rawText) < 500) continue; // Skip if text is empty or too short

            // TRUNCATE text to avoid MySQL 'max_allowed_packet' error (approx 900KB max)
            if (strlen($rawText) > 900000) {
                $rawText = substr($rawText, 0, 900000);
            }
            
            // Try to extract a clean summary if available, or just take first few sentences
            $author = !empty($book['authors']) ? $book['authors'][0]['name'] : 'Unknown';
            // clean author name (sometimes it's "Last, First")
            if (strpos($author, ',') !== false) {
                $parts = explode(',', $author);
                $author = trim($parts[1]) . ' ' . trim($parts[0]);
            }
            
            $synopsis = "Teks public domain klasik dari " . $author . " yang disediakan secara gratis oleh Project Gutenberg.";

            $insertData = [
                'judul' => $book['title'],
                'pengarang' => $author,
                'penerbit' => 'Project Gutenberg',
                'tahun_terbit' => 1900, // Dummy year as gutendex doesn't always provide easy pub year
                'stok' => 999, // Unlimited
                'kategori' => 'Klasik',
                'sinopsis' => $synopsis,
                'cover_url' => $coverUrl,
                'rating' => 4.5,
                'jumlah_halaman' => ceil(strlen($rawText) / 1800), // Estimasi kasar
                'isbn' => 'GUTENBERG-' . $book['id'],
                'read_access' => 'public_domain',
                'source_name' => 'Project Gutenberg',
                'source_url' => 'https://www.gutenberg.org/ebooks/' . $book['id'],
                'reading_text' => $rawText
            ];
            
            $builder->insert($insertData);
            $added++;
        }

        return "Auto-Importer selesai! Berhasil menambahkan $added buku klasik beserta teks penuhnya.";
    }

    /**
     * AI Explainer — Mock/Simulated AI response for text explanation
     */
    public function ai_explain()
    {
        $json = $this->request->getJSON(true);
        $text = $json['text'] ?? '';
        
        if (empty($text)) {
            return $this->response->setJSON(['error' => 'No text provided']);
        }

        // Truncate for safety
        $text = mb_substr($text, 0, 500);
        $original = $text;

        // ── Smart Mock AI Explanation System ──
        // Detect language patterns and provide contextual explanations
        $wordCount = str_word_count($text);
        $hasOldEnglish = preg_match('/\b(thou|thee|thy|hath|doth|whence|hence|thence|wherefore|forsooth|hither|thither|whilst|amongst|betwixt)\b/i', $text);
        $hasLatin = preg_match('/\b(et|ad|per|de|in|non|pro|cum|ex|ab)\b/', $text);
        
        if ($hasOldEnglish) {
            $explanation = "<strong>Bahasa Inggris Kuno/Puitis</strong><br><br>"
                . "Teks ini menggunakan gaya bahasa Inggris klasik yang umum ditemukan dalam karya sastra abad ke-16 hingga 19. "
                . "Beberapa kata kunci:<br>"
                . "• <em>thou/thee</em> = kamu (bentuk akrab)<br>"
                . "• <em>hath/doth</em> = has/does<br>"
                . "• <em>whence</em> = dari mana<br>"
                . "• <em>wherefore</em> = mengapa (bukan 'dimana')<br><br>"
                . "<strong>Arti ringkas:</strong> " . $this->generateSimpleExplanation($text);
        } elseif ($wordCount <= 5) {
            $explanation = "<strong>Kata/Frasa Pendek</strong><br><br>"
                . "Ini adalah frasa singkat. Dalam konteks sastra, frasa seperti ini biasanya merupakan bagian dari kalimat yang lebih panjang. "
                . "Coba blok lebih banyak teks untuk mendapatkan penjelasan yang lebih kaya konteks.";
        } else {
            $explanation = "<strong>Penjelasan Kontekstual</strong><br><br>"
                . $this->generateSimpleExplanation($text)
                . "<br><br><em class='text-navy-900/40'>💡 Tip: LITERIA Explainer saat ini menggunakan sistem analisis lokal. "
                . "Hubungkan API AI untuk penjelasan yang lebih mendalam.</em>";
        }

        return $this->response->setJSON([
            'original' => $original,
            'explanation' => $explanation
        ]);
    }

    private function generateSimpleExplanation(string $text): string
    {
        $sentences = preg_split('/[.!?]+/', $text, -1, PREG_SPLIT_NO_EMPTY);
        $count = count($sentences);
        
        if ($count === 0) return "Teks ini terlalu pendek untuk dianalisis.";
        
        $wordCount = str_word_count($text);
        $avgWordsPerSentence = round($wordCount / max(1, $count));
        
        $complexity = "sederhana";
        if ($avgWordsPerSentence > 20) $complexity = "kompleks";
        elseif ($avgWordsPerSentence > 12) $complexity = "menengah";
        
        return "Kutipan ini terdiri dari <strong>$count kalimat</strong> dengan tingkat kompleksitas <strong>$complexity</strong> "
            . "(rata-rata $avgWordsPerSentence kata per kalimat). "
            . "Teks semacam ini umum ditemukan dalam karya sastra klasik berbahasa Inggris. "
            . "Untuk memahaminya, cobalah membaca secara perlahan dan perhatikan konteks paragraf di sekitarnya.";
    }

    /**
     * Get popular annotations/highlights from other users for a specific book
     */
    public function get_popular_annotations($id_buku = null)
    {
        if (!$id_buku) return $this->response->setJSON([]);

        $db = \Config\Database::connect();
        
        // Get highlights that appear more than once (popular)
        $annotations = $db->table('user_annotations')
            ->select('teks_highlight, warna, COUNT(*) as total')
            ->where('id_buku', $id_buku)
            ->groupBy('teks_highlight')
            ->having('COUNT(*) >', 1)
            ->orderBy('total', 'DESC')
            ->limit(20)
            ->get()->getResultArray();

        return $this->response->setJSON($annotations);
    }
}

