<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Jenssegers\Mongodb\Eloquent\Model as Eloquent;

class Penjualan extends Eloquent
{
    use HasFactory;
    protected $collection = 'penjualan';
    protected $primaryKey = '_id';

    protected $fillable = [
        'id_kendaraan',
        'nama_pembeli',
        'qty',
        'total',
    ];
}
