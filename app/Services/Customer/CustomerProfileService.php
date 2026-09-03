<?php

namespace App\Services\Customer;

use App\Models\CustomerProfile;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class CustomerProfileService
{
    public function getProfile(User $user): CustomerProfile
    {
        return $user->customerProfile()->firstOrCreate([
            'user_id' => $user->id,
        ]);
    }

    public function updateProfile(User $user, array $data): CustomerProfile
    {

        return DB::transaction(function () use ($user, $data) {

            if (isset($data['name'])) {
                $user->update([
                    'name' => $data['name'],
                ]);
            }

            $profile = $this->getProfile($user);

            $profileData = collect($data)
                ->except('name')
                ->map(function ($value) {
                    return $value === '' ? null : $value;
                })
                ->toArray();

            $profile->update($profileData);

            return $profile->fresh();
        });
    }
}
