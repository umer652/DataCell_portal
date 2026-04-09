@extends('layouts.app')

@section('title', 'Students')

@section('styles')
<style>
    /* ===== MAIN CSS ===== */

    .add-btn {
        background: #0f1b5c;
        color: #fff;
        padding: 12px 20px;
        border: none;
        border-radius: 8px;
        cursor: pointer;
        margin-left: auto;
    }

<<<<<<< HEAD
.add-btn {
    background: #0f1b5c;
    color: #fff;
    padding: 12px 20px;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    margin-left: auto;
    transition: all 0.3s ease;
}

.add-btn:hover {
    background: #1a2a7a;
    transform: translateY(-2px);
}
=======
    .main-container {
        position: fixed;
        top: 80px;
        left: 270px;
        width: calc(100% - 290px);
        bottom: 20px;
        background: #ffffff;
        padding: 20px;
        border-radius: 12px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
        display: flex;
        flex-direction: column;
        overflow: hidden;
        transition: left 0.3s ease, width 0.3s ease;
    }
>>>>>>> 0f73c85e6f0254eb9744f874e159aa042318fde4

    body.sidebar-collapsed .main-container {
        left: 100px;
        width: calc(100% - 120px);
    }

    .top-bar {
        position: relative;
        display: flex;
        align-items: center;
        margin-bottom: 25px;
    }

<<<<<<< HEAD
.top-bar {
    position: relative;
    display: flex;
    align-items: center;
    margin-bottom: 25px;
}
.page-title {
    font-size: 20px;
    font-weight: 600;
    color: #0f1b5c;
    margin:10px;
}
=======
    .page-title {
        font-size: 20px;
        font-weight: 600;
        color: #0f1b5c;
        margin-bottom: 10px;
    }
>>>>>>> 0f73c85e6f0254eb9744f874e159aa042318fde4

    .dropdown-wrapper {
        position: absolute;
        left: 50%;
        transform: translateX(-50%);
    }

    .session-dropdown {
        padding: 10px 14px;
        border-radius: 8px;
        border: 1px solid #0f1b5c;
        background-color: #0f1b5c;
        color: #fff;
        font-weight: 500;
        cursor: pointer;
    }

    .session-dropdown option {
        background: #fff;
        color: #000;
    }

    .table-container {
        flex: 1;
        margin-top: 15px;
        border-radius: 10px;
        overflow-x: auto;
        overflow-y: auto;
    }

    table {
        width: max-content;
        min-width: 1600px;
        border-collapse: collapse;
    }

    thead th,
    tbody td {
        padding: 14px 20px;
        white-space: nowrap;
    }

    thead th {
        position: sticky;
        top: 0;
        z-index: 100;
        background: #0f1b5c;
        color: #fff;
    }

    tbody td {
        border-bottom: 1px solid #ddd;
    }

<<<<<<< HEAD
tbody tr:hover {
    background-color: #f5f7fb;
}

/* MODAL */
.modal {
    display: none;
    position: fixed;
    z-index: 2000;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    background: rgba(0,0,0,0.6);
    backdrop-filter: blur(3px);
    align-items: center;
    justify-content: center;
}
=======
    /* MODAL */
    .modal {
        display: none;
        position: fixed;
        z-index: 2000;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.6);
        backdrop-filter: blur(3px);
    }
>>>>>>> 0f73c85e6f0254eb9744f874e159aa042318fde4

    .modal-content {
        background: #fff;
        margin: 2% auto;
        width: 75%;
        max-height: 90vh;
        overflow-y: auto;
        border-radius: 16px;
        padding: 30px;
        position: relative;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.25);
    }

<<<<<<< HEAD
.close {
    position: absolute;
    right: 20px;
    top: 15px;
    font-size: 26px;
    cursor: pointer;
    transition: color 0.2s;
}

.close:hover {
    color: #d33;
}
=======
    .close {
        position: absolute;
        right: 20px;
        top: 15px;
        font-size: 26px;
        cursor: pointer;
    }
>>>>>>> 0f73c85e6f0254eb9744f874e159aa042318fde4

    /* FORM */
    .form-title {
        font-size: 22px;
        font-weight: 700;
        margin-bottom: 20px;
        color: #0f1b5c;
        border-bottom: 2px solid #eee;
        padding-bottom: 10px;
    }

    .form-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 18px;
    }

    .form-group {
        display: flex;
        flex-direction: column;
    }

    label {
        font-size: 13px;
        font-weight: 600;
        margin-bottom: 6px;
        color: #333;
    }

<<<<<<< HEAD
input, select {
    padding: 12px;
    border-radius: 8px;
    border: 1px solid #d0d0d0;
    font-size: 14px;
    transition: border-color 0.2s;
}

input:focus, select:focus {
    outline: none;
    border-color: #0f1b5c;
    box-shadow: 0 0 0 2px rgba(15,27,92,0.1);
}
=======
    input,
    select {
        padding: 12px;
        border-radius: 8px;
        border: 1px solid #d0d0d0;
        font-size: 14px;
    }
>>>>>>> 0f73c85e6f0254eb9744f874e159aa042318fde4

    .full-width {
        grid-column: 1 / -1;
        display: flex;
        justify-content: flex-end;
    }

<<<<<<< HEAD
.submit-btn {
    background: #0f1b5c;
    color: white;
    padding: 12px 30px;
    border: none;
    border-radius: 10px;
    cursor: pointer;
    transition: all 0.3s ease;
}

.submit-btn:hover {
    background: #1a2a7a;
    transform: translateY(-2px);
}
=======
    .submit-btn {
        background: #0f1b5c;
        color: white;
        padding: 12px 30px;
        border: none;
        border-radius: 10px;
        cursor: pointer;
    }
>>>>>>> 0f73c85e6f0254eb9744f874e159aa042318fde4

    .action-icons {
        display: flex;
        gap: 12px;
    }

<<<<<<< HEAD
.edit-icon { 
    color: #0f1b5c; 
    cursor: pointer; 
    font-size: 18px;
    transition: transform 0.2s;
}

.edit-icon:hover {
    transform: scale(1.1);
}

.delete-icon { 
    color: #d33; 
    cursor: pointer; 
    font-size: 18px;
    transition: transform 0.2s;
}

.delete-icon:hover {
    transform: scale(1.1);
}

/* Alert Messages */
.alert {
    padding: 12px 20px;
    border-radius: 8px;
    margin-bottom: 20px;
    position: relative;
    animation: slideDown 0.3s ease;
}

.alert-success {
    background-color: #d4edda;
    color: #155724;
    border: 1px solid #c3e6cb;
}

.alert-error {
    background-color: #f8d7da;
    color: #721c24;
    border: 1px solid #f5c6cb;
}

.alert-danger {
    background-color: #f8d7da;
    color: #721c24;
    border: 1px solid #f5c6cb;
}

.alert-warning {
    background-color: #fff3cd;
    color: #856404;
    border: 1px solid #ffeeba;
}

.close-alert {
    float: right;
    font-size: 20px;
    font-weight: bold;
    cursor: pointer;
    color: inherit;
}
=======
    .edit-icon {
        color: #0f1b5c;
        cursor: pointer;
    }
>>>>>>> 0f73c85e6f0254eb9744f874e159aa042318fde4

    .delete-icon {
        color: #d33;
        cursor: pointer;
    }

    /* Excel Upload Styles */
    .upload-section {
        background: #f8f9fa;
        border-radius: 10px;
        padding: 20px;
        margin-bottom: 20px;
        border: 1px dashed #0f1b5c;
    }

    .upload-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 15px;
        flex-wrap: wrap;
        gap: 10px;
    }

    .upload-title {
        font-size: 16px;
        font-weight: 600;
        color: #0f1b5c;
    }

<<<<<<< HEAD
.btn-excel {
    background: #28a745;
    color: white;
    padding: 8px 16px;
    border: none;
    border-radius: 6px;
    cursor: pointer;
    font-size: 14px;
    transition: opacity 0.2s;
}

.btn-template {
    background: #17a2b8;
    color: white;
    padding: 8px 16px;
    border: none;
    border-radius: 6px;
    cursor: pointer;
    font-size: 14px;
    text-decoration: none;
    display: inline-block;
    transition: opacity 0.2s;
}
=======
    .upload-buttons {
        display: flex;
        gap: 10px;
    }

    .btn-excel {
        background: #28a745;
        color: white;
        padding: 8px 16px;
        border: none;
        border-radius: 6px;
        cursor: pointer;
        font-size: 14px;
    }
>>>>>>> 0f73c85e6f0254eb9744f874e159aa042318fde4

    .btn-template {
        background: #17a2b8;
        color: white;
        padding: 8px 16px;
        border: none;
        border-radius: 6px;
        cursor: pointer;
        font-size: 14px;
        text-decoration: none;
        display: inline-block;
    }

    .btn-excel:hover,
    .btn-template:hover {
        opacity: 0.9;
    }

    .file-input-wrapper {
        position: relative;
        display: inline-block;
    }

<<<<<<< HEAD
.file-label {
    background: #0f1b5c;
    color: white;
    padding: 8px 16px;
    border-radius: 6px;
    cursor: pointer;
    font-size: 14px;
    display: inline-block;
    transition: background 0.2s;
}

.file-label:hover {
    background: #1a2a7a;
}
=======
    .file-input-wrapper input {
        position: absolute;
        opacity: 0;
        width: 0;
        height: 0;
    }
>>>>>>> 0f73c85e6f0254eb9744f874e159aa042318fde4

    .file-label {
        background: #0f1b5c;
        color: white;
        padding: 8px 16px;
        border-radius: 6px;
        cursor: pointer;
        font-size: 14px;
        display: inline-block;
    }

    .selected-file {
        margin-left: 10px;
        font-size: 14px;
        color: #666;
    }

    /* Progress Bar */
    .progress-bar {
        width: 100%;
        background-color: #f0f0f0;
        border-radius: 5px;
        margin: 10px 0;
        display: none;
    }

<<<<<<< HEAD
/* Error Modal Styles */
.error-modal {
    display: none;
    position: fixed;
    z-index: 2100;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    background: rgba(0,0,0,0.8);
    backdrop-filter: blur(5px);
    align-items: center;
    justify-content: center;
}
=======
    .progress {
        width: 0%;
        height: 30px;
        background-color: #0f1b5c;
        border-radius: 5px;
        color: white;
        text-align: center;
        line-height: 30px;
        transition: width 0.3s;
    }
>>>>>>> 0f73c85e6f0254eb9744f874e159aa042318fde4

    /* Error Modal Styles */
    .error-modal {
        display: none;
        position: fixed;
        z-index: 2100;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.8);
        backdrop-filter: blur(5px);
    }

    .error-modal-content {
        background: #fff;
        margin: 5% auto;
        width: 80%;
        max-height: 80vh;
        overflow-y: auto;
        border-radius: 16px;
        padding: 30px;
        position: relative;
    }

    .error-table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
    }

    .error-table th,
    .error-table td {
        border: 1px solid #ddd;
        padding: 10px;
        text-align: left;
        vertical-align: top;
    }

    .error-table th {
        background: #f44336;
        color: white;
        position: sticky;
        top: 0;
    }

<<<<<<< HEAD
@keyframes slideDown {
    from {
        transform: translateY(-20px);
        opacity: 0;
    }
    to {
        transform: translateY(0);
        opacity: 1;
    }
}

/* Help Text */
.help-text {
    color: #666;
    font-size: 11px;
    margin-top: 4px;
    display: block;
}

/* Loading Spinner */
.spinner {
    display: inline-block;
    width: 16px;
    height: 16px;
    border: 2px solid #fff;
    border-radius: 50%;
    border-top-color: transparent;
    animation: spin 0.6s linear infinite;
    margin-right: 8px;
}

@keyframes spin {
    to { transform: rotate(360deg); }
}
=======
    .error-table tr:nth-child(even) {
        background: #f9f9f9;
    }

    /* SweetAlert Custom Styles */
    .swal2-popup {
        font-size: 14px !important;
    }

    .swal2-title {
        font-size: 22px !important;
    }
>>>>>>> 0f73c85e6f0254eb9744f874e159aa042318fde4

    .swal2-html-container {
        font-size: 14px !important;
        text-align: left !important;
    }
</style>
@endsection

@section('content')

<<<<<<< HEAD
<meta name="csrf-token" content="{{ csrf_token() }}">

<div class="main-container" id="mainContainer">
=======
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<div class="main-container" id="main-container">
>>>>>>> 0f73c85e6f0254eb9744f874e159aa042318fde4

    <!-- Display Success/Error Messages -->
    <div id="alertMessages"></div>

     <h2 class="page-title">Student Management</h2>

    <!-- Excel Upload Section -->
    <div class="upload-section">
        <div class="upload-header">
            <div class="upload-title">
                <i class="fa-solid fa-file-excel"></i> Bulk Import Students
            </div>
            <div class="upload-buttons">
                <a href="{{ route('students.download.template') }}" class="btn-template" data-ajax="false">
                    <i class="fa-solid fa-download"></i> Download Template
                </a>
            </div>
        </div>
<<<<<<< HEAD
        
        <form id="excelUploadForm" enctype="multipart/form-data">
=======

        <form id="excelUploadForm" method="POST" action="{{ route('students.import.excel') }}" enctype="multipart/form-data">
>>>>>>> 0f73c85e6f0254eb9744f874e159aa042318fde4
            @csrf
            <div style="display: flex; align-items: center; gap: 15px; flex-wrap: wrap;">
                <div class="file-input-wrapper">
                    <label for="excel_file" class="file-label">
                        <i class="fa-solid fa-upload"></i> Choose Excel File
                    </label>
                    <input type="file" name="excel_file" id="excel_file" accept=".xlsx,.xls,.csv" required>
                </div>
                <span id="fileName" class="selected-file">No file chosen</span>
                <button type="button" class="btn-excel" id="importBtn">
                    <i class="fa-solid fa-cloud-upload-alt"></i> Import Students
                </button>
            </div>
        </form>

        <div class="progress-bar" id="progressBar">
            <div class="progress" id="progress">0%</div>
        </div>

        <small style="color: #666; margin-top: 10px; display: block;">
            <i class="fa-solid fa-info-circle"></i>
            Supported formats: .xlsx, .xls, .csv (Max 5MB).
            First download the template to see the required format.
        </small>
    </div>

    <div class="top-bar">
        <div class="dropdown-wrapper">
<<<<<<< HEAD
            <select name="session_filter" id="sessionFilter" class="session-dropdown">
                <option value="">All Sessions</option>
                @foreach($sessions as $session)
=======
            <form id="sessionFilterForm">
                <select name="session_filter" onchange="this.form.submit()" class="session-dropdown">
                    <option value="">All Sessions</option>
                    @foreach($sessions as $session)
>>>>>>> 0f73c85e6f0254eb9744f874e159aa042318fde4
                    <option value="{{ $session->id }}"
                        {{ request('session_filter') == $session->id ? 'selected' : '' }}>
                        {{ $session->name }}
                    </option>
<<<<<<< HEAD
                @endforeach
            </select>
=======
                    @endforeach
                </select>
            </form>
>>>>>>> 0f73c85e6f0254eb9744f874e159aa042318fde4
        </div>

        <button class="add-btn" id="addStudentBtn">+ Add Student</button>
    </div>

    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Department</th>
                    <th>Designation</th>
                    <th>Gender</th>
                    <th>Father Name</th>
                    <th>Roll No</th>
                    <th>App No</th>
                    <th>Program</th>
                    <th>Semester</th>
                    <th>Session</th>
                    <th>Section</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody id="studentsTableBody">
                @forelse($students as $student)
                <tr id="student-row-{{ $student->id }}">
                    <td>{{ $student->name ?? '' }}</td>
                    <td>{{ $student->user->email ?? '' }}</td>
                    <td>{{ $student->user->department ?? '' }}</td>
                    <td>{{ $student->user->designation ?? '' }}</td>
                    <td>{{ $student->gender }}</td>
                    <td>{{ $student->father_name }}</td>
                    <td>{{ $student->roll_no }}</td>
                    <td>{{ $student->app_no }}</td>
                    <td>{{ $student->program->name ?? '' }}</td>
                    <td>{{ $student->semester }}</td>
                    <td>{{ $student->session->name ?? '' }}</td>
                    <td>{{ $student->section->name ?? '' }}</td>
                    <td>
                        <div class="action-icons">
<<<<<<< HEAD
                            <i class="fa-solid fa-pen-to-square edit-icon" data-id="{{ $student->id }}" style="cursor: pointer;"></i>
                            <i class="fa-solid fa-trash delete-icon" data-id="{{ $student->id }}" style="cursor: pointer;"></i>
=======
                            <i class="fa-solid fa-pen-to-square edit-icon"
                                onclick='editStudent(@json($student))'></i>

                            <i class="fa-solid fa-trash delete-icon"
                                onclick="deleteStudent({{ $student->id }})"></i>
>>>>>>> 0f73c85e6f0254eb9744f874e159aa042318fde4
                        </div>
                    </td>
                </tr>
                @empty
                <tr id="no-students-row">
                    <td colspan="13" style="text-align: center; padding: 40px;">
                        No students found. Click "Add Student" or upload Excel file to create students.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- MODAL --}}
<div id="studentModal" class="modal">
    <div class="modal-content">
        <span class="close" id="closeModalBtn">&times;</span>
        <div class="form-title" id="modalTitle">Student Registration Form</div>

        <form id="studentForm">
            @csrf
            <input type="hidden" name="_method" id="formMethod" value="POST">
            <input type="hidden" name="student_id" id="studentId" value="">

            <div class="form-grid">
                <div class="form-group">
                    <label>Name *</label>
                    <input type="text" name="name" id="name" required>
                </div>

                <div class="form-group">
                    <label>Email *</label>
                    <input type="email" name="email" id="email" required>
                </div>

                <div class="form-group">
                    <label>Password <span id="passwordRequired">*</span></label>
                    <input type="password" name="password" id="password">
                    <small id="passwordHelp" class="help-text" style="display: none;">Leave blank to keep current password (for updates)</small>
                </div>

                <div class="form-group">
                    <label>Department *</label>
                    <input type="text" name="department" id="department" required>
                </div>

                <div class="form-group">
                    <label>Designation</label>
                    <select name="designation" id="designation">
                        <option value="student">Student</option>
                        <option value="teacher">Teacher</option>
                        <option value="admin">Admin</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Gender *</label>
                    <select name="gender" id="gender" required>
                        <option value="">Select Gender</option>
                        <option value="Male">Male</option>
                        <option value="Female">Female</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Father Name *</label>
                    <input type="text" name="father_name" id="father_name" required>
                </div>

                <div class="form-group">
                    <label>Roll No *</label>
                    <input type="text" name="roll_no" id="roll_no" required>
                </div>

                <div class="form-group">
                    <label>App No *</label>
                    <input type="text" name="app_no" id="app_no" required>
                </div>

                <div class="form-group">
                    <label>Semester *</label>
                    <input type="number" name="semester" id="semester" min="1" max="8" required>
                </div>

                <div class="form-group">
                    <label>Program *</label>
                    <select name="program_id" id="program_id" required>
                        <option value="">Select Program</option>
                        @foreach($programs as $program)
                        <option value="{{ $program->id }}">{{ $program->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label>Session *</label>
                    <select name="session_id" id="session_id" required>
                        <option value="">Select Session</option>
                        @foreach($sessions as $session)
                        <option value="{{ $session->id }}">{{ $session->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label>Section *</label>
                    <select name="section_id" id="section_id" required>
                        <option value="">Select Section</option>
                        @foreach($sections as $section)
                        <option value="{{ $section->id }}">{{ $section->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label>Enrollment Date</label>
                    <input type="date" name="enrollment_date" id="enrollment_date">
                </div>

                <div class="form-group">
                    <label>New Student</label>
                    <select name="new_student" id="new_student">
                        <option value="1">Yes</option>
                        <option value="0">No</option>
                    </select>
                </div>

                <div class="full-width">
                    <button type="button" class="submit-btn" id="saveStudentBtn">Save Student</button>
                </div>
            </div>
        </form>
    </div>
</div>

{{-- Error Modal --}}
<div id="errorModal" class="error-modal">
    <div class="error-modal-content">
        <span class="close" onclick="closeErrorModal()">&times;</span>
        <h3 style="color: #f44336; margin-bottom: 20px;">
            <i class="fa-solid fa-exclamation-triangle"></i> Import Errors
        </h3>
        <div id="errorContent"></div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    // ==================== MODAL FUNCTIONS ====================

    function openModal() {
        const modal = document.getElementById('studentModal');
        const form = document.getElementById('studentForm');
        const formMethod = document.getElementById('formMethod');
        const passwordField = document.getElementById('password');
        const passwordRequired = document.getElementById('passwordRequired');
        const passwordHelp = document.getElementById('passwordHelp');
        const studentId = document.getElementById('student_id');

        form.reset();
        formMethod.value = "POST";
        studentId.value = '';

        passwordField.required = true;
        passwordField.placeholder = "Enter password";
        passwordRequired.style.display = 'inline';
        passwordHelp.style.display = 'none';

        // Clear any previous values
        document.getElementById('name').value = '';
        document.getElementById('email').value = '';
        document.getElementById('department').value = '';
        document.getElementById('gender').value = '';
        document.getElementById('father_name').value = '';
        document.getElementById('roll_no').value = '';
        document.getElementById('app_no').value = '';
        document.getElementById('semester').value = '';
        document.getElementById('program_id').value = '';
        document.getElementById('session_id').value = '';
        document.getElementById('section_id').value = '';
        document.getElementById('enrollment_date').value = '';
        document.getElementById('new_student').value = '1';
        document.getElementById('designation').value = 'student';

        modal.style.display = 'block';
    }

    function closeModal() {
        document.getElementById('studentModal').style.display = 'none';
    }

    window.loadPage = function(url) {
        fetch(url)
            .then(res => res.text())
            .then(data => {
                let parser = new DOMParser();
                let doc = parser.parseFromString(data, 'text/html');

                let newContent = doc.querySelector('#content').innerHTML;
                document.querySelector('#content').innerHTML = newContent;

                window.history.pushState({}, '', url);
            });
    }

    function editStudent(student) {
        const modal = document.getElementById('studentModal');
        const formMethod = document.getElementById('formMethod');
        const passwordField = document.getElementById('password');
        const passwordRequired = document.getElementById('passwordRequired');
        const passwordHelp = document.getElementById('passwordHelp');
        const studentId = document.getElementById('student_id');

        formMethod.value = "PUT";
        studentId.value = student.id;

        passwordField.required = false;
        passwordField.placeholder = "Leave blank to keep current password";
        passwordRequired.style.display = 'none';
        passwordHelp.style.display = 'block';
        passwordField.value = '';

        document.getElementById('name').value = student.user?.name || '';
        document.getElementById('email').value = student.user?.email || '';
        document.getElementById('department').value = student.user?.department || '';
        document.getElementById('designation').value = student.user?.designation || 'student';
        document.getElementById('gender').value = student.gender || '';
        document.getElementById('father_name').value = student.father_name || '';
        document.getElementById('roll_no').value = student.roll_no || '';
        document.getElementById('app_no').value = student.app_no || '';
        document.getElementById('semester').value = student.semester || '';
        document.getElementById('program_id').value = student.program_id || '';
        document.getElementById('session_id').value = student.session_id || '';
        document.getElementById('section_id').value = student.section_id || '';
        document.getElementById('enrollment_date').value = student.enrollment_date || '';
        document.getElementById('new_student').value = student.new_student || '1';

        modal.style.display = 'block';
    }

    function saveStudent() {
        const formMethod = document.getElementById('formMethod').value;
        const studentId = document.getElementById('student_id').value;
        const password = document.getElementById('password').value;
        const name = document.getElementById('name').value;
        const email = document.getElementById('email').value;
        const submitBtn = document.getElementById('submitBtn');

        // Validation
        if (!name || !email) {
            Swal.fire('Validation Error', 'Name and Email are required fields.', 'error');
            return;
        }

        if (formMethod === 'POST' && (!password || password.length < 6)) {
            Swal.fire('Validation Error', 'Password is required and must be at least 6 characters for new students.', 'error');
            return;
        }

        if (formMethod === 'PUT' && password && password.length < 6) {
            Swal.fire('Validation Error', 'Password must be at least 6 characters if provided.', 'error');
            return;
        }

        // Prepare form data
        let formData = new FormData();
        formData.append('_token', '{{ csrf_token() }}');
        formData.append('name', name);
        formData.append('email', email);
        formData.append('department', document.getElementById('department').value);
        formData.append('designation', document.getElementById('designation').value);
        formData.append('gender', document.getElementById('gender').value);
        formData.append('father_name', document.getElementById('father_name').value);
        formData.append('roll_no', document.getElementById('roll_no').value);
        formData.append('app_no', document.getElementById('app_no').value);
        formData.append('semester', document.getElementById('semester').value);
        formData.append('program_id', document.getElementById('program_id').value);
        formData.append('session_id', document.getElementById('session_id').value);
        formData.append('section_id', document.getElementById('section_id').value);
        formData.append('enrollment_date', document.getElementById('enrollment_date').value);
        formData.append('new_student', document.getElementById('new_student').value);

        if (password) {
            formData.append('password', password);
        }

        let url = '';
        if (formMethod === 'POST') {
            url = '{{ route("students.store") }}';
        } else {
            url = '/students/' + studentId;
            formData.append('_method', 'PUT');
        }

        // Disable button and show loading
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Saving...';

        $.ajax({
            url: url,
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                if (response.success) {
                    Swal.fire({
                        title: 'Success!',
                        text: response.message || 'Student saved successfully.',
                        icon: 'success',
                        timer: 2000,
                        showConfirmButton: false
                    }).then(() => {
                        loadPage(window.location.pathname);
                    });
                } else {
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = 'Save Student';

                    let errorHtml = '<ul style="text-align: left;">';
                    if (response.errors) {
                        for (let field in response.errors) {
                            errorHtml += `<li><strong>${field}:</strong> ${response.errors[field].join(', ')}</li>`;
                        }
                    } else {
                        errorHtml += `<li>${response.message || 'An error occurred'}</li>`;
                    }
                    errorHtml += '</ul>';

                    Swal.fire({
                        title: 'Error!',
                        html: errorHtml,
                        icon: 'error',
                        confirmButtonColor: '#0f1b5c'
                    });
                }
            },
            error: function(xhr) {
                submitBtn.disabled = false;
                submitBtn.innerHTML = 'Save Student';

                console.log('Error response:', xhr);

                let errorMsg = 'An error occurred. Please try again.';

                if (xhr.status === 422) {
                    // Validation errors
                    let errors = xhr.responseJSON?.errors;
                    if (errors) {
                        let errorHtml = '<ul style="text-align: left;">';
                        for (let field in errors) {
                            errorHtml += `<li><strong>${field}:</strong> ${errors[field].join(', ')}</li>`;
                        }
                        errorHtml += '</ul>';

                        Swal.fire({
                            title: 'Validation Error!',
                            html: errorHtml,
                            icon: 'error',
                            confirmButtonColor: '#0f1b5c'
                        });
                        return;
                    }
                } else if (xhr.status === 409) {
                    errorMsg = xhr.responseJSON?.message || 'Email already exists!';
                } else if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMsg = xhr.responseJSON.message;
                }

                Swal.fire('Error!', errorMsg, 'error');
            }
        });
    }

    function deleteStudent(id) {
        Swal.fire({
            title: 'Are you sure?',
            text: "This action cannot be undone!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "/students/" + id,
                    type: "DELETE",
                    data: {
                        _token: "{{ csrf_token() }}"
                    },
                    success: function(response) {
                        if (response.success) {
                            Swal.fire('Deleted!', response.message || 'Student has been deleted.', 'success').then(() => {
                                loadPage(window.location.pathname);
                            });
                        } else {
                            Swal.fire('Error!', response.message || 'Could not delete student.', 'error');
                        }
                    },
                    error: function(xhr) {
                        let errorMsg = 'Network error. Please try again.';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMsg = xhr.responseJSON.message;
                        }
                        Swal.fire('Error!', errorMsg, 'error');
                    }
                });
            }
        });
    }

    // ==================== EXCEL UPLOAD FUNCTIONS ====================

    function displayFileName() {
        const input = document.getElementById('excel_file');
        const fileName = document.getElementById('fileName');

        if (input.files && input.files[0]) {
            fileName.textContent = input.files[0].name;

            if (input.files[0].size > 5 * 1024 * 1024) {
                Swal.fire('Error', 'File size exceeds 5MB. Please choose a smaller file.', 'error');
                input.value = '';
                fileName.textContent = 'No file chosen';
                return;
            }

            const allowedExtensions = ['.xlsx', '.xls', '.csv'];
            const fileExt = input.files[0].name.substring(input.files[0].name.lastIndexOf('.')).toLowerCase();
            if (!allowedExtensions.includes(fileExt)) {
                Swal.fire('Error', 'Invalid file type. Please upload .xlsx, .xls, or .csv files only.', 'error');
                input.value = '';
                fileName.textContent = 'No file chosen';
            }
        }
    }

    $('#excelUploadForm').on('submit', function(e) {
        e.preventDefault();

        const fileInput = document.getElementById('excel_file');
        if (!fileInput.files || !fileInput.files[0]) {
            Swal.fire('Error', 'Please select a file first.', 'error');
            return;
        }

        const formData = new FormData(this);
        const progressBar = document.getElementById('progressBar');
        const progress = document.getElementById('progress');
        const submitBtn = $(this).find('button[type="submit"]');

        progressBar.style.display = 'block';
        progress.style.width = '0%';
        progress.textContent = '0%';
        submitBtn.prop('disabled', true).html('<i class="fa-solid fa-spinner fa-spin"></i> Uploading...');

        let width = 0;
        const interval = setInterval(function() {
            if (width >= 90) {
                clearInterval(interval);
            } else {
                width += 10;
                progress.style.width = width + '%';
                progress.textContent = width + '%';
            }
        }, 200);

        $.ajax({
            url: $(this).attr('action'),
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(data) {
                clearInterval(interval);
                progress.style.width = '100%';
                progress.textContent = '100%';
                submitBtn.prop('disabled', false).html('<i class="fa-solid fa-cloud-upload-alt"></i> Import Students');

                setTimeout(() => {
                    if (data.success) {
                        Swal.fire('Success!', data.message || 'Students imported successfully.', 'success').then(() => {
                            loadPage(window.location.pathname);
                        });
                    } else {
                        progressBar.style.display = 'none';
                        if (data.errors && data.errors.length > 0) {
                            displayErrors(data.errors);
                        } else {
                            Swal.fire('Error!', data.message || 'Error uploading file', 'error');
                        }
                    }
                }, 500);
            },
            error: function(xhr) {
                clearInterval(interval);
                progressBar.style.display = 'none';
                submitBtn.prop('disabled', false).html('<i class="fa-solid fa-cloud-upload-alt"></i> Import Students');

                let errorMsg = 'Network error. Please try again.';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMsg = xhr.responseJSON.message;
                }
                Swal.fire('Error!', errorMsg, 'error');
            }
        });
    });

    function displayErrors(errors) {
        const errorModal = document.getElementById('errorModal');
        const errorContent = document.getElementById('errorContent');

        let html = '<table class="error-table">';
        html += '<thead><tr>';
        html += '<th>Row #</th>';
        html += '<th>Field</th>';
        html += '<th>Error</th>';
        html += '<th>Values</th>';
        html += '</tr></thead><tbody>';

        errors.forEach(error => {
            html += '<tr>';
            html += `<td>${error.row || 'N/A'}</td>`;
            html += `<td>${error.attribute || 'General'}</td>`;
            html += `<td>${error.errors ? error.errors.join(', ') : error.message}</td>`;
            html += `<td><pre style="margin:0; font-size:12px;">${error.values ? JSON.stringify(error.values, null, 2) : 'N/A'}</pre></td>`;
            html += '</tr>';
        });

        html += '</tbody></table>';
        errorContent.innerHTML = html;
        errorModal.style.display = 'block';
    }

    function closeErrorModal() {
        document.getElementById('errorModal').style.display = 'none';
    }

    // Close modal when clicking outside
    window.onclick = function(event) {
        const modal = document.getElementById('studentModal');
        const errorModal = document.getElementById('errorModal');

        if (event.target === modal) {
            closeModal();
        }

        if (event.target === errorModal) {
            closeErrorModal();
        }
    }

    // Display session messages in SweetAlert
    @if(session('success'))
    Swal.fire({
        title: 'Success!',
        text: '{{ session('
        success ') }}',
        icon: 'success',
        timer: 3000,
        showConfirmButton: true
    });
    @endif

    @if(session('error'))
    Swal.fire({
        title: 'Error!',
        text: '{{ session('
        error ') }}',
        icon: 'error',
        confirmButtonColor: '#d33'
    });
    @endif

    document.getElementById('sessionFilterForm').addEventListener('submit', function(e) {
        e.preventDefault();

        let formData = new FormData(this);
        let params = new URLSearchParams(formData).toString();

        let url = window.location.pathname + '?' + params;

        loadPage(url, routeName);
    });

    function loadPage(url, routeName) {
        $.ajax({
            url: url,
            success: function(res) {
                document.getElementById('main-container').innerHTML = res;
                if (routeName) setActiveSidebar(routeName); // sidebar highlight update
                window.history.pushState({}, '', url);
            }
        });
    }

    document.querySelectorAll('.nav-link').forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault(); // prevent full page reload

            const routeName = this.dataset.route; // from data-route
            const url = this.getAttribute('href'); // actual url

            loadPage(url, routeName); // pass routeName to loadPage
        });
    });
</script>

@endsection