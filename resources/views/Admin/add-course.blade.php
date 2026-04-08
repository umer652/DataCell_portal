@extends('layouts.app')

@section('title', 'Course Management System')

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
    justify-content: space-between;
    margin-bottom: 20px;
}

/* PAGE TITLE */
.page-title {
    font-size: 24px;
    font-weight: 600;
    color: #0f1b5c;
    margin: 0;
}

/* BUTTON GROUP */
.button-group {
    display: flex;
    gap: 10px;
    align-items: center;
}

/* BUTTONS */
.add-btn {
    background: #0f1b5c;
    color: #fff;
    padding: 10px 18px;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    font-weight: 500;
    transition: all 0.3s ease;
}

.add-btn:hover {
    background: #16256e;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(15,27,92,0.2);
}

.import-btn {
    background: #198754;
}

.import-btn:hover {
    background: #157347;
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
    transition: left 0.3s ease, width 0.3s ease;
}

/* SIDEBAR COLLAPSED */
body.sidebar-collapsed .main-container {
    left: 100px;
    width: calc(100% - 120px);
}

/* TABLE */
.table-container {
    flex: 1;
    overflow: auto;
    border-radius: 8px;
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
    text-align: left;
    font-weight: 600;
}

tbody td {
    padding: 12px;
    border-bottom: 1px solid #eee;
}

tbody tr:hover {
    background: #f8f9fa;
}

/* ACTION ICONS */
.action-icons {
    display: flex;
    gap: 12px;
}

.edit-icon {
    color: #0f1b5c;
    cursor: pointer;
    font-size: 18px;
    transition: color 0.2s ease;
}

.edit-icon:hover {
    color: #16256e;
    transform: scale(1.1);
}

.delete-icon {
    color: #d33;
    cursor: pointer;
    font-size: 18px;
    transition: color 0.2s ease;
}

.delete-icon:hover {
    color: #b30000;
    transform: scale(1.1);
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
    margin: 5% auto;
    width: 55%;
    border-radius: 12px;
    padding: 25px;
    position: relative;
    box-shadow: 0 8px 25px rgba(0,0,0,0.15);
    animation: slideDown 0.3s ease;
}

.import-modal-content {
    background: #fff;
    margin: 8% auto;
    width: 450px;
    border-radius: 12px;
    padding: 25px;
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

/* CLOSE BUTTON */
.close {
    position: absolute;
    right: 20px;
    top: 15px;
    cursor: pointer;
    font-size: 24px;
    color: #999;
    transition: color 0.2s ease;
}

.close:hover {
    color: #0f1b5c;
}

/* FORM */
.form-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 15px;
}

.full-width {
    grid-column: 1 / -1;
}

label {
    font-weight: 600;
    margin-bottom: 5px;
    display: block;
    color: #333;
}

input, textarea {
    width: 100%;
    padding: 10px;
    border-radius: 6px;
    border: 1px solid #ccc;
    font-size: 14px;
    transition: border-color 0.2s ease;
}

input:focus, textarea:focus {
    outline: none;
    border-color: #0f1b5c;
    box-shadow: 0 0 0 2px rgba(15,27,92,0.1);
}

textarea {
    resize: vertical;
    min-height: 100px;
}

button[type="submit"] {
    background: #0f1b5c;
    color: #fff;
    padding: 12px 20px;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    font-weight: 500;
    width: 100%;
    transition: all 0.3s ease;
}

button[type="submit"]:hover {
    background: #16256e;
    transform: translateY(-2px);
}

/* IMPORT MODAL STYLES */
#importModal h3 {
    color: #0f1b5c;
    margin-bottom: 20px;
}

#importModal input[type="file"] {
    border: 1px solid #ccc;
    padding: 8px;
    border-radius: 6px;
    width: 100%;
}

#importModal button {
    background: #198754;
    width: 100%;
}

#importModal button:hover {
    background: #157347;
}

/* EMPTY STATE */
.empty-state {
    text-align: center;
    padding: 50px;
    color: #999;
    font-size: 16px;
}

/* SCROLLBAR */
.table-container::-webkit-scrollbar {
    width: 8px;
    height: 8px;
}
.table-container::-webkit-scrollbar-thumb {
    background: #0f1b5c;
    border-radius: 10px;
}
.table-container::-webkit-scrollbar-track {
    background: #f1f1f1;
}

/* RESPONSIVE */
@media (max-width: 768px) {
    .main-container {
        left: 0 !important;
        width: 100% !important;
        top: 60px;
        border-radius: 0;
    }
    
    .modal-content, .import-modal-content {
        width: 95%;
        margin: 10% auto;
    }
    
    .form-grid {
        grid-template-columns: 1fr;
    }
    
    .top-bar {
        flex-direction: column;
        gap: 15px;
    }
    
    .button-group {
        width: 100%;
        justify-content: space-between;
    }
    
    .page-title {
        font-size: 20px;
    }
}

</style>
@endsection

@section('content')

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://kit.fontawesome.com/your-fontawesome-kit.js" crossorigin="anonymous"></script>
<!-- Fallback for Font Awesome if kit not available -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<div class="main-container">

    <!-- HEADER WITH TITLE -->
    <div class="top-bar">
        <h2 class="page-title">Course Management</h2>
        <div class="button-group">
            <button class="add-btn" onclick="openModal()">
                <i class="fas fa-plus"></i> Add Course
            </button>
            <button class="add-btn import-btn" onclick="openImportModal()">
                <i class="fas fa-upload"></i> Upload Excel
            </button>
        </div>
    </div>

    <!-- TABLE -->
    <div class="table-container">
        @if(isset($courses) && count($courses) > 0)
        <table>
            <thead>
                <tr>
                    <th style="width: 15%">Course Code</th>
                    <th style="width: 25%">Course Title</th>
                    <th style="width: 45%">Description</th>
                    <th style="width: 15%">Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($courses as $course)
                <tr>
                    <td>{{ $course->course_code }}</td>
                    <td>{{ $course->course_title }}</td>
                    <td>{{ $course->description ?? 'No description provided' }}</td>
                    <td>
                        <div class="action-icons">
                            <i class="fas fa-pen-to-square edit-icon"
                               title="Edit Course"
                               onclick='editCourse(@json($course))'></i>
                            <i class="fas fa-trash-alt delete-icon"
                               title="Delete Course"
                               onclick="deleteCourse({{ $course->id }})"></i>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @else
        <div class="empty-state">
            <i class="fas fa-book-open" style="font-size: 48px; margin-bottom: 15px; opacity: 0.5;"></i>
            <p>No courses found. Click "Add Course" to get started or upload courses via Excel.</p>
        </div>
        @endif
    </div>

</div>

<!-- ADD / EDIT MODAL -->
<div class="modal" id="courseModal">
    <div class="modal-content">

        <span class="close" onclick="closeModal()">&times;</span>

        <h2 id="modalTitle">Add Course</h2>

        <form method="POST" id="courseForm">
            @csrf
            <input type="hidden" name="_method" id="formMethod" value="POST">

            <div class="form-grid">

                <div>
                    <label>Course Code *</label>
                    <input type="text" name="course_code" id="course_code"
                           pattern="[A-Za-z0-9]{3,10}"
                           placeholder="e.g., CS101 or CSC-301"
                           oninput="this.value=this.value.toUpperCase()" required>
                    <small style="color: #666; font-size: 12px;">Format: 3-10 characters (letters/numbers/hyphen)</small>
                </div>

                <div>
                    <label>Course Title *</label>
                    <input type="text" name="course_title" id="course_title" 
                           placeholder="e.g., Introduction to Programming" required>
                </div>

                <div class="full-width">
                    <label>Description</label>
                    <textarea name="description" id="description" 
                              placeholder="Enter course description here..."></textarea>
                </div>

                <div class="full-width">
                    <button type="submit">
                        <i class="fas fa-save"></i> Save Course
                    </button>
                </div>

            </div>
        </form>

    </div>
</div>

<!-- IMPORT MODAL -->
<div class="modal" id="importModal">
    <div class="import-modal-content">

        <span class="close" onclick="closeImportModal()">&times;</span>

        <h3><i class="fas fa-file-excel"></i> Upload Excel File</h3>
        
        <p style="color: #666; margin-bottom: 15px; font-size: 13px;">
            Upload an Excel file (.xlsx, .xls) with columns: course_code, course_title, description
        </p>

        <form method="POST" action="{{ route('courses.import') }}" enctype="multipart/form-data" id="importForm">
            @csrf
            <input type="file" name="file" accept=".xlsx,.xls,.csv" required>
            <br><br>
            <button type="submit">
                <i class="fas fa-upload"></i> Upload File
            </button>
        </form>

    </div>
</div>

<script>

// CSRF Token for AJAX
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': '{{ csrf_token() }}'
    }
});

// OPEN ADD MODAL
function openModal() {
    document.getElementById('modalTitle').innerText = "Add Course";
    document.getElementById('courseForm').action = "{{ route('courses.store') }}";
    document.getElementById('formMethod').value = "POST";
    document.getElementById('course_code').value = "";
    document.getElementById('course_title').value = "";
    document.getElementById('description').value = "";
    document.getElementById('course_code').removeAttribute('readonly');
    document.getElementById('courseModal').style.display = 'block';
}

// EDIT COURSE
function editCourse(course) {
    document.getElementById('modalTitle').innerText = "Edit Course";
    document.getElementById('courseForm').action = "/courses/" + course.id;
    document.getElementById('formMethod').value = "PUT";
    document.getElementById('course_code').value = course.course_code;
    document.getElementById('course_title').value = course.course_title;
    document.getElementById('description').value = course.description ?? '';
    document.getElementById('courseModal').style.display = 'block';
}

// DELETE COURSE
function deleteCourse(id) {
    Swal.fire({
        title: 'Are you sure?',
        text: "You won't be able to revert this!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
        if (result.isConfirmed) {
            fetch("/courses/" + id, {
                method: "DELETE",
                headers: {
                    "X-CSRF-TOKEN": "{{ csrf_token() }}",
                    "Accept": "application/json",
                    "Content-Type": "application/json"
                }
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    Swal.fire(
                        'Deleted!',
                        'Course has been deleted successfully.',
                        'success'
                    ).then(() => {
                        location.reload();
                    });
                } else {
                    Swal.fire('Error!', data.message || 'Something went wrong.', 'error');
                }
            })
            .catch(error => {
                Swal.fire('Error!', 'Could not delete the course.', 'error');
            });
        }
    });
}

// CLOSE MODALS
function closeModal() {
    document.getElementById('courseModal').style.display = 'none';
}

function openImportModal() {
    document.getElementById('importModal').style.display = 'block';
}

function closeImportModal() {
    document.getElementById('importModal').style.display = 'none';
}

// Handle Import Form Submission
document.getElementById('importForm')?.addEventListener('submit', function(e) {
    const fileInput = this.querySelector('input[type="file"]');
    if (!fileInput.files.length) {
        e.preventDefault();
        Swal.fire('Error', 'Please select a file to upload', 'error');
    }
});

// Close modal when clicking outside
window.onclick = function(event) {
    const courseModal = document.getElementById('courseModal');
    const importModal = document.getElementById('importModal');
    if (event.target === courseModal) {
        closeModal();
    }
    if (event.target === importModal) {
        closeImportModal();
    }
}

// Form validation before submit
document.getElementById('courseForm')?.addEventListener('submit', function(e) {
    const courseCode = document.getElementById('course_code').value.trim();
    const courseTitle = document.getElementById('course_title').value.trim();
    
    if (!courseCode) {
        e.preventDefault();
        Swal.fire('Error', 'Course code is required', 'error');
        return false;
    }
    
    if (!courseTitle) {
        e.preventDefault();
        Swal.fire('Error', 'Course title is required', 'error');
        return false;
    }
    
    // Show loading state
    const submitBtn = this.querySelector('button[type="submit"]');
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Saving...';
    submitBtn.disabled = true;
});

// Display success/error messages from session
@if(session('success'))
Swal.fire({
    icon: 'success',
    title: 'Success!',
    text: '{{ session('success') }}',
    timer: 3000,
    showConfirmButton: false
});
@endif

@if(session('error'))
Swal.fire({
    icon: 'error',
    title: 'Error!',
    text: '{{ session('error') }}',
    timer: 3000,
    showConfirmButton: false
});
@endif

@if($errors->any())
Swal.fire({
    icon: 'error',
    title: 'Validation Error',
    html: '{!! implode('<br>', $errors->all()) !!}',
    confirmButtonColor: '#0f1b5c'
});
@endif

</script>

@endsection