<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

// Web models
use App\Models\webcarouselmodel;
use App\Models\webcareercoursemodel;
use App\Models\webshortcoursemodel;
use App\Models\webcountermodel;
use App\Models\webeventsmodel;
use App\Models\webplacementsmodel;
use App\Models\webstudentofmonthmodel;
use App\Models\webprojectofmonthmodel;
use App\Models\webwinnercirclemodel;

class WebApiController extends Controller
{
    // ----------------------------------------------------------------
    // HOME PAGE DATA
    // ----------------------------------------------------------------
    public function home()
    {
        $year = Carbon::now()->format('Y');
        $data = [
            'carousel' => webcarouselmodel::all(),
            'careercourses' => webcareercoursemodel::all(),
            'shortcourses' => webshortcoursemodel::all(),
            'counter' => webcountermodel::all(),
            'events' => webeventsmodel::where('year', $year)->get(),
            'placements' => webplacementsmodel::all(),
            'studentOfMonth' => webstudentofmonthmodel::where('year', $year)->get(),
            'projectOfMonth' => webprojectofmonthmodel::where('year', $year)->get(),
            'winners' => webwinnercirclemodel::all(),
        ];
        return response()->json(['status' => true, 'data' => $data]);
    }

    // ----------------------------------------------------------------
    // CAROUSEL
    // ----------------------------------------------------------------
    public function carousels()
    {
        return response()->json(['status' => true, 'data' => webcarouselmodel::all()]);
    }

    public function carouselStore(Request $r)
    {
        $r->validate([
            'heading1' => 'required|string',
            'heading2' => 'nullable|string',
            'description' => 'required|string',
            'image' => 'required|image'
        ]);

        $filename = time() . '_' . $r->file('image')->getClientOriginalName();
        $r->file('image')->move(public_path('images/carouselimages/'), $filename);

        $carousel = webcarouselmodel::create([
            'heading1' => $r->heading1,
            'heading2' => $r->heading2,
            'description' => $r->description,
            'image' => $filename
        ]);

        return response()->json(['status' => true, 'message' => 'Carousel added', 'data' => $carousel]);
    }

    // ----------------------------------------------------------------
    // CAREER COURSES
    // ----------------------------------------------------------------
    public function courses()
    {
        return response()->json(['status' => true, 'data' => webcareercoursemodel::all()]);
    }

    public function courseStore(Request $r)
    {
        $r->validate([
            'semester' => 'required|string',
            'coursename' => 'required|string',
            'description' => 'required|string',
            'image' => 'required|image'
        ]);

        $filename = time() . '_' . $r->file('image')->getClientOriginalName();
        $r->file('image')->move(public_path('images/careercourses/'), $filename);

        $course = webcareercoursemodel::create([
            'semester' => $r->semester,
            'coursename' => $r->coursename,
            'endprofile' => $r->endprofile,
            'description' => $r->description,
            'completition' => $r->completition,
            'courseduration' => $r->courseduration,
            'classduration' => $r->classduration,
            'courseinfo' => $r->courseinfo,
            'image' => $filename,
        ]);

        return response()->json(['status' => true, 'message' => 'Career course added', 'data' => $course]);
    }

    // ----------------------------------------------------------------
    // SHORT COURSES
    // ----------------------------------------------------------------
    public function shortCourses()
    {
        return response()->json(['status' => true, 'data' => webshortcoursemodel::all()]);
    }

    public function shortCourseStore(Request $r)
    {
        $r->validate([
            'coursename' => 'required|string',
            'description' => 'required|string',
            'image' => 'required|image'
        ]);

        $filename = time() . '_' . $r->file('image')->getClientOriginalName();
        $r->file('image')->move(public_path('images/shortcourses/'), $filename);

        $course = webshortcoursemodel::create([
            'coursename' => $r->coursename,
            'description' => $r->description,
            'courseduration' => $r->courseduration,
            'classduration' => $r->classduration,
            'courseinfo' => $r->courseinfo,
            'image' => $filename,
        ]);

        return response()->json(['status' => true, 'message' => 'Short course added', 'data' => $course]);
    }

    // ----------------------------------------------------------------
    // EVENTS
    // ----------------------------------------------------------------
    public function events()
    {
        $year = Carbon::now()->format('Y');
        return response()->json(['status' => true, 'data' => webeventsmodel::where('year', $year)->get()]);
    }

    public function eventStore(Request $r)
    {
        $r->validate([
            'title' => 'required|string',
            'description' => 'required|string',
            'date' => 'required|date',
            'month' => 'required|string',
            'year' => 'required|integer',
            'image' => 'required|image'
        ]);

        $filename = time() . '_' . $r->file('image')->getClientOriginalName();
        $r->file('image')->move(public_path('images/eventimage/'), $filename);

        $event = webeventsmodel::create([
            'title' => $r->title,
            'timing' => $r->timing,
            'date' => $r->date,
            'month' => $r->month,
            'year' => $r->year,
            'description' => $r->description,
            'image' => $filename,
        ]);

        return response()->json(['status' => true, 'message' => 'Event created', 'data' => $event]);
    }

    // ----------------------------------------------------------------
    // PLACEMENTS
    // ----------------------------------------------------------------
    public function placements()
    {
        return response()->json(['status' => true, 'data' => webplacementsmodel::all()]);
    }

    public function placementStore(Request $r)
    {
        $r->validate([
            'name' => 'required|string',
            'company' => 'required|string',
            'desgination' => 'required|string',
            'image' => 'required|image'
        ]);

        $filename = time() . '_' . $r->file('image')->getClientOriginalName();
        $r->file('image')->move(public_path('images/placementimages/'), $filename);

        $placement = webplacementsmodel::create([
            'name' => $r->name,
            'company' => $r->company,
            'desgination' => $r->desgination,
            'image' => $filename,
        ]);

        return response()->json(['status' => true, 'message' => 'Placement added', 'data' => $placement]);
    }

    // ----------------------------------------------------------------
    // COUNTER
    // ----------------------------------------------------------------
    public function counter()
    {
        return response()->json(['status' => true, 'data' => webcountermodel::first()]);
    }

    public function counterUpdate(Request $r)
    {
        $r->validate([
            'activestudent' => 'required|integer',
            'alumni' => 'required|integer',
            'placements' => 'required|integer'
        ]);

        $counter = webcountermodel::first();
        $counter->update($r->only(['activestudent', 'alumni', 'placements']));

        return response()->json(['status' => true, 'message' => 'Counter updated', 'data' => $counter]);
    }

    // ----------------------------------------------------------------
    // WINNERS / PROJECT / STUDENT OF MONTH
    // ----------------------------------------------------------------
    public function winners()
    {
        return response()->json(['status' => true, 'data' => webwinnercirclemodel::all()]);
    }

    public function projects()
    {
        return response()->json(['status' => true, 'data' => webprojectofmonthmodel::all()]);
    }

    public function studentsOfMonth()
    {
        return response()->json(['status' => true, 'data' => webstudentofmonthmodel::all()]);
    }
}
