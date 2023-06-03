<?php

namespace App\Repositories;

use App\Models\Kendaraan;
use DB;

class KendaraanRepository extends BaseRepository
{

    public function __construct(Kendaraan $model)
    {
        parent::__construct($model);
    }
}
