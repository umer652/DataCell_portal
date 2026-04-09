@extends('layouts.app')

@section('title', 'Enrollment')

@section('styles')
<style>
/* SAME THEME */
.main-container {
    position: fixed;
    top: 80px;
    left: 270px;
    width: calc(100% - 290px);
    bottom: 20px;
    background: #fff;
    padding: 20px;
    border-radius: 12px;
    overflow-y: hidden;
    display: flex;
    flex-direction: column;
}

.top-bar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-shrink: 0;
}

.page-title {
    font-size: 20px;
    font-weight: 600;
    color: #0f1b5c;
    margin-bottom: 10px;
}

.add-btn {
    background: #0f1b5c;
    color: white;
    padding: 10px 20px;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    transition: all 0.3s;
}
.add-btn:hover {
    background: #0c1445;
    transform: translateY(-1px);
}

/* FILTER BAR */
.search-box {
    margin: 15px 0;
    display: flex;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 10px;
    flex-shrink: 0;
}

.filters-left {
    display: flex;
    gap: 10px;
}

.filters-right {
    display: flex;
    gap: 10px;
}

select, input {
    padding: 10px;
    border-radius: 8px;
    border: 1px solid #ddd;
}

.reset-btn {
    background: #888;
    color: #fff;
    padding: 10px 15px;
    border-radius: 8px;
    border: none;
    cursor: pointer;
}
.reset-btn:hover {
    background: #666;
}

/* TABLE CONTAINER - SCROLLABLE */
.table-container {
    flex: 1;
    overflow-y: auto;
    border: 1px solid #ddd;
    border-radius: 8px;
}

/* TABLE */
table {
    width: 100%;
    border-collapse: collapse;
}

thead {
    background: #0f1b5c;
    color: white;
    position: sticky;
    top: 0;
    z-index: 10;
}

td, th {
    padding: 12px;
    border: 1px solid #ddd;
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

/* MODAL */
.modal {
    display: none;
    position: fixed;
    inset: 0;
    background: rgba(0,0,0,0.5);
    justify-content: center;
    align-items: center;
    z-index: 1000;
}

.modal-content {
    background: #fff;
    width: 60%;
    padding: 25px;
    border-radius: 12px;
    max-height: 85vh;
    overflow-y: auto;
}

.form-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 15px;
}

.full {
    grid-column: 1 / -1;
}

.form-group {
    display: flex;
    flex-direction: column;
    gap: 5px;
}

.form-group label {
    font-weight: 600;
    font-size: 13px;
    color: #333;
}

.form-group select,
.form-group input {
    padding: 10px;
    border-radius: 8px;
    border: 1px solid #ddd;
}

.close {
    float: right;
    font-size: 28px;
    cursor: pointer;
    color: #999;
}

.close:hover {
    color: #000;
}

/* TOAST MESSAGES */
.toast-container {
    position: fixed;
    top: 20px;
    right: 20px;
    z-index: 10000;
    display: flex;
    flex-direction: column;
    gap: 10px;
}

.toast {
    min-width: 300px;
    padding: 12px 20px;
    border-radius: 8px;
    animation: slideIn 0.3s ease;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
}

.toast-success {
    background: #d4edda;
    color: #155724;
    border-left: 4px solid #28a745;
}

.toast-error {
    background: #f8d7da;
    color: #721c24;
    border-left: 4px solid #dc3545;
}

.toast-info {
    background: #d1ecf1;
    color: #0c5460;
    border-left: 4px solid #17a2b8;
}

.toast-warning {
    background: #fff3cd;
    color: #856404;
    border-left: 4px solid #ffc107;
}

.toast-message {
    flex: 1;
    font-size: 14px;
}

.toast-close {
    cursor: pointer;
    font-size: 18px;
    font-weight: bold;
    opacity: 0.7;
    transition: opacity 0.2s;
}

.toast-close:hover {
    opacity: 1;
}

@keyframes slideIn {
    from {
        transform: translateX(100%);
        opacity: 0;
    }
    to {
        transform: translateX(0);
        opacity: 1;
    }
}

@keyframes fadeOut {
    from {
        opacity: 1;
    }
    to {
        opacity: 0;
        transform: translateX(100%);
    }
}

/* CUSTOM CONFIRM DIALOG */
.custom-confirm-dialog {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 11000;
    animation: fadeIn 0.2s ease;
}

.confirm-dialog-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0,0,0,0.5);
}

.confirm-dialog-content {
    position: relative;
    background: white;
    border-radius: 12px;
    width: 400px;
    max-width: 90%;
    padding: 0;
    box-shadow: 0 10px 40px rgba(0,0,0,0.2);
    animation: scaleIn 0.2s ease;
}

@keyframes scaleIn {
    from {
        transform: scale(0.95);
        opacity: 0;
    }
    to {
        transform: scale(1);
        opacity: 1;
    }
}

@keyframes fadeIn {
    from {
        opacity: 0;
    }
    to {
        opacity: 1;
    }
}

.confirm-dialog-header {
    padding: 20px 24px 0 24px;
    display: flex;
    align-items: center;
    gap: 12px;
}

.confirm-dialog-icon {
    width: 40px;
    height: 40px;
    background: #fee2e2;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #dc3545;
}

.confirm-dialog-title {
    font-size: 18px;
    font-weight: 600;
    color: #333;
    margin: 0;
}

.confirm-dialog-body {
    padding: 16px 24px;
}

.confirm-dialog-message {
    font-size: 15px;
    color: #555;
    margin-bottom: 8px;
}

.confirm-dialog-detail {
    font-size: 13px;
    color: #888;
    margin-top: 8px;
}

.confirm-dialog-footer {
    padding: 16px 24px 24px 24px;
    display: flex;
    justify-content: flex-end;
    gap: 12px;
    border-top: 1px solid #eee;
}

.confirm-btn {
    padding: 8px 20px;
    border-radius: 6px;
    border: none;
    cursor: pointer;
    font-size: 14px;
    font-weight: 500;
    transition: all 0.2s;
}

.confirm-btn-cancel {
    background: #e9ecef;
    color: #495057;
}

.confirm-btn-cancel:hover {
    background: #dee2e6;
}

.confirm-btn-delete {
    background: #dc3545;
    color: white;
}

.confirm-btn-delete:hover {
    background: #c82333;
    transform: translateY(-1px);
}
</style>
@endsection

@section('content')

<div class="main-container">

    <!-- TOP -->
    <div class="top-bar">
        <h2 class="page-title">Enrollment Management</h2>
        <button class="add-btn" onclick="openModal()">+ Add Enrollment</button>
    </div>

    <!-- FILTERS -->
    <div class="search-box">
        <div class="filters-left">
            <select id="programFilter">
                <option value="">Program</option>
                @foreach($programs as $p)
                    <option value="{{ $p->id }}">{{ $p->name }}</option>
                @endforeach
            </select>

            <select id="sessionFilter">
                <option value="">Session</option>
                @foreach($sessions as $s)
                    <option value="{{ $s->id }}">{{ $s->name }}</option>
                @endforeach
            </select>

            <select id="semesterFilter">
                <option value="">Semester</option>
                @for($i=1;$i<=12;$i++)
                    <option value="{{ $i }}">{{ $i }}</option>
                @endfor
            </select>
        </div>

        <div class="filters-right">
            <input type="text" id="searchInput" placeholder="Search...">
            <button class="reset-btn" onclick="resetFilters()">Reset</button>
        </div>
    </div>

    <!-- SCROLLABLE TABLE CONTAINER -->
    <div class="table-container">
        <table id="allocationsTable">
            <thead>
                <tr>
                    <th>Student</th>
                    <th>Course</th>
                    <th>Program</th>
                    <th>Session</th>
                    <th>Semester</th>
                    <th>Section</th>
                    <th>Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody id="enrollmentBody">
            @foreach($enrollments as $e)
                <tr data-student-name="{{ $e->student->name }}">
                    <td>{{ $e->student->name }}</td>
                    <td>{{ $e->offeredCourse->course->course_title ?? '-' }}</td>
                    <td>{{ $e->program->name }}</td>
                    <td>{{ $e->session->name }}</td>
                    <td>{{ $e->semester }}</td>
                    <td>{{ $e->section->name }}</td>
                    <td>{{ $e->enrollment_date }}</td>
                    <td class="action-buttons">
                        <button onclick="editEnrollment({{ $e->id }})" class="edit-btn">Edit</button>
                        <button onclick="deleteEnrollment({{ $e->id }}, this)" class="delete-btn">Delete</button>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>

</div>

<!-- MODAL -->
<div class="modal" id="modal">
<div class="modal-content">
    <span class="close" onclick="closeModal()">&times;</span>
    <h3 id="modalTitle">Add Enrollment</h3>

    <form id="enrollmentForm">
        @csrf
        <input type="hidden" name="_method" id="methodField" value="POST">
        <input type="hidden" name="enrollment_id" id="enrollment_id">
        <input type="hidden" name="student_id_for_update" id="student_id_for_update">

        <div class="form-grid">
            <div class="form-group">
                <label>Student *</label>
                <select name="student_id" id="student_id" required>
                    <option value="">Select Student</option>
                    @foreach($students as $s)
                        <option value="{{ $s->id }}">{{ $s->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label>Program *</label>
                <select name="program_id" id="program_id" required>
                    <option value="">Select Program</option>
                    @foreach($programs as $p)
                        <option value="{{ $p->id }}">{{ $p->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label>Session *</label>
                <select name="session_id" id="session_id" required>
                    <option value="">Select Session</option>
                    @foreach($sessions as $s)
                        <option value="{{ $s->id }}">{{ $s->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label>Section *</label>
                <select name="section_id" id="section_id" required>
                    <option value="">Select Section</option>
                    @foreach($sections as $s)
                        <option value="{{ $s->id }}">{{ $s->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label>Semester *</label>
                <input type="number" name="semester" id="semester" placeholder="Semester" required>
            </div>

            <div class="form-group">
                <label>Enrollment Date *</label>
                <input type="date" name="enrollment_date" id="enrollment_date" required>
            </div>

            <div class="form-group full">
                <label>Courses *</label>
                <select name="offered_course_id[]" id="coursesDropdown" multiple class="full" required>
                    <option value="">Select Program and Semester First</option>
                </select>
                <small>Hold Ctrl/Cmd to select multiple courses</small>
            </div>

            <div class="full">
                <button type="submit" class="add-btn" style="width:100%">Save Enrollment</button>
            </div>
        </div>
    </form>
</div>
</div>

<!-- Toast Container -->
<div class="toast-container" id="toastContainer"></div>

<script>
// Store current enrollment ID for editing
let currentEnrollmentId = null;
let isEditMode = false;

// Get CSRF token
const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

// DOM Elements
let searchInput = document.getElementById('searchInput');
let sessionFilter = document.getElementById('sessionFilter');
let semesterFilter = document.getElementById('semesterFilter');
let programFilter = document.getElementById('programFilter');

// Load allocations on page load
document.addEventListener('DOMContentLoaded', function() {
    // Setup form submission
    document.getElementById('enrollmentForm').addEventListener('submit', handleFormSubmit);
    
    // Setup program change handler for course loading
    document.getElementById('program_id').addEventListener('change', loadCourses);
    document.getElementById('semester').addEventListener('input', loadCourses);
    
    // Setup filter handlers
    if (searchInput) searchInput.addEventListener('keyup', fetchData);
    if (sessionFilter) sessionFilter.addEventListener('change', fetchData);
    if (semesterFilter) semesterFilter.addEventListener('change', fetchData);
    if (programFilter) programFilter.addEventListener('change', fetchData);
});

// TOAST MESSAGES SYSTEM
function showToast(message, type = 'success', duration = 3000) {
    const container = document.getElementById('toastContainer');
    if (!container) return;
    
    const toast = document.createElement('div');
    toast.className = `toast toast-${type}`;
    toast.innerHTML = `
        <span class="toast-message">${message}</span>
        <span class="toast-close">&times;</span>
    `;
    
    container.appendChild(toast);
    
    // Add close button functionality
    const closeBtn = toast.querySelector('.toast-close');
    closeBtn.addEventListener('click', () => {
        removeToast(toast);
    });
    
    // Auto remove after duration
    setTimeout(() => {
        removeToast(toast);
    }, duration);
}

function removeToast(toast) {
    toast.style.animation = 'fadeOut 0.3s ease';
    setTimeout(() => {
        if (toast.parentNode) {
            toast.remove();
        }
    }, 300);
}

// CUSTOM CONFIRM DIALOG
function showConfirmDialog({ title, message, detail, onConfirm, onCancel }) {
    // Remove existing dialog if any
    const existingDialog = document.querySelector('.custom-confirm-dialog');
    if (existingDialog) {
        existingDialog.remove();
    }
    
    // Create dialog element
    const dialog = document.createElement('div');
    dialog.className = 'custom-confirm-dialog';
    dialog.innerHTML = `
        <div class="confirm-dialog-overlay"></div>
        <div class="confirm-dialog-content">
            <div class="confirm-dialog-header">
                <div class="confirm-dialog-icon">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M12 8V12M12 16H12.01M3 12C3 7.02944 7.02944 3 12 3C16.9706 3 21 7.02944 21 12C21 16.9706 16.9706 21 12 21C7.02944 21 3 16.9706 3 12Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </div>
                <h3 class="confirm-dialog-title">${title}</h3>
            </div>
            <div class="confirm-dialog-body">
                <div class="confirm-dialog-message">${message}</div>
                ${detail ? `<div class="confirm-dialog-detail">${detail}</div>` : ''}
            </div>
            <div class="confirm-dialog-footer">
                <button class="confirm-btn confirm-btn-cancel">Cancel</button>
                <button class="confirm-btn confirm-btn-delete">Delete</button>
            </div>
        </div>
    `;
    
    document.body.appendChild(dialog);
    
    // Add event listeners
    const overlay = dialog.querySelector('.confirm-dialog-overlay');
    const cancelBtn = dialog.querySelector('.confirm-btn-cancel');
    const deleteBtn = dialog.querySelector('.confirm-btn-delete');
    
    const closeDialog = () => {
        dialog.style.animation = 'fadeOut 0.2s ease';
        setTimeout(() => {
            if (dialog.parentNode) {
                dialog.remove();
            }
        }, 200);
    };
    
    overlay.addEventListener('click', () => {
        closeDialog();
        if (onCancel) onCancel();
    });
    
    cancelBtn.addEventListener('click', () => {
        closeDialog();
        if (onCancel) onCancel();
    });
    
    deleteBtn.addEventListener('click', () => {
        closeDialog();
        if (onConfirm) onConfirm();
    });
}

// FILTER FUNCTION
function fetchData() {
    fetch(`?search=${searchInput.value}&session_id=${sessionFilter.value}&semester=${semesterFilter.value}&program_id=${programFilter.value}`, {
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(res => res.text())
    .then(html => document.getElementById('enrollmentBody').innerHTML = html);
}

function resetFilters() {
    if (searchInput) searchInput.value = '';
    if (sessionFilter) sessionFilter.value = '';
    if (semesterFilter) semesterFilter.value = '';
    if (programFilter) programFilter.value = '';
    fetchData();
    showToast('Filters reset successfully', 'info', 2000);
}

// COURSE LOAD FUNCTION
function loadCourses() {
    let programId = document.getElementById('program_id').value;
    let semester = document.getElementById('semester').value;
    let dropdown = document.getElementById('coursesDropdown');
    
    if (!programId || !semester) {
        dropdown.innerHTML = '<option value="">Select Program and Semester First</option>';
        return;
    }
    
    dropdown.innerHTML = '<option>Loading courses...</option>';
    
    fetch(`/get-offered-courses?program_id=${programId}&semester=${semester}`)
        .then(res => res.json())
        .then(data => {
            dropdown.innerHTML = '';
            if (data.length === 0) {
                dropdown.innerHTML = '<option value="">No courses available</option>';
            } else {
                data.forEach(c => {
                    let option = document.createElement('option');
                    option.value = c.id;
                    option.textContent = c.course.course_title;
                    dropdown.appendChild(option);
                });
            }
        })
        .catch(error => {
            console.error('Error:', error);
            dropdown.innerHTML = '<option value="">Error loading courses</option>';
            showToast('Failed to load courses', 'error', 3000);
        });
}

// Handle form submission (Create/Update)
function handleFormSubmit(e) {
    e.preventDefault();
    
    const form = e.target;
    const formData = new FormData(form);
    const data = Object.fromEntries(formData);
    
    // Get selected courses (multiple)
    const courseSelect = document.getElementById('coursesDropdown');
    const selectedOptions = Array.from(courseSelect.selectedOptions);
    
    if (selectedOptions.length === 0) {
        showToast('Please select at least one course', 'error', 3000);
        return;
    }
    
    // For both create and update, send array of course IDs
    data.offered_course_id = selectedOptions.map(option => option.value);
    
    let url = '/enrollments/store';
    let method = 'POST';
    
    if (currentEnrollmentId) {
        url = `/enrollments/${currentEnrollmentId}`;
        method = 'PUT';
    }
    
    const saveBtn = form.querySelector('button[type="submit"]');
    saveBtn.disabled = true;
    const originalText = saveBtn.textContent;
    saveBtn.textContent = 'Saving...';
    
    fetch(url, {
        method: method,
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken,
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: JSON.stringify(data)
    })
    .then(res => res.json())
    .then(response => {
        if (response.success) {
            showToast(response.message, 'success', 3000);
            closeModal();
            fetchData();
            resetForm();
        } else {
            showToast(response.message || 'Error saving enrollment', 'error', 4000);
            if (response.errors) {
                for (let key in response.errors) {
                    showToast(`${key}: ${response.errors[key].join(', ')}`, 'error', 4000);
                }
            }
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showToast('An error occurred while saving', 'error', 4000);
    })
    .finally(() => {
        saveBtn.disabled = false;
        saveBtn.textContent = originalText;
    });
}

// Edit enrollment - Load student and their enrolled courses
function editEnrollment(id) {
    showToast('Loading enrollment details...', 'info', 2000);
    
    fetch(`/enrollments/${id}/edit`, {
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json'
        }
    })
    .then(res => res.json())
    .then(response => {
        if (response.success) {
            const data = response.data;
            const enrolledCourseIds = response.enrolled_courses || [];
            currentEnrollmentId = id;
            isEditMode = true;
            
            // Fill form fields
            document.getElementById('student_id').value = data.student_id;
            document.getElementById('program_id').value = data.program_id;
            document.getElementById('session_id').value = data.session_id;
            document.getElementById('section_id').value = data.section_id;
            document.getElementById('semester').value = data.semester;
            document.getElementById('enrollment_date').value = data.enrollment_date;
            
            // Load courses and select the enrolled ones
            const programId = data.program_id;
            const semester = data.semester;
            
            fetch(`/get-offered-courses?program_id=${programId}&semester=${semester}`)
                .then(res => res.json())
                .then(courses => {
                    const dropdown = document.getElementById('coursesDropdown');
                    dropdown.innerHTML = '';
                    
                    if (courses.length === 0) {
                        dropdown.innerHTML = '<option value="">No courses available</option>';
                    } else {
                        courses.forEach(c => {
                            const option = document.createElement('option');
                            option.value = c.id;
                            option.textContent = c.course.course_title;
                            // Select if this course is already enrolled
                            if (enrolledCourseIds.includes(c.id)) {
                                option.selected = true;
                            }
                            dropdown.appendChild(option);
                        });
                    }
                    
                    // Keep multiple select for edit mode to allow adding/removing courses
                    dropdown.multiple = true;
                    const helpText = document.querySelector('#coursesDropdown + small');
                    if (helpText) {
                        helpText.textContent = 'Hold Ctrl/Cmd to select multiple courses (add or remove)';
                    }
                });
            
            // Update modal title and button
            document.getElementById('modalTitle').textContent = 'Edit Enrollment (Add/Remove Courses)';
            const submitBtn = document.querySelector('#enrollmentForm button[type="submit"]');
            submitBtn.textContent = 'Update Enrollment';
            
            openModal();
            showToast('Enrollment loaded. You can add or remove courses.', 'success', 3000);
        } else {
            showToast(response.message || 'Failed to load enrollment', 'error', 4000);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showToast('Failed to load enrollment details', 'error', 4000);
    });
}

// Delete enrollment with confirmation dialog
function deleteEnrollment(id, buttonElement) {
    // Get student name from the table row
    const row = buttonElement.closest('tr');
    const studentName = row ? row.cells[0].innerText : 'this enrollment';
    
    // Show custom confirmation dialog
    showConfirmDialog({
        title: 'Confirm Deletion',
        message: `Are you sure you want to delete enrollment for ${studentName}?`,
        detail: '⚠️ This action cannot be undone and will remove all associated course enrollments from the system.',
        onConfirm: () => {
            performDelete(id);
        },
        onCancel: () => {
            showToast('Deletion cancelled', 'info', 2000);
        }
    });
}

// Function to perform the actual delete
function performDelete(id) {
    // Show loading toast
    showToast('Deleting enrollment...', 'info', 2000);
    
    const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || 
                  document.querySelector('input[name="_token"]')?.value;
    
    fetch(`/enrollments/${id}`, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': token,
            'X-Requested-With': 'XMLHttpRequest',
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        },
        credentials: 'same-origin'
    })
    .then(async response => {
        if (!response.ok) {
            const text = await response.text();
            try {
                const error = JSON.parse(text);
                throw new Error(error.message || 'Server error');
            } catch(e) {
                throw new Error(`HTTP ${response.status}: ${response.statusText}`);
            }
        }
        return response.json();
    })
    .then(response => {
        if (response.success) {
            showToast(response.message || '✅ Enrollment deleted successfully!', 'success', 3000);
            fetchData(); // Refresh the table
        } else {
            showToast(response.message || 'Error deleting enrollment', 'error', 4000);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showToast(error.message || 'An error occurred while deleting', 'error', 4000);
    });
}

// Open modal
function openModal() {
    document.getElementById('modal').style.display = 'flex';
    document.body.style.overflow = 'hidden';
}

// Close modal and reset form
function closeModal() {
    document.getElementById('modal').style.display = 'none';
    document.body.style.overflow = '';
    resetForm();
}

// Reset form to initial state
function resetForm() {
    document.getElementById('enrollmentForm').reset();
    currentEnrollmentId = null;
    isEditMode = false;
    document.getElementById('modalTitle').textContent = 'Add Enrollment';
    
    const submitBtn = document.querySelector('#enrollmentForm button[type="submit"]');
    submitBtn.textContent = 'Save Enrollment';
    
    // Reset dropdown to multiple select for create mode
    const dropdown = document.getElementById('coursesDropdown');
    dropdown.innerHTML = '<option value="">Select Program and Semester First</option>';
    dropdown.multiple = true;
    
    // Reset help text
    const helpText = document.querySelector('#coursesDropdown + small');
    if (helpText) {
        helpText.textContent = 'Hold Ctrl/Cmd to select multiple courses';
    }
}

// Close modal when clicking outside
window.onclick = function(event) {
    const modal = document.getElementById('modal');
    if (event.target === modal) {
        closeModal();
    }
}
</script>

@endsection