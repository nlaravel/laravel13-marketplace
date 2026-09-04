<?php

declare(strict_types=1);

namespace Tests\Feature\Customer\Addresses;

use App\Livewire\Customer\Addresses\Edit;
use App\Models\Address;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Tests\TestCase;

class EditTest extends TestCase
{
    use RefreshDatabase;

    public function test_customer_can_view_edit_address_page(): void
    {
        $user = User::factory()->create();

        $address = Address::factory()->create([
            'user_id' => $user->id,
            'recipient_name' => 'Danny Toy',
            'city' => 'Hebron',
        ]);

        Livewire::actingAs($user)
            ->test(Edit::class, [
                'address' => $address,
            ])
            ->assertSet('recipient_name', 'Danny Toy')
            ->assertSet('city', 'Hebron');
    }

    public function test_customer_can_update_their_address(): void
    {
        $user = User::factory()->create();

        $address = Address::factory()->create([
            'user_id' => $user->id,
            'recipient_name' => 'Old Name',
            'city' => 'Old City',
        ]);

        Livewire::actingAs($user)
            ->test(Edit::class, [
                'address' => $address,
            ])
            ->set('recipient_name', 'New Name')
            ->set('city', 'New City')
            ->call('save');

        $this->assertDatabaseHas('addresses', [
            'id' => $address->id,
            'recipient_name' => 'New Name',
            'city' => 'New City',
        ]);
    }

    public function test_customer_can_make_address_default(): void
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
            ->test(Edit::class, [
                'address' => $address,
            ])
            ->set('is_default', true)
            ->call('save');

        $this->assertFalse($oldDefault->fresh()->is_default);
        $this->assertTrue($address->fresh()->is_default);
    }

    public function test_customer_cannot_update_address_with_invalid_data(): void
    {
        $user = User::factory()->create();

        $address = Address::factory()->create([
            'user_id' => $user->id,
        ]);

        Livewire::actingAs($user)
            ->test(Edit::class, [
                'address' => $address,
            ])
            ->set('recipient_name', '')
            ->set('city', '')
            ->call('save')
            ->assertHasErrors([
                'recipient_name' => 'required',
                'city' => 'required',
            ]);
    }

    public function test_customer_cannot_edit_another_users_address(): void
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();

        $address = Address::factory()->create([
            'user_id' => $otherUser->id,
            'recipient_name' => 'Original Name',
        ]);

        $this->actingAs($user);

        $this->expectException(NotFoundHttpException::class);

        app(Edit::class)->mount($address);
    }

    public function test_customer_can_update_coordinates(): void
    {
        $user = User::factory()->create();

        $address = Address::factory()->create([
            'user_id' => $user->id,
            'latitude' => null,
            'longitude' => null,
        ]);

        Livewire::actingAs($user)
            ->test(Edit::class, [
                'address' => $address,
            ])
            ->set('latitude', '31.5326')
            ->set('longitude', '35.0998')
            ->call('save');

        $this->assertDatabaseHas('addresses', [
            'id' => $address->id,
            'latitude' => '31.5326000',
            'longitude' => '35.0998000',
        ]);
    }
}
