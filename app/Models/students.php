<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class students extends Model
{
    // Tentukan kolom yang dapat diisi secara massal
    protected $fillable = [
        'name', 'nim', 'kelas', 'tempat_lahir', 'tanggal_lahir','image', // Tambahkan jika ada kolom image yang ingin diisi secara massal
    ];
}