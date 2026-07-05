<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class StudentSeeder extends Seeder
{
    public function run(): void
    {
        // lớp 6C
        $classId = 3;
        $now = Carbon::now();

        // học sinh lớp 6C 
        $students = [
            ['name' => 'Nguyễn Văn An', 'gender' => 'male'],
            ['name' => 'Trần Thị Bình', 'gender' => 'female'],
            ['name' => 'Lê Văn Cường', 'gender' => 'male'],
            ['name' => 'Phạm Thị Dung', 'gender' => 'female'],
            ['name' => 'Hoàng Văn Em', 'gender' => 'male'],
            ['name' => 'Ngô Thị Phương', 'gender' => 'female'],
            ['name' => 'Đặng Văn Giang', 'gender' => 'male'],
            ['name' => 'Bùi Thị Hoa', 'gender' => 'female'],
            ['name' => 'Vũ Văn Hùng', 'gender' => 'male'],
            ['name' => 'Đỗ Thị Kim', 'gender' => 'female'],
            ['name' => 'Nguyễn Văn Long', 'gender' => 'male'],
            ['name' => 'Trần Thị Mai', 'gender' => 'female'],
            ['name' => 'Lê Văn Nam', 'gender' => 'male'],
            ['name' => 'Phạm Thị Oanh', 'gender' => 'female'],
            ['name' => 'Hoàng Văn Phúc', 'gender' => 'male'],
            ['name' => 'Ngô Thị Quỳnh', 'gender' => 'female'],
            ['name' => 'Đặng Văn Sơn', 'gender' => 'male'],
            ['name' => 'Bùi Thị Tâm', 'gender' => 'female'],
            ['name' => 'Vũ Văn Thắng', 'gender' => 'male'],
            ['name' => 'Đỗ Thị Uyên', 'gender' => 'female'],
            ['name' => 'Nguyễn Văn Việt', 'gender' => 'male'],
            ['name' => 'Trần Thị Xuân', 'gender' => 'female'],
            ['name' => 'Lê Văn Yên', 'gender' => 'male'],
            ['name' => 'Phạm Thị Zung', 'gender' => 'female'],
            ['name' => 'Hoàng Văn Bảo', 'gender' => 'male'],
            ['name' => 'Ngô Thị Châu', 'gender' => 'female'],
            ['name' => 'Đặng Văn Đức', 'gender' => 'male'],
            ['name' => 'Bùi Thị Hằng', 'gender' => 'female'],
            ['name' => 'Vũ Văn Khoa', 'gender' => 'male'],
            ['name' => 'Đỗ Thị Linh', 'gender' => 'female'],
        ];

        // Insert students
        $studentRows = [];
        foreach ($students as $i => $s) {
            $studentRows[] = [
                'name' => $s['name'],
                'gender' => $s['gender'],
                'code' => 'HS6C' . str_pad($i + 1, 3, '0', STR_PAD_LEFT),
                'phone' => '',
                'address' => '',
                'public' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        DB::table('students')->insert($studentRows);

        // Lấy id các học sinh vừa insert
        $studentIds = DB::table('students')
            ->where('code', 'like', 'HS6C%')
            ->pluck('id');

        // Insert class_students
        $classStudentRows = [];
        foreach ($studentIds as $studentId) {
            $classStudentRows[] = [
                'class_id' => $classId,
                'student_id' => $studentId,
                'active' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        DB::table('class_students')->insert($classStudentRows);
    }
}