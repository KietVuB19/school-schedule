<?php
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ClassController;
use App\Http\Controllers\ScheduleController;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/login', [AuthController::class, 'login']);

Route::middleware(['auth:sanctum', 'role:principal,school_admin'])->get('/dashboard/school', [DashboardController::class, 'school']);

// Admin/HIệu trưởng
Route::middleware(['auth:sanctum', 'role:school_admin,principal'])->group(function () {
    Route::get('/classes', [ClassController::class, 'index']);
    Route::post('/classes', [ClassController::class, 'store']);
    Route::put('/classes/{id}', [ClassController::class, 'update']);
    Route::delete('/classes/{id}', [ClassController::class, 'destroy']);
});

// Giáo viên
Route::middleware(['auth:sanctum', 'role:teacher'])->group(function () {
    // kiểm tra lịch báo giảng
    Route::get('/schedule/my', [ScheduleController::class, 'mySchedule']);
    // cập nhật lịch báo giảng (thêm tên bài dạy) - tạm thời để string
});