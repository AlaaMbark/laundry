<?php

namespace App\Http\Controllers\Api\Mobile;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class OrderUserController extends Controller
{

    public function createOrder(Request $request)
    {
        $validateUser = Validator::make($request->all(),
            [
                'grille_id' => 'required|exists:users,id,role,grille',
            ]);


        if($validateUser->fails()){
            return response()->json([
                'status' => false,
                'msg' => __('message.validation-error'),
                'errors' => $validateUser->errors()
            ], 401);
        }

        $order = Order::where('user_id', Auth::user()->id)->where(function($query) {
            $query->where('status', 'finished')->orWhere('canceled', 0);
        })->first();
        if($order){
            return response()->json([
                'status' => false,
                'msg' => __('message.cannot_order_more_once'),
            ], 401);
        }

        Order::create([
            'grille_id' => $request->grille_id,
            'user_id' => Auth::user()->id,
        ]);

        return response()->json([
            'status' => true,
            'msg' => __('message.success'),
        ], 200);

    }

    public function canceledOrder(Request $request)
    {
        $validate = Validator::make($request->all(),
            [
                'reason_cancellation' => 'nullable|string',
            ]);


        if($validate->fails()){
            return response()->json([
                'status' => false,
                'msg' => __('message.validation-error'),
                'errors' => $validate->errors()
            ], 401);
        }

        $order = Order::where('user_id', Auth::user()->id)->where(function($query) {
            $query->where('status', 'finished')->orWhere('canceled', 0);
        })->first();
        if(!$order){
            return response()->json([
                'status' => false,
                'msg' => __('message.no_previous_request'),
            ], 401);
        }

        $order->update([
            'canceled' => 1,
            'status' => 'finished',
            'canceled_from' => 'user',
            'reason_cancellation' => $request->reason_cancellation,
        ]);

        return response()->json([
            'status' => true,
            'msg' => __('message.success'),
        ], 200);

    }

}
