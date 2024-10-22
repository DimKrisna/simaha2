<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Proker extends Model
{
    use HasFactory;

    protected $table = 'proker'; 

    protected $primaryKey = 'id_proker'; 

    protected $fillable = [
        'id_ormawa', 
        'IdPengurus', 
        'nama_kegiatan',
        'uraian_kegiatan',
        'peran_ormawa', 
        'jenis_kegiatan',
        'keunggulan',
        'capaian',
        'strategi_sosialisasi',
        'personalia_pelaksana',
        'estimasi_anggaran',
        'status',        
    ];

    public $timestamps = false;
}
