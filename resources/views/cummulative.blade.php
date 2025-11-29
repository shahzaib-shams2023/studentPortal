<?php
use App\Models\feedback_form;
?>

@extends('layouts.admin_dashboard')

@section('dashboard')
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add and Manage Users</title>
    <script src="https://cdn.datatables.net/1.13.1/css/jquery.dataTables.css"></script>
    <script src="/DataTables/datatables.css"></script>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" href="dashboard/dashboard/vendors/mdi/css/materialdesignicons.min.css">
    <link rel="stylesheet" href="dashboard/dashboard/vendors/base/vendor.bundle.base.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.4/dist/jquery.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Sofia">
</head>
	<style>
.dataTables_wrapper .dataTables_info {
    display: none;
}


element.style {}

.dataTables_wrapper .dataTables_paginate .paginate_button.disabled,
.dataTables_wrapper .dataTables_paginate .paginate_button.disabled:hover,
.dataTables_wrapper .dataTables_paginate .paginate_button.disabled:active {
    display: none;

}
		
.dataTables_wrapper .dataTables_paginate .paginate_button {
	    display: none;

		}
				label {
    display: none;
    margin-bottom: 0.5rem;
    border-bottom: 50px;

}
</style>
<body>
<?php
   $student_da = DB::table('curr_skills')
   ->join('batches','batches.id','curr_skills.Batch_ID')
   ->join('skills','skills.id','curr_skills.Curr_Skill')
->where('batches.Status' , '=' ,'1')
   ->get();
?>

<br><br>
<div class="container-fluid">
  <div class="row">
    <div class="col-md-4">
      <div class="form-group">
                <label for="month">Select Month</label>
                <select name="month" id="month" class="form-control">
                <option value="" selected disabled>Select Month</option>
                    <option value="January">January</option>
                    <option value="February">February</option>
                    <option value="March">March</option>
                    <option value="April">April</option>
                    <option value="May">May</option>
                    <option value="June">June</option>
                    <option value="July">July</option>
                    <option value="August">August</option>
                    <option value="September">September</option>
                    <option value="October">October</option>
                    <option value="November">November</option>
                    <option value="December">December</option>
                </select>
            </div>
      </div>

      <div class="col-md-4">
        <div class="form-group">
                <label for="batch">Select Batch</label>
                <select name="batch" id="batch" class="form-control">
                    <option value="" selected disabled>Select Batch</option>
                    @foreach($student_da as $feedback)

                    <option value="{{$feedback->Batch}}">{{$feedback->Batch}}</option> 

                    @endforeach
                </select>
            </div>
      </div>


      <div class="col-md-4">
      <div class="form-group">
                <label for="year">Select Year</label>

                <select name="year" id="year"  class="form-control">
                    <option value="" selected disabled>Select Year</option>
                    <?php
                        $currentYear = date('Y');
                        $minYear = $currentYear - 1;
                        $maxYear = $currentYear + 60;
                    
                        for ($year = $minYear; $year <= $maxYear; $year++) {
                        echo "<option value='$year'>$year</option>";
                        }
                    ?>
                </select>
                
                
            </div>

            <div class="col-md-2">
            <div class="form-group">

            <form action="{{ URL::to('import_') }}" method="POST" enctype="multipart/form-data">
              @csrf
              <div class="form-group pb-2">
              <a  class="btn btn-success" href="{{ URL::to('export_users') }}" style="background-color:#51E1C3; height:2.1rem;">Export</a>
              
            </form>
            </div>
            </div>
      </div>
  </div>
</div>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            

            
            <div class="table-responsive">
                <table id="table_id" class="table table-striped table-bordered display">
                    <thead>
                        <tr>
                        <th>faculty name</th>
                        <th>batch</th>
                        <!--<th>Month</th>
                        <th>Year</th>-->
                        <th>punctuality</th>
                        <th>course_coverage	</th>
                        <th>technical_support	</th>
                        <th>clearing_doubt</th>
                        <th>exam_assignment</th>
                        <th>book_utilization</th>
                        <th>student_appraisal</th>
                        <th>computer_uptime</th>
                        <th>gpa</th>
                        <th>total_feedback_std</th>
                        <th>percent</th>
                        <th>total_student</th>

                        </tr>
                    </thead>

                    <tbody id="hide">
                    @foreach($cummulative as $f)
                        
                            <tr id="hide">
    
		    <td>     
            {{$f->faculty_name}}         
        </td>
		  
        <td>     
            {{$f->batch}}         
        </td>


        <!--<td>     
            {{$f->month}}         
        </td>


        <td>     
            {{$f->year}}         
        </td>-->


        <td>     
            {{number_format(($f->punctuality),2)}}         
        </td>


        <td>     
            {{number_format(($f->course_coverage),2)}}         
        </td>


        <td>     
            {{number_format(($f->technical_support),2)}}         
        </td>


        <td>     
            {{number_format(($f->clearing_doubt),2)}}         
        </td>


        <td>     
            {{number_format(($f->exam_assignment),2)}}         
        </td>


        <td>     
            {{number_format(($f->book_utilization),2)}}         
        </td>



        <td>     
            {{number_format(($f->student_appraisal),2)}}         
        </td>


        <td>     
            {{number_format(($f->computer_uptime),2)}}         
        </td>



        <td>  
            {{number_format(($f->gpa),2)}}               
        </td>



      


        <td>     
            {{$f->total_feedback_std}}         
        </td>



        <td>   
			  {{number_format(($f->percent),2)}}                  
        </td>



        <td>     
            {{$f->total_student}}         
        </td>

       </tr>

    @endforeach
                          
          
                    </tbody>
                    <tbody id="table_body"></tbody>
                </table>
            </div>
            <div class="pagination justify-content-center">
                
            </div>
            <!-- <div >Total Record <span id="total_records"></span></div> -->
        </div>
    </div>
</div>


<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>
<script src="/DataTables/datatables.js"></script>
<script>
$(document).ready(function() {
    fetch_customer_data();

    function fetch_customer_data(page = 1) {
        var query_month = $('#month').val();
        var query_batch = $('#batch').val();
        var query_year = $('#year').val();

        $.ajax({
            url: "/search3?page=" + page,
            method: 'GET',
            data: {
                month: query_month,
                batch: query_batch,
                year: query_year
            },
            
            dataType: 'json',
            success: function(data) {
              
                $('#table_body').html(data.table_data);
                $('.pagination').html(data.pagination);
                $('#total_records').text(data.total_data);

            },
            error: function(xhr, status, error) {
                console.log("Error:", xhr.responseText);
            }
        });
    }

    $(document).on('click', '.pagination a', function(event) {
        event.preventDefault();
        
        var page = $(this).attr('href').split('page=')[1];

        fetch_customer_data(page);
        
    });

    $('#month, #batch, #year').on('change', function() {
        
        fetch_customer_data();
        $("#hide").hide();

    });

});


</script>

</body>
</html>
@endsection
