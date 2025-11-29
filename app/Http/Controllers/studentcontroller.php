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


class studentcontroller extends Controller
{
    public function getStudentData($id)
    {
        $data = DB::select("SELECT s.Std_id, s.Std_Name, s.Student_email, s.PhoneNo, b.Batch, s.Status, ss.Sem_Name FROM students s JOIN batches b ON s.Batch_ID = b.id JOIN semesters ss ON b.Current_Sem = ss.id WHERE s.Std_id = '" . $id . "'");
        return $data;
    }

    public function registerStd(Request $request)
    {
        $check = DB::select("SELECT email FROM registration_forms WHERE email = '" . $request->stdEmail . "'");
        if ($check) {
            echo "<script>alert('Email Alerady Registered.')
                window.location.href='/cf-register'
                </script>";
        } else {
            $cv_files = $request->file('cv');
            $ext = rand() . "." . $cv_files->getClientOriginalName();
            $cv_files->move('cvs_pdf/', $ext);

            $skills = json_encode($request->skills);

            DB::Select("INSERT INTO `registration_forms`(`name`, `email`, `phone`, `center`, `select_box`, `experience`, `portofolio`, `linkedin`, `technical`, `enter_experience`, `skills`, `applicants`, `batch`, `mode`, `student_id`, `created_at`) VALUES ('" . $request->stdName . "', '" . $request->stdEmail . "', '" . $request->stdContact . "', 'Garden', '" . $request->status . "', '" . $request->exp . "', '" . $request->portfolio . "', '" . $request->linked . "', '" . $request->techExp . "', '" . $request->expYear . "', '" . $skills . "', '" . $ext . "', '" . $request->stdBatch . "', '" . $request->currDesg . "', '" . $request->stdId . "', NOW())");

            echo "<script>alert('Registration for Career Fair is done.')
                window.location.href='/cf-register'
                </script>";
        }
    }
    
    public function get_email()
    {
        return view("/code_match_");
    }

    public function code_match()
    {
        //$user =DB::table("usermodels")->where("email", session('sessionuseremail'))->first();
        $fetch = temp_verfy::all();
        return view("/code_match", compact('fetch'));
    }

    public function input(Request $res)
    {
        //taking input
        $get_Email = $res->emailinput;

        //checking from aptech user data
        $studcheck = DB::table("students")->where("Student_email", $get_Email)->first();
        $email_match1 = temp_verfy::where("email", $get_Email)->first();
        if (isset($studcheck)) {
            $v_code = $this->generateUniqueCode();
            $user = DB::table("usermodels")->where("email", $get_Email)->first();
            $email_match = DB::table("students")->where("Student_email", $get_Email)->first();

            if (isset($user)) {
                echo "<script>alert('Email Already Exists.')
                window.location.href='/login'
                </script>";
            } else {
                if ($email_match1) {
                    $studcheck = temp_verfy::where("email", $get_Email)->first();

                    $studcheck->code = $v_code;
                    $studcheck->update();

                    try
                    {
                        $data = ['name' => $studcheck->name, 'data' => $studcheck->email, 'code' => $studcheck->code];
                        //$data= Auth::User()->name;
                        $user['to'] = $studcheck->email;
                        Mail::send('email_user', $data, function ($messages) use ($user) {
                            $messages->to($user['to']);
                            $messages->subject('Registration Code for Lab Complain');
                        });

                        session(['sessionuseremail' => $get_Email]);

                        return redirect("/code_match");

                    } catch (Exception $ex) {
                        echo $ex->getMessage();
                        die;
                    }
Carbon::setTestNow(Carbon::parse($studcheck->created_at));
                } else {
                    $studcheck = new temp_verfy();
                    $studcheck->email = $get_Email;
                    $studcheck->code = $v_code;
                    $studcheck->save();

                    try
                    {
                        $data = ['name' => $studcheck->name, 'data' => $studcheck->email, 'code' => $studcheck->code];
                        //$data= Auth::User()->name;
                        $user['to'] = $studcheck->email;
                        Mail::send('email_user', $data, function ($messages) use ($user) {
                            $messages->to($user['to']);
                            $messages->subject('Registration Code for Lab Complain');
                        });

                        session(['sessionuseremail' => $get_Email]);

                        return redirect("/code_match");

                    } catch (Exception $ex) {
                        echo $ex->getMessage();
                        die;
                    }
                }
            }

        } else {
            echo
                "<script>alert('Record Not Found.')
            window.location.href='/student'
            </script>";
        }

    }

    public function logout()
    {
        session()->forget("sessionuseremail");
        return redirect("/login");
    }

    public function admin_logout()
    {
        session()->forget("sessionuseremail");
        return redirect("/login");
    }

    public function code_match_(Request $req)
    {
        $email = $req->emailinput;
        $codes = $req->code0;
        $codes = $codes . $req->code1;
        $codes = $codes . $req->code2;
        $codes = $codes . $req->code3;
        $codes = $codes . $req->code4;
        $codes = $codes . $req->code5;

        //$studcheck =DB::table("students")->where("Student_email", $email)->first();
        //$req->emailinput;

        $login = temp_verfy::where("email", $email)->first();
        $code_check = $req->code;
        $user = DB::table("usermodels")->where("email", $email)->first();
        $login2 = DB::table("students")->where("Student_email", $email)->first();
        $pass = $req->passwordinput;
        $conpass = $req->coninput;

        if (isset($codes)) {
            if ($codes == $login->code) {
                session(['sessionuseremail' => $email]);

                $fetch = temp_verfy::all();
                echo "<script>alert('Verfication Code Match.')
                window.location.href='/register'
                </script>";
            } else {
                echo "<script>alert('Wrong Verfication Code.')
                window.location.href='/code_match'
                </script>";
            }
        } else {
            echo "<script>alert('Please enter code and try again.')
            window.location.href='code_match'
            </script>";
        }

    }
    public function registerget()
    {
        $fetch = temp_verfy::all();
        $lab = Labs::all();
        $hardware = hardware_complain::all();
        $software = software_complain::all();
        $Network = network_issue::all();
        return view("/register", compact('fetch', 'hardware', 'software', 'Network'));
    }

    public function registerpost(Request $req)
    {
        $email = $req->emailinput;
        $pass = $req->passwordinput;
        $conpass = $req->coninput;
    
        
        if (strlen($pass) < 8) {
            return redirect()->back()->with('error', 'Password must be at least 8 characters long.');
        }
    
        if ($pass != $conpass) {
            return redirect()->back()->with('error', 'Password and Confirm Password do not match.');
        }
    
        $user = usermodels::where("email", $email)->first();
    
        if ($user) {
            $user->password = $pass;
            $user->save();
    
            session(["sessionuseremail" => $user->email]);
            session(["sessionusername" => $user->Std_id]);
    
            return redirect()->route('student_dashboard')->with('success', 'Password updated successfully.');
        } else {
            $studcheck = DB::table("students")->where("student_email", $email)->first();
    
            if ($studcheck) {
                $newUser = new usermodels();
                $newUser->email = $email;
                $newUser->password = $pass;
                $newUser->Std_id = $studcheck->Std_id;
                $newUser->role = 1;
                $newUser->save();
    
                session(["sessionuseremail" => $email]);
                session(["sessionusername" => $studcheck->Std_Name]);
    
                $announcement = DB::table('announcements')->orderBy('id', 'desc')->limit(1)->get();
                $jobs = DB::table('jobs')->where('status', '1')->orderBy('id', 'desc')->limit(1)->get();
                $attendances = DB::table('attendances')->where('Std_ID', $studcheck->Std_id)->orderBy('id', 'desc')->limit(1)->get();
    
                return redirect()->route('student_dashboard')->with(compact('announcement', 'jobs', 'attendances'));
            } else {
                return redirect()->back()->with('error', 'Student details not found.');
            }
        }

    }

    public function register_complains(Request $req)
    {
        if (isset($request->emailinput)) {
            $email = $req->emailinput;
        } else {
            $email = session('sessionuseremail');
        }
        $fcheck = DB::table("facultyregs")->where('email', $email)->first();
        $schecm = DB::table("usermodels")->where('email', $email)->first();

        //to disguise between faculty & Student
        if (isset($fcheck)) {
            $login = DB::table("users")->where('email', $email)->first();
        } elseif (isset($schecm)) {
            $login = DB::table("usermodels")->where('email', $email)->first();
        }

        if (isset($email)) {
            $systemcheck = temp_comp::where('email', $email)->first();
            if (isset($systemcheck)) {
                $systemcheck = temp_comp::where('email', $email)->first();
                $systemcheck->email = $email;
                $systemcheck->update();
            } else {
                $systemcheck = new temp_comp();
                $systemcheck->email = $email;
                $systemcheck->save();
            }

            $id = $req->inputuserid;
            $fetch = LabSystem::all();

            //role set

            //    echo  'HELLO'.$login->role;
            if ($login->role == 1) {
                $lab = Labs::whereNotIn('lab_number', ['seminar', 'lab 1b'])->get();
            } else if ($login->role == 'Admin' || $login->role == 'Faculty') {
                $lab = Labs::all();
            }

            $hardware = hardware_complain::all();
            $Software = software_complain::all();
            $Network = network_issue::all();
            // $Other = other_issue::all();
            $system = temp_comp::where('email', $email)->get();

            $studcheck = DB::table("students")->where(["Student_email" => session('sessionuseremail')])->first();

            $Complainhards = Complain_Master::join('hardware_complains', 'hardware_complains.id', 'complain__masters.Complain_Category')
                ->where('Regiystered_By', session('sessionuseremail'))
                ->where('role_type', '1')->get();

            $Complainsoft = Complain_Master::join('software_complains', 'software_complains.id', 'complain__masters.Complain_Category')
                ->where('Regiystered_By', session('sessionuseremail'))
                ->where('role_type', '2')->get();

            $Complainnetwork = Complain_Master::join('network_issues', 'network_issues.id', 'complain__masters.Complain_Category')
                ->where('Regiystered_By', session('sessionuseremail'))
                ->where('role_type', '3')->get();

            $Complainnetother = Complain_Master::where('role_type', '4')
                ->where('Regiystered_By', session('sessionuseremail'))
                ->get();

            // $month = Carbon::now()->format('M');
            // echo $month;
            // $systemcheck = new temp_comp();
            // $systemcheck->email= $email;
            // $systemcheck->save();
            return view("/register_complains", compact('Complainnetother', 'Complainnetwork', 'Complainsoft', 'Complainhards', 'fetch', 'lab', 'system', 'hardware', 'Software', 'Network'));
        } else {
            // $email = $req->emailinput;

            // echo "j". $email = $req->emailinput;
            // echo "j".session('sessionuseremail');

            echo
                "<script>alert('Please Login First.')
            window.location.href='/login'
            </script>";
        }
    }

    public function facultylogin()
    {
        return view("faculty_login");
    }

    public function facultyregister()
    {
        return view("faculty_register");
    }
    public function regpost_(Request $req)
    {
        $user = new facultyreg();
        $user->name = $req->nameinput;
        $user->email = $req->emailinput;
        $user->password = $req->passwordinput;
        $user->role = 2;
        $user->save();
        return redirect("/faculty")->with("success", "company has been register");

    }

    public function facultyget(Request $req)
    {
        $email = $req->emailinput;
        $password = $req->passwordinput;

        $userid = session('sessionuseremail');

        $login = DB::table("users")->where(["email" => $email])->first();
        $userid = $req->emailinput;

        if ($login != null) {
            if (Hash::check($password, $login->password)) {
                if (isset($email)) {
                    $systemcheck = temp_comp::where('email', $email)->first();

                    if (isset($systemcheck)) {
                        // System check exists
                    } else {
                        $systemcheck = new temp_comp();
                        $systemcheck->email = $req->emailinput;
                        $systemcheck->save();
                    }
                }

                if ($login->role == 'Admin' || $login->role == 'Faculty') {
                    $systemcheck = temp_comp::where('email', $email)->first();
                    session(["sessionid" => $login->id]);
                    session(["sessionuseremail" => $login->email]);
                    session(["sessionusername" => $login->name]);

                    // Rest of your code...

                    $fetch = LabSystem::all();
                    $lab = Labs::all();
                    $Complainhards = Complain_Master::join('hardware_complains', 'hardware_complains.id', 'complain__masters.Complain_Category')
                        ->where('Regiystered_By', session('sessionuseremail'))
                        ->where('role_type', '1')->get();

                    $Complainsoft = Complain_Master::join('software_complains', 'software_complains.id', 'complain__masters.Complain_Category')
                        ->where('Regiystered_By', session('sessionuseremail'))
                        ->where('role_type', '2')->get();

                    $Complainnetwork = Complain_Master::join('network_issues', 'network_issues.id', 'complain__masters.Complain_Category')
                        ->where('Regiystered_By', session('sessionuseremail'))
                        ->where('role_type', '3')->get();

                    $Complainnetother = Complain_Master::where('role_type', '4')
                        ->where('Regiystered_By', session('sessionuseremail'));

                    return view("/register_complains", compact('fetch', 'lab', 'Complainhards', 'Complainsoft', 'Complainnetwork', 'Complainnetother'));
                }
            } else {
                return redirect()->back()->with("errormessage", "Invalid email or password");
            }
        }

        // echo $password, $login->password;
        return redirect()->back()->with("errormessage", "Record Not Found");
    }

    public function loginadminpost(Request $req)
    {
        $email = $req->emailinput;
        $password = $req->passwordinput;
        $userid = session('sessionuseremail');

        $login = DB::table("usermodels")->where(["email" => $email, "password" => $password])->first();
        $studcheck = DB::table("students")->where(["Student_email" => $email])->first();
        $userid = session('sessionuseremail');

        if ($login != "") {
            if (isset($email)) {

                $systemcheck = temp_comp::where('email', $email)->first();

                if (isset($systemcheck)) {
                    ;
                } else {
                    $systemcheck = new temp_comp();
                    $systemcheck->email = $email;
                    $systemcheck->save();
                }
            }

            if ($login->role == "1") {
                $email = $req->emailinput;
                $password = $req->passwordinput;
                $adm = usermodels::where(["email" => $email, "password" => $password])->get();
                $user = usermodels::where("email", $email)->first();

                session(["sessionid" => $login->id]);
                session(["sessionuseremail" => $login->email]);
                session(["std_id" => $login->std_id]);
                session(["sessionusername" => $studcheck->Std_Name]);

                // echo session("std_id");

                $studcheck = DB::table("usermodels")->where('email', session('sessionuseremail'))->first();
                $examcheck = DB::table("internalexams")->orderBy('id', 'desc')->limit(1)->get();
                $announcement = DB::table('announcements')->orderBy('id', 'desc')->limit(1)->get();
                $attendances = DB::table('attendances')->where('Std_ID', $studcheck->std_id)->orderBy('id', 'desc')->limit(1)->get();
                $student_data = DB::table('examsubjectmasters')
                    ->join('usermodels', 'usermodels.Std_ID', 'examsubjectmasters.std_id');
                return redirect("/student_dashboard");

                // ->orderBy('examsubjectmasters.id','desc')->limit(1)
                // ->where('examsubjectmasters.Std_ID',$studcheck->std_id)
                // ->get();
                // return view('student_dashboard',compact('announcement','attendances','student_data','examcheck'));
            } else if ($login->role == "0") {
                session(["sessionid" => $login->id]);
                session(["sessionuseremail" => $login->email]);
                session(["sessionusername" => $login->name]);

                $fetch = LabSystem::all();
                $lab = Labs::whereNotIn('id', ['14', '19'])->get();
                $hardware = hardware_complain::all();
                $software = software_complain::all();
                $Network = network_issue::all();
                // $other = other_issue::all();

                $mytime = Carbon::now();
                $mytime->toDateTimeString();
                // echo $mytime;

                $countcomplain = Complain_Master::where('Status', 'like', '%2%')->get();
                $count = $countcomplain->count();

                $countcomplains = Complain_Master::where('Status', 'like', '%0%')->get();
                $count1 = $countcomplains->count();

                $fetchprevious = Complain_Master::whereDate('Date_of_Complain', '<', $mytime)->get();
                // echo $fetchprevious;
                $fetchtoday = Complain_Master::orderBy('created_at', 'desc')->get();

                $fetch = Complain_Master::whereDate('Date_of_Complain', '>', $mytime)->get();

                $Complain_Master = Complain_Master::all();

                $count_bat = DB::table("PROTAL_BAT_STUDENT_FEEDBACK_V")->get();
                return redirect("/dashboard_");

                //  return view("dashboard_" ,compact('count_bat','Complain_Master','count1','count','fetchprevious','fetch','software','Network' ,'fetchtoday'));
            }

        } else {
            return redirect()->back()->with("errormessage", "Record Not Found");

        }
    }
    public function adminget()
    {
        return view("/student_login");
    }

    public function gety(Request $req)
    {

        $id = $req->post("batch");

        $date = Carbon::now();
        $monthName = $date->format('F');
        // echo $monthName;

        $month = Carbon::now()->format('o');

        $mytime = Carbon::now();
        $mytime->toDateTimeString();

        $date = now()->format('d');

        // echo now()->format('d');

        $dates = Carbon::now()->subMonth()->format('F');

        if (now()->format('d') >= 20) {
            // Retrieve the month and year from the feedback_forms table
            $feedback = DB::table("feedback_forms")
                ->select('month', 'year')
                ->first();

            $feedbackMonth = $feedback->month;
            $feedbackYear = $feedback->year;

// Fetch data from the std_nt_fill_fd table for the specified batch and exclude students who have filled a feedback form for the specified month and year
            $batch = DB::table("std_nt_fill_fd AS s")
                ->select('s.Batch', 's.Std_Name', 's.Std_id')
                ->whereNotExists(function ($query) use ($feedbackMonth, $feedbackYear) {
                    $query->select(DB::raw(1))
                        ->from('feedback_forms AS f')
                        ->whereRaw('s.Std_id = f.std_name_id')
                        ->where('f.month', $feedbackMonth)
                        ->where('f.year', $feedbackYear);
                })
                ->where('s.Batch', $id)
                ->get();

            $fhtml = '<table class="table text-center table-bordered table-hovere table-striped table-responsive">'
                . '<tr>' . '<th style="width:160px;">' . 'Batch' . '</th>' . '<th style="width:200px;">' . 'Student Name' . '</th>' . '<th style="width:160px;">' . 'Student Id' . '</th>' . '<tr>'
                . '</table>';
            echo $fhtml;

            foreach ($batch as $c) {
                $html = '<table class="table text-center table-bordered table-hovere table-striped table-responsive">'
                . '<tr>' . '<td>' . $c->Batch . '</td>' . '<td>' . $c->Std_Name . '</td>' . '<td>' . $c->Std_id . '</td>' . '<tr>'
                    . '</table>';

                echo $html;
            }

        } else {
            // Retrieve the month and year from the feedback_forms table
            $feedback = DB::table("feedback_forms")
                ->select('month', 'year')
                ->where('month', $dates)
                ->first();

            $feedbackMonth = $feedback->month;
            $feedbackYear = $feedback->year;

// Fetch data from the std_nt_fill_fd table for the specified batch and exclude students who have filled a feedback form for the specified month and year
            $batch = DB::table("std_nt_fill_fd AS s")
                ->select('s.Batch', 's.Std_Name', 's.Std_id')
                ->whereNotExists(function ($query) use ($feedbackMonth, $feedbackYear) {
                    $query->select(DB::raw(1))
                        ->from('feedback_forms AS f')
                        ->whereRaw('s.Std_id = f.std_name_id')
                        ->where('f.month', $feedbackMonth)
                        ->where('f.year', $feedbackYear);
                })
                ->where('s.Batch', $id)
                ->get();

            $fhtml = '<table class="table text-center table-bordered table-hovere table-striped table-responsive">'
                . '<tr>' . '<th style="width:160px;">' . 'Batch' . '</th>' . '<th style="width:200px;">' . 'Student Name' . '</th>' . '<th style="width:160px;">' . 'Student Id' . '</th>' . '<tr>'
                . '</table>';
            echo $fhtml;

            foreach ($batch as $c) {
                $html = '<table class="table text-center table-bordered table-hovere table-striped table-responsive">'
                . '<tr>' . '<td>' . $c->Batch . '</td>' . '<td>' . $c->Std_Name . '</td>' . '<td>' . $c->Std_id . '</td>' . '<tr>'
                    . '</table>';

                echo $html;
            }
        }
    }

    public function get_batch(Request $req)
    {
        $id = $req->post("userid");
        //echo $id;

        $date = Carbon::now();
        $monthName = $date->format('F');
        // echo $monthName;

        $month = Carbon::now()->format('o');

        $mytime = Carbon::now();
        $mytime->toDateTimeString();

        $date = now()->format('d');

        // echo now()->format('d');

        $dates = Carbon::now()->subMonth()->format('F');

        if (now()->format('d') >= 20) {

            $record = DB::table('protal_bat_student_feedback_v')
                ->where('Batch', $id)
                ->where('month', '=', $monthName)
                ->where('year', '=', $month)
                ->get();
            foreach ($record as $r) {
                $user = $r;
                echo json_encode($user);
            }

        } else {
            $record = DB::table('protal_bat_student_feedback_v')
                ->where('Batch', $id)
                ->where('month', '=', $dates)
                ->where('year', '=', $month)
                ->get();
            foreach ($record as $r) {
                $user = $r;
                echo json_encode($user);
            }
        }
    }

    public function dashboard_()
    {
        if (session('sessionuseremail')) {
            session('sessionusername');

            $fetch = LabSystem::all();
            $lab = Labs::whereNotIn('id', ['14', '19'])->get();
            $hardware = hardware_complain::all();
            $software = software_complain::all();
            $Network = network_issue::all();
            // $other = other_issue::all();

            $mytime = Carbon::now();
            $mytime->toDateTimeString();
            // echo $mytime;

            $countcomplain = Complain_Master::where('Status', 'like', '%2%')->get();
            $count = $countcomplain->count();

            $countcomplains = Complain_Master::where('Status', 'like', '%0%')->get();
            $count1 = $countcomplains->count();

            $fetchprevious = Complain_Master::orderBy('created_at', 'desc')->get();
            // echo $fetchprevious;
            $fetchtoday = Complain_Master::orderBy('created_at', 'desc')->get();

            $fetch = Complain_Master::whereDate('Date_of_Complain', '>', $mytime)->get();

            $Complain_Master = Complain_Master::all();

            // $Complainhards = Complain_Master::join('hardware_complains','hardware_complains.id','complain__masters.Complain_Category')
            // ->where('role_type' ,'1')->get();

            $count_bat = DB::table("PROTAL_BAT_STUDENT_FEEDBACK_V")->get();
            // $count_bat = feedback_form::join('batches','batches.Batch','feedback_forms.batch')
            // ->orWhere('month' ,'like', '%january%')
            // ->orWhere('month' ,'like', '%february%')
            // ->where('month' ,'like', '%march%')
            // ->orWhere('month' ,'like', '%april%')
            // ->orWhere('month' ,'like', '%april%')
            // ->orWhere('month' ,'like', '%may%')
            // ->orWhere('month' ,'like', '%jun%')
            // ->orWhere('month' ,'like', '%july%')
            // ->orWhere('month' ,'like', '%august%')
            // ->orWhere('month' ,'like', '%september%')
            // ->orWhere('month' ,'like', '%octuber%')
            // ->orWhere('month' ,'like', '%november%')
            // ->orWhere('month' ,'like', '%december%')
            // ->get();

            return view("dashboard_", compact('count_bat', 'Complain_Master', 'count1', 'count', 'fetchprevious', 'fetchtoday', 'fetch', 'software', 'Network'));

        } else {
            return redirect('/login')->withErrors(['error' => 'Please login first']);
        }

    }

    public function generateUniqueCode()
    {
        // $code = random_int(1000000, 999999);
        $code = str_pad(mt_rand(0, 999999), 6, '0', STR_PAD_LEFT);
        return $code;
    }

    public function lab_systems_()
    {
        $user = DB::table("usermodels")->where("email", session('sessionuseremail'))->first();
        $fetch = LabSystem::all();
        return view("/lab_systems", compact('fetch'));
    }

    public function lab(Request $req)
    {
        $labid = $req->post("userid");
        return view("/register_complains", compact('fetch', 'lab'));
    }

    public function lab_systems(Request $req)
    {

        $lab = new LabSystem();
        $lab->Host_Name = $req->Host_Nameinput;
        $lab->Status = $req->Statusinput;
        $lab->save();
        return redirect()->back();
    }

    public function interviewinvite1(Request $res)
    {
        $developerid = $res->post("interid_");
        echo $developerid;

        $developer = LabSystem::find($developerid);
        $developer->Status = "1";
        $developer->update();

        return redirect()->back();
    }

    public function interviewinvite2(Request $res)
    {
        $developerid = $res->post("interid_1");
        echo $developerid;

        $developer = LabSystem::find($developerid);
        $developer->Status = "0";
        $developer->update();

        return redirect()->back();
    }

    public function getcity($id)
    {
        $labid = $id;
        $lab_system = DB::table("lab_systems")->where("Lab_id", $labid)->get();
        return view("labs_", compact('lab_system'));
    }

    // _______________________________________________________________________________

    public function get_data(Request $req)
    {
        $id = $req->post("userid");
        $record = Labs::where('id', $id)->get();
        foreach ($record as $r) {
            $user = $r;
            echo json_encode($user);
        }
    }
    // ____________________________________________________________________-

    public function get_data_(Request $req)
    {
        $id = $req->post("userid_");
        $record = temp_comp::where('id', $id)
            ->orderBy('created_at', 'desc')->get();
        foreach ($record as $r) {
            $user = $r;
            echo json_encode($user);
        }
    }

    public function temp_comp(Request $request, $id)
    {

        if (isset($request->emailinput)) {
            $userid = $request->emailinput;
        } else {
            $userid = session('sessionuseremail');
        }
        $name = LabSystem::where('id', $id)->first();
        $emailse = session('sessionuseremail');

        $system = temp_comp::where('email', $userid)
            ->where('Host_Name', $name->Host_Name)->first();

        $userid = session('sessionuseremail');
        $systemcheck = temp_comp::where('email', $userid)->first();
        $system = temp_comp::where('email', $userid)->first();
        $system->Host_Name = $name->Host_Name;
        $system->email = session('sessionuseremail');
        $system->Lab_id = $name->Lab_id;
        $system->Pc_ip = $id;
        $system->Date_of_Complain = Date("y-m-d");
        $system->update();

        $fetch = LabSystem::all();
        $lab = Labs::all();
        $hardware = hardware_complain::all();
        $software = software_complain::all();
        $Network = network_issue::all();
        // $other = other_issue::all();

        // $systems = temp_comp::where('email' , $userid)->get();
        return view("/register_complains", compact('fetch', 'lab', 'hardware', 'software', 'Network'));
    }
    public function getdatmodal(Request $req)
    {
        $id = $req->post("userid_");
        //echo $id;
        $record = temp_comp::where('id', $id)->get();
        foreach ($record as $r) {
            $user = $r;
            echo json_encode($user);
        }
    }

    public function hardwareissue(Request $req)
    {

        $user = usermodels::where('email', $req->emailinput)->first();
        // $login =DB::table("hardware_complains")->where('id' ,$req->id)->first();

        $hardware = $req->hardware;
        $other_issue = $req->other_issue;

        $req->validate([
            "other_issue" => "required",
        ]);

        $status = $req->status;

        $userid = session('sessionuseremail');
        echo $userid;

        $studcheck = DB::table("students")->where(["Student_email" => $userid])->first();

        $user = temp_comp::where('email', session('sessionuseremail'))->first();
        $user->hardware_name = $hardware;
        $user->other_hardware_issue = $other_issue;
        // $user->Date_of_Complain = Date("y-m-d");
        $user->update();

        $complain = new Complain_Master();
        $complain->Complain_Category = $hardware;
        $complain->Complain_Description = $other_issue;
        // $complain->installation=$installation;
        // $complain->id_=$id;
        $complain->status_work = $status;
        $complain->Date_of_Complain = Date("y-m-d");
        $complain->Regiystered_By = session('sessionuseremail');
        $complain->Lab_id = $user->Lab_id;
        $complain->Pc_ip = $user->Pc_ip;
        $complain->role_type = $req->role1;
        $complain->save();

        $data = ['data' => session('sessionuseremail')];
        //$data= Auth::User()->name;
        $user['to'] = 'coursewareaptech@gmail.com'; //admins email t0 send the email to the admin of the site
        Mail::send('email', $data, function ($messages) use ($user) {
            $messages->to($user['to']);
            $messages->subject('New Complain register by');
        });

        $delete = temp_comp::where('email', $userid);
        $delete->delete();

        echo "<script>alert('Complain Register Successfully.')
            window.location.href='/register_complains'
            </script>";

        // return redirect("register_complains")->with("updatedsuccess" , "Data has been updated");
    }

    public function softwareissue(Request $req)
    {
        $software = $req->software;
        $other_issue = $req->other_issue;
        // $installation = $req->installation;
        $status = $req->status;

        $userid = session('sessionuseremail');
        $studcheck = DB::table("students")->where(["Student_email" => $userid])->first();
        $user = temp_comp::where('email', $userid)->first();
        echo "ddd" . $userid;

        $user->software_name = $software;
        $user->other_software_issue = $other_issue;
        // $user->Date_of_Complain = Date("y-m-d");
        $user->update();

        $complain = new Complain_Master();
        $complain->Complain_Category = $software;
        $complain->Complain_Description = $other_issue;
        // $complain->installation=$installation;
        $complain->status_work = $status;

        $complain->Date_of_Complain = Date("y-m-d");
        $complain->Regiystered_By = session('sessionuseremail');
        $complain->Lab_id = $user->Lab_id;
        $complain->Pc_ip = $user->Pc_ip;
        $complain->role_type = $req->role1;
        $complain->save();

        $data = ['data' => session('sessionuseremail')];
        //$data= Auth::User()->name;
        $user['to'] = 'coursewareaptech@gmail.com'; //admins email t0 send the email to the admin of the site
        Mail::send('email', $data, function ($messages) use ($user) {
            $messages->to($user['to']);
            $messages->subject('New Complain register by');
        });

        $delete = temp_comp::where('email', $userid);
        $delete->delete();

        echo "<script>alert('Complain Register Successfully.')
            window.location.href='/register_complains'
            </script>";

        // return redirect("register_complains")->with("updatedsuccess" , "Data has been updated");
    }
    public function networkissue(Request $req)
    {
        $network = $req->network;
        $other_issue = $req->other_issue;
        $status = $req->status;

        // $installation = $req->installation;

        $userid = session('sessionuseremail');
        $studcheck = DB::table("students")->where(["Student_email" => $userid])->first();

        $user = temp_comp::where('email', $userid)->first();
        echo "ddd" . $userid;
        $user->Network_issue = $network;
        $user->other_hardware_issue = $other_issue;

        // $user->Date_of_Complain = Date("y-m-d");
        $user->update();

        $complain = new Complain_Master();
        $complain->Complain_Category = $network;
        $complain->Complain_Description = $other_issue;
        // $complain->installation=$installation;
        $complain->status_work = $status;

        $complain->Date_of_Complain = Date("y-m-d");
        $complain->Regiystered_By = session('sessionuseremail');
        $complain->Lab_id = $user->Lab_id;
        $complain->Pc_ip = $user->Pc_ip;
        $complain->role_type = $req->role1;
        $complain->save();

        $data = ['data' => session('sessionuseremail')];
        //$data= Auth::User()->name;
        $user['to'] = 'coursewareaptech@gmail.com'; //admins email t0 send the email to the admin of the site
        Mail::send('email', $data, function ($messages) use ($user) {
            $messages->to($user['to']);
            $messages->subject('New Complain register by');
        });

        $delete = temp_comp::where('email', $userid);
        $delete->delete();

        echo "<script>alert('Complain Register Successfully.')
        window.location.href='/register_complains'
        </script>";

        // return redirect("register_complains")->with("updatedsuccess" , "Data has been updated");
    }

    public function otherissue(Request $req)
    {
        $other = $req->other;
        // $installation = $req->installation;

        $userid = session('sessionuseremail');
        $studcheck = DB::table("students")->where(["Student_email" => $userid])->first();

        $user = temp_comp::where('email', $userid)->first();
        echo "ddd" . $userid;

        $user->other_issue = $other;
        // $user->Date_of_Complain = Date("y-m-d");
        $user->update();

        $complain = new Complain_Master();
        $complain->Complain_Category = 1;
        $complain->Complain_Description = $other;
        // $complain->installation=$installation;
        $complain->Date_of_Complain = Date("y-m-d");
        $complain->Regiystered_By = session('sessionuseremail');
        $complain->Lab_id = $user->Lab_id;
        $complain->Pc_ip = $user->Pc_ip;
        $complain->role_type = $req->role1;
        $complain->save();

        $data = ['data' => session('sessionuseremail')];
        //$data= Auth::User()->name;
        $user['to'] = 'coursewareaptech@gmail.com'; //admins email t0 send the email to the admin of the site
        Mail::send('email', $data, function ($messages) use ($user) {
            $messages->to($user['to']);
            $messages->subject('New Complain register by');
        });

        $delete = temp_comp::where('email', $userid);
        $delete->delete();

        echo "<script>alert('Complain Register Successfully.')
        window.location.href='/register_complains'
        </script>";
        // return redirect("register_complains")->with("updatedsuccess" , "Data has been updated");
    }

    public function register_()
    {
        $userid = session('sessionuseremail');
        $system = temp_comp::where('email', $userid)->get();

        $hardware = hardware_complain::all();
        $software = software_complain::all();
        $Network = network_issue::all();
        // $others = other_issue::all();
        $fetch = LabSystem::all();

        return view("/complain_register", compact('system', 'fetch', 'hardware', 'software', 'Network'));
    }

    public function view_complains()
    {
        // $Complainhards = Complain_Master::join('hardware_complains','hardware_complains.id','complain__masters.Complain_Category')
        // ->where('Regiystered_By',session('sessionuseremail'))
        // ->where('role_type' ,'1')->get();

        // $Complainsoft = Complain_Master::join('software_complains','software_complains.id','complain__masters.Complain_Category')
        // ->where('Regiystered_By',session('sessionuseremail'))
        // ->where('role_type' ,'2')->get();

        // $Complainnetwork = Complain_Master::join('network_issues','network_issues.id','complain__masters.Complain_Category')
        // ->where('Regiystered_By',session('sessionuseremail'))
        // ->where('role_type' ,'3')->get();

        if (session('sessionuseremail')) {
            $studcheck = DB::table("students")->where(["Student_email" => session('sessionuseremail')])->first();

            $Complainhards = Complain_Master::join('hardware_complains', 'hardware_complains.id', 'complain__masters.Complain_Category')
                ->where('Regiystered_By', session('sessionuseremail'))
                ->where('role_type', '1')->get();

            $Complainsoft = Complain_Master::join('software_complains', 'software_complains.id', 'complain__masters.Complain_Category')
                ->where('Regiystered_By', session('sessionuseremail'))
                ->where('role_type', '2')->get();

            $Complainnetwork = Complain_Master::join('network_issues', 'network_issues.id', 'complain__masters.Complain_Category')
                ->where('Regiystered_By', session('sessionuseremail'))
                ->where('role_type', '3')->get();

            $Complainnetother = Complain_Master::where('role_type', '4')
                ->where('Regiystered_By', session('sessionuseremail'))
                ->get();

            // $view_compl =Complain_Master::where('Regiystered_By',session('sessionuseremail'))->get();
            return view("/view_complains", compact('Complainhards', 'Complainsoft', 'Complainnetwork', 'Complainnetother'));

        } else {
            echo
                "<script>alert('Please Login First.')
            window.location.href='/login'
            </script>";
        }
    }
    // _________________________________________________________________________
    public function update_status_company1(Request $res)
    {
        try {
            $companyid = $res->post("userid");
            echo $companyid;

            $company = LabSystem::find($companyid);

            if (is_null($company)) {

                echo "Error";
                die;
            }

            $company->status = "1";
            $company->update();

            return redirect()->back();

        } catch (Exception $ex) {

            echo $ex->getMessage();
            die;
        }
    }

    public function update_status_company0(Request $res)
    {
        $companyid = $res->post("userid1");
        echo $companyid;

        $company = LabSystem::find($companyid);
        $company->status = "0";
        $company->update();

        return redirect()->back();

    }
    public function forgetpassword()
    {
        $fetch = temp_verfy::all();
        return view("/forgetpassword", compact('fetch'));
    }
    public function forgetpassword_(Request $req)
    {
        $v_code = $this->generateUniqueCode();
        $user = usermodels::where('email', $req->emailinput)->first();
        session(["sessionuseremail" => $user->email]);


        if (isset($req->emailinput)) {
            if (isset($user)) {
                $fetch = temp_verfy::all();

                $data = ['data' => $user->email, 'code' => $v_code];
                //$data= Auth::User()->name;
                $user['to'] = $user->email;
                Mail::send('email_user_forg', $data, function ($messages) use ($user) {
                    $messages->to($user['to']);
                    $messages->subject('Forgot Passwword Code for Lab Complain');
                });
                $fuser = temp_verfy::where('email', $req->emailinput)->first();
                $fuser->code = $v_code;
                //$fuser->status = 8;
                $fuser->update();
                return view("/code_match", compact('fetch'));
            } else {
                echo "<script>alert('Invalid Email Address.')
                window.location.href='/forgetpassword'
                </script>";
            }
        } else {
            echo "<script>alert('Please Provide Email Addresss to Continue.')
            </script>";

        }

    }
    public function labs()
    {
        if (session('sessionuseremail')) {
            $lab = Labs::all();
            return view("/lab_insert", compact('lab'));
        } else {
            return redirect('/login')->withErrors(['error' => 'Please login first']);
        }
    }
    public function labsinst_(Request $req)
    {
        $lab = new Labs();
        $lab->No_of_pcs = $req->intlab;
        $lab->lab_number = $req->labnumb;
        $lab->Utilization_status = $req->utlstatus;
        $lab->save();
        return redirect()->back();
    }

    public function labsinst(Request $req)
    {
        $lab = new Labs();
        $lab->No_of_pcs = $req->intlab;
        $lab->lab_number = $req->labnumb;
        $lab->Utilization_status = $req->utlstatus;
        $lab->save();
        return redirect()->back();

    }

    public function labsystem()
    {
        if (session('sessionuseremail')) {
            $lab_sys = LabSystem::all();
            return view("lab_systemS", compact('lab_sys'));
        } else {
            return redirect('/login')->withErrors(['error' => 'Please login first']);
        }
    }
    public function labsystem_(Request $req)
    {
        $labs = new LabSystem();
        $labs->Host_Name = $req->intlab;
        // $labs ->Status=$req->labnumb;
        $labs->Lab_id = $req->utlstatus;

        $labs->save();
        return redirect()->back();

    }

    public function Complain_views_admin()
    {
        if (session('sessionuseremail')) {
            return view("Complain_views_admin");
        } else {
            return redirect('/login')->withErrors(['error' => 'Please login first']);
        }
    }

    // _________________________________________________________________________
    public function updatstatus_1(Request $res)
    {
        $complainid = $res->post("id1");
        echo $complainid;
        $complain = Complain_Master::find($complainid);
        $complain->Status = "1";
        $complain->update();
        return redirect()->back();
    }

    public function updatstatus_0(Request $res)
    {
        $complainid = $res->post("id1");
        echo $complainid;
        $complain = Complain_Master::find($complainid);
        $complain->Status = "0";
        $complain->update();
        return redirect()->back();
    }
    public function hardware_compalins()
    {

        $Complainhards = Complain_Master::join('hardware_complains', 'hardware_complains.id', 'complain__masters.Complain_Category')
            ->where('role_type', '1')->get(['complain__masters.*']);

        // $Complainhards =Complain_Master::all();
        return view("hardware_complains", compact('Complainhards'));
    }

    public function software_compalins()
    {
        $Complainsoft = Complain_Master::join('software_complains', 'software_complains.id', 'complain__masters.Complain_Category')
            ->where('role_type', '2')->get(['complain__masters.*']);
        // $Complainhar =Complain_Master::all();
        return view("software_complains", compact('Complainsoft'));
    }

    public function network_compalins()
    {
        $Complainnetwork = Complain_Master::join('network_issues', 'network_issues.id', 'complain__masters.Complain_Category')
            ->where('role_type', '3')->get(['complain__masters.*']);
        // $Complainhar =Complain_Master::all();
        return view("network_compalins", compact('Complainnetwork'));
    }

    public function other_complains()
    {

        $Complainnetother = Complain_Master::where('role_type', '4')->get();

        // $Complainhar =Complain_Master::all();
        return view("other_complains", compact('Complainnetother'));
    }

    public function Complain_Master(Request $request)
    {
        $mytime = Carbon::now();
        $mytime->toDateTimeString();

        $fetchprevious = Complain_Master::whereDate('date_of_reg', '<', $mytime)->get();

        $fetchtoday = Complain_Master::orderBy('created_at', 'desc')->get();

        $fetch = Complain_Master::whereDate('date_of_reg', '>', $mytime)->get();

        return view("dashboard_", compact('fetchprevious', 'fetchtoday', 'fetch'));

    }

     public function updatstatuscompany_1(Request $res)
    {
        try {
            $complainid = $res->post("comp_id");
            echo $complainid;
            $complain = Complain_Master::join('students', 'students.Student_email', 'complain__masters.Regiystered_By')
                ->where('id', $complainid)->first();
            // $complain = Complain_Master::find($complainid);

            if (is_null($complain)) {

                echo "Error";
                die;
            }
            $complain->Status = "1";
            $complain->update();

            $data = ['name' => $complain->Std_Name, 'data' => $complain->Regiystered_By];
            //$data= Auth::User()->name;
            $user['to'] = $complain->Regiystered_By; //admins email t0 send the email to the admin of the site
            Mail::send('resloved', $data, function ($messages) use ($user) {
                $messages->to($user['to']);
                $messages->subject('Complain is been Resolved');
            });
            return redirect()->back();

        } catch (Exception $ex) {

            echo $ex->getMessage();
            die;
        }
        return redirect()->back();

        // // try{
        //     $complainid = $res->post("comp_id");
        //     echo $complainid;

        //     $complain = Complain_Master::find($complainid);
        //     $complain->Status="1";
        //     $complain->update();

        //     // return redirect()->back();
    }

    public function updatstatuscompany0(Request $res)
    {
        try {
            $complainid = $res->post("compid_1");
            echo $complainid;
            $complain = Complain_Master::join('students', 'students.Student_email', 'complain__masters.Regiystered_By')
                ->where('id', $complainid)->first();
            // $complain = Complain_Master::find($complainid);

            if (is_null($complain)) {

                echo "Error";
                die;
            }
            $complain->Status = "2";
            $complain->update();

            $data = ['name' => $complain->Std_Name, 'data' => $complain->Regiystered_By];
            //$data= Auth::User()->name;
            $user['to'] = $complain->Regiystered_By; //admins email t0 send the email to the admin of the site
            Mail::send('resloved', $data, function ($messages) use ($user) {
                $messages->to($user['to']);
                $messages->subject('Complain is been Resolved');
            });
            return redirect()->back();

        } catch (Exception $ex) {

            echo $ex->getMessage();
            die;
        }
        return redirect()->back();

    }

    public function all_lab()
    {
        $userid = session('sessionuseremail');
        $system = temp_comp::all();
        $lab = Labs::all();
        $software = software_complain::all();
        return view("/all_lab", compact('lab', 'software', 'system'));
    }

    public function lab_s_(Request $req)
    {
        $userid = session('sessionuseremail');
        $lab_id = $req->lab_id;
        $system = temp_comp::where('email', $userid)->first();

        $system = new Complain_Master();
        $system->Lab_id = $req->select_lab;
        $system->Regiystered_By = $userid;
        $system->Complain_Category = $req->software;
        $system->Complain_Description = $req->other_install;
        $system->role_type = 2;
        $system->Date_of_Complain = Date("y-m-d");
        $system->save();

        return redirect()->back();
        $userid = session('sessionuseremail');
        $system = temp_comp::all();
        $hardware = hardware_complain::all();
        $software = software_complain::all();
        $Network = network_issue::all();
        // $other = other_issue::all();
        $fetch = LabSystem::all();

        return view("/lab_issues", compact('hardware', 'software', 'Network', 'fetch', 'system'));

    }

    public function resolve()
    {
        $Complainhards = Complain_Master::join('hardware_complains', 'hardware_complains.id', 'complain__masters.Complain_Category')
            ->where('role_type', '1')->get();

        $Complainsoft = Complain_Master::join('software_complains', 'software_complains.id', 'complain__masters.Complain_Category')
            ->where('role_type', '2')->get();

        $Complainnetwork = Complain_Master::join('network_issues', 'network_issues.id', 'complain__masters.Complain_Category')
            ->where('role_type', '3')->get();

        $Complainnetother = Complain_Master::where('role_type', '4')->get();
        return view("/resolve", compact('Complainhards', 'Complainsoft', 'Complainnetwork', 'Complainnetother'));
    }

    public function get_data_d(Request $req)
    {
        $id = $req->post("labid_");
        //echo $id;
        $record = Labs::where('id', $id)->get();
        foreach ($record as $r) {
            $user = $r;
            echo json_encode($user);
        }
    }
    public function updaterecords(Request $req)
    {
        $useri_d = $req->inputuserid;
        $inputnumberinput = $req->inputnumberinput;
        $inputlabnumberinput = $req->inputlabnumberinput;
        $inputUtilization_status = $req->inputUtilization_status;

        $user = Labs::find($useri_d);
        $user->No_of_pcs = $inputnumberinput;
        $user->lab_number = $inputlabnumberinput;
        $user->Utilization_status = $inputUtilization_status;
        $user->update();
        return redirect()->back()->with("updatedsuccess", "Data has been updated");
    }

    public function get_data_d_(Request $req)
    {
        $id = $req->post("labid_");
        //echo $id;
        $record = LabSystem::where('id', $id)->get();
        foreach ($record as $r) {
            $user = $r;
            echo json_encode($user);
        }
    }
    public function updaterecords_(Request $req)
    {
        $useri_d = $req->inputuserid;
        $inputnumberinput = $req->inputnumberinput;
        $inputlabnumberinput = $req->inputlabnumberinput;
        $inputUtilization_status = $req->inputUtilization_status;

        $user = LabSystem::find($useri_d);
        $user->Host_Name = $inputnumberinput;
        $user->Lab_id = $inputlabnumberinput;
        // $user->Utilization_status = $inputUtilization_status;
        $user->update();
        return redirect()->back()->with("updatedsuccess", "Data has been updated");
    }

    public function software_insert()
    {
        if (session('sessionuseremail')) {
            $software = software_complain::all();
            return view("/software_insert", compact('software'));
        } else {
            return redirect('/login')->withErrors(['error' => 'Please login first']);
        }
    }

    public function software_insert_(Request $req)
    {
        $software = new software_complain();
        $software->software_name = $req->softwareinput;
        $software->save();

        return redirect()->back();

    }

    public function hardware_insert()
    {
        if (session('sessionuseremail')) {
            $hardware = hardware_complain::all();
            return view("/hardware_insert", compact('hardware'));
        } else {
            return redirect('/login')->withErrors(['error' => 'Please login first']);
        }
    }

    public function hardware_insert_(Request $req)
    {
        $hardware = new hardware_complain();
        $hardware->hardware_name = $req->hardwareinput;
        $hardware->save();

        return redirect()->back();

    }

    public function network_insert()
    {
        if (session('sessionuseremail')) {
            $network = network_issue::all();
            return view("/network_insert", compact('network'));
        } else {
            return redirect('/login')->withErrors(['error' => 'Please login first']);
        }
    }

    public function network_insert_(Request $req)
    {
        $network = new network_issue();
        $network->Network_issue = $req->network;
        $network->save();

        return redirect()->back();

    }

    public function _getdata_(Request $req)
    {
        $id = $req->post("labid_");
        //echo $id;
        $record = software_complain::where('id', $id)->get();
        foreach ($record as $r) {
            $user = $r;
            echo json_encode($user);
        }
    }
    public function update_records_(Request $req)
    {
        $useri_d = $req->inputuserid;
        $inputnumberinput = $req->inputnumberinput;
        // $inputlabnumberinput = $req->inputlabnumberinput;
        // $inputUtilization_status = $req->inputUtilization_status;

        $user = software_complain::find($useri_d);
        $user->software_name = $inputnumberinput;
        // $user->Lab_id = $inputlabnumberinput;
        // $user->Utilization_status = $inputUtilization_status;
        $user->update();
        return redirect()->back()->with("updatedsuccess", "Data has been updated");
    }

    public function _get_data_(Request $req)
    {
        $id = $req->post("labid_");
        //echo $id;
        $record = hardware_complain::where('id', $id)->get();
        foreach ($record as $r) {
            $user = $r;
            echo json_encode($user);
        }
    }
    public function _update_records_(Request $req)
    {
        $useri_d = $req->inputuserid;
        $inputnumberinput = $req->inputnumberinput;
        // $inputlabnumberinput = $req->inputlabnumberinput;
        // $inputUtilization_status = $req->inputUtilization_status;

        $user = hardware_complain::find($useri_d);
        $user->hardware_name = $inputnumberinput;
        // $user->Lab_id = $inputlabnumberinput;
        // $user->Utilization_status = $inputUtilization_status;
        $user->update();
        return redirect()->back()->with("updatedsuccess", "Data has been updated");
    }

    public function _get_data_net(Request $req)
    {
        $id = $req->post("labid_");
        //echo $id;
        $record = network_issue::where('id', $id)->get();
        foreach ($record as $r) {
            $user = $r;
            echo json_encode($user);
        }
    }
    public function net_update_records_(Request $req)
    {
        $useri_d = $req->inputuserid;
        $inputnumberinput = $req->inputnumberinput;
        // $inputlabnumberinput = $req->inputlabnumberinput;
        // $inputUtilization_status = $req->inputUtilization_status;

        $user = network_issue::find($useri_d);
        $user->Network_issue = $inputnumberinput;
        // $user->Lab_id = $inputlabnumberinput;
        // $user->Utilization_status = $inputUtilization_status;
        $user->update();
        return redirect()->back()->with("updatedsuccess", "Data has been updated");
    }

    public function updatstatuscompany2(Request $res)
    {
        try {
            $complainid = $res->post("compid");
            echo $complainid;
            $complain = Complain_Master::join('students', 'students.Student_email', 'complain__masters.Regiystered_By')
                ->where('id', $complainid)->first();

            if (is_null($complain)) {

                echo "Error";
                die;
            }

            $complain->Status = "1";
            $complain->update();

            $data = ['name' => $complain->Std_Name, 'data' => $complain->Student_email];
            //$data= Auth::User()->name;
            $user['to'] = $complain->Student_email; //admins email t0 send the email to the admin of the site
            Mail::send('complain_resolve_email', $data, function ($messages) use ($user) {
                $messages->to($user['to']);
                $messages->subject('Working on your Complain is been started');
            });
            return redirect()->back();

        } catch (Exception $ex) {

            echo $ex->getMessage();
            die;
        }

        return redirect()->back();
    }

    public function updatstatuscompany3(Request $res)
    {
        try {
            $complainid = $res->post("compid1");
            echo $complainid;
            $complain = Complain_Master::join('students', 'students.Student_email', 'complain__masters.Regiystered_By')
                ->where('id', $complainid)->first();

            if (is_null($complain)) {

                echo "Error";
                die;
            }

            $complain->Status = "2";
            $complain->update();

            $data = ['name' => $complain->Std_Name, 'data' => $complain->Student_email];
            //$data= Auth::User()->name;
            $user['to'] = $complain->Student_email; //admins email t0 send the email to the admin of the site
            Mail::send('resloved', $data, function ($messages) use ($user) {
                $messages->to($user['to']);
                $messages->subject('Complain is been Resolved');
            });
            return redirect()->back();

        } catch (Exception $ex) {

            echo $ex->getMessage();
            die;
        }

        return redirect()->back();

    }

    public function filter(Request $res)
    {
        $from = $res->from;
        $to = $res->to;

        $date = Carbon::now();
        $monthName = $date->format('F');

        // $fetchtoday = Complain_Master::whereDate('date_of_reg','=',$mytime)->get();

        $fetchtoday = Complain_Master::whereBetween('Date_of_Complain', [$from, $to])->get();

        $Complain_Master = Complain_Master::all();

        $countcomplain = Complain_Master::where('Status', 'like', '%2%')
            ->where('Date_of_Complain', '=', $monthName)->get();
        $count = $countcomplain->count();

        $countcomplains = Complain_Master::where('Status', 'like', '%0%')
            ->where('Date_of_Complain', '=', $monthName)->get();
        $count1 = $countcomplains->count();

        $count_bat = DB::table("PROTAL_BAT_STUDENT_FEEDBACK_V")->get();

        return view("/dashboard_", compact('fetchtoday', 'Complain_Master', 'count', 'count1', 'count_bat'));

    }
    public function getdatare_(Request $req)
    {
        $id = $req->post("labid_");
        //echo $id;
        $record = Complain_Master::where('id', $id)->get();
        foreach ($record as $r) {
            $user = $r;
            echo json_encode($user);
        }
    }
    public function updaterecords_res(Request $req)
    {
        $useri_d = $req->inputuserid;
        $inputnumberinput = $req->inputnumberinput;
        // $inputlabnumberinput = $req->inputlabnumberinput;
        // $inputUtilization_status = $req->inputUtilization_status;

        $user = Complain_Master::find($useri_d);
        $user->software_name = $inputnumberinput;
        // $user->Lab_id = $inputlabnumberinput;
        // $user->Utilization_status = $inputUtilization_status;
        $user->update();
        return redirect()->back()->with("updatedsuccess", "Data has been updated");
    }

    public function feedback_form(Request $req)
    {
        $mytime = Carbon::now();
        $mytime->toDateTimeString();

        if (session('sessionuseremail')) {
            return view("/feedback_form");
        } else {
            return view("/student_login");
        }
    }

    public function feedback(Request $req)
    {
        $date = Carbon::now();
        $monthName = $date->format('F');

        $month = Carbon::now()->format('o');

        $date = Carbon::now();
        $monthName = $date->format('F');

        $month = Carbon::now()->format('o');
        $monthyear = $date->format('Y');

        // $select = faculty_feedback_gpa::get();

        $date = now()->format('d');

        // echo now()->format('d');

        $dates = Carbon::now()->subMonth()->format('F');

        if (now()->format('d') >= 11) {

            $studcheck = DB::table("feedback_forms")->where('std_name_id', $req->std_name_id)
                ->where('month', '=', $monthName)->where('year', '=', $monthyear)->first();
            if (isset($studcheck)) {
                echo
                    "<script>alert('Your Feedback Response is been already recorded.')
                window.location.href='/student_dashboard'
                </script>";

            } else {
                $feedback_insert = new feedback_form();
                $feedback_insert->month = $req->month;
                $feedback_insert->faculty = $req->faculty;
                $feedback_insert->batch = $req->batch;

                $feedback_insert->subject = $req->subject;
                $feedback_insert->std_name_id = $req->std_name_id;
                $feedback_insert->punctuality = $req->punctuality;

                $feedback_insert->course_coverage = $req->course_insert;
                $feedback_insert->course_coverage_r = $req->course_insert_r;

                $feedback_insert->technical_support = $req->technical;
                $feedback_insert->technical_support_r = $req->technical_r;

                $feedback_insert->clearing_doubt = $req->clearing;
                $feedback_insert->clearing_doubt_r = $req->clearing_r;

                $feedback_insert->exam_assignment = $req->exam;
                $feedback_insert->exam_assignment_r = $req->exam_r;

                $feedback_insert->book_utilization = $req->book;
                $feedback_insert->book_utilization_r = $req->book_r;

                $feedback_insert->student_appraisal = $req->student;
                $feedback_insert->student_appraisal_r = $req->student_r;

                $feedback_insert->computer_uptime = $req->computer;
                $feedback_insert->computer_uptime_r = $req->computer_r;

                $feedback_insert->remark = $req->remark;
                $feedback_insert->date = Date("y-m-d");
                $feedback_insert->year = $monthyear;

                $feedback_insert->save();

                echo
                    "<script>alert('Your Feedback Response is been successful Recorded thanks for the cooperating with us. .')
                    window.location.href='/student_dashboard'
                    </script>";

                // $feedback_insert['course_coverage'] = json_encode($req->course_insert);
                // $feedback_insert['technical_support'] = json_encode($req->technical);
                // $feedback_insert['clearing_doubt'] = json_encode($req->clearing);
                // $feedback_insert['exam_assignment'] = json_encode($req->exam);
                // $feedback_insert['book_utilization'] = json_encode($req->book);
                // $feedback_insert['student_appraisal'] = json_encode($req->student);
                // $feedback_insert['computer_uptime'] = json_encode($req->computer);
                // $feedback_insert->date_signature = $req->date_signature;
                // $feedback_insert->status = 'y';

            }

        } else {

            $studcheck = DB::table("feedback_forms")->where('std_name_id', $req->std_name_id)
                ->where('month', '=', $dates)->where('year', '=', $monthyear)->first();
            if (isset($studcheck)) {
                echo
                    "<script>alert('Your Feedback Response is been already recorded.')
                window.location.href='/student_dashboard'
                </script>";

            } else {
                $feedback_insert = new feedback_form();
                $feedback_insert->month = $req->month;
                $feedback_insert->faculty = $req->faculty;
                $feedback_insert->batch = $req->batch;

                $feedback_insert->subject = $req->subject;
                $feedback_insert->std_name_id = $req->std_name_id;
                $feedback_insert->punctuality = $req->punctuality;

                $feedback_insert->course_coverage = $req->course_insert;
                $feedback_insert->course_coverage_r = $req->course_insert_r;

                $feedback_insert->technical_support = $req->technical;
                $feedback_insert->technical_support_r = $req->technical_r;

                $feedback_insert->clearing_doubt = $req->clearing;
                $feedback_insert->clearing_doubt_r = $req->clearing_r;

                $feedback_insert->exam_assignment = $req->exam;
                $feedback_insert->exam_assignment_r = $req->exam_r;

                $feedback_insert->book_utilization = $req->book;
                $feedback_insert->book_utilization_r = $req->book_r;

                $feedback_insert->student_appraisal = $req->student;
                $feedback_insert->student_appraisal_r = $req->student_r;

                $feedback_insert->computer_uptime = $req->computer;
                $feedback_insert->computer_uptime_r = $req->computer_r;

                $feedback_insert->remark = $req->remark;
                $feedback_insert->date = Date("y-m-d");
                $feedback_insert->year = $monthyear;

                $feedback_insert->save();

                echo
                    "<script>alert('Your Feedback Response is been successful Recorded thanks for the cooperating with us. .')
                    window.location.href='/student_dashboard'
                    </script>";

                // $feedback_insert['course_coverage'] = json_encode($req->course_insert);
                // $feedback_insert['technical_support'] = json_encode($req->technical);
                // $feedback_insert['clearing_doubt'] = json_encode($req->clearing);
                // $feedback_insert['exam_assignment'] = json_encode($req->exam);
                // $feedback_insert['book_utilization'] = json_encode($req->book);
                // $feedback_insert['student_appraisal'] = json_encode($req->student);
                // $feedback_insert['computer_uptime'] = json_encode($req->computer);
                // $feedback_insert->date_signature = $req->date_signature;
                // $feedback_insert->status = 'y';

            }
        }
    }

    public function student_dashboard()
    {
        if (session('sessionuseremail')) {
            $student = DB::table("students")
            ->join('batches', 'students.Batch_ID', '=', 'batches.id')
            ->where('Student_email', session('sessionuseremail'))
            ->select('batches.*', 'students.*', 'batches.Current_Sem AS sem')
            ->first();
            $examcheck = DB::table("exam_assignments")->orderBy('id', 'desc')->limit(1)->get();
            $announcement = DB::table('announcements')->orderBy('id', 'desc')->limit(1)->get();
            $attendances = DB::table('attendances')->where('Std_ID', $student->Std_id)->orderBy('id', 'desc')->limit(1)->get();
            $student_data = DB::table('examsubjectmasters')
                ->join('usermodels', 'usermodels.Std_ID', 'examsubjectmasters.std_id');
            $jobs = DB::table('jobs')->where('status', '1')->orderBy('id', 'desc')->limit(1)->get();
            
            $loggedEmail = session('sessionuseremail');

            $examAssignedBatch = DB::table('batches')
            ->join('students', 'students.Batch_ID', '=', 'batches.id')
            ->join('exam_assignments', 'exam_assignments.Std_id', '=', 'students.Std_id')
            ->select('batches.*', 'students.*', 'exam_assignments.*',
            'exam_assignments.Start_Time',
            'exam_assignments.End_Time')
            ->where('students.Student_email', '=', $loggedEmail)
            ->where('exam_assignments.Exam_status', '=', 1)
            ->orderBy('exam_assignments.id', 'desc')->limit(1)
            ->get();

            $date = Carbon::now()->format('d-m-Y');

            return view('student_dashboard', compact('announcement', 'attendances', 'student', 'student_data', 'examcheck', 'jobs', 'examAssignedBatch'));
            // return $examAssignedBatch;
        } else {
            echo
                "<script>alert('Please Login First.')
        window.location.href='/login'
        </script>";
        }
    }


    public function form_fetch(Request $req)
    {
        if (session('sessionuseremail')) {
            $date = Carbon::now();
            $monthName = $date->format('F');
            $month = Carbon::now()->format('o');

            $date = now()->format('d');

            // echo now()->format('d');

            $dates = Carbon::now()->subMonth()->format('F');

            if (now()->format('d') >= 20) {
                $data = feedback_form::
                    where('month', '=', $monthName)
                    ->where('year', '=', $month)
                    ->get();
            } else {
                $data = feedback_form::
                    where('month', '=', $dates)
                    ->where('year', '=', $month)->get();
            }
            return view("/form_fetch", compact('data'));
        } else {
            return redirect('/login')->withErrors(['error' => 'Please login first']);
        }

    }

    public function filter_(Request $res)
    {

        $from = $res->from;
        $to = $res->to;

        $string = feedback_form::where('month', 'LIKE', '%' . $from . '%')
            ->where('Batch', 'LIKE', '%' . $to . '%')->get();
        if (isset($string)) {
            return view("/form_fetch", compact('string'));
        }
    }

    // exam fetch
    public function Fetch_Exam(Request $req)
    {
        $studcheck = DB::table("usermodels")->where('email', session('sessionuseremail'))->first();

        if (session('sessionuseremail')) {
            $session = session('std_id');
            //  echo $studcheck->std_id;

            $examfetch = DB::table('modulars')
                ->where('Sem_ID', '1')->where('Std_ID', $studcheck->std_id)
                ->orderBy('id', 'desc')->get();

            $examfetch2 = DB::table('modulars')
                ->where('Sem_ID', '2')->where('Std_ID', $studcheck->std_id)
                ->orderBy('id', 'desc')->get();

            $examfetch3 = DB::table('modulars')
                ->where('Sem_ID', '3')->where('Std_ID', $studcheck->std_id)
                ->orderBy('id', 'desc')->get();

            $examfetch4 = DB::table('modulars')
                ->where('Sem_ID', '4')->where('Std_ID', $studcheck->std_id)
                ->orderBy('id', 'desc')->get();

            $examfetch5 = DB::table('modulars')
                ->where('Sem_ID', '5')->where('Std_ID', $studcheck->std_id)
                ->orderBy('id', 'desc')->get();

            $examfetch6 = DB::table('modulars')
                ->where('Sem_ID', '6')->where('Std_ID', $studcheck->std_id)
                ->orderBy('id', 'desc')->get();

            return view('examfetch', compact('examfetch', 'examfetch2', 'examfetch3', 'examfetch4', 'examfetch5', 'examfetch6'));

        } else {
            echo
                "<script>alert('Please Login First.')
             window.location.href='/login'
             </script>";
        }
    }
    public function announcement()
    {
        if (session('sessionuseremail')) {
            $announcement = DB::table('announcements')->orderBy('id', 'desc')->get();
            return view('announcement', compact('announcement'));
        } else {
            echo
                "<script>alert('Please Login First.')

                
           window.location.href='/login'
           </script>";
        }

    }
    public function attendances()
    {
        $studcheck = DB::table("usermodels")->where('email', session('sessionuseremail'))->first();

        if (session('sessionuseremail')) {
            $attendances = DB::table('attendances')->where('Std_ID', $studcheck->std_id)->orderBy('id', 'desc')->get();
            return view('attendances', compact('attendances'));
        } else {
            echo
                "<script>alert('Please Login First.')
           window.location.href='/login'
           </script>";
        }
    }

    // data table
    public function student_profile()
    {
        $studcheck = DB::table("usermodels")->where('email', session('sessionuseremail'))->first();

        if (session('sessionuseremail')) {

            $std_profile = DB::table('students')->where('Std_ID', $studcheck->std_id)->get();
            // echo session("std_id");
            return view("student_profile", compact("std_profile"));
        } else {
            echo
                "<script>alert('Please Login First.')
            window.location.href='/login'
            </script>";
        }
    }

    public function gpa_calculates_get()
    {

        $date = Carbon::now();
        $monthName = $date->format('F');

        $month = Carbon::now()->format('o');

        $date = Carbon::now();
        $monthName = $date->format('F');

        $month = Carbon::now()->format('o');

        // $select = faculty_feedback_gpa::get();

        $date = now()->format('d');

        //echo now()->format('d');

        $dates = Carbon::now()->subMonth()->format('F');
		//dd($dates);

        if (now()->format('d') >= 20) {
            try {
                DB::statement("SET SESSION wait_timeout = 1000");

        $gpa_calculates = DB::table('feedback_forms')->where('month' , '=' ,$monthName)->get();

                foreach ($gpa_calculates as $fp) {
                    $studcheck = DB::table("gpa_calculates")
                        ->where('batch', $fp->batch)
                        ->where('month', $monthName)
                        ->where('year', $fp->year)
                        ->first();

                    if (!$studcheck) {
                        set_time_limit(2000);
                        DB::select('call test_proc(?,?,?)', array($fp->batch, $fp->month, $fp->year));
                        DB::select('call faculty_feedback()');
                        // DB::select('call FEEDBACK_FALG_UPDATE()');

                    }
                }

                return redirect("/gpa_calculates");
            } catch (Exception $e) {
                // Handle the exception appropriately (e.g., log the error, display a user-friendly message)

                return response()->json(['error' => 'An error occurred.'], 500);
            }
        } else {
            try {
                DB::statement("SET SESSION wait_timeout = 1000");

        $gpa_calculates = DB::table('feedback_forms')->where('month' , '=' ,$dates)->get();

                foreach ($gpa_calculates as $fp) {
                    $studcheck = DB::table("gpa_calculates")
                        ->where('batch', $fp->batch)
                        ->where('month', $dates)
                        ->where('year', $fp->year)
                        ->first();

                    if (!$studcheck) {
						//dd($fp->batch);
                        set_time_limit(2000);
                        DB::select('call test_proc(?,?,?)', array($fp->batch, $dates, $fp->year));
                        DB::select('call faculty_feedback()');
                        // DB::select('call FEEDBACK_FALG_UPDATE()');
                    }
                }
                // echo "JJ";
                return redirect("/gpa_calculates");
            } catch (Exception $e) {
                // Handle the exception appropriately (e.g., log the error, display a user-friendly message)
                return response()->json(['error' => $e], 500);
            }
        }
    }
    public function gpa_calculates()
    {
        if (session('sessionuseremail')) {

            $date = Carbon::now();
            $monthName = $date->format('F');

            $month = Carbon::now()->format('o');

            $date = Carbon::now();
            $monthName = $date->format('F');

            $month = Carbon::now()->format('o');

            // $select = faculty_feedback_gpa::get();

            $date = now()->format('d');

            // echo now()->format('d');

            $dates = Carbon::now()->subMonth()->format('F');

            if (now()->format('d') >= 20) {
                $gpa_calculates = DB::table('gpa_calculates')
                    ->where('month', '=', $monthName)
                    ->orderby('id', 'ASC')->get();
            } else {
                $gpa_calculates = DB::table('gpa_calculates')
                    ->where('month', '=', $dates)
                    ->orderby('id', 'ASC')->get();
            }

            return view("gpa_calculates", compact('gpa_calculates'));
        } else {
            return redirect('/login')->withErrors(['error' => 'Please login first']);
        }
    }

    public function faculty_feedback()
    {

        if (session('sessionuseremail')) {
            set_time_limit(6000);
            // ini_set('memory_limit', '256M');

            $date = Carbon::now();
            $monthName = $date->format('F');

            $month = Carbon::now()->format('o');

            $date = Carbon::now();
            $monthName = $date->format('F');

            $month = Carbon::now()->format('o');

            // $select = faculty_feedback_gpa::get();

            $date = now()->format('d');

            // echo now()->format('d');

            $dates = Carbon::now()->subMonth()->format('F');

            if (now()->format('d') >= 20) {

                $faculty_feedback_gpas = DB::table("batch_gpa")
                    ->where('Month', '=', $monthName)
                    ->where('Year', '=', $month)
                    ->where('batch_gpa.batch_count', '>', 0) // Exclude batches with a zero batch count
                //->orderBy('batch_gpa.gpa', 'DESC')
                // ->limit(15)

                    ->get();

                $select = DB::table('faculty_feedback_gpas')
                    ->join('users', 'users.id', '=', 'faculty_feedback_gpas.faculty_id')
                    ->where('faculty_feedback_gpas.month', $monthName)
                    ->where('faculty_feedback_gpas.year', $month)
                    ->distinct('faculty_feedback_gpas.batch')
                //  ->limit(15)
                    ->get();

                $faculty_batch = gpa_calculate::join('faculty_feedback_gpas', 'faculty_feedback_gpas.batch', 'gpa_calculates.batch')
                    ->join('batches', 'batches.Batch', 'gpa_calculates.batch')
                    ->where('faculty_feedback_gpas.month', $monthName) // Assuming $dates is the value for the month condition
                    ->where('faculty_feedback_gpas.year', $month) // Assuming $month is the value for the year condition
                // ->select('faculty_feedback_gpas.batch')
                // ->distinct('faculty_feedback_gpas.batch')
                // ->orderBy('gpa_calculates.id', 'DESC')
                    ->limit(55000)
                    ->get();

                // echo $faculty_batch;

                $sum = $faculty_feedback_gpas->sum('batch_count');

                $batch_sum = 0;

                foreach ($select as $item) {
                    $batch_sum += (int) $item->batch;
                }

                $batch_count = $batch_sum;

                $average = $faculty_feedback_gpas->average('gpa');

            } else {

                $faculty_feedback_gpas = DB::table("batch_gpa")
                    ->where('Month', '=', $dates)
                    ->where('Year', '=', $month)
                    ->where('batch_gpa.batch_count', '>', 0) // Exclude batches with a zero batch count
                //->orderBy('batch_gpa.gpa', 'DESC')
                //->limit(15)

                    ->get();

                $select = DB::table('faculty_feedback_gpas')
                    ->join('users', 'users.id', '=', 'faculty_feedback_gpas.faculty_id')
                    ->where('faculty_feedback_gpas.month', $dates)
                    ->where('faculty_feedback_gpas.year', $month)
                    ->distinct('faculty_feedback_gpas.batch')
                //  ->limit(15)
                    ->get();

                $faculty_batch = gpa_calculate::join('faculty_feedback_gpas', 'faculty_feedback_gpas.batch', 'gpa_calculates.batch')
                    ->join('batches', 'batches.Batch', 'gpa_calculates.batch')
                    ->where('faculty_feedback_gpas.month', $dates) // Assuming $dates is the value for the month condition
                    ->where('faculty_feedback_gpas.year', $month) // Assuming $month is the value for the year condition
                // ->select('faculty_feedback_gpas.batch')
                // ->distinct('faculty_feedback_gpas.batch')
                // ->orderBy('gpa_calculates.id', 'DESC')
                    ->limit(55000)
                    ->get();

                // echo $faculty_batch;

                $sum = $faculty_feedback_gpas->sum('batch_count');

                $batch_sum = 0;

                foreach ($select as $item) {
                    $batch_sum += (int) $item->batch;
                }

                $batch_count = $batch_sum;

                $average = $faculty_feedback_gpas->average('gpa');
            }

            return view("faculty_feedback", compact('select', 'faculty_batch', 'faculty_feedback_gpas', 'sum', 'average'));

        } else {
            return redirect('/login')->withErrors(['error' => 'Please login first']);
        }

    }

    public function month_year_f(Request $res)
    {
        $date = Carbon::now();
        $monthName = $date->format('F');

        $month = Carbon::now()->format('o');

        $date = Carbon::now();
        $monthName = $date->format('F');

        $month = Carbon::now()->format('o');

        // $select = faculty_feedback_gpa::get();

        $date = now()->format('d');
        $from = $res->month;
        $to = $res->year;
        // echo now()->format('d');

        $dates = Carbon::now()->subMonth()->format('F');


        $faculty_feedback_gpas = DB::table("batch_gpa")
            ->where('month', 'LIKE', '%' . $from . '%')
            ->where('year', 'LIKE', '%' . $to . '%')
            ->where('batch_gpa.batch_count', '>', 0) // Exclude batches with a zero batch count
        //->orderBy('batch_gpa.gpa', 'DESC')
            ->limit(25)

            ->get();

        $select = DB::table('faculty_feedback_gpas')
            ->join('users', 'users.id', '=', 'faculty_feedback_gpas.faculty_id')
            ->where('faculty_feedback_gpas.month', 'LIKE', '%' . $from . '%')
            ->where('faculty_feedback_gpas.year', 'LIKE', '%' . $to . '%')
            ->distinct('faculty_feedback_gpas.batch')
        //->limit(15)
            ->get();

        $faculty_batch = gpa_calculate::join('faculty_feedback_gpas', 'faculty_feedback_gpas.batch', 'gpa_calculates.batch')
            ->join('batches', 'batches.Batch', 'gpa_calculates.batch')
            ->where('faculty_feedback_gpas.month', 'LIKE', '%' . $from . '%')
            ->where('faculty_feedback_gpas.year', 'LIKE', '%' . $to . '%') // ->select('faculty_feedback_gpas.batch')
        // ->distinct('faculty_feedback_gpas.batch')
        // ->orderBy('gpa_calculates.id', 'DESC')
            ->limit(40000)
            ->get();

        // echo $faculty_batch;

        $sum = $faculty_feedback_gpas->sum('batch_count');

        $batch_sum = 0;

        foreach ($select as $item) {
            $batch_sum += (int) $item->batch;
        }

        $batch_count = $batch_sum;

        $average = $faculty_feedback_gpas->average('gpa');

        return view("faculty_feedback", compact('select', 'faculty_batch', 'faculty_feedback_gpas', 'sum', 'average'));

    }

    public function filter_batch(Request $res)
    {
        $to = $res->to;
        $from = $res->month;

        $date = Carbon::now();
        $monthName = $date->format('F');

        $month = Carbon::now()->format('o');

        $date = now()->format('d');

        // echo now()->format('d');

        $dates = Carbon::now()->subMonth()->format('F');

        if (now()->format('d') >= 11) {
            $cummulative = DB::table('cummulative')
            // ->where('month' ,'=' ,$monthName)
            // ->where('year' ,'=' ,$month)
                ->where('batch', 'LIKE', '%' . $to . '%')
                ->where('month', 'LIKE', '%' . $from . '%')

                ->get();
        } else {
            $cummulative = DB::table('cummulative')
            // ->where('month' ,'=' ,$dates)
            // ->where('year' ,'=' ,$month)
                ->where('batch', 'LIKE', '%' . $to . '%')
                ->where('month', 'LIKE', '%' . $from . '%')

                ->get();
        }
        return view("cummulative", compact('cummulative'));

    }

    public function cummulative(Request $res)
    {
        if (session('sessionuseremail')) {
            $to = $res->to;
            $from = $res->month;

            $date = Carbon::now();
            $monthName = $date->format('F');

            $month = Carbon::now()->format('o');

            $date = now()->format('d');

            // echo now()->format('d');

            $dates = Carbon::now()->subMonth()->format('F');

            if (now()->format('d') >= 20) {
                $cummulative = DB::table('cummulative')
                    ->where('month', '=', $monthName)
                    ->where('year', '=', $month)
                //             ->where('batch' , 'LIKE' , '%'.$to.'%')
                // ->where('month' , 'LIKE' , '%'.$from.'%')

                    ->get();
            } else {
                $cummulative = DB::table('cummulative')
                    ->where('month', '=', $dates)
                    ->where('year', '=', $month)
                // ->where('batch' , 'LIKE' , '%'.$to.'%')
                // ->where('month' , 'LIKE' , '%'.$from.'%')

                    ->get();
            }

            return view("cummulative", compact('cummulative'));
        } else {
            return redirect('/login')->withErrors(['error' => 'Please login first']);
        }
    }

    public function month_year(Request $res)
    {
        $from = $res->month;
        $to = $res->year;

        // $fetchtoday = Complain_Master::whereDate('date_of_reg','=',$mytime)->get();

        // $fetchtoday = DB::table("PROTAL_BAT_STUDENT_FEEDBACK_V")->whereBetween('Date_of_Complain',[$from,$to])->get();
        $fetchtoday = Complain_Master::orderBy('created_at', 'desc')->get();

        $countcomplain = Complain_Master::where('Status', 'like', '%2%')->get();
        $count = $countcomplain->count();

        $countcomplains = Complain_Master::where('Status', 'like', '%0%')->get();
        $count1 = $countcomplains->count();

        $Complain_Master = Complain_Master::all();

        $count_bat = DB::table("PROTAL_BAT_STUDENT_FEEDBACK_V")
            ->where('year', 'LIKE', '%' . $to . '%')
            ->where('month', 'LIKE', '%' . $from . '%')
            ->get();

        $count = DB::table('PROTAL_BAT_STUDENT_FEEDBACK_V')
            ->where('year', 'LIKE', '%' . $to . '%')
            ->where('month', 'LIKE', '%' . $from . '%')
            ->count('batch');

        $sum = DB::table('PROTAL_BAT_STUDENT_FEEDBACK_V')
            ->where('year', 'LIKE', '%' . $to . '%')
            ->where('month', 'LIKE', '%' . $from . '%')
            ->sum('feedback_Average');

        //echo $count;
        //echo $sum;

        $result = 0;
        if ($count > 0) {
            $result = $sum / $count;
        }

        foreach ($count_bat as $fp) {
            $record = DB::table("PROTAL_BAT_STUDENT_FEEDBACK_V")
                ->join('std_nt_fill_fd', 'std_nt_fill_fd.Batch', 'PROTAL_BAT_STUDENT_FEEDBACK_V.Batch')
                ->where('std_nt_fill_fd.Batch', $fp->Batch)
                ->get();
        }

        if (isset($count_bat)) {
            return view("/student_portal", compact('count_bat', 'fetchtoday', 'count', 'count1', 'Complain_Master', 'result'));
        }

        return view("/student_portal", compact('count_bat', 'fetchtoday', 'count', 'count1', 'Complain_Master', 'result'));

    }

    public function stdportal(Request $res)
    {
        if (session('sessionuseremail')) {
            $date = Carbon::now();
            $monthName = $date->format('F');
            // echo $monthName;

            $month = Carbon::now()->format('o');

            $mytime = Carbon::now();
            $mytime->toDateTimeString();

            $date = now()->format('d');

            //echo now()->format('d');

            $dates = Carbon::now()->subMonth()->format('F');

            if (now()->format('d') >= 20) {
                $from = $res->month;
                $to = $res->year;
                $count_bat = DB::table("PROTAL_BAT_STUDENT_FEEDBACK_V")
                    ->where('month', '=', $monthName)
                    ->where('year', '=', $month)

                    ->get();

                $count = DB::table('PROTAL_BAT_STUDENT_FEEDBACK_V')
                    ->where('month', $dates)
                    ->where('year', $month)
                    ->count('batch');

                $sum = DB::table('PROTAL_BAT_STUDENT_FEEDBACK_V')
                    ->where('month', $dates)
                    ->where('year', $month)
                    ->sum('feedback_Average');

                //echo $count;
                //echo $sum;

                $result = 0;
                if ($count > 0) {
                    $result = $sum / $count;
                }

            } else {
                $from = $res->month;
                $to = $res->year;
                $count_bat = DB::table("PROTAL_BAT_STUDENT_FEEDBACK_V")

                    ->where('month', '=', $dates)
                    ->where('year', '=', 2024)

                    ->get();

                $count = DB::table('PROTAL_BAT_STUDENT_FEEDBACK_V')
                    ->where('month', $dates)
                    ->where('year', 2024)
                    ->count('batch');

                $sum = DB::table('PROTAL_BAT_STUDENT_FEEDBACK_V')
                    ->where('month', $dates)
                    ->where('year', 2024)
                    ->sum('feedback_Average');

                //echo $count;
                //echo $sum;

                $result = 0;
                if ($count > 0) {
                    $result = $sum / $count;
                }

                //echo $result;

            }
            return view("student_portal", compact("count_bat", "sum", "count", "result"));

        } else {
            return redirect('/login')->withErrors(['error' => 'Please login first']);
        }
    }

    public function std_view()
    {
        $date = Carbon::now();
        $monthName = $date->format('F');

        $month = Carbon::now()->format('o');

        $std_fd = DB::table("std_nt_fill_fd")
        //  ->where('month' ,'=' ,$monthName)
        // ->where('year' ,'=' ,$month)
            ->get();
        return view("/std_nt_fill_fd", compact('std_fd'));
    }

    public function batch(Request $req)
    {
        $date = Carbon::now();
        $monthName = $date->format('F');

        $month = Carbon::now()->format('o');
        $count_bat = DB::table("PROTAL_BAT_STUDENT_FEEDBACK_V")
            ->get();
        $id = $req->post("labid_");
        //echo $id;
        foreach ($count_bat as $fp) {
            $record = DB::table("PROTAL_BAT_STUDENT_FEEDBACK_V")
                ->join('std_nt_fill_fd', 'std_nt_fill_fd.Batch', 'PROTAL_BAT_STUDENT_FEEDBACK_V.Batch')
                ->where('std_nt_fill_fd.Batch', $fp->Batch)
                ->where('month', '=', $monthName)
                ->where('year', '=', $month)
                ->get();
        }
        foreach ($record as $r) {
            $user = $r;
            echo json_encode($user);

        }
    }
    public function export_Users(Request $request)
    {
        // $data  = DB::table("cummulative")->get();
        return Excel::download(new ExportUser, 'cummulative.xlsx');
    }

    public function export_std(Request $request)
    {
        // $data  = DB::table("cummulative")->get();
        return Excel::download(new Export_User, 'remainingstudent.xlsx');
    }

    public function search2(Request $request)
    {
        if ($request->ajax()) {
            $output = '';
            $query_month = $request->get('month');
            $query_batch = $request->get('batch');
            $query_year = $request->get('year');

            $feedbackForms = feedback_form::join('users', 'users.id', 'feedback_forms.faculty')
                ->join('students', 'students.Std_id', 'feedback_forms.std_name_id')
                ->where('feedback_forms.month', '=', $query_month);

            if ($query_batch != '') {
                $feedbackForms->where('feedback_forms.batch', '=', $query_batch);
            }

            if ($query_year) {
                $feedbackForms->where('feedback_forms.year', '=', $query_year);
            } else {
                $feedbackForms->whereRaw('1 = 0'); // Add a condition that is always false
            }

            // if ($query_batch != '') {
            //     $feedbackForms->where('feedback_forms.batch', '=', $query_batch);
            // }

            // if ($query_year != '') {
            //     $feedbackForms->where('feedback_forms.year', '=', $query_year);
            // }

            $data = $feedbackForms->paginate(10);

            $total_row = $data->total();
            if ($total_row > 0) {
                foreach ($data as $row) {
                    $output .= '
                    <tr>
					    <td>' . $row->date . ' </td>
                        <td>' . $row->name . ' </td>
                        <td>' . $row->Std_Name . ' </td>
                        <td> ' . $row->batch . ' </td>
                        <td> ' . $row->subject . ' </td>';

                    // Example conditional statement for punctuality
                    if ($row->punctuality === '4') {
                        $output .= '<td class="late-punctuality">Every Classes</td>';
                    } else if ($row->punctuality === '3') {
                        $output .= '<td class="late-punctuality">Most of the classes</td>';
                    } else if ($row->punctuality === '2') {
                        $output .= '<td class="late-punctuality">Rarely</td>';
                    } else {
                        $output .= '<td>Never</td>';
                    }

                    // Example conditional statement for course_coverage

                    if ($row->course_coverage === '4') {
                        $output .= '<td class="late-course_coverage">Yes</td>';
                    } else {
                        $output .= '<td>' . $row->course_coverage_r . '</td>';
                    }

                    // Example conditional statement for technical_support

                    if ($row->technical_support === '4') {
                        $output .= '<td class="late-technical_support">
                                Excellent
                                </td>';
                    } else if ($row->technical_support === '3') {
                        $output .= '<td class="late-technical_support">
                                Good
                                </td>';
                    } else if ($row->technical_support === '2') {
                        $output .= '<td class="late-technical_support">Average</td>';
                    } else {
                        $output .= '<td>' . $row->technical_support_r . '</td>';
                    }

                    // Example conditional statement for clearing_doubt

                    if ($row->clearing_doubt === '4') {
                        $output .= '<td class="late-clearing_doubt ">
                                Excellent
                                </td>';
                    } else if ($row->clearing_doubt === '3') {
                        $output .= '<td class="late-clearing_doubt ">
                                Good
                                </td>';
                    } else if ($row->clearing_doubt === '2') {
                        $output .= '<td class="late-clearing_doubt ">Average</td>';
                    } else {
                        $output .= '<td>' . $row->clearing_doubt_r . '</td>';
                    }

                    // Example conditional statement for exam_assignment

                    if ($row->exam_assignment === '4') {
                        $output .= '<td class="late-exam_assignment">Yes</td>';
                    } else {
                        $output .= '<td>' . $row->exam_assignment_r . '</td>';
                    }

                    // Example conditional statement for book_utilization

                    if ($row->book_utilization === '4') {
                        $output .= '<td class="late-book_utilization">Every Classes</td>';

                    } else if ($row->book_utilization === '3') {
                        $output .= '<td class="late-book_utilization">Most of the classes</td>';

                    } else if ($row->book_utilization === '2') {
                        $output .= '<td class="late-book_utilization">Rarely</td>';
                    } else {
                        $output .= '<td>' . $row->book_utilization_r . '</td>';
                    }

                    // Example conditional statement for student_appraisal

                    if ($row->student_appraisal === '4') {
                        $output .= '<td class="late-student_appraisal">Yes</td>';
                    } else {
                        $output .= '<td>No, specify the topics missed</td>';
                    }

                    // Example conditional statement for computer_uptime

                    if ($row->computer_uptime === '4') {
                        $output .= '<td class="late-computer_uptime">No Issues in Computer System/Software</td>';

                    } else if ($row->computer_uptime === '3') {
                        $output .= '<td class="late-computer_uptime">Issues reported & resloved in reasonable time</td>';

                    } else if ($row->computer_uptime === '2') {
                        $output .= '<td class="late-computer_uptime">Issue resolution is delayed</td>';
                    } else {
                        $output .= '<td>' . $row->computer_uptime_r . '</td>';
                    }
                    '</tr>';
                }
            } else if ($total_row > 0) {
                $output = '
                    <tr>
                        <td align="center" colspan="12">No Data Found</td>
                    </tr>
                ';
            }

            $pagination = $data->links('pagination::bootstrap-5')->toHtml();

            $data = array(
                'table_data' => $output,
                'pagination' => $pagination,
                'total_data' => $total_row,
            );
            return response()->json($data);
        }
    }

    public function search3(Request $req)
    {
        if ($req->ajax()) {
            $output = '';
            $query_month = $req->get('month');
            $query_batch = $req->get('batch');
            $query_year = $req->get('year');

            $cummulative = DB::table("cummulative")->where('month', $query_month);

            if ($query_batch != '') {
                $cummulative->where('batch', '=', $query_batch);
            }

            if ($query_year != '') {
                $cummulative->where('year', '=', $query_year);
            }

            $cummulative = $cummulative->paginate(10);

            $total_row = $cummulative->total();
            if ($total_row > 0) {
                foreach ($cummulative as $row) {
                    $output .=
                    '<tr>
                        <td>' . $row->faculty_name . '</td>
                        <td>' . $row->batch . '</td>
                        <td>' . $row->punctuality . '</td>
                        <td>' . $row->course_coverage . '</td>
                        <td>' . $row->technical_support . '</td>
                        <td>' . $row->clearing_doubt . '</td>
                        <td>' . $row->exam_assignment . '</td>
                        <td>' . $row->book_utilization . '</td>
                        <td>' . $row->student_appraisal . '</td>
                        <td>' . $row->computer_uptime . '</td>
                        <td>' . $row->gpa . '</td>
                        <td>' . $row->total_feedback_std . '</td>
                        <td>' . $row->percent . '</td>
                        <td>' . $row->total_student . '</td>
                    </tr>';
                }
            } else if ($total_row > 0) {
                $output = '
                    <tr>
                        <td align="center" colspan="12">No Data Found</td>
                    </tr>
                ';
            }

            $pagination = $cummulative->links('pagination::bootstrap-5')->toHtml();

            $cummulativeData = array(
                'table_data' => $output,
                'pagination' => $pagination,
                'total_data' => $total_row,
            );
            return response()->json($cummulativeData);
        }
    }

    public function export_feedback(Request $request)
    {
        // $data  = DB::table("cummulative")->get();
        return Excel::download(new Export_feedback, 'Feedback Remarks Sheet.xlsx');
    }


    
   
}



