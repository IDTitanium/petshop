<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\AdminUserSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;

class AuthTest extends TestCase
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
    public function test_cannot_login_valid_credentials(): void
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
     * Cannot login to admin section with non admin account
     */
    public function test_cannot_login_to_admin_with_non_admin_account(): void
    {
        User::factory(1)->create();
        $response = $this->post('/api/v1/admin/login', [
            'email' => User::first()->email,
            'password' => 'userpassword'
        ]);

        $response->assertUnauthorized();
        $response->assertDontSeeText('token');
    }

    /**
     * Can login to admin section with admin account
     */
    public function test_can_login_to_admin_with_admin_account(): void
    {
        Artisan::call('db:seed', ['class' => AdminUserSeeder::class]);

        $response = $this->post('/api/v1/admin/login', [
            'email' => User::whereIsAdmin(true)->first()->email,
            'password' => 'admin'
        ]);

        $response->assertSuccessful();
        $response->assertSeeText('token');
    }


}
