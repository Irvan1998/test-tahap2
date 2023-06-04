<?php

namespace App\Repositories;

use App\Models\Kendaraan;


class KendaraanRepository extends BaseRepository
{

    public function __construct(Kendaraan $model)
    {
        parent::__construct($model);
    }
}
