<?php

declare(strict_types=1);

namespace App\Policies;

use App\Enums\StoreStatus;
use App\Models\Product;
use App\Models\User;

class ProductPolicy
{
    public function view(User $user, Product $product): bool
    {
        return $this->ownsProduct($user, $product);
    }

    public function create(User $user): bool
    {
        $sellerProfile = $user->sellerProfile;

        if ($sellerProfile === null) {
            return false;
        }

        return $sellerProfile->stores()
            ->where('status', StoreStatus::APPROVED)
            ->exists();
    }

    public function update(User $user, Product $product): bool
    {
        return $this->ownsProduct($user, $product);
    }

    public function delete(User $user, Product $product): bool
    {
        return $this->ownsProduct($user, $product);
    }

    private function ownsProduct(User $user, Product $product): bool
    {
        return $product->store?->seller?->user_id === $user->id;
    }
}
