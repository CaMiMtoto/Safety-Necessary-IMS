<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title') {{ config('app.name', 'Laravel') }}</title>

    <x-fav-icon/>
    @livewireStyles
{{--    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700"/>--}}
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <!--end::Fonts-->
    @vite(['resources/sass/master.scss', 'resources/js/master.js','resources/css/app.css'])
    @yield('styles')
</head>
<!--begin::Body-->
<body  id="kt_app_body" data-kt-app-header-fixed="true" data-kt-app-header-fixed-mobile="true"
      data-kt-app-sidebar-enabled="true" data-kt-app-sidebar-fixed="true" data-kt-app-sidebar-hoverable="true"
      data-kt-app-sidebar-push-toolbar="true" data-kt-app-sidebar-push-footer="true" data-kt-app-toolbar-enabled="true"
      data-kt-app-aside-enabled="true" data-kt-app-aside-fixed="true" data-kt-app-aside-push-toolbar="true"
      data-kt-app-aside-push-footer="true" class="app-default tw-font-sans tw-antialiased">
<!--begin::Theme mode setup on page load-->
<script>var defaultThemeMode = "light";
    var themeMode;
    if (document.documentElement) {
        if (document.documentElement.hasAttribute("data-bs-theme-mode")) {
            themeMode = document.documentElement.getAttribute("data-bs-theme-mode");
        } else {
            if (localStorage.getItem("data-bs-theme") !== null) {
                themeMode = localStorage.getItem("data-bs-theme");
            } else {
                themeMode = defaultThemeMode;
            }
        }
        if (themeMode === "system") {
            themeMode = window.matchMedia("(prefers-color-scheme: dark)").matches ? "dark" : "light";
        }
        document.documentElement.setAttribute("data-bs-theme", themeMode);
    }</script>
<!--end::Theme mode setup on page load-->
<!--begin::App-->
<div class="d-flex flex-column flex-root app-root" id="kt_app_root">
    <!--begin::Page-->
    <div class="app-page flex-column flex-column-fluid" id="kt_app_page">
        <!--begin::Header-->
        @include('layouts._header')
        <!--end::Header-->
        <!--begin::Wrapper-->
        <div class="app-wrapper flex-column flex-row-fluid" id="kt_app_wrapper">
            <!--begin::Sidebar-->
            @include('layouts._sideBar')
            <!--end::Sidebar-->
            <!--begin::Main-->
            <div class="app-main flex-column flex-row-fluid" id="kt_app_main">
                @include('client.partials._flash')
                <!--begin::Content wrapper-->
                <div id="kt_app_content" class="card card-body py-10 flex-column-fluid ">
                    <!--begin::Content container-->
                    <div id="kt_app_content_container" class="container-fluid ">
                        @yield('content')
                    </div>
                </div>
                <!--end::Content wrapper-->
                <!--begin::Footer-->
                @include('layouts._footer')
                <!--end::Footer-->
            </div>
            <!--end:::Main-->

        </div>
        <!--end::Wrapper-->
    </div>
    <!--end::Page-->
</div>
<!--end::App-->

<!--begin::Scrolltop-->
<div id="kt_scrolltop" class="scrolltop" data-kt-scrolltop="true">
    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-arrow-up" width="24" height="24"
         viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round"
         stroke-linejoin="round">
        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
        <path d="M12 5l0 14"/>
        <path d="M18 11l-6 -6"/>
        <path d="M6 11l6 -6"/>
    </svg>
</div>
<!--end::Scrolltop-->

<!--end::Modals-->

<!--end::Custom Javascript-->
<!--end::Javascript-->

<script src="{{ asset('assets/plugins/global/plugins.bundle.js') }}"></script>
<script src="{{ asset('assets/js/scripts.bundle.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/pikaday/pikaday.js"></script>
@livewireScripts
<script>
    // initialize pikaday .datepicker
    $(".datepicker").flatpickr();
/*    document.querySelectorAll('.datepicker').forEach(function (el) {
        new Pikaday({
            field: el,
            format: 'YYYY-MM-DD',
            showTodayButton: true,
        });
    });*/
/*
    document.querySelectorAll('.datetimepicker').forEach(function (el) {
        new Pikaday({
            field: el,
            format: 'YYYY-MM-DD HH:mm',
            timeFormat: 'HH:mm',
            defaultDate: new Date(),
            setDefaultDate: true,
        });
    });*/


    $(document).ready(function () {
        $(document).on('click', '.js-delete', function (e) {
            e.preventDefault();
            let url = $(this).attr('href');
            let method = 'DELETE';
            let token = $('meta[name="csrf-token"]').attr('content');
            let data = {
                _token: token,
                _method: method
            };

            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, delete it!',
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: url,
                        method: 'DELETE',
                        data: data,
                        success: function (response) {
                            if (window.dt) {
                                window.dt.ajax.reload();
                            } else {
                                window.location.reload();
                            }
                        },
                        error: function (response) {
                            console.log(response);
                            Swal.fire({
                                title: 'Error!',
                                text: 'Something went wrong, please try again',
                                icon: 'error',
                                confirmButtonText: 'Ok'
                            })
                        }
                    })
                }
            })

        })
        $('.selectize').selectize();
    });
    $(document).on('click', '.js-export', function (e) {
        e.preventDefault();
        let url = $(this).attr('href');
        let $form = $(this).closest('form');
        let data = $form.serialize();
        window.location.href = url + '?' + data;
    });

</script>
@stack('scripts')
</body>
<!--end::Body-->
</html>
