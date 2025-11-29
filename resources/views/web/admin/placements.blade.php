@extends("web.admin.adminlayout")
@section("title")
Successfull Placmennts
@endsection
@section("dashboardcontent")
<div class="container">
<br><br><br>
<h3 style="text-align:center;">Add Placements</h3>
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
            <form action="/adminplacements" method="post" enctype="multipart/form-data" >

            <div class="modal-body">
            @csrf
           <div class="row">
           <div class="col-md-6">
           <label for="">Enter Student name </label>
            <input type="text" name="name" placeholder="Enter Student name" class="form-control">
            <br>
           </div>

           <div class="col-md-6">
           <label for="">Enter Company name </label>
            <input type="text" name="company" placeholder="Enter Company name" class="form-control">
            <br>
           </div>

           <div class="col-md-6">
           <label for="">Enter Student Desgination </label>
            <input type="text" name="desgination" placeholder="Enter Student Designation" class="form-control">
            <br>
           </div>
         
           <div class="col-md-6">
           <label for="">Enter Student Image </label>
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
@if(session("insertsuccess"))
<div class="alert alert-success" role="alert">
    <strong>{{session("insertsuccess")}}</strong>
</div>
@endif

@if(session("updatesuccess"))
<div class="alert alert-success" role="alert">
    <strong>{{session("updatesuccess")}}</strong>
</div>
@endif
<table class="table table-hover table-bordered table-striped table-responsive" style="margin-top:50px;">
<tr>
<th>ID</th>
<th>NAME</th>
<th>COMPANY</th>
<th>DESIGNATION</th>
<th>IMAGE</th>
<th colspan="2">Action</th>
</tr>

@foreach($placements as $p)
<tr>
<td>{{$p->id}}</td>
<td>{{$p->name}}</td>
<td>{{$p->company}}</td>
<td>{{$p->desgination}}</td>
<td><img src="images/placementimages/{{$p->image}}" width="100px" height="100px"/></td>
<td><button type="button" class="btn btn-primary" width="100px" height="100px" id="modalbtnid" data-id="{{$p->id}}">Edit</button></td>
<td><a href="deleteplacements/{{$p->id}}" class="btn btn-danger">Delete</a></td>
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
            <form action="{{URL::to('/updateplacements')}}" method="post" enctype="multipart/form-data">
            @csrf
            <div class="modal-body">
               <input type="text" name="placementid" readonly id="placementid" class="form-control">
               <br>
               <input type="text" name="name" id="name" class="form-control">
               <br>
               <input type="text" name="company" id="company" class="form-control">
               <br>
               <input type="text" name="desgination" id="desgination" class="form-control">
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
                url:"/editplacements",
                type:"POST",
                data:"uid="+id+
                '&_token={{csrf_token()}}',
                success:function(result)
                {
                    $("#updatemodalid").modal("show");
                    var result = JSON.parse(result);
                    $("#placementid").val(result["id"]);
                    $("#name").val(result["name"]);
                    $("#company").val(result["company"]);
                    $("#desgination").val(result["desgination"]);
                    document.getElementById("outImg").src="images/placementimages/"+result["image"];
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