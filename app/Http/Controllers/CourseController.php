<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;

class CourseController extends Controller
{
    private int $items_per_page = 12;

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function indexDashboard()
    {
        $courses = Course::with('creator')
            ->paginate($this->items_per_page);

        return Inertia::render('Dashboard', [
            'courseList' => $courses,
            'pageName' => "dashboard",
        ]);
    }

    public function indexCreatedCourses()
    {
        $courses = Course::where('creator_id', Auth::id())
            ->with('creator')
            ->paginate($this->items_per_page);

        return Inertia::render('Dashboard', [
            'courseList' => $courses,
            'pageName' => "createdcourses",
        ]);
    }

    public function indexMyCourses()
    {
        $courses = User::where('id', Auth::user()->id)
            ->get()
            ->first()
            ->myCourses()
            ->with('creator')
            ->paginate($this->items_per_page);

        return Inertia::render('Dashboard', [
            'courseList' => $courses,
            'pageName' => "mycourses",
        ]);
    }

    public function addParticipants(Request $req, string $id)
    {
        Course::where('id', $id)
            ->get()
            ->first()
            ->participants()
            ->attach($req->users);

        return Inertia::location('/course/' . $id);
    }

    public function removeParticipant(string $course_id, string $participant_id)
    {
        $course = Course::where('id', $course_id)->get()->first();

        $course->participants()->detach($participant_id);

        return Inertia::location('/course/' . $course_id);
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
        $course->participants()->detach();

        $course->delete();

        return Redirect::to('/dashboard');
    }
}
