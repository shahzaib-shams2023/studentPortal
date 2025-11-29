<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Career Fair Registration</title>
    <link rel="shortcut icon" href="{{ asset('images/cf-logo.png') }}" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.6.3.min.js"
        integrity="sha256-pvPw+upLPUjgMXY0G+8O0xUf+/Im1MZjXxxgOcBQBXU=" crossorigin="anonymous"></script>
</head>

<body style="background-color: black;" class="text-light">
    <div class="row m-3">
        <div class="col-4"><img src="{{ asset('images/LOGO 2.png') }}" style="width: 50%"></div>
        <div class="col-4 text-center d-flex align-items-center">
            <h3 class="mx-auto">REGISTRATION FORM</h3>
        </div>
        <div class="col-4 d-flex align-items-end flex-column"><img src="{{ asset('images/cf-logo.png') }}"
                style="width: 50%"></div>
    </div>
    <div class="container">
        <form action="registerStd" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="row">
                <div class="col-3">
                    <div class="mb-3">
                        <label class="form-label">StudentID <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" placeholder="Studentxxxxxxx" id="stdId"
                            name="stdId" required>
                        <span id="errorMsg"></span>
                    </div>
                </div>
                <div class="col-3">
                    <div class="mb-3">
                        <label class="form-label">Student Name</label>
                        <input type="text" class="form-control" id="stdName" name="stdName" required>
                    </div>
                </div>
                <div class="col-3">
                    <div class="mb-3">
                        <label class="form-label">Student Email</label>
                        <input type="email" class="form-control" id="stdEmail" name="stdEmail" required>
                    </div>
                </div>
                <div class="col-3">
                    <div class="mb-3">
                        <label class="form-label">Batch</label>
                        <input type="text" class="form-control" id="stdBatch" name="stdBatch" required>
                    </div>
                </div>
                <div class="col-3">
                    <div class="mb-3">
                        <label class="form-label">Contact Number</label>
                        <input type="text" class="form-control" id="stdContact" name="stdContact" required>
                    </div>
                </div>
                <div class="col-3">
                    <div class="mb-3">
                        <label class="form-label">Semester</label>
                        <select class="form-select" id="sem" name="sem">
                            <option value="" disabled selected>---Select---</option>
                            @foreach ($sem as $item)
                                <option value="{{ $item }}">{{ $item }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-3">
                    <div class="mb-3">
                        <label class="form-label">Status</label>
                        <select class="form-select" id="status" name="status">
                            <option value="" disabled selected>---Select---</option>
                            @foreach ($status as $item)
                                <option value="{{ $item }}">{{ $item }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-3">
                    <div class="mb-3">
                        <label class="form-label">Experience</label>
                        <select class="form-select" id="exp" name="exp">
                            <option value="" disabled selected>---Select---</option>
                            <option value="Experienced">Experienced</option>
                            <option value="Mid-Level">Mid-Level</option>
                            <option value="Entry-Level">Entry-Level</option>
                            <option value="Fresher">Fresher</option>
                        </select>
                    </div>
                </div>
                <div class="col-3" id="techExpDiv">
                    <div class="mb-3">
                        <label class="form-label">Technical Experience</label>
                        <select class="form-select" id="techExp" name="techExp">
                            <option value="" disabled selected>---Select---</option>
                            <option value="Yes">Yes</option>
                            <option value="No">No</option>
                        </select>
                    </div>
                </div>
                <div class="col-3" id="expYearDiv">
                    <div class="mb-3">
                        <label class="form-label">Experience Years</label>
                        <select class="form-select" id="expYear" name="expYear">
                            <option value="" disabled selected>---Select---</option>
                            <option value="Less Than 1 Year">Less Than 1 Year</option>
                            <option value="More Than 1 Year">More Than 1 Year</option>
                            <option value="None">None</option>
                        </select>
                    </div>
                </div>
                <div class="col-3" id="currDesgDiv">
                    <div class="mb-3">
                        <label class="form-label">Select Current Designation</label>
                        <select class="form-select" id="currDesg" name="currDesg">
                            <option value="" disabled selected>---Select---</option>
                            <option value="FrontEnd">FrontEnd</option>
                            <option value="BackEnd - ASP .Net">BackEnd - ASP .Net</option>
                            <option value="BackEnd - PHP/Laravel">BackEnd - PHP/Laravel</option>
                            <option value="React / MERN Stack">React / MERN Stack</option>
                            <option value="UI/UX Designer">UI/UX Designer</option>
                            <option value="WordPress Developer">WordPress Developer</option>
                            <option value="Shopify Developer">Shopify Developer</option>
                            <option value="SEO Expert">SEO Expert</option>
                            <option value="Digital Marketer">Digital Marketer</option>
                            <option value="Graphic Designer">Graphic Designer</option>
                            <option value="Flutter Developer">Flutter Developer</option>
                            <option value="Full Stack Developer">Full Stack Developer</option>
                        </select>
                    </div>
                </div>
                <div class="col-3">
                    <div class="mb-3">
                        <label class="form-label">Portfolio Link</label>
                        <input type="url" class="form-control" id="portfolio" name="portfolio" required>
                    </div>
                </div>
                <div class="col-3">
                    <div class="mb-3">
                        <label class="form-label">LinkedIn Profile Link</label>
                        <input type="url" class="form-control" id="linked" name="linked" required>
                        <span id="linkedinerr"></span>
                    </div>
                </div>
                <div class="col-3">
                    <div class="mb-3">
                        <label class="form-label">Upload CV</label>
                        <input type="file" class="form-control" id="cv" name="cv" required>
                    </div>
                </div>
                <div class="col-6">
                    <div class="mb-3">
                        <label class="form-label">Interested Skill</label>
                        <div class="row">
                            <div class="col-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="FrontEnd"
                                        name="skills[]">
                                    <label class="form-check-label">
                                        FrontEnd
                                    </label>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="PHP/Laravel"
                                        name="skills[]">
                                    <label class="form-check-label">
                                        PHP/Laravel
                                    </label>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="ASP .Net"
                                        name="skills[]">
                                    <label class="form-check-label">
                                        ASP .Net
                                    </label>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="SEO" name="skills[]">
                                    <label class="form-check-label">
                                        SEO
                                    </label>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="Digital Marketing"
                                        name="skills[]">
                                    <label class="form-check-label">
                                        Digital Marketing
                                    </label>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="Flutter" name="skills[]">
                                    <label class="form-check-label">
                                        Flutter
                                    </label>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="MERN Stack"
                                        name="skills[]">
                                    <label class="form-check-label">
                                        MERN Stack
                                    </label>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="WordPress"
                                        name="skills[]">
                                    <label class="form-check-label">
                                        WordPress
                                    </label>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="Shopify" name="skills[]">
                                    <label class="form-check-label">
                                        Shopify
                                    </label>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="Graphic Designer"
                                        name="skills[]">
                                    <label class="form-check-label">
                                        Graphic Designer
                                    </label>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="Data Entry"
                                        name="skills[]">
                                    <label class="form-check-label">
                                        Data Entry
                                    </label>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="Content Writer"
                                        name="skills[]">
                                    <label class="form-check-label">
                                        Content Writer
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row mt-5">
                <div class="col-3">
                    <button type="submit" class="btn btn-light" id="btnRegister" disabled>Register</button>
                </div>
            </div>
        </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
    </script>
    <script>
        $(window).on('load', function() {
            $('#techExpDiv').hide();
            $('#expYearDiv').hide();
            $('#currDesgDiv').hide();
        });

        $(document).ready(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $(document).on('change', '#exp', function() {
                var exp = $(this).val();
                if (exp == "Experienced" || exp == "Mid-Level") {
                    $('#techExpDiv').show();
                    $('#expYearDiv').show();
                    $('#currDesgDiv').show();
                } else {
                    $('#techExpDiv').hide();
                    $('#expYearDiv').hide();
                    $('#currDesgDiv').hide();
                }
            })

            $(document).on('keyup', '#linked', function () { 
                var id = $(this).val();
                var linkedin = /^https?:\/\/(www\.)?linkedin\.com\/(in|pub|company)\/[a-zA-Z0-9-_%]+\/?$/
                if (linkedin.test(id)) {
                    //alert("Valid Student ID!");
                    $('#linkedinerr').empty();
                    $('#linkedinerr').append('Correct URL!!')
                } else {
                    //alert("Invalid! Format: Student1234567");
                    $('#linkedinerr').empty();
                    $('#linkedinerr').append('InCorrect URL!! Enter Correct LinkedIn URL')
                }
             })

            $(document).on('keyup', '#stdId', function() {
                var id = $(this).val();

                var regex = /^Student\d{7}$/; // "Student" + exactly 7 digits
                if (regex.test(id)) {
                    //alert("Valid Student ID!");
                    $('#errorMsg').empty();
                    $('#errorMsg').append('Correct StudentID!!')
                    $('#btnRegister').attr('disabled', false);
                } else {
                    //alert("Invalid! Format: Student1234567");
                    $('#errorMsg').empty();
                    $('#errorMsg').append('InCorrect StudentID!! Use Format: Student1234567')
                    $('#btnRegister').attr('disabled', true);
                }

                $.ajax({
                    url: 'getStudentData/' + id,
                    method: 'GET'
                }).done(function(data) {
                    var found = false; // Flag to check if the student ID exists

                    $.each(data, function(k, v) {
                        if (v['Std_id'] == id) {
                            found = true; // Mark as found
                            $('#stdName').val(v['Std_Name']);
                            $('#stdEmail').val(v['Student_email']);
                            $('#stdContact').val(v['PhoneNo']);
                            $('#stdBatch').val(v['Batch']);
                            $('#sem').val(v['Sem_Name']);

                            if (v['Status'] == 'Active') {
                                $('#status').val('Current');
                            } else if (v['Status'] == 'Course Complete') {
                                $('#status').val('Alumni');
                            }

                            // Make fields readonly
                            $('#stdName').attr('readonly', true);
                            $('#stdEmail').attr('readonly', true);
                            $('#stdBatch').attr('readonly', true);
                            return false; // Exit the loop once the match is found
                        }
                    });

                    if (!found) {
                        // If no matching student is found, clear the fields
                        $('#stdName').val('').attr('readonly', false);
                        $('#stdEmail').val('').attr('readonly', false);
                        $('#stdContact').val('');
                        $('#stdBatch').val('').attr('readonly', false);
                        $('#sem').val('');
                        $('#status').val('');
                    }
                }).fail(function() {
                    // Handle errors (e.g., network issues or server errors)
                    $('#stdName').val('').attr('readonly', false);
                    $('#stdEmail').val('').attr('readonly', false);
                    $('#stdContact').val('');
                    $('#stdBatch').val('').attr('readonly', false);
                    $('#sem').val('');
                    $('#status').val('');
                });
            });


        });
    </script>
</body>

</html>
