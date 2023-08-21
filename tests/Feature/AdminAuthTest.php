<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\AdminUserSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;

class AdminAuthTest extends TestCase
{
    use RefreshDatabase;

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
