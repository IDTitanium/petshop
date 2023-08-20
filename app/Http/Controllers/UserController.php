<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\CreateUserRequest;
use App\Http\Requests\ListUserOrdersRequest;
use App\Repositories\OrderRepository;
use App\Services\UserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class UserController extends Controller
{
    public function __construct()
    {
    }

    /**
     * List user orders
     *
     * @param ListUserOrdersRequest $request
     *
     * @return JsonResponse
     */
    public function listUserOrders(ListUserOrdersRequest $request): JsonResponse {
        $data = app(OrderRepository::class)->getOrdersForUser(auth()->id());

        return $this->sendApiResponse(true, Response::HTTP_OK, __('messages.user_orders_retrieved'), $data);
    }
}
