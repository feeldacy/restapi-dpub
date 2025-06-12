<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUserRequest;
use App\Models\User;
use App\Services\UserService;
use Exception;
use Illuminate\Auth\Events\Registered;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class RegisterController extends Controller
{

    protected $userService;

    public function __construct(UserService $userService){
        $this->userService = $userService;
    }

    public function __invoke(StoreUserRequest $request)
    {
        try {
            $registerUserData = $request->validated();

            $user = $this->userService->storeUserData($registerUserData);
            $user->assignRole('admin');

            return response()->json([
                'message' => 'User Created ',
                'status' => 'success'
            ], 201);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Terjadi error saat akun',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
