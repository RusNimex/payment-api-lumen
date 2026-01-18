<?php

namespace App\Services;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Str;
use App\Contracts\TokenServiceInterface;

/**
 * Personal Access Token для сервисов.
 */
class TokenService implements TokenServiceInterface
{
    /**
     * Выдает токен для сервиса или возвращает null при ошибке авторизации.
     *
     * @return array<string, mixed>|null
     */
    public function issueToken(string $serviceName, string $serviceSecret): ?array
    {
        $config = config('services.token_issuer', []);

        if (
            !isset($config['service_name'], $config['service_secret']) ||
            !hash_equals((string) $config['service_name'], $serviceName) ||
            !hash_equals((string) $config['service_secret'], $serviceSecret)
        ) {
            return null;
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

        return [
            'token' => $rawToken,
            'token_type' => 'Bearer',
            'expires_in' => $ttlSeconds,
            'scope' => $scopes,
        ];
    }

    /**
     * Нормализует список скоупов.
     *
     * @param mixed $scopes
     * @return string[]
     */
    private function normalizeScopes(mixed $scopes): array
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
