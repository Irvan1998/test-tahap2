<?php

namespace App\Http\Controllers;

use App\Models\Kendaraan;
use App\Traits\ResponseAPI;
use Illuminate\Http\Request;

class KendaraanController extends Controller
{
    use ResponseAPI;

    public function index()
    {
        $data = Kendaraan::get()->toArray();
        return $data;
    }
}
