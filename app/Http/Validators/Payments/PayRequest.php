<?php

namespace App\Http\Validators\Payments;

use App\Services\Payments\Enums\BankNames;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

/**
 * Валидация создания платежа
 */
class PayRequest
{
    /**
     * Всегда ожидаем название банка через который оплата.
     * Но можем и принять проверку платежа, тогда ожидаем еще и $uuid оплаты.
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
            'bank' => ['required', 'string', Rule::in($allowedBanks)],
            'productId' => ['required', 'string'],
            'amount' => ['required', 'numeric', 'min:0'],
            'price' => ['required', 'numeric', 'min:0'],
            'currency' => ['required', 'string', Rule::in(['RUB', 'USD', 'EUR'])],
            'email' => ['required', 'email'],
            'phone' => ['sometime', 'string', 'min:10'],
            'apiSecret' => ['sometime', 'string', 'min:10'],
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        return $validator->validated();
    }
}
