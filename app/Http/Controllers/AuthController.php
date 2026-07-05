<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(Request $request){
        // Validate email + password không được trống
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);
        // Kiểm tra email + password
        if (!Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            return response()->json([
                'message' => 'Email hoặc mật khẩu không đúng'
            ], 401);
        }
        // Lấy user hiện tại
        $user = Auth::user();
        // Tạo token
        $token = $user->createToken('auth_token')->plainTextToken;
        // Token + thông tin user
        return response()->json([
            'token' => $token,
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role,
            ]
        ]);
    }

    public function logout(Request $request){
        $request->user()->currentAccessToken()->delete();
        return response()->json(['message' => 'Đã đăng xuất']);
    }
}