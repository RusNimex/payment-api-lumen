<?php

namespace App\Services\Payments;

use App\Contracts\Payments\PaymentProviderInterface;
use App\Services\Payments\Providers\BankProviderFactory;
use App\Services\Payments\Repositories\Order;
use App\Services\Payments\ValueObjects\PaymentResult;
use App\Services\Payments\ValueObjects\PaymentStatusResult;

/**
 * Сервис-оркестратор для оплаты товара через банк-провайдера.
 *
 * Банк-провайдеры создают фабрикой @see BankProviderFactory
 * Для расширения списка банков нужно в фабрике добавить новый класс.
 */
class PayProviderService implements PaymentProviderInterface
{
    /**
     * Создадим провайдера
     *
     * @param BankProviderFactory $factory - фабрика для создания банк-провайдера
     */
    public function __construct(
        protected BankProviderFactory $factory
    ){}

    /**
     * {@inheritDoc}
     */
    public function create(string $bankName, Order $order): PaymentResult
    {
        $provider = $this->factory->create($bankName);
        $payId = $provider->create($order);

        return $payId === null
            ? PaymentResult::failure($provider->getError())
            : PaymentResult::success($order, $payId);
    }

    /**
     * {@inheritDoc}
     */
    public function check(string $paymentUuid, string $bankName): PaymentStatusResult
    {
        $provider = $this->factory->create($bankName);

        return PaymentStatusResult::fromBoolean($provider->check($paymentUuid));
    }

}
