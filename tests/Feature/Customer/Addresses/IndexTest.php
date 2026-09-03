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

    public function test_customer_can_view_their_addresses(): void
    {
        $user = User::factory()->create();

        $address = Address::factory()->create([
            'user_id' => $user->id,
        ]);

        Livewire::actingAs($user)
            ->test(Index::class)
            ->assertSee($address->recipient_name)
            ->assertSee($address->city);
    }

    public function test_customer_cannot_see_another_users_address(): void
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();

        $otherAddress = Address::factory()->create([
            'user_id' => $otherUser->id,
            'recipient_name' => 'Other User Unique Name',
        ]);

        Livewire::actingAs($user)
            ->test(Index::class)
            ->assertDontSee($otherAddress->recipient_name);
    }

    public function test_customer_can_delete_their_address(): void
    {
        $user = User::factory()->create();

        $address = Address::factory()->create([
            'user_id' => $user->id,
        ]);

        Livewire::actingAs($user)
            ->test(Index::class)
            ->call('delete', $address->id);

        $this->assertDatabaseMissing('addresses', [
            'id' => $address->id,
        ]);
    }

    public function test_customer_can_set_address_as_default(): void
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
            ->call('setDefault', $address->id);

        $this->assertFalse(
            $oldDefault->fresh()->is_default
        );

        $this->assertTrue(
            $address->fresh()->is_default
        );
    }

    public function test_customer_cannot_delete_another_users_address(): void
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();

        $address = Address::factory()->create([
            'user_id' => $otherUser->id,
        ]);

        try {
            Livewire::actingAs($user)
                ->test(Index::class)
                ->call('delete', $address->id);

            $this->fail('Expected ModelNotFoundException was not thrown.');
        } catch (ModelNotFoundException) {
            $this->assertDatabaseHas('addresses', [
                'id' => $address->id,
            ]);
        }
    }

    public function test_customer_cannot_set_another_users_address_as_default(): void
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();

        $address = Address::factory()->create([
            'user_id' => $otherUser->id,
            'is_default' => false,
        ]);

        try {
            Livewire::actingAs($user)
                ->test(Index::class)
                ->call('setDefault', $address->id);

            $this->fail('Expected ModelNotFoundException was not thrown.');
        } catch (ModelNotFoundException) {
            $this->assertFalse(
                $address->fresh()->is_default
            );
        }
    }
}
