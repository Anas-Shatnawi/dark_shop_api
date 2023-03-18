<?php

namespace App\Http\Controllers;

use App\Models\Favorite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class FavoritesController extends Controller
{
    public function addRemoveFavorites(Request $request)
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

        $favorite = Favorite::where('user_id', $request->userId)
            ->where('product_id', $request->productId)->get();

        if (count($favorite) > 0) {
            $favorite->each->delete();

            return response()->json([
                'status' => 200,
                'message' => "removed from favorites list successfully",
            ]);
        } else {
            $favorite = Favorite::create([
                'user_id' => $request->userId,
                'product_id' => $request->productId,
            ]);

            return response()->json([
                'status' => 200,
                'message' => 'product added successfully to favorites list',
                'data' => $favorite,
            ]);
        }
    }

    public function geUsertFavorites(Request $request)
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

        $favorite = Favorite::where('user_id', $request->userId)->get();

        return response()->json([
            'status' => 200,
            'message' => 'all user favorites list',
            'data' => $favorite,
        ]);
    }
}
