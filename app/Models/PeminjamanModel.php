<?php

namespace App\Models;

use CodeIgniter\Model;

class PeminjamanModel extends Model
{
    protected $table            = 'peminjaman';
    protected $primaryKey       = 'id_pinjam';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $allowedFields    = ['id_buku', 'id_anggota', 'tgl_pinjam', 'tgl_kembali', 'status', 'denda'];

    // Join Query to get detailed data
    public function getDetailPeminjaman()
    {
        return $this->select('peminjaman.*, buku.judul, anggota.nama, anggota.nomor_induk')
                    ->join('buku', 'buku.id_buku = peminjaman.id_buku')
                    ->join('anggota', 'anggota.id_anggota = peminjaman.id_anggota')
                    ->orderBy('peminjaman.id_pinjam', 'DESC')
                    ->findAll();
    }
}
