@extends("dashboardhead_foot")
@section('content')
<?php
use Illuminate\Support\Carbon;
$studcheck =DB::table("usermodels")->where('email', session('sessionuseremail'))->first();

$student_da = DB::table('curr_skills')
->join('batches','batches.id','curr_skills.Batch_ID')
->join('skills','skills.id','curr_skills.Curr_Skill')
->join('students','students.Batch_ID','batches.id')
->where('students.Std_id',$studcheck->std_id)
->first(); 

$subject = DB::table('curr_skills')
->join('batches','batches.id','curr_skills.Batch_ID')
->join('skills','skills.id','curr_skills.Curr_Skill')
->join('students','students.Batch_ID','batches.id')
->where('students.Std_id', $studcheck->std_id)
->first(); 
$sub = DB::table('examsubjectmasters')->get();
$exam2 = DB::table('internalexams')->orderBy('id','desc')->limit(1)->get();

$eexam = DB::table('curr_skills')
->join('batches','batches.id','curr_skills.Batch_ID')
->join('skills','skills.id','curr_skills.Curr_Skill')
->join('students','students.Batch_ID','batches.id')
->join('internalexams','internalexams.id','batches.id')
->where('students.Std_id', $studcheck->std_id)
->first();

?>
<style>
.description {
background-color: white;
height: 400px;
padding:20px;
border-radius: 10PX;
text-align: center;
max-width: -webkit-fill-available;
text-align: center;
}
.description:hover
{
  transition:2s all;
  box-shadow: 10px 10px 20px 10px rgb(227, 222, 222);
  
}
  @media (max-width:800px) {
 .main-container
 {
   flex:0 0 100%;
   max-width:100%;
 }
 .card-container .first-card 
 {
   flex: 0 0 100%;
   max-width: 100%; 
}
 .card-container
 {
  flex:0 0 100%;
   max-width:100%;
 }
}
  @media (max-width:1199px) {
 .main-container
 {
   flex:0 0 100%;
   max-width:100%;
 }
 .card-container
 {
  flex:0 0 100%;
   max-width:100%;
 }
 .card-container .first-card 
{
  flex:0 0 50%;
  margin: 40px;
  max-width:50%;
  border:none;
  margin:0px;
  border-radius:10px;
}
  }
  @media (max-width:600px) {
 .main-container
 {
   flex:0 0 100%;
   max-width:100%;
 }
 .card-container .first-card 
 {
   flex: 0 0 100%;
   max-width: 100%; 
}
 .card-container
 {
  flex:0 0 100%;
   max-width:100%;
 }

 

}

  .main-container
  {
    flex-wrap:wrap;

  }
.card-container
{
  display:flex;
  flex-wrap:wrap;
  flex: 0 0 100%;
  max-width: 100%:

}
.first-card 
{
  flex:0 0 25%;
  max-width:25%;
  padding:20px;
  border:none;
  border-radius:10px;
}
.description h1,b
{
  color:#1E53E0;
  font-size:17px;
}
.description h1
{
  font-size:30px;
}
.main-container
{
  padding: 0px;
}
.card-container
{
  padding:0px 0px 0px 0px;
}
.span
{
  font-size :25px;
  font-weight:bolder;
  color:#1E53E0;
}
.rows
{
  display:flex;
  flex-wrap:wrap;
  padding-left:10px;
  padding-right:10px;
}
.container-fluids2
{
  background-color:white;
}
.cardes
{
  padding:20px;
  align-items:center;
  text-align:center;
  height:100px;
  margin-left:50px;
  box-shadow:10px 10px 20px 10px rgb(228, 232, 240); 
  flex: 0 0 20%;
  max-width:20%;
}
.card
{
  display: flex;
  background-color: blue;
}
.first
{
  /* margin-left:5px; */
}
.tables
{
  background-color:white;
  padding:30px;
}
.content-wrapper
{
  min-height:200px;
}
</style>

<!-- <div class="container-fluids2">
 <div class="rows">
     <div class="cardes">
       <b>Student Id :</b>{{$subject->Std_id}}
       <br>
   <span><b>Semester :</b>{{$subject->Sem_ID}}</span> 
      
   </div>
   <div class="cardes">
       {{$subject->Sem_ID}}
   </div>
   <div class="cardes">
       {{$subject->Sem_ID}}
   </div>
 </div>
</div> -->
<div class="container-fluid p-0" style="width:100%">
<div class="main-panel">
<div class="content-wrapper">
  <div class="main-container">
<div class="row">
<div class="card-container">

<!-- attendances -->
<div class="first-card first" data-aos="zoom-in" data-aos-duration="150"
     data-aos-offset="10"
     data-aos-delay="100"
     data-aos-easing="ease-in-sine">
<a href="/attendances" style="text-decoration:none;">
@foreach($attendances as $atts)
<a href="/attendances" class="author" style="text-decoration:none; color:inherit; ">
<div class="blog-card">
<div class="description" >
<img src="dashboard/images/attendance.jpg" style="width:100px; height: 100px; "
class="mt-3" alt="">
<br><br>
<h1>Attendance Schedule</h1>
<p><b> Student Id : </b><span>{{$atts->Std_ID}}</span></p>
<p> <b> Classes Held : </b><span>{{$atts->Classes_Held}}</span></p>
<p> <b> Classes Attended : </b><span>{{$atts->Classes_Attended }}</span></p>
<p> <b> Month : </b><span>{{$atts->Month}}</span></p>
</div>
</div>
</a>
@endforeach
</a>
</div>

<!-- announcment -->
  <div class="first-card "  data-aos="zoom-in" data-aos-duration="150"
     data-aos-offset="10"
     data-aos-easing="ease-in-sine">
  <a href="/announcement" style="text-decoration:none;">
@foreach($announcement as $annu)
<a href="/announcement" style="text-decoration:none; color:inherit; ">
<div class="blog-card">
<div class="description" >
<img src="dashboard/images/seminar.jpg" style="width:100px; height: 100px;" alt="">
<br><br>
<h1>Announcement Schedule</h1>
<p> <b> Announcement Image </b><span> <img
src="dashboard/images/{{$annu->Sem_img}}" alt=""></span></p>
<p> <b> Subject : </b><span>{{$annu->Text}}</span></p>
<p> <b> Semester : </b><span>{{$annu->Title}}</span></p>
</div>
</div>
</a>
@endforeach
</a>

</div>
<!-- exam -->

<div class="first-card" class="first-card " data-aos="zoom-in" data-aos-duration="100"
     data-aos-anchor="#example-anchor"
     data-aos-offset="10"
     data-aos-delay="100"
     data-aos-duration="10">
<a href="/examfetch" style="text-decoration:none;">
<a href="/examfetch" class="author" style="text-decoration:none; color:inherit; ">
<div class="blog-card">
<div class="description">
<img class="mt-2"src="dashboard/images/exam.png" style="width:100px; height: 100px;" 
class="mt-3" alt="">
<br><br>

@foreach($examcheck as $ec)
<?php
$date = Carbon::now();
$monthName = $date->format('F');
?>
@if($ec->ExamDate > $date)
<h1>Exam Schedule</h1>
<p><b> Semester : </b><span>{{$student_da->Sem_ID}}</span></p>
<p><b> Student Id : </b><span>{{$eexam->Std_id}}</span></p>
<p><b>  Exam Date : </b><span>{{$ec->ExamDate}}</span></p>
<p><b> Batch Name : </b><span>{{$student_da->Batch}}</span></p>
@else
<span Class="span"> your Exam Isn't Scheduled</span>
@endif
@endforeach
</div>
</div>
</a>
</a>
</div>

<!-- jobs -->
<div class="first-card " data-aos="flip-down" data-aos-duration="150"
     data-aos-anchor="#example-anchor"
     data-aos-offset="10"
     data-aos-delay="100"
     data-aos-duration="10">


<a href="/jobs" style="text-decoration:none;">
@foreach($jobs as $j)
<a href="/attendances" class="author" style="text-decoration:none; color:inherit; ">
<div class="blog-card">
<div class="description">
<img src="dashboard/images/jobs.jpg" style="width:120px; height: 110px; "
class="mt-3" alt="">
<br><br>
<h1>jobs Schedule</h1>
<p><b> Gender : </b><span>{{$j->gender}}</span></p>
<p> <b> Job Post Date : </b><span>{{$j->JobPostDate}}</span></p>
<p> <b> Job Closing Date : </b><span>{{$j->JobClosingDate }}</span></p>
<p> <b> Job Location : </b><span>{{$j->JobLocation}}</span></p>
<p> <b> Job Nature : </b><span>{{$j->JobTimingJobNature}}</span></p>
</div>
</div>
</a>
@endforeach
</a>
</div>
</div>
</div>
</div>
<!-- point for student work --> 
</div>



<div class="container-fluid p-5" >
  <div class="row">
    <div class="tables">
      <h1 class="text-center">Point For Student</h1>
<div class="col-lg-12 col-md-12 grid-margin stretch-card" style="display:flex; justify-content:center; align-item:center; background-color:white;" data-aos-duration="100" data-aos="flip-down" data-aos-anchor="#example-anchor"  data-aos-offset="10" data-aos-delay="100" data-aos-duration="150">
<table  class="table table-hovered table-bordered table-striped table-primary table-responsive">
<tr>
<th>All modulars</th>
<td>If a student get above 60% in all modular and practical he will earn 150
point</td>
<td>150</td>
</tr>
<tr>
<th>Attendance</th>
<td>100% monthly attendance will get 20 point</td>
<td>20</td>
</tr>
<tr>
<th>SOM</th>
<td> </td>
<td>100</td>

</tr>
<tr>
<th>POM</th>
<td>100 points will be divided i alll group members</td>
<td>100</td>
</tr>
<tr>
<th>Late Coming</th>
<td>0 late coming student will get 30 points</td>
<td>100</td>
</tr>
<tr>
<th>Term End</th>
<td>on gain abouve 60% 30 points above 70% points above 80% 70 points above
05 100 points</td>
<td>30</td>
</tr>
<tr>
<th>Extra Activities</th>
<td>50 point will added for extra activities</td>
<td>100</td>
</tr>

</table>
</div>
</div>
</div>

</div>
</div>

@endsection
