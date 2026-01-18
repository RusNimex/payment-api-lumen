<?php

namespace App\Http\Controllers\Payments;

use App\Exceptions\Payments\PaymentsException;
use App\Http\Controllers\Controller;
use App\Http\Validators\Payments\PayCreateRequest;
use App\Http\Validators\Payments\PayStatusRequest;
use App\Services\Payments\OrderService;
use App\Services\Payments\PayProviderService;
use App\Services\Payments\Resources\PaymentResource;
use App\Services\Payments\Resources\PaymentStatusResource;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    /**
     * Реализовать АПИ метода для проведения платежа через 2 разных провайдера.
     *
     * Вводная:
     * - Пользователи пользуются мобильным приложением (интернет магазин)
     * - На странице товара, у них есть кнопка купить по нажатию на которую они видят 2 кнопки:
     *      * Купить через Сбербанк.
     *      * Купить через AlphaBank.
     * - Так же в дизайн предусмотрел пару итоговых страниц:
     *      * Успешная оплата
     *      * Ошибка платежа
     *      * Неизвестная ошибка, обратитесь в службу поддержки
     *
     * Что нужно сделать:
     * - Декомпозировать АПИ методы для реализации данного сценария
     * - Реализовать интеграцию с сервисами Сбербанк и AlphaBank
     *
     * Апи платежных провайдеров представлены в виде заглушек.
     * К заглушкам стоит относиться как к стороннему HTTP API, а так же как к документации.
     * @see AlphaPaymentApiMock
     * @see SberPaymentApiMock
     */
    public function __construct(
        private readonly OrderService       $order,
        private readonly PayProviderService $pay,
        private readonly PayCreateRequest   $payCreateRequest,
        private readonly PayStatusRequest   $payStatusRequest
    ){}

    /**
     * Создание платежа
     *
     * @param Request $request
     * @return JsonResponse
     * @throws BindingResolutionException|PaymentsException
     */
    public function pay(Request $request): JsonResponse
    {
        $this->payCreateRequest->validate($request);

        $order = $this->order->create(
            $request->string('productId')->toString(),
            $request->integer('amount')
        );

        $pay = $this->pay->create(
            $request->string('bank')->toString(),
            $order
        );

        return (new PaymentResource($pay))->response();
    }

    /**
     * Проверим статус платежа
     *
     * @param Request $request
     * @return JsonResponse
     * @throws BindingResolutionException
     */
    public function check(Request $request): JsonResponse
    {
        $this->payStatusRequest->validate($request);

        $pay = $this->pay->check(
            $request->string('payId')->toString(),
            $request->string('bank')->toString()
        );

        return (new PaymentStatusResource($pay))->response();
    }

}
