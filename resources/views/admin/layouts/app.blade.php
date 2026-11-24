<!doctype html>
<html lang="en">

<head>
    <title>@yield('title') - Enterprise ERP</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script>
        (function() {
            var savedTheme = localStorage.getItem('erp_theme_class');
            if (savedTheme) {
                document.documentElement.className = savedTheme;
            }
        })();
    </script>
    <!--favicon-->
    <link rel="icon" href="{{ asset('admin/assets/images/favicon-32x32.png') }}" type="image/png" />
    <!-- Google Fonts (Rukada Roboto) -->
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <!--plugins-->
    <link href="{{ asset('admin/assets/plugins/vectormap/jquery-jvectormap-2.0.2.css') }}" rel="stylesheet" />
    <link href="{{ asset('admin/assets/plugins/simplebar/css/simplebar.css') }}" rel="stylesheet" />
    <link href="{{ asset('admin/assets/plugins/perfect-scrollbar/css/perfect-scrollbar.css') }}" rel="stylesheet" />
    <link href="{{ asset('admin/assets/plugins/metismenu/css/metisMenu.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('admin/assets/plugins/datatable/css/dataTables.bootstrap5.min.css') }}" rel="stylesheet" />
    <!-- SweetAlert2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet" />
    <!-- loader-->
    <link href="{{ asset('admin/assets/css/pace.min.css') }}" rel="stylesheet" />
    <script src="{{ asset('admin/assets/js/pace.min.js') }}"></script>
    <!-- Bootstrap & Rukada Core CSS -->
    <link href="{{ asset('admin/assets/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('admin/assets/css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('admin/assets/css/icons.css') }}" rel="stylesheet">
    <!-- Theme Style CSS -->
    <link rel="stylesheet" href="{{ asset('admin/assets/css/dark-theme.css') }}" />
    <link rel="stylesheet" href="{{ asset('admin/assets/css/semi-dark.css') }}" />
    <link rel="stylesheet" href="{{ asset('admin/assets/css/header-colors.css') }}" />
    @stack('styles')
</head>

<body>
    <!--wrapper-->
    <div class="wrapper">
        @include('admin.layouts.sidebar')
        @include('admin.layouts.header')

        <main>
            @yield('content')
        </main>

        <!--start overlay-->
        <div class="overlay toggle-icon"></div>
        <!--end overlay-->
        <!--Start Back To Top Button-->
        <a href="javaScript:;" class="back-to-top"><i class='bx bxs-up-arrow-alt'></i></a>
        <!--End Back To Top Button-->

        @include('admin.layouts.footer')
    </div>
    <!--end wrapper-->

    @include('admin.layouts.theme-customizer')

    <!-- Core & Plugin JS (Local from vertical/assets) -->
    <script src="{{ asset('admin/assets/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('admin/assets/js/jquery.min.js') }}"></script>
    <script src="{{ asset('admin/assets/plugins/simplebar/js/simplebar.min.js') }}"></script>
    <script src="{{ asset('admin/assets/plugins/metismenu/js/metisMenu.min.js') }}"></script>
    <script src="{{ asset('admin/assets/plugins/perfect-scrollbar/js/perfect-scrollbar.js') }}"></script>
    <!-- DataTables -->
    <script src="{{ asset('admin/assets/plugins/datatable/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('admin/assets/plugins/datatable/js/dataTables.bootstrap5.min.js') }}"></script>
    <!-- Charts & Visualization Engines -->
    <script src="{{ asset('admin/assets/plugins/apexcharts-bundle/js/apexcharts.min.js') }}"></script>
    <script src="{{ asset('admin/assets/plugins/chartjs/js/Chart.min.js') }}"></script>
    <script src="{{ asset('admin/assets/plugins/jquery.easy-pie-chart/jquery.easypiechart.min.js') }}"></script>
    <script src="{{ asset('admin/assets/plugins/sparkline-charts/jquery.sparkline.min.js') }}"></script>
    <script src="{{ asset('admin/assets/plugins/jquery-knob/excanvas.js') }}"></script>
    <script src="{{ asset('admin/assets/plugins/jquery-knob/jquery.knob.js') }}"></script>
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- Rukada App JS -->
    <script src="{{ asset('admin/assets/js/app.js') }}"></script>

    <script>
        $(function() {
            if ($.fn.knob) {
                $(".knob").knob();
            }
            // Sync Theme Customizer radio buttons & persist changes
            const savedTheme = localStorage.getItem('erp_theme_class') || '';
            if (savedTheme.includes('dark-theme')) $('#darkmode').prop('checked', true);
            else if (savedTheme.includes('semi-dark')) $('#semidark').prop('checked', true);
            else if (savedTheme.includes('minimal-theme')) $('#minimaltheme').prop('checked', true);
            else $('#lightmode').prop('checked', true);

            $('.switcher-body input[type="radio"], .switcher-body .indigator').on('click change', function() {
                setTimeout(function() {
                    localStorage.setItem('erp_theme_class', $('html').attr('class') || '');
                }, 50);
            });
        });
    </script>

    @include('admin.components.ajax-form-handler')
    @include('admin.components.ajax-action-handler')

    @stack('scripts')
</body>

</html>
