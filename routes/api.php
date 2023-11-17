<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Spatie\RouteDiscovery\Discovery\Discover;
use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ConfirmPasswordController;
use App\Http\Controllers\Api\Crud\Pegawai\KasbonBulananController;

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

Route::post('pegawai/kasbon-bulanan/{id}/update_status', [KasbonBulananController::class, 'updateStatus']);

Route::post('/auth/login', [AuthController::class, 'login']);
Route::post('/auth/register', [AuthController::class, 'register']);
Route::prefix('/auth/password')->group(function () {
    Route::post('/email', [ForgotPasswordController::class, 'sendResetLinkEmail']);
    Route::post('/reset', [ResetPasswordController::class, 'reset']);
});

Route::middleware(['auth:sanctum'])->group(function () {
    Discover::controllers()->in(app_path('Http/Controllers/Api/Crud'));
    Discover::controllers()->in(app_path('Http/Controllers/Api/Bpmn'));
});

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/auth/info', [AuthController::class, 'info']);
    Route::post('/auth/logout', [AuthController::class, 'logout']);
    Route::post('/auth/password/confirm', [AuthController::class, 'confirm']);
});

