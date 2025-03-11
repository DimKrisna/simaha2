<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MnKategori extends Model
{
    use HasFactory;
    protected $table = 'menu_kategori';
    protected $primaryKey = 'id_menu_kategori';

    protected $fillable = [
        'menu_kategori',
    ];

    public function list()
    {
        return $this->hasMany(MnList::class, 'id_menu_kategori')->orderBy('urutan');
    }
}
