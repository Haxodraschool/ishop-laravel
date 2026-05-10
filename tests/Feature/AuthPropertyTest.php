<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

/**
 * Property-based tests for authentication and authorization.
 *
 * P1:  Unauthenticated access redirects to login
 * P2:  Non-admin access to admin routes is denied (403)
 * P13: Login/logout round-trip — session state correct
 * P14: Registration creates customer account (role=0)
 * P15: Duplicate username rejected
 */
class AuthPropertyTest extends TestCase
{
    use RefreshDatabase;

    // ---------------------------------------------------------------
    // P1: All protected routes redirect unauthenticated users to /login
    // ---------------------------------------------------------------

    /**
     * @dataProvider protectedRoutesProvider
     */
    public function test_p1_unauthenticated_access_redirects_to_login(string $method, string $url): void
    {
        $response = $this->$method($url);
        $response->assertRedirect('/login');
    }

    public static function protectedRoutesProvider(): array
    {
        return [
            'orders index'   => ['get',  '/orders'],
            'orders create'  => ['get',  '/orders/create'],
            'wishlist index' => ['get',  '/wishlist'],
            'logout'         => ['post', '/logout'],
        ];
    }

    // ---------------------------------------------------------------
    // P2: Customer (role=0) gets 403 on all admin routes
    // ---------------------------------------------------------------

    /**
     * @dataProvider adminRoutesProvider
     */
    public function test_p2_customer_denied_on_admin_routes(string $method, string $url): void
    {
        $customer = User::factory()->create(['role' => 0]);

        $response = $this->actingAs($customer)->$method($url);

        $response->assertStatus(403);
    }

    public static function adminRoutesProvider(): array
    {
        return [
            'admin dashboard'  => ['get', '/admin/dashboard'],
            'admin products'   => ['get', '/admin/products'],
            'admin categories' => ['get', '/admin/categories'],
            'admin orders'     => ['get', '/admin/orders'],
            'admin users'      => ['get', '/admin/users'],
        ];
    }

    // ---------------------------------------------------------------
    // P13: Login/logout round-trip — authenticated after login, not after logout
    // ---------------------------------------------------------------

    public function test_p13_login_logout_round_trip(): void
    {
        // Run 20 iterations with different credentials
        for ($i = 0; $i < 20; $i++) {
            $username = 'user_p13_' . $i . '_' . uniqid();
            $password = 'password' . rand(1000, 9999);

            $user = User::factory()->create([
                'username' => $username,
                'password' => Hash::make($password),
                'role'     => 0,
            ]);

            // Login
            $this->post('/login', ['username' => $username, 'password' => $password])
                 ->assertRedirect('/');

            $this->assertAuthenticatedAs($user);

            // Logout
            $this->post('/logout');

            $this->assertGuest();
        }
    }

    // ---------------------------------------------------------------
    // P14: Registration always creates a customer account (role=0)
    // ---------------------------------------------------------------

    public function test_p14_registration_creates_customer_account(): void
    {
        for ($i = 0; $i < 20; $i++) {
            $username = 'reg_user_' . $i . '_' . uniqid();
            $name     = 'Test User ' . $i;
            $password = 'password' . rand(1000, 9999);

            $this->post('/register', [
                'name'                  => $name,
                'username'              => $username,
                'password'              => $password,
                'password_confirmation' => $password,
            ])->assertRedirect('/');

            $user = User::where('username', $username)->first();

            $this->assertNotNull($user, "User {$username} should exist after registration");
            $this->assertEquals(0, $user->role, 'Newly registered user must have role=0 (customer)');
            $this->assertEquals($name, $user->name);

            // Logout for next iteration
            $this->post('/logout');
        }
    }

    // ---------------------------------------------------------------
    // P15: Duplicate username is rejected with validation error
    // ---------------------------------------------------------------

    public function test_p15_duplicate_username_rejected(): void
    {
        for ($i = 0; $i < 20; $i++) {
            $username = 'dup_user_' . $i . '_' . uniqid();
            $password = 'password123';

            // Create first user
            User::factory()->create(['username' => $username]);

            // Attempt to register with same username
            $response = $this->post('/register', [
                'name'                  => 'Another User',
                'username'              => $username,
                'password'              => $password,
                'password_confirmation' => $password,
            ]);

            $response->assertSessionHasErrors('username');

            // Ensure only one user with this username exists
            $count = User::where('username', $username)->count();
            $this->assertEquals(1, $count, "Duplicate username '{$username}' should not be created");
        }
    }
}
