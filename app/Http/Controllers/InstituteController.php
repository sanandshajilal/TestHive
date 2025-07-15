<?php

namespace App\Http\Controllers;

use App\Models\Institute;
use Illuminate\Http\Request;

class InstituteController extends Controller
{
    public function index()
    {
        $institutes = Institute::all();
        return view('institutes.index', compact('institutes'));
    }

    public function create()
    {
        return view('institutes.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:institutes,name',
        ]);

        Institute::create([
            'name' => $request->name,
        ]);

        return redirect()->route('institutes.index')->with('success', 'Institute added successfully.');
    }

    public function edit(Institute $institute)
    {
        return view('institutes.edit', compact('institute'));
    }

    public function update(Request $request, Institute $institute)
    {
        $request->validate([
            'name' => 'required|unique:institutes,name,' . $institute->id,
        ]);

        $institute->update([
            'name' => $request->name,
        ]);

        return redirect()->route('institutes.index')->with('success', 'Institute updated successfully.');
    }

    public function destroy(Institute $institute)
    {
        $institute->delete();
        return redirect()->route('institutes.index')->with('success', 'Institute deleted successfully.');
    }
}
