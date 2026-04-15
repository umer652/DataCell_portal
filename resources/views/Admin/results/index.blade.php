@extends('layouts.app')

@section('title', 'Results Management')

@section('content')

<div class="main-container">

    <!-- TOP BAR -->
    <div class="top-bar flex items-center justify-between mb-6">

        <div class="pb-6" style="display:flex; align-items:center; gap:12px;">
            <h2 class="page-title text-xl font-semibold text-[#0f1b53]">Results Management</h2>
        </div>

        <div class="pb-6" style="display:flex; align-items:center; gap:12px;">
            <input type="text"
                id="searchRoll"
                placeholder="Search by Roll No..."
                class="border px-3 py-2 rounded w-64 focus:outline-none focus:ring-2 focus:ring-[#0f1b53]">
        </div>


    </div>

    <!-- TABLE -->
    <div class="table-scroll flex-1">
        <table class="w-full border text-sm">

            <thead class="bg-[#0f1b53] text-white sticky top-0">
                <tr>
                    <th class="p-3 text-left">Name</th>
                    <th class="p-3 text-left">Roll No</th>
                    <th class="p-3 text-left">Action</th>
                </tr>
            </thead>

            <tbody>
                @foreach($students as $std)
                <tr class="border-b hover:bg-gray-50">
                    <td class="p-3">{{ $std->name }}</td>
                    <td class="p-3">{{ $std->roll_no }}</td>
                    <td class="p-3">
                        <a href="{{ route('results.show', $std->id) }}"
                            class="bg-[#0f1b53] text-white px-4 py-2 rounded ajax-link" data-title="Student Result">
                            View Result
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>

        </table>
    </div>

</div>

@endsection

<script>
    document.addEventListener('keyup', function(e) {

        if (e.target.id === 'searchRoll') {

            let value = e.target.value.toLowerCase();
            let rows = document.querySelectorAll("tbody tr");

            rows.forEach(row => {
                let roll = row.children[1].textContent.toLowerCase();
                row.style.display = roll.includes(value) ? "" : "none";
            });

        }

    });
</script>