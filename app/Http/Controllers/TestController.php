<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Subject;
use App\Models\Test;
use App\Models\Question;

class TestController extends Controller
{
    public function store(Request $request)
    {
        $user = Auth::user();

        // التحقق من أن المستخدم هو معلم
        if (!$user->hasRole('teacher')) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        // التحقق من أن المعلم يدرس المادة
        $validatedData = $request->validate([
            'title' => 'required|string',
            'subject_id' => 'required|exists:subjects,id',
            'class_id' => 'required|exists:classes,id',
            'room_id' => 'required|exists:rooms,id',
            'total_marks' => 'required|integer',
            'passing_marks' => 'required|integer',
        ]);

        $subject = Subject::whereHas('teachers', function ($query) use ($user) {
            $query->where('teacher_subject.user_id', $user->id);
        })->where('id', $request->subject_id)->first();

        if (!$subject) {
            return response()->json(['message' => 'You are not authorized to create tests for this subject'], 403);
        }

        // إنشاء اختبار جديد
        $test = Test::create([
            'title' => $validatedData['title'],
            'subject_id' => $validatedData['subject_id'],
            'class_id' => $validatedData['class_id'],
            'room_id' => $validatedData['room_id'],
            'teacher_id' => $user->id,
            'total_marks' => $validatedData['total_marks'],
            'passing_marks' => $validatedData['passing_marks'],
        ]);

        return response()->json(['message' => 'Test created successfully', 'test' => $test], 201);
    }

    public function addQuestion(Request $request, $testId)
    {
        $user = Auth::user();
        $test = Test::findOrFail($testId);

        // التحقق من أن المعلم هو صاحب الاختبار
        if ($test->teacher_id !== $user->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $validatedData = $request->validate([
            'question_text' => 'required|string',
            'option_a' => 'required|string',
            'option_b' => 'required|string',
            'option_c' => 'required|string',
            'option_d' => 'required|string',
            'correct_answer' => 'required|string|in:option_a,option_b,option_c,option_d',
            'marks' => 'required|integer',
        ]);

        // إضافة سؤال جديد للاختبار
        $question = Question::create([
            'test_id' => $test->id,
            'question_text' => $validatedData['question_text'],
            'option_a' => $validatedData['option_a'],
            'option_b' => $validatedData['option_b'],
            'option_c' => $validatedData['option_c'],
            'option_d' => $validatedData['option_d'],
            'correct_answer' => $validatedData['correct_answer'],
            'marks' => $validatedData['marks'],
        ]);

        return response()->json(['message' => 'Question added successfully', 'question' => $question], 201);
    }
}

