<?php

namespace Tests\Feature\Customer\Addresses;

use App\Livewire\Customer\Addresses\Index;
use App\Models\Address;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class IndexTest extends TestCase
{
    use RefreshDatabase;

    public function test_customer_can_view_own_addresses(): void
    {
        $user = User::factory()->create();

        Address::factory()->count(3)->create([
            'user_id' => $user->id,
        ]);

        Livewire::actingAs($user)
            ->test(Index::class)
            ->assertOk();
    }

    public function test_customer_cannot_see_another_users_addresses(): void
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();

        $ownAddress = Address::factory()->create([
            'user_id' => $user->id,
        ]);

        $otherAddress = Address::factory()->create([
            'user_id' => $otherUser->id,
        ]);

        $component = Livewire::actingAs($user)
            ->test(Index::class);

        $addresses = $component->get('addresses');

        $this->assertCount(1, $addresses);

        $this->assertSame(
            $ownAddress->id,
            $addresses->first()->id
        );

        $this->assertFalse(
            $addresses->contains(
                fn (Address $address): bool => $address->id === $otherAddress->id
            )
        );
    }

    public function test_customer_can_delete_own_address(): void
    {
        $user = User::factory()->create();

        $address = Address::factory()->create([
            'user_id' => $user->id,
            'is_default' => false,
        ]);

        Livewire::actingAs($user)
            ->test(Index::class)
            ->call('delete', $address->id)
            ->assertOk();

        $this->assertDatabaseMissing('addresses', [
            'id' => $address->id,
        ]);
    }

    public function test_customer_can_set_own_address_as_default(): void
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

        Livewire::actingAs($user)
            ->test(Index::class)
            ->call('setDefault', $address->id)
            ->assertOk();

        $this->assertDatabaseHas('addresses', [
            'id' => $address->id,
            'is_default' => true,
        ]);

        $this->assertDatabaseHas('addresses', [
            'id' => $oldDefault->id,
            'is_default' => false,
        ]);
    }

    public function test_customer_cannot_delete_another_users_address(): void
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();

        $address = Address::factory()->create([
            'user_id' => $otherUser->id,
            'is_default' => false,
        ]);

        // Defense in depth:
        // the service also validates ownership independently.
        try {
            Livewire::actingAs($user)
                ->test(Index::class)
                ->call('delete', $address->id);

            $this->fail(
                'Expected ModelNotFoundException was not thrown.'
            );
        } catch (ModelNotFoundException) {
            // Expected exception. Continue to verify database state.
        }

        $this->assertDatabaseHas('addresses', [
            'id' => $address->id,
            'user_id' => $otherUser->id,
        ]);
    }

    public function test_customer_cannot_set_another_users_address_as_default(): void
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();

        $address = Address::factory()->create([
            'user_id' => $otherUser->id,
            'is_default' => false,
        ]);

        // Defense in depth:
        // the service also validates ownership independently.
        try {
            Livewire::actingAs($user)
                ->test(Index::class)
                ->call('setDefault', $address->id);

            $this->fail(
                'Expected ModelNotFoundException was not thrown.'
            );
        } catch (ModelNotFoundException) {
            // Expected exception. Continue to verify database state.
        }

        $this->assertDatabaseHas('addresses', [
            'id' => $address->id,
            'user_id' => $otherUser->id,
            'is_default' => false,
        ]);
    }
}
