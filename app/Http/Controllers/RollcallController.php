<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Rollcall;
use App\Models\Period;

class RollcallController extends Controller
{
    // Danh sách học sinh trong 1 tiết học
    public function getStudents($periodId){
        $period = Period::findOrFail($periodId);

        $students = DB::table('class_students')
            ->join('students', 'students.id', '=', 'class_students.student_id')
            ->leftJoin('rollcalls', function ($join) use ($periodId) {
                $join->on('rollcalls.student_id', '=', 'students.id')
                    ->where('rollcalls.period_id', '=', $periodId);
            })
            ->where('class_students.class_id', $period->class_id)
            ->where('class_students.active', 1)
            ->select(
                'students.id',
                'students.name',
                'students.code',
                'students.gender',
                'rollcalls.is_absent'
            )
            ->orderBy('students.name')
            ->get();

        return response()->json([
            'period_id' => $periodId,
            'class_id' => $period->class_id,
            'total' => $students->count(),
            'students' => $students,
        ]);
    }

    // Điểm danh — gửi lên danh sách trạng thái
    public function store(Request $request, $periodId){
        $request->validate([
            'rollcalls' => 'required|array',
            'rollcalls.*.student_id' => 'required|integer',
            'rollcalls.*.is_absent' => 'required|integer|in:0,1',
        ]);

        $period = Period::findOrFail($periodId);

        foreach ($request->rollcalls as $item) {
            Rollcall::updateOrCreate(
                [
                    'period_id' => $periodId,
                    'student_id' => $item['student_id'],
                ],
                [
                    'is_absent' => $item['is_absent'],
                ]
            );
        }

        return response()->json(['message' => 'Điểm danh thành công']);
    }
}