<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Mail\SendCodeResetPassword;
use App\Models\ResetCodePassword;
use App\Models\User;
use App\Notifications\PasswordResetNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Str;


class ForgotPasswordController extends Controller
{
    public function phone(Request $request)
    {
        $data = $request->validate([
            'phone' => 'required|numeric|exists:users',
        ]);

        $user = User::where('phone', $data['phone'])->first();

        $passwordReset = ResetCodePassword::where('phone', $request->phone)->first();

       if ($passwordReset AND $passwordReset->created_at < now()->addHour()) {
           return Response::json([
               'status' => false,
               'msg' => __('auth.throttle')
           ], 422);
       }

        // Delete all old code that user send before.
        if ($passwordReset) {
            $passwordReset->delete();
        }

        $data['code'] = mt_rand(1000, 9999);

        if ($user) {
            $data['token'] = hash('sha256', Str::random(40));
        }

        $url ='';
        // $url ='https://www.tweetsms.ps/api.php?comm=sendsms&api_key=$2y$10$TMvrClek8vLS43r83y5Ituqys1nA4N/D9QlqCUpgule3ve0rp.EmC&to=97'.$request->mobile_number.'&message=' ."+كود+التحقق+الخاص+بك+لإستعادة+كلمة+المرور:" . $data['code'] . "&sender=iclinic.ps";

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_URL, $url);
    //        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
    //        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        $content = curl_exec($ch);

        if ($content == -100 OR $content == -110 OR $content == -113 OR $content == -115 OR $content == -116) {
            return Response::json([
                'status' => false,
                'msg' => __("auth.throttle"),
                'content' => $content,
            ], 400);
        }

        $codeData = ResetCodePassword::create([
            'phone'=>$data['phone'],
            'code'=>$data['code'],
            'token'=>$data['token'],
        ]);

        return Response::json([
            'status' => true,
            'msg' => __('passwords.sent-to-mobile'),
            'data' => [
                'token' => $codeData->token
            ]
        ], 200);
    }

    public function email(Request $request)
    {
        $data = $request->validate([
            'email' => 'required|email|exists:users',
        ]);

        $user = User::where('email', $data['email'])->first();

        $passwordReset = ResetCodePassword::where('email', $request->email)->first();

       if ($passwordReset AND $passwordReset->created_at < now()->addHour()) {
           return Response::json([
               'status' => false,
               'msg' => __('auth.throttle')
           ], 422);
       }

        // Delete all old code that user send before.
        if ($passwordReset) {
            $passwordReset->delete();
        }

        $data['code'] = mt_rand(1000, 9999);

        if ($user) {
            $data['token'] = hash('sha256', Str::random(40));
        }

        $codeData = ResetCodePassword::create([
            'email'=>$data['email'],
            'code'=>$data['code'],
            'token'=>$data['token'],
        ]);

        $user->notify(
            new PasswordResetNotification(
                $user,
                $data['code']
            )
        );
        return Response::json([
            'status' => true,
            'msg' => __('passwords.sent'),
            'data' => [
                'token' => $codeData->token
            ]
        ], 200);
    }


}
