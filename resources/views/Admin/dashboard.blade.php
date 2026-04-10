@extends('layouts.app')

@section('title', 'Students')

@section('styles')
<style>
/* =========================
   MAIN CONTAINER
========================= */
.main-container {
    position: fixed;
    top: 80px;
    left: 270px;
    width: calc(100% - 290px);
    bottom: 20px;
    background: #fff;
    padding: 25px;
    border-radius: 12px;
    box-shadow: 0 3px 12px rgba(0,0,0,0.08);
    display: flex;
    flex-direction: column;
    overflow: hidden;
}

body.sidebar-collapsed .main-container {
    left: 100px;
    width: calc(100% - 120px);
}

/* TOP BAR */
.top-bar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 18px;
    flex-shrink: 0;
}

.page-title {
    font-size: 20px;
    font-weight: 600;
    color: #0f1b5c;
}

.add-btn {
    background: #0f1b5c;
    color: #fff;
    padding: 10px 18px;
    border-radius: 8px;
    border: none;
    cursor: pointer;
    transition: all 0.3s;
}
.add-btn:hover { background: #0c1445; transform: translateY(-1px); }

/* FILTER BAR */
.filter-bar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 18px;
    flex-shrink: 0;
    gap: 15px;
}

.session-dropdown {
    padding: 10px 14px;
    border-radius: 8px;
    border: 1px solid #0f1b5c;
    background-color: #0f1b5c;
    color: #fff;
    font-weight: 500;
    cursor: pointer;
    min-width: 200px;
}

.session-dropdown option {
    background: #fff;
    color: #000;
}

/* TABLE */
.table-container {
    flex: 1;
    overflow: auto;
    border-radius: 10px;
    border: 1px solid #eee;
}

table {
    width: 100%;
    min-width: 1300px;
    border-collapse: collapse;
}

thead {
    background: #0f1b5c;
    color: #fff;
    position: sticky;
    top: 0;
    z-index: 10;
}

th, td {
    padding: 12px;
    font-size: 14px;
    border-bottom: 1px solid #eee;
    text-align: left;
}

tbody tr:hover {
    background: #f5f5f5;
}

/* ACTION BUTTONS */
.action-buttons {
    display: flex;
    gap: 8px;
}

.edit-btn, .delete-btn {
    padding: 5px 12px;
    border-radius: 5px;
    border: none;
    cursor: pointer;
    font-size: 12px;
    transition: all 0.2s;
}

.edit-btn {
    background: #ffc107;
    color: #000;
}

.edit-btn:hover {
    background: #e0a800;
}

.delete-btn {
    background: #dc3545;
    color: #fff;
}

.delete-btn:hover {
    background: #c82333;
}

/* Excel Upload Section */
.upload-section {
    background: #f8f9fa;
    border-radius: 10px;
    padding: 20px;
    margin-bottom: 20px;
    border: 1px dashed #0f1b5c;
    flex-shrink: 0;
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

.btn-excel:hover,
.btn-template:hover {
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

/* MODAL */
.modal {
    display: none;
    position: fixed;
    inset: 0;
    background: rgba(0,0,0,0.5);
    z-index: 2000;
    justify-content: center;
    align-items: center;
}

.modal-content {
    background: #fff;
    width: 75%;
    padding: 25px;
    border-radius: 12px;
    max-height: 85vh;
    overflow-y: auto;
    position: relative;
    animation: slideDown 0.3s ease;
}

@keyframes slideDown {
    from {
        transform: translateY(-50px);
        opacity: 0;
    }
    to {
        transform: translateY(0);
        opacity: 1;
    }
}

.close {
    position: absolute;
    right: 15px;
    top: 10px;
    font-size: 28px;
    cursor: pointer;
    color: #999;
    transition: color 0.2s;
}

.close:hover {
    color: #000;
}

/* FORM GRID */
.form-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 18px;
}

.form-group {
    display: flex;
    flex-direction: column;
    gap: 6px;
}

.form-group label {
    font-size: 13px;
    font-weight: 600;
    color: #333;
}

.form-group label .required {
    color: red;
}

.form-group select,
.form-group input {
    height: 42px;
    padding: 8px 12px;
    border-radius: 8px;
    border: 1px solid #ddd;
    font-size: 14px;
    transition: border-color 0.2s;
}

.form-group select:focus,
.form-group input:focus {
    outline: none;
    border-color: #0f1b5c;
}

/* ACTIONS */
.form-actions {
    grid-column: 1 / -1;
    display: flex;
    justify-content: flex-end;
    gap: 12px;
    padding-top: 15px;
    border-top: 1px solid #eee;
    margin-top: 10px;
}

.cancel-btn {
    background: #ddd;
    padding: 10px 16px;
    border-radius: 8px;
    border: none;
    cursor: pointer;
    transition: background 0.2s;
}

.cancel-btn:hover {
    background: #ccc;
}

.save-btn {
    background: #0f1b5c;
    color: #fff;
    padding: 10px 18px;
    border-radius: 8px;
    border: none;
    cursor: pointer;
    transition: background 0.2s;
}

.save-btn:hover {
    background: #0c1445;
}

.save-btn:disabled {
    background: #999;
    cursor: not-allowed;
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

/* Error Modal Styles */
.error-modal {
    display: none;
    position: fixed;
    inset: 0;
    background: rgba(0,0,0,0.8);
    z-index: 2100;
    justify-content: center;
    align-items: center;
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

/* Empty State */
.empty-state {
    text-align: center;
    padding: 60px 20px;
    color: #999;
}

.empty-state i {
    font-size: 64px;
    color: #cbd5e0;
    margin-bottom: 20px;
}

.empty-state p {
    color: #718096;
    font-size: 16px;
    margin-bottom: 20px;
}

/* Toast Container */
.toast-container {
    position: fixed;
    top: 20px;
    right: 20px;
    z-index: 10000;
}

.toast {
    background: white;
    border-radius: 8px;
    padding: 15px 20px;
    margin-bottom: 10px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    animation: slideInRight 0.3s ease;
    min-width: 300px;
    max-width: 450px;
}

@keyframes slideInRight {
    from {
        transform: translateX(100%);
        opacity: 0;
    }
    to {
        transform: translateX(0);
        opacity: 1;
    }
}

@keyframes slideOutRight {
    from {
        transform: translateX(0);
        opacity: 1;
    }
    to {
        transform: translateX(100%);
        opacity: 0;
    }
}

.toast-success {
    border-left: 4px solid #28a745;
    background: #d4edda;
    color: #155724;
}

.toast-error {
    border-left: 4px solid #dc3545;
    background: #f8d7da;
    color: #721c24;
}

.toast-info {
    border-left: 4px solid #17a2b8;
    background: #d1ecf1;
    color: #0c5460;
}

.toast-warning {
    border-left: 4px solid #ffc107;
    background: #fff3cd;
    color: #856404;
}

.toast-title {
    font-weight: bold;
    margin-bottom: 5px;
}

.toast-message {
    font-size: 14px;
}
</style>
@endsection

@section('content')

<div class="main-container">

    <!-- TOP BAR -->
    <div class="top-bar">
        <h2 class="page-title">Student Management</h2>
    </div>

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

    <div class="filter-bar">
        <form id="sessionFilterForm" method="GET" action="{{ route('dashboard') }}">
            <select name="session_filter" onchange="this.form.submit()" class="session-dropdown">
                <option value="">All Sessions</option>
                @foreach($sessions as $session)
                <option value="{{ $session->id }}" {{ request('session_filter') == $session->id ? 'selected' : '' }}>
                    {{ $session->name }}
                </option>
                @endforeach
            </select>
        </form>
        <button class="add-btn" id="addStudentBtn">+ New Student</button>
    </div>

    <!-- TABLE -->
    <div class="table-container">
        <table id="studentsTable">
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
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody id="studentsTableBody">
                @forelse($students as $student)
                <tr id="student-row-{{ $student->id }}">
                    <td>{{ $student->name ?? '' }}</td>
                    <td>{{ $student->user->email ?? '' }}</td>
                    <td>{{ $student->user->department ?? '' }}</td>
                    <td>{{ $student->user->designation ?? '' }}</td>
                    <td>{{ $student->gender ?? '' }}</td>
                    <td>{{ $student->father_name ?? '' }}</td>
                    <td>{{ $student->roll_no ?? '' }}</td>
                    <td>{{ $student->app_no ?? '' }}</td>
                    <td>{{ $student->program->name ?? '' }}</td>
                    <td>{{ $student->semester ?? '' }}</td>
                    <td>{{ $student->session->name ?? '' }}</td>
                    <td>{{ $student->section->name ?? '' }}</td>
                    <td>
                        <div class="action-buttons">
                            <button class="edit-btn" data-student='@json($student)'>Edit</button>
                            <button class="delete-btn" data-id="{{ $student->id }}">Delete</button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr class="empty-state">
                    <td colspan="13">
                        <div class="empty-state">
                            <i class="fa-solid fa-user-graduate"></i>
                            <p>No students found.</p>
                            <button class="add-btn" onclick="openModal()">+ Add Your First Student</button>
                        </div>
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
        <h3 id="modalTitle" style="margin-bottom:15px;">Student Registration Form</h3>

        <form id="studentForm">
            @csrf
            <input type="hidden" name="_method" id="formMethod" value="POST">
            <input type="hidden" name="student_id" id="studentId" value="">

            <div class="form-grid">
                <div class="form-group">
                    <label>Name <span class="required">*</span></label>
                    <input type="text" name="name" id="name" required>
                </div>

                <div class="form-group">
                    <label>Email <span class="required">*</span></label>
                    <input type="email" name="email" id="email" required>
                </div>

                <div class="form-group">
                    <label>Password <span id="passwordRequired" class="required">*</span></label>
                    <input type="password" name="password" id="password">
                    <small id="passwordHelp" class="help-text" style="display: none;">Leave blank to keep current password (for updates)</small>
                </div>

                <div class="form-group">
                    <label>Department <span class="required">*</span></label>
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
                    <label>Gender <span class="required">*</span></label>
                    <select name="gender" id="gender" required>
                        <option value="">Select Gender</option>
                        <option value="Male">Male</option>
                        <option value="Female">Female</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Father Name <span class="required">*</span></label>
                    <input type="text" name="father_name" id="father_name" required>
                </div>

                <div class="form-group">
                    <label>Roll No <span class="required">*</span></label>
                    <input type="text" name="roll_no" id="roll_no" required>
                </div>

                <div class="form-group">
                    <label>App No <span class="required">*</span></label>
                    <input type="text" name="app_no" id="app_no" required>
                </div>

                <div class="form-group">
                    <label>Semester <span class="required">*</span></label>
                    <input type="number" name="semester" id="semester" min="1" max="8" required>
                </div>

                <div class="form-group">
                    <label>Program <span class="required">*</span></label>
                    <select name="program_id" id="program_id" required>
                        <option value="">Select Program</option>
                        @foreach($programs as $program)
                        <option value="{{ $program->id }}">{{ $program->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label>Session <span class="required">*</span></label>
                    <select name="session_id" id="session_id" required>
                        <option value="">Select Session</option>
                        @foreach($sessions as $session)
                        <option value="{{ $session->id }}">{{ $session->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label>Section <span class="required">*</span></label>
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

                <div class="form-actions">
                    <button type="button" class="cancel-btn" onclick="closeModal()">Cancel</button>
                    <button type="button" class="save-btn" id="saveStudentBtn">Save Student</button>
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
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
// ==================== INITIALIZATION ====================
// Function to initialize all event listeners
function initializePage() {
    console.log('Initializing student page...');
    
    // Re-attach event listeners
    const addBtn = document.getElementById('addStudentBtn');
    if (addBtn) {
        // Remove existing listeners to avoid duplicates
        const newAddBtn = addBtn.cloneNode(true);
        addBtn.parentNode.replaceChild(newAddBtn, addBtn);
        newAddBtn.addEventListener('click', openModal);
    }
    
    const closeBtn = document.getElementById('closeModalBtn');
    if (closeBtn) {
        const newCloseBtn = closeBtn.cloneNode(true);
        closeBtn.parentNode.replaceChild(newCloseBtn, closeBtn);
        newCloseBtn.addEventListener('click', closeModal);
    }
    
    const saveBtn = document.getElementById('saveStudentBtn');
    if (saveBtn) {
        const newSaveBtn = saveBtn.cloneNode(true);
        saveBtn.parentNode.replaceChild(newSaveBtn, saveBtn);
        newSaveBtn.addEventListener('click', saveStudent);
    }
    
    // Re-attach edit buttons
    document.querySelectorAll('.edit-btn').forEach(btn => {
        const newBtn = btn.cloneNode(true);
        btn.parentNode.replaceChild(newBtn, btn);
        const studentData = newBtn.getAttribute('data-student');
        if (studentData) {
            const student = JSON.parse(studentData);
            newBtn.addEventListener('click', () => editStudent(student));
        }
    });
    
    // Re-attach delete buttons
    document.querySelectorAll('.delete-btn').forEach(btn => {
        const newBtn = btn.cloneNode(true);
        btn.parentNode.replaceChild(newBtn, btn);
        const studentId = newBtn.getAttribute('data-id');
        if (studentId) {
            newBtn.addEventListener('click', () => deleteStudent(studentId));
        }
    });
    
    // Re-attach file input change event
    const fileInput = document.getElementById('excel_file');
    if (fileInput) {
        const newFileInput = fileInput.cloneNode(true);
        fileInput.parentNode.replaceChild(newFileInput, fileInput);
        newFileInput.addEventListener('change', handleFileChange);
    }
    
    // Re-attach import button
    const importBtn = document.getElementById('importBtn');
    if (importBtn) {
        const newImportBtn = importBtn.cloneNode(true);
        importBtn.parentNode.replaceChild(newImportBtn, importBtn);
        newImportBtn.addEventListener('click', handleImport);
    }
    
    // Re-attach session filter form submit
    const sessionForm = document.getElementById('sessionFilterForm');
    if (sessionForm) {
        const newSessionForm = sessionForm.cloneNode(true);
        sessionForm.parentNode.replaceChild(newSessionForm, sessionForm);
        newSessionForm.addEventListener('submit', function(e) {
            e.preventDefault();
            this.submit();
        });
    }
    
    // Display any session messages
    @if(session('success'))
    showToast('{{ session("success") }}', 'success');
    @endif
    
    @if(session('error'))
    showToast('{{ session("error") }}', 'error');
    @endif
}

// ==================== MODAL FUNCTIONS ====================

function openModal() {
    const modal = document.getElementById('studentModal');
    const form = document.getElementById('studentForm');
    const formMethod = document.getElementById('formMethod');
    const passwordField = document.getElementById('password');
    const passwordRequired = document.getElementById('passwordRequired');
    const passwordHelp = document.getElementById('passwordHelp');
    const studentId = document.getElementById('studentId');

    form.reset();
    formMethod.value = "POST";
    studentId.value = '';

    passwordField.required = true;
    passwordField.placeholder = "Enter password";
    passwordRequired.style.display = 'inline';
    passwordHelp.style.display = 'none';

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

    document.getElementById('modalTitle').textContent = 'Student Registration Form';
    document.getElementById('saveStudentBtn').textContent = 'Save Student';

    modal.style.display = 'flex';
    document.body.style.overflow = 'hidden';
}

function closeModal() {
    document.getElementById('studentModal').style.display = 'none';
    document.body.style.overflow = '';
}

function editStudent(student) {
    const formMethod = document.getElementById('formMethod');
    const passwordField = document.getElementById('password');
    const passwordRequired = document.getElementById('passwordRequired');
    const passwordHelp = document.getElementById('passwordHelp');
    const studentId = document.getElementById('studentId');

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

    document.getElementById('modalTitle').textContent = 'Edit Student';
    document.getElementById('saveStudentBtn').textContent = 'Update Student';

    openModal();
}

function saveStudent() {
    const formMethod = document.getElementById('formMethod').value;
    const studentId = document.getElementById('studentId').value;
    const password = document.getElementById('password').value;
    const name = document.getElementById('name').value;
    const email = document.getElementById('email').value;
    const saveBtn = document.getElementById('saveStudentBtn');

    if (!name || !email) {
        showToast('Name and Email are required fields.', 'error');
        return;
    }

    if (formMethod === 'POST' && (!password || password.length < 6)) {
        showToast('Password is required and must be at least 6 characters for new students.', 'error');
        return;
    }

    if (formMethod === 'PUT' && password && password.length < 6) {
        showToast('Password must be at least 6 characters if provided.', 'error');
        return;
    }

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

    saveBtn.disabled = true;
    saveBtn.innerHTML = '<span class="spinner"></span> Saving...';

    $.ajax({
        url: url,
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        success: function(response) {
            if (response.success) {
                showToast(response.message || 'Student saved successfully.', 'success');
                setTimeout(() => {
                    location.reload();
                }, 1000);
            } else {
                saveBtn.disabled = false;
                saveBtn.innerHTML = formMethod === 'POST' ? 'Save Student' : 'Update Student';
                showToast(response.message || 'An error occurred', 'error');
            }
        },
        error: function(xhr) {
            saveBtn.disabled = false;
            saveBtn.innerHTML = formMethod === 'POST' ? 'Save Student' : 'Update Student';
            
            let errorMsg = 'An error occurred. Please try again.';
            if (xhr.responseJSON && xhr.responseJSON.message) {
                errorMsg = xhr.responseJSON.message;
            } else if (xhr.responseJSON && xhr.responseJSON.errors) {
                errorMsg = Object.values(xhr.responseJSON.errors).flat().join('\n');
            }
            showToast(errorMsg, 'error');
        }
    });
}

function deleteStudent(id) {
    if (confirm('⚠️ Are you sure you want to delete this student?\n\nThis action cannot be undone!')) {
        showToast('Deleting student...', 'info');
        
        $.ajax({
            url: "/students/" + id,
            type: "DELETE",
            data: {
                _token: "{{ csrf_token() }}"
            },
            success: function(response) {
                if (response.success) {
                    showToast(response.message || 'Student has been deleted.', 'success');
                    setTimeout(() => {
                        location.reload();
                    }, 1000);
                } else {
                    showToast(response.message || 'Could not delete student.', 'error');
                }
            },
            error: function(xhr) {
                let errorMsg = 'Network error. Please try again.';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMsg = xhr.responseJSON.message;
                }
                showToast(errorMsg, 'error');
            }
        });
    } else {
        showToast('Deletion cancelled', 'info');
    }
}

// ==================== EXCEL UPLOAD FUNCTIONS ====================

function handleFileChange() {
    const fileName = document.getElementById('fileName');
    const input = this;

    if (input.files && input.files[0]) {
        fileName.textContent = input.files[0].name;

        if (input.files[0].size > 5 * 1024 * 1024) {
            showToast('File size exceeds 5MB. Please choose a smaller file.', 'error');
            input.value = '';
            fileName.textContent = 'No file chosen';
            return;
        }

        const allowedExtensions = ['.xlsx', '.xls', '.csv'];
        const fileExt = input.files[0].name.substring(input.files[0].name.lastIndexOf('.')).toLowerCase();
        if (!allowedExtensions.includes(fileExt)) {
            showToast('Invalid file type. Please upload .xlsx, .xls, or .csv files only.', 'error');
            input.value = '';
            fileName.textContent = 'No file chosen';
        }
    }
}

function handleImport() {
    const form = document.getElementById('excelUploadForm');
    const fileInput = document.getElementById('excel_file');

    if (!fileInput.files || !fileInput.files[0]) {
        showToast('Please select a file first.', 'error');
        return;
    }

    const formData = new FormData(form);
    const progressBar = document.getElementById('progressBar');
    const progress = document.getElementById('progress');
    const importBtn = this;

    progressBar.style.display = 'block';
    progress.style.width = '0%';
    progress.textContent = '0%';
    importBtn.disabled = true;
    importBtn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Uploading...';

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
        url: form.action,
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        success: function(data) {
            clearInterval(interval);
            progress.style.width = '100%';
            progress.textContent = '100%';
            importBtn.disabled = false;
            importBtn.innerHTML = '<i class="fa-solid fa-cloud-upload-alt"></i> Import Students';

            setTimeout(() => {
                if (data.success) {
                    showToast(data.message || 'Students imported successfully.', 'success');
                    setTimeout(() => {
                        location.reload();
                    }, 1000);
                } else {
                    progressBar.style.display = 'none';
                    if (data.errors && data.errors.length > 0) {
                        displayErrors(data.errors);
                    } else {
                        showToast(data.message || 'Error uploading file', 'error');
                    }
                }
            }, 500);
        },
        error: function(xhr) {
            clearInterval(interval);
            progressBar.style.display = 'none';
            importBtn.disabled = false;
            importBtn.innerHTML = '<i class="fa-solid fa-cloud-upload-alt"></i> Import Students';

            let errorMsg = 'Network error. Please try again.';
            if (xhr.responseJSON && xhr.responseJSON.message) {
                errorMsg = xhr.responseJSON.message;
            }
            showToast(errorMsg, 'error');
        }
    });
}

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

    html += '</tbody></tr>';
    errorContent.innerHTML = html;
    errorModal.style.display = 'flex';
}

function closeErrorModal() {
    document.getElementById('errorModal').style.display = 'none';
}

// Toast notification system
function showToast(message, type = 'success') {
    let container = document.querySelector('.toast-container');
    if (!container) {
        container = document.createElement('div');
        container.className = 'toast-container';
        document.body.appendChild(container);
    }
    
    const toast = document.createElement('div');
    toast.className = `toast toast-${type}`;
    
    let icon = '';
    let title = '';
    switch(type) {
        case 'success':
            icon = '✓';
            title = 'Success!';
            break;
        case 'error':
            icon = '✗';
            title = 'Error!';
            break;
        case 'warning':
            icon = '⚠';
            title = 'Warning!';
            break;
        case 'info':
            icon = 'ℹ';
            title = 'Info';
            break;
        default:
            icon = '✓';
            title = 'Success!';
    }
    
    toast.innerHTML = `
        <div class="toast-title">${icon} ${title}</div>
        <div class="toast-message">${escapeHtml(message)}</div>
    `;
    
    container.appendChild(toast);
    
    setTimeout(() => {
        toast.style.animation = 'slideOutRight 0.3s ease';
        setTimeout(() => {
            if (toast.parentNode) {
                toast.remove();
            }
        }, 300);
    }, 3000);
}

function escapeHtml(text) {
    if (!text) return '';
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
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

// Initialize page when DOM loads and also when AJAX navigation occurs
document.addEventListener('DOMContentLoaded', initializePage);

// Also listen for AJAX navigation events (if your layout uses them)
document.addEventListener('ajax-loaded', initializePage);

// Use MutationObserver to detect when content is replaced via AJAX
const observer = new MutationObserver(function(mutations) {
    mutations.forEach(function(mutation) {
        if (mutation.type === 'childList' && mutation.target.id === 'main-content') {
            initializePage();
        }
    });
});

// Start observing after DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    const mainContent = document.getElementById('main-content');
    if (mainContent) {
        observer.observe(mainContent, { childList: true, subtree: true });
    }
    initializePage();
});
</script>

@endsection