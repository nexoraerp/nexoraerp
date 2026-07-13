<?php

namespace App\Http\Controllers;

use App\Domain\Quotes\Services\QuoteService;
use App\Exceptions\BusinessException;
use App\Http\Requests\StoreQuoteRequest;
use App\Http\Requests\UpdateQuoteRequest;
use App\Models\Customer;
use App\Models\Product;
use App\Models\Quote;
use App\Models\Warehouse;
use Inertia\Inertia;

class QuoteController extends Controller
{
    public function __construct(
        protected QuoteService $quoteService
    ) {
    }

    public function index()
    {
        return Inertia::render('Quotes/Index', [
            'quotes' => Quote::with(['customer', 'user', 'sale'])
                ->latest()
                ->get(),
        ]);
    }

    public function create()
    {
        return Inertia::render('Quotes/Create', [
            'customers' => $this->customerOptions(),
            'products' => $this->productOptions(),
            'warehouses' => $this->warehouseOptions(),
        ]);
    }

    public function store(StoreQuoteRequest $request)
    {
        $quote = $this->quoteService->create($request->validated());

        return redirect()
            ->route('quotes.show', $quote)
            ->with('success', "Teklif {$quote->quote_no} başarıyla oluşturuldu.");
    }

    public function show(Quote $quote)
    {
        return Inertia::render('Quotes/Show', [
            'quote' => $quote->load([
                'customer',
                'items.product',
                'items.warehouse',
                'sale',
                'user',
            ])->append('is_convertible'),
        ]);
    }

    public function print(Quote $quote)
    {
        return Inertia::render('Quotes/Print', [
            'quote' => $quote->load([
                'customer',
                'items.product',
                'items.warehouse',
                'user',
            ]),
            'company' => [
                'name' => auth()->user()?->company_name ?: 'Nexora ERP',
                'contact_name' => auth()->user()?->name,
                'email' => auth()->user()?->email,
                'phone' => auth()->user()?->phone,
            ],
        ]);
    }

    public function edit(Quote $quote)
    {
        if (in_array($quote->status, ['Converted', 'Cancelled'], true)) {
            return redirect()
                ->route('quotes.show', $quote)
                ->with('error', 'Bu teklif düzenlenemez.');
        }

        return Inertia::render('Quotes/Edit', [
            'quote' => $quote->load('items'),
            'customers' => $this->customerOptions(),
            'products' => $this->productOptions(),
            'warehouses' => $this->warehouseOptions(),
        ]);
    }

    public function update(UpdateQuoteRequest $request, Quote $quote)
    {
        try {
            $this->quoteService->update($quote, $request->validated());
        } catch (BusinessException $exception) {
            return back()->with('error', $exception->getMessage());
        }

        return redirect()
            ->route('quotes.show', $quote)
            ->with('success', 'Teklif başarıyla güncellendi.');
    }

    public function send(Quote $quote)
    {
        try {
            $this->quoteService->markAsSent($quote);
        } catch (BusinessException $exception) {
            return back()->with('error', $exception->getMessage());
        }

        return back()->with('success', 'Teklif gönderildi olarak işaretlendi.');
    }

    public function accept(Quote $quote)
    {
        $this->quoteService->accept($quote);

        return back()->with('success', 'Teklif kabul edildi.');
    }

    public function reject(Quote $quote)
    {
        $this->quoteService->reject($quote);

        return back()->with('success', 'Teklif reddedildi.');
    }

    public function analyze(Quote $quote)
    {
        $this->quoteService->refreshAnalysis($quote);

        return back()->with('success', 'Teklif analizi güncellendi.');
    }

    public function convert(Quote $quote)
    {
        try {
            $sale = $this->quoteService->convertToSale($quote);
        } catch (BusinessException $exception) {
            return back()->with('error', $exception->getMessage());
        }

        return redirect()
            ->route('sales.show', $sale)
            ->with('success', "Teklif satışa dönüştürüldü: {$sale->sale_no}");
    }

    private function customerOptions()
    {
        return Customer::orderBy('name')
            ->get()
            ->map(fn ($customer) => [
                'value' => $customer->id,
                'label' => $customer->code . ' - ' . $customer->name,
            ]);
    }

    private function productOptions()
    {
        return Product::orderBy('name')
            ->get()
            ->map(fn ($product) => [
                'value' => $product->id,
                'label' => $product->code . ' - ' . $product->name,
                'price' => (float) $product->sale_price,
                'purchase_price' => (float) $product->purchase_price,
                'vat' => (float) $product->vat,
                'stock' => (float) $product->current_stock,
                'unit' => $product->unit,
                'brand' => $product->brand,
                'model' => $product->model,
                'barcode' => $product->barcode,
            ]);
    }

    private function warehouseOptions()
    {
        return Warehouse::orderBy('name')
            ->get()
            ->map(fn ($warehouse) => [
                'value' => $warehouse->id,
                'label' => $warehouse->name,
            ]);
    }
}
