<?php

namespace App\Controllers;

use App\Models\AnggotaModel;

class Anggota extends BaseController
{
    protected $anggotaModel;

    public function __construct()
    {
        $session = \Config\Services::session();
        if ($session->get('role') != 'admin') {
            header("Location: " . base_url('auth/login'));
            exit;
        }
        $this->anggotaModel = new AnggotaModel();
    }

    public function index()
    {
        $data = [
            'anggota' => $this->anggotaModel->findAll()
        ];
        return view('anggota/index', $data);
    }

    public function tambah()
    {
        return view('anggota/form');
    }

    public function simpan()
    {
        $this->anggotaModel->save([
            'nomor_induk' => $this->request->getPost('nomor_induk'),
            'nama'        => $this->request->getPost('nama'),
            'no_telp'     => $this->request->getPost('no_telp'),
            'alamat'      => $this->request->getPost('alamat'),
        ]);

        return redirect()->to('/anggota')->with('success', 'Anggota berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $data = [
            'anggota' => $this->anggotaModel->find($id)
        ];
        return view('anggota/form', $data);
    }

    public function update($id)
    {
        $this->anggotaModel->update($id, [
            'nomor_induk' => $this->request->getPost('nomor_induk'),
            'nama'        => $this->request->getPost('nama'),
            'no_telp'     => $this->request->getPost('no_telp'),
            'alamat'      => $this->request->getPost('alamat'),
        ]);

        return redirect()->to('/anggota')->with('success', 'Data anggota berhasil diubah.');
    }

    public function hapus($id)
    {
        $this->anggotaModel->delete($id);
        return redirect()->to('/anggota')->with('success', 'Anggota berhasil dihapus.');
    }
}
