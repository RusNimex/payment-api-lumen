<?php

namespace App\Http\Controllers;

use App\Contracts\HealthCheckServiceInterface;
use Illuminate\Http\JsonResponse;

/**
 * Контроллер для проверки работоспособности сервиса.
 */
class HealthCheckController extends Controller
{
    public function __construct(
        private readonly HealthCheckServiceInterface $healthCheckService
    ) {}

    /**
     * Проверка, что процесс жив.
     */
    public function liveness(): JsonResponse
    {
        return response()->json([
            'message' => 'OK',
        ]);
    }

    /**
     * Проверка готовности обслуживать запросы.
     */
    public function readiness(): JsonResponse
    {
        $result = $this->healthCheckService->readiness();

        return response()->json([
            'message' => $result['message'],
            'redis' => $result['redis'],
        ], $result['status']);
    }
}
