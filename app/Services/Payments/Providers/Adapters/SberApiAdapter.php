<?php

namespace App\Services\Payments\Providers\Adapters;

use App\Contracts\Payments\BankApiAdapterInterface;
use App\Contracts\Payments\SberApiClientInterface;
use App\Services\Payments\Repositories\Order;

/**
 * API адаптер для Сбербанка
 */
class SberApiAdapter implements BankApiAdapterInterface
{
    /**
     * @param SberApiClientInterface $api
     */
    public function __construct(
        protected SberApiClientInterface $api
    ) {}

    /**
     * @inheritDoc
     */
    public function createPayment(Order $order): string
    {
        return $this->api->CreatePayment(
            $order->productId,
            $order->amount,
            auth()->user()->email,
        );
    }

    /**
     * @inheritDoc
     */
    public function checkPaymentStatus(string $paymentId): string
    {
        return $this->api->CheckPaymentStatus($paymentId);
    }

}
