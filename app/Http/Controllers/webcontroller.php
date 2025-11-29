<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\webcareercoursemodel;
use App\Models\webshortcoursemodel;
use App\Models\webcarouselmodel;
use App\Models\webconnectmodel;
use App\Models\webeventsmodel;
use App\Models\webcountermodel;
use App\Models\webplacementsmodel;
use App\Models\webprojectofmonthmodel;
use App\Models\webstudentofmonthmodel;
use App\Models\webwinnercirclemodel;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
class webcontroller extends Controller
{
   public function loginv()
   {
      return View("web.signin");
   }
   public function loginadminweb(Request $req)
   {
      $email = $req->input('email');
      $password = $req->input('password');

      $admin = DB::table('admintbls')->where('email', $email)->where('password', $password)->first();
      if($admin){
         $adminName = $admin->name;
         session(['adminwebname'=>$adminName]);
         $webcareercoursecount = webcareercoursemodel::all()->count();
         $webcarouselmodelcount = webcarouselmodel::all()->count();
         $webplacementsmodelcount = webplacementsmodel::all()->count();
         $webconnectmodelcount = webconnectmodel::all()->count();
         $webeventsmodelcount = webeventsmodel::all()->count();
         $webcountermodelcount = webcountermodel::all()->count();
         $webprojectofmonthmodelcount = webprojectofmonthmodel::all()->count();
         $webstudentofmonthmodecount = webstudentofmonthmodel::all()->count();
         $webwinnercirclemodelcount = webwinnercirclemodel::all()->count();
         $webshortcoursemodelcount = webshortcoursemodel::all()->count();

         return View(
            "web.admin.index",
            compact(
               "webcareercoursecount",
               "webcarouselmodelcount",
               "webplacementsmodelcount",
               "webconnectmodelcount",
               "webeventsmodelcount",
               "webcountermodelcount",
               "webprojectofmonthmodelcount",
               "webstudentofmonthmodecount",
               "webwinnercirclemodelcount",
               "webshortcoursemodelcount"
            )
         );
      } else {
         return View('web.signin');
      }
      

   }

   public function adminweblogout()
   {
      session()->forget("adminwebname");
      return redirect("/adminweblogin");
   }
   public function indexget()
   {
      $year = Carbon::now()->format('Y');
      $carousel = webcarouselmodel::all();
      $careercourse = webcareercoursemodel::all();
      $shortcourse = webshortcoursemodel::all();
      $counter = webcountermodel::all();
      $event = webeventsmodel::all();
      $placements = webplacementsmodel::all();
      $studentofmonth = webstudentofmonthmodel::where('year', $year)->get();
      $projectofmonth = webprojectofmonthmodel::where('year', $year)->get();
      $winner = webwinnercirclemodel::all();

      return View("web.index", compact("winner", "projectofmonth", "studentofmonth", "carousel", "careercourse", "shortcourse", "counter", "event", "placements"));
   }

   public function aboutget()
   {
      return View("web.about");
   }
   public function contactget()
   {
      return View("web.contact");
   }
   public function eventget()
   {
      $event = webeventsmodel::where('year', date('Y'))->get();
      return View("web.events", compact("event"));
   }
   public function onlinevarsityget()
   {
      return View("web.onlinevarsity");
   }
   public function careercourseget()
   {
      $careercourse = webcareercoursemodel::all();
      return View("web.careercourse", compact("careercourse"));
   }
   public function shortcourseget()
   {
      $shortcourse = webshortcoursemodel::all();
      return View("web.shortcourse", compact("shortcourse"));
   }
   public function technologycourseget()
   {
      return View("web.technology");
   }
   public function adminindex()
   {

      $webcareercoursecount = webcareercoursemodel::all()->count();
      $webcarouselmodelcount = webcarouselmodel::all()->count();
      $webplacementsmodelcount = webplacementsmodel::all()->count();
      $webconnectmodelcount = webconnectmodel::all()->count();
      $webeventsmodelcount = webeventsmodel::all()->count();
      $webcountermodelcount = webcountermodel::all()->count();
      $webprojectofmonthmodelcount = webprojectofmonthmodel::all()->count();
      $webstudentofmonthmodecount = webstudentofmonthmodel::all()->count();
      $webwinnercirclemodelcount = webwinnercirclemodel::all()->count();
      $webshortcoursemodelcount = webshortcoursemodel::all()->count();
      $webcareercoursecount = webcareercoursemodel::all()->count();
      return View(
         "web.admin.index",
         compact(
            "webcareercoursecount",
            "webcarouselmodelcount",
            "webplacementsmodelcount",
            "webconnectmodelcount",
            "webeventsmodelcount",
            "webcountermodelcount",
            "webprojectofmonthmodelcount",
            "webstudentofmonthmodecount",
            "webwinnercirclemodelcount",
            "webshortcoursemodelcount"
         )
      );
   }
   public function admincarousel()
   {
      if (session("adminwebname") != "") {
         $carousemodel = webcarouselmodel::all();
         return View("web.admin.carousel", compact("carousemodel"));
      } else {
         return View("web.signin");
      }

   }
   public function admincareercourses()
   {
      if (session("adminwebname") != "") {
         $careercourse = webcareercoursemodel::all();
         return View("web.admin.careercourses", compact("careercourse"));
      } else {
         return View("web.signin");
      }

   }
   public function adminshortcourses()
   {
      if (session("adminwebname") != "") {
         $shortcourse = webshortcoursemodel::all();
         return View("web.admin.shortcourse", compact("shortcourse"));
      } else {
         return View("web.signin");
      }

   }
   public function admincounter()
   {

      if (session("adminwebname") != "") {
         $counters = webcountermodel::all();
         return View("web.admin.counter", compact("counters"));
      } else {
         return View("web.signin");
      }
   }
   public function adminevent()
   {

      if (session("adminwebname") != "") {
         $event = webeventsmodel::all();
         return View("web.admin.event", compact("event"));
      } else {
         return View("web.signin");
      }
   }
   public function adminplacements()
   {

      if (session("adminwebname") != "") {
         $placements = webplacementsmodel::all();
         return View("web.admin.placements", compact("placements"));
      } else {
         return View("web.signin");
      }
   }
   public function adminstudentofmonth()
   {

      if (session("adminwebname") != "") {
         $studentofmonth = webstudentofmonthmodel::all();
         return View("web.admin.studentofmonth", compact("studentofmonth"));
      } else {
         return View("web.signin");
      }
   }
   public function adminprojectofmonth()
   {

      if (session("adminwebname") != "") {
         $projectofmonth = webprojectofmonthmodel::all();
         return View("web.admin.projectofmonth", compact("projectofmonth"));
      } else {
         return View("web.signin");
      }
   }
   public function adminwinner()
   {

      if (session("adminwebname") != "") {
         $winner = webwinnercirclemodel::all();
         return View("web.admin.winnercircle", compact("winner"));
      } else {
         return View("web.signin");
      }
   }
   public function admincarouselpost(Request $r)
   {
      $carousemodel = new webcarouselmodel();
      $carousemodel->heading1 = $r->heading1input;
      $carousemodel->heading2 = $r->heading2input;
      $carousemodel->description = $r->descriptioninput;
      $image = $r->file("imageinput");
      $ext = rand() . "." . $image->getClientOriginalName();
      $image->move("images/carouselimages/", $ext);
      $carousemodel->image = $ext;
      $carousemodel->save();
      return redirect()->back()->with("insertsuccess", "Data has been added");
   }
   public function deletecarousel($id)
   {
      $carousemodel = webcarouselmodel::find($id);
      $carousemodel->delete();
      return redirect()->back();
   }
   public function editcarousel(Request $req)
   {
      $uid = $req->post("uid");
      $record = DB::table("webcarouselmodels")->where("id", $uid)->get();
      foreach ($record as $r) {
         $user = $r;
         echo json_encode($user);
      }
   }
   public function updatecarousel(Request $req)
   {
      $id = $req->carouselidid;
      $record = webcarouselmodel::find($id);
      $record->heading1 = $req->heading1;
      $record->heading2 = $req->heading2;
      $record->description = $req->description;
      $file = $req->file('imageinput');
      if (isset($file)) {
         //echo "SND";
         $ext = rand() . "." . $file->getClientOriginalName();
         //$ext = uniqid() . $file->getClientOriginalName() . '.' . $file->getClientOriginalExtension();
         $file->move('images/carouselimages/', $ext);
         $record->image = $ext;
         $record->update();
         return redirect()->back()->with("updatesuccess", "Data has been updated");

      } else {
         //echo "i7gy";
         $record->update();
         return redirect()->back()->with("updatesuccess", "Data has been updated");
      }
      $record->update();
      return redirect()->back();
   }


   public function admincareercoursepost(Request $r)
   {
      $careercoursemodel = new webcareercoursemodel();
      $careercoursemodel->semester = $r->semesterinput;
      $careercoursemodel->coursename = $r->coursenameinput;
      $careercoursemodel->endprofile = $r->endprofileinput;
      $careercoursemodel->description = $r->descriptioninput;
      $careercoursemodel->completition = $r->completitioninput;
      $careercoursemodel->courseduration = $r->coursedurationinput;
      $careercoursemodel->classduration = $r->classdurationinput;
      $careercoursemodel->courseinfo = $r->courseinfoinput;
      $image = $r->file("courseimageinput");
      $ext = rand() . "." . $image->getClientOriginalName();
      $image->move("images/careercourses/", $ext);
      $careercoursemodel->image = $ext;
      $careercoursemodel->save();
      return redirect()->back()->with("insertsuccess", "Data has been added");
   }

   public function deletecareercourse($id)
   {
      $careermodel = webcareercoursemodel::find($id);
      $careermodel->delete();
      return redirect()->back();
   }
   public function editcareercourses(Request $req)
   {
      $uid = $req->post("uid");
      $record = DB::table("webcareercoursemodels")->where("id", $uid)->get();
      foreach ($record as $r) {
         $user = $r;
         echo json_encode($user);
      }
   }
   public function updatecareercourses(Request $req)
   {
      $id = $req->careercourseid;
      $record = webcareercoursemodel::find($id);
      $record->semester = $req->semester;
      $record->coursename = $req->coursename;
      $record->endprofile = $req->endprofile;
      $record->description = $req->description;
      $record->completition = $req->completition;
      $record->courseduration = $req->courseduration;
      $record->classduration = $req->classduration;
      $record->courseinfo = $req->courseinfo;
      $file = $req->file('imageinput');
      if (isset($file)) {
         //echo "SND";
         $ext = rand() . "." . $file->getClientOriginalName();
         //$ext = uniqid() . $file->getClientOriginalName() . '.' . $file->getClientOriginalExtension();
         $file->move('images/careercourses/', $ext);
         $record->image = $ext;
         $record->update();
         return redirect()->back()->with("updatesuccess", "Data has been updated");

      } else {
         //echo "i7gy";
         $record->update();
         return redirect()->back()->with("updatesuccess", "Data has been updated");
      }
      $record->update();
      return redirect()->back();
   }


   public function adminshortcoursepost(Request $r)
   {
      $shortcourse = new webshortcoursemodel();
      $shortcourse->coursename = $r->shortcoursenameinput;
      $shortcourse->description = $r->shortcoursedescriptioninput;
      $shortcourse->courseduration = $r->shortclassdurationinput;
      $shortcourse->classduration = $r->shortclassdurationinput;
      $shortcourse->courseinfo = $r->shortcourseinfoinput;
      $image = $r->file("shortcourseimageinput");
      $ext = rand() . "." . $image->getClientOriginalName();
      $image->move("images/shortcourses/", $ext);
      $shortcourse->image = $ext;
      $shortcourse->save();
      return redirect()->back()->with("insertsuccess", "Data has been added");
   }
   public function deleteshortcourse($id)
   {
      $shortcoursemodel = webshortcoursemodel::find($id);
      $shortcoursemodel->delete();
      return redirect()->back();
   }
   public function editshortcourses(Request $req)
   {
      $uid = $req->post("uid");
      $record = DB::table("webshortcoursemodels")->where("id", $uid)->get();
      foreach ($record as $r) {
         $user = $r;
         echo json_encode($user);
      }
   }


   public function updateshortcourses(Request $req)
   {
      $id = $req->shortcourseid;
      $record = webshortcoursemodel::find($id);
      $record->coursename = $req->coursename;
      $record->description = $req->description;
      $record->courseduration = $req->courseduration;
      $record->classduration = $req->classduration;
      $record->courseinfo = $req->courseinfo;
      $file = $req->file('imageinput');
      if (isset($file)) {
         //echo "SND";
         $ext = rand() . "." . $file->getClientOriginalName();
         //$ext = uniqid() . $file->getClientOriginalName() . '.' . $file->getClientOriginalExtension();
         $file->move('images/shortcourses/', $ext);
         $record->image = $ext;
         $record->update();
         return redirect()->back()->with("updatesuccess", "Data has been updated");

      } else {
         //echo "i7gy";
         $record->update();
         return redirect()->back()->with("updatesuccess", "Data has been updated");
      }
      $record->update();
      return redirect()->back();
   }

   public function admincounterupdate(Request $r)
   {
      $counter = webcountermodel::find(1);
      $active = $r->post("activeinput");
      $alumni = $r->post("alumniinput");
      $placement = $r->post("placementinput");
      $counter->activestudent = $active;
      $counter->alumni = $alumni;
      $counter->placements = $placement;
      $counter->update();
      return redirect()->back();
   }

   public function admineventpost(Request $r)
   {
      $eventmodel = new webeventsmodel();
      $eventmodel->title = $r->title;
      $eventmodel->timing = $r->timing;
      $eventmodel->date = $r->date;
      $eventmodel->month = $r->month;
      $eventmodel->year = $r->year;
      $eventmodel->description = $r->description;
      $image = $r->file("imageinput");
      $ext = rand() . "." . $image->getClientOriginalName();
      $image->move("images/eventimage/", $ext);
      $eventmodel->image = $ext;
      $eventmodel->save();
      return redirect()->back()->with("insertsuccess", "Data has been added");
   }
   public function deletevent($id)
   {
      $eventmodel = webeventsmodel::find($id);
      $eventmodel->delete();
      return redirect()->back();
   }
   public function editevent(Request $req)
   {
      $uid = $req->post("uid");
      $record = DB::table("webeventsmodels")->where("id", $uid)->get();
      foreach ($record as $r) {
         $user = $r;
         echo json_encode($user);
      }
   }
   public function updateevent(Request $req)
   {
      $id = $req->eventid;
      $record = webeventsmodel::find($id);
      $record->title = $req->title;
      $record->timing = $req->timing;
      $record->date = $req->date;
      $record->month = $req->month;
      $record->year = $req->year;
      $record->description = $req->description;
      $file = $req->file('imageinput');
      if (isset($file)) {
         //echo "SND";
         $ext = rand() . "." . $file->getClientOriginalName();
         //$ext = uniqid() . $file->getClientOriginalName() . '.' . $file->getClientOriginalExtension();
         $file->move('images/eventimage/', $ext);
         $record->image = $ext;
         $record->update();
         return redirect()->back()->with("updatesuccess", "Data has been updated");

      } else {
         //echo "i7gy";
         $record->update();
         return redirect()->back()->with("updatesuccess", "Data has been updated");
      }
      $record->update();
      return redirect()->back();
   }


   public function adminplacementpost(Request $r)
   {
      $placementmodel = new webplacementsmodel();
      $placementmodel->name = $r->name;
      $placementmodel->company = $r->company;
      $placementmodel->desgination = $r->desgination;
      $image = $r->file("imageinput");
      $ext = rand() . "." . $image->getClientOriginalName();
      $image->move("images/placementimages/", $ext);
      $placementmodel->image = $ext;
      $placementmodel->save();
      return redirect()->back()->with("insertsuccess", "Data has been added");
   }
   public function deleteplacements($id)
   {
      $placementmodel = webplacementsmodel::find($id);
      $placementmodel->delete();
      return redirect()->back();
   }
   public function editplacements(Request $req)
   {
      $uid = $req->post("uid");
      $record = DB::table("webplacementsmodels")->where("id", $uid)->get();
      foreach ($record as $r) {
         $user = $r;
         echo json_encode($user);
      }
   }
   public function updateplacements(Request $req)
   {
      $id = $req->placementid;
      $record = webplacementsmodel::find($id);
      $record->name = $req->name;
      $record->company = $req->company;
      $record->desgination = $req->desgination;
      $file = $req->file('imageinput');
      if (isset($file)) {
         //echo "SND";
         $ext = rand() . "." . $file->getClientOriginalName();
         //$ext = uniqid() . $file->getClientOriginalName() . '.' . $file->getClientOriginalExtension();
         $file->move('images/placementimages/', $ext);
         $record->image = $ext;
         $record->update();
         return redirect()->back()->with("updatesuccess", "Data has been updated");

      } else {
         //echo "i7gy";
         $record->update();
         return redirect()->back()->with("updatesuccess", "Data has been updated");
      }
      $record->update();
      return redirect()->back();
   }

   public function adminstudentofmonthpost(Request $r)
   {
      $studentofmonth = new webstudentofmonthmodel();
      $studentofmonth->month = $r->studentofmonth_month;
      $studentofmonth->year = $r->studentofmonth_year;
      $image = $r->file("studentofmonthimageinput");
      $ext = rand() . "." . $image->getClientOriginalName();
      $image->move("images/studentofmonth/", $ext);
      $studentofmonth->student_of_month_image = $ext;
      $studentofmonth->save();
      return redirect()->back()->with("insertsuccess", "Data has been added");
   }
   public function deletestudentofmonth($id)
   {
      $studentofmonth = webstudentofmonthmodel::find($id);
      $studentofmonth->delete();
      return redirect()->back();
   }
   public function editstudentofmonth(Request $req)
   {
      $uid = $req->post("uid");
      $record = DB::table("webstudentofmonthmodels")->where("id", $uid)->get();
      foreach ($record as $r) {
         $user = $r;
         echo json_encode($user);
      }
   }
   public function updatestudentofmonth(Request $req)
   {
      $id = $req->studentofmonthid;
      $record = webstudentofmonthmodel::find($id);
      $record->month = $req->month;
      $record->year = $req->year;
      $file = $req->file('imageinput');
      if (isset($file)) {
         //echo "SND";
         $ext = rand() . "." . $file->getClientOriginalName();
         //$ext = uniqid() . $file->getClientOriginalName() . '.' . $file->getClientOriginalExtension();
         $file->move('images/studentofmonth/', $ext);
         $record->student_of_month_image = $ext;
         $record->update();
         return redirect()->back()->with("updatesuccess", "Data has been updated");

      } else {
         //echo "i7gy";
         $record->update();
         return redirect()->back()->with("updatesuccess", "Data has been updated");
      }
      $record->update();
      return redirect()->back();
   }
   public function adminprojectofmonthpost(Request $r)
   {
      $projectofmonth = new webprojectofmonthmodel();
      $projectofmonth->month = $r->month;
      $projectofmonth->year = $r->year;
      $projectofmonth->project_title = $r->title;
      $image = $r->file("imageinput");
      $ext = rand() . "." . $image->getClientOriginalName();
      $image->move("images/projectofmonth/", $ext);
      $projectofmonth->project_of_month_image = $ext;
      $projectofmonth->save();
      return redirect()->back()->with("insertsuccess", "Data has been added");
   }
   public function deleteprojectofmonth($id)
   {
      $projectofmonth = webprojectofmonthmodel::find($id);
      $projectofmonth->delete();
      return redirect()->back();
   }
   public function editprojectofmonth(Request $req)
   {
      $uid = $req->post("uid");
      $record = DB::table("webprojectofmonthmodels")->where("id", $uid)->get();
      foreach ($record as $r) {
         $user = $r;
         echo json_encode($user);
      }
   }
   public function updateprojectofmonth(Request $req)
   {
      $id = $req->projectofmonthid;
      $record = webprojectofmonthmodel::find($id);
      $record->month = $req->month;
      $record->year = $req->year;
      $record->project_title = $req->title;
      $file = $req->file('imageinput');
      if (isset($file)) {
         //echo "SND";
         $ext = rand() . "." . $file->getClientOriginalName();
         //$ext = uniqid() . $file->getClientOriginalName() . '.' . $file->getClientOriginalExtension();
         $file->move('images/projectofmonth/', $ext);
         $record->project_of_month_image = $ext;
         $record->update();
         return redirect()->back()->with("updatesuccess", "Data has been updated");

      } else {
         //echo "i7gy";
         $record->update();
         return redirect()->back()->with("updatesuccess", "Data has been updated");
      }
      $record->update();
      return redirect()->back();
   }


   public function adminwinnerpost(Request $r)
   {
      $winner = new webwinnercirclemodel();
      $winner->winner_name = $r->name;
      $winner->winner_title = $r->title;
      $image = $r->file("imageinput");
      $ext = rand() . "." . $image->getClientOriginalName();
      $image->move("images/winnerimages/", $ext);
      $winner->winner_image = $ext;
      $winner->save();
      return redirect()->back()->with("insertsuccess", "Data has been added");
   }
   public function deletewinner($id)
   {
      $winner = webwinnercirclemodel::find($id);
      $winner->delete();
      return redirect()->back();
   }
   public function editwinner(Request $req)
   {
      $uid = $req->post("uid");
      $record = DB::table("webwinnercirclemodels")->where("id", $uid)->get();
      foreach ($record as $r) {
         $user = $r;
         echo json_encode($user);
      }
   }
   public function updatewinner(Request $req)
   {
      $id = $req->winnerid;
      $record = webwinnercirclemodel::find($id);
      $record->winner_name = $req->name;
      $record->winner_title = $req->title;
      $file = $req->file('imageinput');
      if (isset($file)) {
         //echo "SND";
         $ext = rand() . "." . $file->getClientOriginalName();
         //$ext = uniqid() . $file->getClientOriginalName() . '.' . $file->getClientOriginalExtension();
         $file->move('images/winnerimages/', $ext);
         $record->winner_image = $ext;
         $record->update();
         return redirect()->back()->with("updatesuccess", "Data has been updated");

      } else {
         //echo "i7gy";
         $record->update();
         return redirect()->back()->with("updatesuccess", "Data has been updated");
      }
      $record->update();
      return redirect()->back();
   }

}
