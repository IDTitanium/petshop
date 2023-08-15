<?php

declare(strict_types=1);

namespace App\Services;

use App\Http\Resources\UserResource;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Hash;

class UserService
{
    public function createUser(array $requestData): UserResource {
        $requestData['password'] = Hash::make($requestData['password']);

        $user = app(UserRepository::class)->create($requestData);

        return $user;
    }
}
