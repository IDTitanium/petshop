<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\AdminUserSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;

class UserAuthTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Cannot login without credentials
     */
    public function test_cannot_login_without_credentials(): void
    {
        $response = $this->post('/api/v1/user/login');

        $response->assertUnprocessable();
        $response->assertDontSeeText('token');
    }

    /**
     * Cannot login with invalid credentials
     */
    public function test_cannot_login_with_invalid_credentials(): void
    {
        $response = $this->post('/api/v1/user/login', [
            'email' => 'some@thing.com',
            'password' => 'wrong_password'
        ]);

        $response->assertUnauthorized();
        $response->assertDontSeeText('token');
    }

    /**
     * Can login with valid credentials
     */
    public function test_can_login_valid_credentials(): void
    {
        User::factory(1)->create();
        $response = $this->post('/api/v1/user/login', [
            'email' => User::first()->email,
            'password' => 'userpassword'
        ]);

        $response->assertSuccessful();
        $response->assertSeeText('token');
    }

    /**
     * Can logout of user account
     */
    public function test_can_logout_of_user_account(): void
    {
        User::factory(1)->create();

        $response = $this->post('/api/v1/user/login', [
            'email' => User::first()->email,
            'password' => 'userpassword'
        ]);

        $response->assertSuccessful();

        $body = $response->decodeResponseJson();

        $response = $this->get('/api/v1/user/logout', [
            'Authorization' => 'Bearer '.$body['data']['token']
        ]);

        $response->assertSuccessful();
        $response->assertSeeText(__('messages.logout_successful'));

        /**
         * Try to use the token to call an endpoint with the same token
         */
        $response = $this->get('/api/v1/user/orders', [
            'Authorization' => 'Bearer '.$body['data']['token']
        ]);

        $response->assertUnauthorized();
    }
}
