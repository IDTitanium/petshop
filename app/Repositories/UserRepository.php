<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;

class UserRepository
{
    /**
     * Create user
     *
     * @param array<string, int> $data
     */
    public function create(array $data): User
    {
        return User::create($data);
    }

    /**
     * Get user list
     *
     * @param array<string, int, array> $data
     */
    public function getUserList(array $data): LengthAwarePaginator
    {
        $query = User::whereIsAdmin(false);

        if (isset($data['filters'])) {
            $query = $this->applyGetUserFiltersToQuery($query, $data['filters']);
        }

        $paginationLength = $data['items_per_page'] ?? config('pagination.items_per_page');

        return $query->paginate($paginationLength);
    }

    /**
     * Get user by uuid
     */
    public function getUserByUuid(string $uuid): User|null
    {
        return User::whereUuid($uuid)->first();
    }

    /**
     * Edit User details
     *
     * @param array<string> $data
     *
     * @return User
     */
    public function editUserDetails(array $data): User|null
    {
        $user = $this->getUserByUuid($data['user_uuid']);

        if (! $user) {
            return null;
        }

        User::where('id', $user->id)->update([
            'first_name' => $data['first_name'] ?? $user->first_name,
            'last_name' => $data['last_name'] ?? $user->last_name,
            'email' => $data['email'] ?? $user->email,
            'address' => $data['address'] ?? $user->address,
            'phone_number' => $data['phone_number'] ?? $user->phone_number,
        ]);

        return $user->refresh();
    }

    /**
     * Delete user by uuid
     */
    public function deleteUserByUuid(string $uuid): void
    {
        User::whereUuid($uuid)->delete();
    }

    /**
     * Apply get user filters to query
     *
     * @param array<string> $filters
     */
    private function applyGetUserFiltersToQuery(EloquentBuilder $query, array $filters): EloquentBuilder
    {
        if (isset($filters['name'])) {
            $splitName = explode(' ', $filters['name']);

            $firstName = $splitName[0] ?? null;
            $lastName = $splitName[1] ?? null;

            $query->where(function ($q) use ($firstName, $lastName): void {
                if (! is_null($firstName)) {
                    $q->where('first_name', 'like', "%{$firstName}%");
                    $q->orWhere('last_name', 'like', "%{$firstName}%");
                }
                if (! is_null($lastName)) {
                    $q->orWhere('first_name', 'like', "%{$lastName}%");
                    $q->orWhere('last_name', 'like', "%{$lastName}%");
                }
            });
        }

        unset($filters['name']);

        foreach ($filters as $key => $value) {
            $query->where($key, 'like', "%{$value}%");
        }

        return $query;
    }
}
