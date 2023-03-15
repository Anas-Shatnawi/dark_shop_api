<?php

namespace App\Http\Controllers;

use App\Models\Like;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class LikesController extends Controller
{
    public function addDeleteLike(Request $request)
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

        $like = Like::where('user_id', $request->userId)
            ->where('product_id', $request->productId)->get();

        if (count($like) > 0) {
            $like->each->delete();

            return response()->json([
                'status' => 200,
                'message' => "deleted successfully"
            ]);
        } else {
            $like = Like::create([
                'user_id' => $request->userId,
                'product_id' => $request->productId,
            ]);

            return response()->json([
                'status' => 200,
                'message' => 'like added successfully',
                'data' => $like,
            ]);
        }
    }

    public function getProductLikes(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'productId' => 'required|numeric|exists:products,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 400,
                'error' => $validator->errors(),
            ]);
        }

        $productLikes = Like::where('product_id', $request->productId)->get();

        return response()->json([
            'status' => 200,
            'message' => 'all product comments',
            'data' => $productLikes,
        ]);

    }
}
