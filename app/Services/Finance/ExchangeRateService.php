<?php

namespace App\Services\Finance;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use SimpleXMLElement;
use Throwable;

class ExchangeRateService
{
    private const TCMB_URL = 'https://www.tcmb.gov.tr/kurlar/today.xml';

    public function latest(): array
    {
        $cacheKey = 'finance.exchange_rates.tcmb.latest';

        try {
            if (Cache::has($cacheKey)) {
                return Cache::get($cacheKey);
            }

            $response = Http::timeout(5)->get(self::TCMB_URL);

            if (! $response->successful()) {
                return $this->cacheUnavailable($cacheKey);
            }

            $xml = simplexml_load_string($response->body());

            if (! $xml instanceof SimpleXMLElement) {
                return $this->cacheUnavailable($cacheKey);
            }

            $rates = [
                'source' => 'TCMB',
                'date' => (string) ($xml['Tarih'] ?? $xml['Date'] ?? now()->format('d.m.Y')),
                'updated_at' => now()->format('d.m.Y H:i'),
                'available' => true,
                'items' => $this->currencies($xml),
            ];

            try {
                Cache::put($cacheKey, $rates, now()->addMinutes(30));
            } catch (Throwable $exception) {
                report($exception);
            }

            return $rates;
        } catch (Throwable $exception) {
            report($exception);

            return $this->unavailable();
        }
    }

    private function currencies(SimpleXMLElement $xml): array
    {
        $wanted = ['USD', 'EUR', 'GBP', 'CHF'];
        $items = [];

        foreach ($xml->Currency as $currency) {
            $code = (string) $currency['CurrencyCode'];

            if (! in_array($code, $wanted, true)) {
                continue;
            }

            $items[] = [
                'code' => $code,
                'name' => (string) $currency->Isim,
                'forex_buying' => $this->rate($currency->ForexBuying),
                'forex_selling' => $this->rate($currency->ForexSelling),
                'banknote_buying' => $this->rate($currency->BanknoteBuying),
                'banknote_selling' => $this->rate($currency->BanknoteSelling),
            ];
        }

        return $items;
    }

    private function rate(SimpleXMLElement|string|null $value): ?float
    {
        $normalized = trim((string) $value);

        return $normalized === '' ? null : (float) str_replace(',', '.', $normalized);
    }

    private function unavailable(): array
    {
        return [
            'source' => 'TCMB',
            'date' => null,
            'updated_at' => now()->format('d.m.Y H:i'),
            'available' => false,
            'items' => [],
        ];
    }

    private function cacheUnavailable(string $cacheKey): array
    {
        $rates = $this->unavailable();

        try {
            Cache::put($cacheKey, $rates, now()->addMinutes(5));
        } catch (Throwable $exception) {
            report($exception);
        }

        return $rates;
    }
}
