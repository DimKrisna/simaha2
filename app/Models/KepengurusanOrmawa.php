<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KepengurusanOrmawa extends Model
{
    use HasFactory;

    protected $table = 'kepengurusan_ormawa';

    protected $fillable = [
        'id_ormawa',
        'periode',
    ];


}
