<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\QuestionImport;
use App\Models\ExamMaster;
use Illuminate\Support\Facades\DB;

class FormController extends Controller
{
    public function handleFormSubmit(Request $request)
    {
        // Validate the incoming request
        $request->validate([
            'curriculum' => 'required|exists:curriculums,id', // Correct field name
            'semesterExam' => 'required|exists:semesters,id', // Correct field name
            'file' => 'required|mimes:xlsx,xls,csv', // Ensure file is Excel/CSV
        ]);

        // Get the uploaded file and store it
        $originalName = $request->file('file')->getClientOriginalName();
        $filePath = $request->file('file')->storeAs('files', $originalName);

        // Import the data
        Excel::import(new QuestionImport, storage_path('app/' . $filePath));

        // Get the selected curriculum and semester exam
        $curriculumId = $request->input('curriculum');
        $semesterExamId = $request->input('semesterExam');

        // Logic for saving or processing curriculum and semester data

        // Redirect back with success message
        return redirect()->back()->with('success', 'Data Imported Successfully');
    }

    public function uploadQues() {
        $curriculums = DB::select('select * from curricula where Status = 1');
        $semesters = DB::select('select * from semesters');

        $exams = DB::table('exammasters')
            ->join('examsubjectmasters', 'exammasters.id', '=', 'examsubjectmasters.ExamType_ID')
            ->select(DB::raw("CONCAT(exammasters.ExamType, '-', examsubjectmasters.Subject) AS exam_info"))
            ->get();

        return view('web.admin.import-file', compact('curriculums', 'semesters','exams'));
    }

    public function getExams(Request $request) {
        $request->validate([
            'curriculum_id' => 'required|integer',
            'semester_id' => 'required|integer',
        ]);

        // Fetch exams based on curriculum and semester
        $exams = DB::table('exammasters')
            ->join('examsubjectmasters', 'exammasters.id', '=', 'examsubjectmasters.ExamType_ID')
            ->select('exammasters.id', 'examsubjectmasters.Subject')
            ->where('exammasters.Curr_ID', $request->curriculum_id)
            ->where('exammasters.Sem_ID', $request->semester_id)
            ->get();

        return response()->json($exams);
    }
}
