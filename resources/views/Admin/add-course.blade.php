@extends('layouts.app')

@section('title', 'Courses')

@section('styles')
<style>

/* ICON */
.add-btn i {
    margin-right: 6px;
}

/* TOP BAR */
.top-bar {
    display: flex;
    align-items: center;
    margin-bottom: 20px;
}

/* BUTTON GROUP */
.button-group {
    margin-left: auto;
    display: flex;
    gap: 10px;
}

/* BUTTONS */
.add-btn {
    background: #0f1b5c;
    color: #fff;
    padding: 10px 18px;
    border: none;
    border-radius: 8px;
    cursor: pointer;
}

.import-btn {
    background: #198754;
}

/* MAIN CONTAINER */
.main-container {
    position: fixed;
    top: 80px;
    left: 270px;
    width: calc(100% - 290px);
    bottom: 20px;
    background: #fff;
    padding: 25px;
    border-radius: 12px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.08);
    display: flex;
    flex-direction: column;
    overflow: hidden;
}

/* TEMPLATE BOX */
.template-box {
    background: #f8f9fa;
    border: 1px solid #ddd;
    border-radius: 10px;
    padding: 15px;
    margin-bottom: 15px;
}

/* TABLE */
.table-container {
    flex: 1;
    overflow: auto;
}

table {
    width: 100%;
    border-collapse: collapse;
}

thead th {
    position: sticky;
    top: 0;
    background: #0f1b5c;
    color: #fff;
    padding: 12px;
}

tbody td {
    padding: 10px;
    border-bottom: 1px solid #eee;
}

/* MODAL */
.modal {
    display: none;
    position: fixed;
    z-index: 2000;
    width: 100%;
    height: 100%;
    background: transparent; /* ✅ removed shadow */
}

/* ADD MODAL */
.modal-content {
    background: #fff;
    margin: 5% auto;
    width: 55%;
    border-radius: 12px;
    padding: 25px;
    position: relative;
    box-shadow: 0 8px 25px rgba(0,0,0,0.15);
}

/* IMPORT MODAL (BIGGER) */
.import-modal-content {
    background: #fff;
    margin: 8% auto;
    width: 450px;
    border-radius: 12px;
    padding: 25px;
    position: relative;
    box-shadow: 0 8px 25px rgba(0,0,0,0.15);
}

/* CLOSE */
.close {
    position: absolute;
    right: 15px;
    top: 10px;
    cursor: pointer;
    font-size: 20px;
}

/* FORM GRID */
.form-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 15px;
    width: 100%;
}

.full-width {
    grid-column: 1 / -1;
    width: 100%;
}

/* INPUT FIX */
input, textarea {
    width: 100%;                 /* ✅ prevents overflow */
    max-width: 100%;
    box-sizing: border-box;      /* ✅ important */
    padding: 10px;
    border-radius: 6px;
    border: 1px solid #ccc;
}

/* TEXTAREA FIX */
textarea {
    resize: vertical;            /* ✅ no horizontal stretch */
    min-height: 100px;
}

/* BUTTON */
button[type="submit"] {
    background: #0f1b5c;
    color: #fff;
    padding: 10px;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    width: 100%;
}

</style>
@endsection

@section('content')

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

{{-- SUCCESS --}}
@if(session('success'))
<script>
Swal.fire({
    icon: 'success',
    title: 'Success',
    html: `<b>{{ session('success') }}</b><br>Inserted: {{ session('inserted') ?? 0 }}`,
    toast: true,
    position: 'top',
    timer: 3000,
    showConfirmButton: false
});
</script>
@endif

{{-- ERROR --}}
@if(session('error'))
<script>
Swal.fire({
    icon: 'error',
    title: 'Import Stopped',
    text: '{{ session('error') }}',
    toast: true,
    position: 'top',
    timer: 4000,
    showConfirmButton: false
});
</script>
@endif

<div class="main-container">

    <!-- HEADER -->
    <div class="top-bar">
        <h2>Courses</h2>

        <div class="button-group">
            <button class="add-btn" onclick="openModal()">+ Add Course</button>

            <button class="add-btn import-btn" onclick="openImportModal()">
                <i class="fa-solid fa-upload"></i> Upload Excel
            </button>
        </div>
    </div>

    <!-- TEMPLATE -->
    <div class="template-box">
        <h4>Excel Template Format</h4>

        <table>
            <thead>
                <tr>
                    <th>course_code</th>
                    <th>course_title</th>
                    <th>description</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>CSE-101</td>
                    <td>Programming Fundamentals</td>
                    <td>Intro to programming</td>
                </tr>
                <tr>
                    <td>CSE-102</td>
                    <td>Database Systems</td>
                    <td>SQL basics</td>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- TABLE -->
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>Course Code</th>
                    <th>Course Title</th>
                    <th>Description</th>
                </tr>
            </thead>

            <tbody>
                @foreach($courses as $course)
                <tr>
                    <td>{{ $course->course_code }}</td>
                    <td>{{ $course->course_title }}</td>
                    <td>{{ $course->description }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

</div>

<!-- ADD MODAL -->
<div class="modal" id="courseModal">
    <div class="modal-content">

        <span class="close" onclick="closeModal()">&times;</span>

        <h2>Add Course</h2>

        <form method="POST" action="{{ route('courses.store') }}">
            @csrf

            <div class="form-grid">

                <div>
                    <label>Course Code</label>
                    <input type="text" name="course_code"
                           pattern="[A-Z]{3}-[0-9]{3}"
                           oninput="this.value=this.value.toUpperCase()" required>
                </div>

                <div>
                    <label>Course Title</label>
                    <input type="text" name="course_title" required>
                </div>

                <div class="full-width">
                    <label>Description</label>
                    <textarea name="description"></textarea>
                </div>

                <div class="full-width">
                    <button type="submit">Save Course</button>
                </div>

            </div>
        </form>

    </div>
</div>

<!-- IMPORT MODAL -->
<div class="modal" id="importModal">
    <div class="import-modal-content">

        <span class="close" onclick="closeImportModal()">&times;</span>

        <h3>Upload Excel File</h3>

        <form method="POST" action="{{ route('courses.import') }}" enctype="multipart/form-data">
            @csrf

            <input type="file" name="file" required>

            <br><br>

            <button type="submit">Upload File</button>
        </form>

    </div>
</div>

<script>
function openModal() {
    document.getElementById('courseModal').style.display = 'block';
}

function closeModal() {
    document.getElementById('courseModal').style.display = 'none';
}

function openImportModal() {
    document.getElementById('importModal').style.display = 'block';
}

function closeImportModal() {
    document.getElementById('importModal').style.display = 'none';
}
</script>

@endsection
