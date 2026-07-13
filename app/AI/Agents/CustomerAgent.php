<?php

namespace App\AI\Agents;

use App\AI\Conversation\ConversationState;
use App\AI\Core\Agent;
use App\AI\Core\BaseAgent;
use App\AI\Core\Intent;
use App\AI\Core\Workflow;
use App\AI\Core\WorkflowEngine;
use App\AI\Core\WorkflowStep;
use App\Services\CustomerService;

#[Agent('customer')]
#[Intent('customer.create')]
class CustomerAgent extends BaseAgent
{
    public function __construct(
        ConversationState $state,
        WorkflowEngine $engine,
        protected CustomerService $service
    ) {
        parent::__construct($state, $engine);
    }

    public function workflow(): Workflow
    {
        return new Workflow([

            new WorkflowStep(
                key: 'name',
                question: '🏢 Firma adı nedir?',
                field: 'name'
            ),

            new WorkflowStep(
                key: 'phone',
                question: '📞 Telefon numarası nedir?',
                field: 'phone'
            ),

            new WorkflowStep(
                key: 'email',
                question: '📧 E-posta adresi nedir?',
                field: 'email'
            ),

            new WorkflowStep(
                key: 'tax_number',
                question: '🏛️ Vergi numarası nedir?',
                field: 'tax_number'
            ),

            new WorkflowStep(
                key: 'tax_office',
                question: '🏦 Vergi dairesi nedir?',
                field: 'tax_office'
            ),

            new WorkflowStep(
                key: 'address',
                question: '📍 Firma adresi nedir?',
                field: 'address'
            ),

        ]);
    }

    public function finish(array $data): array
    {
        /*
        |--------------------------------------------------------------------------
        | Aynı Firma Kontrolü
        |--------------------------------------------------------------------------
        */

        if ($this->service->existsByName($data['name'])) {

            return [
                'success' => false,
                'action'  => 'error',
                'message' => '⚠️ Bu isimde bir cari zaten mevcut.',
            ];
        }

        /*
        |--------------------------------------------------------------------------
        | Aynı Telefon Kontrolü
        |--------------------------------------------------------------------------
        */

        if (
            !empty($data['phone']) &&
            $this->service->existsByPhone($data['phone'])
        ) {

            return [
                'success' => false,
                'action'  => 'error',
                'message' => '⚠️ Bu telefon numarası başka bir cariye kayıtlı.',
            ];
        }

        /*
        |--------------------------------------------------------------------------
        | Aynı Vergi No Kontrolü
        |--------------------------------------------------------------------------
        */

        if (
            !empty($data['tax_number']) &&
            $this->service->existsByTaxNumber($data['tax_number'])
        ) {

            return [
                'success' => false,
                'action'  => 'error',
                'message' => '⚠️ Bu vergi numarası zaten kayıtlı.',
            ];
        }

        /*
        |--------------------------------------------------------------------------
        | Cari Oluştur
        |--------------------------------------------------------------------------
        */

        try {

            $customer = $this->service->create($data);

            return [

                'success' => true,

                'action' => 'save',

                'message' => "✅ {$customer->name} başarıyla oluşturuldu.",

                'data' => [

                    'id' => $customer->id,

                    'code' => $customer->code,

                    'customer' => $customer,

                ],

            ];

        } catch (\Throwable $e) {

            dd([
                'message' => $e->getMessage(),
                'file'    => $e->getFile(),
                'line'    => $e->getLine(),
                'trace'   => $e->getTraceAsString(),
            ]);

        }
    }
}