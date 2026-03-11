<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <link rel="icon" href="{{ asset('icon.ico') }}" type="image/x-icon">

    {{-- Base Meta Tags --}}
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- Custom Meta Tags --}}
    @yield('meta_tags')

    {{-- Title --}}
    <title>
        @yield('title_prefix', config('adminlte.title_prefix', ''))
        @yield('title', config('adminlte.title', 'AdminLTE 3'))
        @yield('title_postfix', config('adminlte.title_postfix', ''))
    </title>

    {{-- Base Stylesheets --}}
    @if(config('adminlte.enabled_laravel_mix', false))
        <link rel="stylesheet" href="{{ mix(config('adminlte.laravel_mix_css_path', 'css/app.css')) }}">
    @else
        <link rel="stylesheet" href="{{ asset('vendor/fontawesome-free/css/all.min.css') }}">
        <link rel="stylesheet" href="{{ asset('vendor/overlayScrollbars/css/OverlayScrollbars.min.css') }}">
        <link rel="stylesheet" href="{{ asset('vendor/adminlte/dist/css/adminlte.min.css') }}">
    @endif

    {{-- DataTables CSS --}}
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.3.6/css/buttons.dataTables.min.css">

    {{-- Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    @if(config('adminlte.google_fonts.allowed', true))
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
    @endif

    {{-- Extra Plugins CSS --}}
    @include('adminlte::plugins', ['type' => 'css'])

    {{-- Livewire Styles --}}
    @if(config('adminlte.livewire'))
        @if(intval(app()->version()) >= 7)
            @livewireStyles
        @else
            <livewire:styles />
        @endif
    @endif

    {{-- Custom Stylesheets --}}
    @yield('adminlte_css')

    {{-- ====== GLOBAL THEME ====== --}}
    <style>
        :root{
            --sidebar:#ebe3d2;
            --sidebar-deep:#d8cfbc;
            --text:#2f3d2f;
            --muted:#5e6655;
            --muted2:#7f8478;
            --stroke:rgba(191,175,145,.38);
            --card:rgba(252,248,239,.95);
            --card2:rgba(241,234,219,.92);
            --shadow-soft:0 24px 60px rgba(54,62,42,.12);
            --shadow-card:0 18px 26px rgba(0,0,0,.18);
            --shadow-long:10px 14px 20px rgba(0,0,0,.12);
            --radius:24px;
            --radius-lg:28px;
        }
        html, body{
            min-height:100%;
        }
        body{
            font-family:Inter,system-ui,-apple-system,"Segoe UI",Roboto,Arial,sans-serif !important;
            color:var(--text);
            background:
                radial-gradient(900px 540px at 12% 10%, rgba(120,149,103,.18), transparent 62%),
                radial-gradient(760px 420px at 88% 18%, rgba(160,114,79,.18), transparent 58%),
                linear-gradient(180deg, #d6d2c4 0%, #cec8b7 100%) !important;
        }
        .wrapper,
        .main-footer{
            background:transparent !important;
        }
        .content-wrapper{
            position:relative;
            background:linear-gradient(180deg, rgba(245,240,227,.94), rgba(240,234,220,.92)) !important;
            box-shadow:inset 0 1px 0 rgba(255,255,255,.55);
        }
        .content-wrapper::before{
            content:"";
            position:absolute;
            inset:0;
            pointer-events:none;
            background:
                radial-gradient(720px 280px at 18% 0%, rgba(111,144,105,.10), transparent 65%),
                radial-gradient(640px 280px at 92% 12%, rgba(164,119,84,.10), transparent 60%);
        }
        .content-header,
        .content{
            position:relative;
            z-index:1;
        }
        .main-header.navbar{
            background:linear-gradient(90deg, rgba(34,71,57,.96), rgba(22,54,44,.92)) !important;
            backdrop-filter:blur(10px);
            border-bottom:1px solid rgba(255,255,255,.14) !important;
            box-shadow:0 12px 26px rgba(34,56,43,.18);
        }
        .main-header .nav-link,
        .main-header .navbar-nav .nav-link{
            color:#f7efdf !important;
            font-weight:700;
        }
        .main-header .nav-link:hover{
            color:#fffaf1 !important;
        }
        .main-sidebar{
            background:
                radial-gradient(360px 280px at 12% 0%, rgba(126,154,111,.16), transparent 58%),
                radial-gradient(300px 220px at 92% 14%, rgba(176,131,93,.14), transparent 55%),
                linear-gradient(180deg, rgba(244,239,228,.98), rgba(224,216,198,.96)) !important;
            border-right:1px solid rgba(168,151,121,.28) !important;
            box-shadow:10px 0 30px rgba(91,80,60,.16);
        }
        .brand-link{
            background:linear-gradient(180deg, rgba(236,230,216,.92), rgba(227,219,201,.84)) !important;
            border-bottom:1px solid rgba(168,151,121,.24) !important;
            padding-top:1rem !important;
            padding-bottom:1rem !important;
        }
        .brand-link .brand-text{
            color:#31473b !important;
            font-weight:800 !important;
            letter-spacing:.2px;
        }
        .sidebar{
            padding-top:.7rem;
            padding-left:.35rem;
            padding-right:.35rem;
        }
        .nav-header{
            color:#534536 !important;
            font-weight:900 !important;
            font-size:1rem !important;
            letter-spacing:.3px;
            padding:1rem 1rem .45rem 1rem !important;
            margin-top:.35rem !important;
        }
        .nav-sidebar .nav-item{
            margin-bottom:.42rem;
        }
        .nav-sidebar > .nav-item > .nav-link{
            position:relative;
            display:flex;
            align-items:center;
            min-height:48px;
            margin:0 12px !important;
            padding:.9rem 1rem !important;
            border-radius:20px !important;
            color:#f3e7d9 !important;
            font-weight:800 !important;
            font-size:1rem !important;
            border:1px solid rgba(255,255,255,.06) !important;
            box-shadow:inset 0 1px 0 rgba(255,255,255,.10), var(--shadow-card), var(--shadow-long);
            transition:transform .18s ease, box-shadow .18s ease, filter .18s ease;
            overflow:hidden;
        }
        .nav-sidebar .nav-link p{
            display:flex;
            align-items:center;
            justify-content:space-between;
            gap:.75rem;
            flex:1 1 auto;
            min-width:0;
            margin:0 !important;
            white-space:normal !important;
            line-height:1.2 !important;
        }
        .nav-sidebar .nav-link p .right,
        .nav-sidebar .nav-link p i.right{
            flex:0 0 auto;
            margin-left:auto !important;
        }
        .nav-sidebar .nav-link .nav-icon{
            flex:0 0 1.4rem;
            width:1.4rem;
            margin-right:.7rem;
            font-size:1.05rem;
            text-align:center;
        }
        .nav-sidebar > .nav-item > .nav-link .nav-icon,
        .nav-sidebar > .nav-item > .nav-link .right,
        .nav-sidebar > .nav-item > .nav-link i.right{
            color:rgba(255,248,240,.95) !important;
        }
        .nav-sidebar > .nav-item:nth-of-type(1) > .nav-link{
            background:linear-gradient(135deg, #84a77f 0%, #5e8666 100%) !important;
        }
        .nav-sidebar > .nav-item:nth-of-type(2) > .nav-link{
            background:linear-gradient(135deg, #c18a63 0%, #996347 100%) !important;
        }
        .nav-sidebar > .nav-item:nth-of-type(3) > .nav-link{
            background:linear-gradient(135deg, #be8759 0%, #8c5b37 100%) !important;
        }
        .nav-sidebar > .nav-item:nth-of-type(4) > .nav-link{
            background:linear-gradient(135deg, #9c6748 0%, #73472d 100%) !important;
        }
        .nav-sidebar > .nav-item:nth-of-type(5) > .nav-link{
            background:linear-gradient(135deg, #a67458 0%, #7f523a 100%) !important;
        }
        .nav-sidebar > .nav-item:nth-of-type(6) > .nav-link{
            background:linear-gradient(135deg, #b38a68 0%, #8a6449 100%) !important;
        }
        .nav-sidebar > .nav-item:nth-of-type(7) > .nav-link{
            background:linear-gradient(135deg, #dbc4a0 0%, #f0dfc5 100%) !important;
            color:#6b4f3b !important;
        }
        .nav-sidebar > .nav-item:nth-of-type(7) > .nav-link .nav-icon,
        .nav-sidebar > .nav-item:nth-of-type(7) > .nav-link .right{
            color:#7b5b46 !important;
        }
        .nav-sidebar > .nav-item > .nav-link:hover{
            transform:translateY(-2px);
            filter:brightness(1.05);
            box-shadow:inset 0 1px 0 rgba(255,255,255,.12), 0 20px 30px rgba(0,0,0,.22), 12px 18px 26px rgba(0,0,0,.16);
        }
        .nav-sidebar > .nav-item > .nav-link.active{
            outline:2px solid rgba(255,255,255,.20);
            filter:brightness(1.08);
        }
        .nav-sidebar > .nav-item > .nav-link.active::after{
            content:"";
            position:absolute;
            inset:0;
            border-radius:20px;
            background:linear-gradient(180deg, rgba(255,255,255,.10), rgba(255,255,255,0));
            pointer-events:none;
        }
        .nav-sidebar .menu-open > .nav-link{
            filter:brightness(1.03);
        }
        .nav-sidebar .nav-treeview{
            margin-top:.3rem !important;
            padding-left:.35rem !important;
            padding-bottom:.15rem !important;
        }
        .nav-sidebar .nav-treeview .nav-item{
            margin-bottom:.32rem;
        }
        .nav-sidebar .nav-treeview .nav-link{
            display:flex;
            align-items:center;
            background:rgba(255,255,255,.22) !important;
            border:1px solid rgba(168,151,121,.20) !important;
            border-radius:16px !important;
            min-height:42px;
            margin:0 10px 0 20px !important;
            padding:.72rem .9rem !important;
            color:#5c4937 !important;
            font-weight:700 !important;
            box-shadow:none !important;
        }
        .nav-sidebar .nav-treeview .nav-link:hover{
            background:rgba(255,255,255,.09) !important;
            transform:translateY(-1px);
            box-shadow:0 10px 18px rgba(0,0,0,.16) !important;
        }
        .nav-sidebar .nav-treeview .nav-link.active{
            background:linear-gradient(135deg, rgba(225,205,174,.55), rgba(255,255,255,.30)) !important;
            border:1px solid rgba(168,151,121,.26) !important;
            color:#4f3d2f !important;
            outline:none;
        }
        .nav-sidebar .nav-treeview .nav-link,
        .nav-sidebar .nav-treeview .nav-link .nav-icon,
        .nav-sidebar .nav-treeview .nav-link p,
        .nav-sidebar .nav-treeview .nav-link .right,
        .nav-sidebar .nav-treeview .nav-link i.right{
            color:inherit !important;
        }
        [class*="sidebar-dark-"] .nav-sidebar > .nav-item > .nav-link.active{
            color:inherit !important;
        }
        .sidebar-mini.sidebar-collapse .nav-sidebar .nav-link{
            margin:6px 8px !important;
            border-radius:18px !important;
        }
        @media (max-width: 991.98px){
            .main-sidebar{
                width:290px !important;
            }
            .sidebar{
                padding-left:.45rem;
                padding-right:.45rem;
            }
            .nav-sidebar > .nav-item > .nav-link{
                margin:0 8px !important;
                padding:.82rem .9rem !important;
                border-radius:18px !important;
            }
            .nav-sidebar .nav-treeview .nav-link{
                margin:0 8px 0 18px !important;
            }
        }
        .card,
        .small-box,
        .info-box{
            background:linear-gradient(180deg, rgba(252,248,239,.96), rgba(241,234,219,.92)) !important;
            border:1px solid rgba(191,175,145,.48) !important;
            border-radius:24px !important;
            color:var(--text) !important;
            box-shadow:var(--shadow-soft) !important;
            overflow:hidden;
        }
        .card-header{
            background:linear-gradient(180deg, rgba(255,255,255,.42), rgba(239,232,216,.80)) !important;
            border-bottom:1px solid rgba(191,175,145,.38) !important;
            border-top-left-radius:24px !important;
            border-top-right-radius:24px !important;
        }
        .card-title,
        .card-header .btn-tool,
        .small-box,
        .info-box{
            color:var(--text) !important;
        }
        .small-box .icon,
        .info-box .info-box-icon{
            color:rgba(62,83,59,.16) !important;
        }
        .small-box.bg-primary{
            background:linear-gradient(135deg, #eef0e1, #dfe8d2) !important;
        }
        .small-box.bg-success{
            background:linear-gradient(135deg, #dceacf, #bfd4b4) !important;
        }
        .btn{
            border-radius:16px !important;
            font-weight:800 !important;
            border:1px solid transparent !important;
            box-shadow:0 10px 22px rgba(90,81,60,.12);
        }
        .btn-primary{
            color:#fff7ef !important;
            background:linear-gradient(135deg, #8d6a50, #6f4e38) !important;
            border-color:#6f4e38 !important;
        }
        .btn-primary:hover{
            transform:translateY(-1px);
            filter:brightness(1.05);
        }
        .btn-success{
            color:#fdf8f0 !important;
            background:linear-gradient(135deg, #6e8d62, #56704e) !important;
            border-color:#56704e !important;
        }
        .btn-warning{
            background:linear-gradient(135deg, #c49666, #9d6d47) !important;
            color:#fff8f2 !important;
            border-color:#9d6d47 !important;
        }
        .btn-danger{
            color:#fff7ef !important;
            background:linear-gradient(135deg, #b17160, #894e41) !important;
            border-color:#894e41 !important;
        }
        .btn-info{
            color:#fff9f2 !important;
            background:linear-gradient(135deg, #6b8671, #4f6858) !important;
            border-color:#4f6858 !important;
        }
        .form-control,
        .custom-select,
        select.form-control{
            background:rgba(255,252,245,.88) !important;
            border:1px solid rgba(191,175,145,.48) !important;
            color:var(--text) !important;
            border-radius:16px !important;
            min-height:44px;
            box-shadow:inset 0 1px 2px rgba(98,90,70,.05);
        }
        .form-control::placeholder{
            color:var(--muted2) !important;
        }
        .form-control:focus,
        .custom-select:focus,
        select.form-control:focus{
            background:#fffdf8 !important;
            border-color:rgba(111,144,105,.55) !important;
            box-shadow:0 0 0 .15rem rgba(111,144,105,.12) !important;
            color:var(--text) !important;
        }
        label{
            color:var(--muted) !important;
            font-weight:700 !important;
        }
        .table,
        table.dataTable{
            color:var(--text) !important;
        }
        .table thead th,
        table.dataTable thead th{
            background:rgba(215,204,182,.28) !important;
            color:var(--text) !important;
            border-bottom:1px solid rgba(191,175,145,.42) !important;
        }
        .table td,
        .table th,
        table.dataTable td,
        table.dataTable th{
            border-top:1px solid rgba(191,175,145,.28) !important;
        }
        .table-striped tbody tr:nth-of-type(odd){
            background:rgba(247,243,233,.75) !important;
        }
        .dataTables_wrapper .dataTables_filter input,
        .dataTables_wrapper .dataTables_length select{
            background:rgba(255,252,245,.88) !important;
            border:1px solid rgba(191,175,145,.45) !important;
            color:var(--text) !important;
            border-radius:14px !important;
        }
        .dataTables_wrapper .dataTables_info,
        .dataTables_wrapper .dataTables_paginate{
            color:var(--muted) !important;
        }
        .dataTables_wrapper .dataTables_paginate .paginate_button{
            color:var(--text) !important;
            border-radius:12px !important;
            border:1px solid rgba(191,175,145,.40) !important;
            background:rgba(251,247,236,.88) !important;
        }
        .dataTables_wrapper .dataTables_paginate .paginate_button.current{
            background:linear-gradient(135deg, #8d6a50, #6f4e38) !important;
            color:#fff8f2 !important;
            border-color:transparent !important;
        }
        .alert{
            background:rgba(248,244,235,.92) !important;
            border:1px solid rgba(191,175,145,.42) !important;
            border-radius:18px !important;
            color:var(--text) !important;
        }
        .modal-content{
            background:linear-gradient(180deg, rgba(248,243,232,.98), rgba(239,233,220,.96)) !important;
            border:1px solid rgba(191,175,145,.42) !important;
            border-radius:24px !important;
            box-shadow:0 30px 60px rgba(61,56,43,.22) !important;
            color:var(--text) !important;
        }
        .modal-header,
        .modal-footer{
            border-color:rgba(191,175,145,.34) !important;
        }
        .content-header .breadcrumb{
            background:rgba(250,245,234,.78) !important;
            border:1px solid rgba(191,175,145,.34) !important;
            border-radius:16px !important;
        }
        .breadcrumb-item a{
            color:var(--muted) !important;
            font-weight:700;
        }
        .breadcrumb-item.active{
            color:var(--text) !important;
        }
        .content-header h1{
            color:var(--text) !important;
            font-weight:900 !important;
            letter-spacing:-.4px;
        }
        .main-footer{
            color:var(--muted) !important;
            border-top:1px solid rgba(191,175,145,.28) !important;
        }
        .os-theme-light>.os-scrollbar>.os-scrollbar-track>.os-scrollbar-handle{
            background:rgba(112,120,92,.28) !important;
        }
        ::-webkit-scrollbar{
            width:10px;
            height:10px;
        }
        ::-webkit-scrollbar-track{
            background:rgba(123,122,104,.08);
        }
        ::-webkit-scrollbar-thumb{
            background:rgba(100,112,84,.32);
            border-radius:999px;
        }
        ::-webkit-scrollbar-thumb:hover{
            background:rgba(100,112,84,.44);
        }
    </style>
    {{-- Extra per-page theme overrides (optional) --}}
    @stack('styles')
</head>

<body class="@yield('classes_body')" @yield('body_data')>
    {{-- Body Content --}}
    @yield('body')

    {{-- Base Scripts --}}
    @if(config('adminlte.enabled_laravel_mix', false))
        <script src="{{ mix(config('adminlte.laravel_mix_js_path', 'js/app.js')) }}"></script>
    @else
        <script src="{{ asset('vendor/jquery/jquery.min.js') }}"></script>
        <script src="{{ asset('vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
        <script src="{{ asset('vendor/overlayScrollbars/js/jquery.overlayScrollbars.min.js') }}"></script>
        <script src="{{ asset('vendor/adminlte/dist/js/adminlte.min.js') }}"></script>
    @endif

    {{-- DataTables JS --}}
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.3.6/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.print.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>

    {{-- Extra Plugins JS --}}
    @include('adminlte::plugins', ['type' => 'js'])

    {{-- Livewire Scripts --}}
    @if(config('adminlte.livewire'))
        @if(intval(app()->version()) >= 7)
            @livewireScripts
        @else
            <livewire:scripts />
        @endif
    @endif

    {{-- Custom Scripts --}}
    @yield('adminlte_js')

    {{-- Extra per-page scripts (optional) --}}
    @stack('scripts')
</body>
</html>




