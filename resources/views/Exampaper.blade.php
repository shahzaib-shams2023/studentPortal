<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        crossorigin="anonymous" />
    <title>Online Exam</title>
    <style>
        body,
        html {
            height: 100%;
            margin: 0;
            overflow: hidden;
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
        }

        .containermain {
            max-width: 1200px;
            margin: 12px auto;
            background-color: #fff;
            box-shadow: 0px 2px 8px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            overflow: hidden;
        }

        .containerContent {
            max-width: 1100px;
            margin: 50px auto;
            background-color: #fff;
            box-shadow: 0px 2px 8px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            overflow: hidden;
        }
        .header {
            background-color: #b01c2e;
            color: white;
            padding: 10px;
            display: flex;
            justify-content: space-between;
        }

        .header .info {
            display: flex;
            align-items: center;
            justify-content: space-around;
            flex-grow: 1;
            text-align: center;
        }

        .header .info div {
            margin: 0 10px;
        }

        .content {
            padding: 30px;
            font-size: 18px;
        }

        .question-number {
            display: flex;
            justify-content: space-between;
            font-weight: bold;
            margin-bottom: 10px;
            color: #b01c2e;
        }

        .question-box {
            padding: 10px;
            background-color: #f9f9f9;
            border: 1px solid #ddd;
            border-radius: 4px;
            margin-bottom: 20px;
        }

        .question-box p {
            margin-bottom: 20px;
            font-size: 18px;
            font-weight: bold;
        }

        .options {
            display: flex;
            flex-direction: column;
        }

        .option {
            display: flex;
            align-items: center;
            margin-bottom: 10px;
        }

        .option input[type="radio"],
        .option input[type="checkbox"] {
            margin-right: 10px;
        }

        .navigation {
            display: flex;
            justify-content: space-between;
            margin-top: 20px;
        }

        .navigation button {
            background-color: #b01c2e;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            margin-left: 3px;
            cursor: pointer;
        }

        .navigation button:disabled {
            background-color: #ccc;
            cursor: not-allowed;
            opacity: 0;
        }

        .timer {
            font-weight: bold;
            background-color: #b01c2e;
            color: white;
            padding: 10px;
            border-radius: 5px;
            text-align: center;
        }

        .timer-danger {
            color: red;
            font-weight: bold;
        }

        .question-number .marks {
            margin-left: 10px;
            color: #b01c2e;
        }
    </style>
</head>

<body>
    <div class="containermain">
        <div class="header">
            <div class="info">
                <div>{{ session('exam_id') }}</div>
                <div>{{ session('sessionusername') }}</div>
                <div>TERM: ACCP-Semester</div>
                <div>Questions: {{ count($Question) }}</div>
                <div>Total Time: 30 Minutes</div>
                <div id="startTime"></div>
            </div>
            <div class="timer" id="timer"></div>
        </div>
    </div>

    <div class="containerContent">
        <div id="warningMessage" class="alert alert-danger d-none" role="alert">
            15 minutes remaining!
        </div>

        <form action="{{ url('/exam-result') }}" method="POST" class="p-3">
    @csrf
    @foreach ($Question as $index => $question)
        <div class="question-box question" id="question{{ $index + 1 }}">
            <div class="question-number">
                <span>Question # {{ $index + 1 }}</span>
                <span class="marks">(Mark: 1)</span> <!-- Marks displayed to the right -->
            </div>
            <p>{{ $question->Question }}</p>
            <div class="options">
                @if ($question->Type == "Radio")
                    @foreach (['Option_1', 'Option_2', 'Option_3', 'Option_4'] as $option)
                        <div class="option">
                            <input type="radio" id="option{{ $question->id }}{{ $loop->index + 1 }}"
                                name="answers[{{ $question->id }}]" value="{{ $question->$option }}">
                            <label for="option{{ $question->id }}{{ $loop->index + 1 }}">{{ $question->$option }}</label>
                        </div>
                    @endforeach
                @elseif ($question->Type == "Checkbox")
                <p style="color: #b01c2e; margin-top: -14px;">2 Options Are Correct ?</p>
                         @foreach (['Option_1', 'Option_2', 'Option_3', 'Option_4'] as $option)
                        @if ($question->$option)
                            <div class="option">
                                <input type="checkbox" id="option{{ $question->id }}{{ $loop->index + 1 }}"
                                    name="answers[{{ $question->id }}][]" value="{{ $question->$option }}">
                                <label for="option{{ $question->id }}{{ $loop->index + 1 }}">{{ $question->$option }}</label>
                            </div>
                        @endif
                    @endforeach
                @elseif ($question->Type == "True/False")
                    @foreach (['Option_1', 'Option_2'] as $option)
                        <div class="option">
                            <input type="radio" id="option{{ $question->id }}{{ $loop->index + 1 }}"
                                name="answers[{{ $question->id }}]" value="{{ $question->$option }}">
                            <label for="option{{ $question->id }}{{ $loop->index + 1 }}">{{ $question->$option }}</label>
                        </div>
                    @endforeach
                @endif
            </div>
        </div>
    @endforeach

    <div class="navigation p-3">
        <button id="previousBtn" class="btn btn-success" >Previous</button>
        <button id="nextBtn" class="btn btn-success">Next</button>
        <!-- End Exam Button -->
        <button type="submit" id="submit" class="btn btn-danger">
            End Exam
        </button>
    </div>
</form>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
$(document).ready(function () {
    let currentQuestionIndex = 0;
    const totalQuestions = {{ count($Question) }};  // Get total questions from Laravel
    const questions = @json($Question);  // Convert the question data from Laravel to JSON

    function formatTime(date) {
        const options = { hour: '2-digit', minute: '2-digit', hour12: true };
        return date.toLocaleTimeString([], options);
    }

    const startTime = new Date();
    $('#startTime').text(`Start Time: ${formatTime(startTime)}`);

    function loadQuestion(index) {
        $('.question-box').hide();
        $(`#question${index + 1}`).show();

        $('#currentpagedisplay').text(`Question # ${index + 1} (Mark:1)`);  // Display mark for each question
        $('#previousBtn').prop('disabled', index === 0);
        $('#nextBtn').prop('disabled', index === totalQuestions - 1);

        updateButtonVisibility();
    }

    function updateButtonVisibility() {
        if (currentQuestionIndex === totalQuestions - 1) {
            $('#submit').show();
        } else {
            $('#submit').hide();
        }
    }

    $('#previousBtn').click(function (e) {
    e.preventDefault(); // Prevent default form submission behavior
    if (currentQuestionIndex > 0) {
        currentQuestionIndex--;
        loadQuestion(currentQuestionIndex);
    }
});

$('#nextBtn').click(function (e) {
    e.preventDefault(); // Prevent default form submission behavior
    if (currentQuestionIndex < totalQuestions - 1) {
        currentQuestionIndex++;
        loadQuestion(currentQuestionIndex);
    }
});


    loadQuestion(currentQuestionIndex);

    let totalTime = 1800;  // 30 minutes in seconds
    let timerInterval = setInterval(function () {
        totalTime--;
        const minutes = Math.floor(totalTime / 60);
        const seconds = totalTime % 60;

        $('#timer').text(`Time Remaining: ${minutes}:${seconds < 10 ? '0' : ''}${seconds}`);

        if (totalTime <= 900) {  // If 15 minutes left
            $('#warningMessage').removeClass('d-none');
        }

        if (totalTime <= 0) {  // If time is up
            clearInterval(timerInterval);
            $('#warningMessage').removeClass('d-none').text("Time's up!").addClass('alert-danger');
            $("form").submit();
        }
    }, 1000);

    $('#submit').click(() => {
        const answers = {
            1: "Option A",
            2: "Option B",
            3: ["Option C", "Option D"]
        };

        fetch("/exam-result", {
            method: "GET",
            headers: {
                "Content-Type": "application/json",
            },
            body: JSON.stringify({ answers }),
        })
        .then((response) => {
            if (!response.ok) {
                throw new Error("Network response was not ok");
            }
            return response.json();
        })
        .then((data) => {
            localStorage.setItem("/exam-result", JSON.stringify(data));
            window.location.href = "/exam-result";
        })
        .catch((error) => {
            console.error("Error:", error);
        });
    });

    function disableBackAndReload() {
        window.addEventListener('popstate', function () {
            history.pushState(null, null, location.href); // Keeps pushing state to block back and forward
        });

        // Disable page reload (blocking reload via F5, Ctrl+R, etc.)
        window.addEventListener('beforeunload', function (e) {
            e.preventDefault();
            e.returnValue = ''; // For modern browsers (e.g., Chrome, Firefox)
        });
    }

    // Apply the disable behavior
    disableBackAndReload();
});

window.onload = function() {
    document.getElementById('question1').style.display = 'block'; // Show first question on load
};


</script>
</body>
</html>
