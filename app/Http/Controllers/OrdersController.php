<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderDetails;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Stripe\Charge;
use Stripe\Stripe;
use Stripe\Token;

class OrdersController extends Controller
{
    public function makeOrder(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'userId' => 'numeric|exists:cart,user_id',
            'amount' => 'required|numeric|min:0.01',
            'card' => 'required|string|size:16',
            'expMonth' => 'required|string|size:2',
            'expYear' => 'required|string|size:4',
            'cvc' => 'required|string|size:3',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 400,
                'error' => $validator->errors(),
            ]);
        }

        $userId = $request->userId;

        Stripe::setApiKey(env('STRIPE_SECRET'));

        try {
            $token = Token::create([
                'card' => [
                    'number' => $request->card,
                    'exp_month' => $request->expMonth,
                    'exp_year' => $request->expYear,
                    'cvc' => $request->cvc,
                ],
            ]);
        } catch (\Throwable$th) {
            return response()->json([
                'status' => 400,
                'error' => $th->getMessage(),
            ]);
        }

        $amount = $request->amount;

        try {
            $charge = Charge::create([
                'amount' => $amount,
                'currency' => 'usd',
                'description' => 'Order Payment',
                'source' => $token,
            ]);
        } catch (\Throwable$th) {
            return response()->json([
                'status' => 400,
                'error' => $th->getMessage(),
            ]);
        }

        if ($charge->status === 'succeeded') {

            $cartProducts = Cart::where('user_id', $userId)->get();
            $orderCreate = Order::create([
                'user_id' => $userId,
                'total_price' => $amount,
            ]);

            foreach ($cartProducts as $product) {
                $orderDetails = OrderDetails::create([
                    'product_id' => $product->product_id,
                    'order_id' => $orderCreate->id,
                    'quantity' => $product->quantity,
                ]);
            }

            Cart::where('user_id', $userId)->delete();

            return response()->json([
                'status' => 200,
                'message' => "payment success , order placed",
            ]);
        } else {
            return response()->json([
                'status' => 200,
                'message' => "Payment Failed",
            ]);
        }
    }

    public function getUserOrders(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'userId' => 'numeric|exists:orders,user_id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 400,
                'error' => $validator->errors(),
            ]);
        }

        $userOrders = Order::where('user_id', $request->userId)->get();

        return response()->json([
            'status' => 200,
            'message' => "all users orders",
            'data' => $userOrders
        ]);

    }
}
