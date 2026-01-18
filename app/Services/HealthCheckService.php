<?php

namespace App\Services;

use App\Contracts\HealthCheckServiceInterface;

/**
 * Проверки состояния сервиса.
 */
class HealthCheckService implements HealthCheckServiceInterface
{
    /**
     * Редис должен быть доступен.
     */
    public function readiness(): array
    {
        $redisOk = $this->isRedisAvailable();

        return [
            'message' => $redisOk ? 'OK' : 'Redis unavailable',
            'redis' => $redisOk,
            'status' => $redisOk ? 200 : 503,
        ];
    }

    /**
     * Проверим что инстанс готов к работе.
     */
    private function isRedisAvailable(): bool
    {
        try {
            return (string) app('redis')->ping() === 'PONG';
        } catch (\Throwable $exception) {
            return false;
        }
    }
}
