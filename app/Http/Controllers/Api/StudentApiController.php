<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use App\Models\Student;
use App\Models\Complain_Master;
use App\Models\feedback_form;
use App\Models\Faculty;
use App\Models\LabSystem;

class StudentApiController extends Controller
{
    // -------------------------------------------------------------
    // STUDENT INFO
    // -------------------------------------------------------------
    public function getStudentData($id)
    {
        $data = DB::select("
            SELECT s.Std_id, s.Std_Name, s.Student_email, s.PhoneNo,
                   b.Batch, s.Status, ss.Sem_Name
            FROM students s
            JOIN batches b ON s.Batch_ID = b.id
            JOIN semesters ss ON b.Current_Sem = ss.id
            WHERE s.Std_id = ?",
            [$id]
        );

        if (!$data)
            return response()->json(['status' => false, 'message' => 'Student not found'], 404);

        return response()->json(['status' => true, 'data' => $data]);
    }

    // -------------------------------------------------------------
    // ALL STUDENTS
    // -------------------------------------------------------------
    public function allStudents()
    {
        $data = DB::select("
    SELECT id, name, email, password, role, std_id
    FROM usermodels
    ORDER BY id DESC
");

        return response()->json([
            'status' => true,
            'data' => $data
        ]);

    }

    // -------------------------------------------------------------
    // ATTENDANCE
    // -------------------------------------------------------------
    public function getAttendance($studentId)
    {
        $records = DB::table('attendances')
            ->where('Std_ID', $studentId)
            ->orderBy('id', 'desc')
            ->get();
        return response()->json(['status' => true, 'data' => $records]);
    }

    // -------------------------------------------------------------
    // COMPLAINTS
    // -------------------------------------------------------------
    public function getComplaints()
    {
        $complaints = DB::table('complain__masters')->orderBy('id', 'desc')->get();
        return response()->json(['status' => true, 'data' => $complaints]);
    }

    public function createComplaint(Request $r)
    {
        $r->validate([
            'Regiystered_By' => 'required|string',
            'Type' => 'required|string',
            'Description' => 'required|string'
        ]);

        DB::table('complain__masters')->insert([
            'Regiystered_By' => $r->Regiystered_By,
            'Type' => $r->Type,
            'Description' => $r->Description,
            'Status' => 0,
            'Date_of_Complain' => Carbon::now(),
            'created_at' => now(),
            'updated_at' => now()
        ]);

        return response()->json(['status' => true, 'message' => 'Complaint registered']);
    }

    // -------------------------------------------------------------
    // FEEDBACK
    // -------------------------------------------------------------
    public function getFeedback()
    {
        $feedback = DB::table('feedback_forms')->get();
        return response()->json(['status' => true, 'data' => $feedback]);
    }

    // -------------------------------------------------------------
    // FACULTY LIST
    // -------------------------------------------------------------
    public function getFaculty()
    {
        $faculty = DB::table('faculties')->get();
        return response()->json(['status' => true, 'data' => $faculty]);
    }

    // -------------------------------------------------------------
    // LABS & ISSUES
    // -------------------------------------------------------------
    public function getLabs()
    {
        $labs = DB::table('lab_systems')->get();
        return response()->json(['status' => true, 'data' => $labs]);
    }

    public function getLabIssues()
    {
        $hardware = DB::table('hardwareissues')->get();
        $software = DB::table('softwareissues')->get();
        $network = DB::table('networkissues')->get();
        $other = DB::table('otherissues')->get();

        return response()->json([
            'status' => true,
            'data' => [
                'hardware' => $hardware,
                'software' => $software,
                'network' => $network,
                'other' => $other,
            ]
        ]);
    }

    // -------------------------------------------------------------
    // GPA / ACADEMIC SUMMARY
    // -------------------------------------------------------------
    public function getGPA($studentId)
    {
        $gpa = DB::table('gpas')->where('Std_ID', $studentId)->get();
        return response()->json(['status' => true, 'data' => $gpa]);
    }
}
