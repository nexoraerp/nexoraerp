<?php

namespace App\AI\Tools;

use App\AI\Core\BaseTool;
use App\AI\Core\Task;
use App\AI\Core\TaskContext;
use App\AI\Core\Tool;
use App\Services\CustomerService;

#[Tool('customer')]
class CustomerTool extends BaseTool
{
    public function __construct(
        CustomerService $service
    ) {
        $this->service = $service;
    }

    /*
    |--------------------------------------------------------------------------
    | Cari Oluştur
    |--------------------------------------------------------------------------
    */

    public function create(
        Task $task,
        TaskContext $context
    ): array {

        $data = $task->payload;

        if ($this->service->existsByName($data['name'])) {

            return [

                'success' => false,

                'message' => 'Bu isimde cari mevcut.'

            ];
        }

        $customer = $this->service->create($data);

        $context->set(
            'customer',
            $customer
        );

        return [

            'success' => true,

            'customer' => $customer,

        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Cari Bul
    |--------------------------------------------------------------------------
    */

    public function find(
        Task $task,
        TaskContext $context
    ): array {

        $customer = $this->service->findByName(
            $task->payload('name')
        );

        $context->set(
            'customer',
            $customer
        );

        return [

            'success' => $customer !== null,

            'customer' => $customer,

        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Cari Ara
    |--------------------------------------------------------------------------
    */

    public function search(
        Task $task,
        TaskContext $context
    ): array {

        $customers = $this->service->search(
            $task->payload('search')
        );

        $context->set(
            'customers',
            $customers
        );

        return [

            'success' => true,

            'customers' => $customers,

        ];
    }
}