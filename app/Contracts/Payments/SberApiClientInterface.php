<?php

namespace App\Contracts\Payments;

/**
 * Клиент для API Сбербанка
 */
interface SberApiClientInterface
{
    /**
     * Создает платеж на стороне сбербанка и возвращает его ИД
     *
     * @param string $orderId ИД заказа на стороне магазина
     * @param int $amount Сумма платежа
     * @param string $contact Контактная информация покупателя
     * @return string
     */
    public function CreatePayment(string $orderId, int $amount, string $contact): string;

    /**
     * Возвращает статус платежа по его ИД
     *
     * @param string $paymentUuid
     * @return string
     */
    public function CheckPaymentStatus(string $paymentUuid): string;
}
