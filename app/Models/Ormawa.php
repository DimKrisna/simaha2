<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ormawa extends Model
{
    use HasFactory;

    protected $table = 'ormawa';

    protected $primarykey = 'id_ormawa';

    protected $fillable = [
        'nama_ormawa',
        'nama_singkatan',   
    ];
}
