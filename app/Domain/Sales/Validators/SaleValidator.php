<?php

namespace App\Domain\Sales\Validators;

use App\Domain\Kernel\Validator;
use App\Exceptions\BusinessException;

class SaleValidator extends Validator
{
    public function validate(array $data): array
    {
        if (empty($data['customer_id'])) {
            throw new BusinessException(
                'Lütfen bir cari seçiniz.'
            );
        }

        if (empty($data['items'])) {
            throw new BusinessException(
                'Satış için en az bir ürün eklemelisiniz.'
            );
        }

        foreach ($data['items'] as $index => $item) {

            if (empty($item['product_id'])) {
                throw new BusinessException(
                    ($index + 1) . '. satırda ürün seçilmedi.'
                );
            }

            if (empty($item['warehouse_id'])) {
                throw new BusinessException(
                    ($index + 1) . '. satırda depo seçilmedi.'
                );
            }

            if (($item['quantity'] ?? 0) <= 0) {
                throw new BusinessException(
                    sprintf("%d. satırdaki miktar 0'dan büyük olmalıdır.", $index + 1)
                );
            }

            if (($item['unit_price'] ?? 0) < 0) {
                throw new BusinessException(
                    ($index + 1) . '. satırdaki fiyat geçersiz.'
                );
            }
        }

        return $data;
    }
}