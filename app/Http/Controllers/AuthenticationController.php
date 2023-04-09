<?php

namespace App\Http\Controllers;

use App\Mail\ResetPassword;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Closure;
use Exception;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use PhpParser\Node\Stmt\TryCatch;

class AuthenticationController extends Controller
{
    // public function login(Request $request)
    // {
    //     $credentials = $request->validate([
    //         'email' => ['required', 'email'],
    //         'password' => ['required'],
    //     ]);

    //     if (Auth::attempt($credentials)) {
    //             return response()->json(["user" => Auth::user()], 200);
    //     } else {
    //         return response()->json(["error" => "user not found"], 404);
    //     }
    // }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            if (Auth::user()->status == "inactive") {
                return response()->json(["error" => "Your account has been blocked"], 400);
                // return redirect()->back()->with('error', 'Your account has been blocked. Please contact support for assistance.');
            }
            return response()->json(["user" => Auth::user()], 200);
        }
        return response()->json(['error' => 'user not found'], 400);
    }


    public function forgotPassword(Request $request) {
        try {
            $query = User::where("email", $request->email)->getQuery();
            if($query->exists()){
                $user = $query->first();
                $token = md5($request->email);
                DB::table("password_reset_tokens")->insert([
                    "email" => $request->email,
                    "token" => $token,
                    "created_at" => Carbon::now()
                ]);
                Mail::to($request->email)->send(new ResetPassword($user->first_name, $token));
                return response()->json(["success" => "email sent. check your mail box"], 200);
            } else {
                return response()->json(["error" => "user not found"], 400);
            }
        } catch (Exception $e) {
            return response()->json(["error" => "email could not be sent".$e->getMessage()], 500);
        }
    }

    public function resetPassword(Request $request, $token)
    {
        if ($request->isMethod('post')) {
            $query = DB::table('password_reset_tokens')->where("token", $token);
            if ($query->count() == 1) {
                $passwordResets = $query->first();
                $user = User::where("email", $passwordResets->email)->first();
                try {
                    User::where("id", $user->id)->update(["password" => Hash::make($request->password)]);
                    DB::table('password_reset_tokens')->where("token", $token)->delete();
                    return response()->json(["success" => "password changed successfully"]);
                } catch (Exception $e) {
                    return response()->json(["error" => "an error has occured" . $e->getMessage()], 500);
                }
            }
        }

    }
    // public function logout(Request $request)
    // {
    //     Auth::logout();
    //     $request->session()->invalidate();
    //     $request->session()->regenerateToken();
    //     return response()->json();
    // }
}
