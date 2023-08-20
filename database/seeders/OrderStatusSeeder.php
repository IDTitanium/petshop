<?php

namespace Database\Seeders;

use App\Models\OrderStatus;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class OrderStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        OrderStatus::truncate();

        foreach($this->statuses() as $status) {
            OrderStatus::create([
                'title' => $status
            ]);
        }
    }

    /**
     * Get the order statuses
     */
    public function statuses(): array {
        return [
            'Pending',
            'Accepted',
            'Paid',
            'Rejected',
            'Cancelled'
        ];
    }
}
