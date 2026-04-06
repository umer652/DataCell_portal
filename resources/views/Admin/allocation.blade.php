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
}
.add-btn:hover { background: #0c1445; }

/* TABLE */
.table-container {
    flex: 1;
    overflow: auto;
    border-radius: 10px;
}

table {
    width: 100%;
    border-collapse: collapse;
}

thead {
    background: #0f1b5c;
    color: #fff;
}

th, td {
    padding: 12px;
    font-size: 14px;
    border-bottom: 1px solid #eee;
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
}

.close {
    position: absolute;
    right: 15px;
    top: 10px;
    font-size: 22px;
    cursor: pointer;
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
}

.form-group select,
.form-group input {
    height: 42px;
    padding: 8px 12px;
    border-radius: 8px;
    border: 1px solid #ddd;
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
}

.save-btn {
    background: #0f1b5c;
    color: #fff;
    padding: 10px 18px;
    border-radius: 8px;
    border: none;
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
        <table>
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
                </tr>
            </thead>
            <tbody>
                {{-- DATA --}}
            </tbody>
        </table>
    </div>

</div>

<!-- MODAL -->
<div class="modal" id="allocationModal">
<div class="modal-content">

    <span class="close" onclick="closeModal()">&times;</span>

    <h3 style="margin-bottom:15px;">New Teacher Allocation</h3>

    <form method="POST">
        @csrf

        <div class="form-grid">

            <!-- LEFT SIDE -->
            <div class="form-group">
                <label>Program</label>
                <select name="program_id" id="program_id">
                    <option value="">Select Program</option>
                    @foreach($programs as $program)
                        <option value="{{ $program->id }}">{{ $program->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label>Course</label>
                <select name="course_id" id="course_id">
                    <option value="">Select Course</option>
                </select>
            </div>

            <div class="form-group">
                <label>Scheme</label>
                <select name="scheme_id" id="scheme_id">
                    <option value="">Select Scheme</option>
                </select>
            </div>

            <div class="form-group">
                <label>Session</label>
                <select name="session_id">
                    @foreach($sessions as $session)
                        <option value="{{ $session->id }}"
                            {{ $activeSession && $activeSession->id == $session->id ? 'selected' : '' }}>
                            {{ $session->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- RIGHT SIDE -->
            <div class="form-group">
                <label>Teacher</label>
                <select name="teacher_id">
                    @foreach($teachers as $teacher)
                        <option value="{{ $teacher->id }}">{{ $teacher->user->name ?? '' }}  </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label>Semester</label>
                <select name="semester">
                    @for($i=1;$i<=8;$i++)
                        <option value="{{ $i }}">Semester {{ $i }}</option>
                    @endfor
                </select>
            </div>

            <div class="form-group">
                <label>Section</label>
                <select name="section_id">
                    @foreach($sections as $section)
                        <option value="{{ $section->id }}">{{ $section->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-actions">
                <button type="button" class="cancel-btn" onclick="closeModal()">Cancel</button>
                <button type="submit" class="save-btn">Save</button>
            </div>

        </div>
    </form>

</div>
</div>

<!-- SCRIPT -->
<script>

function openModal(){
    document.getElementById('allocationModal').style.display = 'flex';
}

function closeModal(){
    document.getElementById('allocationModal').style.display = 'none';
}

/**
 * PROGRAM CHANGE
 */
document.getElementById('program_id').addEventListener('change', function () {

    let programId = this.value;

    let courseSelect = document.getElementById('course_id');
    let schemeSelect = document.getElementById('scheme_id');

    courseSelect.innerHTML = '<option>Loading...</option>';
    schemeSelect.innerHTML = '<option>Loading...</option>';

    if(!programId){
        courseSelect.innerHTML = '<option value="">Select Course</option>';
        schemeSelect.innerHTML = '<option value="">Select Scheme</option>';
        return;
    }

    // COURSES
    fetch('/get-courses/' + programId)
        .then(res => res.json())
        .then(data => {
            courseSelect.innerHTML = '<option value="">Select Course</option>';
            data.forEach(c => {
                courseSelect.innerHTML += `<option value="${c.id}">${c.course_title}</option>`;
            });
        });

    // ACTIVE SCHEME
    fetch('/get-active-scheme/' + programId)
        .then(res => res.json())
        .then(data => {
            schemeSelect.innerHTML = '';

            if(data && data.id){
                schemeSelect.innerHTML =
                    `<option value="${data.id}" selected>${data.title} (Active)</option>`;
            } else {
                schemeSelect.innerHTML = `<option value="">No Active Scheme</option>`;
            }
        });

});

</script>

@endsection
