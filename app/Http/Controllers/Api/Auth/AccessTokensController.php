<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use Laravel\Sanctum\PersonalAccessToken;
use Laravel\Socialite\Facades\Socialite;

class AccessTokensController extends Controller
{
    public function createUser(Request $request)
    {
        $validateUser = Validator::make($request->all(),
        [
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'icc_phone' => 'required',
            'phone' => 'required|numeric|unique:users,phone',
            'role' => 'required|in:grille,user',
            'location' => 'required',
            'latitude' => 'required',
            'longitude' => 'required',
            'password' => 'required'
        ]);

        if($validateUser->fails()){
            return response()->json([
                'status' => false,
                'msg' => __('message.validation-error'),
                'errors' => $validateUser->errors()
            ], 401);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'icc_phone' => $request->icc_phone,
            'phone' => $request->phone,
            'role' => $request->role,
            'location' => $request->location,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'password' => Hash::make($request->password)
        ]);

        return response()->json([
            'status' => true,
            'msg' => __('message.success'),
            'token' => $user->createToken("API TOKEN")->plainTextToken
        ], 200);

    }

    public function store(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'max:255'],
            'password' => ['required'],
            'device_name' => ['string', 'max:255'],
        ]);

        $user = User::where('email', $request->email)
                ->orWhere('phone', $request->email)
                ->first();

        if($user && $user->status == 0){
            return Response::json([
                'status' => false,
                'msg' => __('auth.status')
            ], 400);
        }
        if ($user && Hash::check($request->password, $user->password)) {
            $device_name = $request->post('device_name', $request->userAgent());
            $token = $user->createToken($device_name);

            return Response::json([
                'status' => true,
                'msg' => __('message.login-success'),
                'data' => [
                    'token' => $token->plainTextToken,
                    'user' => $user
                ]
            ], 200);
        }

        return Response::json([
            'status' => false,
            'msg' => __('auth.failed')
        ], 401);
    }

    public function destroy($token = null)
    {
        $user = Auth::user();

        if($token === null){
            $user->currentAccessToken()->delete();
            return;
        }

        $personal_access_token = PersonalAccessToken::findToken($token);

        if($user->id == $personal_access_token->tokenable_id){
            $personal_access_token->delete();
        }

        $user->tokens()->where('token', $token)->delete();
    }


    public function redirectToGoogle()
    {
        return Socialite::driver('google')->stateless()->redirect();
    }

    public function handleGoogleCallback()
    {
        $user = Socialite::driver('google')->user();

        $email = $user->email;
        $db_user = User::where('email', $email)->first();

        if ($db_user == null) {
            $registerd_user = User::create([
                'name' => $user->nickname,
                'icc_phone' => '00971',
                'phone' => '123456789',
                'location' => '0',
                'latitude' => '0',
                'longitude' => '0',
                'email' => $user->email,
                'password' => Hash::make('password'),
            ]);
            if ($registerd_user) {
                $token = $user->token;

                return Response::json([
                    'status' => true,
                    'msg' => __('message.login-success'),
                    'data' => [
                        'token' => $token->plainTextToken,
                        'user' => $user
                    ]
                ], 200);
            }
        } else {
            if ($db_user) {
                $token = $user->token;

                return Response::json([
                    'status' => true,
                    'msg' => __('message.login-success'),
                    'data' => [
                        'token' => $token->plainTextToken,
                        'user' => $user
                    ]
                ], 200);
            }
        }
        return Response::json([
            'status' => false,
            'msg' => __('auth.failed')
        ], 401);
    }


    public function redirectToFacebook()
    {
        return Socialite::driver('facebook')->stateless()->redirect();
    }

    public function handleFacebookCallback()
    {
        $user = Socialite::driver('facebook')->user();

        $email = $user->email;
        $db_user = User::where('email', $email)->first();

        if ($db_user == null) {
            $registerd_user = User::create([
                    'name' => $user->nickname,
                    'icc_phone' => '00971',
                    'phone' => '123456789',
                    'location' => '0',
                    'latitude' => '0',
                    'longitude' => '0',
                    'email' => $user->email,
                    'password' => Hash::make('password'),
                ]);
            if ($registerd_user) {
                $token = $user->token;

                return Response::json([
                    'status' => true,
                    'msg' => __('message.login-success'),
                    'data' => [
                        'token' => $token->plainTextToken,
                        'user' => $user
                    ]
                ], 200);
            }
        } else {
            if ($db_user) {
                $token = $user->token;

                return Response::json([
                    'status' => true,
                    'msg' => __('message.login-success'),
                    'data' => [
                        'token' => $token->plainTextToken,
                        'user' => $user
                    ]
                ], 200);
            }
        }
        return Response::json([
            'status' => false,
            'msg' => __('auth.failed')
        ], 401);
    }
}
