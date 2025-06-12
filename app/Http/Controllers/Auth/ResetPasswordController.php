<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use App\Http\Controllers\Controller;


class ResetPasswordController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
         try {
            $updatePassword = DB::table('password_reset_tokens')
                ->where([
                    'email' => $request->email,
                    'token' => $request->token,
                ])
                ->first();

            if (! $updatePassword) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid OTP',
                ], 422);
            }
            $user = User::query()->where('email', $request->email)
                ->update(['password' => Hash::make($request->new_password)]);

            DB::table('password_reset_tokens')->where(['email' => $request->email])->delete();
            DB::table('forgot_password_otps')->where(['email' => $request->email])->delete();
            
            return response()->json([
                'success' => true,
                'message' => 'Successfully reset password',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => true,
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}
