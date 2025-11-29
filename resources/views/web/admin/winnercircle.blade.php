@extends("web.admin.adminlayout")
@section("title")
Winner Circle
@endsection
@section("dashboardcontent")
<div class="container">
<br><br><br>
<h3 style="text-align:center;">Add Winner</h3>
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
            <form action="/adminwinner" method="post" enctype="multipart/form-data" >

            <div class="modal-body">
            @csrf
           <div class="row">
           <div class="col-md-6">
           <label for="">Enter Winner name </label>
            <input type="text" name="name" placeholder="Enter Winner name" class="form-control">
            <br>
           </div>

           <div class="col-md-6">
           <label for="">Enter Company title </label>
            <input type="text" name="title" placeholder="Enter Winner title" class="form-control">
            <br>
           </div>

           <div class="col-md-6">
           <label for="">Enter Winner Image </label>
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
<th>TITLE</th>
<th>IMAGE</th>
<th colspan="2">Action</th>
</tr>

@foreach($winner as $w)
<tr>
<td>{{$w->id}}</td>
<td>{{$w->winner_name}}</td>
<td>{{$w->winner_title}}</td>
<td><img src="images/winnerimages/{{$w->winner_image}}" width="100px" height="100px"/></td>
<td><button type="button" class="btn btn-primary" width="100px" height="100px" id="modalbtnid" data-id="{{$w->id}}">Edit</button></td>
<td><a href="deletewinner/{{$w->id}}" class="btn btn-danger">Delete</a></td>
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
            <form action="{{URL::to('/updatewinner')}}" method="post" enctype="multipart/form-data">
            @csrf
            <div class="modal-body">
               <input type="text" name="winnerid" readonly id="winnerid" class="form-control">
               <br>
               <input type="text" name="name" id="name" class="form-control">
               <br>
               <input type="text" name="title" id="title" class="form-control">
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
                url:"/editwinner",
                type:"POST",
                data:"uid="+id+
                '&_token={{csrf_token()}}',
                success:function(result)
                {
                    $("#updatemodalid").modal("show");
                    var result = JSON.parse(result);
                    $("#winnerid").val(result["id"]);
                    $("#name").val(result["winner_name"]);
                    $("#title").val(result["winner_title"]);
                    document.getElementById("outImg").src="images/winnerimages/"+result["winner_image"];
                    $("#imageinput").val(result["winner_image"]);  


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