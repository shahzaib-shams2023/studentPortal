<?php
namespace App\Http\Controllers;

use Illuminate\Support\Facades\Log;
use App\Models\Batch;
use App\Models\Semester;
use App\Models\ExamAssignment;
use Illuminate\Http\Request;
use App\Models\Curriculum;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;

class AssignExamController extends Controller
{
    // Show the form to assign exams with batches and semesters data
    public function showForm()
    {
        $batches = Batch::where('Status', 1)->get(); // Only fetch batches with Status = 1
        $curricula = Curriculum::where('Status', 1)->get(); // Only fetch curricula with Status = 1
        $semesters = Semester::all();

        return view('web.admin.AssignExam', compact('batches', 'curricula', 'semesters'));
    }

    // Handle assigning an exam
    public function assignExam(Request $request)
    {
        // Log the incoming request data
        Log::info('Request Data: ' . json_encode($request->all()));

        // Validate the incoming request
        $request->validate([
            'students' => 'required|array',
            'students.*' => 'exists:students,Std_id', // Ensure students are valid
            'batch' => 'required|exists:batches,id',
            'curriculum' => 'required|exists:curricula,id',
            'semesterExam' => 'required|exists:semesters,id',
            'examInput' => 'required|exists:exammasters,id',
            'examDate' => 'required|date',
        ]);

        try {
            // Loop through selected students and create an exam assignment for each
            foreach ($request->students as $studentId) {
                ExamAssignment::create([
                    'batch_id' => $request->batch,
                    'curr_id' => $request->curriculum,
                    'semester_id' => $request->semesterExam,
                    'exam_id' => $request->examInput,
                    'exam_date' => $request->examDate,
                    'Start_Time' => $request->starttime,  // Added start time
                    'End_Time' => $request->endtime,      // Added end time
                    'Std_id' => $studentId,  
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
                
            }

            // Log successful exam assignment
            Log::info('Exam assigned successfully for Batch: ' . $request->batch);

            return redirect()->back()->with('success', 'Exam assigned successfully!');
        } catch (QueryException $e) {
            Log::error('Database error while assigning exam: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to assign exam due to database error.');
        } catch (\Exception $e) {
            Log::error('Error while assigning exam: ' . $e->getMessage());
            return redirect()->back()->with('error', 'An error occurred while assigning the exam.');
        }
    }

    // Retrieve exams based on the selected batch and semester
    public function getStudentsAndExams(Request $request)
    {
        // Validate the incoming request
        $request->validate([
            'batch_id' => 'required|integer|exists:batches,id',
            'semester_id' => 'required|integer|exists:semesters,id',
        ]);
    
        try {
            // Fetch the batch along with the related curriculum based on the batch's Curr_ID
            $batch = DB::table('batches as b')
                ->join('curricula as c', 'b.Curr_ID', '=', 'c.id') // Join with curricula based on Curr_ID
                ->where('b.id', $request->batch_id)
                ->select('b.id as batch_id', 'b.Batch', 'c.id as curr_id', 'c.Curr_Name')
                ->first();
    
            // If batch or curriculum not found, return error
            if (!$batch) {
                return response()->json(['error' => 'Batch or Curriculum not found'], 404);
            }
    
            // Fetch students for the given batch (filtered by status 'Active')
            $students = DB::table('students')
                ->where('batch_id', $request->batch_id)
                ->where('status', 'Active') // Assuming 'Active' status is for students who can be selected
                ->select('Std_id', 'Std_Name')
                ->get();
    
            // Fetch exams based on the batch's curriculum and semester
            $exams = DB::table('exammasters as em')
                ->join('examsubjectmasters as es', 'em.id', '=', 'es.ExamType_ID')
                ->join('semesters as s', 'em.Sem_ID', '=', 's.id')
                ->join('curricula as c', 'em.Curr_ID', '=', 'c.id')
                ->select('es.ExamType_ID', 'es.Subject')
                ->where('s.id', $request->semester_id)
                ->where('c.id', $batch->curr_id) // Filter by the curriculum of the batch
                ->distinct()
                ->get();
    
            if ($students->isEmpty() || $exams->isEmpty()) {
                return response()->json(['error' => 'No exams or students found'], 404);
            }
    
            // Return the students, exams, and the curriculum associated with the selected batch
            return response()->json([
                'students' => $students,
                'exams' => $exams,
                'curriculum' => $batch, // Returning the curriculum along with batch info
            ]);
        } catch (\Exception $e) {
            Log::error('Error fetching students and exams: ' . $e->getMessage());
            return response()->json(['error' => 'Unable to fetch data'], 500);
        }
    }
    public function getCurriculumByBatch(Request $request)
    {
        // Validate the batch_id
        $request->validate([
            'batch_id' => 'required|exists:batches,id',
        ]);
    
        try {
            // Fetch the curriculum for the selected batch
            $batch = Batch::find($request->batch_id);
            $curriculum = Curriculum::where('id', $batch->Curr_ID)->get(); // Assuming 'Curr_ID' relates to 'Curriculum'
    
            return response()->json(['curriculum' => $curriculum]);
    
        } catch (\Exception $e) {
            // Log the error
            Log::error('Error fetching curriculum: ' . $e->getMessage());
            return response()->json(['error' => 'Unable to fetch curriculum'], 500);
        }
    }
}