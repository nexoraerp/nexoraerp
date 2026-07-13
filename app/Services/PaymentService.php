<?php

namespace App\Services;

use App\Models\Payment;

class PaymentService extends BaseService
{
    public function __construct()
    {
        $this->model = new Payment();
    }
}