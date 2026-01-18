<?php

namespace App\Contracts\Payments;

use App\Exceptions\Payments\PaymentsException;
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
     * @param string $bankName
     * @param Order $order - полная инфа по заказу
     * @return PaymentResult
     * @throws BindingResolutionException
     * @throws PaymentsException
     */
    public function create(string $bankName, Order $order): PaymentResult;

    /**
     * Проверим что оплата прошла.
     *
     * Оплачивается не мгновенно, поэтому после создания оплаты
     * нужно подождать некоторое время, а потом проверить статус оплаты.
     *
     * Удобно получать оплату через веб-хук.
     * @param string $paymentUuid
     * @param string $bankName
     * @return PaymentStatusResult
     * @throws BindingResolutionException|PaymentsException
     */
    public function check(string $paymentUuid, string $bankName): PaymentStatusResult;

}
