<?php

namespace App\Domain\Sales\Workflows;

use App\Domain\Kernel\Workflow;
use App\Domain\Sales\Validators\SaleValidator;
use App\Domain\Sales\Calculators\SaleCalculator;
use App\Domain\Sales\Actions\CreateSaleAction;
use App\Domain\Sales\Actions\CreateSaleItemsAction;
use App\Domain\Sales\Actions\CreateStockMovementsAction;
use App\Domain\Sales\Actions\CreateAccountMovementAction;
use App\Models\Sale;

class CreateSaleWorkflow extends Workflow
{
    public function __construct(
        protected SaleValidator $validator,
        protected SaleCalculator $calculator,
        protected CreateSaleAction $createSale,
        protected CreateSaleItemsAction $createItems,
        protected CreateStockMovementsAction $createStock,
        protected CreateAccountMovementAction $createMovement,
    ) {
    }

    public function handle(array $data): Sale
    {
        $validated = $this->validator->validate($data);

        $prepared = $this->calculator->calculate($validated);

        $sale = $this->createSale->execute(
            $prepared,
            $prepared['totals']
        );

        $this->createItems->execute(
            $sale,
            $prepared['items']
        );

        $this->createStock->execute(
            $sale,
            $prepared['items']
        );

        $this->createMovement->execute($sale);

        return $sale->fresh([
            'customer',
            'items.product',
        ]);
    }
}