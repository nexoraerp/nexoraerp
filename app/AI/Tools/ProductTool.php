<?php

namespace App\AI\Tools;

use App\AI\Core\BaseTool;
use App\AI\Core\Task;
use App\AI\Core\TaskContext;
use App\AI\Core\Tool;
use App\Services\ProductService;

#[Tool('product')]
class ProductTool extends BaseTool
{
    public function __construct(
        ProductService $service
    ) {
        $this->service = $service;
    }

    /*
    |--------------------------------------------------------------------------
    | Ürün Bul
    |--------------------------------------------------------------------------
    */

    public function find(
        Task $task,
        TaskContext $context
    ): array {

        $product = $this->service->findByName(
            $task->payload('name')
        );

        if (!$product) {

            return [

                'success' => false,

                'message' => 'Ürün bulunamadı.',

            ];
        }

        $context->set('product', $product);

        return [

            'success' => true,

            'product' => $product,

        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Ürün Ara
    |--------------------------------------------------------------------------
    */

    public function search(
        Task $task,
        TaskContext $context
    ): array {

        $products = $this->service->search(
            $task->payload('search')
        );

        $context->set('products', $products);

        return [

            'success' => true,

            'products' => $products,

        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Satış Fiyatı
    |--------------------------------------------------------------------------
    */

    public function price(
        Task $task,
        TaskContext $context
    ): array {

        $product = $context->get('product');

        if (!$product) {

            return [

                'success' => false,

                'message' => 'Ürün bulunamadı.',

            ];
        }

        $price = $this->service->price($product);

        $context->set('price', $price);

        return [

            'success' => true,

            'price' => $price,

        ];
    }

    /*
    |--------------------------------------------------------------------------
    | KDV
    |--------------------------------------------------------------------------
    */

    public function vat(
        Task $task,
        TaskContext $context
    ): array {

        $product = $context->get('product');

        if (!$product) {

            return [

                'success' => false,

                'message' => 'Ürün bulunamadı.',

            ];
        }

        $vat = $this->service->vat($product);

        $context->set('vat', $vat);

        return [

            'success' => true,

            'vat' => $vat,

        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Stok
    |--------------------------------------------------------------------------
    */

    public function stock(
        Task $task,
        TaskContext $context
    ): array {

        $product = $context->get('product');

        if (!$product) {

            return [

                'success' => false,

                'message' => 'Ürün bulunamadı.',

            ];
        }

        $stock = $this->service->stock($product);

        $context->set('stock', $stock);

        return [

            'success' => true,

            'stock' => $stock,

        ];
    }
}