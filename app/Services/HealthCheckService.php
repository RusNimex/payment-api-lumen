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
            $response = app('redis')->ping();

            if (is_bool($response)) {
                return $response;
            }

            if (is_int($response)) {
                return $response === 1;
            }

            $pong = strtoupper(ltrim((string) $response, '+'));

            return $pong === 'PONG';
        } catch (\Throwable $exception) {
            return false;
        }
    }
}
