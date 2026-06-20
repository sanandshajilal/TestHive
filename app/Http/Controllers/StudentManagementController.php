<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\Batch;


class StudentManagementController extends Controller
{
    /**
     * Display a listing of the resource.
     */


            public function index()
            {
                $batches = \App\Models\Batch::with([
                        'institute',
                        'students' => function ($query) {
                            $query->orderBy('name');
                        }
                    ])->orderBy('name')->get();
                $studentCount = \App\Models\Student::count();

                return view('students.index', compact(
                    'batches',
                    'studentCount'
                ));
            }

    /**
     * Show the form for creating a new resource.
     */
        public function create()
        {
            $batches = \App\Models\Batch::with('institute')
                ->orderBy('name')
                ->get();

            return view('students.create', compact('batches'));
        }

    /**
     * Store a newly created resource in storage.
     */
            public function store(Request $request)
            {
                $request->validate([
                    'batch_id' => 'required|exists:batches,id',
                    'name' => 'required|string|max:255',
                    'email' => 'required|email|max:255',
                ]);

                \App\Models\Student::create([
                    'batch_id' => $request->batch_id,
                    'name' => trim($request->name),
                    'email' => strtolower(trim($request->email)),
                    'is_active' => true,
                ]);

                return redirect()
                    ->route('students.index')
                    ->with('success', 'Student added successfully.');
            }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
        public function edit(Student $student)
        {
            $batches = Batch::with('institute')
                ->orderBy('name')
                ->get();

            return view('students.edit', compact(
                'student',
                'batches'
            ));
        }

    /**
     * Update the specified resource in storage.
     */
        public function update(Request $request, Student $student)
        {
            $request->validate([
                'batch_id' => 'required|exists:batches,id',
                'name' => 'required|string|max:255',
                'email' => 'required|email|max:255',
            ]);

          $student->update([
                'batch_id' => $request->batch_id,
                'name' => trim($request->name),
                'email' => strtolower(trim($request->email)),
                'is_active' => $request->has('is_active'),
            ]);

            return redirect()
                ->route('students.index')
                ->with('success', 'Student updated successfully.');
        }

    /**
     * Remove the specified resource from storage.
     */
            public function destroy(Student $student)
            {
                $student->update([
                    'is_active' => !$student->is_active
                ]);

                return redirect()
                    ->route('students.index')
                    ->with(
                        'success',
                        $student->is_active
                            ? 'Student activated successfully.'
                            : 'Student deactivated successfully.'
                    );
            }

                public function showBulkUploadForm()
                    {
                        $batches = Batch::with('institute')
                            ->orderBy('name')
                            ->get();

                        return view('students.bulk-upload', compact('batches'));
                    }

                    public function bulkUpload(Request $request)
                    {
                        $request->validate([
                            'batch_id' => 'required|exists:batches,id',
                            'csv_file' => 'required|file|mimes:csv,txt',
                        ]);

                        $path = $request->file('csv_file')->getRealPath();

                        $rows = array_map('str_getcsv', file($path));

                        $header = array_map(function ($value) {
                            return strtolower(trim(preg_replace('/^\xEF\xBB\xBF/', '', $value)));
                        }, $rows[0]);

                            if ($header !== ['name', 'email']) {
                                return back()->withErrors([
                                    'csv_file' => 'Invalid CSV format. Expected columns: name,email'
                                ]);
                            }

                        unset($rows[0]);

                        $created = 0;
                        $skipped = 0;

                        foreach ($rows as $row) {

                            if (count($row) < 2) {
                                continue;
                            }

                            $name = trim($row[0]);
                            $email = strtolower(trim($row[1]));

                            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                                    $skipped++;
                                    continue;
                                }

                            $exists = Student::where('batch_id', $request->batch_id)
                                ->where('email', $email)
                                ->exists();

                            if ($exists) {
                                $skipped++;
                                continue;
                            }

                            Student::create([
                                'batch_id' => $request->batch_id,
                                'name' => $name,
                                'email' => $email,
                                'is_active' => true,
                            ]);

                            $created++;
                        }

                        return redirect()
                            ->route('students.index')
                            ->with(
                                'success',
                                "{$created} students imported successfully. {$skipped} duplicate records skipped."
                            );
                    }
    }
