<?php

namespace App\Http\Controllers;

use App\Mail\ResetEmail;
use App\Models\ResetPasswordCode;
use App\Models\User;
use DateTime;
use Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;

class ResetPasswordController extends Controller
{
    public function sendResetPasswordEmail(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:users,email',
        ]);

        if ($validator->fails()) {
            return respons()->json(['error' => $validator->errors()], 422);
        }

        $user = User::where('email', $request->email)->first();
        $code = strval(rand(10000, 99999));

        $user_codes = ResetPasswordCode::where('user_id', $user->id)->first();

        if (!$user_codes) {
            ResetPasswordCode::create([
                "user_id" => $user->id,
                "code" => Hash::make($code),
            ]);
        } else {
            $user_codes->code = Hash::make($code);
            $user_codes->save();
        }

        try {
            Mail::to($request->email)->send(new ResetEmail($user->name, $code));
        } catch (\Throwable$th) {
            return $th;
        }

        return response([
            'status' => 200,
            'message' => 'email sent check ur mail',
        ]);
    }
    public function checkCode(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'code' => 'required',
            'email' => 'required|exists:users,email',
        ]);

        if ($validator->fails()) {
            return respons()->json(['error' => $validator->errors()], 422);
        }

        $user = User::where('email', $request->email)->first();
        $code = ResetPasswordCode::where('user_id', $user->id)->first();

        $code_date = $code->updated_at;
        $current_date = new DateTime();

        $interval = $code_date->diff($current_date);
        $hours = $interval->h;

        if ($hours >= 1) {
            return response()->json([
                'status' => 400,
                'state' => 'the code expired',
            ]);
        } else {
            if (Hash::check($request->code,$code->code)) {
                return response()->json([
                    'status' => 200,
                    'state' => 'correct code',
                ]);
            } else {
                return response()->json([
                    "status" => 400,
                    "message" => "The Code Dosent Match"
                ]);
            }
        }
    }

    public function resetPassword(Request $request){
        $validator = Validator::make($request->all(),[
            'email' => 'required|exists:users,email',
            'password' => ['required', 'max:20',
                Password::min(8)
                    ->letters()
                    ->mixedCase()
                    ->numbers()
                    ->symbols()],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 400,
                'message' => $validator->errors()
            ]);
        }

        $user = User::where('email',$request->email)->first();

        if (!$user) {
            return response()->json([
                'status'=> 400,
                'message'=>'error user not found'
            ]);
        }

        $user->password = Hash::make($request->password);
        $user->save();

        return response()->json([
            'status' => 200,
            'message' => 'password updated successfully ,please login again',
            'data' => $user
        ]);
    }
}
