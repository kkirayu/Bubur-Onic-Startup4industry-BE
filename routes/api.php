<?php

use App\Http\Controllers\Api\Auth\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Spatie\RouteDiscovery\Discovery\Discover;

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


Route::post('/auth/login', [AuthController::class, 'login']);

Route::middleware(['auth:sanctum'])->group(function () {
    Discover::controllers()->in(app_path('Http/Controllers/Api/Crud'));
    // Discover::controllers()->in(app_path('Http/Controllers/Api/Bpmn'));
});

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/auth/info', [AuthController::class, 'info']);
    Route::post('/auth/logout', [AuthController::class, 'logout']);
});

