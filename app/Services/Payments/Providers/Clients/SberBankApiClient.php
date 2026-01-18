<?php

namespace App\Services\Payments\Providers\Clients;

use App\Contracts\Payments\SberApiClientInterface;

/**
 * Клиент для API Сбербанка
 */
class SberBankApiClient implements SberApiClientInterface
{
    /**
     * {@inheritDoc}
     */
    public function CreatePayment(string $orderId, int $amount, string $contact): string
    {
        // TODO: Implement CreatePayment() method.
    }

    /**
     * {@inheritDoc}
     */
    public function CheckPaymentStatus(string $paymentUuid): string
    {
        // TODO: Implement CheckPaymentStatus() method.
    }
}
