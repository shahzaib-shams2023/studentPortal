<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Exam Result</title>
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
            padding: 8px;
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

        .completion-text {
            text-align: center;
            font-weight: bold;
            color: #007bff;
            margin-top: 10px;
            font-size: 16px;
        }

        .btn-container {
            text-align: center;
            margin-top: 20px;
        }
    </style>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function () {
            // Redirect to the student dashboard on button click
            $('#backToDashboard').on('click', function () {
                // Close the current window if opened in a new tab
                if (window.opener) {
                    window.opener.location.href = "{{ route('student_dashboard') }}";
                    window.close();
                } else {
                    // Redirect to the dashboard in the same tab
                    window.location.href = "{{ route('student_dashboard') }}";
                }
            });
        });
    </script>
</head>
<body>
    <div class="container">
        <div class="result-header">Exam Result</div>

        <!-- Student and Exam Information -->
        <table class="table">
            <tr>
                <td><strong>Examination</strong></td>
                <td>{{ $examResult->Subject ?? 'N/A' }}</td>
                <td><strong>Course</strong></td>
                <td>{{ $examResult->Sem_Name ?? 'N/A' }}</td>
            </tr>
            <tr>
                <td><strong>Student ID</strong></td>
                <td>{{ session('std_id') }}</td>
                <td><strong>Student Name</strong></td>
                <td>{{ session('sessionusername') }}</td>
            </tr>
            <tr>
                <td><strong>Exam Date</strong></td>
                <td>{{ $examResult->exam_date }}</td>
                <td><strong>Total Questions</strong></td>
                <td>{{ $totalQuestions }}</td>
            </tr>
            <tr>
                <td><strong>Correctly Answered</strong></td>
                <td>{{ $totalScore }}</td>
                <td><strong>Attempted</strong></td>
                <td>{{ $totalQuestions }}</td>
            </tr>
            <tr>
                <td><strong>Student Marks</strong></td>
                <td>{{ $totalScore }}</td>
                <td><strong>Percentage</strong></td>
                <td>{{ number_format($percentage) }}%</td>
            </tr>
            <tr style="background-color: {{ $percentage >= 40 ? '#d4edda' : '#f8d7da' }}; text-align: center;">
                <td colspan="4">
                    <strong>Status: </strong>
                    @if($percentage >= 40)
                        <span style="color: #28a745;">Pass</span>
                    @else
                        <span style="color: #dc3545;">Fail</span>
                    @endif
                </td>
            </tr>
        </table>

        <!-- Module Wise Marks -->
        <div class="section-title">Module Wise Marks:</div>
        <table class="module-table">
            <thead>
                <tr>
                    <th>Module</th>
                    <th>Student Marks</th>
                    <th>Module Marks</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>{{ $examResult->Subject }}</td>
                    <td>{{ $totalScore }}</td>
                    <td>{{ $totalQuestions }}</td>
                </tr>
                <tr>
                    <td><strong>Total</strong></td>
                    <td><strong>{{ $totalScore }}</strong></td>
                    <td><strong>{{ $totalQuestions }}</strong></td>
                </tr>
            </tbody>
        </table>

        <!-- Button to Dashboard -->
        <div class="btn-container">
            <button id="backToDashboard" class="btn btn-primary">Back To Dashboard</button>
        </div>

        <!-- Completion Text -->
        <div class="completion-text">You have completed the exam</div>
    </div>
</body>
</html>
