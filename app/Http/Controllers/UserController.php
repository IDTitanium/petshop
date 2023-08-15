<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\CreateUserRequest;
use App\Services\UserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class UserController extends Controller
{
    public function __construct(private UserService $userService)
    {
    }

    public function create(CreateUserRequest $request): JsonResponse {
        $data = $this->userService->createUser($request->validated());

        return $this->sendApiResponse(true, Response::HTTP_CREATED, __('messages.user_created'), $data);
    }
}
