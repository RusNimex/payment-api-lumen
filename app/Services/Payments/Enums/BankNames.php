<?php

namespace App\Services\Payments\Enums;

enum BankNames: string
{
    /** Сбербанк */
    case SBER = 'sber';

    /** AlphaBank */
    case ALFA = 'alfa';
}
