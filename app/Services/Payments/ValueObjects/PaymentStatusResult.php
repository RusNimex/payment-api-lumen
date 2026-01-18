<?php

namespace App\Services\Payments\ValueObjects;

/**
 * Результат проверки статуса оплаты
 */
final class PaymentStatusResult
{
    /**
     * @param bool $status - статус оплаты
     * @param string|null $message - сообщение от банка
     */
    private function __construct(
        private readonly bool $status,
        private readonly ?string $message,
    ){}

    /**
     * Успешная проверка статуса оплаты
     *
     * @param bool $status
     * @return self
     */
    public static function fromBoolean(bool $status): self
    {
        return new self($status, null);
    }

    /**
     * Успешная проверка статуса оплаты
     *
     * @param string $status
     * @return self
     */
    public static function fromString(string $status): self
    {
        return new self($status, null);
    }

    /**
     * Успешная или нет.
     *
     * @return bool
     */
    public function isSuccess(): bool
    {
        return $this->status;
    }

    /**
     * Что за ошибка в случае неудачи.
     *
     * @return string
     */
    public function message(): string
    {
        return $this->message;
    }

}
