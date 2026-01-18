<?php

namespace App\Services\Payments\Providers;

use App\Contracts\Payments\BankProviderInterface;

/**
 * Базовый класс для банков.
 */
abstract class BaseBankProvider implements BankProviderInterface
{
    /**
     * @var string ошибка апи
     */
    protected string $error = '';

    /**
     * @inheritDoc
     */
    public function getError(): string
    {
        return $this->error;
    }

}
