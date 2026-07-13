<?php

namespace App\Controllers;

use App\Models\PeminjamanModel;
use App\Models\BukuModel;
use App\Models\AnggotaModel;

class Peminjaman extends BaseController
{
    protected $peminjamanModel;
    protected $bukuModel;
    protected $anggotaModel;

    public function __construct()
    {
        $session = \Config\Services::session();
        if ($session->get('role') != 'admin') {
            header("Location: " . base_url('auth/login'));
            exit;
        }
        $this->peminjamanModel = new PeminjamanModel();
        $this->bukuModel       = new BukuModel();
        $this->anggotaModel    = new AnggotaModel();
    }

    public function index()
    {
        $data = [
            'peminjaman' => $this->peminjamanModel->getDetailPeminjaman()
        ];
        return view('peminjaman/index', $data);
    }

    public function tambah()
    {
        $data = [
            'buku'    => $this->bukuModel->where('stok >', 0)->findAll(),
            'anggota' => $this->anggotaModel->findAll()
        ];
        return view('peminjaman/form', $data);
    }

    public function simpan()
    {
        $id_buku = $this->request->getPost('id_buku');
        
        // Simpan transaksi
        $this->peminjamanModel->save([
            'id_buku'     => $id_buku,
            'id_anggota'  => $this->request->getPost('id_anggota'),
            'tgl_pinjam'  => $this->request->getPost('tgl_pinjam'),
            'tgl_kembali' => $this->request->getPost('tgl_kembali'),
            'status'      => 'Dipinjam'
        ]);

        // Kurangi stok buku
        $buku = $this->bukuModel->find($id_buku);
        $this->bukuModel->update($id_buku, ['stok' => $buku['stok'] - 1]);

        return redirect()->to('/peminjaman')->with('success', 'Transaksi berhasil.');
    }

    public function kembali($id_pinjam)
    {
        $peminjaman = $this->peminjamanModel->find($id_pinjam);
        
        if ($peminjaman && $peminjaman['status'] == 'Dipinjam') {
            // Hitung denda
            $tgl_kembali = strtotime($peminjaman['tgl_kembali']);
            $hari_ini = strtotime(date('Y-m-d'));
            $denda = 0;
            
            if ($hari_ini > $tgl_kembali) {
                $selisih_hari = round(($hari_ini - $tgl_kembali) / (60 * 60 * 24));
                $denda = $selisih_hari * 1000;
            }

            // Ubah status dan simpan denda
            $this->peminjamanModel->update($id_pinjam, [
                'status' => 'Dikembalikan',
                'denda'  => $denda
            ]);
            
            // Tambah stok buku
            $buku = $this->bukuModel->find($peminjaman['id_buku']);
            $this->bukuModel->update($peminjaman['id_buku'], ['stok' => $buku['stok'] + 1]);

            $pesan = 'Buku telah dikembalikan.';
            if ($denda > 0) {
                $pesan .= ' Denda Keterlambatan: Rp ' . number_format($denda, 0, ',', '.');
            }

            return redirect()->to('/peminjaman')->with('success', $pesan);
        }

        return redirect()->to('/peminjaman')->with('error', 'Transaksi tidak valid.');
    }
}
