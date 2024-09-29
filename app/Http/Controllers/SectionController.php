<?php

namespace App\Http\Controllers;

use App\Models\Section;
use Illuminate\Http\Request;

class SectionController extends Controller
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
        ]);

        $section = Section::create([
            'name' => $request->name,
        ]);

        return response()->json(['section' => $section], 201);
    }
}

