<?php

namespace App\Http\Controllers;

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
}
