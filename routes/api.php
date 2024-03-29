<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\InspectorController;
use App\Http\Controllers\RegistrationController;
use App\Http\Controllers\InspectionRequestController;

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
Route::post('flutterwave-callback',[InspectionRequestController::class,'VerifyInspectionRequest']);
Route::middleware(["auth:api"])->group(function(){
    Route::get('settings',[SettingsController::class,'GetcontactDetials']);
    Route::post('logout', [AuthController::class, 'Logout']);
    Route::middleware(["must.be.propertymanager"])->prefix("request")->group(function(){
        Route::post("new",[InspectionRequestController::class,'CreateInspectionRequest']);
    });
    Route::prefix("profile")->group(function(){
        Route::patch("change-password",[ProfileController::class,'ChangePassword']);
        Route::patch("update-profile",[ProfileController::class,'UpdateProfile']);
    });
    Route::middleware(["must.be.inspector"])->prefix("reports")->group(function(){
        Route::post("create",[InspectorController::class,'CreateReport']);
        Route::get("dashboard",[InspectorController::class,'DashboardRequests']);
        Route::get("getreports",[InspectorController::class,'GetReports']);
        Route::get("getreport/{id}",[InspectorController::class,'GetReport'])->where('id','[0-9]+');
        Route::post("updatereportstatus/{id}",[InspectorController::class,'ChangeInspectionRequestStatus'])->where('id','[0-9]+');
    });

    Route::middleware(["must.be.admin"])->prefix("admin")->group(function(){
        Route::get("dashboard",[AdminController::class,'AdminDashboard']);
        Route::get("payment-dashboard",[AdminController::class,'AdminPaymentDashboard']);
        Route::get("inspections",[AdminController::class,'Inspections']);
        Route::get("getinspectors",[AdminController::class,'Inspectors']);
        Route::post("assigninspector/{id}",[AdminController::class,'AssignInspector'])->where('id','[0-9]+');
    });
});

