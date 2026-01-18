<?php

namespace App\Services\Payments\Resources;

use App\Services\Payments\ValueObjects\PaymentResult;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Ресурс платежа
 *
 * @property-read PaymentResult $resource
 */
final class PaymentResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'result' => $this->resource->isSuccess() ? 'success' : 'error',
            'order' => $this->resource->order(),
            'payId' => $this->resource->payId(),
            'message' => $this->resource->message(),
        ];
    }
}
