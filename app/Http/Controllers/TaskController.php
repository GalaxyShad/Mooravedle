<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;

class TaskController extends Controller
{
    /**
     * Show the form for creating a new resource.
     */
    public function create(string $courseId)
    {
        return Inertia::render('TaskAddOrEdit', ['courseId' => $courseId]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        Task::create([
            'name' => $request->name,
            'course_id' => $request->courseId,
            'description' => $request->description,
            'deadline' => $request->deadline,
            'max_mark' => 5,
            'passing_mark' => 3,
        ]);

        return Redirect::to('/course/' . $request->courseId);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $tsk = Task::where('id', $id)->get()->first();

        return Inertia::render('Task', [
            'task' => $tsk
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $courseId, string $id)
    {
        $previousForm = Task::where('id', $id)->get()->first();

        return Inertia::render('TaskAddOrEdit', [
            'courseId' => $courseId, 
            'editId' => $id,
            'previousForm' => $previousForm
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        Task::where('id', $id)->update([
            'name' => $request->name,
            'description' => $request->description,
        ]);

        return Redirect::to('/course/' . $request->course_id);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $courseId, string $id)
    {
        Task::destroy($id);

        return Inertia::location('/course/' . $courseId);
        // return Redirect::to('/course/' . $courseId);
    }
}
