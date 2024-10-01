<?php

namespace Modules\Admin\Http\Controllers;

use Illuminate\Http\Request;

use Modules\Admin\Models\Subject;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
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
            'room_ids' => 'sometimes|array', 
            'room_ids.*' => 'exists:rooms,id', 
        ]);

        $teacher = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $teacher->assignRole('teacher');
        $teacher->subjects()->sync($request->subject_ids);

        if ($request->has('room_ids')) {
            foreach ($request->room_ids as $room_id) {
                foreach ($request->subject_ids as $subject_id) {
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
        $teachers = User::role('teacher')
            ->with(['subjects', 'subjects.classRoom', 'subjects.classRoom.rooms'])
            ->get();

        return response()->json(['teachers' => $teachers], 200);
    }
}
