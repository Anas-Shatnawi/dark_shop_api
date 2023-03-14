<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CommentController extends Controller
{
    public function addComment(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'comment' => 'required|max:255',
            'userId' => 'required|numeric|exists:users,id',
            'productId' => 'required|numeric|exists:products,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 400,
                'error' => $validator->errors(),
            ]);
        }

        $comment = Comment::create([
            'user_id' => $request->userId,
            'product_id' => $request->productId,
            'comment' => $request->comment,
        ]);

        return response()->json([
            'status' => 200,
            'message' => 'comment added successfully',
            'data' => $comment,
        ]);
    }

    public function deleteComment(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'userId' => 'required|numeric|exists:users,id',
            'commentId' => 'required|numeric|exists:comments,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 400,
                'error' => $validator->errors(),
            ]);
        }

        $comment = Comment::find($request->commentId)->where('user_id', $request->userId)->delete();

        if (!$comment) {
            return response()->json([
                'status' => 400,
                'error' => 'something wrong ',
                'message' => 'the user dose not have the permission to delete this comment',
            ]);
        }

        return response()->json([
            'status' => 200,
            'message' => "deleted successfully",
            'data' => $comment,
        ]);
    }

    public function getProductComment(Request $request)
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

        $productComments = Comment::where('product_id', $request->productId)->get();

        return response()->json([
            'status' => 200,
            'message' => 'all product comments',
            'data' => $productComments,
        ]);

    }
}
