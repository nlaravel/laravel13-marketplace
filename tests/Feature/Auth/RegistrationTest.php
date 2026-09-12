<?php

declare(strict_types=1);

namespace Tests\Feature\Auth;

use App\Actions\Fortify\CreateNewUser;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_new_user_is_assigned_customer_role(): void
    {
        Role::findOrCreate('customer', 'web');

        $user = (new CreateNewUser)->create([
            'name' => 'Test Customer',
            'email' => 'customer@example.com',
            'password' => 'Password123!',
            'password_confirmation' => 'Password123!',
        ]);

        $this->assertInstanceOf(User::class, $user);
        $this->assertTrue($user->hasRole('customer'));
    }
}
