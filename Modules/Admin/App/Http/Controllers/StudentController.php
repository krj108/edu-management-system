<?php

namespace Modules\Admin\Http\Controllers;

use Illuminate\Http\Request;
use Modules\Admin\Models\Room;

use Modules\Admin\Models\Subject;
use Illuminate\Support\Facades\DB;
use Modules\Admin\Models\ClassRoom;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
class StudentController extends Controller
{
    public function store(Request $request)
    {
        DB::beginTransaction();

        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:6',
                'class_id' => 'required|exists:classes,id',
                'room_id' => 'required|exists:rooms,id'
            ]);

            $room = Room::where('id', $request->room_id)
                ->where('class_id', $request->class_id)
                ->first();

            if (!$room) {
                return response()->json(['message' => 'The room does not belong to the selected class.'], 422);
            }

            $student = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'class_id' => $request->class_id,
                'room_id' => $request->room_id,
            ]);

            $student->assignRole('student');

            $subjects = Subject::where('class_id', $request->class_id)->pluck('id');
            if ($subjects->isEmpty()) {
                return response()->json(['message' => 'No subjects found for the selected class.'], 422);
            }

            $student->studentSubjects()->sync($subjects);

            DB::commit();

            return response()->json(['message' => 'Student added successfully!'], 201);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json(['message' => 'Error adding student: ' . $e->getMessage()], 500);
        }
    }
}
