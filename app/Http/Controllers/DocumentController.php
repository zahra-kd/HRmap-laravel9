<?php

namespace App\Http\Controllers;

use App\Models\Departement;
use App\Models\Document;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class DocumentController extends Controller
{
    public function requestDocument(Request $request){
        $validator = Validator::make($request->all(), [
            'document_type' => 'required|in:Work certificate,Salary Slip',
            'description' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 422);
        } else {
            try {
                if($validator){
                    Document::create([
                        'document_type' => $request->document_type,
                        'request_date' => Carbon::now(),
                        'description' => $request->description,
                        'status' => 'pending',
                        'user_id' => $request->user_id,
                        'created_at' => Carbon::now(),
                        'updated_at' => Carbon::now(),
                    ]);
                    return response()->json(["success" => " the request is sent"], 200);
                }
            } catch (Exception $e) {
                return response()->json(["error" => "something went wrong :".$e->getMessage()], 500);
            }
        }
    }

    public function allDocuments(){
        $departements = Departement::All();
        $documents = Document::orderBy('created_at', 'desc')->with('employee')->get();
        return response()->json(['documents' => $documents, 'departements' => $departements]);
    }

    public function pendingDocuments () {
        $pending = Document::orderBy('created_at', 'desc')->where("status", "pending")->with("employee")->get();
        $total = $pending->count();
        return response()->json(['pending' => $pending, 'total' => $total]);
    }

    public function validateDocument (Request $request, $id) {
        $document = Document::find($id);
        if($request->isMethod("post")){
            if ($request->filled(["status"])) {
                try {
                    DB::table('documents')
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

    public function getDocumentsByUser ($id) {
        try {
            $documents = Document::where("user_id", $id)->get();
            return response()->json(["documents" => $documents], 200);
        } catch (Exception $e) {
            return response()->json(["error" => "error: " . $e->getMessage()], 500);
        }
    }
}
