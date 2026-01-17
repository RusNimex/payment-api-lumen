<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Str;

/**
 * Работа с токенами
 */
class TokenController extends Controller
{
    /**
     * Ручная выдача токена с фиксированным ключом
     * 
     * @param Request $request
     */
    public function issue(Request $request)
    {
        $config = config('services.token_issuer', []);
        $serviceName = (string) $request->header('X-Service-Name');
        $serviceSecret = (string) $request->header('X-Service-Secret');

        if (
            !isset($config['service_name'], $config['service_secret']) ||
            !hash_equals((string) $config['service_name'], $serviceName) ||
            !hash_equals((string) $config['service_secret'], $serviceSecret)
        ) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $ttlSeconds = (int) ($config['ttl_seconds'] ?? 86400);
        if ($ttlSeconds <= 0) {
            $ttlSeconds = 86400;
        }

        $rawToken = Str::random(48);
        $tokenHash = hash('sha256', $rawToken);
        $scopes = $this->normalizeScopes($config['scopes'] ?? []);
        $redisKey = (string) ($config['redis_prefix'] ?? 'svc_token:') . $tokenHash;

        $payload = [
            'service' => $serviceName,
            'scopes' => $scopes,
            'exp' => Carbon::now()->addSeconds($ttlSeconds)->timestamp,
        ];

        Redis::setex($redisKey, $ttlSeconds, json_encode($payload));

        return response()->json([
            'token' => $rawToken,
            'token_type' => 'Bearer',
            'expires_in' => $ttlSeconds,
            'scope' => $scopes,
        ]);
    }

    /**
     * Скоупы, которые разрешены выполнять токену
     * 
     * @param string $scopes
     */
    private function normalizeScopes(string $scopes): array
    {
        if (is_string($scopes)) {
            $scopes = array_map('trim', explode(',', $scopes));
        }

        if (!is_array($scopes)) {
            return [];
        }

        return array_values(array_filter(array_map('trim', $scopes)));
    }
}
