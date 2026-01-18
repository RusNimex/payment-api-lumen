<?php

namespace App\Services\Payments\Providers;

use App\Contracts\Payments\BankApiAdapterInterface;
use App\Services\Payments\Enums\PayStatus;
use App\Services\Payments\Repositories\Order;

/**
 * Провайдер платежей Сбербанка
 */
class SberBankProvider extends BaseBankProvider
{
    /**
     * @param BankApiAdapterInterface $adapter - внедрение через {@see AppServiceProvider}
     */
    public function __construct(
        protected BankApiAdapterInterface $adapter
    ) {}

    /**
     * @inheritDoc
     */
    public function create(Order $order): ?string
    {
        $paymentId = $this->adapter->createPayment($order);

        if ( ! empty($paymentId)) {
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

        if ($response === PayStatus::SUCCESS->value) {
            return true;
        }

        $this->error = PayStatus::from($response)->getMessage();

        return false;
    }

}
