<!-- ExamDetails.blade.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Exam Details</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.1.3/css/bootstrap.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
            padding: 20px;
        }

        .container {
            max-width: 900px;
            background-color: #fff;
            padding: 20px;
            border: 1px solid #ddd;
            margin: auto;
        }

        .result-header {
            text-align: center;
            font-weight: bold;
            color: #007bff;
            font-size: 22px;
            margin-bottom: 20px;
            border-bottom: 1px solid #ddd;
            padding-bottom: 10px;
        }

        .table, .module-table {
            width: 100%;
            margin-bottom: 20px;
            border: 1px solid #ddd;
            border-collapse: collapse;
        }

        .table th, .table td, .module-table th, .module-table td {
            padding: 8px 12px;
            border: 1px solid #ddd;
        }

        .section-title {
            font-weight: bold;
            font-size: 16px;
            color: #333;
            padding: 10px;
            background-color: #f5f5f5;
            border: 1px solid #ddd;
            margin-top: 15px;
        }

        .module-table th {
            background-color: #dc3545;
            color: #fff;
            text-align: center;
        }

        .commence-btn {
            display: flex;
            justify-content: center;
            margin-top: 20px;
        }

        .commence-btn a {
            background-color: #dc3545;
            color: #fff;
            text-decoration: none;
            font-weight: bold;
            padding: 10px 20px;
            border-radius: 5px;
            border: none;
        }

        .commence-btn a:hover {
            background-color: #218838;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="result-header">Exam Details</div>

        <!-- Table displaying exam details -->
        <table class="table">
    @if($examDetails)
        <tr>
            <td>Examination</td>
            <td>{{ $examDetails->Subject }}</td> 
            <td>Course</td>
            <td>{{ $examDetails->Sem_Name }}</td> 
        </tr>
        <tr>
            <td>Student-ID</td>
            <td id="student_id">{{ session('std_id') }}</td>
            <td>Student Name</td>
            <td>{{ session('sessionusername') }}</td>
        </tr>
        <tr>
            <td>Exam Date</td>
            <td id="exam_date">{{ $examDetails->exam_date }}</td> 
            <td>Total Marks</td>
            <td>20</td>
        </tr>
    
</table>


        <!-- Module Wise Marks -->
        <div class="section-title">Module Wise Marks :</div>
        <table class="module-table">
            <thead>
                <tr>
                    <th>Module</th>
                    <th>Module Marks</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                <td>{{ $examDetails->Subject }}</td> 
                    <td>20</td>
                </tr>
                <tr>
                    <td><strong>Total</strong></td>
                    <td><strong>20</strong></td>
                </tr>
            </tbody>
        </table>

        <!-- Commence Exam Button -->
        <div class="commence-btn">
    <a href="#" onclick="openExamPaper({{ $examDetails->exam_id }})" id="commenceExamButton">Commence Exam</a>
</div>
    </div>
    @else
        
    @endif
    <script src="https://cdnjs.cloudflare.com/ajax/libs/mousetrap/1.6.0/mousetrap.min.js"></script>
    <script>
function openExamPaper(exam_id) {
    // Open the exam paper in a new window
    const newWindow = window.open(
        `{{ URL::to('Exampaper/${exam_id}') }}`,
        "_blank",
        "width=" + screen.width + ", height=" + screen.height + 
        ", top=0, left=0, fullscreen=yes, toolbar=no, menubar=no, scrollbars=no, resizable=no, location=no, status=no"
    );

    if (newWindow) {
        newWindow.focus();

        // Block all key events in the new window
        newWindow.addEventListener("keydown", function (e) {
            blockKeyEvents(e);
        });

        // Disable right-click in the new window
        newWindow.addEventListener("contextmenu", function (e) {
            e.preventDefault();
        });

        // Prevent window from being resized or closed
        newWindow.onresize = function () {
            newWindow.moveTo(0, 0);
            newWindow.resizeTo(screen.width, screen.height);
        };

        newWindow.onbeforeunload = function () {
            return "Are you sure you want to close the exam window?";
        };

        // Monitor and keep the window in fullscreen mode
        const monitorWindow = setInterval(function () {
            if (newWindow.document.hidden) {
                newWindow.document.documentElement.requestFullscreen();
            }
        }, 500);
    } else {
        alert("Pop-up blocked! Please allow pop-ups for this site.");
    }

    // Hide the "Commence Exam" button
    const button = document.getElementById("commenceExamButton");
    button.style.display = "none";
}

// Block all keys and prevent key combinations
function blockKeyEvents(e) {
    e.preventDefault();
}

// Block keyboard events in the main window
document.addEventListener("keydown", function (e) {
    blockKeyEvents(e);
});

// Prevent navigation or refresh
// window.onbeforeunload = function () {
//     return "Are you sure you want to leave this page?";
// };

// Prevent inspecting developer tools
document.addEventListener("keydown", function (e) {
    if (e.key === "F12" || (e.ctrlKey && e.shiftKey && e.key === "I")) {
        e.preventDefault();
        return false;
    }
});

// Block all keyboard key combinations in the main window
document.addEventListener("keydown", function (e) {
    e.preventDefault();  // Prevent all key events

    // Block all key combinations (Ctrl, Alt, Shift, Meta)
    if (e.ctrlKey || e.AltKey || e.shiftKey || e.metaKey) {
        alert("Keyboard input is disabled during the exam.");
    }
});

// Fullscreen mode for the main window
function enableFullScreen() {
    if (document.documentElement.requestFullscreen) {
        document.documentElement.requestFullscreen();
    } else if (document.documentElement.webkitRequestFullscreen) {
        document.documentElement.webkitRequestFullscreen();
    } else if (document.documentElement.mozRequestFullScreen) {
        document.documentElement.mozRequestFullScreen();
    } else if (document.documentElement.msRequestFullscreen) {
        document.documentElement.msRequestFullscreen();
    }
}

</script>
</body>
</html>
