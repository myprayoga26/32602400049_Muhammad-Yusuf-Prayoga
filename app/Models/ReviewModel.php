<?php

namespace App\Models;

use CodeIgniter\Model;

class ReviewModel extends Model
{
    protected $table            = 'reviews';
    protected $primaryKey       = 'id_review';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['id_buku', 'id_anggota', 'rating', 'komentar', 'created_at'];

    // Dates
    protected $useTimestamps = false;

    public function getReviewsForBook($id_buku)
    {
        return $this->select('reviews.*, anggota.nama as nama_anggota, anggota.avatar_url')
                    ->join('anggota', 'anggota.id_anggota = reviews.id_anggota')
                    ->where('id_buku', $id_buku)
                    ->orderBy('created_at', 'DESC')
                    ->findAll();
    }
}
