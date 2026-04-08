<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title')</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <script src="https://cdn.tailwindcss.com"></script>

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
        }

        /* ================= HAMBURGER ================= */
        .hamburger {
            position: fixed;
            top: 10px;
            height: 60px;
            width: 50px;

            left: 260px;

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
        }

        /* Move when collapsed */
        body.sidebar-collapsed .hamburger {
            left: 20px !important;
        }

        /* Hide when sidebar open */
        body:not(.sidebar-collapsed) .hamburger {
            display: none;
        }

        /* ================= SIDEBAR ================= */
        .sidebar {
            width: 250px;
            height: 100vh;
            background: #0f1b5c;
            position: fixed;
            top: 0;
            left: -250px;
            transition: 0.3s ease;
            padding: 20px 0;
            overflow-y: auto;
            box-shadow: 2px 0 10px rgba(0, 0, 0, 0.2);
            z-index: 1000;
        }

        .sidebar.active {
            left: 0;
        }

        /* CLOSE BUTTON */
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

        .sidebar ul li a i {
            margin-right: 12px;
            width: 20px;
        }

        .sidebar ul li a:hover {
            background: rgba(255, 255, 255, 0.15);
            transform: translateX(5px);
        }

        /* ================= CONTENT ================= */
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
            gap: 8px;

            background: transparent;
            color: #fff;

            border: 1px solid #fff;
            padding: 6px 14px;
            border-radius: 8px;

            cursor: pointer;
            font-weight: 500;

            transition: all 0.3s ease;
        }

        /* Hover Effect */
        .back-btn:hover {
            background: #fff;
            color: #0f1b5c;
            transform: translateX(-4px);
        }

        /* Icon styling */
        .back-btn i {
            font-size: 14px;
        }
    </style>

    @yield('styles')
</head>

<body>

    <!-- HAMBURGER (ONLY FOR OPENING) -->
    <i class="fas fa-bars hamburger" id="hamburger"></i>

    <!-- SIDEBAR -->
    @include('layouts.sidebar')

    <!-- TOPBAR -->
    <div class="topbar">
        <button class="back-btn" onclick="window.history.back()">
            <i class="fas fa-chevron-left"></i>
            {{-- <span>Back</span> --}}

        </button>

        <div class="font-semibold text-lg">
            <span>@yield('title')</span>
        </div>

        <div class="profile">
            <span>{{ Auth::user()->name ?? 'User' }}</span>
            <img src="https://ui-avatars.com/api/?name={{ Auth::user()->name ?? 'User' }}">
        </div>
    </div>

    <!-- CONTENT -->
    <div class="content">
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

            // CLOSE BUTTON INSIDE SIDEBAR
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

        // HAMBURGER → ONLY OPENS SIDEBAR
        hamburger.addEventListener('click', (e) => {
            e.stopPropagation();

            sidebar.classList.add('active');
            localStorage.setItem('sidebarOpen', true);

            setLayout(true);
        });
    </script>

    @yield('scripts')

</body>

</html>