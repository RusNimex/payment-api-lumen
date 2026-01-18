<?php

namespace App\Contracts\Payments;

interface CapturableBankApiAdapterInterface extends BankApiAdapterInterface
{
    /**
     * Готовность принять оплату от юзера
     *
     * @param int $paymentId
     * @return bool
     */
    public function capturePayment(int $paymentId): bool;
}
