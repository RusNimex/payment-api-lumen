<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;

class TokenAuth
{
    /**
     * Токен должен быть в заголовке.
     */
    public function handle($request, Closure $next, $guard = null)
    {
        $token = $this->extractToken($request);
        if ($token === null) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $payload = $this->validateToken($token);
        if ($payload === null) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $request->attributes->set('service_token', $payload);

        return $next($request);
    }

    /**
     * Получим токен из заголовка.
     */
    private function extractToken(Request $request): ?string
    {
        $token = $request->bearerToken();
        if ($token !== null && $token !== '') {
            return $token;
        }

        return null;
    }

    /**
     * Валидируем токен
     *
     * @return array<string, mixed>|null
     */
    private function validateToken(string $rawToken): ?array
    {
        $tokenHash = hash('sha256', $rawToken);
        $prefix = (string) config('services.token_issuer.redis_prefix', 'svc_token:');
        $redisKey = $prefix . $tokenHash;

        $rawPayload = Redis::get($redisKey);
        if ($rawPayload === null) {
            return null;
        }

        $payload = json_decode($rawPayload, true);
        if (!is_array($payload)) {
            return null;
        }

        if (isset($payload['exp']) && is_numeric($payload['exp'])) {
            if (time() >= (int) $payload['exp']) {
                Redis::del($redisKey);
                return null;
            }
        }

        return $payload;
    }
}
