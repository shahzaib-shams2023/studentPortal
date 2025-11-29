@extends("web.admin.adminlayout")
@section("title")
Events
@endsection
@section("dashboardcontent")

<div class="container">
<br><br><br>
<h3 style="text-align:center;">Add Event</h3>
<!-- Button trigger modal -->.
<br><br>
<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modelId">
  Create new
</button>

<!-- Modal -->
<div class="modal fade" id="modelId" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add Record</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
            </div>
            <form action="/adminevent" method="post" enctype="multipart/form-data" >

            <div class="modal-body">
            @csrf
           <div class="row">
           <div class="col-md-6">
           <label for="">Enter Title </label>
            <input type="text" name="title" placeholder="Enter Title" class="form-control">
            <br>
           </div>

           <div class="col-md-6">
           <label for="">Enter Timing </label>
            <input type="text" name="timing" placeholder="Enter Timing" class="form-control">
            <br>
           </div>

           <div class="col-md-6">
           <label for="">Enter Date </label>
            <input type="text" name="date" placeholder="Enter Date" class="form-control">
            <br>
           </div>

           <div class="col-md-6">
           <label for="">Enter Month </label>
            <input type="text" name="month" placeholder="Enter Month" class="form-control">
            <br>
           </div>
           <div class="col-md-6">
           <label for="">Enter Year </label>
            <input type="text" name="year" placeholder="Enter Year" class="form-control">
            <br>
           </div>

           <div class="col-md-6">
           <label for="">Enter Description </label>
            <input type="text" name="description" placeholder="Enter Description" class="form-control">
            <br>
           </div>

         
           <div class="col-md-6">
           <label for="">Enter Event Image </label>
            <input type="file" name="imageinput"  class="form-control">
            <br>
           </div>
           </div>
                       </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Save</button>
            </div>
            </form>

        </div>
    </div>
</div>
<table class="table table-hover table-bordered table-striped table-responsive" style="margin-top:50px;">
<tr>
<th>ID</th>
<th>IMAGE</th>
<th>DATE</th>
<th>TIMING</th>
<th>YEAR</th>
<th>DESCRIPTION</th>
<th colspan="2">Action</th>
</tr>

@foreach($event as $c)
<tr>
<td>{{$c->id}}</td>
<td>{{$c->title}}</td>
<td>{{$c->timing}}</td>
<td>{{$c->date}}</td>
<td>{{$c->month}}</td>
<td>{{$c->year}}</td>
<td>{{$c->description}}</td>

<td><img src="images/eventimage/{{$c->image}}" width="100px" height="100px"/></td>
<td><button type="button" class="btn btn-primary" width="100px" height="100px" id="modalbtnid" data-id="{{$c->id}}">Edit</button></td>
<td><a href="deleteevent/{{$c->id}}" class="btn btn-danger">Delete</a></td>
</tr>
@endforeach
</table>
</div>

<div class="modal fade" id="updatemodalid" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Update Record</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
            </div>
            <form action="{{URL::to('/updateevent')}}" method="post" enctype="multipart/form-data">
            @csrf
            <div class="modal-body">
               <input type="text" name="eventid" readonly id="eventid" class="form-control">
               <br>
               <input type="text" name="title" id="title" class="form-control">
               <br>
               <input type="text" name="timing" id="timing" class="form-control">
               <br>
               <input type="text" name="date" id="date" class="form-control">
               <br>
               <input type="text" name="month" id="month" class="form-control">
               <br>
               <input type="text" name="year" id="year" class="form-control">
               <br>
               <input type="text" name="description" id="description" class="form-control">
               <br>
               <img class="form-control"  style="height:8rem; width:10rem; border-radius:50rem;"  id="outImg" src="" alt="" height="250px">
               <br>
                      <input type="file"  name="imageinput" class="imageinput" id="imageinput" onchange="onFileSelected(event)">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Update</button>
            </div>
            </form>
        </div>
    </div>
</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

<script>
    $(document).ready(function(){
        $(document).on("click","#modalbtnid",function(){
            // alert("clicked");
            var id = $(this).attr("data-id");
            // console.log(id);
            // $("#updatemodalid").modal("show");
            $.ajax({
                url:"/editevent",
                type:"POST",
                data:"uid="+id+
                '&_token={{csrf_token()}}',
                success:function(result)
                {
                    $("#updatemodalid").modal("show");
                    var result = JSON.parse(result);
                    $("#eventid").val(result["id"]);
                    $("#title").val(result["title"]);
                    $("#timing").val(result["timing"]);
                    $("#date").val(result["date"]);
                    $("#month").val(result["month"]);
                    $("#year").val(result["year"]);
                    $("#description").val(result["description"]);
                    document.getElementById("outImg").src="images/eventimage/"+result["image"];
                    $("#imageinput").val(result["image"]);  


                }
            });


        });
    });


    function onFileSelected(event) {
    var selectedFile = event.target.files[0];
    var reader = new FileReader();

    var imgtag = document.getElementById("outImg");
    imgtag.title = selectedFile.name;

    reader.onload = function(event) {
    imgtag.src = event.target.result;
    };

    reader.readAsDataURL(selectedFile);
  }


 </script>
 
@endsection