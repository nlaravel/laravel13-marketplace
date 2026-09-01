<?php

namespace App\Services\Delivery;

use App\Enums\DeliveryStatus;
use App\Models\Delivery;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;
use App\Services\Orders\SellerOrderStatusService;
class DeliveryService
{

    public function __construct(
    private SellerOrderStatusService $sellerOrderStatusService
) {
}

    public function assign(
        Delivery $delivery,
        int $deliveryProfileId
    ): Delivery {
        return DB::transaction(function () use ($delivery, $deliveryProfileId) {
            $delivery->refresh();

            if ($delivery->status !== DeliveryStatus::PENDING) {
                throw new InvalidArgumentException(
                    'Only pending deliveries can be assigned.'
                );
            }

            $delivery->update([
                'delivery_profile_id' => $deliveryProfileId,
                'status' => DeliveryStatus::ASSIGNED,
                'assigned_at' => now(),
            ]);

            return $delivery->refresh();
        });
    }

    public function pickUp(Delivery $delivery): Delivery
    {
        return $this->transition(
            $delivery,
            DeliveryStatus::ASSIGNED,
            DeliveryStatus::PICKED_UP,
            [
                'picked_up_at' => now(),
            ]
        );
    }

    public function startTransit(Delivery $delivery): Delivery
    {
        return $this->transition(
            $delivery,
            DeliveryStatus::PICKED_UP,
            DeliveryStatus::IN_TRANSIT
        );
    }

    public function deliver(Delivery $delivery): Delivery
{
    return DB::transaction(function () use ($delivery) {
        $delivery->refresh();

        if ($delivery->status !== DeliveryStatus::IN_TRANSIT) {
            throw new InvalidArgumentException(
                "Only in-transit deliveries can be delivered."
            );
        }

        $delivery->update([
            'status' => DeliveryStatus::DELIVERED,
            'delivered_at' => now(),
        ]);

        $delivery->refresh();

        $sellerOrder = $delivery->sellerOrder()->firstOrFail();

        $this->sellerOrderStatusService->markDelivered($sellerOrder);

        return $delivery;
    });
}

    public function fail(Delivery $delivery, ?string $notes = null): Delivery
    {
        return DB::transaction(function () use ($delivery, $notes) {
            $delivery->refresh();

            if (in_array($delivery->status, [
                DeliveryStatus::DELIVERED,
                DeliveryStatus::CANCELLED,
            ], true)) {
                throw new InvalidArgumentException(
                    'Delivered or cancelled deliveries cannot be failed.'
                );
            }

            $delivery->update([
                'status' => DeliveryStatus::FAILED,
                'failed_at' => now(),
                'notes' => $notes,
            ]);

            return $delivery->refresh();
        });
    }

    public function cancel(Delivery $delivery, ?string $notes = null): Delivery
    {
        return DB::transaction(function () use ($delivery, $notes) {
            $delivery->refresh();

            if (in_array($delivery->status, [
                DeliveryStatus::DELIVERED,
                DeliveryStatus::CANCELLED,
            ], true)) {
                throw new InvalidArgumentException(
                    'Delivered or already cancelled deliveries cannot be cancelled.'
                );
            }

            $delivery->update([
                'status' => DeliveryStatus::CANCELLED,
                'notes' => $notes,
            ]);

            return $delivery->refresh();
        });
    }

    private function transition(
        Delivery $delivery,
        DeliveryStatus $from,
        DeliveryStatus $to,
        array $attributes = []
    ): Delivery {
        return DB::transaction(function () use (
            $delivery,
            $from,
            $to,
            $attributes
        ) {
            $delivery->refresh();

            if ($delivery->status !== $from) {
                throw new InvalidArgumentException(
                    "Invalid delivery transition from {$delivery->status->value} to {$to->value}."
                );
            }

            $delivery->update([
                'status' => $to,
                ...$attributes,
            ]);

            return $delivery->refresh();
        });
    }
}