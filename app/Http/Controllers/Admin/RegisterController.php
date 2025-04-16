<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUserRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    public function __invoke(StoreUserRequest $request)
    {
        $registerUserData = $request->validated();

        $user = User::create([
            'name' => $registerUserData['name'],
            'email' => $registerUserData['email'],
            'password' => Hash::make($registerUserData['password']),
        ]);

        $user->assignRole('admin');

        return response()->json([
            'message' => 'User Created ',
            'status' => 'success'
        ], 200);
    }
}
