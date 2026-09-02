<?php

namespace App\Services\Customer;

use App\Models\Address;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class AddressService
{
    public function getAddresses(User $user): Collection
    {
        return $user->addresses()
            ->orderByDesc('is_default')
            ->orderByDesc('id')
            ->get();
    }

    public function findAddress(
        User $user,
        int $addressId
    ): Address {
        return $user->addresses()
            ->findOrFail($addressId);
    }

    public function createAddress(
        User $user,
        array $data
    ): Address {
        return DB::transaction(function () use ($user, $data) {

            $isDefault = (bool) ($data['is_default'] ?? false);

            if ($isDefault) {
                $this->clearDefaultAddress($user);
            }

            return $user->addresses()->create([
                ...$data,
                'is_default' => $isDefault,
            ]);
        });
    }

    public function updateAddress(
        User $user,
        Address $address,
        array $data
    ): Address {
        return DB::transaction(function () use (
            $user,
            $address,
            $data
        ) {

            $isDefault = array_key_exists(
                'is_default',
                $data
            )
                ? (bool) $data['is_default']
                : $address->is_default;

            if ($isDefault) {
                $this->clearDefaultAddress(
                    $user,
                    $address->id
                );
            }

            $address->update([
                ...$data,
                'is_default' => $isDefault,
            ]);

            return $address->fresh();
        });
    }

    public function deleteAddress(
        User $user,
        Address $address
    ): void {
        DB::transaction(function () use ($user, $address) {

            $wasDefault = $address->is_default;

            $address->delete();

            if ($wasDefault) {
                $this->makeLatestAddressDefault($user);
            }
        });
    }

    public function setDefaultAddress(
        User $user,
        Address $address
    ): Address {
        return DB::transaction(function () use (
            $user,
            $address
        ) {

            $this->clearDefaultAddress(
                $user,
                $address->id
            );

            $address->update([
                'is_default' => true,
            ]);

            return $address->fresh();
        });
    }

    private function clearDefaultAddress(
        User $user,
        ?int $exceptAddressId = null
    ): void {
        $query = $user->addresses()
            ->where('is_default', true);

        if ($exceptAddressId !== null) {
            $query->where('id', '!=', $exceptAddressId);
        }

        $query->update([
            'is_default' => false,
        ]);
    }

    private function makeLatestAddressDefault(
        User $user
    ): void {
        $address = $user->addresses()
            ->latest('id')
            ->first();

        if ($address) {
            $address->update([
                'is_default' => true,
            ]);
        }
    }
}
