<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Jenssegers\Mongodb\Eloquent\Model as Eloquent;

class Kendaraan extends Eloquent
{
    use HasFactory;


    protected $collection = 'users';
    protected $primaryKey = '_id';

    protected $fillable = [
        'name',
        'email',
        'password',
    ];
}
