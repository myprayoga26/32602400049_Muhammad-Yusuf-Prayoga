<?php

namespace App\Controllers;

use App\Models\BukuModel;
use App\Models\AnggotaModel;
use App\Models\PeminjamanModel;
use App\Models\UserShelfModel;

class Home extends BaseController
{
    public function __construct()
    {
        $session = \Config\Services::session();
        if ($session->get('role') != 'admin') {
            header("Location: " . base_url('auth/login'));
            exit;
        }
    }

    public function index(): string
    {
        $bukuModel = new BukuModel();
        $anggotaModel = new AnggotaModel();
        $peminjamanModel = new PeminjamanModel();
        $shelfModel = new UserShelfModel();

        // Borrow Stats by Date
        $db = \Config\Database::connect();
        $borrowQuery = $db->query("SELECT DATE(tgl_pinjam) as date, COUNT(id_pinjam) as total FROM peminjaman GROUP BY DATE(tgl_pinjam) ORDER BY DATE(tgl_pinjam) DESC LIMIT 7");
        $borrowStats = array_reverse($borrowQuery->getResultArray());

        // Popular Books
        $popularQuery = $db->query("SELECT b.judul, COUNT(s.id_buku) as total FROM user_shelf s JOIN buku b ON s.id_buku = b.id_buku GROUP BY s.id_buku ORDER BY total DESC LIMIT 5");
        $popularBooks = $popularQuery->getResultArray();

        $data = [
            'total_buku'      => $bukuModel->countAllResults(),
            'total_anggota'   => $anggotaModel->countAllResults(),
            'total_pinjam'    => $peminjamanModel->where('status', 'Dipinjam')->countAllResults(),
            'riwayat_terbaru' => $peminjamanModel->getDetailPeminjaman(),
            'borrowStats'     => json_encode($borrowStats),
            'popularBooks'    => json_encode($popularBooks),
        ];

        return view('welcome_message', $data);
    }
}
