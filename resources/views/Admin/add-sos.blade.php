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
        padding: 12px;
    }

    tbody td {
        padding: 12px;
        border-bottom: 1px solid #e0e0e0;
    }

    tbody tr:hover {
        background-color: #f5f7fb;
    }

    /* ================= MODAL FIXED ================= */

    .modal {
        display: none;
        position: fixed;
        z-index: 2000;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;

        background: rgba(0, 0, 0, 0.5);

        /* PERFECT CENTER */
        align-items: center;
        justify-content: center;

        padding: 20px;
    }

    .modal-content {
        background: #fff;
        width: 55%;
        max-width: 700px;

        padding: 25px;
        border-radius: 12px;

        position: relative;

        max-height: 90vh;
        overflow-y: auto;
    }

    /* CLOSE BUTTON */
    .close {
        position: absolute;
        right: 18px;
        top: 12px;
        cursor: pointer;
        font-size: 24px;
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
        font-weight: 500;
        color: #333;
    }

    /* INPUTS */
    input,
    select,
    textarea {
        width: 100%;
        padding: 10px 12px;
        border-radius: 6px;
        border: 1px solid #ccc;
        outline: none;
        transition: 0.2s;
    }

    input:focus,
    select:focus,
    textarea:focus {
        border-color: #0f1b5c;
    }

    /* TEXTAREA */
    textarea {
        min-height: 90px;
        resize: vertical;
    }

    /* SUBMIT BUTTON */
    button[type="submit"] {
        background: #0f1b5c;
        color: #fff;
        padding: 12px 25px;
        border: none;
        border-radius: 8px;
        cursor: pointer;

        grid-column: 1 / -1;
        width: 200px;
        justify-self: center;
    }

    /* MESSAGE */
    #message {
        display: none;
        padding: 10px;
        margin-top: 10px;
        border-radius: 6px;
    }

    #message.success {
        background: #d4edda;
        color: #155724;
    }

    #message.error {
        background: #f8d7da;
        color: #721c24;
    }
</style>
@endsection

@section('content')

<meta name="csrf-token" content="{{ csrf_token() }}">

<div class="main-container">

    <!-- TOP BAR -->
    <div class="top-bar">
        <h2>Scheme of Study</h2>
        <button class="add-btn" onclick="openModal()">+ Add Scheme</button>
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
                </tr>
            </thead>

            <tbody id="schemeTableBody">
                @foreach($schemes as $scheme)
                <tr>
                    <td>{{ $scheme->title }}</td>
                    <td>{{ $scheme->credit_hrs }}</td>
                    <td>{{ $scheme->description }}</td>
                    <td>{{ $scheme->is_active ? 'Active' : 'Inactive' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

</div>

<!-- MODAL -->
<div class="modal" id="schemeModal">
    <div class="modal-content">

        <span class="close" onclick="closeModal()">&times;</span>

        <h2>Add Scheme</h2>

        <div id="message"></div>

        <form id="schemeForm">
            @csrf

            <div class="form-grid">

                <div>
                    <label>Title</label>
                    <input type="text" name="title" required>
                </div>

                <div>
                    <label>Status</label>
                    <select name="is_active" required>
                        <option value="">Select</option>
                        <option value="1">Active</option>
                        <option value="0">Inactive</option>
                    </select>
                </div>

                <div>
                    <label>Credit Hours</label>
                    <input type="number" name="credit_hrs" required>
                </div>

                <div class="full-width">
                    <label>Description</label>
                    <textarea name="description"></textarea>
                </div>

                <button type="submit">Save</button>

            </div>
        </form>

    </div>
</div>

@endsection

@section('scripts')

<script>
    function openModal() {
        document.getElementById('schemeModal').style.display = 'flex';
    }

    function closeModal() {
        document.getElementById('schemeModal').style.display = 'none';
    }

    // FIXED CLICK HANDLER
    document.addEventListener('click', function(event) {
        let modal = document.getElementById('schemeModal');
        if (event.target === modal) {
            modal.style.display = 'none';
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