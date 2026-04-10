@extends('layouts.app')

@section('title', 'Course Management System')

@section('styles')
<style>
    /* ===== MAIN CSS ===== */

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

    .main-container {
        position: fixed;
        top: 80px;
        left: 270px;
        width: calc(100% - 290px);
        bottom: 20px;
        background: #ffffff;
        padding: 20px;
        border-radius: 12px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
        display: flex;
        flex-direction: column;
        overflow: hidden;
        transition: left 0.3s ease, width 0.3s ease;
    }

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

    /* PAGE TITLE */
    .page-title {
        font-size: 20px;
        font-weight: 600;
        color: #0f1b5c;
    }

    /* BUTTON GROUP */
    .button-group {
        display: flex;
        gap: 10px;
        align-items: center;
    }

    .import-btn {
        background: #198754;
    }

    .import-btn:hover {
        background: #157347;
    }

    /* TABLE */
    .table-container {
        flex: 1;
        margin-top: 15px;
        border-radius: 10px;
        overflow-x: auto;
        overflow-y: auto;
    }

    table {
        width: 100%;
        border-collapse: collapse;
    }

    thead th {
        position: sticky;
        top: 0;
        z-index: 100;
        background: #0f1b5c;
        color: #fff;
        padding: 14px 20px;
        font-weight: 600;
        white-space: nowrap;
    }

    thead th:nth-child(1) {
        width: 15%;
        min-width: 120px;
    }

    thead th:nth-child(2) {
        width: 25%;
        min-width: 200px;
    }

    thead th:nth-child(3) {
        width: 45%;
        min-width: 350px;
    }

    thead th:nth-child(4) {
        width: 15%;
        min-width: 100px;
    }

    tbody td {
        padding: 14px 20px;
        border-bottom: 1px solid #e0e0e0;
        vertical-align: top;
    }

    tbody tr:hover {
        background-color: #f5f7fb;
    }

    tbody td:nth-child(1) {
        white-space: nowrap;
    }

    tbody td:nth-child(2) {
        white-space: nowrap;
    }

    tbody td:nth-child(3) {
        white-space: normal;
        word-wrap: break-word;
        word-break: break-word;
        line-height: 1.6;
    }

    tbody td:nth-child(4) {
        white-space: nowrap;
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
        transition: all 0.2s ease;
    }

    .edit-icon:hover {
        color: #1a2a7a;
        transform: scale(1.1);
    }

    .delete-icon {
        color: #dc3545;
        cursor: pointer;
        font-size: 18px;
        transition: all 0.2s ease;
    }

    .delete-icon:hover {
        color: #c82333;
        transform: scale(1.1);
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
        background: rgba(0, 0, 0, 0.6);
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
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.25);
    }

    .import-modal-content {
        background: #fff;
        width: 450px;
        padding: 30px;
        border-radius: 16px;
        position: relative;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.25);
    }

    /* CLOSE BUTTON */
    .close {
        position: absolute;
        right: 20px;
        top: 15px;
        cursor: pointer;
        font-size: 26px;
        transition: color 0.2s;
        color: #999;
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

    /* IMPORT MODAL TITLE */
    .import-modal-content h3 {
        font-size: 20px;
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
    input,
    textarea {
        width: 100%;
        padding: 10px 12px;
        border-radius: 6px;
        border: 1px solid #d0d0d0;
        outline: none;
        transition: 0.2s;
        font-size: 14px;
    }

    input:focus,
    textarea:focus {
        border-color: #0f1b5c;
        box-shadow: 0 0 0 2px rgba(15, 27, 92, 0.1);
    }

    input.error,
    textarea.error {
        border-color: #dc3545;
        background-color: #fff8f8;
    }

    /* FIELD ERROR */
    .field-error {
        color: #dc3545;
        font-size: 12px;
        margin-top: 5px;
        display: none;
    }

    /* HELP TEXT */
    .help-text {
        color: #666;
        font-size: 11px;
        margin-top: 4px;
        display: block;
    }

    textarea {
        resize: vertical;
        min-height: 100px;
    }

    /* BUTTON GROUP */
    .button-group {
        display: flex;
        justify-content: flex-end;
        gap: 15px;
        margin-top: 20px;
    }

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

    /* IMPORT MODAL STYLES */
    .import-modal-content p {
        color: #666;
        margin-bottom: 15px;
        font-size: 13px;
        line-height: 1.5;
    }

    .import-modal-content input[type="file"] {
        border: 1px solid #d0d0d0;
        padding: 10px;
        border-radius: 6px;
        width: 100%;
    }

    .import-modal-content .submit-btn {
        width: 100%;
        margin-top: 15px;
    }

    /* EMPTY STATE */
    .empty-state {
        text-align: center;
        padding: 60px;
        color: #999;
        font-size: 16px;
    }

    .empty-state i {
        font-size: 48px;
        margin-bottom: 15px;
        opacity: 0.5;
    }

    /* SCROLLBAR */
    .table-container::-webkit-scrollbar {
        width: 6px;
        height: 6px;
    }

    .table-container::-webkit-scrollbar-thumb {
        background: #0f1b5c;
        border-radius: 10px;
    }

    .table-container::-webkit-scrollbar-track {
        background: #f1f1f1;
    }

    /* Loading Spinner */
    .spinner {
        display: inline-block;
        width: 16px;
        height: 16px;
        border: 2px solid #fff;
        border-radius: 50%;
        border-top-color: transparent;
        animation: spin 0.6s linear infinite;
        margin-right: 8px;
    }

    @keyframes spin {
        to {
            transform: rotate(360deg);
        }
    }

    /* RESPONSIVE */
    @media (max-width: 768px) {
        .main-container {
            left: 0 !important;
            width: 100% !important;
            top: 60px;
            border-radius: 0;
        }

        .modal-content,
        .import-modal-content {
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
    }
</style>
@endsection

@section('content')

<meta name="csrf-token" content="{{ csrf_token() }}">

<div class="main-container">

    <h2 class="page-title">Course Management</h2>

    <div class="top-bar">
        <div></div>
        <div class="button-group">
            <button class="add-btn" id="addCourseBtn">
                <i class="fas fa-plus"></i> Add Course
            </button>
            <button class="add-btn import-btn" id="importCourseBtn">
                <i class="fas fa-upload"></i> Upload Excel
            </button>
        </div>
    </div>

    <div class="table-container">
        @if(isset($courses) && count($courses) > 0)
        <table>
            <thead>
                <tr>
                    <th>Course Code</th>
                    <th>Course Title</th>
                    <th>Description</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody id="coursesTableBody">
                @foreach($courses as $course)
                <tr id="course-row-{{ $course->id }}">
                    <td><strong>{{ $course->course_code }}</strong></td>
                    <td>{{ $course->course_title }}</td>
                    <td>{{ $course->description ?? 'No description provided' }}</td>
                    <td>
                        <div class="action-icons">
                            <i class="fa-solid fa-pen-to-square edit-icon" data-id="{{ $course->id }}" style="cursor: pointer;"></i>
                            <i class="fa-solid fa-trash delete-icon" data-id="{{ $course->id }}" style="cursor: pointer;"></i>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @else
        <div class="empty-state">
            <i class="fas fa-book-open"></i>
            <p>No courses found. Click "Add Course" to get started or upload courses via Excel.</p>
        </div>
        @endif
    </div>

</div>

<!-- ADD / EDIT MODAL -->
<div class="modal" id="courseModal">
    <div class="modal-content">
        <span class="close" id="closeModalBtn">&times;</span>
        <div class="form-title" id="modalTitle">Add Course</div>

        <form id="courseForm">
            @csrf
            <input type="hidden" name="_method" id="formMethod" value="POST">
            <input type="hidden" name="course_id" id="course_id">

            <div class="form-grid">
                <div class="form-group">
                    <label>Course Code *</label>
                    <input type="text" name="course_code" id="course_code" placeholder="e.g., CSC-101">
                    <small class="help-text">Format: 3 letters, hyphen, 3 digits (e.g., CSC-101)</small>
                    <span class="field-error" id="course_codeError"></span>
                </div>

                <div class="form-group">
                    <label>Course Title *</label>
                    <input type="text" name="course_title" id="course_title" placeholder="e.g., Introduction to Programming">
                    <span class="field-error" id="course_titleError"></span>
                </div>

                <div class="form-group full-width">
                    <label>Description (Optional)</label>
                    <textarea name="description" id="description" placeholder="Enter course description here..." rows="4"></textarea>
                    <span class="field-error" id="descriptionError"></span>
                </div>
            </div>

            <div class="button-group">
                <button type="button" class="cancel-btn" id="cancelBtn">Cancel</button>
                <button type="button" class="submit-btn" id="saveCourseBtn">Save Course</button>
            </div>
        </form>
    </div>
</div>

<!-- IMPORT MODAL -->
<div class="modal" id="importModal">
    <div class="import-modal-content">
        <span class="close" id="closeImportBtn">&times;</span>
        <h3><i class="fas fa-file-excel"></i> Upload Excel File</h3>

        <p>
            Upload an Excel file (.xlsx, .xls, .csv) with columns:<br>
            <strong>course_code, course_title, description</strong>
        </p>

        <form id="importForm" enctype="multipart/form-data">
            @csrf
            <input type="file" name="file" id="importFile" accept=".xlsx,.xls,.csv" required>
            <button type="submit" class="submit-btn" id="importSubmitBtn">
                <i class="fas fa-upload"></i> Upload File
            </button>
        </form>
    </div>
</div>

@endsection

@section('scripts')

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    // CSRF Token setup
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    // Get elements
    const courseModal = document.getElementById('courseModal');
    const importModal = document.getElementById('importModal');
    const addBtn = document.getElementById('addCourseBtn');
    const importBtn = document.getElementById('importCourseBtn');
    const closeModalBtn = document.getElementById('closeModalBtn');
    const closeImportBtn = document.getElementById('closeImportBtn');
    const cancelBtn = document.getElementById('cancelBtn');
    const saveCourseBtn = document.getElementById('saveCourseBtn');
    const modalTitle = document.getElementById('modalTitle');
    const courseForm = document.getElementById('courseForm');
    const formMethod = document.getElementById('formMethod');
    const courseId = document.getElementById('course_id');
    const courseCode = document.getElementById('course_code');
    const courseTitle = document.getElementById('course_title');
    const description = document.getElementById('description');

    // ==================== CLEAR VALIDATION ====================

    function clearValidationMessages() {
        document.querySelectorAll('.field-error').forEach(el => {
            el.style.display = 'none';
            el.textContent = '';
        });
        document.querySelectorAll('input, textarea').forEach(el => {
            el.classList.remove('error');
        });
    }

    function showFieldError(field, message) {
        const errorSpan = document.getElementById(field + 'Error');
        const inputField = document.getElementById(field);
        if (errorSpan) {
            errorSpan.textContent = message;
            errorSpan.style.display = 'block';
        }
        if (inputField) {
            inputField.classList.add('error');
        }
    }

    // ==================== VALIDATE FORM ====================

    function validateForm() {
        let isValid = true;
        clearValidationMessages();

        const code = courseCode.value.trim();
        const title = courseTitle.value.trim();

        if (!code) {
            showFieldError('course_code', 'Course code is required');
            isValid = false;
        } else if (!/^[A-Za-z]{3}-[0-9]{3}$/.test(code)) {
            showFieldError('course_code', 'Course code must be in format: ABC-123 (3 letters, hyphen, 3 digits)');
            isValid = false;
        }

        if (!title) {
            showFieldError('course_title', 'Course title is required');
            isValid = false;
        }

        return isValid;
    }

    // ==================== MODAL FUNCTIONS ====================

    function openModal() {
        courseForm.reset();
        formMethod.value = "POST";
        modalTitle.textContent = "Add Course";
        courseId.value = '';
        clearValidationMessages();
        courseModal.style.display = 'flex';
    }

    function closeModal() {
        courseModal.style.display = 'none';
        clearValidationMessages();
    }

    function openImportModal() {
        importModal.style.display = 'flex';
    }

    function closeImportModal() {
        importModal.style.display = 'none';
        document.getElementById('importForm').reset();
    }

    // Event listeners
    addBtn.addEventListener('click', openModal);
    importBtn.addEventListener('click', openImportModal);
    closeModalBtn.addEventListener('click', closeModal);
    cancelBtn.addEventListener('click', closeModal);
    closeImportBtn.addEventListener('click', closeImportModal);

    // Close modal when clicking outside
    window.addEventListener('click', function(event) {
        if (event.target === courseModal) closeModal();
        if (event.target === importModal) closeImportModal();
    });

    // ==================== SAVE COURSE ====================

    async function saveCourse() {
        if (!validateForm()) return;

        const method = formMethod.value;
        const id = courseId.value;

        const formData = new FormData();
        formData.append('_token', csrfToken);
        formData.append('course_code', courseCode.value.trim().toUpperCase());
        formData.append('course_title', courseTitle.value.trim());
        formData.append('description', description.value.trim());

        let url = "{{ route('courses.store') }}";

        if (method === 'PUT' && id) {
            formData.append('_method', 'PUT');
            url = "/courses/" + id;
        }

        saveCourseBtn.disabled = true;
        saveCourseBtn.innerHTML = '<span class="spinner"></span> Saving...';

        try {
            const response = await fetch(url, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });

            const data = await response.json();

            if (response.ok && data.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: data.message || 'Course saved successfully',
                    timer: 2000,
                    showConfirmButton: false
                });
                closeModal();
                setTimeout(() => {
                    window.location.reload();
                }, 2000);
            } else {
                if (data.errors) {
                    clearValidationMessages();
                    for (let field in data.errors) {
                        showFieldError(field, data.errors[field][0]);
                    }
                } else {
                    Swal.fire('Error!', data.message || 'Error saving course', 'error');
                }
                saveCourseBtn.disabled = false;
                saveCourseBtn.innerHTML = 'Save Course';
            }
        } catch (error) {
            console.error('Error:', error);
            Swal.fire('Error!', 'Network error. Please try again.', 'error');
            saveCourseBtn.disabled = false;
            saveCourseBtn.innerHTML = 'Save Course';
        }
    }

    saveCourseBtn.addEventListener('click', saveCourse);

    // ==================== EDIT COURSE ====================

    async function editCourse(id) {
        Swal.fire({
            title: 'Loading...',
            text: 'Please wait while we fetch course details',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        try {
            const response = await fetch("/courses/" + id + "/edit", {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            });

            const data = await response.json();
            Swal.close();

            if (response.ok && data.success !== false) {
                modalTitle.textContent = "Edit Course";
                formMethod.value = "PUT";
                courseId.value = data.id;
                courseCode.value = data.course_code;
                courseTitle.value = data.course_title;
                description.value = data.description || '';
                clearValidationMessages();
                courseModal.style.display = 'flex';
            } else {
                Swal.fire('Error', data.message || 'Could not fetch course details', 'error');
            }
        } catch (error) {
            Swal.close();
            console.error('Error:', error);
            Swal.fire('Error', 'Network error. Could not fetch course details.', 'error');
        }
    }

    // ==================== DELETE COURSE ====================

    async function deleteCourse(id) {
        const result = await Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Yes, delete it!'
        });

        if (result.isConfirmed) {
            try {
                const response = await fetch("/courses/" + id, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });

                const data = await response.json();

                if (response.ok && data.success) {
                    const row = document.getElementById('course-row-' + id);
                    if (row) row.remove();

                    Swal.fire({
                        icon: 'success',
                        title: 'Deleted!',
                        text: data.message || 'Course has been deleted',
                        timer: 2000,
                        showConfirmButton: false
                    });
                } else {
                    Swal.fire('Error!', data.message || 'Could not delete course', 'error');
                }
            } catch (error) {
                console.error('Error:', error);
                Swal.fire('Error!', 'Network error. Please try again.', 'error');
            }
        }
    }

    // ==================== IMPORT EXCEL ====================

    document.getElementById('importForm')?.addEventListener('submit', async function(e) {
        e.preventDefault();

        const fileInput = document.getElementById('importFile');
        const file = fileInput.files[0];

        if (!file) {
            Swal.fire('Error', 'Please select a file to upload', 'error');
            return;
        }

        const allowedExtensions = ['.xlsx', '.xls', '.csv'];
        const fileExt = file.name.substring(file.name.lastIndexOf('.')).toLowerCase();

        if (!allowedExtensions.includes(fileExt)) {
            Swal.fire('Error', 'Invalid file type. Please upload .xlsx, .xls, or .csv files only.', 'error');
            return;
        }

        const formData = new FormData();
        formData.append('_token', csrfToken);
        formData.append('file', file);

        const submitBtn = document.getElementById('importSubmitBtn');
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<span class="spinner"></span> Uploading...';

        try {
            const response = await fetch("{{ route('courses.import') }}", {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });

            const data = await response.json();

            if (response.ok && data.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: data.message || 'Courses imported successfully',
                    timer: 2000,
                    showConfirmButton: false
                });
                closeImportModal();
                setTimeout(() => {
                    window.location.reload();
                }, 2000);
            } else {
                Swal.fire('Error!', data.message || 'Error uploading file', 'error');
                submitBtn.disabled = false;
                submitBtn.innerHTML = '<i class="fas fa-upload"></i> Upload File';
            }
        } catch (error) {
            console.error('Error:', error);
            Swal.fire('Error!', 'Network error. Please try again.', 'error');
            submitBtn.disabled = false;
            submitBtn.innerHTML = '<i class="fas fa-upload"></i> Upload File';
        }
    });

    // ==================== ATTACH EDIT/DELETE EVENTS ====================

    function attachIconEvents() {
        document.querySelectorAll('.edit-icon').forEach(icon => {
            icon.removeEventListener('click', icon.clickHandler);
            const id = icon.getAttribute('data-id');
            icon.clickHandler = (e) => {
                e.stopPropagation();
                editCourse(id);
            };
            icon.addEventListener('click', icon.clickHandler);
        });

        document.querySelectorAll('.delete-icon').forEach(icon => {
            icon.removeEventListener('click', icon.clickHandler);
            const id = icon.getAttribute('data-id');
            icon.clickHandler = (e) => {
                e.stopPropagation();
                deleteCourse(id);
            };
            icon.addEventListener('click', icon.clickHandler);
        });
    }

    attachIconEvents();

    // Enter key submit
    courseForm.addEventListener('keypress', function(e) {
        if (e.key === 'Enter' && !e.target.matches('textarea')) {
            e.preventDefault();
            saveCourse();
        }
    });

    // Display session messages
    @if(session('success'))
    Swal.fire({
        title: 'Success!',
        text: '{{ session('
        success ') }}',
        icon: 'success',
        timer: 3000,
        showConfirmButton: true
    });
    @endif

    @if(session('error'))
    Swal.fire({
        title: 'Error!',
        text: '{{ session('
        error ') }}',
        icon: 'error',
        confirmButtonColor: '#d33'
    });
    @endif

    document.querySelectorAll('.nav-link').forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault(); // prevent full page reload

            const routeName = this.dataset.route; // from data-route
            const url = this.getAttribute('href'); // actual url

            loadPage(url, routeName); // pass routeName to loadPage
        });
    });
</script>

@endsection