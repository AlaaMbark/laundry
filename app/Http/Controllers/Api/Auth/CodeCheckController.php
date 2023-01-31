<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Models\ResetCodePassword;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class CodeCheckController extends Controller
{
    public function __invoke(Request $request)
    {
        $request->validate([
            'code' => 'required|string|exists:reset_code_passwords',
            'token' => 'required|string|exists:reset_code_passwords',
        ]);

        $passwordReset = ResetCodePassword::where('code', $request->code)
            ->where('token', $request->token)
            ->first();
        // check if it does not expired: the time is one hour
        if ($passwordReset->created_at > now()->addHour()) {
            $passwordReset->delete();
            return Response::json([
                'status' => false,
                'msg' => __('passwords.token')
            ], 422);
        }
        $user = User::where('phone', $passwordReset->phone)->orWhere('email', $passwordReset->email)->first();

        if($user && $user->status == 0){
            return Response::json([
                'status' => false,
                'msg' => __('auth.status')
            ], 401);
        }

        if ($user) {
            $device_name = $request->post('device_name', $request->userAgent());
            $token = $user->createToken($device_name);

            $passwordReset->delete();

            return Response::json([
                'status' => true,
                'msg' => __('passwords.check-code-suc'),
                'data' => [
                    'token' => $token->plainTextToken,
                    'user' => $user
                ]
            ], 200);
        }

        return Response::json([
            'status' => false,
            'msg' => __('passwords.again'),
        ], 400);
    }
}
