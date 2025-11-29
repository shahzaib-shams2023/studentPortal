@extends('layouts.admin_dashboard')
@section('dashboard')

<?php
	use App\Models\faculty_feedback_gpa;
	use App\Models\feedback_form;
	use App\Models\users;
	use App\Models\gpa_calculate;

?>

<!doctype html>
<html lang="en">

<head>
    <title>Title</title>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"
        integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
</head>

<body>

    <br><br><br>
    <div class="container">
        <div class="row">
            <div class="col-md-6 offset-md-4">
                <form action="{{URL::to ('/month_year_f')}}" method="post">
                    @csrf
                    <!-- <input type="type" name="month" style="height:2rem; width:12rem;" > -->

                    <select name="month" style="height:2rem; width:12rem;">
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

                    <!-- <select name="years[]" multiple style="height:2rem; width:12rem;">
                        @for($i = date('Y'); $i >= date('Y') - 10; $i--)
                            <option value="{{ $i }}">{{ $i }}</option>
                        @endfor
                    </select> -->
                    <input type="number" name="year" id="year" min="{{ date('Y') - 10 }}" max="{{ date('Y') + 50 }}"
                        value="2023" style="height:2rem; width:12rem;">
                    <button type="submit" class="btn btn-primary mb-1 pt-1"
                        style="background-color:#51E1C3; height:2rem;">Search</button>
                </form>
                <h1>Faculty Feedback</h1>

                @foreach($select->unique('name') as $fp)

                @if($fp->name == 'Mirza Mouaviz')
                <td colspan="12">
                    <h3>Month: {{$fp->month}}</h3>
                </td>
                @endif

                @endforeach
                <br>

                <div class="table-responsive">

                    <div class="table table-bordered table-hovered table-striped table-responsive">
                        <table class="table table-bordered table-hovered table-striped table-responsive">
                            <tr>
                                <th>Faculty</th>
                                <th>Total Batch</th>

                                <th>Gpa</th>
                                <!-- <th>Month</th>
                                <th>year</th> -->
                            </tr>
                            @foreach($faculty_feedback_gpas as $ffg)
                            <tr>
                                <td>{{$ffg->faculty}}</td>
                                <td>{{$ffg->batch_count}}</td>
                                <td>
                                    {{number_format(($ffg->gpa),2)}}

                                    <!-- {{$ffg->gpa}} -->
                                </td>
                                <!-- <td>{{$ffg->Month}}</td>
                                <td>{{$ffg->Year}}</td> -->
                                <!-- <td> -->
                                <!-- <button id="updatebtn" data-id="{{$ffg->id}}" style="background-color:white;"><img src="draw.png" alt="" style="height:1rem;">View Faculty Btaches</button> -->
                                <!-- <button class="btn btn-primary">View Faculty Btaches</button> -->
                                <!-- </td> -->
                            </tr>
                            @endforeach
                            <tr>
                                <th>Total Batches</th>
                                <td>{{$sum}}</td>
                                <td>{{number_format(($average),2)}}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <center>
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    @foreach($select->unique('name') as $faculty_name)
                    <!-- Button trigger modal -->
                    <button type="button" class="btn btn-primary btn-lg ml-3 mt-4" data-toggle="modal"
                        data-target="#modelId{{$faculty_name->faculty_id}}">
                        {{$faculty_name->name}}
                    </button>
                    <!-- Modal -->
                    <div class="modal fade" id="modelId{{$faculty_name->faculty_id}}" tabindex="-1" role="dialog"
                        aria-labelledby="modelTitleId" aria-hidden="true">
                        <div class="modal-dialog modal-xl" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">{{$faculty_name->name}}</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <div class="container">
                                        <div class="row">
                                            @php
                                            $previousBatch = null; // Variable to store the previous batch
                                            @endphp
                                            @foreach($faculty_batch->unique('batch') as $gpa)


                                            @php
                                            $select = faculty_feedback_gpa::join('users', 'users.id',
                                            'faculty_feedback_gpas.faculty_id')
                                            ->where('faculty_feedback_gpas.batch', '=', $gpa->batch)
                                            ->where('faculty_feedback_gpas.month', '=', $gpa->month)
                                            ->where('faculty_feedback_gpas.year', '=', $gpa->year)
                                            ->first();

                                            $std_count = feedback_form::where('batch', '=', $gpa->batch)
                                            ->where('month', '=', $gpa->month)
                                            ->where('year', '=', $gpa->year)
                                            ->count();

                                            $student_count = feedback_form::where('batch', '=', $gpa->batch)
                                            ->count();

                                            $Overall = gpa_calculate::
                                            join('faculty_feedback_gpas', 'faculty_feedback_gpas.batch',
                                            'gpa_calculates.batch')
                                            ->where('faculty_feedback_gpas.batch', '=', $gpa->batch)
											->where('faculty_feedback_gpas.month', '=', $gpa->month)
                                            ->where('faculty_feedback_gpas.year', '=', $gpa->year)	
											
                                            ->first();

                                            @endphp
                                            @if($select && $select->name == $faculty_name->name &&
                                            $faculty_name->faculty_id == $faculty_name->faculty_id)

                                            @php
                                            $previousBatch = $gpa->batch; // Update the previous batch
                                            @endphp


                                            <div class="col-md-6 mb-5">
                                                <div class="faculty-details" style="border:1px solid black; ">
                                                    <h4 class="pt-2"
                                                        style="border-bottom: 1px solid black; height:3rem; background-color:#E6E6E6;">
                                                        Faculty: {{$select->name ?? 'None'}}</h4>
                                                    <h4 class="pt-2"
                                                        style="border-bottom: 1px solid black; height:3rem;">
                                                        Batch: {{$gpa->batch ?? 'None'}}</h4>
                                                    <h4 class="pt-2"
                                                        style="border-bottom: 1px solid black; height:3rem; background-color:#E6E6E6;">
                                                        Year: {{$gpa->year}} | Month: {{$gpa->month}}</h4>
                                                    <h5 class="pt-2"
                                                        style="border-bottom: 1px solid black; height:3rem; background-color:#E6E6E6;">
                                                        Overall GPA: {{number_format($gpa->gpa, 2)}}</h5>
                                                    <h5>Number of Respondents: {{$std_count}}</h5>
                                                    <div class="table-responsive pt-2">
                                                        <table
                                                            class="table table-bordered table-hovered table-striped table-responsive">
                                                            <thead>
                                                                <tr>
                                                                    <th>QUESTION</th>
                                                                    <th>GPA 4</th>
                                                                    <th>GPA 3</th>
                                                                    <th>GPA 2</th>
                                                                    <th>GPA 1</th>
                                                                    <th>SUM</th>
                                                                    <th>AVERAGE</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                @foreach($faculty_batch->reverse()->where('batch',
                                                                $gpa->batch)->where('month', $gpa->month)->where('year',
                                                                $gpa->year)->unique('question') as $gpa_unique)
                                                                <tr>
                                                                    <td>{{$gpa_unique->question}}</td>
                                                                    <td>
                                                                        @if($gpa_unique->column_iv)
                                                                        {{$gpa_unique->column_iv}}
                                                                        @else

                                                                        @endif
                                                                    </td>
                                                                    <td>
                                                                        @if($gpa_unique->column_iii)
                                                                        {{$gpa_unique->column_iii}}
                                                                        @else

                                                                        @endif
                                                                    </td>
                                                                    <td>
                                                                        @if($gpa_unique->column_ii)
                                                                        {{$gpa_unique->column_ii}}
                                                                        @else

                                                                        @endif
                                                                    </td>
                                                                    <td>
                                                                        @if($gpa_unique->column_i)
                                                                        {{$gpa_unique->column_i}}
                                                                        @else

                                                                        @endif
                                                                    </td>
                                                                    <td>{{$gpa_unique->sum}}</td>
                                                                    <td>{{$gpa_unique->average}}</td>
                                                                </tr>
                                                                @endforeach
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                            @endif
                                            @endforeach
                                        </div>


                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>


    </center>

    <br>
    <br>
    <br>
    <br>
    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"
        integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous">
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"
        integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous">
    </script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"
        integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous">
    </script>
</body>

</html>
@endsection