<?php

namespace App\Repositories;


use App\Models\User;
use DB;

class UserRepository extends BaseRepository
{

    public function __construct(User $model)
    {
        parent::__construct($model);
    }
}
