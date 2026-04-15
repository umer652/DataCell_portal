@extends('layouts.app')

@section('title', 'Student Result')

@section('content')

<div class="main-container flex flex-col overflow-hidden">

    <!-- TOP BAR -->
    <div class="flex items-center gap-3 mb-4">
        <h2 class="text-xl font-semibold text-[#0f1b53]">
            Student Result
        </h2>
    </div>

    <!-- INFO -->
    <div class="mb-4">
        <p><b>Name:</b> {{ $student->name }}</p>
        <p><b>Roll No:</b> {{ $student->roll_no }}</p>
    </div>

    <div class="flex-1 overflow-y-auto pr-2">

        @foreach($groupedEnrollments as $semester => $enrollments)

        <!-- Semester Heading -->
        <h3 class="text-lg font-bold mt-6 mb-2 text-[#0f1b53]">
            Semester {{ $semester }}
        </h3>

        <table class="w-full border text-sm mb-4">

            <thead class="bg-[#0f1b53] text-white">
                <tr>
                    <th class="p-3">Course Code</th>
                    <th class="p-3">Course Title</th>
                    <th class="p-3">Grade</th>
                </tr>
            </thead>

            <tbody>
                @foreach($enrollments as $enroll)
                <tr class="border-b hover:bg-gray-50">
                    <td class="p-3">
                        {{ optional($enroll->offeredCourse->course)->course_code }}
                    </td>
                    <td class="p-3">
                        {{ optional($enroll->offeredCourse->course)->course_title }}
                    </td>
                    <td class="p-3">
                        {{ $enroll->grade ?? 'N/A' }}
                    </td>
                </tr>
                @endforeach
            </tbody>

        </table>

        @endforeach
    </div>
</div>

@endsection