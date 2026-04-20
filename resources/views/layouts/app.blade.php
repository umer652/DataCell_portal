<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title')</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            display: flex;
            background: #f4f7f9;
            overflow-x: hidden;
        }

        /* ================= HAMBURGER ================= */
        .hamburger {
            position: fixed;
            top: 10px;
            left: 260px;
            height: 60px;
            width: 50px;
            font-size: 20px;
            color: #fff;
            cursor: pointer;
            z-index: 1001;
            background: #0f1b5c;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: left 0.3s ease;
            line-height: 1;
        }

        .hamburger i {
            display: block;
            line-height: 1;
        }

        body.sidebar-collapsed .hamburger {
            left: 20px !important;
        }

        body:not(.sidebar-collapsed) .hamburger {
            display: none;
        }

        /* ================= SIDEBAR - NO SCROLLBAR ================= */
        .sidebar {
            width: 250px;
            height: 100vh;
            background: #0f1b5c;
            position: fixed;
            top: 0;
            left: -250px;
            transition: 0.3s ease;
            padding: 20px 0;
            box-shadow: 2px 0 10px rgba(0, 0, 0, 0.2);
            z-index: 1000;
            
            /* Remove scrollbar - Hide scrollbar completely */
            overflow-y: hidden !important;
            overflow-x: hidden !important;
            scrollbar-width: none !important; /* Firefox */
            -ms-overflow-style: none !important; /* IE/Edge */
        }
        
        /* Hide scrollbar for Chrome/Safari/Edge */
        .sidebar::-webkit-scrollbar {
            display: none !important;
            width: 0 !important;
            background: transparent !important;
        }
        
        /* Sidebar inner content - handle scrolling without showing scrollbar */
        .sidebar-inner {
            height: 100%;
            overflow-y: auto;
            overflow-x: hidden;
            scrollbar-width: none; /* Firefox */
            -ms-overflow-style: none; /* IE/Edge */
        }
        
        .sidebar-inner::-webkit-scrollbar {
            display: none; /* Chrome/Safari/Edge */
        }

        .sidebar.active {
            left: 0;
        }

        .sidebar .close-btn {
            display: flex;
            justify-content: flex-end;
            padding: 10px 20px;
        }

        .sidebar .close-btn i {
            color: #fff;
            font-size: 22px;
            cursor: pointer;
        }

        .sidebar img {
            width: 90px;
            height: 90px;
            border-radius: 50%;
            display: block;
            margin: 0 auto 10px;
            border: 3px solid #fff;
        }

        .sidebar h2 {
            color: #fff;
            text-align: center;
            margin-bottom: 20px;
            font-size: 18px;
        }

        .sidebar ul {
            list-style: none;
            padding-bottom: 20px;
        }

        .sidebar ul li {
            margin: 8px 0;
        }

        .sidebar ul li a {
            color: #fff;
            text-decoration: none;
            display: flex;
            align-items: center;
            padding: 12px 20px;
            border-radius: 8px;
            font-weight: 500;
            transition: 0.3s;
        }

        .sidebar ul li a:hover {
            background: rgba(255, 255, 255, 0.15);
            transform: translateX(5px);
        }

        .nav-link.active {
            background: #fff !important;
            color: #0f1b5c !important;
            font-weight: bold;
            margin-left: 5px;
            margin-right: 5px;
        }

        /* ================= CONTENT ================= */

        #main-content {
            transition: opacity 0.3s ease;
        }

        .content {
            flex: 1;
            padding: 90px 40px 40px;
            width: 100%;
        }

        /* ================= TOPBAR ================= */
        .topbar {
            position: fixed;
            top: 10px;
            left: 280px;
            right: 20px;
            height: 60px;
            background: #0f1b5c;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 20px;
            color: #fff;
            z-index: 999;
            transition: left 0.3s ease;
        }

        body.sidebar-collapsed .topbar {
            left: 100px !important;
        }

        .topbar-left {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .topbar-title {
            font-size: 18px;
            font-weight: 600;
            letter-spacing: 0.5px;
        }

        .topbar .profile {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .topbar img {
            width: 35px;
            height: 35px;
            border-radius: 50%;
        }

        .back-btn {
            display: flex;
            align-items: center;
            gap: 6px;
            background: transparent;
            color: #fff;
            border: 1px solid #fff;
            padding: 6px 12px;
            border-radius: 8px;
            cursor: pointer;
            transition: 0.3s;
        }

        .back-btn:hover {
            background: #fff;
            color: #0f1b5c;
        }

        :root {
            --sidebar-width: 270px;
            --sidebar-collapsed-width: 70px;
        }

        /* MAIN CONTENT AREA (GLOBAL FOR ALL PAGES) */
        .main-container {
            position: fixed;
            top: 80px;
            left: var(--sidebar-width);
            right: 20px;
            bottom: 20px;
            background: white;
            border-radius: 12px;
            padding: 20px;
            display: flex;
            flex-direction: column;
            overflow: hidden;
            transition: left 0.3s ease;
        }

        /* SIDEBAR COLLAPSED SUPPORT */
        body.sidebar-collapsed .main-container {
            left: var(--sidebar-collapsed-width);
        }

        /* TABLE SCROLL CUSTOM BLUE */
        .table-scroll {
            overflow: auto;
        }

        /* scrollbar styling */
        .table-scroll::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }

        .table-scroll::-webkit-scrollbar-track {
            background: #e6f0ff;
            border-radius: 10px;
        }

        .table-scroll::-webkit-scrollbar-thumb {
            background: #0f1b53;
            border-radius: 10px;
        }

        .table-scroll::-webkit-scrollbar-thumb:hover {
            background: #2c3d89;
        }
    </style>

    @yield('styles')
</head>

<body>

    <!-- HAMBURGER -->
    <i class="fas fa-bars hamburger" id="hamburger"></i>

    <!-- SIDEBAR -->
    @include('layouts.sidebar')

    <!-- TOPBAR -->
    <div class="topbar">

        <!-- LEFT -->
        <div class="topbar-left">
            <button class="back-btn" onclick="window.history.back()">
                <i class="fas fa-chevron-left"></i>
            </button>

            <div class="topbar-title">
                @yield('title')
            </div>
        </div>

        <!-- RIGHT -->
        <div class="profile">
            <span>{{ Auth::user()->name ?? 'User' }}</span>
            <img src="https://ui-avatars.com/api/?name={{ Auth::user()->name ?? 'User' }}">
        </div>

    </div>

    <!-- CONTENT -->
    <div class="content" id="main-content">
        @yield('content')
    </div>

    <script>
        const hamburger = document.getElementById('hamburger');
        const sidebar = document.getElementById('sidebar');

        function setLayout(isOpen) {
            document.body.classList.toggle('sidebar-collapsed', !isOpen);
        }

        document.addEventListener('DOMContentLoaded', () => {
            const isSidebarOpen = localStorage.getItem('sidebarOpen') === 'true';

            if (isSidebarOpen) {
                sidebar.classList.add('active');
            } else {
                sidebar.classList.remove('active');
            }

            setLayout(isSidebarOpen);

            const closeSidebar = document.getElementById('closeSidebar');

            if (closeSidebar) {
                closeSidebar.addEventListener('click', function(e) {
                    e.stopPropagation();
                    sidebar.classList.remove('active');
                    localStorage.setItem('sidebarOpen', false);
                    setLayout(false);
                });
            }
        });

        hamburger.addEventListener('click', (e) => {
            e.stopPropagation();
            sidebar.classList.add('active');
            localStorage.setItem('sidebarOpen', true);
            setLayout(true);
        });

        // Function to set active sidebar link
        function setActiveSidebar(routeName) {
            document.querySelectorAll('.nav-link').forEach(link => {
                link.classList.remove('active');
            });

            const activeLink = document.querySelector(`.nav-link[data-route='${routeName}']`);
            if (activeLink) {
                activeLink.classList.add('active');
            }
        }

        // Function to extract and execute scripts from HTML
        function executeScripts(html, container) {
            const tempDiv = document.createElement('div');
            tempDiv.innerHTML = html;

            const scripts = tempDiv.querySelectorAll('script');
            scripts.forEach(oldScript => {
                const newScript = document.createElement('script');
                if (oldScript.src) {
                    newScript.src = oldScript.src;
                } else {
                    newScript.textContent = oldScript.textContent;
                }
                document.body.appendChild(newScript);
            });
        }

        // Handle AJAX navigation
        document.addEventListener('click', function(e) {
            const link = e.target.closest('.ajax-link');
            if (link) {
                e.preventDefault();

                const route = link.getAttribute('data-route');
                setActiveSidebar(route);

                // Show loading state
                const content = document.getElementById('main-content');
                content.style.opacity = '0.5';

                // Add loading spinner or text if desired
                const originalContent = content.innerHTML;
                content.style.pointerEvents = 'none';

                fetch(link.href, {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'text/html'
                        }
                    })
                    .then(res => {
                        if (!res.ok) throw new Error('Network response was not ok');
                        return res.text();
                    })
                    .then(html => {
                        // Update content
                        content.innerHTML = html;
                        content.style.opacity = '1';
                        content.style.pointerEvents = 'auto';

                        // Update browser history
                        window.history.pushState({}, '', link.href);

                        // Extract and execute scripts from the loaded HTML
                        const tempDiv = document.createElement('div');
                        tempDiv.innerHTML = html;
                        const scripts = tempDiv.querySelectorAll('script');

                        // Execute each script
                        scripts.forEach(oldScript => {
                            const newScript = document.createElement('script');
                            if (oldScript.src) {
                                newScript.src = oldScript.src;
                                newScript.async = false;
                            } else {
                                newScript.textContent = oldScript.textContent;
                            }
                            document.body.appendChild(newScript);
                            // Remove it after execution to keep DOM clean
                            setTimeout(() => {
                                if (newScript.parentNode) {
                                    newScript.parentNode.removeChild(newScript);
                                }
                            }, 100);
                        });

                        // Re-initialize modules based on the loaded page
                        setTimeout(() => {
                            // Check for teacher module
                            if (link.href.includes('teacher')) {
                                if (typeof window.initTeacherModule === 'function') {
                                    console.log('Re-initializing Teacher Module');
                                    window.initTeacherModule();
                                }
                            }
                            // Check for enrollment module
                            if (link.href.includes('enrollment')) {
                                if (typeof window.initEnrollmentModule === 'function') {
                                    window.initEnrollmentModule();
                                }
                            }
                            // Check for allocation module
                            if (link.href.includes('allocation')) {
                                if (typeof window.initAllocationModule === 'function') {
                                    window.initAllocationModule();
                                }
                            }
                            // Check for courses module
                            if (link.href.includes('courses')) {
                                if (typeof window.initCoursesModule === 'function') {
                                    window.initCoursesModule();
                                }
                            }
                            // Check for SOS module
                            if (link.href.includes('sos')) {
                                if (typeof loadSchemes === "function") {
                                    loadSchemes();
                                }
                            }
                            // Check for results module
                            if (link.href.includes('results')) {
                                console.log('Results page loaded');
                            }
                        }, 100);
                    })
                    .catch(error => {
                        console.error('Error loading page:', error);
                        content.innerHTML = '<div class="error-message" style="text-align: center; padding: 50px; color: red;">Error loading page. Please try again.</div>';
                        content.style.opacity = '1';
                        content.style.pointerEvents = 'auto';
                    });
            }
        });

        // Handle browser back/forward buttons
        window.addEventListener('popstate', function() {
            let path = window.location.pathname;

            // Fetch and load the page
            fetch(path, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'text/html'
                    }
                })
                .then(res => res.text())
                .then(html => {
                    const content = document.getElementById('main-content');
                    content.innerHTML = html;

                    // Extract and execute scripts
                    const tempDiv = document.createElement('div');
                    tempDiv.innerHTML = html;
                    const scripts = tempDiv.querySelectorAll('script');

                    scripts.forEach(oldScript => {
                        const newScript = document.createElement('script');
                        if (oldScript.src) {
                            newScript.src = oldScript.src;
                            newScript.async = false;
                        } else {
                            newScript.textContent = oldScript.textContent;
                        }
                        document.body.appendChild(newScript);
                        setTimeout(() => {
                            if (newScript.parentNode) {
                                newScript.parentNode.removeChild(newScript);
                            }
                        }, 100);
                    });

                    // Update active sidebar based on path
                    let route = '';
                    if (path.includes('dashboard')) route = 'dashboard';
                    else if (path.includes('scheme_of_study')) route = 'scheme_of_study';
                    else if (path.includes('add-courses')) route = 'add-courses';
                    else if (path.includes('course-prerequisite')) route = 'course-prerequisite.index';
                    else if (path.includes('teacher')) route = 'teacher.index';
                    else if (path.includes('enrollment')) route = 'enrollment.index';
                    else if (path.includes('allocation')) route = 'allocation.index';
                    else if (path.includes('results')) route = 'results.index';

                    setActiveSidebar(route);

                    // Re-initialize modules
                    setTimeout(() => {
                        if (path.includes('teacher') && typeof window.initTeacherModule === 'function') {
                            window.initTeacherModule();
                        }
                    }, 100);
                });
        });

        // Initial active sidebar set
        document.addEventListener('DOMContentLoaded', function() {
            let currentUrl = window.location.pathname;
            let route = '';

            if (currentUrl.includes('dashboard')) route = 'dashboard';
            else if (currentUrl.includes('scheme_of_study')) route = 'scheme_of_study';
            else if (currentUrl.includes('add-courses')) route = 'add-courses';
            else if (currentUrl.includes('course-prerequisite')) route = 'course-prerequisite.index';
            else if (currentUrl.includes('teacher')) route = 'teacher.index';
            else if (currentUrl.includes('enrollment')) route = 'enrollment.index';
            else if (currentUrl.includes('allocation')) route = 'allocation.index';
            else if (currentUrl.includes('results')) route = 'results.index';

            setActiveSidebar(route);
        });
    </script>

    @yield('scripts')

    <!-- Include any additional JS files -->
    <script src="{{ asset('js/app.js') }}"></script>
</body>

</html>