<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class UserRepository
{
    public function create(array $data): User {
        return User::create($data);
    }

    public function getUserList(): LengthAwarePaginator {
        return User::whereIsAdmin(false)->paginate();
    }

    public function getUserByUuid(string $uuid): User|null {
        return User::whereUuid($uuid)->first();
    }

    public function editUserDetails(array $data): User|null {
        $user = $this->getUserByUuid($data['user_uuid']);

        if (!$user) return null;

        User::where('id', $user->id)->update([
            'first_name' => $data['first_name'] ?? $user->first_name,
            'last_name' => $data['last_name'] ?? $user->last_name,
            'email' => $data['email'] ?? $user->email,
            'address' => $data['address'] ?? $user->address,
            'phone_number' => $data['phone_number'] ?? $user->phone_number,
        ]);

        return $user->refresh();
    }

    public function deleteUserByUuid($uuid): void {
        User::whereUuid($uuid)->delete();
    }
}
