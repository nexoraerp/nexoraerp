<?php

namespace App\Enums;

enum OnboardingTask: string
{
    case FirstCustomer = 'first_customer';
    case FirstProduct = 'first_product';
    case FirstSale = 'first_sale';
    case FirstPayment = 'first_payment';

    public function label(): string
    {
        return match ($this) {
            self::FirstCustomer => 'İlk cari kaydını oluştur',
            self::FirstProduct => 'İlk ürününü oluştur',
            self::FirstSale => 'İlk satışını oluştur',
            self::FirstPayment => 'İlk tahsilatını oluştur',
        };
    }

    public function completedAtColumn(): string
    {
        return match ($this) {
            self::FirstCustomer => 'first_customer_completed_at',
            self::FirstProduct => 'first_product_completed_at',
            self::FirstSale => 'first_sale_completed_at',
            self::FirstPayment => 'first_payment_completed_at',
        };
    }

    public static function count(): int
    {
        return count(self::cases());
    }
}
