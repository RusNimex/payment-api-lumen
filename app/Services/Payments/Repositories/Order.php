<?php

namespace App\Services\Payments\Repositories;

class Order
{
    public string $productId;
    public int $amount;

    public function __construct(string $productId, int $amount)
    {
        $this->productId = $productId;
        $this->amount = $amount;
    }

    /**
     * Создание заказа
     *
     * @param string $productId
     * @param int $amount
     * @return self
     */
    public static function create(string $productId, int $amount): self
    {
        return new self($productId, $amount);
    }

}
