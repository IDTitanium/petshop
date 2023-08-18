<?php

namespace App\Http\Controllers;

use App\Http\Requests\DeleteUserRequest;
use App\Http\Requests\EditUserRequest;
use App\Repositories\UserRepository;
use App\Traits\SendApiResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class UserAccountController extends Controller
{
    use SendApiResponse;

    public function getUsers() {
        $data = app(UserRepository::class)->getUserList();

        return $this->sendApiResponse(true, Response::HTTP_OK, __('messages.users_retrieved'), $data);
    }

    public function editUser(EditUserRequest $request) {
        $data = app(UserRepository::class)->editUserDetails($request->validated());

        return $this->sendApiResponse(true, Response::HTTP_OK, __('messages.user_updated'), $data);
    }

    public function deleteUser(DeleteUserRequest $request, $uuid) {
        app(UserRepository::class)->deleteUserByUuid($uuid);

        return $this->sendApiResponse(true, Response::HTTP_OK, __('messages.user_deleted'));
    }
}
