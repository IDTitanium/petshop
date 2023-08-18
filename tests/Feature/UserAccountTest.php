<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\AdminUserSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class UserAccountTest extends TestCase
{
    use RefreshDatabase;

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

    /**
     * Can edit user account
     */
    public function test_can_edit_user_account_with_admin_token(): void
    {
        User::factory(1)->create();
        Artisan::call('db:seed', ['class' => AdminUserSeeder::class]);

        $response = $this->post('/api/v1/admin/login', [
            'email' => User::whereIsAdmin(true)->first()->email,
            'password' => 'admin'
        ]);

        $body = $response->decodeResponseJson();

        $user = User::first();

        $data = [
            'first_name' => 'Idris',
            'last_name' => "lawal",
            'email' => 'idriseun222@gmail.com',
            'phone_number' => '2349018063510',
            'address' => 'Lagos, Nigeria'
        ];

        $response = $this->put("/api/v1/admin/user-edit/{$user->uuid}", $data, [
            'Authorization' => 'Bearer '.$body['data']['token']
        ]);

        $response->assertSuccessful();
        $response->assertSeeText(__('messages.user_updated'));

        $user->refresh();

        $this->assertEquals($user->first_name, 'Idris');
        $this->assertEquals($user->last_name, 'lawal');
        $this->assertEquals($user->email, 'idriseun222@gmail.com');
        $this->assertEquals($user->phone_number, '2349018063510');
        $this->assertEquals($user->address, 'Lagos, Nigeria');
    }

    /**
     * Cannot edit admin account
     */
    public function test_cannot_edit_admin_account_with_admin_token(): void
    {
        Artisan::call('db:seed', ['class' => AdminUserSeeder::class]);

        $admin = User::whereIsAdmin(true)->first();
        $response = $this->post('/api/v1/admin/login', [
            'email' => $admin->email,
            'password' => 'admin'
        ]);

        $body = $response->decodeResponseJson();

        $data = [
            'first_name' => 'Idris',
            'last_name' => "lawal",
            'email' => 'idriseun222@gmail.com',
            'phone_number' => '2349018063510',
            'address' => 'Lagos, Nigeria'
        ];

        $response = $this->put("/api/v1/admin/user-edit/{$admin->uuid}", $data, [
            'Authorization' => 'Bearer '.$body['data']['token']
        ]);

        $response->assertUnprocessable();
        $response->assertDontSeeText(__('messages.user_updated'));
    }

    /**
     * Admin user cannot be deleted
     */
    public function test_admin_user_cannot_be_deleted(): void {
        Artisan::call('db:seed', ['class' => AdminUserSeeder::class]);

        $admin = User::whereIsAdmin(true)->first();
        $response = $this->post('/api/v1/admin/login', [
            'email' => $admin->email,
            'password' => 'admin'
        ]);

        $body = $response->decodeResponseJson();

        $response = $this->delete("/api/v1/admin/user-delete/{$admin->uuid}", [
            'Authorization' => 'Bearer '.$body['data']['token']
        ]);

        $response->assertUnprocessable();
        $response->assertSeeText(__('messages.admin_cannot_be_deleted'));
    }

    /**
     * Non existing user cannot be deleted
     */
    public function test_non_existing_user_cannot_be_deleted(): void {
        Artisan::call('db:seed', ['class' => AdminUserSeeder::class]);

        $admin = User::whereIsAdmin(true)->first();

        $response = $this->post('/api/v1/admin/login', [
            'email' => $admin->email,
            'password' => 'admin'
        ]);

        $body = $response->decodeResponseJson();

        $response = $this->delete("/api/v1/admin/user-delete/".fake()->uuid(), [
            'Authorization' => 'Bearer '.$body['data']['token']
        ]);

        $response->assertUnprocessable();
        $response->assertSeeText(__('messages.invalid_user_id'));
    }

    /**
     * User can be deleted by admin
     */
    public function test_user_can_be_deleted_by_admin(): void {
        Artisan::call('db:seed', ['class' => AdminUserSeeder::class]);
        User::factory(1)->create();

        $admin = User::whereIsAdmin(true)->first();
        $user = User::whereIsAdmin(false)->first();

        $response = $this->post('/api/v1/admin/login', [
            'email' => $admin->email,
            'password' => 'admin'
        ]);

        $body = $response->decodeResponseJson();

        $response = $this->delete("/api/v1/admin/user-delete/{$user->uuid}", [
            'Authorization' => 'Bearer '.$body['data']['token']
        ]);

        $response->assertSuccessful();
        $response->assertSeeText(__('messages.user_deleted'));

        $this->assertDatabaseMissing('users', $user->toArray());
    }

    /**
     * Can create admin user
     */
    public function test_can_create_admin(): void
    {
        Artisan::call('db:seed', ['class' => AdminUserSeeder::class]);

        $admin = User::whereIsAdmin(true)->first();

        $response = $this->post('/api/v1/admin/login', [
            'email' => $admin->email,
            'password' => 'admin'
        ]);

        $body = $response->decodeResponseJson();

        $data = [
            'first_name' => 'Idris',
            'last_name' => "lawal",
            'email' => 'idriseun222+1@gmail.com',
            'phone_number' => '2349018063510',
            'address' => 'Lagos, Nigeria',
            'password' => 'admin'
        ];

        $response = $this->post("/api/v1/admin/create", $data, [
            'Authorization' => 'Bearer '.$body['data']['token']
        ]);

        $response->assertSuccessful();

        $response->assertStatus(Response::HTTP_CREATED);

        $data['is_admin'] = 1;
        unset($data['password']);

        $this->assertDatabaseHas('users', $data);
    }
}
