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
}
