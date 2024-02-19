<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;

class CourseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function indexDashboard()
    {
        return Inertia::render('Dashboard', [
            'courseList' => Course::with('creator')->get()
        ]);
    }

    public function indexCreatedCourses()
    {
        return Inertia::render('Dashboard', [
            'courseList' => Course::where('creator_id', Auth::user()->id)->with('creator')->get()
        ]);
    }

    public function indexMyCourses()
    {
        return Inertia::render('Dashboard', [
            'courseList' => []
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('CourseAdd');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        Course::create([
            'name' => request()->name,
            'creator_id' => request()->user()->id,
        ]);

        return Redirect::to('/dashboard');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $taskList = Task::all()->where('course_id', $id);
        $c = Course::with('creator')->where('id', $id)->get()->first();

        return Inertia::render('Course', [
            'props' => [
                'taskList' => $taskList,
                'course' => $c,
            ]
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        return $id;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
