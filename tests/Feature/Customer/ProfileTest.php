<?php

declare(strict_types=1);

namespace Tests\Feature\Customer;

use App\Livewire\Customer\Profile;
use App\Models\CustomerProfile;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class ProfileTest extends TestCase
{
    use RefreshDatabase;

    public function test_profile_data_is_loaded_when_customer_opens_profile(): void
    {
        // Arrange
        $user = $this->createUser([
            'name' => 'Noor Abed',
        ]);

        $this->createCustomerProfile($user, [
            'phone' => '0599000000',
        ]);

        $this->actingAs($user);

        // Act & Assert
        Livewire::test(Profile::class)
            ->assertSet('name', 'Noor Abed')
            ->assertSet('phone', '0599000000');
    }

    public function test_customer_can_update_profile(): void
    {
        // Arrange
        $user = $this->createUser([
            'name' => 'Old Name',
        ]);

        $this->createCustomerProfile($user, [
            'phone' => '0599000000',
        ]);

        $this->actingAs($user);

        // Act
        Livewire::test(Profile::class)
            ->set('name', 'New Name')
            ->set('phone', '0566000000')
            ->call('updateProfile')
            ->assertHasNoErrors()
            ->assertDispatched('show-success', message: 'Your profile has been updated successfully.');

        // Assert
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => 'New Name',
        ]);

        $this->assertDatabaseHas('customer_profiles', [
            'user_id' => $user->id,
            'phone' => '0566000000',
        ]);
    }

    public function test_name_is_required(): void
    {
        // Arrange
        $user = $this->createUser();

        $this->actingAs($user);

        // Act & Assert
        Livewire::test(Profile::class)
            ->set('name', '')
            ->call('updateProfile')
            ->assertHasErrors([
                'name' => 'required',
            ]);
    }

    public function test_name_cannot_exceed_255_characters(): void
    {
        // Arrange
        $user = $this->createUser();

        $this->actingAs($user);

        // Act & Assert
        Livewire::test(Profile::class)
            ->set('name', str_repeat('a', 256))
            ->call('updateProfile')
            ->assertHasErrors([
                'name' => 'max',
            ]);
    }

    public function test_phone_is_optional(): void
    {
        // Arrange
        $user = $this->createUser();

        $this->createCustomerProfile($user, [
            'phone' => '0599000000',
        ]);

        $this->actingAs($user);

        // Act & Assert
        Livewire::test(Profile::class)
            ->set('name', 'Noor Abed')
            ->set('phone', '')
            ->call('updateProfile')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('customer_profiles', [
            'user_id' => $user->id,
            'phone' => null,
        ]);
    }

    public function test_phone_cannot_exceed_30_characters(): void
    {
        // Arrange
        $user = $this->createUser();

        $this->actingAs($user);

        // Act & Assert
        Livewire::test(Profile::class)
            ->set('name', 'Noor Abed')
            ->set('phone', str_repeat('1', 31))
            ->call('updateProfile')
            ->assertHasErrors([
                'phone' => 'max',
            ]);
    }

    private function createUser(array $attributes = []): User
    {
        return User::factory()->create($attributes);
    }

    private function createCustomerProfile(User $user, array $attributes = []): CustomerProfile
    {
        return CustomerProfile::factory()->create([
            'user_id' => $user->id,
            ...$attributes,
        ]);
    }
}
