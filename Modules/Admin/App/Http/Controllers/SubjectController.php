<?php

namespace Modules\Admin\Http\Controllers;

use Illuminate\Http\Request;
use Modules\Admin\Models\Subject;
use Illuminate\Routing\Controller;

class SubjectController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth:sanctum', 'role:admin']);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'class_id' => 'required|exists:classes,id',
        ]);

        $subject = Subject::create([
            'name' => $request->name,
            'class_id' => $request->class_id,
        ]);

        return response()->json(['subject' => $subject], 201);
    }

    public function index()
    {
        $subjects = Subject::with('classRoom')->get();

        return response()->json(['subjects' => $subjects], 200);
    }
}
