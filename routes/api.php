<?php
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ClassController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\RollcallController;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/login', [AuthController::class, 'login']);
Route::middleware('auth:sanctum')->post('/logout', [AuthController::class, 'logout']);

// Admin
Route::middleware(['auth:sanctum', 'role:super_admin'])->group(function () {
    // tạo trường
    // tạo tài khoản hiệu trưởng
});

// HIệu trưởng
Route::middleware(['auth:sanctum', 'role:principal'])->group(function () {
    // check dashboard
    Route::get('/dashboard/school', [DashboardController::class, 'school']);
    // quanr lý lớp (xem, thêm, sửa, xóa)
    Route::get('/classes', [ClassController::class, 'index']);
    Route::post('/classes', [ClassController::class, 'store']);
    Route::put('/classes/{id}', [ClassController::class, 'update']);
    Route::delete('/classes/{id}', [ClassController::class, 'destroy']);
    // duyệt lịch báo giảng
    Route::put('/schedule/{id}/approve', [ScheduleController::class, 'approve']);

    // tạo tài khoản hiệu trưởng (TODO)
});

// Giáo viên
Route::middleware(['auth:sanctum', 'role:teacher'])->group(function () {
    // kiểm tra lịch báo giảng
    Route::get('/schedule/my', [ScheduleController::class, 'mySchedule']);
    // cập nhật lịch báo giảng (thêm tên bài dạy) - tạm thời để string
    Route::put('/periods/{id}/lesson', [ScheduleController::class, 'updateLesson']);
    // gửi duyệt lịch báo giảng
    Route::put('/schedule/{id}/submit', [ScheduleController::class, 'submit']);

    // điểm danh
    Route::get('/periods/{id}/students', [RollcallController::class, 'getStudents']);
    Route::post('/periods/{id}/rollcall', [RollcallController::class, 'store']);
});