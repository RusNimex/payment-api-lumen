<?php

namespace App\Contracts\Payments;

interface AlfaApiClientInterface
{
    /**
     * Создает сырой платеж на стороне провайдера, и возвращает ИД платежа.
     * После создания платежа его нужно подтвердить к готовности принимать оплату
     *
     * @param string $orderId ИД заказа на стороне магазина
     * @param int $amount Сумма платежа
     * @param string $contact Контактная информация покупателя
     * @param string $apiSecret Секретный ключ AlphaBank из кабинета
     * @return string
     */
    public function CreatePayment(string $orderId, int $amount, string $contact, string $apiSecret): string;

    /**
     * Подтверждает платеж на стороне AlphaBank, после чего пользователь может сделать перевод
     *
     * @param string $paymentUuid
     * @return bool
     */
    public function CheckPaymentStatus(string $paymentUuid): bool;

    /**
     * Возвращает статус платежа по его ИД
     *
     * @param string $paymentUuid
     * @return string
     */
    public function CapturePayment(string $paymentUuid): string;
}
