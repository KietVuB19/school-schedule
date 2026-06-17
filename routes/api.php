<?php
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/login', [AuthController::class, 'login']);
Route::middleware(['auth:sanctum', 'role:principal,school_admin'])->get('/dashboard/school', [DashboardController::class, 'school']);