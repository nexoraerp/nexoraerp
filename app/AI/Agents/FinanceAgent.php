<?php

namespace App\AI\Agents;

class FinanceAgent
{
    public function execute(): array
    {
        return [

            'type'=>'form',

            'form'=>'payment'

        ];
    }
}