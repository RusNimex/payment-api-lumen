<?php

namespace App\Http\Controllers\Payments;

use App\Exceptions\Payments\PaymentsException;
use App\Http\Controllers\Controller;
use App\Http\Validators\Payments\PayRequest;
use App\Http\Validators\Payments\CheckRequest;
use App\Services\Payments\Enums\PayStatus;
use App\Services\Payments\OrderService;
use App\Services\Payments\PaymentService;
use App\Services\Payments\Resources\PaymentResource;
use App\Services\Payments\Resources\PaymentStatusResource;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

/**
 * Оплата заказов через платежные системы.
 *
 * Схема работы:
 *  1. (API) Создается платеж через провайдера {@see PaymentService}
 *  2. (Bank) В ответ должны получить - платеж создан успешно/ошибка.
 *  3. (Bank) Ожидаем оплаты от юзера (ждем по времени или ждем вебхук от банка)
 *  4. (API) Проверяем статус платежа через провайдера {@see PaymentService}
 *  5. (Bank) В ответ должны получить один из статусов платежа {@see PayStatus}
 *
 * Плтаежные системы представлены как моки {@see AlphaPaymentApiMock} и {@see SberPaymentApiMock}
 */
class PaymentController extends Controller
{
    /**
     * @param OrderService $order - инфа по заказу (товар, кол-во, сумма, юзер, банк)
     * @param PaymentService $paymentService - провайдер платежа
     * @param PayRequest $payRequest - запрос с фронта на создание платежа
     * @param CheckRequest $check - запрос с фронта на проверку статуса платежа
     */
    public function __construct(
        private readonly OrderService   $order,
        private readonly PaymentService $paymentService,
        private readonly PayRequest     $payRequest,
        private readonly CheckRequest   $check
    ){}

    /**
     * Создание платежа.
     *
     * @param Request $request
     * @return JsonResponse
     * @throws BindingResolutionException|PaymentsException|ValidationException
     */
    public function pay(Request $request): JsonResponse
    {
        $this->payRequest->validate($request);

        $payment = $this->paymentService->create(
            $this->order->createFromRequest($this->payRequest)
        );

        return (new PaymentResource($payment))->response();
    }

    /**
     * Проверка статуса платежа.
     *
     * @param Request $request
     * @return JsonResponse
     * @throws BindingResolutionException|ValidationException
     */
    public function check(Request $request): JsonResponse
    {
        $this->check->validate($request);

        $payment = $this->paymentService->check(
            $request->string('payId')->toString(),
            $request->string('bank')->toString()
        );

        return (new PaymentStatusResource($payment))->response();
    }

}
