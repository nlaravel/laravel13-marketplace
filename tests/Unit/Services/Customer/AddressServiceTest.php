<?php

namespace Tests\Unit\Services\Customer;

use App\Models\Address;
use App\Models\User;
use App\Services\Customer\AddressService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Tests\TestCase;

class AddressServiceTest extends TestCase
{
    use RefreshDatabase;

    private AddressService $addressService;

    protected function setUp(): void
    {
        parent::setUp();

        $this->addressService = app(AddressService::class);
    }

    public function test_get_addresses_returns_user_addresses_ordered_by_default_then_latest(): void
    {
        $user = User::factory()->create();

        $oldAddress = Address::factory()->create([
            'user_id' => $user->id,
            'is_default' => false,
        ]);

        $latestAddress = Address::factory()->create([
            'user_id' => $user->id,
            'is_default' => false,
        ]);

        $defaultAddress = Address::factory()->create([
            'user_id' => $user->id,
            'is_default' => true,
        ]);

        $addresses = $this->addressService->getAddresses($user);

        $this->assertSame(
            [
                $defaultAddress->id,
                $latestAddress->id,
                $oldAddress->id,
            ],
            $addresses->pluck('id')->all()
        );
    }

    public function test_find_address_returns_address_belonging_to_user(): void
    {
        $user = User::factory()->create();

        $address = Address::factory()->create([
            'user_id' => $user->id,
        ]);

        $result = $this->addressService->findAddress(
            $user,
            $address->id
        );

        $this->assertSame($address->id, $result->id);
    }

    public function test_find_address_cannot_access_another_users_address(): void
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();

        $address = Address::factory()->create([
            'user_id' => $otherUser->id,
        ]);

        $this->expectException(ModelNotFoundException::class);

        $this->addressService->findAddress(
            $user,
            $address->id
        );
    }

    public function test_create_address_creates_non_default_address(): void
    {
        $user = User::factory()->create();

        $address = $this->addressService->createAddress(
            $user,
            [
                'label' => 'Home',
                'recipient_name' => 'Noor Abed',
                'phone' => '0599000000',
                'country' => 'Palestine',
                'city' => 'Hebron',
                'area' => 'Center',
                'street' => 'Main Street',
                'building' => '10',
                'apartment' => '3',
                'address_line' => 'Main Street, Building 10',
                'latitude' => 31.5326,
                'longitude' => 35.0998,
                'is_default' => false,
            ]
        );

        $this->assertDatabaseHas('addresses', [
            'id' => $address->id,
            'user_id' => $user->id,
            'label' => 'Home',
            'is_default' => false,
        ]);
    }

    public function test_create_default_address_clears_previous_default(): void
    {
        $user = User::factory()->create();

        $oldDefault = Address::factory()->create([
            'user_id' => $user->id,
            'is_default' => true,
        ]);

        $newDefault = $this->addressService->createAddress(
            $user,
            [
                'label' => 'Work',
                'recipient_name' => 'Noor Abed',
                'phone' => '0599000000',
                'country' => 'Palestine',
                'city' => 'Hebron',
                'area' => 'Center',
                'street' => 'Main Street',
                'building' => '10',
                'apartment' => '3',
                'address_line' => 'Main Street, Building 10',
                'latitude' => 31.5326,
                'longitude' => 35.0998,
                'is_default' => true,
            ]
        );

        $this->assertFalse(
            $oldDefault->fresh()->is_default
        );

        $this->assertTrue(
            $newDefault->fresh()->is_default
        );
    }

    public function test_update_address_updates_data(): void
    {
        $user = User::factory()->create();

        $address = Address::factory()->create([
            'user_id' => $user->id,
            'label' => 'Home',
            'city' => 'Hebron',
            'is_default' => false,
        ]);

        $updated = $this->addressService->updateAddress(
            $user,
            $address,
            [
                'label' => 'Office',
                'city' => 'Ramallah',
            ]
        );

        $this->assertSame('Office', $updated->label);
        $this->assertSame('Ramallah', $updated->city);
        $this->assertFalse($updated->is_default);
    }

    public function test_update_address_as_default_clears_previous_default(): void
    {
        $user = User::factory()->create();

        $oldDefault = Address::factory()->create([
            'user_id' => $user->id,
            'is_default' => true,
        ]);

        $address = Address::factory()->create([
            'user_id' => $user->id,
            'is_default' => false,
        ]);

        $updated = $this->addressService->updateAddress(
            $user,
            $address,
            [
                'label' => 'New Default',
                'is_default' => true,
            ]
        );

        $this->assertFalse(
            $oldDefault->fresh()->is_default
        );

        $this->assertTrue(
            $updated->fresh()->is_default
        );
    }

    public function test_update_address_preserves_default_when_is_default_is_omitted(): void
    {
        $user = User::factory()->create();

        $address = Address::factory()->create([
            'user_id' => $user->id,
            'is_default' => true,
        ]);

        $updated = $this->addressService->updateAddress(
            $user,
            $address,
            [
                'label' => 'Updated Home',
            ]
        );

        $this->assertTrue($updated->is_default);
        $this->assertSame('Updated Home', $updated->label);
    }

    public function test_delete_address_removes_address(): void
    {
        $user = User::factory()->create();

        $address = Address::factory()->create([
            'user_id' => $user->id,
        ]);

        $this->addressService->deleteAddress(
            $user,
            $address
        );

        $this->assertDatabaseMissing('addresses', [
            'id' => $address->id,
        ]);
    }

    public function test_delete_default_address_promotes_latest_remaining_address(): void
    {
        $user = User::factory()->create();

        Address::factory()->create([
            'user_id' => $user->id,
            'is_default' => false,
        ]);

        $latestAddress = Address::factory()->create([
            'user_id' => $user->id,
            'is_default' => false,
        ]);

        $defaultAddress = Address::factory()->create([
            'user_id' => $user->id,
            'is_default' => true,
        ]);

        $this->addressService->deleteAddress(
            $user,
            $defaultAddress
        );

        $this->assertTrue(
            $latestAddress->fresh()->is_default
        );
    }

    public function test_delete_non_default_address_keeps_existing_default(): void
    {
        $user = User::factory()->create();

        $defaultAddress = Address::factory()->create([
            'user_id' => $user->id,
            'is_default' => true,
        ]);

        $address = Address::factory()->create([
            'user_id' => $user->id,
            'is_default' => false,
        ]);

        $this->addressService->deleteAddress(
            $user,
            $address
        );

        $this->assertTrue(
            $defaultAddress->fresh()->is_default
        );
    }

    public function test_set_default_address_clears_previous_default(): void
    {
        $user = User::factory()->create();

        $oldDefault = Address::factory()->create([
            'user_id' => $user->id,
            'is_default' => true,
        ]);

        $address = Address::factory()->create([
            'user_id' => $user->id,
            'is_default' => false,
        ]);

        $updated = $this->addressService->setDefaultAddress(
            $user,
            $address
        );

        $this->assertFalse(
            $oldDefault->fresh()->is_default
        );

        $this->assertTrue(
            $updated->fresh()->is_default
        );
    }

    public function test_update_address_cannot_update_another_users_address(): void
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();

        $address = Address::factory()->create([
            'user_id' => $otherUser->id,
            'label' => 'Original',
        ]);

        $this->expectException(NotFoundHttpException::class);

        $this->addressService->updateAddress(
            $user,
            $address,
            [
                'label' => 'Hacked',
            ]
        );

        $this->assertDatabaseHas('addresses', [
            'id' => $address->id,
            'label' => 'Original',
        ]);
    }

    public function test_delete_address_cannot_delete_another_users_address(): void
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();

        $address = Address::factory()->create([
            'user_id' => $otherUser->id,
        ]);

        $this->expectException(NotFoundHttpException::class);

        $this->addressService->deleteAddress(
            $user,
            $address
        );

        $this->assertDatabaseHas('addresses', [
            'id' => $address->id,
        ]);
    }

    public function test_set_default_address_cannot_change_another_users_address(): void
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();

        $address = Address::factory()->create([
            'user_id' => $otherUser->id,
            'is_default' => false,
        ]);

        $this->expectException(NotFoundHttpException::class);

        $this->addressService->setDefaultAddress(
            $user,
            $address
        );

        $this->assertFalse(
            $address->fresh()->is_default
        );
    }
}
