<?php

/** @var \Laravel\Lumen\Routing\Router $router */

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Маршруты для всех запросов с префиксом /api.
|
*/

$router->get('liveness', 'HealthCheckController@liveness');
$router->get('readiness', 'HealthCheckController@readiness');

$router->post('token', [
    'uses' => 'Auth\\TokenController@issue',
]);

$router->group(['prefix' => 'v1', 'middleware' => 'token'], function () use ($router) {
    $router->post('pay', 'PaymentsController@pay');
    $router->get('check', 'PaymentsController@check');
});
