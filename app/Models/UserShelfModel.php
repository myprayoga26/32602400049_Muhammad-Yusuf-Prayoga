<?php

namespace App\Models;

use CodeIgniter\Model;

class UserShelfModel extends Model
{
    protected $table            = 'user_shelf';
    protected $primaryKey       = 'id_rak';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $allowedFields    = ['id_anggota', 'id_buku', 'status_baca', 'progress_persen', 'halaman_terakhir'];
    
    // Automatically manage created_at and updated_at if you use $useTimestamps = true, but we let MySQL handle updated_at.

    public function getUserShelf($id_anggota, $status = null)
    {
        $builder = $this->select('user_shelf.*, buku.judul, buku.pengarang, buku.penerbit, buku.tahun_terbit, buku.cover_url, buku.rating, buku.kategori, buku.jumlah_halaman, buku.isbn')
                        ->join('buku', 'buku.id_buku = user_shelf.id_buku')
                        ->where('id_anggota', $id_anggota);
                        
        if ($status) {
            $builder->where('status_baca', $status);
        }
        
        return $builder->orderBy('updated_at', 'DESC')->findAll();
    }
}
