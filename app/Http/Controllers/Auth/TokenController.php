<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Contracts\TokenServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * Работа с токенами PAT
 */
class TokenController extends Controller
{
    public function __construct(
        private readonly TokenServiceInterface $tokenService
    ) {}

    /**
     * Ручная выдача токена с фиксированным ключом.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function issue(Request $request): JsonResponse
    {
        $serviceName = (string) $request->header('X-Service-Name');
        $serviceSecret = (string) $request->header('X-Service-Secret');

        $tokenPayload = $this->tokenService->issueToken($serviceName, $serviceSecret);
        if ($tokenPayload === null) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        return response()->json($tokenPayload);
    }
}
