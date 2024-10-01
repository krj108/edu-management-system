<?php

namespace Modules\Admin\Http\Controllers;
use Illuminate\Http\Request;
use Modules\Admin\Models\Section;
use Illuminate\Routing\Controller;
use Modules\Admin\Models\ClassRoom;
use Illuminate\Support\Facades\Auth;

class ClassRoomController extends Controller
{
    public function __construct()
    {
        // Only admin can access these routes
        $this->middleware(['auth:sanctum', 'role:admin']);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'section_id' => 'required|exists:sections,id',
        ]);

        $classRoom = ClassRoom::create([
            'name' => $request->name,
            'section_id' => $request->section_id,
        ]);

        return response()->json(['class' => $classRoom], 201);
    }

    public function addRoom(Request $request, $classId)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $classRoom = ClassRoom::findOrFail($classId);

        $room = $classRoom->rooms()->create([
            'name' => $request->name,
        ]);

        return response()->json(['room' => $room], 201);
    }
}
