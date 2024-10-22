<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Monitoring extends Model
{
    use HasFactory;

    protected $table = 'monitoring';

    protected $primaryKey = 'id_proker';
    
    protected $fillable = [
        'id_proker',
        'keterangan',
        'foto', 
        'waktu', 
        'status',   
    ];
}
