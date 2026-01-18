<?php

namespace App\Mocks\Payments;

use App\Contracts\Payments\AlfaApiClientInterface;
use Illuminate\Support\Str;

class AlphaPaymentApiMock implements AlfaApiClientInterface
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
    public function CreatePayment(string $orderId, int $amount, string $contact, string $apiSecret): string
    {
        return Str::uuid();
    }

    /**
     * Подтверждает платеж на стороне AlphaBank, после чего пользователь может сделать перевод
     *
     * @param string $paymentUuid
     * @return bool
     */
    public function CapturePayment(string $paymentUuid): bool
    {
        return (bool)rand(0, 1);
    }

    /**
     * Возвращает статус платежа по его ИД
     *
     * @param string $paymentUuid
     * @return string
     */
    public function CheckPaymentStatus(string $paymentUuid): string
    {
        $statuses = [
            'capture_waiting',
            'ok',
            'error',
            'banned',
        ];

        return $statuses[rand(0, count($statuses) - 1)];
    }
}
