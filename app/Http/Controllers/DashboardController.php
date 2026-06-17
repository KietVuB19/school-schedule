<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Week;
use App\Models\Period;
use Carbon\Carbon;

use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function school(Request $request)
    {
        $user = $request->user();
        $schoolId = $user->school_id;

        // Lấy tuần hiện tại -> vào ngày hôm nay giữa start và end
        $today = Carbon::today();
        $week = Week::where('school_id', $schoolId)
            ->whereDate('start', '<=', $today)
            ->whereDate('end', '>=', $today)
            ->first();

        if (!$week) {
            return response()->json(['message' => 'Không có tuần hiện tại'], 404);
        }

        // Lấy thứ hiện tại (0 = thứ 2, 6 = chủ nhật)
        $dayOfWeek = $today->dayOfWeekIso - 1; // Carbon: 1=Mon...7=Sun -> chuyển về 0-6

        // Lấy danh sách tiết học trong ngày của toàn bộ lớp trong trường 
        // "SELECT * FROM periods 
        //             JOIN schedules ON schedules.id = periods.schedule_id
        //             JOIN classes ON classes.id = periods.class_id               
        //             LEFT JOIN subjects ON subjects.id = periods.subject_id
        //             LEFT JOIN users ON users.id = schedules.teacher_id
        //             JOIN weeks ON weeks.id = schedules.week_id
        //         WHERE schedules.week_id = %d AND periods.day = %d AND classes.school_id = %d";
        
        $periods = DB::table('periods')
            ->join('schedules', 'schedules.id', '=', 'periods.schedule_id')
            ->join('classes', 'classes.id', '=', 'periods.class_id')
            ->leftJoin('subjects', 'subjects.id', '=', 'periods.subject_id')
            ->leftJoin('users', 'users.id', '=', 'schedules.teacher_id')
            ->where('schedules.week_id', $week->id)
            ->where('periods.day', $dayOfWeek)
            ->where('classes.school_id', $schoolId)
            ->select(
                'periods.id',
                'periods.session',
                'periods.lesson_name',
                'periods.status',
                'classes.name as class_name',
                'subjects.name as subject_name',
                'users.name as teacher_name'
            )
            ->orderBy('periods.session')
            ->orderBy('classes.name')
            ->get();


        return response()->json([
            'week' => $week,
            'day' => $dayOfWeek,
            'periods' => $periods,
        ]);
    }
}