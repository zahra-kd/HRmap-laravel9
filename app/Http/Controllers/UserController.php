<?php

namespace App\Http\Controllers;

use App\Mail\WelcomeMail;
use App\Models\User;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function addUser(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:30',
            'last_name' => 'required|string|max:30',
            'gender' => 'required|in:male,female',
            'birth_date' => 'required|date',
            'nationality' => 'required|string|max:30',
            'national_id' => 'required|string|max:20',
            'phone' => 'required|digits:10',
            'email' => 'required|email|max:40',
            'password' => 'required|min:6',
            'situation' => 'required|in:single,married,divorced,widowed',
            'spouse_name' => 'nullable|string|max:30',
            'children' => 'nullable|integer',
            'job_title' => 'required|string|max:30',
            'departement_id' => 'required',
            'date_of_joining' => 'required|date',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 422);
        } else {
            if ($validator) {
                try {
                    $picture = null;
                    if ($request->hasFile('picture') && $request->file('picture')->isValid()) {
                        $picture = 'storage/' . $request->picture->store('user/images');
                    }
                    User::create([
                        'first_name' => $request->first_name,
                        'last_name' => $request->last_name,
                        'gender' => $request->gender,
                        'birth_date' => $request->birth_date,
                        'nationality' => $request->nationality,
                        'national_id' => $request->national_id,
                        'picture' => $picture,
                        'phone' => $request->phone,
                        'email' => $request->email,
                        'password' => Hash::make($request->password),
                        'situation' => $request->situation,
                        'spouse_name' => $request->spouse_name,
                        'children' => $request->children,
                        'job_title' => $request->job_title,
                        'departement_id' => $request->departement_id,
                        'date_of_joining' => $request->date_of_joining,
                        'role' => 'user',
                        'status' => 'active',
                    ]);
                    try {
                        Mail::to($request->email)->send(new WelcomeMail($request->first_name, $request->email, $request->password));
                        return response()->json(["success" => "employee created successfully"], 200);
                    } catch(Exception $e) {
                        return response()->json(["error" => "something went wrong :" . $e->getMessage()], 500);
                    }
                } catch (Exception $e) {
                    return response()->json(["error" => "something went wrong :" . $e->getMessage()], 500);
                }
            }
        }
    }


    public function listUsers()
    {
        $users = User::orderBy('created_at', 'desc')->with('departement')->get();
        $total = $users->count();
        return response()->json(['users' => $users, 'total' => $total]);
    }


    public function adminUpdate(Request $request, $id)
    {
        $user = User::find($id);
        if ($request->isMethod("post")) {
            if ($request->filled(["job_title", "departement_id", "date_of_joining"])) {
                try {
                    DB::table('users')
                        ->where('id', $id)
                        ->update([
                            'job_title' => $request->job_title,
                            'departement_id' => $request->departement_id,
                            'date_of_joining' => $request->date_of_joining,
                            'date_of_leaving' => $request->date_of_leaving == "null" ? null : $request->date_of_leaving,
                            'status' => $request->status,
                        ]);
                    return response()->json(["success" => "employee's data is updated"], 200);
                } catch (Exception $e) {
                    return response()->json(["error" => "something went wrong :" . $e->getMessage()], 500);
                }
            } else {
                return response()->json(["error" => "input field is empty"], 500);
            }
        }
    }

    public function userUpdate(Request $request, $id)
    {
        $user = User::find($id);
        if ($request->isMethod("post")) {
            if ($request->filled(["phone", "email", "situation", "spouse_name", "children"])) {
                $picture = $user->picture;
                if ($request->hasFile("picture") && $request->picture->isValid()) {
                    $picture = "storage/" . $request->picture->store('user/images');
                }
                try {
                    DB::table('users')
                        ->where('id', $id)
                        ->update([
                            'phone' => $request->phone,
                            'email' => $request->email,
                            'password' => $request->password ? hash::make($request->password) : $user->password,
                            'picture' => $picture,
                            'situation' => $request->situation,
                            'spouse_name' => $request->spouse_name,
                            'children' => $request->children,
                        ]);
                    $user = User::find($id);
                    return response()->json(["success" => "your data is updated","user" => $user], 200);
                } catch (Exception $e) {
                    return response()->json(["error" => "something went wrong :" . $e->getMessage()], 500);
                }
            } else {
                return response()->json(["error" => "input field is empty"], 500);
            }
        }
    }


    public function userDetails($id){
        $user = User::find($id);
        return response()->json(["user" => $user]);
    }
}
