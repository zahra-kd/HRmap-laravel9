<?php

namespace App\Http\Controllers;

use App\Models\Departement;
use App\Models\Leave;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use PhpParser\Node\Stmt\TryCatch;

class LeaveController extends Controller
{
    public function createLeave(Request $request){
        $validator = Validator::make($request->all(), [
            'leave_type' => 'required|in:Sick Leave,Casual Leave,Maternity Leave,Unpaid Leave,Bereavement Leave',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
            'days' => 'required|numeric',
            'description' => 'required|string|max:255',
            'user_id' => 'required',
        ]);
        if($validator->fails()){
            return response()->json(['error' => $validator->messages()], 422);
        } else {
            if($validator) {
                try {
                    $picture = null;
                    if($request->hasFile('picture') && $request->file('picture')->isValid()) {
                        $picture = 'storage/' . $request->picture->store('user/images');
                    }
                    Leave::create([
                        'leave_type' => $request->leave_type,
                        'start_date' => $request->start_date,
                        'end_date' => $request->end_date,
                        'days' => $request->days,
                        'description' => $request->description,
                        'file' => $picture,
                        'user_id' => $request->user_id,
                        'status' => 'pending',
                        'created_at' => Carbon::now(),
                        'updated_at' => Carbon::now(),
                    ]);
                    return response()->json(["success" => " the request is sent"], 200);
                } catch (Exception $e){
                    return response()->json(["error" => "something went wrong :".$e->getMessage()], 500);
                }
            }
        }
    }

    public function allLeaves (){
        $departements = Departement::All();
        $leaves = Leave::orderBy('created_at', 'desc')->with("employee")->get();
        return response()->json(['leaves' => $leaves, 'departements' => $departements]);
    }

    public function pendingLeaves () {
        $pending = Leave::orderBy('created_at', 'desc')->where("status", "pending")->with("employee")->get();
        $total = $pending->count();
        return response()->json(['pending' => $pending, 'total' => $total]);
    }

    public function validateLeave (Request $request, $id) {
        $leave = Leave::find($id);
        if($request->isMethod("post")){
            if ($request->filled(["status"])) {
                try {
                    $leave = DB::table('leaves')
                        ->where('id', $id)
                        ->update([
                            'status' => $request->status
                        ]);
                    return response()->json(["success" => "the request is treated successfully"], 200);
                } catch (Exception $e) {
                    return response()->json(["error" => "something went wrong:" . $e->getMessage()], 500);
                }
            }
        } else {
            return response()->json(["error" => "Please select a status"], 500);
        }
    }

    public function getLeavesByUser($id){
        try {
            $leaves = Leave::where('user_id', $id)->get();
            return response()->json(["leaves" => $leaves], 200);
        } catch (Exception $e) {
            return response()->json(['error' => "error: " . $e->getMessage()], 400);
        }

    }


    public function deleteLeave ($id) {
        try {
            Leave::find($id)->delete();
        } catch (Exception $e) {
            return response()->json(["error" => "error : " . $e->getMessage()], 404);
        }
    }
}
