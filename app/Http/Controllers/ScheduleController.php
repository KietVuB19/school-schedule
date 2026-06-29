<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Schedule;
use App\Models\Period;
use App\Models\Week;

class ScheduleController extends Controller
{
    // Giáo viên xem lịch báo giảng cá nhân trong 1 tuần
    public function mySchedule(Request $request){
        $user = $request->user();
        $weekId = $request->query('week_id');

        // mặc định lấy tuần hiện tại (nếu không có param week_id)
        if (!$weekId) {
            $week = Week::where('school_id', $user->school_id)
                ->whereDate('start', '<=', now())
                ->whereDate('end', '>=', now())
                ->first();

            if (!$week) {
                return response()->json(['message' => 'Không có tuần hiện tại'], 404);
            }
            $weekId = $week->id;
        }

        $schedule = Schedule::where('teacher_id', $user->id)
            ->where('week_id', $weekId)
            ->first();

        if (!$schedule) {
            return response()->json(['message' => 'Không có lịch trong tuần này'], 404);
        }

        $periods = DB::table('periods')
            ->join('classes', 'classes.id', '=', 'periods.class_id')
            ->leftJoin('subjects', 'subjects.id', '=', 'periods.subject_id')
            ->where('periods.schedule_id', $schedule->id)
            ->select(
                'periods.id',
                'periods.day',
                'periods.session',
                'periods.order',
                'periods.lesson_name',
                'periods.status',
                'classes.name as class_name',
                'subjects.name as subject_name'
            )
            ->orderBy('periods.day')
            ->orderBy('periods.session')
            ->orderBy('periods.order')
            ->get();

        return response()->json([
            'schedule_id' => $schedule->id,
            'week_id' => $weekId,
            'status' => $schedule->status,
            'periods' => $periods,
        ]);
    }

    // Giáo viên điền tên bài dạy vào 1 tiết
    public function updateLesson(Request $request, $id){
        $request->validate([
            'lesson_name' => 'required|string|max:255',
        ]);
        $period = Period::findOrFail($id);
        
        // Kiểm tra tiết này có thuộc schedule của giáo viên đang login không
        $schedule = Schedule::where('id', $period->schedule_id)
            ->where('teacher_id', $request->user()->id)
            ->first();

        if (!$schedule) {
            return response()->json(['message' => 'Bạn không có quyền sửa tiết này'], 403);
        }

        // Không cho sửa nếu đã duyệt
        if ($schedule->status == 3) {
            return response()->json(['message' => 'Lịch đã được duyệt, không thể sửa'], 403);
        }

        $period->update(['lesson_name' => $request->lesson_name]);

        return response()->json(['message' => 'Đã cập nhật bài dạy', 'period' => $period]);
    }

    // Giáo viên submit báo giảng cả tuần (Cập nhật trạng thái từ 1 (chưa gửi duyệt) -> 2 (chưa duyệt))
    public function submit(Request $request, $id){
        $schedule = Schedule::where('id', $id)
            ->where('teacher_id', $request->user()->id)
            ->firstOrFail();

        if ($schedule->status != 1) {
            return response()->json(['message' => 'Lịch báo giảng đã được gửi hoặc đã duyệt rồi'], 400);
        }

        $schedule->update(['status' => 2]);
        return response()->json(['message' => 'Đã gửi lịch báo giảng thành công']);
    }

    // Hiệu trưởng duyệt báo giảng
    public function approve(Request $request, $id){
        $schedule = Schedule::findOrFail($id);

        if ($schedule->status != 2) {
            return response()->json(['message' => 'Lịch chưa được gửi duyệt'], 400);
        }

        $schedule->update(['status' => 3]);

        return response()->json(['message' => 'Đã duyệt báo giảng']);
    }

}