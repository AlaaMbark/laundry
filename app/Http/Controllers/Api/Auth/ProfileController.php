<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    public function showProfile()
    {
        $user = Auth::user();
        $order = Order::where('user_id', $user->id)->where('status', 'finished')->where('canceled', 0)->first();

        return response()->json([
            'user' => $user,
            'has_order' => $order ? true : false,
        ]);
    }

    public function updateProfile(Request $request)
    {
        $validateUser = Validator::make($request->all(),
            [
                'name' => 'required',
                'email' => ['required', 'email', Rule::unique('users', 'email')->ignore(Auth::user())],
                'icc_phone' => 'required',
                'phone' => ['required', 'numeric', Rule::unique('users', 'phone')->ignore(Auth::user())],
                'location' => 'required',
                'latitude' => 'required',
                'longitude' => 'required',
            ]);

        if($validateUser->fails()){
            return response()->json([
                'status' => false,
                'msg' => __('message.validation-error'),
                'errors' => $validateUser->errors()
            ], 401);
        }

        $user = Auth::user()->update([
            'name' => $request->name,
            'email' => $request->email,
            'icc_phone' => $request->icc_phone,
            'phone' => $request->phone,
            'location' => $request->location,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
        ]);

        return response()->json([
            'status' => true,
            'msg' => __('message.success'),
        ], 200);

    }

}
