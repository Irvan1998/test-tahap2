<?php

namespace App\Repositories;

use App\Models\Penjualan;

class PenjualanRepository extends BaseRepository
{

    public function __construct(Penjualan $model)
    {
        parent::__construct($model);
    }
}
