<?php

namespace App\Models\PaymentMethod;

class PaymentMethodFactory {
    public static function create(string $type, array $data): PaymentMethodInterface {
        switch ($type) {
            case 'CreditCard':
                return new CreditCard(
                    $data['card_number'] ?? '',
                    $data['expiration_date'] ?? '',
                    $data['cardholder_name'] ?? '',
                    $data['status'] ?? true,
                    $data['patient_id'] ?? null,
                    $data['id'] ?? null
                );
                
            case 'ACH':
                return new ACH(
                    $data['account_number'] ?? '',
                    $data['routing_number'] ?? '',
                    $data['account_holder_name'] ?? '',
                    $data['status'] ?? true,
                    $data['patient_id'] ?? null,
                    $data['id'] ?? null
                );
                
            default:
                throw new \InvalidArgumentException("Invalid payment method type: $type");
        }
    }
}