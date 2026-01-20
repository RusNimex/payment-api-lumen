<?php

namespace App\Services\Payments\Repositories;

/**
 * Заказ, который нужно оплатить.
 */
class Order
{
    /**
     * @var string - товар магазина
     */
    public string $productId;

    /**
     * @var int - количество товара
     */
    public int $amount;

    /**
     * @var float - цена товара
     */
    public float $price;

    /**
     * @var string - валюта товара
     */
    public string $currency;

    /**
     * @var string - email пользователя
     */
    public string $email;

    /**
     * @var null|string - секретный ключ пользователя
     */
    public ?string $apiSecret;

    /**
     * @var string - название платежной системы {@see BankNames}
     */
    public string $payProviderName;

    public function __construct(
        string $productId,
        int $amount,
        float $price,
        string $currency,
        string $email,
        string $apiSecret,
        string $payProviderName
    ) {}

    public static function create(
        string $productId,
        int $amount,
        float $price,
        string $currency,
        string $email,
        string $apiSecret,
        string $payProviderName
    ): self
    {
        return new self(
            $productId,
            $amount,
            $price,
            $currency,
            $email,
            $apiSecret,
            $payProviderName
        );
    }

}
