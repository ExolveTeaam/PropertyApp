<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RegistrationController;
use App\Http\Controllers\InspectionRequestController;
use App\Http\Controllers\InspectorController;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});


Route::post('register', [RegistrationController::class, 'RegisterUser']);
Route::post('login', [AuthController::class, 'Login']);
Route::middleware(["auth:api"])->group(function(){
    Route::post('logout', [AuthController::class, 'Logout']);
    Route::middleware(["must.be.propertymanager"])->prefix("request")->group(function(){
        Route::post("new",[InspectionRequestController::class,'CreateInspectionRequest']);
        Route::patch("verifypayment",[InspectionRequestController::class,'VerifyInspectionRequest']);
    });
    Route::prefix("profile")->group(function(){
        Route::patch("change-password",[ProfileController::class,'ChangePassword']);
        Route::patch("update-profile",[ProfileController::class,'UpdateProfile']);
    });
    Route::middleware(["must.be.inspector"])->prefix("reports")->group(function(){
        Route::post("create",[InspectorController::class,'CreateReport']);
        Route::get("dashboard",[InspectorController::class,'DashboardRequests']);
        Route::get("getreports",[InspectorController::class,'GetReports']);
        Route::get("getreport/{id}",[InspectorController::class,'GetReport']);
        Route::post("updatereportstatus/{id}",[InspectorController::class,'ChangeInspectionRequestStatus']);
    });
});

