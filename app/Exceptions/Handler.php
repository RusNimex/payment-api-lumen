<?php

namespace App\Exceptions;

use App\Exceptions\Payments\PaymentsException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;
use Laravel\Lumen\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * Общий текст для ответа на не обработанные ошибки.
     */
    private const UNKNOWN_ERROR_MSG = 'Неизвестная ошибка, обратитесь в службу поддержки';

    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
        AuthorizationException::class,
        HttpException::class,
        ModelNotFoundException::class,
        ValidationException::class,
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Throwable  $exception
     * @return void
     *
     * @throws \Exception
     */
    public function report(Throwable $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Throwable  $exception
     * @return \Illuminate\Http\Response|\Illuminate\Http\JsonResponse
     *
     * @throws \Throwable
     */
    public function render($request, Throwable $exception): JsonResponse|Response
    {
        if ($this->useDefaultRender($request)) {
            return parent::render($request, $exception);
        }

        if ($exception instanceof PaymentsException) {
            return response()->json([
                'message' => $exception->getMessage(),
            ], 400);
        }

        if ($exception instanceof BindingResolutionException) {
            return response()->json([
                'message' => self::UNKNOWN_ERROR_MSG
            ], 500);
        }

        report($exception);

        return response()->json([
            'message' => self::UNKNOWN_ERROR_MSG
        ], 500);
    }

    /**
     * Показывать stack trace в разработке и чистый вывод ошибок на проде
     *
     * @param $request
     * @return bool
     */
    private function useDefaultRender($request): bool
    {
        return config('app.debug') || ! $request->expectsJson();
    }
}
