@extends('layouts.app')

@section('title', 'Student Result')

@section('content')

<div class="main-container overflow-y-auto p-4">

    <!-- HEADER -->
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-2xl font-bold text-[#0f1b53]">
            Student Result Dashboard
        </h2>
    </div>

    <!-- STUDENT INFO CARD -->
    <div class="bg-white shadow-md rounded-xl p-4 mb-6 border">
        <div class="grid grid-cols-2 gap-4">
            <div>
                <p class="text-gray-500 text-sm">Student Name</p>
                <p class="text-lg font-semibold text-[#0f1b53]">{{ $student->name }}</p>
            </div>
            <div>
                <p class="text-gray-500 text-sm">Roll No</p>
                <p class="text-lg font-semibold text-[#0f1b53]">{{ $student->roll_no }}</p>
            </div>
            <div>
                <p class="text-gray-500 text-sm">Program</p>
                <p class="text-lg font-semibold text-[#0f1b53]">
                    {{ optional($student->program)->name ?? 'N/A' }}
                </p>
            </div>

            <div>
                <p class="text-sm opacity-80">Total Semesters</p>
                <p class="text-lg font-semibold text-[#0f1b53]">
                    {{ $minSemester }}
                </p>
            </div>
            <div>
                <p class="text-gray-500 text-sm">Session</p>
                <p class="text-lg font-semibold text-[#0f1b53]">
                    {{ optional($student->session)->name ?? 'N/A' }}
                </p>
            </div>
            <div>
                <p class="text-gray-500 text-sm">Current Semester</p>
                <p class="text-lg font-semibold text-[#0f1b53]">
                    {{ $student->semester }}
                </p>
            </div>

        </div>
    </div>

    <!-- STATS CARDS -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 mb-6">

        <div class="bg-[#0f1b53] border rounded-xl p-5 shadow-sm hover:shadow-md transition">
            <p class="text-lg font-semibold text-white">CGPA</p>
            <p class="text-3xl font-bold text-white mt-1">
                {{ number_format($cgpa, 2) }}
            </p>
        </div>

        <div class="bg-[#0f1b53] border rounded-xl p-5 shadow-sm hover:shadow-md transition">
            <p class="text-lg font-semibold text-white">GPA (Current)</p>
            <p class="text-3xl font-bold text-white mt-1"> {{ number_format($currentGpa, 2) }}</p>
        </div>

        <div class="bg-[#0f1b53] border rounded-xl p-5 shadow-sm hover:shadow-md transition">
            <p class="text-lg font-semibold text-white">Total Courses</p>
            <p class="text-3xl font-bold text-white mt-1"> {{ $totalCourses }}</p>
        </div>

    </div>

    <!-- CURRENT SEMESTER -->
    <div class="bg-green-50 border-l-4 border-green-500 p-4 mb-6 rounded">
        <h3 class="text-lg font-bold text-green-700 mb-2">
            Current Semester {{ $student->enrollments->max('semester') }}
        </h3>

        <table class="w-full text-sm border-collapse">

            <thead class="bg-green-100 text-gray-700">
                <tr>
                    <th class="px-4 py-3 text-left">Course Code</th>
                    <th class="px-4 py-3 text-left">Course Title</th>
                    <th class="px-4 py-3 text-left">Grade</th>
                </tr>
            </thead>

            <tbody class="divide-y">
                @foreach($currentEnrollments as $enroll)

                <tr class="{{ $enroll->result_status == 'fail' ? 'bg-red-50' : 'bg-green-50' }}">

                    <td class="px-4 py-3">
                        {{ optional($enroll->offeredCourse->course)->course_code }}
                    </td>

                    <td class="px-4 py-3">
                        {{ optional($enroll->offeredCourse->course)->course_title }}
                    </td>

                    <td class="px-4 py-3 font-semibold">

                        <span class="
            px-2 py-1 rounded text-sm
            {{ $enroll->result_status == 'fail' 
                ? 'bg-red-200 text-red-700' 
                : 'bg-green-200 text-green-700' 
            }}
        ">
                            {{ $enroll->grade }}
                        </span>

                    </td>

                </tr>

                @endforeach
            </tbody>

        </table>
    </div>

    <!-- PREVIOUS RESULTS -->
    <h3 class="text-xl font-bold mb-4 text-[#0f1b53]">
        Previous Semesters
    </h3>

    @foreach($previousEnrollments as $semester => $enrollments)

    <div class="bg-white shadow rounded mb-4">
        <div class="bg-gray-200 px-4 py-2 font-semibold">
            Semester {{ $semester }}
        </div>

        <table class="w-full text-sm border-collapse">

            <thead class="bg-[#0f1b53] text-white">
                <tr>
                    <th class="px-4 py-3 text-left">Course Code</th>
                    <th class="px-4 py-3 text-left">Course Title</th>
                    <th class="px-4 py-3 text-left">Grade</th>
                </tr>
            </thead>

            <tbody class="divide-y">
                @foreach($enrollments as $enroll)
                <tr class="hover:bg-gray-50">

                    <td class="px-4 py-3">
                        {{ optional($enroll->offeredCourse->course)->course_code }}
                    </td>

                    <td class="px-4 py-3">
                        {{ optional($enroll->offeredCourse->course)->course_title }}
                    </td>

                    <td class="px-4 py-3">
                        <span class="
            px-2 py-1 rounded text-sm font-semibold
            {{ $enroll->result_status == 'fail'
                ? 'bg-red-200 text-red-700'
                : 'bg-green-200 text-green-700'
            }}
        ">
                            {{ $enroll->grade }}
                        </span>
                    </td>

                </tr>
                @endforeach
            </tbody>

        </table>
    </div>

    @endforeach
</div>

@endsection