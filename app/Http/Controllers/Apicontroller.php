<?php

namespace App\Http\Controllers;

use DB;
use Mail;
use Exception;
use Carbon\Carbon;
use App\Models\Labs;
use App\Models\student;
use App\Models\LabSystem;
use App\Models\temp_comp;
use App\Models\facultyreg;
use App\Models\temp_verfy;
use App\Models\usermodels;
use App\Exports\ExportUser;
use App\Models\cummulative;
use App\Models\other_issue;
use App\Exports\Export_User;
use Illuminate\Http\Request;
use App\Models\feedback_form;
use App\Models\gpa_calculate;
use App\Models\network_issue;
use App\Models\ExamAssignment;
use App\Models\Complain_Master;
use App\Exports\Export_feedback;
use App\Models\hardware_complain;
use App\Models\software_complain;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;

class Apicontroller extends Controller
{
    
    public function loginApi(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);
    
        $user = usermodels::where('email', $request->email)->first();
        return response()->json(['msg'=>$user]);
    
        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid credentials.'
            ], 401);
        }
    
        $token = $user->createToken('student-token')->plainTextToken;
    
        if ($user->role == "1") {
            $student = DB::table("students")->where("Student_email", $user->email)->first();
    
            return response()->json([
                'success' => true,
                'role' => 'student',
                'token' => $token,
                'data' => [
                    'id' => $user->id,
                    'email' => $user->email,
                    'std_id' => $user->std_id,
                    'name' => $student->Std_Name ?? '',
                ]
            ]);
        }
    
        if ($user->role == "0") {
            return response()->json([
                'success' => true,
                'role' => 'admin',
                'token' => $token,
                'data' => [
                    'id' => $user->id,
                    'email' => $user->email,
                    'name' => $user->name
                ]
            ]);
        }
    
        return response()->json([
            'success' => false,
            'message' => 'User role not recognized.'
        ], 400);
    }
    

public function logoutapi(Request $request)
   
{
        $request->user()->tokens()->delete();
        return response()->json(['success' => true, 'message' => 'Logged out successfully']);
    }
    
    public function studentProfileApi(Request $request)
    {
        $user = $request->user();
    
     
        $studcheck = DB::table('usermodels')->where('email', $user->email)->first();
    
        if (!$studcheck) {
            return response()->json([
                'success' => false,
                'message' => 'Student record not found in usermodels table.'
            ], 404);
        }
    

        $studentProfile = DB::table('students')->where('Std_ID', $studcheck->std_id)->first();
    
        if (!$studentProfile) {
            return response()->json([
                'success' => false,
                'message' => 'Profile not found in students table.'
            ], 404);
        }
    
        return response()->json([
            'success' => true,
            'profile' => $studentProfile
        ]);
    }
    

public function mycomplaints(Request $request)
{
    $email = $request->user()->email;
    $complaints = \App\Models\Complain_Master::where('Regiystered_By', $email)->get();

    return response()->json(['success' => true, 'data' => $complaints]);
}

public function addcomplaint(Request $request)
{
    $request->validate([
        'Lab_id' => 'required',
        'Complain_Category' => 'required',
        'Complain_Description' => 'required',
        'role_type' => 'required|in:1,2,3,4',
    ]);

    $complaint = \App\Models\Complain_Master::create([
        'Lab_id' => $request->Lab_id,
        'Regiystered_By' => $request->user()->email,
        'Complain_Category' => $request->Complain_Category,
        'Complain_Description' => $request->Complain_Description,
        'role_type' => $request->role_type,
        'Date_of_Complain' => now(),
    ]);

    return response()->json(['success' => true, 'data' => $complaint]);
}

public function updatecomplaint(Request $request, $id)
{
    $complaint = \App\Models\Complain_Master::findOrFail($id);

    if ($complaint->Regiystered_By != $request->user()->email) {
        return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
    }

    $complaint->update($request->only('Complain_Description', 'role_type'));
    return response()->json(['success' => true, 'data' => $complaint]);
}

public function deletecomplaint(Request $request, $id)
{
    $complaint = \App\Models\Complain_Master::findOrFail($id);

    if ($complaint->Regiystered_By != $request->user()->email) {
        return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
    }

    $complaint->delete();
    return response()->json(['success' => true, 'message' => 'Complaint deleted']);
}

public function attendancesApi(Request $request)
{
    $user = $request->user(); 

    $student = DB::table('usermodels')->where('email', $user->email)->first();

    if (!$student || !$student->std_id) {
        return response()->json([
            'success' => false,
            'message' => 'Student record not found.'
        ], 404);
    }

    $attendances = DB::table('attendances')
        ->where('Std_ID', $student->std_id)
        ->orderBy('id', 'desc')
        ->get();

    return response()->json([
        'success' => true,
        'data' => $attendances
    ]);
}



public function examAssignmentsApi(Request $request)
{
    $userEmail = session('sessionuseremail') ?? auth()->user()->email ?? null;

    if (!$userEmail) {
        return response()->json(['error' => 'Unauthorized or session expired'], 401);
    }

    $student = DB::table("students")
        ->join('batches', 'students.Batch_ID', '=', 'batches.id')
        ->where('Student_email', $userEmail)
        ->select('batches.*', 'students.*', 'batches.Current_Sem AS sem')
        ->first();

    if (!$student) {
        return response()->json(['error' => 'Student not found'], 404);
    }

    $examAssignedBatch = DB::table('batches')
        ->join('students', 'students.Batch_ID', '=', 'batches.id')
        ->join('exam_assignments', 'exam_assignments.Std_id', '=', 'students.Std_id')
        ->select(
            'batches.Batch_Name',
            'exam_assignments.exam_date',
            'exam_assignments.Start_Time',
            'exam_assignments.End_Time',
            'exam_assignments.exam_id',
            'exam_assignments.Exam_status'
        )
        ->where('students.Student_email', '=', $userEmail)
        ->where('exam_assignments.Exam_status', '=', 1)
        ->orderBy('exam_assignments.id', 'desc')
        ->limit(1)
        ->get();

    return response()->json([
        'student' => $student,
        'exam_assignment' => $examAssignedBatch
    ]);
    return response()->json(['msg'=> 'done']);
}


}
