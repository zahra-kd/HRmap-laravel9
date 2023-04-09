<?php

use App\Http\Controllers\AuthenticationController;
use App\Http\Controllers\DepartementController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\LeaveController;
use App\Http\Controllers\UserController;
use App\Models\Document;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::get('/departements', [DepartementController::class, 'listDepartements']);
Route::post('/departements', [DepartementController::class, 'createDepartement']);
Route::delete('/delete-departement/{id}', [DepartementController::class, 'deleteDepartement']);

Route::post('/add-user', [UserController::class, 'addUser']);
Route::get('/users', [UserController::class, 'listUsers']);
Route::get('/user-details/{id}', [UserController::class, 'userDetails']);
Route::post('/update-user/{id}', [UserController::class, 'adminUpdate']);
Route::post('/update-profile/{id}', [UserController::class, 'userUpdate']);

Route::post('/login', [AuthenticationController::class, 'login']);
Route::post('/forgot-password', [AuthenticationController::class, 'forgotPassword']);
Route::post('/reset-password/{token}', [AuthenticationController::class, 'resetPassword']);

Route::post('/create-leave', [LeaveController::class, 'createLeave']);
Route::get('/leaves', [LeaveController::class, 'allLeaves']);
Route::get('/pending-leaves', [LeaveController::class, 'pendingLeaves']);
Route::post('/validate-leave/{id}', [LeaveController::class, 'validateLeave']);
Route::get('/get-leaves-by-user/{id}', [LeaveController::class, 'getLeavesByUser']);

Route::post('/request-document', [DocumentController::class, 'requestDocument']);
Route::get('/documents', [DocumentController::class, 'allDocuments']);
Route::post('/validate-document/{id}', [DocumentController::class, 'validateDocument']);
Route::get('/get-documents-by-user/{id}', [DocumentController::class, 'getDocumentsByUser']);
Route::get('/pending-documents', [DocumentController::class, 'pendingDocuments']);





