<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use DB;

class TeacherController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth:sanctum', 'role:admin']);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8',
            'subject_ids' => 'required|array',
            'subject_ids.*' => 'exists:subjects,id',
            'room_ids' => 'sometimes|array', // الغرف اختيارية
            'room_ids.*' => 'exists:rooms,id', // تحقق من أن الغرف موجودة
        ]);
    
        // إنشاء المدرس
        $teacher = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);
    
        // تعيين دور المدرس
        $teacher->assignRole('teacher');
    
        // ربط المدرس بالمواد
        $teacher->subjects()->sync($request->subject_ids);
    
        // إذا تم تحديد الغرف
        if ($request->has('room_ids')) {
            foreach ($request->room_ids as $room_id) {
                foreach ($request->subject_ids as $subject_id) {
                    // ربط المدرس بالغرفة والمادة
                    DB::table('teacher_room')->insert([
                        'teacher_id' => $teacher->id,
                        'room_id' => $room_id,
                        'subject_id' => $subject_id,
                    ]);
                }
            }
        }
    
        return response()->json(['teacher' => $teacher], 201);
    }
    

    public function index()
    {
        // استرجاع المدرسين مع المواد، الصفوف، والغرف
        $teachers = User::role('teacher')
            ->with([
                'subjects',                      // المواد التي يدرسها المدرس
                'subjects.classRoom',            // الصف المرتبط بالمادة
                'subjects.classRoom.rooms'       // الغرف المرتبطة بالصف
            ])
            ->get();
    
        return response()->json(['teachers' => $teachers], 200);
    }
}
