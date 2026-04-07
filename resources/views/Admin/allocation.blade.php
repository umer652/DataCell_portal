@extends('layouts.app')

@section('title', 'Teacher Allocation')

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

/* TABLE */
.table-container {
    flex: 1;
    overflow: auto;
    border-radius: 10px;
    border: 1px solid #eee;
}

table {
    width: 100%;
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
    width: 65%;
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
    grid-template-columns: 1fr 1fr;
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

.alert-info {
    background: #d1ecf1;
    color: #0c5460;
    border: 1px solid #bee5eb;
}

.alert-warning {
    background: #fff3cd;
    color: #856404;
    border: 1px solid #ffeeba;
}

/* LOADING STATE */
.loading {
    text-align: center;
    padding: 40px;
    color: #999;
}

/* EMPTY STATE */
.empty-state {
    text-align: center;
    padding: 40px;
    color: #999;
}

/* REQUIRED FIELD INDICATOR */
.required:after {
    content: " *";
    color: red;
}

/* TOAST CONTAINER */
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
        <h2 class="page-title">Teacher Course Allocation</h2>
        <button class="add-btn" onclick="openModal()">+ New Allocation</button>
    </div>

    <!-- TABLE -->
    <div class="table-container">
        <table id="allocationsTable">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Program</th>
                    <th>Scheme</th>
                    <th>Course</th>
                    <th>Teacher</th>
                    <th>Session</th>
                    <th>Section</th>
                    <th>Semester</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <tr class="loading">
                    <td colspan="9">Loading allocations...<\/td>
                </tr>
            </tbody>
        月末
    </div>

</div>

<!-- MODAL -->
<div class="modal" id="allocationModal">
<div class="modal-content">

    <span class="close" onclick="closeModal()">&times;</span>

    <h3 id="modalTitle" style="margin-bottom:15px;">New Teacher Allocation</h3>

    <form id="allocationForm">
        @csrf
        
        <div class="form-grid">

            <div class="form-group">
                <label class="required">Program</label>
                <select name="program_id" id="program_id" required>
                    <option value="">Select Program</option>
                    @foreach($programs as $program)
                        <option value="{{ $program->id }}">{{ $program->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label class="required">Course</label>
                <select name="course_id" id="course_id" required disabled>
                    <option value="">Select Program First</option>
                </select>
            </div>

            <div class="form-group">
                <label class="required">Scheme</label>
                <select name="scheme_id" id="scheme_id" required disabled>
                    <option value="">Select Program First</option>
                </select>
            </div>

            <div class="form-group">
                <label class="required">Session</label>
                <select name="session_id" id="session_id" required>
                    <option value="">Select Session</option>
                    @foreach($sessions as $session)
                        <option value="{{ $session->id }}"
                            {{ $activeSession && $activeSession->id == $session->id ? 'selected' : '' }}>
                            {{ $session->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label class="required">Teacher</label>
                <select name="teacher_id" id="teacher_id" required>
                    <option value="">Select Teacher</option>
                    @foreach($teachers as $teacher)
                        <option value="{{ $teacher->id }}">{{ $teacher->user->name ?? $teacher->name ?? 'N/A' }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label class="required">Semester</label>
                <select name="semester" id="semester" required>
                    <option value="">Select Semester</option>
                    @for($i=1;$i<=8;$i++)
                        <option value="{{ $i }}">Semester {{ $i }}</option>
                    @endfor
                </select>
            </div>

            <div class="form-group">
                <label class="required">Section</label>
                <select name="section_id" id="section_id" required>
                    <option value="">Select Section</option>
                    @foreach($sections as $section)
                        <option value="{{ $section->id }}">{{ $section->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-actions">
                <button type="button" class="cancel-btn" onclick="closeModal()">Cancel</button>
                <button type="submit" class="save-btn" id="saveBtn">Save Allocation</button>
            </div>

        </div>
    </form>

</div>
</div>

<script>
// Store current allocation ID for editing
let currentAllocationId = null;

// Get CSRF token from meta tag
const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

// Load allocations on page load
document.addEventListener('DOMContentLoaded', function() {
    loadAllocations();
    
    // Setup form submission
    document.getElementById('allocationForm').addEventListener('submit', handleFormSubmit);
    
    // Setup program change handler
    document.getElementById('program_id').addEventListener('change', handleProgramChange);
});

// Load all allocations
function loadAllocations() {
    const tbody = document.querySelector('#allocationsTable tbody');
    tbody.innerHTML = '<tr class="loading"><td colspan="9">Loading allocations...<\/td><\/tr>';
    
    fetch('/allocations/data', {
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
        .then(res => {
            if (!res.ok) {
                throw new Error('Network response was not ok');
            }
            return res.json();
        })
        .then(response => {
            if (!response.data || response.data.length === 0) {
                tbody.innerHTML = '<tr class="empty-state"><td colspan="9">No allocations found. Click "New Allocation" to add.<\/td><\/tr>';
                showToast('No allocations found', 'info');
                return;
            }
            
            tbody.innerHTML = '';
            
            response.data.forEach(allocation => {
                tbody.innerHTML += `
                    <tr>
                        <td>${allocation.serial}<\/td>
                        <td>${escapeHtml(allocation.program)}<\/td>
                        <td>${escapeHtml(allocation.scheme)}<\/td>
                        <td>${escapeHtml(allocation.course)}<\/td>
                        <td>${escapeHtml(allocation.teacher)}<\/td>
                        <td>${escapeHtml(allocation.session)}<\/td>
                        <td>${escapeHtml(allocation.section)}<\/td>
                        <td>Semester ${allocation.semester}<\/td>
                        <td class="action-buttons">
                            <button onclick="editAllocation(${allocation.id})" class="edit-btn">Edit</button>
                            <button onclick="deleteAllocation(${allocation.id})" class="delete-btn">Delete</button>
                        <\/td>
                    <\/tr>
                `;
            });
            
            showToast(`${response.data.length} allocation(s) loaded successfully`, 'success');
        })
        .catch(error => {
            console.error('Error:', error);
            tbody.innerHTML = '<tr class="empty-state"><td colspan="9">Error loading allocations. Please refresh the page.<\/td><\/tr>';
            showToast('Failed to load allocations: ' + error.message, 'error');
        });
}

// Handle program change - load courses and schemes
function handleProgramChange() {
    let programId = this.value;
    let courseSelect = document.getElementById('course_id');
    let schemeSelect = document.getElementById('scheme_id');
    
    if (!programId) {
        courseSelect.innerHTML = '<option value="">Select Program First</option>';
        schemeSelect.innerHTML = '<option value="">Select Program First</option>';
        courseSelect.disabled = true;
        schemeSelect.disabled = true;
        return;
    }
    
    // Disable and show loading
    courseSelect.disabled = true;
    schemeSelect.disabled = true;
    courseSelect.innerHTML = '<option>Loading courses...</option>';
    schemeSelect.innerHTML = '<option>Loading scheme...</option>';
    
    // Load courses
    fetch(`/get-courses/${programId}`)
        .then(res => res.json())
        .then(data => {
            courseSelect.innerHTML = '<option value="">Select Course</option>';
            if (data.length === 0) {
                courseSelect.innerHTML = '<option value="">No courses available</option>';
                courseSelect.disabled = true;
                showToast('No courses found for this program', 'warning');
            } else {
                data.forEach(c => {
                    courseSelect.innerHTML += `<option value="${c.id}">${escapeHtml(c.course_title)}</option>`;
                });
                courseSelect.disabled = false;
                showToast(`${data.length} course(s) loaded`, 'success');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            courseSelect.innerHTML = '<option value="">Error loading courses</option>';
            courseSelect.disabled = true;
            showToast('Failed to load courses', 'error');
        });
    
    // Load active scheme
    fetch(`/get-active-scheme/${programId}`)
        .then(res => res.json())
        .then(data => {
            if (data && data.id) {
                schemeSelect.innerHTML = `<option value="${data.id}" selected>${escapeHtml(data.title)} (Active)</option>`;
                schemeSelect.disabled = false;
                showToast('Active scheme loaded', 'success');
            } else {
                schemeSelect.innerHTML = '<option value="">No active scheme found</option>';
                schemeSelect.disabled = true;
                showToast('No active scheme found for this program', 'warning');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            schemeSelect.innerHTML = '<option value="">Error loading scheme</option>';
            schemeSelect.disabled = true;
            showToast('Failed to load scheme', 'error');
        });
}

// Handle form submission (Create/Update)
function handleFormSubmit(e) {
    e.preventDefault();
    
    // Validate form
    const form = e.target;
    if (!form.checkValidity()) {
        form.reportValidity();
        showToast('Please fill all required fields', 'warning');
        return;
    }
    
    const formData = new FormData(form);
    const data = Object.fromEntries(formData);
    
    // Disable save button to prevent double submission
    const saveBtn = document.getElementById('saveBtn');
    saveBtn.disabled = true;
    saveBtn.textContent = 'Saving...';
    
    let url = '/allocations/store';
    let method = 'POST';
    let action = 'create';
    
    if (currentAllocationId) {
        url = `/allocations/${currentAllocationId}`;
        method = 'PUT';
        action = 'update';
    }
    
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
            showToast(response.message, 'success');
            closeModal();
            loadAllocations();
            resetForm();
        } else {
            showToast(response.message || `Error ${action}ing allocation`, 'error');
            if (response.errors) {
                console.error('Validation errors:', response.errors);
                for (let key in response.errors) {
                    showToast(`${key}: ${response.errors[key].join(', ')}`, 'error');
                }
            }
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showToast(`An error occurred while ${action}ing allocation: ${error.message}`, 'error');
    })
    .finally(() => {
        saveBtn.disabled = false;
        saveBtn.textContent = currentAllocationId ? 'Update Allocation' : 'Save Allocation';
    });
}

// Edit allocation
async function editAllocation(id) {
    try {
        showToast('Loading allocation details...', 'info');
        
        const response = await fetch(`/allocations/${id}/edit`, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        });
        
        const result = await response.json();
        
        if (result.success) {
            const data = result.data;
            currentAllocationId = id;
            
            // Set program
            document.getElementById('program_id').value = data.program_id;
            
            // Load courses for this program
            const coursesResponse = await fetch(`/get-courses/${data.program_id}`);
            const courses = await coursesResponse.json();
            
            const courseSelect = document.getElementById('course_id');
            courseSelect.innerHTML = '<option value="">Select Course</option>';
            courses.forEach(c => {
                const selected = c.id == data.course_id ? 'selected' : '';
                courseSelect.innerHTML += `<option value="${c.id}" ${selected}>${escapeHtml(c.course_title)}</option>`;
            });
            courseSelect.disabled = false;
            
            // Load scheme
            const schemeResponse = await fetch(`/get-active-scheme/${data.program_id}`);
            const scheme = await schemeResponse.json();
            
            const schemeSelect = document.getElementById('scheme_id');
            if (scheme && scheme.id) {
                const selected = scheme.id == data.scheme_id ? 'selected' : '';
                schemeSelect.innerHTML = `<option value="${scheme.id}" ${selected}>${escapeHtml(scheme.title)} (Active)</option>`;
                schemeSelect.disabled = false;
            } else {
                schemeSelect.innerHTML = '<option value="">No active scheme found</option>';
                schemeSelect.disabled = true;
            }
            
            // Fill other fields
            document.getElementById('teacher_id').value = data.teacher_id;
            document.getElementById('session_id').value = data.session_id;
            document.getElementById('section_id').value = data.section_id;
            document.getElementById('semester').value = data.semester;
            
            // Update modal
            document.getElementById('modalTitle').textContent = 'Edit Teacher Allocation';
            document.getElementById('saveBtn').textContent = 'Update Allocation';
            
            openModal();
            showToast('Allocation loaded successfully', 'success');
        } else {
            showToast(result.message || 'Failed to load allocation details', 'error');
        }
    } catch (error) {
        console.error('Error:', error);
        showToast('Failed to load allocation details: ' + error.message, 'error');
    }
}

// Delete allocation
function deleteAllocation(id) {
    if (confirm('⚠️ Are you sure you want to delete this allocation?\n\nThis action cannot be undone!')) {
        showToast('Deleting allocation...', 'info');
        
        // Get CSRF token
        let token = csrfToken;
        if (!token) {
            token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
        }
        if (!token) {
            token = document.querySelector('input[name="_token"]')?.value;
        }
        
        // Create form data with _method and _token
        const formData = new FormData();
        formData.append('_method', 'DELETE');
        formData.append('_token', token);
        
        fetch(`/allocations/${id}`, {
            method: 'POST',
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: formData
        })
        .then(res => res.json())
        .then(response => {
            if (response.success) {
                showToast(response.message, 'success');
                loadAllocations(); // Refresh the table
            } else {
                showToast(response.message || 'Error deleting allocation', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showToast('An error occurred while deleting: ' + error.message, 'error');
        });
    } else {
        showToast('Deletion cancelled', 'info');
    }
}

// Open modal
function openModal() {
    document.getElementById('allocationModal').style.display = 'flex';
    document.body.style.overflow = 'hidden';
}

// Close modal and reset form
function closeModal() {
    document.getElementById('allocationModal').style.display = 'none';
    document.body.style.overflow = '';
    resetForm();
}

// Reset form to initial state
function resetForm() {
    document.getElementById('allocationForm').reset();
    currentAllocationId = null;
    
    document.getElementById('modalTitle').textContent = 'New Teacher Allocation';
    document.getElementById('saveBtn').textContent = 'Save Allocation';
    
    const courseSelect = document.getElementById('course_id');
    const schemeSelect = document.getElementById('scheme_id');
    courseSelect.innerHTML = '<option value="">Select Program First</option>';
    schemeSelect.innerHTML = '<option value="">Select Program First</option>';
    courseSelect.disabled = true;
    schemeSelect.disabled = true;
}

// Enhanced Toast notification system
function showToast(message, type = 'success') {
    // Create toast container if it doesn't exist
    let container = document.querySelector('.toast-container');
    if (!container) {
        container = document.createElement('div');
        container.className = 'toast-container';
        document.body.appendChild(container);
    }
    
    // Create toast element
    const toast = document.createElement('div');
    toast.className = `toast toast-${type}`;
    
    // Set icon based on type
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
    
    // Auto remove after 3 seconds
    setTimeout(() => {
        toast.style.animation = 'slideOutRight 0.3s ease';
        setTimeout(() => {
            if (toast.parentNode) {
                toast.remove();
            }
        }, 300);
    }, 3000);
    
    // Add slide out animation
    const style = document.createElement('style');
    style.textContent = `
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
    `;
    document.head.appendChild(style);
}

// Escape HTML
function escapeHtml(text) {
    if (!text) return '-';
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

// Close modal when clicking outside
window.onclick = function(event) {
    const modal = document.getElementById('allocationModal');
    if (event.target === modal) {
        closeModal();
    }
}
</script>

@endsection