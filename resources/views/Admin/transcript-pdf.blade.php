<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Transcript - {{ $student->name }}</title>
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
            margin: 20px;
            font-size: 12px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #0f1b5c;
            padding-bottom: 10px;
        }
        .header h2 {
            margin: 0;
            color: #0f1b5c;
        }
        .student-info {
            margin-bottom: 20px;
            padding: 10px;
            border: 1px solid #ddd;
            background: #f9f9f9;
        }
        .info-row {
            margin-bottom: 5px;
        }
        .info-label {
            font-weight: bold;
            display: inline-block;
            width: 120px;
        }
        .semester-section {
            margin-bottom: 25px;
            page-break-inside: avoid;
        }
        .semester-title {
            background: #0f1b5c;
            color: white;
            padding: 8px;
            margin: 10px 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background: #f5f5f5;
            font-weight: bold;
        }
        .text-center {
            text-align: center;
        }
        .text-right {
            text-align: right;
        }
        .gpa-box {
            text-align: right;
            margin-top: 10px;
            padding: 8px;
            background: #e8f5e9;
            font-weight: bold;
        }
        .cgpa-box {
            margin-top: 20px;
            padding: 10px;
            background: #0f1b5c;
            color: white;
            text-align: center;
            font-weight: bold;
        }
        .footer {
            margin-top: 40px;
            padding-top: 10px;
            border-top: 1px solid #ddd;
            font-size: 10px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h2>NORTHERN UNIVERSITY, NOWSHERA</h2>
        <h2> ACADEMIC TRANSCRIPT</h2>
        <p>Official Transcript of Records</p>
    </div>

    <div class="student-info">
        <div class="info-row"><span class="info-label">Roll No:</span> {{ $student->roll_no ?? 'N/A' }}</div>
        <div class="info-row"><span class="info-label">Name:</span> {{ $student->name ?? 'N/A' }}</div>
        <div class="info-row"><span class="info-label">Father Name:</span> {{ $student->father_name ?? 'N/A' }}</div>
        <div class="info-row"><span class="info-label">Program:</span> {{ $student->program->name ?? 'N/A' }}</div>
        <div class="info-row"><span class="info-label">Current Semester:</span> {{ $student->semester ?? 'N/A' }}</div>
    </div>

    @foreach($semesterData as $semester => $data)
    <div class="semester-section">
        <div class="semester-title">{{ $semester }}</div>
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Course Code</th>
                    <th>Course Name</th>
                    <th class="text-center">Credits</th>
                    <th class="text-center">Grade</th>
                    <th class="text-center">Points</th>
                </tr>
            </thead>
            <tbody>
                @foreach($data['enrollments'] as $index => $enrollment)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>{{ $enrollment->course_code ?? 'N/A' }}</td>
                    <td>{{ $enrollment->course_name ?? 'N/A' }}</td>
                    <td class="text-center">{{ $enrollment->credits ?? 3 }}</td>
                    <td class="text-center">{{ $enrollment->grade ?? 'N/A' }}</td>
                    <td class="text-center">
                        @php
                            $points = ['A+'=>4.0,'A'=>4.0,'A-'=>3.7,'B+'=>3.3,'B'=>3.0,'B-'=>2.7,'C+'=>2.3,'C'=>2.0,'C-'=>1.7,'D+'=>1.3,'D'=>1.0,'F'=>0.0];
                            $grade = $enrollment->grade;
                            if(is_numeric($grade)) {
                                if($grade>=90) echo '4.0';
                                elseif($grade>=85) echo '3.7';
                                elseif($grade>=80) echo '3.3';
                                elseif($grade>=75) echo '3.0';
                                elseif($grade>=70) echo '2.7';
                                elseif($grade>=65) echo '2.3';
                                elseif($grade>=60) echo '2.0';
                                elseif($grade>=55) echo '1.7';
                                elseif($grade>=50) echo '1.0';
                                else echo '0.0';
                            } else {
                                echo $points[strtoupper(trim($grade))] ?? '0.0';
                            }
                        @endphp
                    </td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="3" class="text-right"><strong>Semester GPA:</strong></td>
                    <td colspan="3"><strong>{{ $data['gpa'] }}</strong> (Credits: {{ $data['total_credits'] }})</td>
                </tr>
            </tfoot>
        </table>
    </div>
    @endforeach

    <div class="cgpa-box">
        CUMULATIVE GPA (CGPA): {{ $cgpa }}
    </div>

    <div class="footer">
        <div>Registrar's Signature: ___________________</div>
        <div class="text-right">Date: {{ date('F d, Y') }}</div>
    </div>
</body>
</html>