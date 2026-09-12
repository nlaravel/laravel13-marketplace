<?php

declare(strict_types=1);

namespace Tests\Feature\Auth;

use App\Http\Responses\LoginResponse;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Tests\TestCase;

class LoginRedirectTest extends TestCase
{
    use RefreshDatabase;

    public function test_customer_is_redirected_to_customer_dashboard(): void
    {
        Role::findOrCreate('customer', 'web');

        $customer = User::factory()->create();
        $customer->assignRole('customer');

        $request = Request::create('/login', 'POST');
        $request->setUserResolver(fn (): User => $customer);

        $response = (new LoginResponse)->toResponse($request);

        $this->assertTrue($response->isRedirect(route('customer.dashboard')));
    }

    public function test_seller_is_redirected_to_seller_dashboard(): void
    {
        Role::findOrCreate('seller', 'web');

        $seller = User::factory()->create();
        $seller->assignRole('seller');

        $request = Request::create('/login', 'POST');
        $request->setUserResolver(fn (): User => $seller);

        $response = (new LoginResponse)->toResponse($request);

        $this->assertTrue($response->isRedirect(route('seller.dashboard')));
    }

    public function test_user_without_role_receives_forbidden_response(): void
    {
        $user = User::factory()->create();

        $request = Request::create('/login', 'POST');
        $request->setUserResolver(fn (): User => $user);

        $this->expectException(HttpException::class);
        $this->expectExceptionMessage('User does not have an assigned role.');

        (new LoginResponse)->toResponse($request);
    }
}
