<?php

namespace App\Models;

use CodeIgniter\Model;

class BukuModel extends Model
{
    protected $table            = 'buku';
    protected $primaryKey       = 'id_buku';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $allowedFields    = ['judul', 'pengarang', 'penerbit', 'tahun_terbit', 'stok', 'kategori', 'sinopsis', 'cover_url', 'rating', 'jumlah_halaman', 'isbn', 'read_access', 'source_name', 'source_url', 'reading_text'];
}
