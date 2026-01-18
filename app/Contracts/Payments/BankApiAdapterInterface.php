<?php

namespace App\Contracts\Payments;
use App\Services\Payments\Repositories\Order;

interface BankApiAdapterInterface
{
    /**
     * Создание платежа в банке
     *
     * @param Order $order
     * @return mixed
     */
    public function createPayment(Order $order): string;

    /**
     * Проверка статуса платежа
     *
     * @param string $paymentId
     * @return string - {@see PayStatus}
     */
    public function checkPaymentStatus(string $paymentId): string;
}
