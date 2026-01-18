<?php

namespace App\Contracts;

/**
 * Проверки состояния сервиса.
 */
interface HealthCheckServiceInterface
{
    /**
     * Редис должен быть доступен.
     *
     * @return array{message: string, redis: bool, status: int}
     */
    public function readiness(): array;
}
