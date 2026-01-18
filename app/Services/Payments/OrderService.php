<?php

namespace App\Services\Payments;

use App\Services\Payments\Repositories\Order;

class OrderService
{
    /**
     * Создание заказа
     *
     * @param string $productId - товар
     * @param int $amount - кол-во
     * @return Order
     */
    public function create(string $productId, int $amount): Order
    {
        return Order::create($productId, $amount);
    }
}
