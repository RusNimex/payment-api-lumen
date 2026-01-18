# payment-api-lumen (PHP)

REST API для оплаты заказов.

## Структура проекта
- `bootstrap/app.php` — регистрация сервисов, middleware, провайдеров.
- `routes/api.php` — все API маршруты.
- `app/Http/Controllers` — контроллеры.
- `app/Http/Middleware/TokenAuth.php` — проверка PAT в запросах.
- `app/Services/TokenService.php` — выпуск и хранение PAT в Redis.
- `config/services.php` — настройки token issuer.
- `config/database.php` — настройки Redis.

## Взаимодействие
Внешний сервис заказов вызывает API по цепочке:
1. `POST /api/token` — получить сервисный токен.
2. `POST /api/v1/pay` — выполнить оплату с токеном.
3. Ответ — результат создания платежа.
4. `GET /api/v1/check` — проверка оплаты.

Пример заголовка:
```
Authorization: Bearer <token>
```

## PAT (Personal Access Token)
 Для авторизации сервисов (с заказами, которые нужно оплатить) реализован Personal Access Token (PAT), который нужно получить перед началом сессии.
- Токен выдается после проверки `X-Service-Name` и `X-Service-Secret`.
- В Redis хранится не токен, а его SHA-256 хэш.
- Есть TTL токена (по умолчанию 86400 секунд).
- Токен передается в `Authorization: Bearer ...`.
