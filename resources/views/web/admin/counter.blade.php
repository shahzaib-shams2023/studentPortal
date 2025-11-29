@extends("web.admin.adminlayout")
@section("title")
Counter
@endsection
@section("dashboardcontent")
@foreach($counters as $c)
<div class="container">
<br><br><br>
<h3 style="text-align:center;">Update Counter</h3>
<br><br><br>
<div class="row">
    <div class="col-md-4 offset-md-4">
    <form action="/admincounter" method="post">
    @csrf
<label for="">Enter Active No</label>
<input type="text" name="activeinput" id="" value="{{$c->activestudent}}" class="form-control">
<br>
<label for="">Enter Alumni No</label>
<input type="text" name="alumniinput" id="" value="{{$c->alumni}}" class="form-control">
<br>
<label for="">Enter Placment No</label>
<input type="text" name="placementinput" id="" value="{{$c->placements}}" class="form-control">
<br><br>
<button type="submit" class="btn btn-info">Update</button>
</form>
    </div>
</div>
</div>
@endforeach
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

 
@endsection