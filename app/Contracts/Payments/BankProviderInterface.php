<?php

namespace App\Contracts\Payments;

use App\Services\Payments\Repositories\Order;

interface BankProviderInterface
{
    /**
     * Создание платежа
     *
     * @param Order $order
     * @return string|null
     */
    public function create(Order $order): ?string;

    /**
     * Проверка статуса платежа
     *
     * @param string $paymentId
     * @return bool
     */
    public function check(string $paymentId): bool;

    /**
     * Получение ошибки
     *
     * @return string
     */
    public function getError(): string;
}
