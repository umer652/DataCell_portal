@extends('layouts.app')

@section('title', 'Generate Transcript')

@section('styles')
<style>

/* ADD BUTTON - Same style as teachers */
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

/* Search and Filter Bar */
.filter-bar {
    display: flex;
    gap: 15px;
    margin-bottom: 20px;
    flex-wrap: wrap;
}

.search-box {
    flex: 1;
    position: relative;
}

.search-box input {
    width: 100%;
    padding: 10px 15px;
    padding-left: 40px;
    border: 1px solid #ddd;
    border-radius: 8px;
    font-size: 14px;
    transition: all 0.3s ease;
}

.search-box input:focus {
    outline: none;
    border-color: #0f1b5c;
    box-shadow: 0 0 0 2px rgba(15,27,92,0.1);
}

.search-box i {
    position: absolute;
    left: 15px;
    top: 50%;
    transform: translateY(-50%);
    color: #999;
}

.filter-select {
    min-width: 200px;
    padding: 10px 15px;
    border: 1px solid #ddd;
    border-radius: 8px;
    font-size: 14px;
    background: white;
    cursor: pointer;
}

.filter-select:focus {
    outline: none;
    border-color: #0f1b5c;
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
    cursor: pointer;
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

.view-btn {
    background: #0f1b5c;
    color: #fff;
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

.view-btn:hover {
    background: #1a2a7a;
    transform: translateY(-1px);
}

/* BADGE */
.badge {
    display: inline-block;
    padding: 4px 10px;
    border-radius: 12px;
    font-size: 12px;
    font-weight: 500;
}

.badge-primary {
    background: #e3f2fd;
    color: #0f1b5c;
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

/* RESPONSIVE */
@media (max-width: 768px) {
    .main-container {
        left: 0 !important;
        width: 100% !important;
        top: 60px;
        border-radius: 0;
    }
    
    .filter-bar {
        flex-direction: column;
    }
    
    .filter-select {
        width: 100%;
    }
    
    thead th,
    tbody td {
        padding: 8px 12px;
        font-size: 14px;
    }
    
    .action-buttons {
        flex-direction: column;
        gap: 5px;
    }
}

/* STATS CARDS */
.stats-container {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 15px;
    margin-bottom: 20px;
}

.stat-card {
    background:#0f1b5c;
    padding: 15px;
    border-radius: 10px;
    color: white;
    text-align: center;
}

.stat-card h3 {
    font-size: 24px;
    margin: 0;
    font-weight: 700;
}

.stat-card p {
    margin: 5px 0 0;
    font-size: 13px;
    opacity: 0.9;
}

</style>
@endsection

@section('content')

<meta name="csrf-token" content="{{ csrf_token() }}">

<div class="main-container">

    <!-- TOP BAR -->
    <div class="top-bar">
        <h2><i class="fas fa-print"></i> Generate Transcript</h2>
        <button class="add-btn" id="refreshBtn">
            <i class="fas fa-sync-alt"></i> Refresh List
        </button>
    </div>

    <!-- Stats Cards -->
    <div class="stats-container">
        <div class="stat-card">
            <h3>{{ $students->count() }}</h3>
            <p>Total Students</p>
        </div>
        <div class="stat-card">
            <h3>{{ $students->where('semester', '>=', 1)->count() }}</h3>
            <p>Active Students</p>
        </div>
        <div class="stat-card">
            <h3>{{ $students->groupBy('program_id')->count() }}</h3>
            <p>Programs</p>
        </div>
    </div>

    <!-- Search and Filter Bar -->
    <div class="filter-bar">
        <div class="search-box">
            <i class="fas fa-search"></i>
            <input type="text" id="searchInput" placeholder="Search by Roll No, Name or Father Name...">
        </div>
        <select id="programFilter" class="filter-select">
            <option value="">All Programs</option>
            @php
                $programs = App\Models\Program::all();
            @endphp
            @foreach($programs as $program)
                <option value="{{ $program->id }}">{{ $program->name }}</option>
            @endforeach
        </select>
        <select id="semesterFilter" class="filter-select">
            <option value="">All Semesters</option>
            @for($i = 1; $i <= 8; $i++)
                <option value="{{ $i }}">Semester {{ $i }}</option>
            @endfor
        </select>
    </div>

    <!-- TABLE -->
    <div class="table-container">
        @if(count($students) > 0)
        <table id="studentTable">
            <thead>
                <tr>
                    <th style="width: 5%">#</th>
                    <th style="width: 15%">Roll No</th>
                    <th style="width: 20%">Name</th>
                    <th style="width: 20%">Father Name</th>
                    <th style="width: 25%">Program</th>
                    <th style="width: 10%">Semester</th>
                    <th style="width: 10%">Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($students as $index => $student)
                <tr data-program="{{ $student->program_id }}" data-semester="{{ $student->semester }}">
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>
                        <strong>{{ $student->roll_no }}</strong>
                        <br>
                        <small class="text-muted">ID: {{ $student->id }}</small>
                    </td>
                    <td>{{ $student->name }}</td>
                    <td>{{ $student->father_name }}</td>
                    <td>{{ $student->program->name ?? 'N/A' }}</td>
                    <td class="text-center">
                        <span class="badge badge-primary">Semester {{ $student->semester }}</span>
                    </td>
                    <td class="text-center">
                        <div class="action-buttons">
                            <button class="view-btn" data-id="{{ $student->id }}">
                                <i class="fas fa-eye"></i> View Transcript
                            </button>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @else
        <div class="empty-state">
            <i class="fas fa-users"></i>
            <p>No students found. Add students to generate transcripts.</p>
        </div>
        @endif
    </div>
</div>

@endsection

@section('scripts')
<script>
// Transcript Management Module
function initTranscriptModule() {
    console.log('Initializing Transcript Module...');
    
    // Get elements
    const refreshBtn = document.getElementById('refreshBtn');
    const searchInput = document.getElementById('searchInput');
    const programFilter = document.getElementById('programFilter');
    const semesterFilter = document.getElementById('semesterFilter');
    const table = document.getElementById('studentTable');
    
    // Refresh button
    if (refreshBtn) {
        refreshBtn.addEventListener('click', function() {
            window.location.reload();
        });
    }
    
    // Filter function
    function filterTable() {
        const searchTerm = searchInput ? searchInput.value.toLowerCase() : '';
        const programValue = programFilter ? programFilter.value : '';
        const semesterValue = semesterFilter ? semesterFilter.value : '';
        
        if (!table) return;
        
        const rows = table.querySelectorAll('tbody tr');
        let visibleCount = 0;
        
        rows.forEach(row => {
            const rollNo = row.cells[1]?.textContent.toLowerCase() || '';
            const name = row.cells[2]?.textContent.toLowerCase() || '';
            const fatherName = row.cells[3]?.textContent.toLowerCase() || '';
            const program = row.getAttribute('data-program') || '';
            const semester = row.getAttribute('data-semester') || '';
            
            const matchesSearch = searchTerm === '' || 
                rollNo.includes(searchTerm) || 
                name.includes(searchTerm) || 
                fatherName.includes(searchTerm);
            
            const matchesProgram = programValue === '' || program === programValue;
            const matchesSemester = semesterValue === '' || semester === semesterValue;
            
            if (matchesSearch && matchesProgram && matchesSemester) {
                row.style.display = '';
                visibleCount++;
            } else {
                row.style.display = 'none';
            }
        });
        
        // Show/hide no results message
        let noResultsMsg = document.getElementById('noResultsMsg');
        if (visibleCount === 0) {
            if (!noResultsMsg) {
                noResultsMsg = document.createElement('tr');
                noResultsMsg.id = 'noResultsMsg';
                noResultsMsg.innerHTML = '<td colspan="7" style="text-align: center; padding: 40px;"><i class="fas fa-search"></i> No students found matching your filters</td>';
                if (table && table.querySelector('tbody')) {
                    table.querySelector('tbody').appendChild(noResultsMsg);
                }
            }
            noResultsMsg.style.display = '';
        } else if (noResultsMsg) {
            noResultsMsg.style.display = 'none';
        }
    }
    
    // Add event listeners for filters
    if (searchInput) searchInput.addEventListener('keyup', filterTable);
    if (programFilter) programFilter.addEventListener('change', filterTable);
    if (semesterFilter) semesterFilter.addEventListener('change', filterTable);
    
    // View Transcript buttons
    document.querySelectorAll('.view-btn').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.stopPropagation();
            const studentId = this.getAttribute('data-id');
            if (studentId) {
                window.location.href = "/admin/transcript/" + studentId;
            }
        });
    });
    
    // Make rows clickable
    if (table) {
        const rows = table.querySelectorAll('tbody tr');
        rows.forEach(row => {
            row.addEventListener('click', function(e) {
                // Don't trigger if clicking on the action button
                if (e.target.closest('.view-btn')) return;
                const viewBtn = this.querySelector('.view-btn');
                if (viewBtn) {
                    const studentId = viewBtn.getAttribute('data-id');
                    if (studentId) {
                        window.location.href = "/admin/transcript/" + studentId;
                    }
                }
            });
            row.style.cursor = 'pointer';
        });
    }
    
    console.log('Transcript Module Initialized Successfully');
}

// Initialize when DOM is ready
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', function() {
        initTranscriptModule();
    });
} else {
    initTranscriptModule();
}

// Export to global scope
window.initTranscriptModule = initTranscriptModule;
</script>
@endsection