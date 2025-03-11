<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MnList extends Model
{
    use HasFactory;
    protected $table = 'menu_list';
    protected $primaryKey = 'id_menu_list';
    protected $fillable = [
        'icon',
        'menu',
        'url',
        'status',
    ];

    public function sub()
    {
        return $this->hasMany(MnSub::class, 'id_menu_list')->orderBy('urutan');;
    }
}