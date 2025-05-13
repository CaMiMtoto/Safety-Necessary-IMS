@extends('layouts.master')
@section('content')
    <div>
        <!--begin::Toolbar-->
        <div class="mb-5">
            <div class="app-toolbar-wrapper d-flex flex-stack flex-wrap gap-4 w-100">
                <!--begin::Page title-->
                <div class="page-title d-flex flex-column gap-1 me-3 mb-2">
                    <!--begin::Breadcrumb-->
                    <ul class="breadcrumb breadcrumb-separatorless fw-semibold mb-6">
                        <!--begin::Item-->
                        <li class="breadcrumb-item text-gray-700 fw-bold lh-1">
                            <a href="{{ route('admin.dashboard') }}" class="text-gray-500">
                                <i class="bi bi-house fs-3 text-gray-400 me-n1"></i>
                            </a>
                        </li>
                        <!--end::Item-->
                        <!--begin::Item-->
                        <li class="breadcrumb-item text-gray-700 fw-bold lh-1">Dashboard</li>
                        <!--end::Item-->
                        <!--begin::Item-->
                        <li class="breadcrumb-item">
                            <i class="bi bi-chevron-right fs-4 text-gray-700 mx-n1"></i>
                        </li>
                        <!--end::Item-->
                        <!--begin::Item-->
                        <li class="breadcrumb-item text-gray-700">
                            Analytics
                        </li>
                        <!--end::Item-->
                    </ul>
                    <!--end::Breadcrumb-->
                    <!--begin::Title-->
                    <h1 class="page-heading d-flex flex-column justify-content-center text-dark fw-bolder fs-1 lh-0">
                        Dashboard
                    </h1>
                    <!--end::Title-->
                </div>
                <!--end::Page title-->
                <!--begin::Actions-->

                <!--end::Actions-->
            </div>
        </div>
        <!--end::Toolbar-->
        <!--begin::Content-->
        <div class="">

            <!-- Stats Overview -->
            <div class="tw-grid tw-grid-cols-1 md:tw-grid-cols-2 lg:tw-grid-cols-4 tw-gap-6">
                <!-- Total Stock Value -->
                <div class="card card-body bg-primary text-white">
                    <h1>
                        <i class="bi bi-clipboard-data tw-text-3xl text-white"></i>
                    </h1>
                    <h2 class="tw-text-xl font-semibold text-light-primary">Total Stock Value</h2>
                    <p class="tw-text-2xl tw-font-bold mt-2">{{ number_format($totalStockValue) }}</p>
                </div>
                <!-- Total Stock Value -->
                <div class="card card-body bg-success text-white">
                    <h1>
                        <i class="bi bi-graph-up-arrow tw-text-3xl text-white"></i>
                    </h1>
                    <h2 class="tw-text-xl font-semibold  text-light-success">Total Sales</h2>
                    <p class="tw-text-2xl tw-font-bold mt-2">{{ number_format($totalSalesValue) }}</p>
                </div>

                <!-- Low Stock Alerts -->
                <div class="card card-body bg-warning text-white">
                    <h1>
                        <i class="bi bi-journal-arrow-down tw-text-3xl text-white"></i>
                    </h1>
                    <h2 class="tw-text-xl font-semibold  text-light-success">Low Stock Products</h2>
                    <p class="tw-text-2xl tw-font-bold mt-2">{{ count($lowStockProducts) }} Items</p>
                </div>


                <!-- Out of Stock -->

                <div class="card card-body bg-danger text-white">
                    <h1>
                        <i class="bi bi-box-arrow-down tw-text-3xl text-white"></i>
                    </h1>
                    <h2 class="tw-text-xl font-semibold  text-light-success">Out of Stock Products</h2>
                    <p class="tw-text-2xl tw-font-bold mt-2">{{ count($outOfStockProducts) }} Items</p>
                </div>
            </div>

            <!-- Charts and Graphs -->
            <div class="tw-grid tw-grid-cols-1 lg:tw-grid-cols-2 tw-gap-6 tw-mt-8">
                <!-- Sales Trend Chart -->
                <div class="card card-body border-secondary">
                    <h2 class="text-xl font-semibold">Sales Trends</h2>
                    <p>
                        Monthly sales trends for the current year.
                    </p>
                    <canvas id="salesChart"></canvas>
                </div>

                <!-- Top Selling Products -->
                <div class="card card-body border-secondary">
                    <h2 class="tw-text-xl tw-font-semibold">Top Selling Products</h2>
                    <p class="text-muted">
                        Top selling products based on the number of orders.
                    </p>
                    <ul class="tw-list-disc tw-list-inside list-group list-group-flush">
                        @foreach ($topSellingProducts as $product)
                            <li class="list-group-item d-flex justify-content-between dark:tw-rounded-lg mt-2">
                                <div>
                                    <div class=" dark:tw-text-gray-700">{{ $product->name }} </div>
                                    <span class="text-muted tw-text-sm">{{$product->category->name}}</span>
                                </div>
                                <strong class="text-dark dark:tw-text-gray-500">{{ $product->orders_count }} Orders</strong>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
        <!--end::Content-->
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>

        // e.g 2K, 3M, 4B
        const convertToKMB = (value) => {
            let suffix = '';
            if (value > 999 && value <= 999999) {
                suffix = 'K';
                value = value / 1000;
            } else if (value > 999999 && value <= 999999999) {
                suffix = 'M';
                value = value / 1000000;
            } else if (value > 999999999) {
                suffix = 'B';
                value = value / 1000000000;
            }
            return value.toFixed(1) + suffix;
        };

        const ctx = document.getElementById('salesChart').getContext('2d');
        const salesChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: @json($months), // Month labels from the controller
                datasets: [{
                    label: 'Monthly Sales',
                    data: @json($sales), // Sales data from the controller
                    backgroundColor: 'rgba(75, 192, 192, 0.6)',
                    borderColor: 'rgba(75, 192, 192, 1)',
                    fill: true,
                    tension: 0.4,
                    pointRadius: 2.0,
                    pointHoverRadius: 5,
                    pointBackgroundColor: 'rgba(75, 192, 192, 1)',
                    weight: 0.5,
                    borderWidth: 1,
                    barThickness: 15, // Reduce the bar thickness
                }]
            },
            options: {
                scales: {
                    x: {
                        beginAtZero: true,
                        grid: {
                            display: false
                        }
                    },
                    y: {
                        beginAtZero: true,
                        grid: {
                            display: true
                        },
                        ticks: {
                            callback: function (value) {
                                return convertToKMB(value);
                            }
                        }
                    }
                }
            }
        });
    </script>
@endpush
