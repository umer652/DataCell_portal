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
                class="flex items-center p-2 rounded
          hover:bg-blue-500 hover:text-white
          {{ request()->routeIs('dashboard') ? '!bg-white !text-[#0f1b5c] font-bold' : 'text-gray-700' }}">
                <i class="fas fa-user-plus mr-2"></i> Student Management
            </a>
        </li>
        <li>
            <a href="{{ route('scheme_of_study') }}"
                class="flex items-center p-2 rounded hover:bg-blue-500 hover:text-white {{ request()->routeIs('scheme_of_study') ? '!bg-white !text-[#0f1b5c] font-bold' : 'text-gray-700' }}">
                <i class="fas fa-plus-circle mr-2"></i> SOS Management
            </a>
        </li>
        <li>
            <a href="{{ route('add-courses') }}"
                class="flex items-center p-2 rounded hover:bg-blue-500 hover:text-white {{ request()->routeIs('add-courses') ? '!bg-white !text-[#0f1b5c] font-bold' : 'text-gray-700' }}">
                <i class="fas fa-book mr-2"></i> Courses Management
            </a>
        </li>
        <li>
            <a href="{{ route('course-prerequisite.index') }}"
                class="flex items-center p-2 rounded hover:bg-blue-500 hover:text-white {{ request()->routeIs('course-prerequisite.*') ? '!bg-white !text-[#0f1b5c] font-bold' : 'text-gray-700' }}">
                <i class="fas fa-link mr-2"></i> Add Course Prerequisite
            </a>
        </li>
        <li>
            <a href="{{ route('teacher.index') }}"
                class="flex items-center p-2 rounded hover:bg-blue-500 hover:text-white {{ request()->routeIs('teacher.*') ? '!bg-white !text-[#0f1b5c] font-bold' : 'text-gray-700' }}">
                <i class="fas fa-chalkboard-teacher mr-2"></i> Faculty Management
            </a>
        </li>
        <li>
            <a href="{{ route('enrollment.index') }}"
                class="flex items-center p-2 rounded hover:bg-blue-500 hover:text-white {{ request()->routeIs('enrollment.*') ? '!bg-white !text-[#0f1b5c] font-bold' : 'text-gray-700' }}">
                <i class="fas fa-user-graduate mr-2"></i> Enrollment Management
            </a>
        </li>
        <li>
            <a href="{{ route('allocation.index') }}"
                class="flex items-center p-2 rounded hover:bg-blue-500 hover:text-white {{ request()->routeIs('allocation.*') ? 'bg-blue-500 text-white font-bold' : 'text-gray-700' }}">
                <i class="fas fa-tasks mr-2"></i> Allocation Management
            </a>
        </li>
    </ul>

</div>