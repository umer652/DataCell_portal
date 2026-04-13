<div class="sidebar" id="sidebar">

    <!-- CLOSE BUTTON -->
    <div class="close-btn">
        <i class="fas fa-xmark" id="closeSidebar"></i>
    </div>

    <img src="{{ asset('logo.png') }}" alt="Logo">
    <h2>Datacell Portal</h2>

    <ul class="space-y-2">
        <li>
            <a href="{{ route('dashboard') }}"
                class="nav-link ajax-link flex items-center p-2 rounded
          hover:bg-blue-500 hover:text-white
           text-gray-700" data-route="dashboard">
                <i class="fas fa-user-plus mr-2"></i> Student Management
            </a>
        </li>
        <li>
            <a href="{{ route('scheme_of_study') }}"
                class="nav-link ajax-link flex items-center p-2 rounded hover:bg-blue-500 hover:text-white text-gray-700" data-route="scheme_of_study">
                <i class="fas fa-plus-circle mr-2"></i> SOS Management
            </a>
        </li>
        <li>
            <a href="{{ route('add-courses') }}"
                class="nav-link ajax-link flex items-center p-2 rounded hover:bg-blue-500 hover:text-white text-gray-700" data-route="add-courses">
                <i class="fas fa-book mr-2"></i> Courses Management
            </a>
        </li>
        <li>
            <a href="{{ route('course-prerequisite.index') }}"
                class="nav-link ajax-link flex items-center p-2 rounded hover:bg-blue-500 hover:text-white text-gray-700" data-route="course-prerequisite.index">
                <i class="fas fa-link mr-2"></i> Add Course Prerequisite
            </a>
        </li>
        <li>
            <a href="{{ route('teacher.index') }}"
                class="nav-link ajax-link flex items-center p-2 rounded hover:bg-blue-500 hover:text-white text-gray-700" data-route="teacher.index">
                <i class="fas fa-chalkboard-teacher mr-2"></i> Faculty Management
            </a>
        </li>
        <li>
            <a href="{{ route('enrollment.index') }}"
                class="nav-link ajax-link flex items-center p-2 rounded hover:bg-blue-500 hover:text-white text-gray-700" data-route="enrollment.index">
                <i class="fas fa-user-graduate mr-2"></i> Enrollment Management
            </a>
        </li>
        <li>
            <a href="{{ route('allocation.index') }}"
                class="nav-link ajax-link flex items-center p-2 rounded hover:bg-blue-500 hover:text-white text-gray-700" data-route="allocation.index">
                <i class="fas fa-tasks mr-2"></i> Allocation Management
            </a>
        </li>
        <!-- Add this to your sidebar menu -->
<li class="nav-item">
    <a class="nav-link" href="{{ route('transcripts.index') }}">
        <i class="fas fa-print"></i> 
        <span>Print Transcript</span>
    </a>
</li>
    </ul>

</div>