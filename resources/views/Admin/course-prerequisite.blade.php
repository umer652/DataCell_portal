@extends('layouts.app')

@section('title', 'Scheme of Study')

@section('content')

<meta name="csrf-token" content="{{ csrf_token() }}">

<style>
    /* ========================================
       CUSTOM CSS FOR COURSE PREREQUISITE PAGE
    ========================================== */
    
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }
    
    /* Main Container */
    .prerequisite-container {
        position: fixed;
        top: 80px;
        left: 270px;
        right: 20px;
        bottom: 20px;
        background: white;
        padding: 20px;
        border-radius: 12px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        display: flex;
        flex-direction: column;
        overflow: hidden;
        transition: all 0.3s ease;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }
    
    /* Center Dropdown */
    .center-dropdown {
        position: absolute;
        left: 50%;
        transform: translateX(-50%);
        top: 20px;
        z-index: 10;
    }
    
    .scheme-select {
        background: #0f1b5c;
        color: white;
        padding: 8px 20px;
        border-radius: 8px;
        border: none;
        cursor: pointer;
        font-size: 14px;
        font-weight: 500;
        transition: background 0.3s;
    }
    
    .scheme-select:hover {
        background: #1a2a7a;
    }
    
    .scheme-select:focus {
        outline: none;
        outline: 2px solid #3b82f6;
    }
    
    /* Top Bar */
    .top-bar {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 20px;
        margin-top: 10px;
    }
    
    .page-title {
        font-size: 22px;
        font-weight: 600;
        color: #1e293b;
        margin: 0;
    }
    
    .btn-primary {
        background: #0f1b5c;
        color: white;
        padding: 10px 20px;
        border-radius: 8px;
        border: none;
        cursor: pointer;
        font-size: 14px;
        font-weight: 500;
        transition: all 0.3s;
    }
    
    .btn-primary:hover {
        background: #1a2a7a;
        transform: translateY(-1px);
        box-shadow: 0 2px 8px rgba(15, 27, 92, 0.3);
    }
    
    /* Search Box */
    .search-wrapper {
        width: 100%;
        position: relative;
        margin-bottom: 16px;
    }
    
    .global-search {
        width: 100%;
        padding: 10px 16px;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        font-size: 14px;
        transition: all 0.3s;
    }
    
    .global-search:focus {
        outline: none;
        border-color: #0f1b5c;
        box-shadow: 0 0 0 3px rgba(15, 27, 92, 0.1);
    }
    
    .search-results {
        position: absolute;
        left: 0;
        right: 0;
        background: white;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        max-height: 240px;
        overflow-y: auto;
        display: none;
        z-index: 50;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }
    
    .search-results li {
        padding: 12px 16px;
        cursor: pointer;
        transition: background 0.2s;
        border-bottom: 1px solid #f1f5f9;
    }
    
    .search-results li:hover {
        background: #f8fafc;
    }
    
    .result-title {
        font-weight: 600;
        color: #0f1b5c;
        margin-bottom: 4px;
    }
    
    .result-details {
        font-size: 12px;
        color: #64748b;
    }
    
    /* Table Styles */
    .table-wrapper {
        flex: 1;
        overflow: auto;
        border-radius: 8px;
        border: 1px solid #e2e8f0;
    }
    
    .data-table {
        width: 100%;
        min-width: 800px;
        border-collapse: collapse;
    }
    
    .data-table thead tr {
        background: #0f1b5c;
        color: white;
        position: sticky;
        top: 0;
    }
    
    .data-table th {
        padding: 12px 16px;
        text-align: left;
        font-weight: 600;
        font-size: 14px;
    }
    
    .data-table td {
        padding: 12px 16px;
        border-bottom: 1px solid #e2e8f0;
        font-size: 14px;
    }
    
    .data-table tbody tr:hover {
        background: #f8fafc;
    }
    
    .prerequisite-course {
        color: #dc2626;
        background: #fef2f2;
        font-weight: 500;
    }
    
    /* Action Buttons */
    .action-buttons {
        display: flex;
        gap: 8px;
    }
    
    .btn-edit {
        background: #333c6e;
        color: white;
        padding: 6px 12px;
        border-radius: 50px;
        border: none;
        cursor: pointer;
        font-size: 14px;
        transition: all 0.3s;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }
    
    .btn-edit:hover {
        background: #444d80;
        transform: scale(1.05);
    }
    
    .btn-delete {
        background: #6e3333;
        color: white;
        padding: 6px 12px;
        border-radius: 50px;
        border: none;
        cursor: pointer;
        font-size: 14px;
        transition: all 0.3s;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }
    
    .btn-delete:hover {
        background: #8a4444;
        transform: scale(1.05);
    }
    
    /* Modal Styles */
    .modal {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.5);
        z-index: 2000;
        align-items: center;
        justify-content: center;
    }
    
    .modal.active {
        display: flex;
    }
    
    .modal-content {
        background: white;
        width: 60%;
        max-height: 85vh;
        overflow-y: auto;
        border-radius: 12px;
        padding: 24px;
        position: relative;
        animation: modalFadeIn 0.3s ease;
    }
    
    @keyframes modalFadeIn {
        from {
            opacity: 0;
            transform: translateY(-20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    .modal-close {
        position: absolute;
        right: 20px;
        top: 16px;
        font-size: 24px;
        cursor: pointer;
        color: #94a3b8;
        transition: color 0.3s;
    }
    
    .modal-close:hover {
        color: #0f1b5c;
    }
    
    .modal-title {
        font-size: 22px;
        font-weight: 600;
        margin-bottom: 20px;
        color: #0f1b5c;
    }
    
    /* Form Styles */
    .form-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 16px;
    }
    
    .form-group {
        display: flex;
        flex-direction: column;
    }
    
    .form-group label {
        font-weight: 500;
        margin-bottom: 6px;
        color: #334155;
        font-size: 14px;
    }
    
    .form-group select {
        width: 100%;
        padding: 10px 12px;
        border: 1px solid #e2e8f0;
        border-radius: 6px;
        font-size: 14px;
        transition: all 0.3s;
    }
    
    .form-group select:focus {
        outline: none;
        border-color: #0f1b5c;
        box-shadow: 0 0 0 3px rgba(15, 27, 92, 0.1);
    }
    
    .full-width {
        grid-column: span 2;
    }
    
    .text-center {
        text-align: center;
    }
    
    .btn-save {
        background: #0f1b5c;
        color: white;
        padding: 10px 24px;
        border-radius: 8px;
        border: none;
        cursor: pointer;
        font-size: 14px;
        font-weight: 500;
        transition: all 0.3s;
    }
    
    .btn-save:hover {
        background: #1a2a7a;
        transform: translateY(-1px);
        box-shadow: 0 2px 8px rgba(15, 27, 92, 0.3);
    }
    
    /* Message Alert */
    .message-alert {
        display: none;
        padding: 10px 16px;
        margin-bottom: 16px;
        border-radius: 6px;
        font-size: 14px;
    }
    
    .message-success {
        background: #dcfce7;
        color: #166534;
        border: 1px solid #bbf7d0;
    }
    
    .message-error {
        background: #fee2e2;
        color: #991b1b;
        border: 1px solid #fecaca;
    }
    
    /* Scrollbar Styling */
    .table-wrapper::-webkit-scrollbar,
    .modal-content::-webkit-scrollbar {
        width: 8px;
        height: 8px;
    }
    
    .table-wrapper::-webkit-scrollbar-track,
    .modal-content::-webkit-scrollbar-track {
        background: #f1f5f9;
        border-radius: 4px;
    }
    
    .table-wrapper::-webkit-scrollbar-thumb,
    .modal-content::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 4px;
    }
    
    .table-wrapper::-webkit-scrollbar-thumb:hover,
    .modal-content::-webkit-scrollbar-thumb:hover {
        background: #94a3b8;
    }
    
    /* Responsive */
    @media (max-width: 1024px) {
        .prerequisite-container {
            left: 20px;
        }
        
        .modal-content {
            width: 90%;
        }
        
        .form-grid {
            grid-template-columns: 1fr;
        }
        
        .full-width {
            grid-column: span 1;
        }
    }
    
    @media (max-width: 768px) {
        .top-bar {
            flex-direction: column;
            gap: 12px;
            align-items: flex-start;
        }
        
        .center-dropdown {
            position: relative;
            left: 0;
            transform: none;
            margin-bottom: 16px;
        }
        
        .action-buttons {
            flex-direction: column;
        }
    }
</style>

<div class="prerequisite-container">

    <!-- CENTER DROPDOWN -->
    <div class="center-dropdown">
        <form method="GET" action="{{ route('course-prerequisite.index') }}">
            <select name="scheme_filter" onchange="this.form.submit()" class="scheme-select">
                <option value="">All Schemes</option>
                @foreach($schemes as $scheme)
                <option value="{{ $scheme->id }}"
                    {{ request('scheme_filter') == $scheme->id ? 'selected' : '' }}>
                    {{ $scheme->title }}
                </option>
                @endforeach
            </select>
        </form>
    </div>

    <!-- TOP BAR -->
    <div class="top-bar">
        <h2 class="page-title">Course Prerequisites</h2>
        <button onclick="openModal()" class="btn-primary">
            + Add Prerequisite
        </button>
    </div>

    <!-- SEARCH -->
    <div class="search-wrapper">
        <input type="text" id="globalSearch" placeholder="Search SOS, Program, Course..." class="global-search">
        <ul id="searchResults" class="search-results"></ul>
    </div>

    <!-- TABLE -->
    <div class="table-wrapper">
        <table class="data-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>SOS</th>
                    <th>Program</th>
                    <th>Prerequisite Course</th>
                    <th>Course</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody id="schemeTableBody">
                @foreach($prerequisites as $prerequisite)
                <tr>
                    <td>{{ $prerequisite->id }}</td>
                    <td>{{ $prerequisite->sos->title ?? 'N/A' }}</td>
                    <td>{{ $prerequisite->program->name ?? 'N/A' }}</td>
                    <td class="prerequisite-course">{{ $prerequisite->prerequisiteCourse->course_title ?? 'N/A' }} (Required)</td>
                    <td>{{ $prerequisite->course->course_title ?? 'N/A' }}</td>
                    <td>
                        <div class="action-buttons">
                            <a href="javascript:void(0)" onclick='editScheme(@json($prerequisite))' class="btn-edit">
                                <i class="fas fa-edit"></i> Edit
                            </a>
                            <form method="POST" action="{{ route('course-prerequisite.destroy', $prerequisite->id) }}"
                                onsubmit="return confirm('Are you sure you want to delete this prerequisite?');"
                                style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-delete">
                                    <i class="fas fa-trash"></i> Delete
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

</div>

<!-- MODAL -->
<div id="schemeModal" class="modal">
    <div class="modal-content">
        <span onclick="closeModal()" class="modal-close">&times;</span>
        <h2 class="modal-title">Add Prerequisite</h2>
        <div id="message" class="message-alert"></div>
        
        <form id="schemeForm">
            @csrf
            <input type="hidden" name="id" id="scheme_id">
            
            <div class="form-grid">
                <div class="form-group">
                    <label>SOS</label>
                    <select name="scheme_id" required>
                        <option value="">Select Scheme</option>
                        @foreach($schemes as $scheme)
                        <option value="{{ $scheme->id }}">{{ $scheme->title }}</option>
                        @endforeach
                    </select>
                </div>
                
                <div class="form-group">
                    <label>Program</label>
                    <select name="program_id" required>
                        <option value="">Select Program</option>
                        @foreach($programs as $program)
                        <option value="{{ $program->id }}">{{ $program->name }}</option>
                        @endforeach
                    </select>
                </div>
                
                <div class="form-group">
                    <label>Course</label>
                    <select name="course_id" required>
                        <option value="">Select Course</option>
                        @foreach($courses as $course)
                        <option value="{{ $course->id }}">{{ $course->course_title }}</option>
                        @endforeach
                    </select>
                </div>
                
                <div class="form-group">
                    <label>Prerequisite Course (Required)</label>
                    <select name="prerequisite_course_id" required>
                        <option value="">Select Prerequisite Course</option>
                        @foreach($courses as $course)
                        <option value="{{ $course->id }}">{{ $course->course_title }}</option>
                        @endforeach
                    </select>
                </div>
                
                <div class="full-width text-center">
                    <button type="submit" class="btn-save">Save</button>
                </div>
            </div>
        </form>
    </div>
</div>

@endsection

@section('scripts')

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.0/jquery.min.js"></script>
<script src="https://kit.fontawesome.com/your-fontawesome-kit.js" crossorigin="anonymous"></script>

<script>
    // Open Modal
    function openModal() {
        document.getElementById('schemeModal').classList.add('active');
    }
    
    // Close Modal on outside click
    window.onclick = function(event) {
        let modal = document.getElementById('schemeModal');
        if (event.target == modal) {
            modal.classList.remove('active');
        }
    }
    
    // CSRF setup
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    
    // FORM SUBMIT
    $('#schemeForm').on('submit', function(e) {
        e.preventDefault();
        
        let id = $('#scheme_id').val();
        let url = id ? `/course-prerequisite/${id}` : "{{ route('course-prerequisite.store') }}";
        
        $.ajax({
            url: url,
            method: "POST",
            data: $(this).serialize() + (id ? '&_method=PUT' : ''),
            
            success: function(res) {
                let msgDiv = $('#message');
                msgDiv.removeClass('message-error').addClass('message-success');
                msgDiv.html(res.message).fadeIn();
                msgDiv.css('display', 'block');
                
                setTimeout(() => {
                    location.reload();
                }, 1000);
            },
            
            error: function(err) {
                let msg = 'Something went wrong';
                
                if (err.status === 422 && err.responseJSON.errors) {
                    let errors = err.responseJSON.errors;
                    msg = Object.values(errors).join('<br>');
                }
                
                let msgDiv = $('#message');
                msgDiv.removeClass('message-success').addClass('message-error');
                msgDiv.html(msg).fadeIn();
                msgDiv.css('display', 'block');
            }
        });
    });
    
    // Prevent same course as prerequisite
    $('select[name="prerequisite_course_id"]').on('change', function() {
        let course = $('select[name="course_id"]').val();
        
        if ($(this).val() == course) {
            alert("Course and prerequisite cannot be the same");
            $(this).val('');
        }
    });
    
    // Edit function
    function editScheme(data) {
        openModal();
        
        $('#scheme_id').val(data.id);
        $('select[name="course_id"]').val(data.course_id);
        $('select[name="prerequisite_course_id"]').val(data.prerequisite_course_id);
        $('select[name="scheme_id"]').val(data.scheme_id);
        $('select[name="program_id"]').val(data.program_id);
        
        // Update modal title
        $('.modal-title').text('Edit Prerequisite');
    }
    
    // Close Modal
    function closeModal() {
        document.getElementById('schemeModal').classList.remove('active');
        $('#schemeForm')[0].reset();
        $('#scheme_id').val('');
        $('.modal-title').text('Add Prerequisite');
        $('#message').hide().html('');
    }
    
    // SEARCH
    $('#globalSearch').on('keyup', function() {
        let query = $(this).val();
        
        if (query.length < 2) {
            $('#searchResults').hide();
            return;
        }
        
        $.ajax({
            url: '/search-all',
            method: 'GET',
            data: { search: query },
            
            success: function(data) {
                let html = '';
                
                if (data.length > 0) {
                    data.forEach(item => {
                        html += `
                            <li onclick="goToFilter(${item.id})">
                                <div class="result-title">${item.course?.course_title ?? ''}</div>
                                <div class="result-details">
                                    Pre: ${item.prerequisite_course?.course_title ?? ''} |
                                    Program: ${item.program?.name ?? ''} |
                                    SOS: ${item.sos?.title ?? ''}
                                </div>
                            </li>
                        `;
                    });
                } else {
                    html = `<li style="padding: 12px 16px; color: #64748b;">No results found</li>`;
                }
                
                $('#searchResults').html(html).show();
            }
        });
    });
    
    // Redirect filter
    function goToFilter(id) {
        window.location.href = `/course-prerequisite?search_id=${id}`;
    }
</script>

@endsection