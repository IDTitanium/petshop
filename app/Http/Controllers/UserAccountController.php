<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\CreateUserRequest;
use App\Http\Requests\DeleteUserRequest;
use App\Http\Requests\EditUserRequest;
use App\Http\Requests\GetUsersRequest;
use App\Repositories\UserRepository;
use App\Traits\SendApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class UserAccountController extends Controller
{
    use SendApiResponse;

    public function __construct(private UserRepository $userRepository)
    {
    }

    /**
     * Get users list
     */
    public function getUsers(GetUsersRequest $request): JsonResponse
    {
        $data = $this->userRepository->getUserList($request->validated());

        return $this->sendApiResponse(true, Response::HTTP_OK, __('messages.users_retrieved'), $data);
    }

    /**
     * Edit user
     */
    public function editUser(EditUserRequest $request): JsonResponse
    {
        $data = $this->userRepository->editUserDetails($request->validated());

        return $this->sendApiResponse(true, Response::HTTP_OK, __('messages.user_updated'), $data);
    }

    /**
     * Delete user
     */
    public function deleteUser(DeleteUserRequest $request, string $uuid): JsonResponse
    {
        $this->userRepository->deleteUserByUuid($uuid);

        return $this->sendApiResponse(true, Response::HTTP_OK, __('messages.user_deleted'));
    }

    /**
     * Create admin user
     */
    public function createAdmin(CreateUserRequest $request): JsonResponse
    {
        $data = $request->validated();
        $data['is_admin'] = true;

        $admin = $this->userRepository->create($data);

        return $this->sendApiResponse(true, Response::HTTP_CREATED, __('messages.admin_created'), $admin);
    }
}
