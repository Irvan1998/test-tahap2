<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Jenssegers\Mongodb\Eloquent\Model as Eloquent;

class Kendaraan extends Eloquent
{
    use HasFactory;


    protected $collection = 'kendaraan';
    protected $primaryKey = '_id';

    protected $fillable = [
        'tahun_keluaran',
        'warna',
        'harga',
        'kategori',
        'mesin',
        'qty',
        'kapasitas_penumpang',
        'tipe',
        'tipe_suspensi',
        'tipe_transmisi',
    ];
}
