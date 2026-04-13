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
    overflow-y: hidden;
    display: flex;
    flex-direction: column;
}

.top-bar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-shrink: 0;
}

.page-title {
    font-size: 20px;
    font-weight: 600;
    color: #0f1b5c;
    margin-bottom: 10px;
}

.add-btn {
    background: #0f1b5c;
    color: white;
    padding: 10px 20px;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    transition: all 0.3s;
}
.add-btn:hover {
    background: #0c1445;
    transform: translateY(-1px);
}

/* FILTER BAR */
.search-box {
    margin: 15px 0;
    display: flex;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 10px;
    flex-shrink: 0;
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
    cursor: pointer;
}
.reset-btn:hover {
    background: #666;
}

/* TABLE CONTAINER - SCROLLABLE */
.table-container {
    flex: 1;
    overflow-y: auto;
    border: 1px solid #ddd;
    border-radius: 8px;
}

/* TABLE */
table {
    width: 100%;
    border-collapse: collapse;
}

thead {
    background: #0f1b5c;
    color: white;
    position: sticky;
    top: 0;
    z-index: 10;
}

td, th {
    padding: 12px;
    border: 1px solid #ddd;
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
    justify-content: center;
    align-items: center;
    z-index: 1000;
}

.modal-content {
    background: #fff;
    width: 60%;
    padding: 25px;
    border-radius: 12px;
    max-height: 85vh;
    overflow-y: auto;
}

.form-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 15px;
}

.full {
    grid-column: 1 / -1;
}

.form-group {
    display: flex;
    flex-direction: column;
    gap: 5px;
}

.form-group label {
    font-weight: 600;
    font-size: 13px;
    color: #333;
}

.form-group select,
.form-group input {
    padding: 10px;
    border-radius: 8px;
    border: 1px solid #ddd;
}

.close {
    float: right;
    font-size: 28px;
    cursor: pointer;
    color: #999;
}

.close:hover {
    color: #000;
}

/* TOAST MESSAGES */
.toast-container {
    position: fixed;
    top: 20px;
    right: 20px;
    z-index: 10000;
    display: flex;
    flex-direction: column;
    gap: 10px;
}

.toast {
    min-width: 300px;
    padding: 12px 20px;
    border-radius: 8px;
    animation: slideIn 0.3s ease;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
}

.toast-success {
    background: #d4edda;
    color: #155724;
    border-left: 4px solid #28a745;
}

.toast-error {
    background: #f8d7da;
    color: #721c24;
    border-left: 4px solid #dc3545;
}

.toast-info {
    background: #d1ecf1;
    color: #0c5460;
    border-left: 4px solid #17a2b8;
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
</style>
@endsection

@section('content')

<div class="main-container">
    <div class="top-bar">
        <h2 class="page-title">Enrollment Management</h2>
        <button class="add-btn" onclick="window.openModal()">+ Add Enrollment</button>
    </div>

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
            <button class="reset-btn" onclick="window.resetFilters()">Reset</button>
        </div>
    </div>

    <div class="table-container">
        <table id="allocationsTable">
            <thead>
                <tr>
                    <th>Student</th>
                    <th>Course</th>
                    <th>Program</th>
                    <th>Session</th>
                    <th>Semester</th>
                    <th>Section</th>
                    <th>Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody id="enrollmentBody">
                <tr><td colspan="8" style="text-align: center;">Loading data...</td></tr>
            </tbody>
        </table>
    </div>
</div>

<!-- MODAL -->
<div class="modal" id="modal">
<div class="modal-content">
    <span class="close" onclick="window.closeModal()">&times;</span>
    <h3 id="modalTitle">Add Enrollment</h3>

    <form id="enrollmentForm">
        @csrf
        <input type="hidden" name="_method" id="methodField" value="POST">
        <input type="hidden" name="enrollment_id" id="enrollment_id">
        <input type="hidden" name="student_id_for_update" id="student_id_for_update">

        <div class="form-grid">
            <div class="form-group">
                <label>Student *</label>
                <select name="student_id" id="student_id" required>
                    <option value="">Select Student</option>
                    @foreach($students as $s)
                        <option value="{{ $s->id }}">{{ $s->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label>Program *</label>
                <select name="program_id" id="program_id" required>
                    <option value="">Select Program</option>
                    @foreach($programs as $p)
                        <option value="{{ $p->id }}">{{ $p->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label>Session *</label>
                <select name="session_id" id="session_id" required>
                    <option value="">Select Session</option>
                    @foreach($sessions as $s)
                        <option value="{{ $s->id }}">{{ $s->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label>Section *</label>
                <select name="section_id" id="section_id" required>
                    <option value="">Select Section</option>
                    @foreach($sections as $s)
                        <option value="{{ $s->id }}">{{ $s->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label>Semester *</label>
                <input type="number" name="semester" id="semester" placeholder="Semester" required>
            </div>

            <div class="form-group">
                <label>Enrollment Date *</label>
                <input type="date" name="enrollment_date" id="enrollment_date" required>
            </div>

            <div class="form-group full">
                <label>Courses *</label>
                <select name="offered_course_id[]" id="coursesDropdown" multiple class="full" required>
                    <option value="">Select Program and Semester First</option>
                </select>
                <small>Hold Ctrl/Cmd to select multiple courses</small>
            </div>

            <div class="full">
                <button type="submit" class="add-btn" style="width:100%">Save Enrollment</button>
            </div>
        </div>
    </form>
</div>
</div>

<div class="toast-container" id="toastContainer"></div>

<script>
// Make sure all functions are attached to window
(function() {
    // Get CSRF token
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
    
    let currentEnrollmentId = null;
    
    // Show toast
    window.showToast = function(message, type = 'success', duration = 3000) {
        const container = document.getElementById('toastContainer');
        if (!container) return;
        
        const toast = document.createElement('div');
        toast.className = `toast toast-${type}`;
        toast.innerHTML = `
            <span class="toast-message">${message}</span>
            <span class="toast-close">&times;</span>
        `;
        
        container.appendChild(toast);
        
        const closeBtn = toast.querySelector('.toast-close');
        closeBtn.addEventListener('click', () => {
            toast.remove();
        });
        
        setTimeout(() => {
            if (toast.parentNode) toast.remove();
        }, duration);
    };
    
    // Fetch data function
    window.fetchData = function() {
        const searchInput = document.getElementById('searchInput');
        const sessionFilter = document.getElementById('sessionFilter');
        const semesterFilter = document.getElementById('semesterFilter');
        const programFilter = document.getElementById('programFilter');
        
        let url = window.location.pathname + '?';
        url += 'search=' + (searchInput ? encodeURIComponent(searchInput.value) : '');
        url += '&session_id=' + (sessionFilter ? encodeURIComponent(sessionFilter.value) : '');
        url += '&semester=' + (semesterFilter ? encodeURIComponent(semesterFilter.value) : '');
        url += '&program_id=' + (programFilter ? encodeURIComponent(programFilter.value) : '');
        
        fetch(url, {
            headers: { 
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'text/html'
            }
        })
        .then(res => res.text())
        .then(html => {
            const tbody = document.getElementById('enrollmentBody');
            if (tbody) {
                tbody.innerHTML = html;
            }
        })
        .catch(error => {
            console.error('Error:', error);
            const tbody = document.getElementById('enrollmentBody');
            if (tbody) {
                tbody.innerHTML = '<tr><td colspan="8" style="text-align: center; color: red;">Error loading data</td></tr>';
            }
        });
    };
    
    // Reset filters
    window.resetFilters = function() {
        const searchInput = document.getElementById('searchInput');
        const sessionFilter = document.getElementById('sessionFilter');
        const semesterFilter = document.getElementById('semesterFilter');
        const programFilter = document.getElementById('programFilter');
        
        if (searchInput) searchInput.value = '';
        if (sessionFilter) sessionFilter.value = '';
        if (semesterFilter) semesterFilter.value = '';
        if (programFilter) programFilter.value = '';
        
        window.fetchData();
        window.showToast('Filters reset successfully', 'info', 2000);
    };
    
    // Load courses
    window.loadCourses = function() {
        let programId = document.getElementById('program_id').value;
        let semester = document.getElementById('semester').value;
        let dropdown = document.getElementById('coursesDropdown');
        
        if (!programId || !semester) {
            dropdown.innerHTML = '<option value="">Select Program and Semester First</option>';
            return;
        }
        
        dropdown.innerHTML = '<option>Loading courses...</option>';
        
        fetch(`/get-offered-courses?program_id=${programId}&semester=${semester}`)
            .then(res => res.json())
            .then(data => {
                dropdown.innerHTML = '';
                if (data.length === 0) {
                    dropdown.innerHTML = '<option value="">No courses available</option>';
                } else {
                    data.forEach(c => {
                        let option = document.createElement('option');
                        option.value = c.id;
                        option.textContent = c.course.course_title;
                        dropdown.appendChild(option);
                    });
                }
            })
            .catch(error => {
                console.error('Error:', error);
                dropdown.innerHTML = '<option value="">Error loading courses</option>';
                window.showToast('Failed to load courses', 'error', 3000);
            });
    };
    
    // Handle form submission
    window.handleFormSubmit = function(e) {
        e.preventDefault();
        
        const form = e.target;
        const formData = new FormData(form);
        const data = Object.fromEntries(formData);
        
        const courseSelect = document.getElementById('coursesDropdown');
        const selectedOptions = Array.from(courseSelect.selectedOptions);
        
        if (selectedOptions.length === 0) {
            window.showToast('Please select at least one course', 'error', 3000);
            return;
        }
        
        data.offered_course_id = selectedOptions.map(option => option.value);
        
        let url = '/enrollments/store';
        let method = 'POST';
        
        if (currentEnrollmentId) {
            url = `/enrollments/${currentEnrollmentId}`;
            method = 'PUT';
        }
        
        const saveBtn = form.querySelector('button[type="submit"]');
        saveBtn.disabled = true;
        const originalText = saveBtn.textContent;
        saveBtn.textContent = 'Saving...';
        
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
                window.showToast(response.message, 'success', 3000);
                window.closeModal();
                window.fetchData();
                window.resetForm();
            } else {
                window.showToast(response.message || 'Error saving enrollment', 'error', 4000);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            window.showToast('An error occurred while saving', 'error', 4000);
        })
        .finally(() => {
            saveBtn.disabled = false;
            saveBtn.textContent = originalText;
        });
    };
    
    // Edit enrollment
    window.editEnrollment = function(id) {
        window.showToast('Loading enrollment details...', 'info', 2000);
        
        fetch(`/enrollments/${id}/edit`, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
        .then(res => res.json())
        .then(response => {
            if (response.success) {
                const data = response.data;
                currentEnrollmentId = id;
                
                document.getElementById('student_id').value = data.student_id;
                document.getElementById('program_id').value = data.program_id;
                document.getElementById('session_id').value = data.session_id;
                document.getElementById('section_id').value = data.section_id;
                document.getElementById('semester').value = data.semester;
                document.getElementById('enrollment_date').value = data.enrollment_date;
                
                document.getElementById('modalTitle').textContent = 'Edit Enrollment';
                window.openModal();
                
                // Load courses
                window.loadCourses();
                
                window.showToast('Enrollment loaded', 'success', 3000);
            } else {
                window.showToast(response.message || 'Failed to load enrollment', 'error', 4000);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            window.showToast('Failed to load enrollment details', 'error', 4000);
        });
    };
    
    // Delete enrollment
    window.deleteEnrollment = function(id, buttonElement) {
        if (confirm('Are you sure you want to delete this enrollment?')) {
            fetch(`/enrollments/${id}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'X-Requested-With': 'XMLHttpRequest',
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                }
            })
            .then(res => res.json())
            .then(response => {
                if (response.success) {
                    window.showToast(response.message, 'success', 3000);
                    window.fetchData();
                } else {
                    window.showToast(response.message || 'Error deleting enrollment', 'error', 4000);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                window.showToast('An error occurred while deleting', 'error', 4000);
            });
        }
    };
    
    // Open modal
    window.openModal = function() {
        document.getElementById('modal').style.display = 'flex';
        document.body.style.overflow = 'hidden';
    };
    
    // Close modal
    window.closeModal = function() {
        document.getElementById('modal').style.display = 'none';
        document.body.style.overflow = '';
        window.resetForm();
    };
    
    // Reset form
    window.resetForm = function() {
        document.getElementById('enrollmentForm').reset();
        currentEnrollmentId = null;
        document.getElementById('modalTitle').textContent = 'Add Enrollment';
        const dropdown = document.getElementById('coursesDropdown');
        dropdown.innerHTML = '<option value="">Select Program and Semester First</option>';
    };
    
    // Initialize everything when DOM is ready
    function init() {
        console.log('Initializing enrollment page...');
        
        // Set up event listeners
        const form = document.getElementById('enrollmentForm');
        if (form) {
            form.removeEventListener('submit', window.handleFormSubmit);
            form.addEventListener('submit', window.handleFormSubmit);
        }
        
        const programSelect = document.getElementById('program_id');
        if (programSelect) {
            programSelect.removeEventListener('change', window.loadCourses);
            programSelect.addEventListener('change', window.loadCourses);
        }
        
        const semesterInput = document.getElementById('semester');
        if (semesterInput) {
            semesterInput.removeEventListener('input', window.loadCourses);
            semesterInput.addEventListener('input', window.loadCourses);
        }
        
        const searchInput = document.getElementById('searchInput');
        if (searchInput) {
            searchInput.removeEventListener('keyup', window.fetchData);
            searchInput.addEventListener('keyup', window.fetchData);
        }
        
        const sessionFilter = document.getElementById('sessionFilter');
        if (sessionFilter) {
            sessionFilter.removeEventListener('change', window.fetchData);
            sessionFilter.addEventListener('change', window.fetchData);
        }
        
        const semesterFilter = document.getElementById('semesterFilter');
        if (semesterFilter) {
            semesterFilter.removeEventListener('change', window.fetchData);
            semesterFilter.addEventListener('change', window.fetchData);
        }
        
        const programFilter = document.getElementById('programFilter');
        if (programFilter) {
            programFilter.removeEventListener('change', window.fetchData);
            programFilter.addEventListener('change', window.fetchData);
        }
        
        // Load data
        window.fetchData();
    }
    
    // Run init when DOM is ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }
    
    // Handle modal outside click
    window.onclick = function(event) {
        const modal = document.getElementById('modal');
        if (event.target === modal) {
            window.closeModal();
        }
    };
})();
</script>

@endsection