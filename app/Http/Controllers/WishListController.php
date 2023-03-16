<?php

namespace App\Http\Controllers;

use App\Models\WishList;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class WishListController extends Controller
{
    public function addRemoveWishList(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'userId' => 'required|numeric|exists:users,id',
            'productId' => 'required|numeric|exists:products,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 400,
                'error' => $validator->errors(),
            ]);
        }

        $wishList = WishList::where('user_id', $request->userId)
            ->where('product_id', $request->productId)->get();

        if (count($wishList) > 0) {
            $wishList->each->delete();

            return response()->json([
                'status' => 200,
                'message' => "removed from wish list successfully",
            ]);
        } else {
            $wishList = WishList::create([
                'user_id' => $request->userId,
                'product_id' => $request->productId,
            ]);

            return response()->json([
                'status' => 200,
                'message' => 'product added successfully to wish list',
                'data' => $wishList,
            ]);
        }
    }

    public function geUsertWishList(Request $request)
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

        $wishList = WishList::where('user_id', $request->userId)->get();

        return response()->json([
            'status' => 200,
            'message' => 'all user wish list',
            'data' => $wishList,
        ]);
    }
}
