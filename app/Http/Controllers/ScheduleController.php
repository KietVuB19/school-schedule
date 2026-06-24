<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Schedule;
use App\Models\Period;
use App\Models\Week;

class ScheduleController extends Controller
{
    // Lịch báo giảng cá nhân trong 1 tuần
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


}