<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUserRequest;
use App\Models\User;
use App\Services\UserService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    /**
     * Handle the incoming request.
     */
    protected $userService;

    public function __construct(UserService $userService){
        $this->userService = $userService;
    }

    public function __invoke(StoreUserRequest $request)
    {
        try {
            $registerUserData = $request->validated();

        $user = $this->userService->storeUserData($registerUserData);

        $user->assignRole('guest');

        return response()->json([
            'message' => 'User Guest Created ',
            'status' => 'Success'
        ], 201);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Terjadi error saat membuat akun',
                'error' => $e->getMessage()
            ], 500);
        }

    }
}
