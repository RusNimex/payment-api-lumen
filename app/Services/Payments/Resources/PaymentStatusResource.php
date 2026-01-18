<?php

namespace App\Services\Payments\Resources;

use App\Services\Payments\ValueObjects\PaymentStatusResult;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Ресурс статуса платежа и сообщения ошибки (если есть)
 *
 * @property-read PaymentStatusResult $resource
 */
final class PaymentStatusResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'result' => $this->resource->isSuccess() ? 'success' : 'error',
            'message' => $this->resource->message(),
        ];
    }
}
