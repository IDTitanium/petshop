<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Resources\UserResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class UserAuthController extends Controller
{
    /**
     * User login
     */
    public function login(LoginRequest $request): JsonResponse
    {
        $credentials = $request->validated();

        $token = auth()->attempt($credentials);

        if (! $token) {
            return $this->sendApiResponse(false, Response::HTTP_UNAUTHORIZED, __('messages.unauthorized'));
        }

        return $this->sendApiResponse(
            true,
            Response::HTTP_OK,
            __('messages.login_successful'),
            ['user' => UserResource::make(auth()->user()), 'token' => $token]
        );
    }
}
