<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Resources\UserResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class UserAuthController extends Controller
{
    public function login(LoginRequest $request): JsonResponse {
        $credentials = $request->validated();

        if (! $token = auth()->attempt($credentials)) {
            return $this->sendApiResponse(false, Response::HTTP_UNAUTHORIZED, __('messages.unauthorized'));
        }

        return $this->sendApiResponse(true, Response::HTTP_OK, __('messages.login_successful'),
                ['user' => UserResource::make(auth()->user()), 'token' => $token]);
    }

    public function logout(): JsonResponse {
        $user = auth()->user();

        auth()->logout();

        return $this->sendApiResponse(true, Response::HTTP_OK, __('messages.logout_successful'), UserResource::make($user));
    }
}
