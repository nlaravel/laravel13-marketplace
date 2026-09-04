<?php

declare(strict_types=1);

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

    /*
    |--------------------------------------------------------------------------
    | Get Addresses
    |--------------------------------------------------------------------------
    */

    public function test_get_addresses_returns_user_addresses_ordered_by_default_then_latest(): void
    {
        $user = User::factory()->create();

        $oldAddress = Address::factory()->create([
            'user_id' => $user->id,
            'is_default' => false,
        ]);

        $newAddress = Address::factory()->create([
            'user_id' => $user->id,
            'is_default' => false,
        ]);

        $defaultAddress = Address::factory()->create([
            'user_id' => $user->id,
            'is_default' => true,
        ]);

        $addresses = $this->addressService->getAddresses($user);

        $this->assertCount(3, $addresses);

        $this->assertSame($defaultAddress->id, $addresses->first()->id);

        $this->assertSame($newAddress->id, $addresses->get(1)->id);

        $this->assertSame($oldAddress->id, $addresses->get(2)->id);
    }

    /*
    |--------------------------------------------------------------------------
    | Find Address
    |--------------------------------------------------------------------------
    */

    public function test_find_address_returns_own_address(): void
    {
        $user = User::factory()->create();

        $address = Address::factory()->create([
            'user_id' => $user->id,
        ]);

        $result = $this->addressService->findAddress($user, $address->id);

        $this->assertSame($address->id, $result->id);
    }

    public function test_find_address_cannot_find_another_users_address(): void
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();

        $address = Address::factory()->create([
            'user_id' => $otherUser->id,
        ]);

        $this->expectException(ModelNotFoundException::class);

        $this->addressService->findAddress($user, $address->id);
    }

    /*
    |--------------------------------------------------------------------------
    | Create Address
    |--------------------------------------------------------------------------
    */

    public function test_create_address_as_non_default(): void
    {
        $user = User::factory()->create();

        $address = $this->addressService->createAddress($user, [
            'recipient_name' => 'Noor Abed',
            'phone' => '0590000000',
            'label' => 'Home',
            'address_line' => 'Main Street',
            'city' => 'Hebron',
            'state' => 'West Bank',
            'postal_code' => '00000',
            'country' => 'Palestine',
            'latitude' => null,
            'longitude' => null,
            'is_default' => false,
        ]);

        $this->assertDatabaseHas('addresses', [
            'id' => $address->id,
            'user_id' => $user->id,
            'recipient_name' => 'Noor Abed',
            'phone' => '0590000000',
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

        $newDefault = $this->addressService->createAddress($user, [
            'recipient_name' => 'Noor Abed',
            'phone' => '0590000000',
            'label' => 'Work',
            'address_line' => 'Work Street',
            'city' => 'Hebron',
            'state' => 'West Bank',
            'postal_code' => '00000',
            'country' => 'Palestine',
            'latitude' => null,
            'longitude' => null,
            'is_default' => true,
        ]);

        $this->assertDatabaseHas('addresses', [
            'id' => $newDefault->id,
            'recipient_name' => 'Noor Abed',
            'phone' => '0590000000',
            'is_default' => true,
        ]);

        $this->assertDatabaseHas('addresses', [
            'id' => $oldDefault->id,
            'is_default' => false,
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Update Address
    |--------------------------------------------------------------------------
    */

    public function test_update_address_updates_data(): void
    {
        $user = User::factory()->create();

        $address = Address::factory()->create([
            'user_id' => $user->id,
            'is_default' => false,
        ]);

        $updatedAddress = $this->addressService->updateAddress($user, $address, [
            'label' => 'Updated Home',
            'city' => 'Ramallah',
        ]);

        $this->assertSame('Updated Home', $updatedAddress->label);

        $this->assertSame('Ramallah', $updatedAddress->city);

        $this->assertDatabaseHas('addresses', [
            'id' => $address->id,
            'label' => 'Updated Home',
            'city' => 'Ramallah',
        ]);
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

        $this->addressService->updateAddress($user, $address, [
            'is_default' => true,
        ]);

        $this->assertDatabaseHas('addresses', [
            'id' => $address->id,
            'is_default' => true,
        ]);

        $this->assertDatabaseHas('addresses', [
            'id' => $oldDefault->id,
            'is_default' => false,
        ]);
    }

    public function test_update_address_preserves_default_when_is_default_is_omitted(): void
    {
        $user = User::factory()->create();

        $address = Address::factory()->create([
            'user_id' => $user->id,
            'is_default' => true,
        ]);

        $this->addressService->updateAddress($user, $address, [
            'label' => 'Updated Home',
        ]);

        $this->assertDatabaseHas('addresses', [
            'id' => $address->id,
            'label' => 'Updated Home',
            'is_default' => true,
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Delete Address
    |--------------------------------------------------------------------------
    */

    public function test_delete_address(): void
    {
        $user = User::factory()->create();

        $address = Address::factory()->create([
            'user_id' => $user->id,
            'is_default' => false,
        ]);

        $this->addressService->deleteAddress($user, $address);

        $this->assertDatabaseMissing('addresses', [
            'id' => $address->id,
        ]);
    }

    public function test_delete_default_address_promotes_latest_remaining_address(): void
    {
        $user = User::factory()->create();

        $olderAddress = Address::factory()->create([
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

        $this->addressService->deleteAddress($user, $defaultAddress);

        $this->assertDatabaseMissing('addresses', [
            'id' => $defaultAddress->id,
        ]);

        $this->assertDatabaseHas('addresses', [
            'id' => $latestAddress->id,
            'is_default' => true,
        ]);

        $this->assertDatabaseHas('addresses', [
            'id' => $olderAddress->id,
            'is_default' => false,
        ]);
    }

    public function test_delete_last_remaining_address_does_not_error(): void
    {
        $user = User::factory()->create();

        $address = Address::factory()->create([
            'user_id' => $user->id,
            'is_default' => true,
        ]);

        $this->addressService->deleteAddress($user, $address);

        $this->assertDatabaseMissing('addresses', [
            'id' => $address->id,
        ]);

        $this->assertSame(0, $user->addresses()->count());
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

        $this->addressService->deleteAddress($user, $address);

        $this->assertDatabaseMissing('addresses', [
            'id' => $address->id,
        ]);

        $this->assertDatabaseHas('addresses', [
            'id' => $defaultAddress->id,
            'is_default' => true,
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Set Default Address
    |--------------------------------------------------------------------------
    */

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

        $result = $this->addressService->setDefaultAddress($user, $address);

        $this->assertSame($address->id, $result->id);

        $this->assertDatabaseHas('addresses', [
            'id' => $address->id,
            'is_default' => true,
        ]);

        $this->assertDatabaseHas('addresses', [
            'id' => $oldDefault->id,
            'is_default' => false,
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Ownership / Authorization
    |--------------------------------------------------------------------------
    */

    public function test_update_address_cannot_update_another_users_address(): void
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();

        $address = Address::factory()->create([
            'user_id' => $otherUser->id,
            'label' => 'Original',
        ]);

        try {
            $this->addressService->updateAddress($user, $address, [
                'label' => 'Hacked',
            ]);

            $this->fail('Expected NotFoundHttpException was not thrown.');
        } catch (NotFoundHttpException) {
            // Expected exception. Continue to verify database state.
        }

        $this->assertDatabaseHas('addresses', [
            'id' => $address->id,
            'user_id' => $otherUser->id,
            'label' => 'Original',
        ]);
    }

    public function test_delete_address_cannot_delete_another_users_address(): void
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();

        $address = Address::factory()->create([
            'user_id' => $otherUser->id,
            'label' => 'Original',
            'is_default' => false,
        ]);

        try {
            $this->addressService->deleteAddress($user, $address);

            $this->fail('Expected NotFoundHttpException was not thrown.');
        } catch (NotFoundHttpException) {
            // Expected exception. Continue to verify database state.
        }

        $this->assertDatabaseHas('addresses', [
            'id' => $address->id,
            'user_id' => $otherUser->id,
            'label' => 'Original',
            'is_default' => false,
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

        try {
            $this->addressService->setDefaultAddress($user, $address);

            $this->fail('Expected NotFoundHttpException was not thrown.');
        } catch (NotFoundHttpException) {
            // Expected exception. Continue to verify database state.
        }

        $this->assertDatabaseHas('addresses', [
            'id' => $address->id,
            'user_id' => $otherUser->id,
            'is_default' => false,
        ]);
    }
}
