<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class ExamController extends Controller
{
    // ----------------------------------------------------------------
    // 1.  LIST ALL EXAMS
    // ----------------------------------------------------------------
    public function index()
    {
        $exams = DB::table('exammasters')->get();
        return response()->json(['status' => true, 'data' => $exams]);
    }

    // ----------------------------------------------------------------
    // 2.  GET SPECIFIC EXAM BY ID
    // ----------------------------------------------------------------
    public function show($id)
    {
        $exam = DB::table('exammasters')
            ->where('id', $id)
            ->first();

        if (!$exam)
            return response()->json(['status' => false, 'message' => 'Exam not found'], 404);

        // get its questions if exist
        $questions = DB::table('question_bank')
            ->where('Exam_ID', $id)
            ->get();

        return response()->json(['status' => true, 'exam' => $exam, 'questions' => $questions]);
    }

    // ----------------------------------------------------------------
    // 3.  IMPORT QUESTIONS (no Excel lib, just CSV read)
    // ----------------------------------------------------------------
    public function importExcel(Request $request)
    {
        $request->validate([
            'exam_id' => 'required|integer|exists:exammasters,id',
            'file' => 'required|mimes:csv,txt'
        ]);

        $exam_id = $request->exam_id;
        $file = $request->file('file');
        $path = $file->storeAs('exam_uploads', time() . '_' . $file->getClientOriginalName());

        $rows = [];
        if (($handle = fopen(storage_path('app/' . $path), 'r')) !== false) {
            $header = fgetcsv($handle, 1000, ','); // skip header
            while (($data = fgetcsv($handle, 1000, ',')) !== false) {
                $rows[] = [
                    'Exam_ID' => $exam_id,
                    'Question' => $data[0] ?? '',
                    'Option_A' => $data[1] ?? '',
                    'Option_B' => $data[2] ?? '',
                    'Option_C' => $data[3] ?? '',
                    'Option_D' => $data[4] ?? '',
                    'Correct_Ans' => $data[5] ?? '',
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
            fclose($handle);
        }

        if (count($rows) > 0)
            DB::table('question_bank')->insert($rows);

        return response()->json([
            'status' => true,
            'message' => 'Questions imported successfully',
            'inserted' => count($rows)
        ]);
    }

    // ----------------------------------------------------------------
    // 4.  GET SPECIFIC QUESTION INDEX
    // ----------------------------------------------------------------
    public function getQuestion($examId, $index)
    {
        $questions = DB::table('question_bank')
            ->where('Exam_ID', $examId)
            ->orderBy('id')
            ->get();

        if ($index < 0 || $index >= count($questions))
            return response()->json(['status' => false, 'message' => 'Index out of range'], 404);

        return response()->json(['status' => true, 'data' => $questions[$index]]);
    }

    // ----------------------------------------------------------------
    // 5.  CALCULATE RESULTS (raw compare logic)
    // ----------------------------------------------------------------
    public function calculateResults(Request $req)
    {
        $req->validate([
            'exam_id' => 'required|integer',
            'answers' => 'required|array'
        ]);

        $exam_id = $req->exam_id;
        $answers = $req->answers;

        $questions = DB::table('question_bank')
            ->where('Exam_ID', $exam_id)
            ->orderBy('id')
            ->get();

        $score = 0;
        foreach ($questions as $index => $q) {
            $userAnswer = $answers[$index] ?? null;
            if ($userAnswer && strtolower(trim($userAnswer)) == strtolower(trim($q->Correct_Ans))) {
                $score++;
            }
        }

        $total = count($questions);
        $percent = $total ? round(($score / $total) * 100, 2) : 0;

        return response()->json([
            'status' => true,
            'exam_id' => $exam_id,
            'total_questions' => $total,
            'correct' => $score,
            'percentage' => $percent
        ]);
    }

    // ----------------------------------------------------------------
    // 6.  STUDENT-EXAM ASSIGNMENT
    // ----------------------------------------------------------------
    public function assignExam(Request $req)
    {
        $req->validate([
            'student_id' => 'required|integer|exists:students,id',
            'exam_id' => 'required|integer|exists:exammasters,id',
            'exam_date' => 'required|date',
            'start_time' => 'required',
            'end_time' => 'required'
        ]);

        DB::table('exam_assignments')->insert([
            'student_id' => $req->student_id,
            'exam_id' => $req->exam_id,
            'exam_date' => $req->exam_date,
            'start_time' => $req->start_time,
            'end_time' => $req->end_time,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        return response()->json(['status' => true, 'message' => 'Exam assigned']);
    }

    // ----------------------------------------------------------------
    // 7.  FETCH ASSIGNED EXAMS PER STUDENT
    // ----------------------------------------------------------------
    public function assignedExams($student_id)
    {
        $data = DB::table('exam_assignments')
            ->join('exammasters', 'exam_assignments.exam_id', '=', 'exammasters.id')
            ->select('exam_assignments.*', 'exammasters.ExamType')
            ->where('exam_assignments.student_id', $student_id)
            ->get();

        return response()->json(['status' => true, 'data' => $data]);
    }
}
