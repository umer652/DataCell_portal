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
    transition: all 0.3s ease;
}

.add-btn:hover {
    background: #1a2a7a;
    transform: translateY(-2px);
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
    margin:10px;
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
    transition: color 0.2s;
}

.close:hover {
    color: #d33;
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
    transition: border-color 0.2s;
}

input:focus, select:focus {
    outline: none;
    border-color: #0f1b5c;
    box-shadow: 0 0 0 2px rgba(15,27,92,0.1);
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
    transition: all 0.3s ease;
}

.submit-btn:hover {
    background: #1a2a7a;
    transform: translateY(-2px);
}

.action-icons {
    display: flex;
    gap: 12px;
}

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
    transition: background 0.2s;
}

.file-label:hover {
    background: #1a2a7a;
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
    align-items: center;
    justify-content: center;
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

</style>
@endsection

@section('content')

<meta name="csrf-token" content="{{ csrf_token() }}">

<div class="main-container" id="mainContainer">

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
        
        <form id="excelUploadForm" enctype="multipart/form-data">
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
            <select name="session_filter" id="sessionFilter" class="session-dropdown">
                <option value="">All Sessions</option>
                @foreach($sessions as $session)
                    <option value="{{ $session->id }}"
                        {{ request('session_filter') == $session->id ? 'selected' : '' }}>
                        {{ $session->name }}
                    </option>
                @endforeach
            </select>
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
                            <i class="fa-solid fa-pen-to-square edit-icon" data-id="{{ $student->id }}" style="cursor: pointer;"></i>
                            <i class="fa-solid fa-trash delete-icon" data-id="{{ $student->id }}" style="cursor: pointer;"></i>
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
// CSRF Token setup
const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

// Get DOM elements
const modal = document.getElementById('studentModal');
const addBtn = document.getElementById('addStudentBtn');
const closeModalBtn = document.getElementById('closeModalBtn');
const saveStudentBtn = document.getElementById('saveStudentBtn');
const modalTitle = document.getElementById('modalTitle');
const studentForm = document.getElementById('studentForm');
const formMethod = document.getElementById('formMethod');
const studentId = document.getElementById('studentId');
const sessionFilter = document.getElementById('sessionFilter');

// ==================== MODAL FUNCTIONS ====================

function openModal() {
    studentForm.reset();
    formMethod.value = "POST";
    modalTitle.textContent = "Student Registration Form";
    studentId.value = '';
    
    // Set password field for new student
    document.getElementById('password').required = true;
    document.getElementById('passwordRequired').style.display = 'inline';
    document.getElementById('passwordHelp').style.display = 'none';
    
    modal.style.display = 'flex';
}

function closeModal() {
    modal.style.display = 'none';
}

addBtn.addEventListener('click', openModal);
closeModalBtn.addEventListener('click', closeModal);

// Close modal when clicking outside
window.addEventListener('click', function(event) {
    if (event.target === modal) closeModal();
    if (event.target === document.getElementById('errorModal')) closeErrorModal();
});

// ==================== FILTER FUNCTION ====================

sessionFilter.addEventListener('change', function() {
    const sessionId = this.value;
    let url = '{{ route("dashboard") }}';
    if (sessionId) url += '?session_filter=' + sessionId;
    window.location.href = url;
});

// ==================== FILE UPLOAD ====================

document.getElementById('excel_file')?.addEventListener('change', function() {
    const fileName = document.getElementById('fileName');
    if (this.files && this.files[0]) {
        fileName.textContent = this.files[0].name;
        
        if (this.files[0].size > 5 * 1024 * 1024) {
            Swal.fire('Error', 'File size exceeds 5MB', 'error');
            this.value = '';
            fileName.textContent = 'No file chosen';
        }
        
        const allowedExtensions = ['.xlsx', '.xls', '.csv'];
        const fileExt = this.files[0].name.substring(this.files[0].name.lastIndexOf('.')).toLowerCase();
        if (!allowedExtensions.includes(fileExt)) {
            Swal.fire('Error', 'Invalid file type. Please upload .xlsx, .xls, or .csv files only.', 'error');
            this.value = '';
            fileName.textContent = 'No file chosen';
        }
    } else {
        fileName.textContent = 'No file chosen';
    }
});

document.getElementById('importBtn')?.addEventListener('click', async function() {
    const fileInput = document.getElementById('excel_file');
    const file = fileInput.files[0];
    
    if (!file) {
        Swal.fire('Error', 'Please select a file first.', 'error');
        return;
    }
    
    const formData = new FormData();
    formData.append('excel_file', file);
    formData.append('_token', csrfToken);
    
    const progressBar = document.getElementById('progressBar');
    const progress = document.getElementById('progress');
    
    progressBar.style.display = 'block';
    progress.style.width = '0%';
    progress.textContent = '0%';
    
    let width = 0;
    const interval = setInterval(() => {
        if (width >= 90) clearInterval(interval);
        else {
            width += 10;
            progress.style.width = width + '%';
            progress.textContent = width + '%';
        }
    }, 200);
    
    try {
        const response = await fetch('{{ route("students.import.excel") }}', {
            method: 'POST',
            body: formData
        });
        
        const data = await response.json();
        clearInterval(interval);
        progress.style.width = '100%';
        progress.textContent = '100%';
        
        if (data.success) {
            Swal.fire('Success!', data.message, 'success').then(() => location.reload());
        } else {
            progressBar.style.display = 'none';
            if (data.errors) displayErrors(data.errors);
            else Swal.fire('Error!', data.message || 'Error uploading file', 'error');
        }
    } catch (error) {
        clearInterval(interval);
        progressBar.style.display = 'none';
        Swal.fire('Error!', 'Network error. Please try again.', 'error');
    }
});

// ==================== SAVE STUDENT ====================

async function saveStudent() {
    const method = formMethod.value;
    const id = studentId.value;
    const password = document.getElementById('password').value;
    
    // Validation
    if (method === 'POST' && (!password || password.length < 6)) {
        Swal.fire('Error', 'Password is required and must be at least 6 characters for new students.', 'error');
        return;
    }
    
    if (method === 'PUT' && password && password.length < 6) {
        Swal.fire('Error', 'Password must be at least 6 characters if provided.', 'error');
        return;
    }
    
    const formData = new FormData();
    formData.append('_token', csrfToken);
    formData.append('name', document.getElementById('name').value);
    formData.append('email', document.getElementById('email').value);
    if (password) formData.append('password', password);
    formData.append('gender', document.getElementById('gender').value);
    formData.append('father_name', document.getElementById('father_name').value);
    formData.append('roll_no', document.getElementById('roll_no').value);
    formData.append('app_no', document.getElementById('app_no').value);
    formData.append('semester', document.getElementById('semester').value);
    formData.append('program_id', document.getElementById('program_id').value);
    formData.append('session_id', document.getElementById('session_id').value);
    formData.append('section_id', document.getElementById('section_id').value);
    formData.append('department', document.getElementById('department').value);
    formData.append('designation', document.getElementById('designation').value);
    formData.append('enrollment_date', document.getElementById('enrollment_date').value);
    formData.append('new_student', document.getElementById('new_student').value);
    
    let url = "{{ route('students.store') }}";
    
    if (method === 'PUT' && id) {
        formData.append('_method', 'PUT');
        url = "/students/" + id;
    }
    
    saveStudentBtn.disabled = true;
    saveStudentBtn.innerHTML = '<span class="spinner"></span> Saving...';
    
    try {
        const response = await fetch(url, {
            method: 'POST',
            body: formData,
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        });
        
        const data = await response.json();
        
        if (data.success) {
            Swal.fire('Success!', data.message, 'success').then(() => location.reload());
        } else {
            if (data.errors) {
                let errorMsg = '';
                for (let field in data.errors) {
                    errorMsg += data.errors[field].join(', ') + '\n';
                }
                Swal.fire('Error!', errorMsg, 'error');
            } else {
                Swal.fire('Error!', data.message || 'Error saving student', 'error');
            }
            saveStudentBtn.disabled = false;
            saveStudentBtn.innerHTML = 'Save Student';
        }
    } catch (error) {
        Swal.fire('Error!', 'Network error. Please try again.', 'error');
        saveStudentBtn.disabled = false;
        saveStudentBtn.innerHTML = 'Save Student';
    }
}

saveStudentBtn.addEventListener('click', saveStudent);

// ==================== EDIT STUDENT ====================

async function editStudent(id) {
    try {
        const response = await fetch(`/students/${id}/edit`, {
            headers: { 
                'X-Requested-With': 'XMLHttpRequest', 
                'Accept': 'application/json'
            }
        });
        
        const student = await response.json();
        
        if (response.ok && student.success !== false) {
            modalTitle.textContent = "Edit Student";
            formMethod.value = "PUT";
            studentId.value = student.id;
            
            document.getElementById('name').value = student.user?.name || student.name || '';
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
            
            // Set password field for update (optional)
            document.getElementById('password').required = false;
            document.getElementById('passwordRequired').style.display = 'none';
            document.getElementById('passwordHelp').style.display = 'block';
            document.getElementById('password').value = '';
            
            modal.style.display = 'flex';
        } else {
            Swal.fire('Error', student.message || 'Could not fetch student details', 'error');
        }
    } catch (error) {
        console.error('Error:', error);
        Swal.fire('Error', 'Network error. Could not fetch student details.', 'error');
    }
}
// ==================== DELETE STUDENT ====================

async function deleteStudent(id) {
    const result = await Swal.fire({
        title: 'Are you sure?',
        text: "This action cannot be undone!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc3545',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Yes, delete it!'
    });
    
    if (result.isConfirmed) {
        try {
            const response = await fetch("/students/" + id, {
                method: "DELETE",
                headers: {
                    "X-CSRF-TOKEN": csrfToken,
                    "Content-Type": "application/json",
                    "X-Requested-With": "XMLHttpRequest"
                }
            });
            
            const data = await response.json();
            
            if (data.success) {
                const row = document.getElementById('student-row-' + id);
                if (row) row.remove();
                
                Swal.fire('Deleted!', data.message, 'success');
            } else {
                Swal.fire('Error!', data.message || 'Could not delete student', 'error');
            }
        } catch (error) {
            Swal.fire('Error!', 'Network error. Please try again.', 'error');
        }
    }
}

// ==================== ATTACH EVENTS ====================

function attachIconEvents() {
    document.querySelectorAll('.edit-icon').forEach(icon => {
        icon.removeEventListener('click', icon.clickHandler);
        const id = icon.getAttribute('data-id');
        icon.clickHandler = () => editStudent(id);
        icon.addEventListener('click', icon.clickHandler);
    });
    
    document.querySelectorAll('.delete-icon').forEach(icon => {
        icon.removeEventListener('click', icon.clickHandler);
        const id = icon.getAttribute('data-id');
        icon.clickHandler = () => deleteStudent(id);
        icon.addEventListener('click', icon.clickHandler);
    });
}

attachIconEvents();

// ==================== ERROR DISPLAY ====================

function displayErrors(errors) {
    const errorModal = document.getElementById('errorModal');
    const errorContent = document.getElementById('errorContent');
    
    let html = '<table class="error-table"><thead><tr><th>Row #</th><th>Field</th><th>Error</th><th>Values</th></tr></thead><tbody>';
    
    errors.forEach(error => {
        html += `<tr>
            <td>${error.row}</td>
            <td>${error.attribute}</td>
            <td>${error.errors.join(', ')}</td>
            <td><pre style="margin:0; font-size:12px;">${JSON.stringify(error.values, null, 2)}</pre></td>
        </tr>`;
    });
    
    html += '</tbody></table>';
    errorContent.innerHTML = html;
    errorModal.style.display = 'flex';
}

function closeErrorModal() {
    document.getElementById('errorModal').style.display = 'none';
}

// Enter key submit
document.getElementById('studentForm')?.addEventListener('keypress', function(e) {
    if (e.key === 'Enter') {
        e.preventDefault();
        saveStudent();
    }
});

</script>

@endsection