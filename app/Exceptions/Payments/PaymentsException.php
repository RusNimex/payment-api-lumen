<?php 

namespace App\Exceptions\Payments;

use RuntimeException;

/**
 * Проблемы при создании платежа
 */
class PaymentsException extends RuntimeException
{
    /**
     * Создание исключения для неподдерживаемого банка
     *
     * @param string $name - название банка
     * @return self
     */
    public static function unknownBank(string $name): self
    {
        return new self("Bank '{$name}' not handled");
    }

}
