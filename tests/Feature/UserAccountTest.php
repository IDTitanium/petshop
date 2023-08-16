<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\AdminUserSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;

class UserAccountTest extends TestCase
{
    /**
     * Cannot get list of users without token
     */
    public function test_cannot_get_list_of_users_without_token(): void
    {
        $response = $this->get('/api/v1/admin/users');

        $response->assertUnauthorized();
    }

    /**
     * Cannot get list of users without admin token
     */
    public function test_cannot_get_list_of_users_without_admin_token(): void
    {
        User::factory(1)->create();
        $response = $this->post('/api/v1/user/login', [
            'email' => User::first()->email,
            'password' => 'userpassword'
        ]);

        $body = $response->decodeResponseJson();

        $response = $this->get('/api/v1/admin/users', [
            'Authorization' => 'Bearer '.$body['data']['token']
        ]);

        $response->assertUnauthorized();
        $response->assertDontSeeText(__('messages.users_retrieved'));
    }

    /**
     * Can get list of users with admin token
     */
    public function test_can_get_list_of_users_with_admin_token(): void
    {
        Artisan::call('db:seed', ['class' => AdminUserSeeder::class]);

        $response = $this->post('/api/v1/admin/login', [
            'email' => User::whereIsAdmin(true)->first()->email,
            'password' => 'admin'
        ]);

        $body = $response->decodeResponseJson();

        $response = $this->get('/api/v1/admin/users', [
            'Authorization' => 'Bearer '.$body['data']['token']
        ]);

        $response->assertSuccessful();
        $response->assertSeeText(__('messages.users_retrieved'));
    }
}
