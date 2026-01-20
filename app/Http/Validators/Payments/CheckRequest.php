<?php

namespace App\Http\Validators\Payments;

use App\Services\Payments\Enums\BankNames;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

/**
 * Валидация статуса платежа.
 */
class CheckRequest
{
    /**
     * Всегда ожидаем название банка через который оплатчивается.
     * payId - платеж, который проверяем.
     *
     * @param Request $request
     * @return array<string, mixed>
     * @throws ValidationException
     */
    public function validate(Request $request): array
    {
        $allowedBanks = array_map(
            static fn (BankNames $bank) => $bank->value,
            BankNames::cases()
        );

        $validator = Validator::make($request->all(), [
            'payId' => ['required', 'string', 'min:16'],
            'bank' => ['required', 'string', Rule::in($allowedBanks)],
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        return $validator->validated();
    }
}
