<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Week;
use App\Models\Schedule;
use App\Models\Classes;
use App\Models\Subject;
use App\Models\User;
use Carbon\Carbon;

class SchoolDataSeeder extends Seeder
{
    public function run(): void
    {
        $schoolId = 1;

        // Tạo 4 giáo viên - mỗi giáo viên phụ trách 1 khối
        $teacherNames = ['Thầy Hòa', 'Thầy Ngọc', 'Cô Lan', 'Cô Mai'];
        $teachers = [];
        foreach ($teacherNames as $i => $name) {
            $teachers[] = User::firstOrCreate(
                ['email' => 'teacher' . ($i + 1) . '@test.com'],
                [
                    'name' => $name,
                    'password' => bcrypt('123456'),
                    'role' => 'teacher',
                    'school_id' => $schoolId,
                ]
            );
        }

        // Tạo 12 lớp: khối 6-9, mỗi khối 3 lớp A/B/C
        $grades = [6, 7, 8, 9];
        $sections = ['A', 'B', 'C'];
        $classesByGrade = [];

        foreach ($grades as $gIndex => $grade) {
            $classesByGrade[$grade] = [];
            foreach ($sections as $section) {
                $class = Classes::firstOrCreate(
                    ['name' => $grade . $section, 'school_id' => $schoolId, 'grade' => $grade],
                    [
                        'homeroom_teacher_id' => $teachers[$gIndex]->id,
                        'public' => 1,
                    ]
                );
                $classesByGrade[$grade][] = $class;
            }
        }

        // Tạo 3 môn học cho mỗi khối: Toán, Văn, Anh
        $subjectNames = ['Toán', 'Ngữ Văn', 'Tiếng Anh'];
        $subjectCodes = ['toan', 'van', 'anh'];
        $subjectsByGrade = [];

        foreach ($grades as $grade) {
            $subjectsByGrade[$grade] = [];
            foreach ($subjectNames as $i => $name) {
                $subject = Subject::firstOrCreate(
                    ['name' => $name, 'grade' => $grade],
                    ['code' => $subjectCodes[$i] . $grade, 'public' => 1]
                );
                $subjectsByGrade[$grade][] = $subject;
            }
        }

        // Tạo tuần từ 01/06/2026 đến 28/05/2027 + schedule cho mỗi giáo viên
        $start = Carbon::create(2026, 6, 1);
        $endBoundary = Carbon::create(2027, 5, 28);
        $order = 1;
        $now = Carbon::now();
        $periodRows = [];

        while ($start->lte($endBoundary)) {
            $end = $start->copy()->addDays(6);

            $week = Week::create([
                'name' => "Tuần {$order} từ " . $start->format('d/m/Y') . " đến " . $end->format('d/m/Y'),
                'order' => $order,
                'start' => $start->format('Y-m-d'),
                'end' => $end->format('Y-m-d'),
                'school_id' => $schoolId,
                'year' => 2026,
            ]);

            // Mỗi giáo viên có 1 schedule cho tuần này
            $schedules = [];
            foreach ($teachers as $teacher) {
                $schedules[] = Schedule::create([
                    'teacher_id' => $teacher->id,
                    'week_id' => $week->id,
                    'status' => 1,
                    'public' => 1,
                ]);
            }

            // Chỉ tạo periods chi tiết cho 5 tuần đầu
            if ($order <= 5) {
                foreach ($grades as $gIndex => $grade) {
                    $schedule = $schedules[$gIndex]; // giáo viên phụ trách khối này

                    foreach ($classesByGrade[$grade] as $class) {
                        for ($day = 0; $day <= 4; $day++) { // thứ 2 - thứ 6
                            for ($session = 1; $session <= 2; $session++) { // 1: sáng, 2: chiều
                                for ($per = 1; $per <= 5; $per++) { // tiết 1-5
                                    $periodIndex = ($session - 1) * 5 + ($per - 1);
                                    $subject = $subjectsByGrade[$grade][$periodIndex % 3];

                                    $periodRows[] = [
                                        'schedule_id' => $schedule->id,
                                        'class_id' => $class->id,
                                        'subject_id' => $subject->id,
                                        'day' => $day,
                                        'session' => $session,
                                        'order' => $per,
                                        'lesson_name' => null,
                                        'status' => 1,
                                        'created_at' => $now,
                                        'updated_at' => $now,
                                    ];
                                }
                            }
                        }
                    }
                }
            }

            $start->addWeek();
            $order++;
        }

        // Bulk insert periods (3000 rows) theo từng phần 500 - tránh chậm
        foreach (array_chunk($periodRows, 500) as $chunk) {
            DB::table('periods')->insert($chunk);
        }
    }
}