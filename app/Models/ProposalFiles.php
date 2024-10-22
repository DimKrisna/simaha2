<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProposalFiles extends Model
{
    use HasFactory;

    protected $table = 'prop_files';

    protected $fillable = [
        'nama_file', 
        'path'
    ];
}
