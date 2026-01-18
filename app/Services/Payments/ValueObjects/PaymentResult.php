<?php

namespace App\Services\Payments\ValueObjects;

use App\Services\Payments\Repositories\Order;

/**
 * Результат создания платежа
 */
final class PaymentResult
{
    /**
     * @param bool $success - успешность платежа
     * @param Order|null $order - данные платежа
     * @param string|null $message - сообщение об ошибке
     */
    private function __construct(
        private readonly bool    $success,
        private readonly ?Order  $order,
        private readonly ?string $message,
        private readonly ?string $payId,
    ) {}

    /**
     * Успешное создание платеж.
     *
     * @param Order $order - данные платежа
     * @param string $payId
     * @return self
     */
    public static function success(Order $order, string $payId): self
    {
        return new self(
            success: true,
            order: $order,
            message: 'Платеж успешно создан, ожидаем оплаты',
            payId: $payId
        );
    }

    /**
     * Неудачное создание платежа
     *
     * @param string $error - сообщение об ошибке
     * @return self
     */
    public static function failure(string $error): self
    {
        return new self(
            success: false,
            order: null,
            message: $error,
            payId: null
        );
    }

    /**
     * Общее состояние.
     *
     * @return bool
     */
    public function isSuccess(): bool
    {
        return $this->success;
    }

    /**
     * Данные платежа.
     *
     * @return Order|null
     */
    public function order(): ?Order
    {
        return $this->order;
    }

    /**
     * Данные платежа.
     *
     * @return Order|null
     */
    public function payId(): ?string
    {
        return $this->payId;
    }

    /**
     * Сообщение об ошибке.
     *
     * @return string|null
     */
    public function message(): ?string
    {
        return $this->message;
    }
}
