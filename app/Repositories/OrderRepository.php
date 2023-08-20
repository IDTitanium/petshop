<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Order;
use Illuminate\Pagination\LengthAwarePaginator;

class OrderRepository
{
    /**
     * Get orders for user
     *
     * @param int|null $userId
     *
     * @return LengthAwarePaginator
     */
    public function getOrdersForUser(?int $userId): LengthAwarePaginator {
        return Order::where('user_id', $userId)->paginate(config('pagination.items_per_page'));
    }
}
