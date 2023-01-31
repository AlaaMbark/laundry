<?php

namespace App\Http\Controllers\Api\Mobile;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class OrderGrilleController extends Controller
{

    public function indexNewOrder()
    {
        return Order::where('grille_id', auth()->user()->id)
            ->where('canceled', 0)
            ->where('status', 'new')
            ->with('user')
            ->paginate();
    }

    public function indexPendingOrder()
    {
        return Order::where('grille_id', auth()->user()->id)
            ->where('canceled', 0)
            ->where('status', 'pending')
            ->paginate();
    }

    public function indexStartedOrder()
    {
        return Order::where('grille_id', auth()->user()->id)
            ->where('canceled', 0)
            ->where('status', 'started')
            ->paginate();
    }

    public function indexFinishedOrder()
    {
        return Order::where('grille_id', auth()->user()->id)
            ->where('canceled', 0)
            ->where('status', 'finished')
            ->paginate();
    }

    public function canceledOrder(Request $request, $id)
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

        $order = Order::where('grille_id', Auth::user()->id)->where(function($query) {
            $query->where('status', 'new')->where('canceled', 0);
        })->findOrFail($id);

        if(!$order){
            return response()->json([
                'status' => false,
                'msg' => __('message.no_previous_request'),
            ], 401);
        }

        $order->update([
            'canceled' => 1,
            'status' => 'finished',
            'canceled_from' => 'grille',
            'reason_cancellation' => $request->reason_cancellation,
        ]);

        return response()->json([
            'status' => true,
            'msg' => __('message.success'),
        ], 200);

    }

    public function acceptableOrder(Request $request, $id)
    {
        $order = Order::where('grille_id', Auth::user()->id)->where(function($query) {
            $query->where('status', 'new')->where('canceled', 0);
        })->findOrFail($id);

        if(!$order){
            return response()->json([
                'status' => false,
                'msg' => __('message.no_previous_request'),
            ], 401);
        }

        $order->update([
            'acceptable' => 1,
            'status' => 'pending',
        ]);

        return response()->json([
            'status' => true,
            'msg' => __('message.success'),
        ], 200);

    }

    public function startedOrder(Request $request, $id)
    {
        $order = Order::where('grille_id', Auth::user()->id)->where(function($query) {
            $query->where('status', 'pending')->where('canceled', 0);
        })->findOrFail($id);

        if(!$order){
            return response()->json([
                'status' => false,
                'msg' => __('message.no_previous_request'),
            ], 401);
        }

        $order->update([
            'acceptable' => 1,
            'status' => 'started',
        ]);

        return response()->json([
            'status' => true,
            'msg' => __('message.success'),
        ], 200);

    }

    public function finishedOrder(Request $request, $id)
    {
        $order = Order::where('grille_id', Auth::user()->id)->where(function($query) {
            $query->where('status', 'started')->where('canceled', 0);
        })->findOrFail($id);

        if(!$order){
            return response()->json([
                'status' => false,
                'msg' => __('message.no_previous_request'),
            ], 401);
        }

        $order->update([
            'acceptable' => 1,
            'status' => 'finished',
        ]);

        return response()->json([
            'status' => true,
            'msg' => __('message.success'),
        ], 200);

    }

    public function detailsOrder($id)
    {
        $order = Order::where('grille_id', Auth::user()->id)->with('user')->findOrFail($id);

        if(!$order){
            return response()->json([
                'status' => false,
                'msg' => __('message.no_previous_request'),
            ], 401);
        }

        return response()->json([
            'status' => true,
            'order' => $order,
        ], 200);

    }

    public function notification()
    {
        return Order::where('grille_id', auth()->user()->id)
            ->where('status', 'new')
            ->with('user')
            ->OrderBy('created_at', 'DESC')
            ->paginate();
    }
}
