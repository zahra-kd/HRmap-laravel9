<?php

namespace App\Http\Controllers;

use App\Models\Departement;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class DepartementController extends Controller
{
    public function listDepartements(){
        $departements = Departement::with(["employees"])->get();
        $count = $departements->count();
        return response()->json(['departements' => $departements, 'total' => $count]);
    }

    public function createDepartement(Request $request){
        if($request->isMethod("post")){
            if($request->filled('name')){
                try {
                    Departement::create([
                        'name' => $request->name,
                        'created_at' => Carbon::now(),
                    ]);
                    return response()->json(["success" => "departement added"], 200);
                } catch (Exception $e) {
                    return response()->json(["error" => "error : " . $e->getMessage()], 404);
                }
            }
        }
    }
    // public function addHeadOfDepartment(Request $request, $id)
    // {
    //     $department = Departement::find($id);
    //     if (!$department) {
    //         return response()->json(["error" => "Department not found"], 404);
    //     }

    //     if ($request->isMethod("put")) {

    //             try {
    //                 $department->update([

    //                     'head_of_departement' => $request->head_of_departement, // new field
    //                 ]);
    //                 return response()->json(["success" => "Department updated"], 200);
    //             } catch (Exception $e) {
    //                 return response()->json(["error" => "error : " . $e->getMessage()], 404);
    //             }

    //     }
    // }
    public function deleteDepartement($id){
        try {
            Departement::find($id)->delete();
            return response()->json(["success" => "the departement is deleted"]);
        } catch(Exception $e) {
            return response()->json(["error" => "error : " . $e->getMessage()], 404);
        }
    }
}


