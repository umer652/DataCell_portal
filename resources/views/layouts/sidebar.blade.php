<div class="sidebar" id="sidebar">

    <!-- CLOSE BUTTON -->
    <div class="close-btn">
        <i class="fas fa-xmark" id="closeSidebar"></i>
    </div>

    <img src="{{ asset('logo.png') }}" alt="Logo">
    <h2>Datacell Portal</h2>

    <ul>
        <li><a href="{{route('dashboard')}}"><i class="fas fa-user-plus"></i>Student Management</a></li>
        <li><a href="{{route('scheme_of_study')}}"><i class="fas fa-plus-circle"></i>SOS Management</a></li>
        <li><a href="{{route('add-courses')}}"><i class="fas fa-book"></i>Courses Management</a></li>
        <li><a href="{{ route('course-prerequisite.index') }}"><i class="fas fa-link"></i> Add Course Prerequisite</a></li>
        <li><a href="{{route('teacher.index')}}"><i class="fas fa-chalkboard-teacher"></i>Faculty Management</a></li>
        <li><a href="{{route('enrollment.index')}}"><i class="fas fa-user-graduate"></i>Enrollment Management</a></li>
        <li><a href="{{route('allocation.index')}}"><i class="fas fa-tasks"></i>Allocation Management</a></li>
    </ul>

</div>
