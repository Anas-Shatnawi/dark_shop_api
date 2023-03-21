<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CartController extends Controller
{
    public function addToCart(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'userId' => 'required|numeric|exists:users,id',
            'productId' => 'required|numeric|exists:products,id',
            'quantity' => 'required|numeric|min:1',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 400,
                'message' => $validator->errors(),
            ]);
        }

        $cart = Cart::where('user_id', $request->userId)->where('product_id', $request->productId)->first();

        if ($cart) {
            $updateCart = Cart::where('id', $cart->id)->update([
                'quantity' => $request->quantity + $cart->quantity,
            ]);

            return response()->json([
                'status' => 200,
                'message' => "updated quantity successfully",
            ]);

        } else {
            $createCart = Cart::create([
                'user_id' => $request->userId,
                'product_id' => $request->productId,
                'quantity' => $request->quantity,
            ]);
            return response()->json([
                'status' => 200,
                'message' => "added to cart",
                'data' => $createCart,
            ]);
        }
    }

    public function getUserCart(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'userId' => 'required|numeric|exists:users,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 400,
                'error' => $validator->errors(),
            ]);
        }

        $cartItems = Cart::where('user_id', $request->userId)->get();
        $totalPrice = 0;

        foreach ($cartItems as $item) {
            $totalPrice += $item->quantity * $item->product->price;
        }

        return response()->json([
            'status' => 200,
            'message' => "user cart",
            'data' => ['cartItems' => $cartItems, 'totalPrice' => $totalPrice],
        ]);

    }

    public function removeFromUserCart(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'cartId' => 'required|numeric|exists:cart,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 400,
                'error' => $validator->errors(),
            ]);
        }

        $cart = Cart::where('id', $request->cartId)->delete();

        return response()->json([
            'status' => 200,
            'message' => "item has been deleted",
        ]);
    }

    public function emptyUserCart(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'userId' => 'required|numeric|exists:cart,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 400,
                'error' => $validator->errors(),
            ]);
        }

        $cart = Cart::where('user_id', $request->userId)->delete();

        return response()->json([
            'status' => 200,
            'message' => "The cart has been emptied successfully",
        ]);

    }
}
