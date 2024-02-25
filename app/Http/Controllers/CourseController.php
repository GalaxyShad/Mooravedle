<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use PHPUnit\Framework\Constraint\Count;

class CourseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function indexDashboard()
    {
        return Inertia::render('Dashboard', [
            'courseList' => Course::with('creator')->get(),
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
        $user = User::where('id', Auth::user()->id)->get()->first();

        return Inertia::render('Dashboard', [
            'courseList' => $user->myCourses()->with('creator')->get()
        ]);
    }

    public function addParticipants(Request $req, string $id)
    {
        Course::where('id', $id)
            ->get()
            ->first()
            ->participants()
            ->attach($req->users);

        return Redirect::to('/course/' . $id);
    }

    public function removeParticipant(string $course_id, string $participant_id) 
    {
        $course = Course::where('id', $course_id)->get()->first();

        $course->participants()->detach($participant_id);

        return Redirect::to('/course/' . $course_id);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('CourseAddOrEdit');
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

        $availableForCurrentUser = ($c->creator_id == Auth::user()->id) || ($c->participants()->where('id', Auth::user()->id)->count() != 0);

        return Inertia::render('Course', [
            'props' => [
                'taskList' => $taskList,
                'course' => $c,
                'participants' => $c->participants()->get(),
                'isAvailableForCurrentUser' => $availableForCurrentUser
            ]
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        return Inertia::render('CourseAddOrEdit', ['editId' => $id]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        Course::where('id', $id)->update(['name' => $request->name]);

        return Redirect::to('/course/' . $id);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $course = Course::where('id', $id)->get()->first();

        $course->tasks()->delete();
        $course->participants()->delete();

        $course->delete();

        return Redirect::to('/dashboard');
    }
}
