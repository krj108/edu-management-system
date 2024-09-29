<?php
namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string|min:6',
        ]);

        // التحقق من بيانات تسجيل الدخول
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            // المستخدم مسجل الدخول بنجاح
            $user = Auth::user();

            // تحقق من صلاحيات المدرس أو الأدمن
            if (!$user->hasRole(['admin', 'teacher','student' ])) {
                return response()->json(['message' => 'Unauthorized'], 403); // ليس لديه الصلاحيات
            }

            // إنشاء رمز مميز (token) للمستخدم
            $token = $user->createToken('authToken')->plainTextToken;

            return response()->json([
                'user' => $user,
                'token' => $token,
                'message' => 'تم تسجيل الدخول بنجاح.'
            ], 200);
        }

        return response()->json(['message' => 'Unauthorized'], 401); // فشل المصادقة
    }

    public function logout(Request $request)
    {
        // حذف جميع الرموز المميزة (tokens) للمستخدم الحالي
        $request->user()->tokens()->delete();

        return response()->json([
            'message' => 'تم تسجيل الخروج بنجاح.'
        ], 200);
    }
}

