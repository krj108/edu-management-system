<?php

namespace App\Http\Controllers;

use App\Models\Lesson;
use DB;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
class LessonController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth:sanctum']);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'subject_id' => 'required|exists:subjects,id',
            'class_id' => 'required|exists:classes,id',
            'room_id' => 'required|exists:rooms,id',
            'content' => 'required|string',
            'pdf' => 'nullable|file|mimes:pdf',
            'video' => 'nullable|url',
        ]);
    
        $teacher = Auth::user();
        
        if (!$teacher->subjects()->where('subjects.id', $request->subject_id)->exists()) {
            return response()->json(['message' => 'Unauthorized to add lesson for this subject.'], 403);
        }
    
        $lesson = Lesson::create([
            'name' => $request->name,
            'subject_id' => $request->subject_id,
            'class_id' => $request->class_id,
            'room_id' => $request->room_id,
            'content' => $request->{'content'},
            'pdf' => $request->file('pdf') ? $request->file('pdf')->store('pdfs') : null,
            'video' => $request->video,
            'teacher_id' => $teacher->id,
        ]);
    
        return response()->json(['lesson' => $lesson], 201);
    }
    

    public function index()
    {
        $user = Auth::user();
    
        DB::beginTransaction();
        try {
            if ($user->hasRole('student')) {
                // استرجاع الدروس بناءً على المواد المسجلة للطالب
                $lessons = Lesson::whereHas('subject.students', function ($query) use ($user) {
                    // البحث عن المواد التي الطالب مسجل بها
                    $query->where('student_subject.student_id', $user->id);
                })
                ->with('subject') // تحميل العلاقة مع المواد
                ->get();
            } elseif ($user->hasRole('teacher')) {
                // استرجاع الدروس الخاصة بالمدرس
                $lessons = Lesson::whereHas('subject.teachers', function ($query) use ($user) {
                    $query->where('teacher_subject.user_id', $user->id);
                })
                ->with(['subject', 'room'])
                ->get();
            } else {
                // المدير يمكنه رؤية جميع الدروس
                $lessons = Lesson::with(['subject', 'room'])->get();
            }
    
            DB::commit();
            return response()->json(['lessons' => $lessons], 200);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Failed to fetch lessons.', 'error' => $e->getMessage()], 500);
        }
    }
    
    
    
}

    
    
    

    
    

