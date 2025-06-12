<?php

namespace App\Http\Controllers\Auth;

use App\Models\ForgotPasswordOtp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;


class VerifyForgotPasswordController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $messages = [
            'email.required' => 'The email field is required',
            'email.email' => 'The email must be a valid email address',
            'email.exists' => 'The selected email is invalid',
            'otp.required' => 'The OTP field is required',
            'otp.min' => 'The OTP must be at least 5 characters',
        ];

        $validator = Validator::make(
            $request->all(),
            [
                'email' => 'required|email|exists:users',
                'otp' => 'required|min:5',
            ],
            $messages
        );
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->messages()->first()
            ], 422);
        }
        $email = $request->input('email');
        $otp = $request->input('otp');

        $forgotOtp = ForgotPasswordOtp::query()->where('email', $email)->orderBy('created_at', 'desc')->first();

        if (!$forgotOtp) {
            return response()->json(['error' => 'Invalid email']);
        }

        if ($forgotOtp->otp == $otp && now()->diffInSeconds($forgotOtp->created_at) <= 300) {
            return response()->json([
                'success' => true,
                'message' => 'OTP Verified Successfully',
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Invalid OTP',
            ], 422);
        }
    }
}
