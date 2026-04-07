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

/* ALERT MESSAGES */
.alert {
    position: fixed;
    top: 20px;
    right: 20px;
    padding: 12px 20px;
    border-radius: 8px;
    z-index: 10000;
    animation: slideIn 0.3s ease;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
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

.alert-success {
    background: #d4edda;
    color: #155724;
    border: 1px solid #c3e6cb;
}

.alert-error {
    background: #f8d7da;
    color: #721c24;
    border: 1px solid #f5c6cb;
}

</style>
@endsection

@section('content')

<div class="main-container">

    <!-- TOP -->
    <div class="top-bar">
        <h2>Enrollment</h2>
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
                <tr>
                    <td>{{ $e->student->name }}</td>
                    <td>{{ $e->offeredCourse->course->course_title ?? '-' }}</td>
                    <td>{{ $e->program->name }}</td>
                    <td>{{ $e->session->name }}</td>
                    <td>{{ $e->semester }}</td>
                    <td>{{ $e->section->name }}</td>
                    <td>{{ $e->enrollment_date }}</td>
                    <td class="action-buttons">
                        <button onclick="editEnrollment({{ $e->id }})" class="edit-btn">Edit</button>
                        <button onclick="deleteEnrollment({{ $e->id }})" class="delete-btn">Delete</button>
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
        showMessage('Please select at least one course', 'error');
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
            showMessage(response.message, 'success');
            closeModal();
            fetchData();
            resetForm();
        } else {
            showMessage(response.message || 'Error saving enrollment', 'error');
            if (response.errors) {
                for (let key in response.errors) {
                    showMessage(`${key}: ${response.errors[key].join(', ')}`, 'error');
                }
            }
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showMessage('An error occurred while saving', 'error');
    })
    .finally(() => {
        saveBtn.disabled = false;
        saveBtn.textContent = isEditMode ? 'Update Enrollment' : 'Save Enrollment';
    });
}

// Edit enrollment - Load student and their enrolled courses
function editEnrollment(id) {
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
            showMessage('Enrollment loaded successfully. You can add or remove courses.', 'success');
        } else {
            showMessage(response.message || 'Failed to load enrollment', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showMessage('Failed to load enrollment details', 'error');
    });
}

// Delete enrollment
function deleteEnrollment(id) {
    if (confirm('⚠️ Are you sure you want to delete this enrollment?\n\nThis action cannot be undone!')) {
        fetch(`/enrollments/${id}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'X-Requested-With': 'XMLHttpRequest',
                'Content-Type': 'application/json'
            }
        })
        .then(res => res.json())
        .then(response => {
            if (response.success) {
                showMessage(response.message, 'success');
                fetchData();
            } else {
                showMessage(response.message || 'Error deleting enrollment', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showMessage('An error occurred while deleting', 'error');
        });
    }
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

// Show message
function showMessage(message, type = 'success') {
    const existingAlerts = document.querySelectorAll('.alert');
    existingAlerts.forEach(alert => alert.remove());
    
    const messageDiv = document.createElement('div');
    messageDiv.className = `alert alert-${type}`;
    messageDiv.innerHTML = message;
    document.body.appendChild(messageDiv);
    
    setTimeout(() => {
        if (messageDiv.parentNode) {
            messageDiv.remove();
        }
    }, 3000);
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