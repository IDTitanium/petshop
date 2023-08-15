<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Resources\UserResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class AdminAuthController extends Controller
{
    public function login(LoginRequest $request): JsonResponse {
        $credentials = $request->validated();

        if (! $token = auth()->attempt($credentials)) {
            return $this->sendApiResponse(false, Response::HTTP_UNAUTHORIZED, __('messages.unauthorized'));
        }

        if (!auth()->user()->is_admin) {
            return $this->sendApiResponse(false, Response::HTTP_UNAUTHORIZED, __('messages.unauthorized'));
        }

        return $this->sendApiResponse(true, Response::HTTP_OK, "Login Successful",
                ['admin' => UserResource::make(auth()->user()), 'token' => $token]);
    }
}
