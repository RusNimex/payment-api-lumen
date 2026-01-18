<?php

namespace App\Services\Payments\Providers;

use App\Contracts\Payments\CapturableBankApiAdapterInterface;
use App\Services\Payments\Enums\PayStatus;
use App\Services\Payments\Repositories\Order;

/**
 * Провайдер платежей алфа-банка
 */
class AlfaBankProvider extends BaseBankProvider
{
    /**
     * @param CapturableBankApiAdapterInterface $adapter - внедрение через {@see AppServiceProvider}
     */
    public function __construct(
        protected CapturableBankApiAdapterInterface $adapter
    ) {}

    /**
     * @inheritDoc
     */
    public function create(Order $order): ?string
    {
        $paymentId = $this->adapter->createPayment($order);

        if ($this->adapter->capturePayment($paymentId)) {
            return $paymentId;
        }

        $this->error = 'Не удалось создать платеж';

        return null;
    }

    /**
     * @inheritDoc
     */
    public function check(string $paymentId): bool
    {
        $response = $this->adapter->checkPaymentStatus($paymentId);

        if ($response === PayStatus::OK->value) {
            return true;
        }

        $this->error = PayStatus::from($response)->getMessage();

        return false;
    }

}
