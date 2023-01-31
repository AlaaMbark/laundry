<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Response;

class ResetPasswordController extends Controller
{
    public function __invoke(Request $request)
    {
        $request->validate([
            'password' => 'required|string|min:6|confirmed',
        ]);

        $user = Auth::user();
        $request['password'] = Hash::make($request->password);
        $user->update([
            'password'=>$request['password']
        ]);

        return Response::json([
            'status' => true,
            'msg' => __('passwords.reset')
        ], 200);
    }
}
