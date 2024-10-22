<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProposalKegiatan extends Model
{
    use HasFactory;
    protected $table = 'proposal_kegiatan'; // Tentukan nama tabel

    protected $primaryKey = 'id_proposal'; // Tentukan primary key

    protected $fillable = [
        'id_ormawa',
        'jenis_proposal',
        'id_proker',
        'tema',
        'judul_kegiatan',
        'latar_belakang',
        'deskripsi_kegiatan',
        'tujuan_kegiatan',
        'manfaat_kegiatan',
        'tempat_pelaksanaan',
        'anggaran_kegiatan',
        'anggaran_diajukan',
        'susunan_acara',
        'susunan_panitia',
        'lampiran_anggaran',
    ];

    // Jika Anda ingin menonaktifkan timestamps (created_at dan updated_at)
    public $timestamps = false;
}
