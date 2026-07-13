<?php

namespace App\AI\Core;

use App\AI\Router\IntentDetector;

class TaskPlanner
{
    public function __construct(
        protected IntentDetector $detector
    ) {
    }

    public function plan(string $message): TaskCollection
    {
        $tasks = new TaskCollection();

        $intent = $this->detector->detect($message);

        switch ($intent) {

            /*
            |--------------------------------------------------------------------------
            | Cari Oluştur
            |--------------------------------------------------------------------------
            */

            case 'customer.create':

                $tasks->add(new Task(

                    agent: 'customer',

                    action: 'create'

                ));

                break;

            /*
            |--------------------------------------------------------------------------
            | Ürün Oluştur
            |--------------------------------------------------------------------------
            */

            case 'product.create':

                $tasks->add(new Task(

                    agent: 'product',

                    action: 'create'

                ));

                break;

            /*
            |--------------------------------------------------------------------------
            | Satış Oluştur
            |--------------------------------------------------------------------------
            */

            case 'sale.create':

                /*
                |--------------------------------------------------------------------------
                | Önce Cari Bul
                |--------------------------------------------------------------------------
                */

                $tasks->add(new Task(

                    agent: 'customer',

                    action: 'find'

                ));

                /*
                |--------------------------------------------------------------------------
                | Sonra Ürün Bul
                |--------------------------------------------------------------------------
                */

                $tasks->add(new Task(

                    agent: 'product',

                    action: 'find'

                ));

                /*
                |--------------------------------------------------------------------------
                | Stok Kontrol Et
                |--------------------------------------------------------------------------
                */

                $tasks->add(new Task(

                    agent: 'stock',

                    action: 'check'

                ));

                /*
                |--------------------------------------------------------------------------
                | Satışı Oluştur
                |--------------------------------------------------------------------------
                */

                $tasks->add(new Task(

                    agent: 'sales',

                    action: 'create'

                ));

                break;

            /*
            |--------------------------------------------------------------------------
            | Tahsilat
            |--------------------------------------------------------------------------
            */

            case 'payment.create':

                $tasks->add(new Task(

                    agent: 'payment',

                    action: 'create'

                ));

                break;

        }

        return $tasks;
    }
}
