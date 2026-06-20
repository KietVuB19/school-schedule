<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Classes;

class ClassController extends Controller
{
    // Lấy danh sách lớp của trường user đang đăng nhập
    public function index(Request $request)
    {
        $classes = Classes::where('school_id', $request->user()->school_id)
            ->where('public', 1)
            ->get();
        return response()->json($classes);
    }

    // Tạo lớp mới
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'grade' => 'required|integer',
        ]);

        $class = Classes::create([
            'name' => $request->name,
            'school_id' => $request->user()->school_id,
            'grade' => $request->grade,
            'homeroom_teacher_id' => $request->homeroom_teacher_id,
            'public' => 1,
        ]);

        return response()->json($class, 201);
    }

    // Cập nhật lớp
    public function update(Request $request, $id)
    {
        $class = Classes::findOrFail($id);
        $class->update($request->only(['name', 'grade', 'homeroom_teacher_id']));
        return response()->json($class);
    }

    // Xóa -> đổi public = 0
    public function destroy($id)
    {
        $class = Classes::findOrFail($id);
        $class->update(['public' => 0]);
        return response()->json(['message' => 'Đã xóa lớp']);
    }
}