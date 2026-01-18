<?php

namespace App\Services\Payments\Enums;

/**
 * Статусы платежа, которые приходят от банков.
 */
enum PayStatus: string
{
    case SUCCESS = 'success';
    case OK = 'ok';
    case ERROR = 'error';
    case FAILURE = 'failure';
    case PENDING = 'pending';
    case CAPTURE_WAITING = 'capture_waiting';
    case BANNED = 'banned';

    public function getMessage(): bool
    {
        return match($this) {
            self::CAPTURE_WAITING,
            self::BANNED,
            self::FAILURE,
            self::ERROR => 'Ошибка платежа',
            self::PENDING => 'Ожидание платежа',
            self::OK,
            self::SUCCESS => 'Успешный платеж',
        };
    }
}
