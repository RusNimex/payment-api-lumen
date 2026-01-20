<?php

namespace App\Contracts\Payments;

use App\Exceptions\Payments\PaymentsException;
use App\Services\Payments\Enums\BankNames;
use App\Services\Payments\Repositories\Order;
use App\Services\Payments\ValueObjects\PaymentResult;
use App\Services\Payments\ValueObjects\PaymentStatusResult;
use Illuminate\Contracts\Container\BindingResolutionException;

/**
 * Платежная система должны быть представлена в виде провайдера.
 */
interface PaymentProviderInterface
{
    /**
     * Создадим оплату через провайдера.
     *
     * Если проблемы на стороне сети|банка, то вернем null
     * @param Order $order - полная инфа по заказу включая и название платежной системы.
     * @return PaymentResult
     * @throws BindingResolutionException
     * @throws PaymentsException
     */
    public function create(Order $order): PaymentResult;

    /**
     * Проверим что оплата прошла.
     *
     * Оплачивается не мгновенно, поэтому после создания оплаты
     * нужно подождать некоторое время, а потом проверить статус оплаты.
     *
     * Удобно получать оплату через веб-хук.
     * @param string $paymentUuid
     * @param string $payProviderName - название платежной системы {@see BankNames}
     * @return PaymentStatusResult
     * @throws BindingResolutionException|PaymentsException
     */
    public function check(string $paymentUuid, string $payProviderName): PaymentStatusResult;

}
