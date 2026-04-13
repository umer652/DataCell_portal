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

/* CUSTOM CONFIRM DIALOG */
.confirm-dialog {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0,0,0,0.5);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 11000;
    animation: fadeIn 0.2s ease;
}

.confirm-dialog-content {
    background: white;
    border-radius: 12px;
    width: 400px;
    max-width: 90%;
    overflow: hidden;
    animation: scaleIn 0.2s ease;
    box-shadow: 0 10px 40px rgba(0,0,0,0.2);
}

@keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
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
    white-space: pre-line;
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

/* LOADING STATE */
.loading {
    text-align: center;
    padding: 40px;
    color: #999;
}

.empty-state {
    text-align: center;
    padding: 40px;
    color: #999;
}

.required:after {
    content: " *";
    color: red;
}
</style>
@endsection

@section('content')

<div class="main-container">

    <!-- TOP BAR -->
    <div class="top-bar">
        <h2 class="page-title">Teacher Course Allocation</h2>
        <button class="add-btn" onclick="window.openModal()">+ New Allocation</button>
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
                    <td colspan="9">Loading allocations...</td>
                </tr>
            </tbody>
        </table>
    </div>

</div>

<!-- MODAL -->
<div class="modal" id="allocationModal">
<div class="modal-content">

    <span class="close" onclick="window.closeModal()">&times;</span>

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
                <button type="button" class="cancel-btn" onclick="window.closeModal()">Cancel</button>
                <button type="submit" class="save-btn" id="saveBtn">Save Allocation</button>
            </div>

        </div>
    </form>

</div>
</div>

<script>
// Self-executing function to avoid conflicts
(function() {
    // Store current allocation ID for editing
    let currentAllocationId = null;

    // Get CSRF token from meta tag
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

    // Show toast message
    window.showToast = function(message, type = 'success') {
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
            case 'success': icon = '✓'; title = 'Success!'; break;
            case 'error': icon = '✗'; title = 'Error!'; break;
            case 'warning': icon = '⚠'; title = 'Warning!'; break;
            case 'info': icon = 'ℹ'; title = 'Info'; break;
            default: icon = '✓'; title = 'Success!';
        }
        
        toast.innerHTML = `
            <div class="toast-title">${icon} ${title}</div>
            <div class="toast-message">${escapeHtml(message)}</div>
        `;
        
        container.appendChild(toast);
        
        setTimeout(() => {
            toast.style.animation = 'slideOutRight 0.3s ease';
            setTimeout(() => {
                if (toast.parentNode) toast.remove();
            }, 300);
        }, 3000);
    };

    // Custom confirm dialog
    window.showConfirmDialog = function({ title, message, detail, onConfirm, onCancel }) {
        // Remove existing dialog
        const existingDialog = document.querySelector('.confirm-dialog');
        if (existingDialog) existingDialog.remove();
        
        // Create dialog
        const dialog = document.createElement('div');
        dialog.className = 'confirm-dialog';
        dialog.innerHTML = `
            <div class="confirm-dialog-content">
                <div class="confirm-dialog-header">
                    <div class="confirm-dialog-icon">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M12 8V12M12 16H12.01M3 12C3 7.02944 7.02944 3 12 3C16.9706 3 21 7.02944 21 12C21 16.9706 16.9706 21 12 21C7.02944 21 3 16.9706 3 12Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </div>
                    <h3 class="confirm-dialog-title">${escapeHtml(title)}</h3>
                </div>
                <div class="confirm-dialog-body">
                    <div class="confirm-dialog-message">${escapeHtml(message)}</div>
                    ${detail ? `<div class="confirm-dialog-detail">${escapeHtml(detail)}</div>` : ''}
                </div>
                <div class="confirm-dialog-footer">
                    <button class="confirm-btn confirm-btn-cancel">Cancel</button>
                    <button class="confirm-btn confirm-btn-delete">Delete</button>
                </div>
            </div>
        `;
        
        document.body.appendChild(dialog);
        
        const overlay = dialog.querySelector('.confirm-dialog');
        const cancelBtn = dialog.querySelector('.confirm-btn-cancel');
        const deleteBtn = dialog.querySelector('.confirm-btn-delete');
        
        const closeDialog = () => {
            dialog.style.animation = 'fadeOut 0.2s ease';
            setTimeout(() => {
                if (dialog.parentNode) dialog.remove();
            }, 200);
        };
        
        cancelBtn.addEventListener('click', () => {
            closeDialog();
            if (onCancel) onCancel();
        });
        
        deleteBtn.addEventListener('click', () => {
            closeDialog();
            if (onConfirm) onConfirm();
        });
        
        // Click outside to cancel
        overlay.addEventListener('click', (e) => {
            if (e.target === overlay) {
                closeDialog();
                if (onCancel) onCancel();
            }
        });
    };

    // Escape HTML
    function escapeHtml(text) {
        if (!text) return '-';
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }

    // Load all allocations
    window.loadAllocations = function() {
        const tbody = document.querySelector('#allocationsTable tbody');
        if (!tbody) return;
        
        tbody.innerHTML = '<tr class="loading"><td colspan="9">Loading allocations...</td></tr>';
        
        fetch('/allocations/data', {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
        .then(res => {
            if (!res.ok) throw new Error('Network response was not ok');
            return res.json();
        })
        .then(response => {
            if (!response.data || response.data.length === 0) {
                tbody.innerHTML = '<tr class="empty-state"><td colspan="9">No allocations found. Click "New Allocation" to add.</td></tr>';
                return;
            }
            
            tbody.innerHTML = '';
            
            response.data.forEach(allocation => {
                tbody.innerHTML += `
                    <tr>
                        <td>${allocation.serial}</td>
                        <td>${escapeHtml(allocation.program)}</td>
                        <td>${escapeHtml(allocation.scheme)}</td>
                        <td>${escapeHtml(allocation.course)}</td>
                        <td>${escapeHtml(allocation.teacher)}</td>
                        <td>${escapeHtml(allocation.session)}</td>
                        <td>${escapeHtml(allocation.section)}</td>
                        <td>Semester ${allocation.semester}</td>
                        <td class="action-buttons">
                            <button onclick="window.editAllocation(${allocation.id})" class="edit-btn">Edit</button>
                            <button onclick="window.deleteAllocation(${allocation.id}, '${escapeHtml(allocation.course)}', '${escapeHtml(allocation.teacher)}')" class="delete-btn">Delete</button>
                        </td>
                    </tr>
                `;
            });
        })
        .catch(error => {
            console.error('Error:', error);
            tbody.innerHTML = '<tr class="empty-state"><td colspan="9">Error loading allocations. Please refresh the page.</td></tr>';
            window.showToast('Failed to load allocations: ' + error.message, 'error');
        });
    };

    // Handle program change - load courses and schemes
    window.handleProgramChange = function() {
        let programId = document.getElementById('program_id').value;
        let courseSelect = document.getElementById('course_id');
        let schemeSelect = document.getElementById('scheme_id');
        
        if (!programId) {
            courseSelect.innerHTML = '<option value="">Select Program First</option>';
            schemeSelect.innerHTML = '<option value="">Select Program First</option>';
            courseSelect.disabled = true;
            schemeSelect.disabled = true;
            return;
        }
        
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
                } else {
                    data.forEach(c => {
                        courseSelect.innerHTML += `<option value="${c.id}">${escapeHtml(c.course_title)}</option>`;
                    });
                    courseSelect.disabled = false;
                }
            })
            .catch(error => {
                console.error('Error:', error);
                courseSelect.innerHTML = '<option value="">Error loading courses</option>';
                courseSelect.disabled = true;
                window.showToast('Failed to load courses', 'error');
            });
        
        // Load active scheme
        fetch(`/get-active-scheme/${programId}`)
            .then(res => res.json())
            .then(data => {
                if (data && data.id) {
                    schemeSelect.innerHTML = `<option value="${data.id}" selected>${escapeHtml(data.title)} (Active)</option>`;
                    schemeSelect.disabled = false;
                } else {
                    schemeSelect.innerHTML = '<option value="">No active scheme found</option>';
                    schemeSelect.disabled = true;
                }
            })
            .catch(error => {
                console.error('Error:', error);
                schemeSelect.innerHTML = '<option value="">Error loading scheme</option>';
                schemeSelect.disabled = true;
                window.showToast('Failed to load scheme', 'error');
            });
    };

    // Handle form submission
    window.handleFormSubmit = function(e) {
        e.preventDefault();
        
        const form = e.target;
        if (!form.checkValidity()) {
            form.reportValidity();
            window.showToast('Please fill all required fields', 'warning');
            return;
        }
        
        const formData = new FormData(form);
        const data = Object.fromEntries(formData);
        
        const saveBtn = document.getElementById('saveBtn');
        saveBtn.disabled = true;
        saveBtn.textContent = 'Saving...';
        
        let url = '/allocations/store';
        let method = 'POST';
        
        if (currentAllocationId) {
            url = `/allocations/${currentAllocationId}`;
            method = 'PUT';
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
                window.showToast(response.message, 'success');
                window.closeModal();
                window.loadAllocations();
                window.resetForm();
            } else {
                window.showToast(response.message || 'Error saving allocation', 'error');
                if (response.errors) {
                    for (let key in response.errors) {
                        window.showToast(`${key}: ${response.errors[key].join(', ')}`, 'error');
                    }
                }
            }
        })
        .catch(error => {
            console.error('Error:', error);
            window.showToast('An error occurred while saving', 'error');
        })
        .finally(() => {
            saveBtn.disabled = false;
            saveBtn.textContent = currentAllocationId ? 'Update Allocation' : 'Save Allocation';
        });
    };

    // Edit allocation
    window.editAllocation = async function(id) {
        try {
            window.showToast('Loading allocation details...', 'info');
            
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
                
                document.getElementById('program_id').value = data.program_id;
                
                // Trigger program change to load courses
                await window.handleProgramChange();
                
                // Small delay to ensure courses are loaded
                setTimeout(() => {
                    document.getElementById('course_id').value = data.course_id;
                    document.getElementById('scheme_id').value = data.scheme_id;
                    document.getElementById('teacher_id').value = data.teacher_id;
                    document.getElementById('session_id').value = data.session_id;
                    document.getElementById('section_id').value = data.section_id;
                    document.getElementById('semester').value = data.semester;
                }, 500);
                
                document.getElementById('modalTitle').textContent = 'Edit Teacher Allocation';
                document.getElementById('saveBtn').textContent = 'Update Allocation';
                
                window.openModal();
                window.showToast('Allocation loaded successfully', 'success');
            } else {
                window.showToast(result.message || 'Failed to load allocation', 'error');
            }
        } catch (error) {
            console.error('Error:', error);
            window.showToast('Failed to load allocation details', 'error');
        }
    };

    // Delete allocation with custom confirm dialog
    window.deleteAllocation = function(id, courseName, teacherName) {
        window.showConfirmDialog({
            title: 'Confirm Deletion',
            message: `Are you sure you want to delete this allocation?`,
            detail: `Course: ${courseName}\nTeacher: ${teacherName}\n\n⚠️ This action cannot be undone!`,
            onConfirm: () => {
                // Proceed with deletion
                window.showToast('Deleting allocation...', 'info');
                
                // Get CSRF token from multiple possible sources
                let token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
                
                if (!token) {
                    const tokenInput = document.querySelector('input[name="_token"]');
                    if (tokenInput) {
                        token = tokenInput.value;
                    }
                }
                
                if (!token) {
                    window.showToast('CSRF token not found. Please refresh the page.', 'error');
                    return;
                }
                
                // Create form data for POST request with _method DELETE
                const formData = new FormData();
                formData.append('_method', 'DELETE');
                formData.append('_token', token);
                
                fetch(`/allocations/${id}`, {
                    method: 'POST',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    },
                    body: formData
                })
                .then(async response => {
                    if (response.status === 419) {
                        throw new Error('Session expired. Please refresh the page.');
                    }
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
                        window.showToast(response.message, 'success');
                        window.loadAllocations();
                    } else {
                        window.showToast(response.message || 'Error deleting allocation', 'error');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    window.showToast(error.message || 'An error occurred while deleting', 'error');
                });
            },
            onCancel: () => {
                window.showToast('Deletion cancelled', 'info');
            }
        });
    };

    // Open modal
    window.openModal = function() {
        document.getElementById('allocationModal').style.display = 'flex';
        document.body.style.overflow = 'hidden';
    };

    // Close modal
    window.closeModal = function() {
        document.getElementById('allocationModal').style.display = 'none';
        document.body.style.overflow = '';
        window.resetForm();
    };

    // Reset form
    window.resetForm = function() {
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
    };

    // Initialize everything
    function init() {
        console.log('Initializing Allocation page...');
        
        // Set up event listeners
        const form = document.getElementById('allocationForm');
        if (form) {
            form.removeEventListener('submit', window.handleFormSubmit);
            form.addEventListener('submit', window.handleFormSubmit);
        }
        
        const programSelect = document.getElementById('program_id');
        if (programSelect) {
            programSelect.removeEventListener('change', window.handleProgramChange);
            programSelect.addEventListener('change', window.handleProgramChange);
        }
        
        // Load allocations immediately
        window.loadAllocations();
    }
    
    // Run init when DOM is ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }
    
    // Handle modal outside click
    window.onclick = function(event) {
        const modal = document.getElementById('allocationModal');
        if (event.target === modal) {
            window.closeModal();
        }
    };
})();
</script>

@endsection