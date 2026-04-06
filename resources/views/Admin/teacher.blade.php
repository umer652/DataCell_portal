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
    margin-bottom: 20px;
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
td button {
    padding: 6px 12px;
    border-radius: 6px;
    border: none;
    cursor: pointer;
    font-size: 13px;
}

td button:first-child {
    background: #0f1b5c;
    color: #fff;
}

td button:last-child {
    background: #b30000;
    color: #fff;
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
}

.close {
    position: absolute;
    right: 20px;
    top: 15px;
    font-size: 22px;
    cursor: pointer;
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
}

input {
    padding: 10px;
    border-radius: 6px;
    border: 1px solid #ccc;
}

.full-width {
    grid-column: 1 / -1;
    display: flex;
    justify-content: center;
}

button {
    background-color: #0f1b5c;
    color: #fff;
    padding: 12px 25px;
    border: none;
    border-radius: 8px;
    cursor: pointer;
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
}

</style>
@endsection

@section('content')

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<div class="main-container">

    <!-- TOP BAR -->
    <div class="top-bar">
        <h2>Teachers</h2>
        <button class="add-btn" onclick="openModal()">+ Add Teacher</button>
    </div>

    <!-- TABLE -->
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Designation</th>
                    <th>Department</th>
                    <th>Action</th>
                </tr>
            </thead>

            <tbody>
                @foreach($teachers as $t)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $t->user->name }}</td>
                    <td>{{ $t->user->email }}</td>
                    <td>{{ $t->user->designation }}</td>
                    <td>{{ $t->user->department }}</td>
                    <td>
                        <button onclick="editTeacher({{ $t->id }})">Edit</button>
                        <button onclick="deleteTeacher({{ $t->id }})">Delete</button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

</div>

<!-- MODAL -->
<div class="modal" id="teacherModal">
    <div class="modal-content">

        <span class="close" onclick="closeModal()">&times;</span>

        <h2>Teacher Form</h2>

        <div id="formError"></div>

        <input type="hidden" id="teacher_id">

        <div class="form-grid">

            <div>
                <label>Name</label>
                <input type="text" id="name">
            </div>

            <div>
                <label>Email</label>
                <input type="email" id="email">
            </div>

            <div>
                <label>Password</label>
                <input type="password" id="password">
            </div>

            <div>
                <label>Designation</label>
                <input type="text" id="designation">
            </div>

            <div class="full-width">
                <label>Department</label>
                <input type="text" id="department">
            </div>

        </div>

        <br>

        <div class="full-width">
            <button onclick="saveTeacher()">Save</button>
            <button onclick="updateTeacher()" id="updateBtn" style="display:none;">Update</button>
        </div>

    </div>
</div>

<script>

/* MODAL */
function openModal() {
    resetForm();
    $('#teacherModal').show();
}

function closeModal() {
    $('#teacherModal').hide();
}

/* RESET */
function resetForm() {
    $('#teacher_id').val('');
    $('#name, #email, #password, #designation, #department').val('');
    $('#updateBtn').hide();
    $('#formError').hide().html('');
}

/* ERROR */
function showError(msg) {
    $('#formError').html(msg).show();
}

/* CREATE */
function saveTeacher() {
    $.post("/teachers/store", {
        _token: "{{ csrf_token() }}",
        name: $('#name').val(),
        email: $('#email').val(),
        password: $('#password').val(),
        designation: $('#designation').val(),
        department: $('#department').val(),
    })
    .done(res => {
        Swal.fire('Success', res.message, 'success');
        location.reload();
    })
    .fail(xhr => {
        handleError(xhr);
    });
}

/* EDIT */
function editTeacher(id) {
    $.get("/teachers/edit/" + id, function(data) {
        openModal();

        $('#teacher_id').val(data.id);
        $('#name').val(data.user.name);
        $('#email').val(data.user.email);
        $('#designation').val(data.user.designation);
        $('#department').val(data.user.department);

        $('#updateBtn').show();
    });
}

/* UPDATE */
function updateTeacher() {
    let id = $('#teacher_id').val();

    $.post("/teachers/update/" + id, {
        _token: "{{ csrf_token() }}",
        name: $('#name').val(),
        email: $('#email').val(),
        password: $('#password').val(),
        designation: $('#designation').val(),
        department: $('#department').val()
    })
    .done(res => {
        Swal.fire('Updated', res.message, 'success');
        location.reload();
    })
    .fail(xhr => {
        handleError(xhr);
    });
}

/* DELETE */
function deleteTeacher(id) {
    if (!confirm("Are you sure?")) return;

    $.ajax({
        url: "/teachers/delete/" + id,
        type: "DELETE",
        data: { _token: "{{ csrf_token() }}" },
        success: function(res) {
            Swal.fire('Deleted', res.message, 'success');
            location.reload();
        }
    });
}

/* ERROR HANDLER */
function handleError(xhr) {
    let msg = 'Something went wrong';

    if (xhr.status === 422) {
        msg = '';
        $.each(xhr.responseJSON.errors, function(k, v) {
            msg += v[0] + '<br>';
        });
    }

    showError(msg);
}

</script>

@endsection
