<?php

namespace Database\Factories;

use App\Models\OrderStatus;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Order>
 */
class OrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $products = [
            [
                'product' => Product::first()->uuid,
                'quantity' => 5
            ],
            [
                'product' => Product::skip(1)->first()->uuid,
                'quantity' => 6
            ]
        ];

        return [
            'user_id' => User::first()->id,
            'order_status_id' => OrderStatus::first()->id,
            'payment_id' => 1,
            'products' => json_encode($products),
            'address' => json_encode([
                'billing' => fake()->address(),
                'shipping' => fake()->address()
            ]),
            'delivery_fee' => fake()->numberBetween(100, 200),
            'amount' => fake()->numberBetween(10000, 20000),
        ];
    }
}
