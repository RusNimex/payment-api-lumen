<?php

namespace App\Contracts;

/**
 * Personal Access Token для сервисов.
 * 
 * Генерирует и хранит токен в Redis.
 */
interface TokenServiceInterface
{
    /**
     * Выдает токен для сервиса или возвращает null при ошибке авторизации.
     *
     * @return array<string, mixed>|null
     */
    public function issueToken(string $serviceName, string $serviceSecret): ?array;
}
