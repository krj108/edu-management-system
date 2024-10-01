<?php
namespace Modules\Student\Http\Controllers;

use Illuminate\Http\Request;
use Modules\Student\Models\Test;
use Modules\Admin\Models\Subject;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\Teacher\Models\Question;
 
class TestController extends Controller
{
    public function store(Request $request)
    {
        $user = Auth::user();

      
        if (!$user->hasRole('teacher')) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

       
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

    public function availableTests()
    {
        $user = Auth::user();

        if ($user->hasRole('student')) {
            $classId = $user->class_id;
            $roomId = $user->room_id;
            $subjects = $user->subjects;

            $tests = Test::whereIn('subject_id', $subjects->pluck('id'))
                         ->where('class_id', $classId)
                         ->where('room_id', $roomId)
                         ->get();

            return response()->json(['tests' => $tests], 200);
        }

        return response()->json(['message' => 'Unauthorized'], 403);
    }

    public function takeTest(Request $request, $testId)
    {
        $user = Auth::user();
        $test = Test::findOrFail($testId);

        if ($user->hasRole('student')) {
            $classId = $user->class_id;
            $roomId = $user->room_id;
            $subjects = $user->subjects;

            if ($test->class_id == $classId && $test->room_id == $roomId && $subjects->contains('id', $test->subject_id)) {
                return response()->json(['message' => 'Test taken successfully'], 200);
            } else {
                return response()->json(['message' => 'You are not authorized to take this test'], 403);
            }
        }

        return response()->json(['message' => 'Unauthorized'], 403);
    }
}
