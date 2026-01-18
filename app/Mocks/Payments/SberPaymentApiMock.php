<?php

namespace App\Mocks\Payments;

use App\Contracts\Payments\SberApiClientInterface;
use Illuminate\Support\Str;

class SberPaymentApiMock implements SberApiClientInterface
{
    /**
     * Создает платеж на стороне сбербанка и возвращает его ИД
     *
     * @param string $orderId ИД заказа на стороне магазина
     * @param int $amount Сумма платежа
     * @param string $contact Контактная информация покупателя
     * @return string
     */
    public function CreatePayment(string $orderId, int $amount, string $contact): string
    {
        return Str::uuid();
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
            'success',
            'error',
        ];

        return $statuses[rand(0, count($statuses) - 1)];
    }
}
