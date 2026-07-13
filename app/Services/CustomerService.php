<?php

namespace App\Services;

use App\Models\Customer;
use Illuminate\Database\Eloquent\Collection;

class CustomerService extends BaseService
{
    public function __construct()
    {
        $this->model = new Customer();
    }

    /*
    |--------------------------------------------------------------------------
    | Cari Oluştur
    |--------------------------------------------------------------------------
    */

    public function create(array $data): Customer
    {
        return Customer::create([

            'code' => 'CR' . now()->format('YmdHis'),

            'name' => $data['name'],

            'company' => $data['company'] ?? $data['name'],

            'phone' => $data['phone'] ?? null,

            'email' => $data['email'] ?? null,

            'tax_number' => $data['tax_number'] ?? null,

            'tax_office' => $data['tax_office'] ?? null,

            'address' => $data['address'] ?? null,

            'type' => $data['type'] ?? 'customer',

            'is_active' => $data['is_active'] ?? true,

        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Arama
    |--------------------------------------------------------------------------
    */

    public function search(string $search): Collection
    {
        return Customer::query()

            ->where('name', 'like', "%{$search}%")
            ->orWhere('company', 'like', "%{$search}%")
            ->orWhere('code', 'like', "%{$search}%")
            ->orWhere('phone', 'like', "%{$search}%")
            ->orWhere('tax_number', 'like', "%{$search}%")

            ->get();
    }

    /*
    |--------------------------------------------------------------------------
    | Aynı Firma Kontrolü
    |--------------------------------------------------------------------------
    */

    public function existsByName(string $name): bool
    {
        return Customer::query()
            ->where('name', $name)
            ->exists();
    }

    /*
    |--------------------------------------------------------------------------
    | Aynı Telefon Kontrolü
    |--------------------------------------------------------------------------
    */

    public function existsByPhone(?string $phone): bool
    {
        if (blank($phone)) {
            return false;
        }

        return Customer::query()
            ->where('phone', $phone)
            ->exists();
    }

    /*
    |--------------------------------------------------------------------------
    | Aynı Vergi No Kontrolü
    |--------------------------------------------------------------------------
    */

    public function existsByTaxNumber(?string $taxNumber): bool
    {
        if (blank($taxNumber)) {
            return false;
        }

        return Customer::query()
            ->where('tax_number', $taxNumber)
            ->exists();
    }

    /*
    |--------------------------------------------------------------------------
    | Cari Bakiyesi
    |--------------------------------------------------------------------------
    */

    public function balance(Customer $customer): float
    {
        return (float) $customer->balance;
    }

    /*
    |--------------------------------------------------------------------------
    | Cari Ekstresi
    |--------------------------------------------------------------------------
    */

    public function statement(Customer $customer): Collection
    {
        return $customer
            ->movements()
            ->latest()
            ->get();
    }

    /*
    |--------------------------------------------------------------------------
    | Koda Göre Bul
    |--------------------------------------------------------------------------
    */

    public function findByCode(string $code): ?Customer
    {
        return Customer::query()
            ->where('code', $code)
            ->first();
    }

    /*
    |--------------------------------------------------------------------------
    | İsme Göre Bul
    |--------------------------------------------------------------------------
    */

    public function findByName(string $name): ?Customer
    {
        return Customer::query()
            ->where('name', $name)
            ->first();
    }
}