<?php

namespace App\Services\Payments\Providers\Clients;

use App\Contracts\Payments\AlfaApiClientInterface;

/**
 * Клиент для API AlfaBank
 */
class AlfaBankApiClient implements AlfaApiClientInterface
{
    /**
     * {@inheritDoc}
     */
    public function CreatePayment(string $orderId, int $amount, string $contact, string $apiSecret): string
    {
        // TODO: Implement CreatePayment() method.
    }

    /**
     * {@inheritDoc}
     */
    public function CheckPaymentStatus(string $paymentUuid): bool
    {
        // TODO: Implement CheckPaymentStatus() method.
    }

    /**
     * {@inheritDoc}
     */
    public function CapturePayment(string $paymentUuid): string
    {
        // TODO: Implement CapturePayment() method.
    }
}
