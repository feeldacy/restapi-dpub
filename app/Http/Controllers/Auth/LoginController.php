<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\LoginUserRequest;

class LoginController extends Controller
{
    /**
     * Handle the incoming login request.
     */
    public function __invoke(LoginUserRequest $request)
    {
        try {
            $loginUserData = $request->validated();

            $user = User::where('email', $loginUserData['email'])->first();

            if (!$user) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Email tidak ditemukan.'
                ], 401);
            }

            if (!Hash::check($loginUserData['password'], $user->password)) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Password salah, mohon masukkan password yang sesuai.'
                ], 401);
            }

            $token = $user->createToken($user->name . '-AuthToken')->plainTextToken;

            return response()->json([
                'status' => 'success',
                'access_token' => $token
            ], 200);
        }

        catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validasi gagal',
                'errors' => $e->errors()
            ], 422);
        }

        catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi error saat login',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
