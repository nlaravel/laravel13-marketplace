<?php

namespace App\Http\Controllers\Api\V1\Customer;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Customer\UpdateProfileRequest;
use App\Http\Resources\Api\Customer\CustomerProfileResource;
use App\Services\Customer\CustomerProfileService;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function __construct(private readonly CustomerProfileService $profileService) {}

public function show(Request $request): CustomerProfileResource
{
    $profile = $this->profileService->getProfile(
        $request->user()
    );

    return new CustomerProfileResource(
        $profile->load('user')
    );
}

public function update(UpdateProfileRequest $request): CustomerProfileResource
{
    $profile = $this->profileService->updateProfile(
        $request->user(),
        $request->validated()
    );

    return new CustomerProfileResource(
        $profile->load('user')
    );
}
}