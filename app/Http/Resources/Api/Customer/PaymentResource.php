<?php

declare(strict_types=1);

namespace App\Http\Resources\Api\Customer;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PaymentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'payment_number' => $this->payment_number,
            'provider' => $this->provider,
            'method' => $this->method->value,
            'status' => $this->status->value,
            'amount' => $this->amount,
            'currency' => $this->currency,
            'transaction_id' => $this->transaction_id,
            'provider_payment_id' => $this->provider_payment_id,
            'provider_reference' => $this->provider_reference,
            'paid_at' => $this->paid_at?->toISOString(),
            'failed_at' => $this->failed_at?->toISOString(),
            'refunded_at' => $this->refunded_at?->toISOString(),
            'failure_reason' => $this->failure_reason,
            'created_at' => $this->created_at?->toISOString(),
        ];
    }
}
