<?php

declare(strict_types=1);

namespace Tests\Unit\Services\Customer;

use App\Models\CustomerProfile;
use App\Models\User;
use App\Services\Customer\CustomerProfileService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CustomerProfileServiceTest extends TestCase
{
    use RefreshDatabase;

    private CustomerProfileService $profileService;

    protected function setUp(): void
    {
        parent::setUp();

        $this->profileService = app(CustomerProfileService::class);
    }

    public function test_get_profile_creates_profile_when_customer_has_no_profile(): void
    {
        // Arrange
        $user = User::factory()->create();

        // Act
        $profile = $this->profileService->getProfile($user);

        // Assert
        $this->assertInstanceOf(CustomerProfile::class, $profile);

        $this->assertSame($user->id, $profile->user_id);

        $this->assertDatabaseHas('customer_profiles', [
            'user_id' => $user->id,
        ]);
    }

    public function test_get_profile_returns_existing_profile(): void
    {
        // Arrange
        $user = User::factory()->create();

        $existingProfile = CustomerProfile::factory()->create([
            'user_id' => $user->id,
            'phone' => '0599000000',
        ]);

        // Act
        $profile = $this->profileService->getProfile($user);

        // Assert
        $this->assertSame($existingProfile->id, $profile->id);

        $this->assertSame('0599000000', $profile->phone);
    }

    public function test_update_profile_updates_user_name_and_phone(): void
    {
        // Arrange
        $user = User::factory()->create([
            'name' => 'Old Name',
        ]);

        CustomerProfile::factory()->create([
            'user_id' => $user->id,
            'phone' => '0599000000',
        ]);

        // Act
        $profile = $this->profileService->updateProfile($user, [
            'name' => 'New Name',
            'phone' => '0566000000',
        ]);

        // Assert
        $this->assertSame('0566000000', $profile->phone);

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => 'New Name',
        ]);

        $this->assertDatabaseHas('customer_profiles', [
            'user_id' => $user->id,
            'phone' => '0566000000',
        ]);
    }

    public function test_update_profile_converts_empty_phone_to_null(): void
    {
        // Arrange
        $user = User::factory()->create();

        CustomerProfile::factory()->create([
            'user_id' => $user->id,
            'phone' => '0599000000',
        ]);

        // Act
        $profile = $this->profileService->updateProfile($user, [
            'name' => 'Noor Abed',
            'phone' => '',
        ]);

        // Assert
        $this->assertNull($profile->phone);

        $this->assertDatabaseHas('customer_profiles', [
            'user_id' => $user->id,
            'phone' => null,
        ]);
    }

    public function test_update_profile_creates_profile_if_it_does_not_exist(): void
    {
        // Arrange
        $user = User::factory()->create();

        // Act
        $profile = $this->profileService->updateProfile($user, [
            'name' => 'Noor Abed',
            'phone' => '0599000000',
        ]);

        // Assert
        $this->assertDatabaseHas('customer_profiles', [
            'user_id' => $user->id,
            'phone' => '0599000000',
        ]);

        $this->assertSame('0599000000', $profile->phone);
    }
}
