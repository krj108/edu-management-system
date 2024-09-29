<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\ClassRoom;
use App\Models\Room;
use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class StudentController extends Controller
{
    public function store(Request $request)
    {
        // Start a database transaction
        DB::beginTransaction();

        try {
            // Validate the incoming request
            $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:6',
                'class_id' => 'required|exists:classes,id',
                'room_id' => 'required|exists:rooms,id'
            ]);

            // Check if the room belongs to the selected class
            $room = Room::where('id', $request->room_id)
                ->where('class_id', $request->class_id)
                ->first();

            if (!$room) {
                return response()->json(['message' => 'The room does not belong to the selected class.'], 422);
            }

            // Create the student and store in the users table
            $student = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'class_id' => $request->class_id,
                'room_id' => $request->room_id,
            ]);

            // Assign the role of 'student' to the user
            $student->assignRole('student');

            // Automatically assign the subjects based on the selected class and room
            $subjects = Subject::where('class_id', $request->class_id)->pluck('id');
            if ($subjects->isEmpty()) {
                return response()->json(['message' => 'No subjects found for the selected class.'], 422);
            }

            // Sync the subjects with the student
            $student->studentSubjects()->sync($subjects);

            // Commit the transaction if everything is fine
            DB::commit();

            return response()->json(['message' => 'Student added successfully!'], 201);
        } catch (\Exception $e) {
            // Rollback the transaction if any error occurs
            DB::rollBack();

            return response()->json(['message' => 'Error adding student: ' . $e->getMessage()], 500);
        }
    }
}
