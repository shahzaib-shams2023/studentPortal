<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Exception;
use App\Models\ExcelSheet;
use Illuminate\Http\Request;
use App\Imports\QuestionImport;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;

class ExcelImportController extends Controller
{
    public function importExcel(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv',
        ]);

        $originalName = $request->file('file')->getClientOriginalName();
        $file = $request->file('file')->storeAs('files', $originalName);

        Excel::import(new QuestionImport, $file);

        return redirect()->back()->with('success', 'Data Imported Successfully');
    }

    public function getExamDetails()
    {
        $studentId = session('std_id');
        DB::table('exam_assignments')->where('Std_id', '=', $studentId)->update(['Exam_status' => 0]);

        $examDetails = DB::table('exam_assignments')
            ->join('semesters', 'exam_assignments.semester_id', '=', 'semesters.id')
            ->join('examsubjectmasters', 'exam_assignments.exam_id', '=', 'examsubjectmasters.ExamType_ID')
            ->join('exammasters', 'exam_assignments.exam_id', '=', 'examsubjectmasters.ExamType_ID')
            ->select(
                'exam_assignments.semester_id',
                'exam_assignments.exam_id',
                'exam_assignments.exam_date',
                'semesters.Sem_Name',
                'examsubjectmasters.Subject'
            )
            ->orderBy('exam_assignments.id', 'desc')->first();

        session(['exam_id' => $examDetails->exam_id]);

        if (!$examDetails) {
            return redirect()->back()->with('error', 'No exam details found');
        }

        return view('ExamDetails', compact('examDetails'));
        // return $student;
    }

    public function getExamResult(Request $request)
    {
        $studentId = session('std_id');
    
        // Fetch exam result details
        $examResult = DB::table('exam_assignments')
            ->join('semesters', 'exam_assignments.semester_id', '=', 'semesters.id')
            ->join('examsubjectmasters', 'exam_assignments.exam_id', '=', 'examsubjectmasters.ExamType_ID')
            ->select(
                'exam_assignments.semester_id',
                'exam_assignments.exam_id',
                'exam_assignments.exam_date',
                'semesters.Sem_Name',
                'examsubjectmasters.Subject',
                'exam_assignments.Curr_ID'
            )
            ->first();
    
        if (!$examResult) {
            return redirect()->back()->with('error', 'No exam results found.');
        }
    
        // Score calculation
        $storedQuestionIds = session('exam_questions', []);
        $submittedAnswers = $request->input('answers', []);
        $questions = ExcelSheet::whereIn('id', $storedQuestionIds)->get();
    
        $totalScore = 0;
        foreach ($questions as $question) {
            $correctAnswer = trim($question->Right_Answers);
            $userAnswer = $submittedAnswers[$question->id] ?? null;
            if (is_array($userAnswer)) {
                $userAnswer = implode(', ', array_map('trim', $userAnswer));
            }
            if (strcasecmp(trim($userAnswer), $correctAnswer) === 0) {
                $totalScore++;
            }
        }
    
        $totalQuestions = count($storedQuestionIds);
        $percentage = ($totalQuestions > 0) ? ($totalScore / $totalQuestions) * 100 : 0;
    
        $exam_id = session('exam_id');
        $exam_value = DB::table('exammasters')->where('id', $exam_id)->select('ExamType')->first();
        if (!$exam_value) {
            return back()->with('error', 'Exam type not found.');
        }
    
        $exam_name = $exam_value->ExamType;
        $current_time = Carbon::now()->toDateTimeString();
    
        if ($totalScore >= 8) {
            // Check if column exists
            $columns = Schema::getColumnListing('modulars7144s');
            if (!in_array($exam_name, $columns)) {
                return back()->with('error', "Column '$exam_name' not found in modulars7144s table.");
            }
    
            // Check if student already exists
            $modularRecord = DB::table('modulars7144s')->where('Std_ID', $studentId)->first();
    
            if ($modularRecord) {
                // Update existing
                DB::table('modulars7144s')->where('Std_ID', $studentId)->update([
                    $exam_name => $totalScore
                ]);
            } else {
                // Insert new
                DB::table('modulars7144s')->insert([
                    'Std_ID' => $studentId,
                    $exam_name => $totalScore,
                    'Sem_ID' => $examResult->semester_id,
                    'Curr_ID' => $examResult->Curr_ID,
                    'created_at' => now()
                ]);
            }
    
            $attempt = DB::table('exam_attempts')->where('Std_ID', $studentId)->first();
            if ($attempt) {
                DB::table('exam_attempts')->where('Std_ID', $studentId)->update([
                    'attempts' => $attempt->attempts + 1,
                    'status' => 'passed'
                ]);
            } else {
                DB::table('exam_attempts')->insert([
                    'Std_ID' => $studentId,
                    'exam_id' => $exam_id,
                    'marks' => $totalScore,
                    'attempts' => 1,
                    'status' => 'passed',
                    'attempt_time' => $current_time
                ]);
            }
    
        } else {
            $attempt = DB::table('exam_attempts')->where('Std_ID', $studentId)->first();
            if ($attempt) {
                DB::table('exam_attempts')->where('Std_ID', $studentId)->update([
                    'attempts' => $attempt->attempts + 1,
                    'status' => 'failed'
                ]);
            } else {
                DB::table('exam_attempts')->insert([
                    'Std_ID' => $studentId,
                    'exam_id' => $exam_id,
                    'marks' => $totalScore,
                    'attempts' => 1,
                    'status' => 'failed',
                    'attempt_time' => $current_time
                ]);
            }
        }
    
        // Show result
        return view('examResult', compact('examResult', 'totalScore', 'percentage', 'totalQuestions'));
    }



    public function showdata($exam)
    {
        $Question = ExcelSheet::where('exam_id', '=', $exam)->inRandomOrder()->limit(20)->get();
        session(['exam_questions' => $Question->pluck('id')->toArray()]);

        return view('Exampaper', compact('Question'));
    }

 

    public function getQuestion($index, Request $request)
    {
        $question = ExcelSheet::skip($index)->take(1)->first();

        if (!$question) {
            return response()->json(['error' => 'Question not found'], 404);
        }

        return response()->json([
            'exam_id' => $question->exam_id,
            'Question' => $question->question_text,
            'Options' => [
                $question->option1,
                $question->option2,
                $question->option3,
                $question->option4,
            ],
            'Right_Answers' => $question->right_answers,
        ]);
    }


}
