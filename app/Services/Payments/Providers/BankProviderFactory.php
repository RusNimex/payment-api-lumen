<?php

namespace App\Services\Payments\Providers;

use App\Contracts\Payments\BankProviderInterface;
use App\Exceptions\Payments\PaymentsException;
use App\Services\Payments\Enums\BankNames;
use Illuminate\Container\Container;
use Illuminate\Contracts\Container\BindingResolutionException;

/**
 * Фабрика для создания провайдера платежной системы.
 */
class BankProviderFactory
{
    /**
     * @param Container $container - внедрим контейнер
     */
    public function __construct(
        protected Container $container
    ){}

    /**
     * Выберем нужный провайдер и вернем в сервис.
     *
     * @param string $bankName - название банка
     * @throws BindingResolutionException|PaymentsException - обработаем в {@see \App\Exceptions\Handler}.
     */
    public function create(string $bankName): BankProviderInterface
    {
        $providerClassName = match ($bankName) {
            BankNames::SBER->value => SberBankProvider::class,
            BankNames::ALFA->value => AlfaBankProvider::class,
            default => throw PaymentsException::unknownBank($bankName)
        };

        return $this->container->make($providerClassName);
    }
}
