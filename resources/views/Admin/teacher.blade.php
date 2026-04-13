@extends('layouts.app')

@section('title', 'Teachers')

@section('styles')
<style>

/* ADD BUTTON */
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
    overflow: hidden;

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
}

.top-bar h2 {
    font-size: 20px;
    font-weight: 600;
    color: #0f1b5c;
    margin: 0;
}

/* TABLE CONTAINER */
.table-container {
    flex: 1;
    overflow: auto;
    border-radius: 10px;
}

/* TABLE */
table {
    width: 100%;
    border-collapse: collapse;
}

/* HEADER */
thead th {
    position: sticky;
    top: 0;
    z-index: 100;
    background: #0f1b5c;
    color: #fff;
    text-align: left;
    vertical-align: middle;
    padding: 14px 16px;
    font-weight: 600;
}

/* BODY CELLS */
tbody td {
    text-align: left;
    vertical-align: middle;
    border-bottom: 1px solid #e0e0e0;
    padding: 12px 16px;
}

tbody tr:hover {
    background-color: #f5f7fb;
}

/* LAST COLUMN (ACTIONS) CENTER */
thead th:last-child,
tbody td:last-child {
    text-align: center;
}

/* ACTION BUTTONS */
.action-buttons {
    display: flex;
    gap: 10px;
    justify-content: center;
}

.edit-btn, .delete-btn {
    padding: 6px 12px;
    border-radius: 6px;
    border: none;
    cursor: pointer;
    font-size: 13px;
    font-weight: 500;
    transition: all 0.2s ease;
    display: inline-flex;
    align-items: center;
    gap: 5px;
}

.edit-btn {
    background: #0f1b5c;
    color: #fff;
}

.edit-btn:hover {
    background: #1a2a7a;
    transform: translateY(-1px);
}

.delete-btn {
    background: #dc3545;
    color: #fff;
}

.delete-btn:hover {
    background: #c82333;
    transform: translateY(-1px);
}

/* SCROLLBAR */
.table-container::-webkit-scrollbar {
    width: 6px;
    height: 8px;
}
.table-container::-webkit-scrollbar-thumb {
    background: #0f1b5c;
    border-radius: 10px;
}
.table-container::-webkit-scrollbar-track {
    background: #f1f1f1;
}

/* ================= MODAL STYLES ================= */
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
    width: 55%;
    max-width: 700px;
    padding: 30px;
    border-radius: 16px;
    position: relative;
    max-height: 90vh;
    overflow-y: auto;
    box-shadow: 0 10px 30px rgba(0,0,0,0.25);
}

/* CLOSE BUTTON */
.close {
    position: absolute;
    right: 20px;
    top: 15px;
    cursor: pointer;
    font-size: 26px;
    transition: color 0.2s;
}

.close:hover {
    color: #d33;
}

/* FORM TITLE */
.form-title {
    font-size: 22px;
    font-weight: 700;
    margin-bottom: 20px;
    color: #0f1b5c;
    border-bottom: 2px solid #eee;
    padding-bottom: 10px;
}

/* FORM GRID */
.form-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 18px;
    margin-top: 10px;
}

/* FULL WIDTH */
.full-width {
    grid-column: 1 / -1;
}

/* LABELS */
label {
    display: block;
    margin-bottom: 6px;
    font-weight: 600;
    color: #333;
    font-size: 13px;
}

/* INPUTS */
input, select {
    width: 100%;
    padding: 10px 12px;
    border-radius: 6px;
    border: 1px solid #d0d0d0;
    outline: none;
    transition: 0.2s;
    font-size: 14px;
}

input:focus, select:focus {
    border-color: #0f1b5c;
    box-shadow: 0 0 0 2px rgba(15, 27, 92, 0.1);
}

/* HELP TEXT */
.help-text {
    color: #666;
    font-size: 11px;
    margin-top: 4px;
    display: block;
}

/* SUBMIT BUTTON */
.submit-btn {
    background: #0f1b5c;
    color: #fff;
    padding: 12px 30px;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    font-size: 14px;
    font-weight: 600;
    transition: all 0.3s ease;
}

.submit-btn:hover {
    background: #1a2a7a;
    transform: translateY(-2px);
}

.cancel-btn {
    background: #6c757d;
    color: #fff;
    padding: 12px 30px;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    font-size: 14px;
    font-weight: 600;
    transition: all 0.3s ease;
}

.cancel-btn:hover {
    background: #5a6268;
    transform: translateY(-2px);
}

.button-group {
    display: flex;
    justify-content: flex-end;
    gap: 15px;
    margin-top: 20px;
}

/* ERROR BOX */
#formError {
    display: none;
    background: #f8d7da;
    color: #721c24;
    padding: 12px;
    margin-bottom: 20px;
    border-radius: 8px;
    font-size: 14px;
    border-left: 4px solid #dc3545;
}

/* EMPTY STATE */
.empty-state {
    text-align: center;
    padding: 60px;
    color: #999;
    font-size: 16px;
}

/* RESPONSIVE */
@media (max-width: 768px) {
    .main-container {
        left: 0 !important;
        width: 100% !important;
        top: 60px;
        border-radius: 0;
    }
    
    .modal-content {
        width: 95%;
        margin: 10% auto;
    }
    
    .form-grid {
        grid-template-columns: 1fr;
    }
    
    thead th,
    tbody td {
        padding: 8px 12px;
        font-size: 14px;
    }
}

</style>
@endsection

@section('content')

<meta name="csrf-token" content="{{ csrf_token() }}">

<div class="main-container">

    <!-- TOP BAR -->
    <div class="top-bar">
        <h2>Teachers Management</h2>
        <button class="add-btn" id="addTeacherBtn">+ Add New Teacher</button>
    </div>

    <!-- TABLE -->
    <div class="table-container">
        @if(count($teachers) > 0)
        <table>
            <thead>
                <tr>
                    <th style="width: 5%">#</th>
                    <th style="width: 20%">Name</th>
                    <th style="width: 25%">Email</th>
                    <th style="width: 15%">Designation</th>
                    <th style="width: 20%">Department</th>
                    <th style="width: 15%">Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($teachers as $index => $t)
                <tr id="teacher-row-{{ $t->id }}">
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $t->user->name }}</td>
                    <td>{{ $t->user->email }}</td>
                    <td>{{ $t->user->designation ?? 'N/A' }}</td>
                    <td>{{ $t->user->department ?? 'N/A' }}</td>
                    <td>
                        <div class="action-buttons">
                            <button class="edit-btn" data-id="{{ $t->id }}">
                                <i class="fa-solid fa-pen-to-square"></i> Edit
                            </button>
                            <button class="delete-btn" data-id="{{ $t->id }}">
                                <i class="fa-solid fa-trash"></i> Delete
                            </button>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @else
        <div class="empty-state">
            <i class="fa-solid fa-chalkboard-user" style="font-size: 48px; margin-bottom: 15px; opacity: 0.5;"></i>
            <p>No teachers found. Click "Add New Teacher" to get started.</p>
        </div>
        @endif
    </div>

</div>

<!-- MODAL -->
<div class="modal" id="teacherModal">
    <div class="modal-content">
        <span class="close" id="closeModalBtn">&times;</span>
        <div class="form-title" id="modalTitle">Add New Teacher</div>
        
        <div id="formError"></div>
        <input type="hidden" id="teacher_id">
        
        <div class="form-grid">
            <div>
                <label>Full Name *</label>
                <input type="text" id="name" placeholder="Enter teacher's full name">
            </div>
            <div>
                <label>Email Address *</label>
                <input type="email" id="email" placeholder="teacher@example.com">
            </div>
            <div>
                <label>Password <span id="passwordRequired">*</span></label>
                <input type="password" id="password" placeholder="Enter password">
                <small class="help-text" id="passwordHelp" style="display: none;">Leave blank to keep unchanged when editing</small>
                <small class="help-text" id="passwordRequiredText">Required for new teacher</small>
            </div>
            <div>
                <label>Designation</label>
                <input type="text" id="designation" placeholder="e.g., Senior Teacher">
            </div>
            <div class="full-width">
                <label>Department</label>
                <input type="text" id="department" placeholder="e.g., Computer Science">
            </div>
        </div>
        
        <div class="button-group">
            <button type="button" class="cancel-btn" id="cancelBtn">Cancel</button>
            <button type="button" class="submit-btn" id="saveBtn">Save Teacher</button>
            <button type="button" class="submit-btn" id="updateBtn" style="display:none;">Update Teacher</button>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
// Teacher Management Module
function initTeacherModule() {
    console.log('Initializing Teacher Module...');
    
    // CSRF Token setup
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
    if (!csrfToken) {
        console.error('CSRF token not found');
        return;
    }
    
    // Get elements
    const modal = document.getElementById('teacherModal');
    const addBtn = document.getElementById('addTeacherBtn');
    const closeModalBtn = document.getElementById('closeModalBtn');
    const cancelBtn = document.getElementById('cancelBtn');
    const saveBtn = document.getElementById('saveBtn');
    const updateBtn = document.getElementById('updateBtn');
    const modalTitle = document.getElementById('modalTitle');
    const teacherId = document.getElementById('teacher_id');
    const nameInput = document.getElementById('name');
    const emailInput = document.getElementById('email');
    const passwordInput = document.getElementById('password');
    const designationInput = document.getElementById('designation');
    const departmentInput = document.getElementById('department');
    const passwordRequired = document.getElementById('passwordRequired');
    const passwordHelp = document.getElementById('passwordHelp');
    const passwordRequiredText = document.getElementById('passwordRequiredText');
    
    // Check if essential elements exist
    if (!modal || !addBtn) {
        console.error('Required elements not found');
        return;
    }
    
    function openModal() {
        resetForm();
        modalTitle.textContent = "Add New Teacher";
        if (passwordRequired) passwordRequired.style.display = 'inline';
        if (passwordHelp) passwordHelp.style.display = 'none';
        if (passwordRequiredText) passwordRequiredText.style.display = 'inline';
        if (passwordInput) passwordInput.required = true;
        modal.style.display = 'flex';
    }
    
    function closeModal() {
        modal.style.display = 'none';
        resetForm();
    }
    
    function resetForm() {
        if (teacherId) teacherId.value = '';
        if (nameInput) nameInput.value = '';
        if (emailInput) emailInput.value = '';
        if (passwordInput) passwordInput.value = '';
        if (designationInput) designationInput.value = '';
        if (departmentInput) departmentInput.value = '';
        if (saveBtn) saveBtn.style.display = 'inline-block';
        if (updateBtn) updateBtn.style.display = 'none';
        const formError = document.getElementById('formError');
        if (formError) {
            formError.style.display = 'none';
            formError.innerHTML = '';
        }
    }
    
    function showError(msg) {
        const errorDiv = document.getElementById('formError');
        if (errorDiv) {
            errorDiv.innerHTML = msg;
            errorDiv.style.display = 'block';
            setTimeout(() => {
                errorDiv.style.display = 'none';
            }, 5000);
        } else {
            Swal.fire('Error', msg, 'error');
        }
    }
    
    function validateForm(isEdit = false) {
        const name = nameInput ? nameInput.value.trim() : '';
        const email = emailInput ? emailInput.value.trim() : '';
        const password = passwordInput ? passwordInput.value : '';
        
        if (!name) {
            showError('Please enter teacher name');
            if (nameInput) nameInput.focus();
            return false;
        }
        if (!email) {
            showError('Please enter email address');
            if (emailInput) emailInput.focus();
            return false;
        }
        if (!isEdit && !password) {
            showError('Please enter password for new teacher');
            if (passwordInput) passwordInput.focus();
            return false;
        }
        if (email && !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
            showError('Please enter a valid email address');
            if (emailInput) emailInput.focus();
            return false;
        }
        if (password && password.length < 6) {
            showError('Password must be at least 6 characters');
            if (passwordInput) passwordInput.focus();
            return false;
        }
        return true;
    }
    
    async function saveTeacher() {
        if (!validateForm(false)) return;
        
        if (saveBtn) {
            saveBtn.disabled = true;
            saveBtn.textContent = 'Saving...';
        }
        
        try {
            const response = await fetch("{{ route('teachers.store') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({
                    name: nameInput ? nameInput.value.trim() : '',
                    email: emailInput ? emailInput.value.trim() : '',
                    password: passwordInput ? passwordInput.value : '',
                    designation: designationInput ? designationInput.value.trim() : '',
                    department: departmentInput ? departmentInput.value.trim() : ''
                })
            });
            
            const data = await response.json();
            
            if (response.ok && data.status) {
                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: data.message || 'Teacher added successfully',
                    timer: 2000,
                    showConfirmButton: false
                });
                setTimeout(() => {
                    window.location.reload();
                }, 2000);
            } else {
                if (data.errors) {
                    let errorMsg = '';
                    for (let field in data.errors) {
                        errorMsg += data.errors[field].join(', ') + '<br>';
                    }
                    showError(errorMsg);
                } else {
                    showError(data.message || 'Error saving teacher');
                }
                if (saveBtn) {
                    saveBtn.disabled = false;
                    saveBtn.textContent = 'Save Teacher';
                }
            }
        } catch (error) {
            console.error('Error:', error);
            showError('Error saving teacher. Please try again.');
            if (saveBtn) {
                saveBtn.disabled = false;
                saveBtn.textContent = 'Save Teacher';
            }
        }
    }
    
    async function editTeacher(id) {
        try {
            const response = await fetch("/teachers/edit/" + id, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            });
            
            const data = await response.json();
            
            if (response.ok) {
                if (modalTitle) modalTitle.textContent = "Edit Teacher";
                if (teacherId) teacherId.value = data.id;
                if (nameInput) nameInput.value = data.user.name;
                if (emailInput) emailInput.value = data.user.email;
                if (designationInput) designationInput.value = data.user.designation || '';
                if (departmentInput) departmentInput.value = data.user.department || '';
                if (passwordInput) passwordInput.value = '';
                
                if (passwordRequired) passwordRequired.style.display = 'none';
                if (passwordHelp) passwordHelp.style.display = 'inline';
                if (passwordRequiredText) passwordRequiredText.style.display = 'none';
                if (passwordInput) passwordInput.required = false;
                
                if (saveBtn) saveBtn.style.display = 'none';
                if (updateBtn) updateBtn.style.display = 'inline-block';
                if (modal) modal.style.display = 'flex';
            } else {
                Swal.fire('Error', 'Could not fetch teacher details', 'error');
            }
        } catch (error) {
            console.error('Error:', error);
            Swal.fire('Error', 'Could not fetch teacher details', 'error');
        }
    }
    
    async function updateTeacher() {
        if (!validateForm(true)) return;
        
        const id = teacherId ? teacherId.value : '';
        if (updateBtn) {
            updateBtn.disabled = true;
            updateBtn.textContent = 'Updating...';
        }
        
        try {
            const response = await fetch("/teachers/update/" + id, {
                method: 'POST', // Changed to POST as your route shows POST for update
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({
                    name: nameInput ? nameInput.value.trim() : '',
                    email: emailInput ? emailInput.value.trim() : '',
                    password: passwordInput ? passwordInput.value : '',
                    designation: designationInput ? designationInput.value.trim() : '',
                    department: departmentInput ? departmentInput.value.trim() : '',
                    _method: 'PUT' // Laravel method spoofing
                })
            });
            
            const data = await response.json();
            
            if (response.ok && data.status) {
                Swal.fire({
                    icon: 'success',
                    title: 'Updated!',
                    text: data.message || 'Teacher updated successfully',
                    timer: 2000,
                    showConfirmButton: false
                });
                setTimeout(() => {
                    window.location.reload();
                }, 2000);
            } else {
                if (data.errors) {
                    let errorMsg = '';
                    for (let field in data.errors) {
                        errorMsg += data.errors[field].join(', ') + '<br>';
                    }
                    showError(errorMsg);
                } else {
                    showError(data.message || 'Error updating teacher');
                }
                if (updateBtn) {
                    updateBtn.disabled = false;
                    updateBtn.textContent = 'Update Teacher';
                }
            }
        } catch (error) {
            console.error('Error:', error);
            showError('Error updating teacher. Please try again.');
            if (updateBtn) {
                updateBtn.disabled = false;
                updateBtn.textContent = 'Update Teacher';
            }
        }
    }
    
    async function deleteTeacher(id) {
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
                const response = await fetch("/teachers/delete/" + id, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });
                
                const data = await response.json();
                
                if (response.ok && data.status) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Deleted!',
                        text: data.message || 'Teacher deleted successfully',
                        timer: 2000,
                        showConfirmButton: false
                    });
                    setTimeout(() => {
                        window.location.reload();
                    }, 2000);
                } else {
                    Swal.fire('Error', data.message || 'Could not delete teacher', 'error');
                }
            } catch (error) {
                console.error('Error:', error);
                Swal.fire('Error', 'Could not delete teacher', 'error');
            }
        }
    }
    
    // Remove old event listeners and attach new ones
    // Add button
    const newAddBtn = addBtn.cloneNode(true);
    addBtn.parentNode.replaceChild(newAddBtn, addBtn);
    newAddBtn.addEventListener('click', openModal);
    
    // Close button
    if (closeModalBtn) {
        const newCloseBtn = closeModalBtn.cloneNode(true);
        closeModalBtn.parentNode.replaceChild(newCloseBtn, closeModalBtn);
        newCloseBtn.addEventListener('click', closeModal);
    }
    
    // Cancel button
    if (cancelBtn) {
        const newCancelBtn = cancelBtn.cloneNode(true);
        cancelBtn.parentNode.replaceChild(newCancelBtn, cancelBtn);
        newCancelBtn.addEventListener('click', closeModal);
    }
    
    // Save button
    if (saveBtn) {
        const newSaveBtn = saveBtn.cloneNode(true);
        saveBtn.parentNode.replaceChild(newSaveBtn, saveBtn);
        newSaveBtn.addEventListener('click', saveTeacher);
    }
    
    // Update button
    if (updateBtn) {
        const newUpdateBtn = updateBtn.cloneNode(true);
        updateBtn.parentNode.replaceChild(newUpdateBtn, updateBtn);
        newUpdateBtn.addEventListener('click', updateTeacher);
    }
    
    // Edit buttons
    document.querySelectorAll('.edit-btn').forEach(btn => {
        const newBtn = btn.cloneNode(true);
        btn.parentNode.replaceChild(newBtn, btn);
        newBtn.addEventListener('click', (e) => {
            e.stopPropagation();
            const id = newBtn.getAttribute('data-id');
            editTeacher(id);
        });
    });
    
    // Delete buttons
    document.querySelectorAll('.delete-btn').forEach(btn => {
        const newBtn = btn.cloneNode(true);
        btn.parentNode.replaceChild(newBtn, btn);
        newBtn.addEventListener('click', (e) => {
            e.stopPropagation();
            const id = newBtn.getAttribute('data-id');
            deleteTeacher(id);
        });
    });
    
    // Enter key submit
    if (modal) {
        modal.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                if (saveBtn && saveBtn.style.display !== 'none' && saveBtn.offsetParent !== null) {
                    saveTeacher();
                } else if (updateBtn && updateBtn.style.display !== 'none' && updateBtn.offsetParent !== null) {
                    updateTeacher();
                }
            }
        });
    }
    
    // Close modal when clicking outside
    window.addEventListener('click', function(event) {
        if (event.target === modal) {
            closeModal();
        }
    });
    
    console.log('Teacher Module Initialized Successfully');
}

// Initialize when DOM is ready
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', function() {
        initTeacherModule();
    });
} else {
    initTeacherModule();
}

// Export to global scope
window.initTeacherModule = initTeacherModule;
</script>
@endsection