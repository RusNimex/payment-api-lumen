<?php

namespace App\Services\Payments;

use App\Http\Validators\Payments\PayRequest;
use App\Services\Payments\Repositories\Order;

class OrderService
{
    /**
     * Создание заказа
     *
     * @param PayRequest $request
     * @return Order
     */
    public function createFromRequest(PayRequest $request): Order
    {
        return Order::create(
            $request['productId'],
            $request['amount'],
            $request['price'],
            $request['currency'],
            $request['email'],
            $request['apiSecret'] ?? null,
            $request['payProviderName']
        );
    }
}
