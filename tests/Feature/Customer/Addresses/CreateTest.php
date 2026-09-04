<?php

declare(strict_types=1);

namespace Tests\Feature\Customer\Addresses;

use App\Livewire\Customer\Addresses\Create;
use App\Models\Address;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class CreateTest extends TestCase
{
    use RefreshDatabase;

    public function test_customer_can_view_create_address_page(): void
    {
        $user = User::factory()->create();

        Livewire::actingAs($user)
            ->test(Create::class)
            ->assertStatus(200);
    }

    public function test_customer_can_create_address(): void
    {
        $user = User::factory()->create();

        Livewire::actingAs($user)
            ->test(Create::class)
            ->set('label', 'Home')
            ->set('recipient_name', 'Noor Abed')
            ->set('phone', '0599999999')
            ->set('country', 'Palestine')
            ->set('city', 'Hebron')
            ->set('area', 'Center')
            ->set('street', 'Main Street')
            ->set('building', '10')
            ->set('apartment', '3')
            ->set('address_line', 'Main Street, Building 10, Apartment 3')
            ->set('latitude', '31.5326')
            ->set('longitude', '35.0998')
            ->set('is_default', false)
            ->call('save');

        $this->assertDatabaseHas('addresses', [
            'user_id' => $user->id,
            'label' => 'Home',
            'recipient_name' => 'Noor Abed',
            'phone' => '0599999999',
            'city' => 'Hebron',
            'is_default' => false,
        ]);
    }

    public function test_customer_can_create_default_address(): void
    {
        $user = User::factory()->create();

        Livewire::actingAs($user)
            ->test(Create::class)
            ->set('recipient_name', 'Noor Abed')
            ->set('phone', '0599999999')
            ->set('country', 'Palestine')
            ->set('city', 'Hebron')
            ->set('street', 'Main Street')
            ->set('address_line', 'Main Street, Building 10')
            ->set('is_default', true)
            ->call('save');

        $this->assertDatabaseHas('addresses', [
            'user_id' => $user->id,
            'recipient_name' => 'Noor Abed',
            'is_default' => true,
        ]);
    }

    public function test_customer_cannot_create_address_with_invalid_data(): void
    {
        $user = User::factory()->create();

        Livewire::actingAs($user)
            ->test(Create::class)
            ->set('recipient_name', '')
            ->set('phone', '')
            ->set('country', '')
            ->set('city', '')
            ->set('street', '')
            ->set('address_line', '')
            ->call('save')
            ->assertHasErrors([
                'recipient_name',
                'phone',
                'country',
                'city',
                'street',
                'address_line',
            ]);

        $this->assertDatabaseCount('addresses', 0);
    }

    public function test_creating_new_default_address_clears_previous_default(): void
    {
        $user = User::factory()->create();

        $oldDefault = Address::factory()->create([
            'user_id' => $user->id,
            'is_default' => true,
        ]);

        Livewire::actingAs($user)
            ->test(Create::class)
            ->set('recipient_name', 'New Recipient')
            ->set('phone', '0599999999')
            ->set('country', 'Palestine')
            ->set('city', 'Hebron')
            ->set('street', 'New Street')
            ->set('address_line', 'New Street, Building 20')
            ->set('is_default', true)
            ->call('save');

        $this->assertFalse($oldDefault->fresh()->is_default);

        $this->assertDatabaseHas('addresses', [
            'user_id' => $user->id,
            'recipient_name' => 'New Recipient',
            'is_default' => true,
        ]);
    }
}
