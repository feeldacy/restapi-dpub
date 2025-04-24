<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUserRequest;
use App\Models\User;
use App\Services\UserService;
use Database\Seeders\UserSeeder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{

    protected $userService;

    public function __construct(UserService $userService){
        $this->userService = $userService;
    }

    public function __invoke(StoreUserRequest $request)
    {
        $registerUserData = $request->validated();

        $user = $this->userService->storeUserData($registerUserData);

        $user->assignRole('superAdmin');

        return response()->json([
            'message' => 'User Created ',
            'status' => 'Success'
        ], 200);
    }
}
