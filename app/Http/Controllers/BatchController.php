<?php

namespace App\Http\Controllers;

use App\Models\Batch;
use App\Models\Institute;
use Illuminate\Http\Request;

class BatchController extends Controller
{
    public function index()
    {
        $institutes = Institute::with('batches')->get();
        return view('batches.index', compact('institutes'));
    }

    public function create()
    {
        $institutes = Institute::all();
        return view('batches.create', compact('institutes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'institute_id' => 'required|exists:institutes,id',
            'name' => 'required',
        ]);

        Batch::create([
            'institute_id' => $request->institute_id,
            'name' => $request->name,
        ]);

        return redirect()->route('batches.index')->with('success', 'Batch added successfully.');
    }

    public function edit(Batch $batch)
    {
        $institutes = Institute::all();
        return view('batches.edit', compact('batch', 'institutes'));
    }

    public function update(Request $request, Batch $batch)
    {
        $request->validate([
            'institute_id' => 'required|exists:institutes,id',
            'name' => 'required',
        ]);

        $batch->update([
            'institute_id' => $request->institute_id,
            'name' => $request->name,
        ]);

        return redirect()->route('batches.index')->with('success', 'Batch updated successfully.');
    }

    public function destroy(Batch $batch)
    {
        $batch->delete();
        return redirect()->route('batches.index')->with('success', 'Batch deleted successfully.');
    }
}
