<div id="kt_app_header" class="app-header d-flex flex-column flex-stack">
    <!--begin::Header main-->
    <div class="d-flex align-items-center flex-stack flex-grow-1">
        <div class="app-header-logo d-flex align-items-center flex-stack px-lg-11 mb-2" id="kt_app_header_logo">
            <!--begin::Sidebar mobile toggle-->
            <div class="btn btn-icon btn-active-color-primary w-35px h-35px ms-3 me-2 d-flex d-lg-none"
                 id="kt_app_sidebar_mobile_toggle">
                <i class="ki-duotone ki-abstract-14 fs-2">
                    <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none"
                         stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                         class="icon icon-tabler icons-tabler-outline icon-tabler-square-chevron-left">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                        <path d="M13 15l-3 -3l3 -3"/>
                        <path d="M3 3m0 2a2 2 0 0 1 2 -2h14a2 2 0 0 1 2 2v14a2 2 0 0 1 -2 2h-14a2 2 0 0 1 -2 -2z"/>
                    </svg>
                </i>
            </div>
            <!--end::Sidebar mobile toggle-->
            <!--begin::Logo-->
            <a href="" class="app-sidebar-logo">
                <img alt="Logo" src="{{ asset('assets/media/logos/logo.png') }}"
                     class="tw-h-12  theme-light-show"/>
                <img alt="Logo" src="{{ asset('assets/media/logos/logo.png') }}"
                     class="tw-h-12 theme-dark-show"/>
            </a>
            <!--end::Logo-->
            <!--begin::Sidebar toggle-->
            <div id="kt_app_sidebar_toggle"
                 class="app-sidebar-toggle btn btn-sm btn-icon btn-color-warning me-n2 d-none d-lg-flex"
                 data-kt-toggle="true" data-kt-toggle-state="active" data-kt-toggle-target="body"
                 data-kt-toggle-name="app-sidebar-minimize">
                <i class="ki-duotone ki-exit-left fs-2x rotate-180">
                    <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none"
                         stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                         class="icon icon-tabler icons-tabler-outline icon-tabler-square-chevron-left">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                        <path d="M13 15l-3 -3l3 -3"/>
                        <path d="M3 3m0 2a2 2 0 0 1 2 -2h14a2 2 0 0 1 2 2v14a2 2 0 0 1 -2 2h-14a2 2 0 0 1 -2 -2z"/>
                    </svg>
                </i>
            </div>
            <!--end::Sidebar toggle-->
        </div>
        <!--begin::Navbar-->
        <div class="app-navbar flex-grow-1 justify-content-end" id="kt_app_header_navbar">
            <div class="app-navbar-item d-none d-lg-flex align-items-stretch flex-lg-grow-1 me-2 me-lg-0">
                <div>
                    <h2 class="fw-bolder">
                        {{ config('app.name') }}
                    </h2>
                    <p>
                        Inventory Management System
                    </p>
                </div>
            </div>


            <!--begin::User menu-->
            <div class="app-navbar-item ms-3 ms-lg-4 me-lg-2" id="kt_header_user_menu_toggle">
                <!--begin::Menu wrapper-->
                <div class="cursor-pointer symbol symbol-30px symbol-lg-40px"
                     data-kt-menu-trigger="{default: 'click', lg: 'hover'}" data-kt-menu-attach="parent"
                     data-kt-menu-placement="bottom-end">
                    <img src="{{ auth()->user()->profilePhotoUrl }}" alt="user"/>
                </div>
                <!--begin::User account menu-->
                <div
                    class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 menu-state-bg menu-state-color fw-semibold py-4 fs-6 w-275px"
                    data-kt-menu="true">
                    <!--begin::Menu item-->
                    <div class="menu-item px-3">
                        <div class="menu-content d-flex align-items-center px-3">
                            <!--begin::Avatar-->
                            <div class="symbol symbol-50px me-5">
                                <img alt="Logo" src="{{ auth()->user()->profilePhotoUrl }}"/>
                            </div>
                            <!--end::Avatar-->
                            <!--begin::Username-->
                            <div class="d-flex flex-column">
                                <div class="fw-bold d-flex align-items-center fs-5">
                                    {{ Auth::user()->name }}
                                </div>
                                <a href="#"
                                   class="fw-semibold text-muted text-hover-primary fs-7">
                                    {{ Auth::user()->email }}
                                </a>
                            </div>
                            <!--end::Username-->
                        </div>
                    </div>
                    <!--end::Menu item-->
                    <!--begin::Menu separator-->
                    <div class="separator my-2"></div>
                    <!--end::Menu separator-->
                    <!--begin::Menu item-->
                    <div class="menu-item px-5">
                        <a href="" class="menu-link px-5">My Profile</a>
                    </div>
                    <!--end::Menu item-->


                    <!--end::Menu item-->
                    <!--begin::Menu separator-->
                    <div class="separator my-2"></div>
                    <!--end::Menu separator-->
                    <!--begin::Menu item-->
                    <div class="menu-item px-5" data-kt-menu-trigger="{default: 'click', lg: 'hover'}"
                         data-kt-menu-placement="left-start" data-kt-menu-offset="-15px, 0">
                        <a href="#" class="menu-link px-5">
											<span class="menu-title position-relative">Mode
											<span class="ms-5 position-absolute translate-middle-y top-50 end-0">
												<i class="ki-duotone ki-night-day theme-light-show fs-2">
													<span class="path1"></span>
													<span class="path2"></span>
													<span class="path3"></span>
													<span class="path4"></span>
													<span class="path5"></span>
													<span class="path6"></span>
													<span class="path7"></span>
													<span class="path8"></span>
													<span class="path9"></span>
													<span class="path10"></span>
												</i>
												<i class="ki-duotone ki-moon theme-dark-show fs-2">
													<span class="path1"></span>
													<span class="path2"></span>
												</i>
											</span></span>
                        </a>
                        <!--begin::Menu-->
                        <div
                            class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-title-gray-700 menu-icon-gray-500 menu-active-bg menu-state-color fw-semibold py-4 fs-base w-150px"
                            data-kt-menu="true" data-kt-element="theme-mode-menu">
                            <!--begin::Menu item-->
                            <div class="menu-item px-3 my-0">
                                <a href="#" class="menu-link px-3 py-2" data-kt-element="mode"
                                   data-kt-value="light">
													<span class="menu-icon" data-kt-element="icon">
														<svg xmlns="http://www.w3.org/2000/svg"
                                                             class="icon icon-tabler icon-tabler-brightness-up"
                                                             width="24" height="24" viewBox="0 0 24 24" stroke-width="2"
                                                             stroke="currentColor" fill="none" stroke-linecap="round"
                                                             stroke-linejoin="round"><path stroke="none"
                                                                                           d="M0 0h24v24H0z"
                                                                                           fill="none"/><path
                                                                d="M12 12m-3 0a3 3 0 1 0 6 0a3 3 0 1 0 -6 0"/><path
                                                                d="M12 5l0 -2"/><path d="M17 7l1.4 -1.4"/><path
                                                                d="M19 12l2 0"/><path d="M17 17l1.4 1.4"/><path
                                                                d="M12 19l0 2"/><path d="M7 17l-1.4 1.4"/><path
                                                                d="M6 12l-2 0"/><path d="M7 7l-1.4 -1.4"/></svg>
													</span>
                                    <span class="menu-title">Light</span>
                                </a>
                            </div>
                            <!--end::Menu item-->
                            <!--begin::Menu item-->
                            <div class="menu-item px-3 my-0">
                                <a href="#" class="menu-link px-3 py-2" data-kt-element="mode"
                                   data-kt-value="dark">
													<span class="menu-icon" data-kt-element="icon">
													<svg xmlns="http://www.w3.org/2000/svg"
                                                         class="icon icon-tabler icon-tabler-moon-filled" width="24"
                                                         height="24" viewBox="0 0 24 24" stroke-width="2"
                                                         stroke="currentColor" fill="none" stroke-linecap="round"
                                                         stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z"
                                                                                       fill="none"/><path
                                                            d="M12 1.992a10 10 0 1 0 9.236 13.838c.341 -.82 -.476 -1.644 -1.298 -1.31a6.5 6.5 0 0 1 -6.864 -10.787l.077 -.08c.551 -.63 .113 -1.653 -.758 -1.653h-.266l-.068 -.006l-.06 -.002z"
                                                            stroke-width="0" fill="currentColor"/></svg>
													</span>
                                    <span class="menu-title">Dark</span>
                                </a>
                            </div>
                            <!--end::Menu item-->
                            <!--begin::Menu item-->
                            <div class="menu-item px-3 my-0">
                                <a href="#" class="menu-link px-3 py-2" data-kt-element="mode"
                                   data-kt-value="system">
													<span class="menu-icon" data-kt-element="icon">
													<svg xmlns="http://www.w3.org/2000/svg"
                                                         class="icon icon-tabler icon-tabler-brightness" width="24"
                                                         height="24" viewBox="0 0 24 24" stroke-width="2"
                                                         stroke="currentColor" fill="none" stroke-linecap="round"
                                                         stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z"
                                                                                       fill="none"/><path
                                                            d="M12 12m-9 0a9 9 0 1 0 18 0a9 9 0 1 0 -18 0"/><path
                                                            d="M12 3l0 18"/><path d="M12 9l4.65 -4.65"/><path
                                                            d="M12 14.3l7.37 -7.37"/><path
                                                            d="M12 19.6l8.85 -8.85"/></svg>
													</span>
                                    <span class="menu-title">System</span>
                                </a>
                            </div>
                            <!--end::Menu item-->
                        </div>
                        <!--end::Menu-->
                    </div>
                    <!--end::Menu item-->

                    <!--begin::Menu item-->
                    <div class="menu-item px-5 my-1">
                        <a href="{{ route('users.change-password-view') }}" class="menu-link px-5">Change Password</a>
                    </div>
                    <!--end::Menu item-->
                    <!--begin::Menu item-->
                    <div class="menu-item px-5">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <a href="{{ route('logout') }}" class="menu-link px-5"
                               onclick="event.preventDefault(); this.closest('form').submit();">
                                Sign Out
                            </a>
                        </form>
                    </div>
                    <!--end::Menu item-->
                </div>
                <!--end::User account menu-->
                <!--end::Menu wrapper-->
            </div>
            <!--end::User menu-->
            <!--begin::Action-->
            <!--end::Action-->
            <!--begin::Header menu toggle-->
            <div class="app-navbar-item ms-3 ms-lg-4 ms-n2 me-3 d-flex d-lg-none">
                <div
                    class="btn btn-icon btn-custom btn-color-gray-600 btn-active-color-primary w-35px h-35px w-md-40px h-md-40px"
                    id="kt_app_aside_mobile_toggle">
                    <i class="ki-duotone ki-burger-menu-2 fs-2">
                        <span class="path1"></span>
                        <span class="path2"></span>
                        <span class="path3"></span>
                        <span class="path4"></span>
                        <span class="path5"></span>
                        <span class="path6"></span>
                        <span class="path7"></span>
                        <span class="path8"></span>
                        <span class="path9"></span>
                        <span class="path10"></span>
                    </i>
                </div>
            </div>
            <!--end::Header menu toggle-->
        </div>
        <!--end::Navbar-->
    </div>
    <!--end::Header main-->
    <!--begin::Separator-->
    {{--    <div class="app-header-separator"></div>--}}
    <!--end::Separator-->
</div>
