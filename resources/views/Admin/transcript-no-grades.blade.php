@extends('layouts.app')

@section('title', 'No Grades Available')

@section('styles')
<style>
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

body.sidebar-collapsed .main-container {
    left: 100px;
    width: calc(100% - 120px);
}

.no-grades-container {
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100%;
    min-height: 500px;
}

.message-box {
    text-align: center;
    padding: 50px;
    background: #fff;
    border-radius: 12px;
    box-shadow: 0 5px 20px rgba(0,0,0,0.1);
    max-width: 500px;
    margin: 0 auto;
}

.message-box i {
    font-size: 80px;
    color: #ffc107;
    margin-bottom: 20px;
}

.message-box h3 {
    color: #0f1b5c;
    margin-bottom: 15px;
}

.message-box p {
    color: #666;
    margin-bottom: 25px;
}

.back-btn {
    background: #0f1b5c;
    color: #fff;
    padding: 10px 25px;
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
    background: #1a2a7a;
    transform: translateY(-2px);
    color: #fff;
}

.student-info-card {
    background: #f8f9fa;
    padding: 15px 20px;
    border-radius: 10px;
    margin-bottom: 30px;
    display: inline-block;
    width: 100%;
}

.student-info-card p {
    margin: 5px 0;
}

@media (max-width: 768px) {
    .main-container {
        left: 0 !important;
        width: 100% !important;
        top: 60px;
    }
    
    .message-box {
        margin: 20px;
        padding: 30px;
    }
}
</style>
@endsection

@section('content')
<div class="main-container">
    <div class="no-grades-container">
        <div class="message-box">
            <i class="fas fa-chalkboard-teacher"></i>
            <h3>No Grades Available</h3>
            
            <div class="student-info-card">
                <p><strong>Student Name:</strong> {{ $student->name }}</p>
                <p><strong>Roll No:</strong> {{ $student->roll_no }}</p>
                <p><strong>Father Name:</strong> {{ $student->father_name }}</p>
                <p><strong>Program:</strong> {{ $student->program->name ?? 'N/A' }}</p>
            </div>
            
            <p>Grades have not been entered for this student yet.<br>
            Please check back after grades are published.</p>
            
            <a href="{{ route('transcripts.index') }}" class="back-btn">
                <i class="fas fa-arrow-left"></i> Back to Students List
            </a>
        </div>
    </div>
</div>
@endsection