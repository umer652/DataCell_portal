@extends('layouts.app')

@section('title', 'Student Transcript')

@section('styles')
<style>

/* BACK BUTTON */
.back-btn {
    background: #6c757d;
    color: #fff;
    padding: 10px 20px;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    transition: all 0.3s ease;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 8px;
}

.back-btn:hover {
    background: #5a6268;
    transform: translateY(-2px);
    color: #fff;
}

/* ACTION BUTTONS */
.action-buttons {
    display: flex;
    gap: 10px;
}

.print-btn, .pdf-btn, .quick-print-btn {
    padding: 10px 20px;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    font-size: 14px;
    font-weight: 500;
    transition: all 0.3s ease;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    text-decoration: none;
}

.print-btn {
    background: #28a745;
    color: #fff;
}

.print-btn:hover {
    background: #218838;
    transform: translateY(-2px);
}

.pdf-btn {
    background: #dc3545;
    color: #fff;
}

.pdf-btn:hover {
    background: #c82333;
    transform: translateY(-2px);
}

.quick-print-btn {
    background: #17a2b8;
    color: #fff;
}

.quick-print-btn:hover {
    background: #138496;
    transform: translateY(-2px);
}

/* MAIN CONTAINER */
.main-container {
    position: fixed;
    top: 80px;
    left: 270px;
    width: calc(100% - 290px);
    bottom: 20px;
    background: #ffffff;
    padding: 20px;
    border-radius: 12px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.08);
    display: flex;
    flex-direction: column;
    overflow-y: auto;
    transition: left 0.3s ease, width 0.3s ease;
}

/* SIDEBAR COLLAPSED */
body.sidebar-collapsed .main-container {
    left: 100px;
    width: calc(100% - 120px);
}

/* TOP BAR */
.top-bar {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 20px;
    flex-wrap: wrap;
    gap: 15px;
}

.top-bar h2 {
    font-size: 20px;
    font-weight: 600;
    color: #0f1b5c;
    margin: 0;
}

/* TRANSCRIPT CONTENT */
.transcript-content {
    background: #fff;
    border-radius: 12px;
    padding: 30px;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
}

/* HEADER */
.transcript-header {
    text-align: center;
    margin-bottom: 30px;
    padding-bottom: 20px;
    border-bottom: 2px solid #0f1b5c;
}

.transcript-header h2 {
    font-size: 24px;
    font-weight: 700;
    color: #0f1b5c;
    margin: 0 0 5px 0;
}

.transcript-header p {
    color: #666;
    margin: 0;
}

/* STUDENT INFO */
.student-info {
    background: #f8f9fa;
    padding: 20px;
    border-radius: 10px;
    margin-bottom: 30px;
}

.info-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 15px;
}

.info-item {
    display: flex;
    align-items: baseline;
}

.info-label {
    font-weight: 600;
    color: #0f1b5c;
    width: 120px;
    font-size: 14px;
}

.info-value {
    color: #333;
    font-size: 14px;
    flex: 1;
}

/* SEMESTER SECTION */
.semester-section {
    margin-bottom: 30px;
    page-break-inside: avoid;
}

.semester-title {
    background: #0f1b5c;
    color: #fff;
    padding: 10px 15px;
    border-radius: 8px;
    margin-bottom: 15px;
    font-size: 16px;
    font-weight: 600;
}

/* TABLE */
.table-container {
    overflow-x: auto;
    margin-bottom: 15px;
}

table {
    width: 100%;
    border-collapse: collapse;
}

thead th {
    background: #f8f9fa;
    color: #0f1b5c;
    font-weight: 600;
    padding: 12px;
    border: 1px solid #dee2e6;
    font-size: 13px;
}

tbody td {
    padding: 10px 12px;
    border: 1px solid #dee2e6;
    font-size: 13px;
}

tbody tr:hover {
    background: #f5f7fb;
}

.gpa-box {
    text-align: right;
    padding: 10px;
    background: #e8f5e9;
    border-radius: 8px;
    margin-top: 10px;
    font-weight: 600;
}

.cgpa-box {
    background: #0f1b5c;
    color: #fff;
    padding: 15px;
    border-radius: 10px;
    text-align: center;
    margin: 20px 0;
}

.cgpa-box h4 {
    margin: 0;
    font-size: 18px;
}

.cgpa-box h3 {
    margin: 5px 0 0;
    font-size: 28px;
    font-weight: 700;
}

/* FOOTER */
.transcript-footer {
    margin-top: 40px;
    padding-top: 20px;
    border-top: 1px solid #dee2e6;
    display: flex;
    justify-content: space-between;
    font-size: 12px;
}

/* EMPTY STATE */
.empty-state {
    text-align: center;
    padding: 60px;
    color: #999;
}

.empty-state i {
    font-size: 48px;
    margin-bottom: 15px;
    opacity: 0.5;
}

/* WARNING ALERT */
.alert-warning {
    background: #fff3cd;
    border-left: 4px solid #ffc107;
    padding: 15px;
    border-radius: 8px;
    margin-bottom: 20px;
}

/* RESPONSIVE */
@media (max-width: 768px) {
    .main-container {
        left: 0 !important;
        width: 100% !important;
        top: 60px;
        border-radius: 0;
    }
    
    .top-bar {
        flex-direction: column;
        align-items: flex-start;
    }
    
    .action-buttons {
        width: 100%;
        justify-content: space-between;
    }
    
    .info-grid {
        grid-template-columns: 1fr;
    }
    
    .info-label {
        width: 100px;
    }
    
    .transcript-content {
        padding: 15px;
    }
}

@media print {
    .no-print {
        display: none !important;
    }
    
    .main-container {
        position: relative;
        left: 0 !important;
        top: 0;
        width: 100%;
        padding: 0;
        margin: 0;
        box-shadow: none;
    }
    
    .transcript-content {
        padding: 0;
        box-shadow: none;
    }
    
    .student-info {
        background: none;
        border: 1px solid #ddd;
    }
    
    .semester-title {
        background: #0f1b5c;
        -webkit-print-color-adjust: exact;
        print-color-adjust: exact;
    }
    
    .cgpa-box {
        -webkit-print-color-adjust: exact;
        print-color-adjust: exact;
    }
}

/* BADGE */
.badge {
    display: inline-block;
    padding: 4px 10px;
    border-radius: 12px;
    font-size: 11px;
    font-weight: 500;
}

.badge-success {
    background: #d4edda;
    color: #155724;
}

.badge-warning {
    background: #fff3cd;
    color: #856404;
}

/* SCROLLBAR */
.main-container::-webkit-scrollbar {
    width: 6px;
}
.main-container::-webkit-scrollbar-thumb {
    background: #0f1b5c;
    border-radius: 10px;
}
.main-container::-webkit-scrollbar-track {
    background: #f1f1f1;
}
</style>
@endsection

@section('content')

<div class="main-container">

    <!-- TOP BAR -->
    <div class="top-bar no-print">
        <h2><i class="fas fa-file-alt"></i> Student Transcript</h2>
        <div class="action-buttons">
            <a href="{{ route('transcript.pdf', $student->id) }}" class="pdf-btn">
                <i class="fas fa-download"></i> Download PDF
            </a>
            <a href="{{ route('transcript.print', $student->id) }}" class="print-btn" target="_blank">
                <i class="fas fa-print"></i> Print
            </a>
            <button onclick="window.print()" class="quick-print-btn">
                <i class="fas fa-print"></i> Quick Print
            </button>
            <a href="{{ route('transcripts.index') }}" class="back-btn">
                <i class="fas fa-arrow-left"></i> Back to Students
            </a>
        </div>
    </div>

    <!-- TRANSCRIPT CONTENT -->
    <div class="transcript-content">
        
        <!-- HEADER -->
        <div class="transcript-header">
            <h2>NORTHERN UNIVERSITY, NOWSHERA</h2>
            <p>Official Transcript of Records</p>
        </div>

        <!-- STUDENT INFO -->
        <div class="student-info">
            <div class="info-grid">
                <div class="info-item">
                    <span class="info-label">Roll No:</span>
                    <span class="info-value">{{ $student->roll_no ?? 'N/A' }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Name:</span>
                    <span class="info-value">{{ $student->name ?? 'N/A' }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Father Name:</span>
                    <span class="info-value">{{ $student->father_name ?? 'N/A' }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Program:</span>
                    <span class="info-value">{{ $student->program->name ?? 'N/A' }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Current Semester:</span>
                    <span class="info-value">
                        <span class="badge badge-success">Semester {{ $student->semester ?? 'N/A' }}</span>
                    </span>
                </div>
            </div>
        </div>

        <!-- SEMESTER RESULTS -->
        @php
            $totalGradePoints = 0;
            $totalCreditsAll = 0;
        @endphp

        @forelse($semesterData as $semester => $data)
        <div class="semester-section">
            <div class="semester-title">{{ $semester }}</div>
            
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th width="5%">#</th>
                            <th width="20%">Course Code</th>
                            <th width="35%">Course Name</th>
                            <th width="10%">Credits</th>
                            <th width="10%">Grade</th>
                            <th width="10%">Grade Points</th>
                            <th width="10%">Remarks</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($data['enrollments'] as $index => $enrollment)
                        @php
                            // Calculate grade points for this course
                            $gradeLetter = $enrollment->grade ?? 'N/A';
                            $gradePoints = 0.0;
                            
                            if(is_numeric($gradeLetter)) {
                                $numericGrade = floatval($gradeLetter);
                                if($numericGrade >= 90) $gradePoints = 4.0;
                                elseif($numericGrade >= 85) $gradePoints = 3.7;
                                elseif($numericGrade >= 80) $gradePoints = 3.3;
                                elseif($numericGrade >= 75) $gradePoints = 3.0;
                                elseif($numericGrade >= 70) $gradePoints = 2.7;
                                elseif($numericGrade >= 65) $gradePoints = 2.3;
                                elseif($numericGrade >= 60) $gradePoints = 2.0;
                                elseif($numericGrade >= 55) $gradePoints = 1.7;
                                elseif($numericGrade >= 50) $gradePoints = 1.0;
                                else $gradePoints = 0.0;
                            } else {
                                $pointsMap = [
                                    'A+'=>4.0,'A'=>4.0,'A-'=>3.7,'B+'=>3.3,'B'=>3.0,'B-'=>2.7,
                                    'C+'=>2.3,'C'=>2.0,'C-'=>1.7,'D+'=>1.3,'D'=>1.0,'F'=>0.0
                                ];
                                $gradePoints = $pointsMap[strtoupper(trim($gradeLetter))] ?? 0.0;
                            }
                            
                            $credits = $enrollment->credits ?? 3;
                            
                            // Add to cumulative totals if not failed
                            if($gradeLetter != 'F' && $gradeLetter != 'F' && !(is_numeric($gradeLetter) && floatval($gradeLetter) < 50)) {
                                $totalGradePoints += ($gradePoints * $credits);
                                $totalCreditsAll += $credits;
                            }
                        @endphp
                        <tr>
                            <td class="text-center">{{ $index + 1 }}</td>
                            <td>{{ $enrollment->course_code ?? 'N/A' }}</td>
                            <td>{{ $enrollment->course_name ?? 'N/A' }}</td>
                            <td class="text-center">{{ $credits }}</td>
                            <td class="text-center">
                                @php
                                    $gradeDisplay = $gradeLetter;
                                    $gradeClass = 'badge';
                                    if($gradeDisplay == 'A+' || $gradeDisplay == 'A') $gradeClass .= ' badge-success';
                                    elseif($gradeDisplay == 'F') $gradeClass .= ' badge-warning';
                                @endphp
                                <span class="{{ $gradeClass }}">{{ $gradeDisplay }}</span>
                            </td>
                            <td class="text-center">{{ number_format($gradePoints, 2) }}</td>
                            <td class="text-center">
                                @if($gradeDisplay == 'F')
                                    <span class="badge badge-warning">Fail</span>
                                @elseif($gradeDisplay == 'A+' || $gradeDisplay == 'A')
                                    <span class="badge badge-success">Excellent</span>
                                @else
                                    <span class="badge badge-success">Pass</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center">No courses found for this semester</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <div class="gpa-box">
                <strong>Semester GPA:</strong> {{ number_format($data['gpa'], 2) }} &nbsp;|&nbsp;
                <strong>Total Credits:</strong> {{ $data['total_credits'] }}
            </div>
        </div>
        @empty
        <div class="empty-state">
            <i class="fas fa-book-open"></i>
            <p>No enrollment records found for this student.</p>
            <small>Please check if grades have been entered for this student.</small>
        </div>
        @endforelse

        <!-- CGPA - DYNAMICALLY CALCULATED -->
        @if(!empty($semesterData) && $totalCreditsAll > 0)
        @php
            $cgpa = $totalGradePoints / $totalCreditsAll;
        @endphp
        <div class="cgpa-box">
            <h4>CUMULATIVE GPA (CGPA)</h4>
            <h3>{{ number_format($cgpa, 2) }}</h3>
            <small>Total Quality Points: {{ number_format($totalGradePoints, 2) }} | Total Credits: {{ $totalCreditsAll }}</small>
        </div>
        @elseif(!empty($semesterData))
        <div class="cgpa-box">
            <h4>CUMULATIVE GPA (CGPA)</h4>
            <h3>0.00</h3>
            <small>No passing grades recorded yet</small>
        </div>
        @endif

        <!-- FOOTER -->
        <div class="transcript-footer">
            <div>
                <strong>Registrar's Signature:</strong> ___________________
            </div>
            <div>
                <strong>Date:</strong> {{ date('F d, Y') }}
            </div>
        </div>
        
        <!-- Disclaimer -->
        <div style="margin-top: 20px; font-size: 10px; color: #999; text-align: center;">
            This is a computer generated transcript. No signature required.
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
// Auto-adjust for print
window.onload = function() {
    console.log('Transcript loaded');
    
    // Add print styles dynamically if needed
    if (window.matchMedia) {
        var mediaQueryList = window.matchMedia('print');
        mediaQueryList.addListener(function(mql) {
            if (mql.matches) {
                console.log('Print mode activated');
            }
        });
    }
};
</script>
@endsection