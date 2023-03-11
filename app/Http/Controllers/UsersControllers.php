<?php

namespace App\Http\Controllers;

use App\Models\User;
use Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;

class UsersControllers extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email',
            'phone' => 'required|min:8|max:20',
            'password' => ['required', 'min:8', 'max:20',
                Password::min(8)
                    ->letters()
                    ->mixedCase()
                    ->numbers()
                    ->symbols()],
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        $user = new User([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
        ]);

        if ($request->file('user_image')) {
            $filename = uniqid() . '.' . $request->file('user_image')->getClientOriginalExtension();
            $path = $request->file('user_image')->storeAs('public/usersImages', $filename);
            $user->image = url('/') . '/' . $path;
        }

        $user->save();

        // attach user role

        if ($request->role && $request->role == 'store') {
            $user->attachRole('store');
        } else {
            $user->attachRole('user');
        }

        $token = $user->createToken('my-api-token')->plainTextToken;

        return response([
            'status' => 200,
            'message' => 'registered Successfully ',
            'data' => $user,
            'token' => $token,
        ]);

    }

    public function login(Request $request)
    {
        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'message' => ['These credentials do not match our records.'],
            ], 404);
        }

        $token = $user->createToken('my-api-token')->plainTextToken;

        $response = [
            'user' => $user,
            'token' => $token,
        ];

        return response($response, 201);
    }

    public function getUserDetails(Request $request)
    {
        $user = User::find($request->userId);

        if (!$user) {
            return response([
                'status' => 404,
                'message' => 'user not found',
            ]);
        }

        return response([
            'status' => 200,
            'message' => 'user detals',
            'data' => $user,
        ]);
    }

    public function updateUserDetails(Request $request)
    {
        $id = $request->userId;

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $id,
            'user_image' => 'image|mimes:jpeg,png,jpg|max:2048',
            'phone' => 'required|min:8|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        $user = User::findOrFail($id);

        $user->name = $request->name;
        $user->email = $request->email;
        $user->phone = $request->phone;

        if ($request->file('user_image')) {
            $filename = uniqid() . '.' . $request->file('user_image')->getClientOriginalExtension();
            $path = $request->file('user_image')->storeAs('public/usersImages', $filename);
            $user->image = url('/') . '/' . $path;
        }

        $user->save();

        return response([
            'status' => 200,
            'message' => 'user detals',
            'data' => $user,
        ]);
    }

    public function getStores()
    {
        $stores = User::whereRoleIs('store')->orderBy('id', 'DESC')->limit(100)->get();
        
        return response([
            'status' => 200,
            'message' => 'all stores',
            'data' => $stores,
        ]);

    }
}
