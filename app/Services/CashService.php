<?php

namespace App\Services;

use App\Models\CashAccount;

class CashService extends BaseService
{
    public function __construct()
    {
        $this->model = new CashAccount();
    }
}