<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AuthTest extends TestCase
{
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
}
