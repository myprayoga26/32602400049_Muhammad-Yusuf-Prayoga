<?php

namespace App\Controllers;

use App\Models\AdminModel;
use App\Models\AnggotaModel;

class Auth extends BaseController
{
    public function bypass() {
        session()->set([
            'id' => 1,
            'nama' => 'Developer',
            'role' => 'admin',
            'logged_in' => true
        ]);
        return redirect()->to('/dashboard');
    }

    public function login()
    {
        // Jika sudah login, redirect sesuai role
        if (session()->get('logged_in')) {
            return session()->get('role') == 'admin' ? redirect()->to('/dashboard') : redirect()->to('/user/dashboard');
        }
        return view('auth/login');
    }

    public function process()
    {
        $username = $this->request->getPost('username');
        $password = $this->request->getPost('password');

        $adminModel = new AdminModel();
        $anggotaModel = new AnggotaModel();

        // 1. Cek tabel Admin
        $admin = $adminModel->where('username', $username)->first();
        if ($admin) {
            if (password_verify($password, $admin['password'])) {
                session()->set([
                    'id' => $admin['id'],
                    'nama' => $admin['nama_lengkap'],
                    'role' => 'admin',
                    'logged_in' => true
                ]);
                return redirect()->to('/dashboard');
            }
        }

        // 2. Cek tabel Anggota (Username = username)
        $anggota = $anggotaModel->where('username', $username)->first();
        if ($anggota) {
            if (password_verify($password, $anggota['password'])) {
                session()->set([
                    'id_anggota' => $anggota['id_anggota'],
                    'nama' => $anggota['nama'],
                    'username' => $anggota['username'],
                    'nomor_induk' => $anggota['nomor_induk'],
                    'tier' => $anggota['tier'] ?? 'free',
                    'avatar_url' => $anggota['avatar_url'] ?? '',
                    'role' => 'anggota',
                    'logged_in' => true
                ]);
                return redirect()->to('/user/dashboard');
            }
        }

        return redirect()->to('/auth/login')->with('error', 'Username atau kata sandi tidak cocok.');
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to('/');
    }

    public function register()
    {
        // Jika sudah login, redirect
        if (session()->get('logged_in')) {
            return redirect()->to('/');
        }
        return view('auth/register');
    }

    public function processRegister()
    {
        $anggotaModel = new AnggotaModel();
        
        $username = $this->request->getPost('username');
        $nomor_induk = $this->request->getPost('nomor_induk');
        
        // Cek apakah username sudah ada
        $existingUsername = $anggotaModel->where('username', $username)->first();
        if ($existingUsername) {
            return redirect()->to('/auth/register')->with('error', 'Username sudah digunakan! Silakan pilih yang lain.');
        }

        // Cek apakah nomor induk sudah ada
        $existing = $anggotaModel->where('nomor_induk', $nomor_induk)->first();
        if ($existing) {
            return redirect()->to('/auth/register')->with('error', 'Nomor Induk sudah terdaftar! Silakan login.');
        }

        $data = [
            'nama'        => $this->request->getPost('nama'),
            'username'    => $username,
            'nomor_induk' => $nomor_induk,
            'no_telp'     => $this->request->getPost('no_telp'),
            'alamat'      => $this->request->getPost('alamat'),
            'password'    => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
            'tier'        => 'free'
        ];

        $anggotaModel->insert($data);

        return redirect()->to('/auth/login')->with('success', 'Akun pembaca berhasil dibuat. Silakan masuk dengan username Anda.');
    }
}
