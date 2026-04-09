@extends('layouts.app')

@section('title', 'Scheme of Study')

@section('styles')
<style>
    /* ROOT WIDTH CONTROL */
    :root {
        --sidebar-width: 270px;
        --sidebar-collapsed-width: 70px;
    }

    /* MAIN CONTAINER */
    .main-container {
        position: fixed;
        top: 80px;
        left: var(--sidebar-width);
        right: 20px;
        bottom: 20px;
        background: #ffffff;
        padding: 20px;
        border-radius: 12px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
        display: flex;
        flex-direction: column;
        overflow: hidden;
        transition: left 0.3s ease;
    }

    body.sidebar-collapsed .main-container {
        left: var(--sidebar-collapsed-width);
    }

    /* TOP BAR */
    .top-bar {
        width: 100%;
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 20px;
    }

    .page-title {
        font-size: 20px;
        font-weight: 600;
        color: #0f1b5c;
        margin-bottom: 10px;
    }

    /* BUTTON */
    .add-btn {
        background: #0f1b5c;
        color: #fff;
        padding: 12px 20px;
        border: none;
        border-radius: 8px;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .add-btn:hover {
        background: #1a2a7a;
        transform: translateY(-2px);
    }

    /* TABLE */
    .table-container {
        flex: 1;
        overflow-x: auto;
        overflow-y: auto;
    }

    table {
        width: 100%;
        min-width: 800px;
        border-collapse: collapse;
    }

    thead th {
        position: sticky;
        top: 0;
        background: #0f1b5c;
        color: #fff;
        padding: 14px 16px;
        font-weight: 600;
        text-align: left;
    }

    tbody td {
        padding: 12px 16px;
        border-bottom: 1px solid #e0e0e0;
    }

    tbody tr:hover {
        background-color: #f5f7fb;
    }

    /* Action Icons */
    .action-icons {
        display: flex;
        gap: 12px;
    }

    .edit-icon {
        color: #0f1b5c;
        cursor: pointer;
        font-size: 18px;
        transition: transform 0.2s;
    }

    .edit-icon:hover {
        transform: scale(1.1);
    }

    .delete-icon {
        color: #d33;
        cursor: pointer;
        font-size: 18px;
        transition: transform 0.2s;
    }

    .delete-icon:hover {
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
        background: rgba(0,0,0,0.6);
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
        box-shadow: 0 10px 30px rgba(0,0,0,0.25);
    }

    /* CLOSE BUTTON */
    .close {
        position: absolute;
        right: 20px;
        top: 15px;
        cursor: pointer;
        font-size: 26px;
        transition: color 0.2s;
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
    input, select, textarea {
        width: 100%;
        padding: 10px 12px;
        border-radius: 6px;
        border: 1px solid #d0d0d0;
        outline: none;
        transition: 0.2s;
        font-size: 14px;
    }

    input:focus, select:focus, textarea:focus {
        border-color: #0f1b5c;
        box-shadow: 0 0 0 2px rgba(15, 27, 92, 0.1);
    }

    /* FIELD ERROR STYLES */
    input.error, select.error, textarea.error {
        border-color: #dc3545;
        background-color: #fff8f8;
    }

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

    /* Alert Messages */
    .alert {
        padding: 12px 20px;
        border-radius: 8px;
        margin-bottom: 20px;
        position: relative;
        animation: slideDown 0.3s ease;
    }

    .alert-success {
        background-color: #d4edda;
        color: #155724;
        border: 1px solid #c3e6cb;
    }

    .alert-error {
        background-color: #f8d7da;
        color: #721c24;
        border: 1px solid #f5c6cb;
    }

    .close-alert {
        float: right;
        font-size: 20px;
        font-weight: bold;
        cursor: pointer;
        color: inherit;
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
        to { transform: rotate(360deg); }
    }

    @keyframes slideDown {
        from {
            transform: translateY(-20px);
            opacity: 0;
        }
        to {
            transform: translateY(0);
            opacity: 1;
        }
    }
</style>
@endsection

@section('content')

<meta name="csrf-token" content="{{ csrf_token() }}">

<div class="main-container">

    <!-- Alert Messages -->
    <div id="alertMessages"></div>

    <!-- TOP BAR -->
    <div class="top-bar">
        <h2 class="page-title">Scheme of Study</h2>
        <button class="add-btn" id="addSchemeBtn">+ Add Scheme</button>
    </div>

    <!-- TABLE -->
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Credit Hours</th>
                    <th>Description</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>

            <tbody id="schemeTableBody">

            </tbody>
        </table>
    </div>

</div>

<!-- MODAL -->
<div class="modal" id="schemeModal">
    <div class="modal-content">
        <span class="close" id="closeModalBtn">&times;</span>
        <div class="form-title" id="modalTitle">Add Scheme of Study</div>

        <form id="schemeForm">
            @csrf
            <input type="hidden" name="_method" id="formMethod" value="POST">
            <input type="hidden" name="id" id="schemeId" value="">

            <div class="form-grid">
                <div class="full-width">
                    <label>Title *</label>
                    <input type="text" name="title" id="title" placeholder="Enter scheme title" required>
                    <span class="field-error" id="titleError"></span>
                </div>

                <div>
                    <label>Credit Hours *</label>
                    <input type="number" name="credit_hrs" id="credit_hrs" placeholder="e.g., 3" required min="1" step="1">
                    <span class="field-error" id="credit_hrsError"></span>
                </div>

                <div>
                    <label>Status *</label>
                    <select name="is_active" id="is_active" required>
                        <option value="">Select Status</option>
                        <option value="1">Active</option>
                        <option value="0">Inactive</option>
                    </select>
                    <span class="field-error" id="is_activeError"></span>
                </div>

                <div class="full-width">
                    <label>Description</label>
                    <textarea name="description" id="description" rows="3" placeholder="Enter scheme description..."></textarea>
                    <span class="field-error" id="descriptionError"></span>
                </div>
            </div>

            <div class="button-group">
                <button type="button" class="cancel-btn" id="cancelBtn">Cancel</button>
                <button type="button" class="submit-btn" id="saveSchemeBtn">Save Scheme</button>
            </div>
        </form>
    </div>
</div>

@endsection

@section('scripts')

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://kit.fontawesome.com/your-kit.js"></script>

<script>
    function openModal() {
        document.getElementById('schemeModal').style.display = 'flex';
    }

    function closeModal() {
        document.getElementById('schemeModal').style.display = 'none';
    }
    if (inputField) {
        inputField.classList.add('error');
    }
}

// ==================== VALIDATE FORM ====================

function validateForm() {
    let isValid = true;
    clearValidation();
    
    const title = titleInput.value.trim();
    const creditHrs = creditHrsInput.value.trim();
    const isActive = isActiveSelect.value;
    
    if (!title) {
        showFieldError('title', 'Title is required');
        isValid = false;
    }
    
    if (!creditHrs) {
        showFieldError('credit_hrs', 'Credit hours are required');
        isValid = false;
    } else if (creditHrs < 1 || creditHrs > 140) {
        showFieldError('credit_hrs', 'Credit hours must be between 1 and 140');
        isValid = false;
    }
    
    if (!isActive) {
        showFieldError('is_active', 'Please select a status');
        isValid = false;
    }
    
    return isValid;
}

// ==================== MODAL FUNCTIONS ====================

function openModal() {
    schemeForm.reset();
    formMethod.value = "POST";
    modalTitle.textContent = "Add Scheme of Study";
    schemeId.value = '';
    clearValidation();
    modal.style.display = 'flex';
}

function closeModal() {
    modal.style.display = 'none';
    clearValidation();
}

// Open modal on add button click
addBtn.addEventListener('click', openModal);

// Close modal on close button click
closeModalBtn.addEventListener('click', closeModal);
cancelBtn.addEventListener('click', closeModal);

// Close modal when clicking outside
window.addEventListener('click', function(event) {
    if (event.target === modal) {
        closeModal();
    }
});

// ==================== EDIT SCHEME ====================

async function editScheme(id) {
    Swal.fire({
        title: 'Loading...',
        text: 'Please wait while we fetch scheme details',
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });

    // CSRF
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    function appendSchemeRow(scheme) {
        let status = scheme.is_active ? 'Active' : 'Inactive';

        let row = `
        <tr>
            <td>${scheme.title}</td>
            <td>${scheme.credit_hrs}</td>
            <td>${scheme.description ?? ''}</td>
            <td>${status}</td>
        </tr>
    `;

        $('#schemeTableBody').prepend(row);
    }

    // FIXED EVENT
    $(document).on('submit', '#schemeForm', function(e) {
        e.preventDefault();

        $.ajax({
            url: "{{ route('scheme.store') }}",
            method: "POST",
            data: $(this).serialize(),

            success: function(res) {
                $('#message')
                    .removeClass('error')
                    .addClass('success')
                    .html(res.message)
                    .fadeIn();

                appendSchemeRow(res.data);

                $('#schemeForm')[0].reset();

                setTimeout(() => {
                    closeModal();
                    $('#message').hide();
                }, 1000);
            },

            error: function(err) {
                let msg = 'Something went wrong';

                if (err.status === 422) {
                    let errors = err.responseJSON.errors;
                    msg = Object.values(errors).join('<br>');
                } else if (err.responseJSON?.message) {
                    msg = err.responseJSON.message;
                }

                $('#message')
                    .removeClass('success')
                    .addClass('error')
                    .html(msg)
                    .fadeIn();
            }
        });
    });
</script>

@endsection