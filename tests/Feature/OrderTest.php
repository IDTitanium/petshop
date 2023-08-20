<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Order;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;

class OrderTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Can get orders for logged in user
     */
    public function test_can_get_orders_for_logged_in_user(): void
    {
        Artisan::call('db:seed');

        $response = $this->post('/api/v1/user/login', [
            'email' => User::whereIsAdmin(false)->first()->email,
            'password' => 'userpassword'
        ]);

        $response->assertSuccessful();

        $responseBody = $response->decodeResponseJson();

        $response = $this->get('/api/v1/user/orders', [
            'Authorization' => 'Bearer '.$responseBody['data']['token']
        ]);

        $response->assertSuccessful();

        $body = $response->decodeResponseJson();

        $this->assertNotEmpty($body['data']);
    }
}
