<?php

namespace App\Controllers;

use App\Models\BukuModel;

class Buku extends BaseController
{
    protected $bukuModel;

    public function __construct()
    {
        $session = \Config\Services::session();
        if ($session->get('role') != 'admin') {
            header("Location: " . base_url('auth/login'));
            exit;
        }
        $this->bukuModel = new BukuModel();
    }

    public function index()
    {
        $data = [
            'buku' => $this->bukuModel->findAll()
        ];
        return view('buku/index', $data);
    }

    public function tambah()
    {
        return view('buku/form');
    }

    public function simpan()
    {
        $this->bukuModel->save([
            'judul'        => $this->request->getPost('judul'),
            'pengarang'    => $this->request->getPost('pengarang'),
            'penerbit'     => $this->request->getPost('penerbit'),
            'tahun_terbit' => $this->request->getPost('tahun_terbit'),
            'stok'         => $this->request->getPost('stok'),
            'kategori'     => $this->request->getPost('kategori'),
            'isbn'         => $this->request->getPost('isbn'),
            'cover_url'    => $this->request->getPost('cover_url'),
            'sinopsis'     => $this->request->getPost('sinopsis'),
            'rating'       => $this->request->getPost('rating'),
            'jumlah_halaman' => $this->request->getPost('jumlah_halaman'),
        ]);

        return redirect()->to('/buku')->with('success', 'Data buku berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $data = [
            'buku' => $this->bukuModel->find($id)
        ];
        return view('buku/form', $data);
    }

    public function update($id)
    {
        $this->bukuModel->update($id, [
            'judul'        => $this->request->getPost('judul'),
            'pengarang'    => $this->request->getPost('pengarang'),
            'penerbit'     => $this->request->getPost('penerbit'),
            'tahun_terbit' => $this->request->getPost('tahun_terbit'),
            'stok'         => $this->request->getPost('stok'),
            'kategori'     => $this->request->getPost('kategori'),
            'isbn'         => $this->request->getPost('isbn'),
            'cover_url'    => $this->request->getPost('cover_url'),
            'sinopsis'     => $this->request->getPost('sinopsis'),
            'rating'       => $this->request->getPost('rating'),
            'jumlah_halaman' => $this->request->getPost('jumlah_halaman'),
        ]);

        return redirect()->to('/buku')->with('success', 'Data buku berhasil diubah.');
    }

    public function hapus($id)
    {
        $this->bukuModel->delete($id);
        return redirect()->to('/buku')->with('success', 'Data buku berhasil dihapus.');
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
}
