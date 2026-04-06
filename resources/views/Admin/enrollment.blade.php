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
    overflow-y: auto;
}

.top-bar {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.add-btn {
    background: #0f1b5c;
    color: white;
    padding: 10px 20px;
    border: none;
    border-radius: 8px;
    cursor: pointer;
}

/* FILTER BAR */
.search-box {
    margin: 15px 0;
    display: flex;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 10px;
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
}

/* TABLE */
table {
    width: 100%;
    border-collapse: collapse;
}

thead {
    background: #0f1b5c;
    color: white;
}

td, th {
    padding: 10px;
    border: 1px solid #ddd;
}

/* MODAL */
.modal {
    display: none;
    position: fixed;
    inset: 0;
    background: rgba(0,0,0,0.5);
    justify-content: center;
    align-items: center;
}

.modal-content {
    background: #fff;
    width: 55%;
    padding: 25px;
    border-radius: 12px;
}

.form-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 15px;
}

.full {
    grid-column: 1 / -1;
}

</style>
@endsection

@section('content')

<div class="main-container">

    <!-- SUCCESS -->
    @if(session('success'))
        <div style="background:#d4edda; padding:10px; margin-bottom:10px; border-radius:6px;">
            {{ session('success') }}
        </div>
    @endif

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

    <!-- TABLE -->
    <table>
        <thead>
        <tr>
            <th>Student</th>
            <th>Course</th>
            <th>Program</th>
            <th>Session</th>
            <th>Semester</th>
            <th>Section</th>
            <th>Date</th>
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
            </tr>
        @endforeach
        </tbody>
    </table>

</div>

<!-- MODAL -->
<div class="modal" id="modal">
<div class="modal-content">

    <h3>Add Enrollment</h3>

    <form method="POST" action="{{ route('enrollment.store') }}">
    @csrf

    <div class="form-grid">

        <select name="student_id" required>
            <option>Select Student</option>
            @foreach($students as $s)
                <option value="{{ $s->id }}">{{ $s->name }}</option>
            @endforeach
        </select>

        <select name="program_id" id="program">
            @foreach($programs as $p)
                <option value="{{ $p->id }}">{{ $p->name }}</option>
            @endforeach
        </select>

        <select name="session_id">
            @foreach($sessions as $s)
                <option value="{{ $s->id }}">{{ $s->name }}</option>
            @endforeach
        </select>

        <select name="section_id">
            @foreach($sections as $s)
                <option value="{{ $s->id }}">{{ $s->name }}</option>
            @endforeach
        </select>

        <input type="number" name="semester" id="semester" placeholder="Semester">

        <input type="date" name="enrollment_date">

        <select name="offered_course_id[]" id="coursesDropdown" multiple class="full"></select>

        <div class="full">
            <button class="add-btn" style="width:100%">Save</button>
        </div>

    </div>

    </form>

</div>
</div>

<script>

/* MODAL */
function openModal(){ document.getElementById('modal').style.display='flex'; }
function closeModal(){ document.getElementById('modal').style.display='none'; }

/* FILTER */
function fetchData(){
    fetch(`?search=${searchInput.value}&session_id=${sessionFilter.value}&semester=${semesterFilter.value}&program_id=${programFilter.value}`,{
        headers:{'X-Requested-With':'XMLHttpRequest'}
    })
    .then(res=>res.text())
    .then(html=>document.getElementById('enrollmentBody').innerHTML=html);
}

let searchInput=document.getElementById('searchInput');
let sessionFilter=document.getElementById('sessionFilter');
let semesterFilter=document.getElementById('semesterFilter');
let programFilter=document.getElementById('programFilter');

searchInput.addEventListener('keyup',fetchData);
sessionFilter.addEventListener('change',fetchData);
semesterFilter.addEventListener('change',fetchData);
programFilter.addEventListener('change',fetchData);

function resetFilters(){
    searchInput.value='';
    sessionFilter.value='';
    semesterFilter.value='';
    programFilter.value='';
    fetchData();
}

/* COURSE LOAD */
let program=document.getElementById('program');
let semester=document.getElementById('semester');
let dropdown=document.getElementById('coursesDropdown');

function loadCourses(){
    if(!program.value || !semester.value) return;

    fetch(`/get-offered-courses?program_id=${program.value}&semester=${semester.value}`)
    .then(res=>res.json())
    .then(data=>{
        dropdown.innerHTML='';
        data.forEach(c=>{
            dropdown.innerHTML+=`<option value="${c.id}">${c.course.course_title}</option>`;
        });
    });
}

program.addEventListener('change',loadCourses);
semester.addEventListener('input',loadCourses);

</script>

@endsection
