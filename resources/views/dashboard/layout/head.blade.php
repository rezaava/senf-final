<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title-site')</title>
    <!-- Bootstrap 5 RTL CSS -->
    <link rel="stylesheet" href="{{ asset('boot/bootstrap.rtl.min.css') }}">
        <link rel="shortcut icon" href="{{ asset('img/logo.ico') }}" type="image/x-icon">
    <!-- Vazirmatn Font -->
    {{-- <link href="https://cdn.jsdelivr.net/gh/rastikerdar/vazirmatn@v33.003/Vazirmatn-font-face.css" rel="stylesheet" type="text/css" /> --}}
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://lib.arvancloud.ir/font-awesome/6.3.0/css/all.css">
    <link href="https://lib.arvancloud.ir/sweetalert2/9.17.4/sweetalert2.min.css" rel="stylesheet">
    <style>
      
    :root {
        /* پالت اصلی */
        --primary-color: #2C5EAD;
        --secondary-color: #1591DC;
        --there-color: #4BB8FA;
        --accent-color: #C4E2F5;
    
        /* پس‌زمینه‌ها */
        --main-bg: #EAF5FD;
        --sidebar-bg: #D8ECFA;
        --card-bg: #F4FBFF;
        --card-border: #A8D3F0;
    
        /* متن */
        --text-main: #2C5EAD;
        --text-secondary: #1591DC;
    
        /* تعاملات */
        --hover-color: #D9EFFD;
        --active-color: #C4E2F5;
    
        /* فرم‌ها */
        --input-bg: #FFFFFF;
        --input-border: #8CCBF2;
        --input-focus-border: #1591DC;
        --input-focus-shadow: rgba(21, 145, 220, 0.25);
    
        --input-placeholder: #5CA9DD;
        --input-disabled-bg: #E3F3FD;
        --input-disabled-text: #6A9FC2;
    
        /* دکمه اصلی */
        --btn-primary-bg: #2C5EAD;
        --btn-primary-hover: #245093;
        --btn-primary-active: #1D427A;
        --btn-primary-text: #FFFFFF;
    
        /* دکمه ثانویه */
        --btn-secondary-bg: #1591DC;
        --btn-secondary-hover: #117FC1;
        --btn-secondary-active: #0D6BA4;
        --btn-secondary-text: #FFFFFF;
    
        /* اسکرول */
        --scroll-track: #DDEFFC;
        --scroll-thumb: #1591DC;
        --scroll-thumb-hover: #2C5EAD;
    
        /* سایر */
        --construction-color: #2C5EAD;
        --light-color: #F4FBFF;
        --dark-color: #2C5EAD;
    
        --card-radius: 20px;
        --sidebar-width: 240px;
        --sidebar-width-collapsed: 75px;
        --sidebar-icon-size: 1.4rem;
        --transition: 0.35s cubic-bezier(0.2, 0.8, 0.4, 1);
        --scroll-width: 10px;
    }

        @font-face {
            font-family: dana;
            src: url({{asset('fonts/woff/IRANSansXFaNum-regular.woff')}});
        }
        @font-face {
            font-family: dana-lg;
            src: url({{asset('fonts/woff/IRANSansXFaNum-black.woff')}});
        }

        .header {
            background: var(--card-bg);
            padding: 10px 30px;
            border-radius: var(--card-radius);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
            /* margin-bottom: 25px; */
        }

        body {
            font-family: dana;
            background: var(--main-bg);
            color: var(--text-main);
        }
        .btn-success{
            background-color: var(--btn-primary-bg);
            color: var(--btn-primary-text);
            border: none;
        }
        .btn-success:hover{
            background-color: var(--btn-primary-hover);
        }
        .form-control{
            background-color: var(--input-bg);
            border:2px solid var(--input-border);
            color: var(--input-disabled-text);
        }

        .form-select{
            background-color: var(--input-bg);
            border:2px solid var(--input-border);
            color: var(--input-disabled-text);
        }

        .form-control:focus{
            border: 2px solid var(--input-focus-border);
            box-shadow: 0 0 2px 4px  var(--input-focus-shadow);
        }
        .form-control::placeholder {
            color: var(--input-placeholder);
            opacity: 0.8;
        }
        .sidebar {
            font-family: dana;
            background: var(--sidebar-bg);
            height: 100vh;
            position: fixed;
            right: 0;
            top: 0;
            width: var(--sidebar-width);
            transition: width var(--transition);
            z-index: 1000;
            display: flex;
            flex-direction: column;
            align-items: stretch;
            padding-top: 0;
            box-shadow: 0 0 10px 0 #000a;
            overflow-y: auto;
            overflow-x: hidden;
        }
        .sidebar.collapsed {
            width: var(--sidebar-width-collapsed);
        }
        .sidebar .sidebar-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 24px 20px 12px 12px;
        }
        .sidebar .sidebar-logo {
            color: var(--accent-green);
            font-size: 2rem;
            display: flex;
            align-items: center;
            gap: 6px;
            cursor: pointer;
            user-select: none;
        }
        .sidebar .sidebar-title {
            color: var(--text-main);
            font-family: dana-lg;
            font-size: 1.3rem;
            margin-right: 8px;
            transition: opacity var(--transition);
        }
        .sidebar.collapsed .sidebar-title {
            opacity: 0;
            width: 0;
            overflow: hidden;
        }
        .sidebar .sidebar-toggle {
            background: none;
            border: none;
            color: var(--text-secondary);
            font-size: 1.5rem;
            cursor: pointer;
            transition: color 0.2s;
        }
        .sidebar .sidebar-toggle:hover {
            color: var(--accent-green);
        }
        .sidebar .nav {
            width: 100%;
            margin-top: 24px;
        }
        .sidebar .nav-link {
            color: var(--text-secondary);
            display: flex;
            align-items: center;
            height: 48px;
            font-size: var(--sidebar-icon-size);
            border-radius: 12px;
            margin: 8px 0;
            transition: background 0.2s, color 0.2s, padding var(--transition);
            padding-right: 18px;
            gap: 16px;
            white-space: nowrap;
        }
        .sidebar .nav-link.active {
            background: linear-gradient(135deg, var(--primary-color),var(--there-color));
            color: #fff!important;
            font-weight: bold;
            box-shadow: 0 5px 15px rgba(67, 233, 123, 0.3);
        }

        .sidebar .nav-link .menu-label.active {
            color: var(--light-color);
        }

        .sidebar .nav-link.active1{
            color: var(--accent-green);
            list-style-type: disc;
            font-weight: bold;

        }

        .activeLi{
            color: var(--accent-green);
        }

        .sidebar .nav-link:hover:not(.active) {
            background: var(--hover-color);
            color: var(--text-main);
            transform: translateX(-3px)
        }
        .sidebar .nav-link .menu-label {
            font-size: 1rem;
            color: var(--text-main);
            transition: opacity var(--transition);
        }
        .sidebar.collapsed .nav-link .menu-label {
            opacity: 0;
            width: 0;
            overflow: hidden;
        }
        .sidebar .nav-link .fa {
            margin-left: 0;
        }
        .main-content {
            margin-right: var(--sidebar-width);
            padding: 40px 32px 32px 32px;
            min-height: 100vh;
            background: var(--main-bg);
            transition: margin-right var(--transition);
        }
        .main-content.expanded {
            margin-right: var(--sidebar-width-collapsed);
        }
        .header .user-info {
            display: flex;
            align-items: center;
            gap: 16px;
        }
        .header .user-avatar {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid var(--accent-green);
        }
        .header .user-name {
            color: var(--text-main);
            font-weight: 600;
            font-size: 1.1rem;
            font-family: dana;
        }
        .header .desc {
            color: var(--text-secondary);
            font-size: 1rem;
        }
        .stat-card {
            background: var(--card-bg);
            border-radius: var(--card-radius);
            box-shadow: 0 2px 16px 0 #0003;
            padding: 28px 24px 20px 24px;
            margin-bottom: 24px;
            border: 1px solid var(--card-border);
            color: var(--text-main);
            margin-top: 2rem;
        }
        .stat-card .stat-title {
            color: var(--text-secondary);
            font-size: 1.1rem;
            margin-bottom: 8px;
        }
        .stat-card .stat-value {
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 6px;
        }
        .stat-card .stat-desc {
            color: var(--accent-green);
            font-size: 1rem;
        }
        .card {
            background: var(--card-bg);
            border-radius: var(--card-radius);
            box-shadow: 0 2px 16px 0 #0003;
            border: 1px solid var(--card-border);
            color: var(--text-main);
        }
        .card-header {
            background: transparent;
            border-bottom: none;
            color: var(--text-main);
            font-weight: 600;
            font-size: 1.1rem;
        }
        .notification-area {
            background: var(--card-bg);
            border-radius: var(--card-radius);
            box-shadow: 0 2px 16px 0 #0003;
            border: 1px solid var(--card-border);
            padding: 20px 24px;
            margin-bottom: 24px;
        }
        .notification-area .notif-title {
            color: var(--accent-green);
            font-weight: 700;
            margin-bottom: 12px;
        }
        .notification-area .notif-item {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 10px;
        }
        .notification-area .notif-icon {
            font-size: 1.3rem;
            color: var(--accent-green);
        }
        .notification-area .notif-text {
            color: var(--text-main);
            font-size: 1rem;
        }
        .chart-card {
            background: var(--card-bg);
            border-radius: var(--card-radius);
            box-shadow: 0 2px 16px 0 #0003;
            border: 1px solid var(--card-border);
            padding: 24px 24px 12px 24px;
            margin-bottom: 24px;
        }
        .chart-placeholder {
            width: 100%;
            height: 180px;
            background: linear-gradient(90deg, #232b39 60%, #181f2a 100%);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--text-secondary);
            font-size: 1.2rem;
            font-weight: 500;
        }
        @media (max-width: 991px) {
            .main-content {
                padding: 24px 8px 8px 8px;
            }
        }
        @media (max-width: 767px) {
            .main-content {
                margin-right: 0;
                padding: 16px 4px 16px 4px;
            }
            .sidebar {
                display: none;
            }
            .sidebar.offcanvas-mobile {
                display: none !important;
            }
            .sidebar.offcanvas-mobile.show {
                display: flex !important;
                flex-direction: column;
                position: fixed;
                top: 0;
                right: 0;
                left: 0;
                bottom: 0;
                width: 100vw;
                height: 100vh;
                background: var(--sidebar-bg);
                z-index: 2000;
                box-shadow: 0 0 32px 0 #000a;
                animation: slideInRight 0.3s cubic-bezier(.4,2,.6,1);
            }
            @keyframes slideInRight {
                from { right: -100vw; }
                to { right: 0; }
            }
            .sidebar-header {
                display: flex !important;
                flex-direction: row;
                align-items: center;
                justify-content: space-between;
                padding: 24px 20px 12px 12px;
            }
            .sidebar .close-offcanvas {
                display: block;
                background: none;
                border: none;
                color: var(--text-secondary);
                font-size: 2rem;
                cursor: pointer;
            }
            .sidebar .nav {
                flex-direction: column;
                width: 100%;
                margin-top: 32px;
                align-items: flex-start;
            }
            .sidebar .nav-link {
                margin: 0 0 8px 0;
                padding: 0 24px;
                height: 48px;
                flex-direction: row;
                justify-content: flex-start;
                align-items: center;
                font-size: 1.3rem;
                gap: 16px;
            }
            .sidebar .nav-link .menu-label {
                display: block;
                font-size: 1.05rem;
                color: var(--text-main);
                opacity: 1;
                width: auto;
            }
            .dashboard-header {
                flex-direction: row;
                align-items: center;
                text-align: right;
                gap: 8px;
                justify-content: space-between;
            }
            .dashboard-header .user-info {
                flex-direction: row;
                gap: 8px;
            }
            .dashboard-header .user-avatar {
                width: 36px;
                height: 36px;
            }
            .mobile-menu-btn {
                display: inline-flex !important;
                align-items: center;
                justify-content: center;
                background: var(--card-bg);
                border: 1px solid var(--card-border);
                color: var(--accent-green);
                font-size: 1.3rem;
                padding: 6px 10px;
                border-radius: 12px;
                /* position: fixed;
                top: 36px;
                right: 36px;
                z-index: 2100; */
                box-shadow: 0 2px 8px 0 #0002;
                transition: all 0.2s;
                /* margin-top: 1rem; */
            }
            .mobile-menu-btn:hover {
                background: var(--accent-green);
                color: var(--card-bg);
            }
        }
        @media (min-width: 768px) {
            .mobile-menu-btn {
                display: none !important;
            }
        }

        /* WebKit (Chrome, Edge, Safari) */
::-webkit-scrollbar {
    width: var(--scroll-width);
}

::-webkit-scrollbar-track {
    background: var(--scroll-track);
    border-radius: 10px;
}

::-webkit-scrollbar-thumb {
    background-color: var(--scroll-thumb);
    border-radius: 10px;
    border: 2px solid var(--scroll-track);
    transition: background-color 0.3s ease;
}

::-webkit-scrollbar-thumb:hover {
    background-color: var(--scroll-thumb-hover);
}

/* Firefox */
* {
    scrollbar-width: thin;
    scrollbar-color: var(--scroll-thumb) var(--scroll-track);
}

/* Optional: smooth scrolling */
html {
    scroll-behavior: smooth;
}
    </style>
    @yield('head')
    @yield('title')
</head>