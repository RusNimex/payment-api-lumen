<?php

namespace App\Services\Payments\Providers\Adapters;

use App\Contracts\Payments\AlfaApiClientInterface;
use App\Contracts\Payments\CapturableBankApiAdapterInterface;
use App\Services\Payments\Repositories\Order;

/**
 * API адаптер для Альфа-банка
 */
class AlfaApiAdapter implements CapturableBankApiAdapterInterface
{
    /**
     * @param AlfaApiClientInterface $api
     */
    public function __construct(
        protected AlfaApiClientInterface $api
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
            auth()->user()->apiSecret,
        );
    }

    /**
     * @inheritDoc
     */
    public function checkPaymentStatus(string $paymentId): string
    {
        return $this->api->CheckPaymentStatus($paymentId);
    }

    /**
     * @inheritDoc
     */
    public function capturePayment(int $paymentId): bool
    {
        return $this->api->CapturePayment($paymentId);
    }

}
