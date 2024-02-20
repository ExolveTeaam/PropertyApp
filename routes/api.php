<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\InspectionRequestController;
use App\Http\Controllers\RegistrationController;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::post('register', [RegistrationController::class, 'RegisterUser']);
Route::post('login', [AuthController::class, 'Login']);
Route::middleware(["api"])->group(function(){
    Route::post('logout', [AuthController::class, 'Logout']);
    Route::prefix("request")->group(function(){
        Route::post("new",[InspectionRequestController::class,'CreateInspectionRequest']);
    });
});

