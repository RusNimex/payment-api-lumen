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
$router->get('liveness', 'Health\HealthCheckController@liveness');
$router->get('readiness', 'Health\HealthCheckController@readiness');

$router->group(['prefix' => 'v1'], function () use ($router) {
    $router->post('token', [
        'uses' => 'Auth\\TokenController@issue',
    ]);
    $router->group(['middleware' => 'token'], function () use ($router) {
        $router->post('pay', 'Payments\PaymentController@pay');
        $router->get('check', 'Payments\PaymentController@check');
    });
});
