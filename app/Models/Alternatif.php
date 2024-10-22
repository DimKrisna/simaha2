<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Alternatif extends Model
{
    use HasFactory;

    protected $table = 'alternatif'; // Sesuaikan dengan nama tabel Anda

    protected $primaryKey = 'id_alternatif';

    protected $fillable = [
        'id_ormawa',
        'C1',
        'C2',
        'C3', 
        'periode'   
    ];

    public $timestamps = false;
}
