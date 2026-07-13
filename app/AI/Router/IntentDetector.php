<?php

namespace App\AI\Router;

class IntentDetector
{
    public function detect(string $message): string
    {
        $text = mb_strtolower($message);

        return match (true) {

            str_contains($text, 'cari') &&
            (
                str_contains($text, 'oluştur') ||
                str_contains($text, 'ekle')
            ) => 'customer.create',

            str_contains($text, 'satış') &&
            (
                str_contains($text, 'oluştur') ||
                str_contains($text, 'ekle')
            ) => 'sale.create',

            str_contains($text, 'ürün') &&
            (
                str_contains($text, 'oluştur') ||
                str_contains($text, 'ekle')
            ) => 'product.create',

            str_contains($text, 'tahsilat') => 'payment.create',

            str_contains($text, 'kasa') => 'finance.cash',

            default => 'chat',
        };
    }
}