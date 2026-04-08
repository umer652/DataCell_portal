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
    font-size: 24px;
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
    table-layout: fixed;
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
}

/* BODY CELLS */
tbody td {
    text-align: left;
    vertical-align: middle;
    border-bottom: 1px solid #ddd;
}

/* LAST COLUMN (ACTIONS) CENTER */
thead th:last-child,
tbody td:last-child {
    text-align: center;
}

/* PADDING */
thead th,
tbody td {
    padding: 12px 16px;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

/* ACTION BUTTONS */
.action-buttons {
    display: flex;
    gap: 8px;
    justify-content: center;
}

td button {
    padding: 6px 12px;
    border-radius: 6px;
    border: none;
    cursor: pointer;
    font-size: 13px;
    transition: all 0.2s ease;
}

td button:first-child {
    background: #0f1b5c;
    color: #fff;
}

td button:first-child:hover {
    background: #16256e;
    transform: translateY(-1px);
}

td button:last-child {
    background: #b30000;
    color: #fff;
}

td button:last-child:hover {
    background: #cc0000;
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

/* MODAL */
.modal {
    display: none;
    position: fixed;
    z-index: 2000;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    background: rgba(0,0,0,0.5);
    animation: fadeIn 0.3s ease;
}

@keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}

.modal-content {
    background: #fff;
    margin: 3% auto;
    width: 60%;
    max-height: 85vh;
    overflow-y: auto;
    border-radius: 10px;
    padding: 25px;
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
    right: 20px;
    top: 15px;
    font-size: 28px;
    cursor: pointer;
    color: #999;
    transition: color 0.2s ease;
}

.close:hover {
    color: #0f1b5c;
}

/* FORM GRID */
.form-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 15px;
}

.form-grid div {
    display: flex;
    flex-direction: column;
}

label {
    margin-bottom: 5px;
    font-weight: 600;
    color: #333;
}

input, select {
    padding: 10px;
    border-radius: 6px;
    border: 1px solid #ccc;
    font-size: 14px;
    transition: border-color 0.2s ease;
}

input:focus, select:focus {
    outline: none;
    border-color: #0f1b5c;
    box-shadow: 0 0 0 2px rgba(15,27,92,0.1);
}

.full-width {
    grid-column: 1 / -1;
    display: flex;
    justify-content: center;
    gap: 15px;
}

.full-width button {
    background-color: #0f1b5c;
    color: #fff;
    padding: 12px 25px;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    font-size: 14px;
    font-weight: 500;
    transition: all 0.2s ease;
}

.full-width button:hover {
    background-color: #16256e;
    transform: translateY(-1px);
}

/* ERROR BOX */
#formError {
    display: none;
    background: #ffe5e5;
    color: #b30000;
    padding: 10px;
    margin-bottom: 10px;
    border-radius: 6px;
    font-size: 14px;
    border-left: 4px solid #b30000;
}

/* EMPTY STATE */
.empty-state {
    text-align: center;
    padding: 40px;
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

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<div class="main-container">

    <!-- TOP BAR -->
    <div class="top-bar">
        <h2>Teachers Management</h2>
        <button class="add-btn" onclick="openModal()">+ Add New Teacher</button>
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
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $t->user->name }}</td>
                    <td>{{ $t->user->email }}</td>
                    <td>{{ $t->user->designation ?? 'N/A' }}</td>
                    <td>{{ $t->user->department ?? 'N/A' }}</td>
                    <td>
                        <div class="action-buttons">
                            <button onclick="editTeacher({{ $t->id }})" title="Edit Teacher">✏️ Edit</button>
                            <button onclick="deleteTeacher({{ $t->id }})" title="Delete Teacher">🗑️ Delete</button>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @else
        <div class="empty-state">
            <p>No teachers found. Click "Add New Teacher" to get started.</p>
        </div>
        @endif
    </div>

</div>

<!-- MODAL -->
<div class="modal" id="teacherModal">
    <div class="modal-content">
        <span class="close" onclick="closeModal()">&times;</span>
        <h2 id="modalTitle">Add New Teacher</h2>
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
                <label>Password *</label>
                <input type="password" id="password" placeholder="Enter password">
                <small style="color: #666; font-size: 12px; margin-top: 4px;">Leave blank to keep unchanged when editing</small>
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
        
        <br>
        
        <div class="full-width">
            <button onclick="saveTeacher()" id="saveBtn">💾 Save Teacher</button>
            <button onclick="updateTeacher()" id="updateBtn" style="display:none;">🔄 Update Teacher</button>
            <button onclick="closeModal()" style="background: #6c757d;">❌ Cancel</button>
        </div>
    </div>
</div>

<script>

// Make sure CSRF token is set for all AJAX requests
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') || "{{ csrf_token() }}"
    }
});

/* MODAL FUNCTIONS */
function openModal() {
    resetForm();
    $('#modalTitle').text('Add New Teacher');
    $('#teacherModal').fadeIn(200);
}

function closeModal() {
    $('#teacherModal').fadeOut(200);
    resetForm();
}

/* RESET FORM */
function resetForm() {
    $('#teacher_id').val('');
    $('#name, #email, #password, #designation, #department').val('');
    $('#saveBtn').show();
    $('#updateBtn').hide();
    $('#formError').hide().html('');
    $('#modalTitle').text('Add New Teacher');
}

/* SHOW ERROR */
function showError(msg) {
    $('#formError').html(msg).fadeIn();
    setTimeout(() => {
        $('#formError').fadeOut();
    }, 5000);
}

/* VALIDATE FORM */
function validateForm() {
    let name = $('#name').val().trim();
    let email = $('#email').val().trim();
    let password = $('#password').val();
    let isEdit = $('#teacher_id').val() !== '';
    
    if (!name) {
        showError('Please enter teacher name');
        return false;
    }
    if (!email) {
        showError('Please enter email address');
        return false;
    }
    if (!isEdit && !password) {
        showError('Please enter password for new teacher');
        return false;
    }
    if (email && !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
        showError('Please enter a valid email address');
        return false;
    }
    return true;
}

/* CREATE TEACHER */
function saveTeacher() {
    if (!validateForm()) return;
    
    let submitBtn = $('#saveBtn');
    submitBtn.prop('disabled', true).text('Saving...');
    
    $.ajax({
        url: "{{ route('teachers.store') }}",
        method: "POST",
        data: {
            name: $('#name').val().trim(),
            email: $('#email').val().trim(),
            password: $('#password').val(),
            designation: $('#designation').val().trim(),
            department: $('#department').val().trim(),
            _token: "{{ csrf_token() }}"
        },
        success: function(res) {
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: res.message || 'Teacher added successfully',
                timer: 2000,
                showConfirmButton: false
            });
            setTimeout(() => {
                location.reload();
            }, 2000);
        },
        error: function(xhr) {
            handleError(xhr);
            submitBtn.prop('disabled', false).text('💾 Save Teacher');
        }
    });
}

/* EDIT TEACHER */
function editTeacher(id) {
    $.ajax({
        url: "/teachers/edit/" + id,
        method: "GET",
        success: function(data) {
            openModal();
            $('#modalTitle').text('Edit Teacher');
            $('#teacher_id').val(data.id);
            $('#name').val(data.user.name);
            $('#email').val(data.user.email);
            $('#designation').val(data.user.designation || '');
            $('#department').val(data.user.department || '');
            $('#password').val('');
            $('#saveBtn').hide();
            $('#updateBtn').show();
        },
        error: function() {
            Swal.fire('Error', 'Could not fetch teacher details', 'error');
        }
    });
}

/* UPDATE TEACHER */
function updateTeacher() {
    if (!validateForm()) return;
    
    let id = $('#teacher_id').val();
    let updateBtn = $('#updateBtn');
    updateBtn.prop('disabled', true).text('Updating...');
    
    $.ajax({
        url: "/teachers/update/" + id,
        method: "POST",
        data: {
            name: $('#name').val().trim(),
            email: $('#email').val().trim(),
            password: $('#password').val(),
            designation: $('#designation').val().trim(),
            department: $('#department').val().trim(),
            _token: "{{ csrf_token() }}",
            _method: "PUT"
        },
        success: function(res) {
            Swal.fire({
                icon: 'success',
                title: 'Updated!',
                text: res.message || 'Teacher updated successfully',
                timer: 2000,
                showConfirmButton: false
            });
            setTimeout(() => {
                location.reload();
            }, 2000);
        },
        error: function(xhr) {
            handleError(xhr);
            updateBtn.prop('disabled', false).text('🔄 Update Teacher');
        }
    });
}

/* DELETE TEACHER */
function deleteTeacher(id) {
    Swal.fire({
        title: 'Are you sure?',
        text: "This action cannot be undone!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#b30000',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: "/teachers/delete/" + id,
                method: "DELETE",
                data: {
                    _token: "{{ csrf_token() }}"
                },
                success: function(res) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Deleted!',
                        text: res.message || 'Teacher deleted successfully',
                        timer: 2000,
                        showConfirmButton: false
                    });
                    setTimeout(() => {
                        location.reload();
                    }, 2000);
                },
                error: function() {
                    Swal.fire('Error', 'Could not delete teacher', 'error');
                }
            });
        }
    });
}

/* ERROR HANDLER */
function handleError(xhr) {
    let msg = 'Something went wrong. Please try again.';
    
    if (xhr.status === 422) {
        let errors = xhr.responseJSON.errors;
        msg = '';
        $.each(errors, function(key, value) {
            msg += '• ' + value[0] + '<br>';
        });
    } else if (xhr.status === 409) {
        msg = xhr.responseJSON.message || 'Email already exists!';
    } else if (xhr.status === 500) {
        msg = 'Server error. Please check your connection.';
    }
    
    showError(msg);
}

// Close modal when clicking outside
$(document).ready(function() {
    $(window).click(function(event) {
        if ($(event.target).hasClass('modal')) {
            closeModal();
        }
    });
    
    // Enter key submit
    $('#teacherModal input').keypress(function(e) {
        if (e.which === 13) {
            e.preventDefault();
            if ($('#saveBtn').is(':visible')) {
                saveTeacher();
            } else if ($('#updateBtn').is(':visible')) {
                updateTeacher();
            }
        }
    });
});

</script>

@endsection