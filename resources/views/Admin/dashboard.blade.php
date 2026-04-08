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
    overflow: hidden;
    transition: left 0.3s ease, width 0.3s ease;
}

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
.page-title {
    font-size: 20px;
    font-weight: 600;
    color: #0f1b5c;
    margin-bottom: 10px;
}

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
}

.modal-content {
    background: #fff;
    margin: 2% auto;
    width: 75%;
    max-height: 90vh;
    overflow-y: auto;
    border-radius: 16px;
    padding: 30px;
    position: relative;
    box-shadow: 0 10px 30px rgba(0,0,0,0.25);
}

.close {
    position: absolute;
    right: 20px;
    top: 15px;
    font-size: 26px;
    cursor: pointer;
}

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

input, select {
    padding: 12px;
    border-radius: 8px;
    border: 1px solid #d0d0d0;
    font-size: 14px;
}

.full-width {
    grid-column: 1 / -1;
    display: flex;
    justify-content: flex-end;
}

.submit-btn {
    background: #0f1b5c;
    color: white;
    padding: 12px 30px;
    border: none;
    border-radius: 10px;
    cursor: pointer;
}

.action-icons {
    display: flex;
    gap: 12px;
}

.edit-icon { color: #0f1b5c; cursor: pointer; }
.delete-icon { color: #d33; cursor: pointer; }

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

.btn-excel:hover, .btn-template:hover {
    opacity: 0.9;
}

.file-input-wrapper {
    position: relative;
    display: inline-block;
}

.file-input-wrapper input {
    position: absolute;
    opacity: 0;
    width: 0;
    height: 0;
}

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

.error-table tr:nth-child(even) {
    background: #f9f9f9;
}

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

</style>
@endsection

@section('content')

<div class="main-container">

    <!-- Display Success/Error Messages -->
    @if(session('success'))
        <div class="alert alert-success">
            <span class="close-alert" onclick="this.parentElement.style.display='none';">&times;</span>
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-error">
            <span class="close-alert" onclick="this.parentElement.style.display='none';">&times;</span>
            {{ session('error') }}
        </div>
    @endif

    @if(session('warning'))
        <div class="alert alert-warning">
            <span class="close-alert" onclick="this.parentElement.style.display='none';">&times;</span>
            {{ session('warning') }}
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger">
            <span class="close-alert" onclick="this.parentElement.style.display='none';">&times;</span>
            <strong>Validation Errors:</strong>
            <ul style="margin-top: 10px; margin-bottom: 0;">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

     <h2 class="page-title">Student Management</h2>

    <!-- Excel Upload Section -->
    <div class="upload-section">
        <div class="upload-header">
            <div class="upload-title">
                <i class="fa-solid fa-file-excel"></i> Bulk Import Students
            </div>
            <div class="upload-buttons">
                <a href="{{ route('students.download.template') }}" class="btn-template">
                    <i class="fa-solid fa-download"></i> Download Template
                </a>
            </div>
        </div>
        
        <form id="excelUploadForm" method="POST" action="{{ route('students.import.excel') }}" enctype="multipart/form-data">
            @csrf
            <div style="display: flex; align-items: center; gap: 15px; flex-wrap: wrap;">
                <div class="file-input-wrapper">
                    <label for="excel_file" class="file-label">
                        <i class="fa-solid fa-upload"></i> Choose Excel File
                    </label>
                    <input type="file" name="excel_file" id="excel_file" accept=".xlsx,.xls,.csv" required onchange="displayFileName()">
                </div>
                <span id="fileName" class="selected-file">No file chosen</span>
                <button type="submit" class="btn-excel" onclick="showProgress()">
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
            <form method="GET" action="{{ route('dashboard') }}">
                <select name="session_filter" onchange="this.form.submit()" class="session-dropdown">
                    <option value="">All Sessions</option>
                    @foreach($sessions as $session)
                        <option value="{{ $session->id }}"
                            {{ request('session_filter') == $session->id ? 'selected' : '' }}>
                            {{ $session->name }}
                        </option>
                    @endforeach
                </select>
            </form>
        </div>

        <button class="add-btn" onclick="openModal()">+ Add Student</button>
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

            <tbody>
                @forelse($students as $student)
                <tr>
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
                            <i class="fa-solid fa-pen-to-square edit-icon"
                               onclick='editStudent(@json($student))'></i>

                            <i class="fa-solid fa-trash delete-icon"
                               onclick="deleteStudent({{ $student->id }})"></i>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
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
        <span class="close" onclick="closeModal()">&times;</span>
        <div class="form-title">Student Registration Form</div>

        <form method="POST" id="studentForm" onsubmit="return validateForm()">
            @csrf
            <input type="hidden" name="_method" id="formMethod" value="POST">

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
                    <small id="passwordHelp" style="color: #666; font-size: 12px; display: none;">Leave blank to keep current password (for updates)</small>
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
                    <button type="submit" class="submit-btn" id="submitBtn">Save Student</button>
                </div>
            </div>
        </form>
    </div>
</div>

{{-- Error Modal --}}
<div id="errorModal" class="error-modal">
    <div class="error-modal-content">
        <span class="close" onclick="closeErrorModal()" style="float: right; font-size: 28px; cursor: pointer;">&times;</span>
        <h3 style="color: #f44336; margin-bottom: 20px;">
            <i class="fa-solid fa-exclamation-triangle"></i> Import Errors
        </h3>
        <div id="errorContent"></div>
    </div>
</div>

<script>

// ==================== MODAL FUNCTIONS ====================

function openModal() {
    const modal = document.getElementById('studentModal');
    const form = document.getElementById('studentForm');
    const formMethod = document.getElementById('formMethod');
    const passwordField = document.getElementById('password');
    const passwordRequired = document.getElementById('passwordRequired');
    const passwordHelp = document.getElementById('passwordHelp');
    
    form.reset();
    form.action = "{{ route('students.store') }}";
    formMethod.value = "POST";
    
    passwordField.required = true;
    passwordRequired.style.display = 'inline';
    passwordHelp.style.display = 'none';
    
    document.getElementById('name').value = '';
    document.getElementById('email').value = '';
    document.getElementById('department').value = '';
    
    modal.style.display = 'block';
}

function closeModal() {
    document.getElementById('studentModal').style.display = 'none';
}

function editStudent(student) {
    const modal = document.getElementById('studentModal');
    const form = document.getElementById('studentForm');
    const formMethod = document.getElementById('formMethod');
    const passwordField = document.getElementById('password');
    const passwordRequired = document.getElementById('passwordRequired');
    const passwordHelp = document.getElementById('passwordHelp');
    
    form.action = "/students/" + student.id;
    formMethod.value = "PUT";
    
    passwordField.required = false;
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

function deleteStudent(id) {
    if (confirm('Are you sure you want to delete this student? This action cannot be undone.')) {
        fetch("/students/" + id, {
            method: "DELETE",
            headers: {
                "X-CSRF-TOKEN": "{{ csrf_token() }}",
                "Accept": "application/json",
                "Content-Type": "application/json"
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Error deleting student: ' + (data.message || 'Unknown error'));
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error deleting student. Please try again.');
        });
    }
}

function validateForm() {
    const formMethod = document.getElementById('formMethod').value;
    const password = document.getElementById('password').value;
    
    if (formMethod === 'POST' && (!password || password.length < 6)) {
        alert('Password is required and must be at least 6 characters for new students.');
        return false;
    }
    
    if (formMethod === 'PUT' && password && password.length < 6) {
        alert('Password must be at least 6 characters if provided.');
        return false;
    }
    
    return true;
}

// ==================== EXCEL UPLOAD FUNCTIONS ====================

function displayFileName() {
    const input = document.getElementById('excel_file');
    const fileName = document.getElementById('fileName');
    
    if (input.files && input.files[0]) {
        fileName.textContent = input.files[0].name;
        
        if (input.files[0].size > 5 * 1024 * 1024) {
            alert('File size exceeds 5MB. Please choose a smaller file.');
            input.value = '';
            fileName.textContent = 'No file chosen';
        }
        
        const allowedExtensions = ['.xlsx', '.xls', '.csv'];
        const fileExt = input.files[0].name.substring(input.files[0].name.lastIndexOf('.')).toLowerCase();
        if (!allowedExtensions.includes(fileExt)) {
            alert('Invalid file type. Please upload .xlsx, .xls, or .csv files only.');
            input.value = '';
            fileName.textContent = 'No file chosen';
        }
    }
}

function showProgress() {
    const fileInput = document.getElementById('excel_file');
    if (!fileInput.files || !fileInput.files[0]) {
        alert('Please select a file first.');
        return false;
    }
    
    const progressBar = document.getElementById('progressBar');
    const progress = document.getElementById('progress');
    
    progressBar.style.display = 'block';
    progress.style.width = '0%';
    progress.textContent = '0%';
    
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
    
    return true;
}

document.getElementById('excelUploadForm')?.addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const progressBar = document.getElementById('progressBar');
    const progress = document.getElementById('progress');
    
    progressBar.style.display = 'block';
    progress.style.width = '0%';
    progress.textContent = '0%';
    
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
    
    fetch(this.action, {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    })
    .then(response => response.json())
    .then(data => {
        clearInterval(interval);
        progress.style.width = '100%';
        progress.textContent = '100%';
        
        setTimeout(() => {
            if (data.success) {
                location.reload();
            } else {
                progressBar.style.display = 'none';
                if (data.errors) {
                    displayErrors(data.errors);
                } else {
                    alert(data.message || 'Error uploading file');
                }
            }
        }, 500);
    })
    .catch(error => {
        clearInterval(interval);
        progressBar.style.display = 'none';
        console.error('Error:', error);
        alert('Error uploading file. Please try again.');
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
        html += `<td>${error.row}</td>`;
        html += `<td>${error.attribute}</td>`;
        html += `<td>${error.errors.join(', ')}</td>`;
        html += `<td><pre style="margin:0; font-size:12px;">${JSON.stringify(error.values, null, 2)}</pre></td>`;
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

// Auto-hide alerts after 5 seconds
setTimeout(function() {
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(function(alert) {
        alert.style.opacity = '0';
        setTimeout(function() {
            alert.style.display = 'none';
        }, 300);
    });
}, 5000);

// Display import errors from server if any
@if(session('import_errors'))
    document.addEventListener('DOMContentLoaded', function() {
        const errors = @json(session('import_errors'));
        displayErrors(errors);
    });
@endif

</script>

@endsection