<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LaporanFiles extends Model
{
    use HasFactory;

    protected $table = 'lap_files';

    protected $fillable = [
        'nama_file', 
        'path'
    ];
}
