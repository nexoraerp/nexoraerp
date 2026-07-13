<?php

namespace App\Domain\Quotes\Services;

use App\Models\Quote;

class QuoteAnalysisService
{
    public function analyze(Quote $quote): array
    {
        $quote->loadMissing(['customer', 'items.product']);

        $score = 50;
        $signals = [];
        $risks = [];
        $suggestions = [];

        if ((float) $quote->grand_total >= 100000) {
            $score += 12;
            $signals[] = 'Yüksek tutarlı teklif, yönetici takibi önerilir.';
        }

        if ($quote->valid_until && now()->diffInDays($quote->valid_until, false) <= 7) {
            $score += 8;
            $signals[] = 'Geçerlilik süresi yakın, hızlı takip fırsatı var.';
        }

        if ((int) $quote->probability >= 70) {
            $score += 15;
            $signals[] = 'Kazanma olasılığı yüksek görünüyor.';
        }

        if ((int) $quote->probability <= 30) {
            $score -= 12;
            $risks[] = 'Kazanma olasılığı düşük, fiyat veya şartlar yeniden değerlendirilmeli.';
        }

        $discountTotal = $quote->items->sum(fn ($item) => (float) $item->discount);

        if ($discountTotal > 0 && $quote->subtotal > 0) {
            $discountRate = ($discountTotal / (float) $quote->subtotal) * 100;

            if ($discountRate > 15) {
                $score -= 10;
                $risks[] = 'İskonto oranı yüksek, karlılık kontrol edilmeli.';
            }
        }

        if ($quote->items->count() >= 5) {
            $score += 6;
            $signals[] = 'Çok kalemli teklif, çapraz satış potansiyeli taşıyor.';
        }

        if ($quote->customer?->balance > 0) {
            $risks[] = 'Cari açık bakiye taşıyor, ödeme şartları netleştirilmeli.';
        }

        $score = max(0, min(100, $score));

        if ($score >= 75) {
            $level = 'strong';
            $summary = 'Teklif güçlü görünüyor. Kısa takip ve net kapanış aksiyonu önerilir.';
        } elseif ($score >= 50) {
            $level = 'watch';
            $summary = 'Teklif orta seviyede. Şartlar, vade ve müşteri ihtiyacı netleştirilmeli.';
        } else {
            $level = 'risk';
            $summary = 'Teklif riskli görünüyor. Fiyat, iskonto ve ödeme koşulları yeniden çalışılmalı.';
        }

        $suggestions[] = 'Müşteriye teklif geçerlilik tarihinden önce hatırlatma yapılmalı.';
        $suggestions[] = 'Teklif kalemleri ihtiyaca göre paketlenip alternatif senaryo sunulmalı.';

        if ($quote->customer?->balance > 0) {
            $suggestions[] = 'Açık bakiye için tahsilat planı teklif kabulünden önce konuşulmalı.';
        }

        return [
            'score' => $score,
            'level' => $level,
            'summary' => $summary,
            'signals' => $signals,
            'risks' => $risks,
            'suggestions' => $suggestions,
            'generated_at' => now()->toDateTimeString(),
        ];
    }
}
