<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MnSub extends Model
{
    use HasFactory;
    protected $table = 'menu_sub';
    protected $primaryKey = 'id_menu_sub';
    protected $fillable = [
        'sub_menu',
        'sub_url'
    ];
}
