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
                    <li class="breadcrumb-item text-gray-700 fw-bold lh-1">
                        <a href="{{ route('admin.sales_payment.index') }}">
                            Sales Payments
                        </a>
                    </li>
                    <!--end::Item-->
                    <!--begin::Item-->
                    <li class="breadcrumb-item">
                        <i class="bi bi-chevron-right fs-4 text-gray-700 mx-n1"></i>
                    </li>
                    <!--end::Item-->
                    <!--begin::Item-->
                    <li class="breadcrumb-item text-gray-700">
                        Sales Payment Report
                    </li>
                    <!--end::Item-->
                </ul>
                <!--end::Breadcrumb-->
                <!--begin::Title-->
                <h1 class="page-heading d-flex flex-column justify-content-center text-dark fw-bolder fs-1 lh-0">
                    Sales Payment Report
                </h1>
                <!--end::Title-->
            </div>
            <!--end::Page title-->
            <!--begin::Actions-->
            <div>
                <form action="{{route("admin.reports.print-payments")}}" target="_blank"
                      class="d-flex align-items-center gap-2">
                    <input type="text" class="datepicker form-control form-control-sm" wire:model.live="startDate"
                           name="start_date"
                           placeholder="Start Date"/>
                    <input type="text" class="datepicker  form-control form-control-sm" wire:model.live="endDate"
                           name="end_date"
                           placeholder="End Date"/>
                    <select class="form-select form-select-sm" name="paymentMethodId" wire:model.live="paymentMethodId">
                        <option value="">All Methods</option>
                        @foreach($paymentMethods as $item)
                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                        @endforeach
                    </select>
                    <button type="submit" class="btn btn-sm btn-danger flex-shrink-0">
                        View
                        <i class="bi bi-file-pdf"></i>
                    </button>
                </form>
            </div>
            <!--end::Actions-->
        </div>
    </div>
    <!--end::Toolbar-->
    <!--begin::Content-->
    <div>
        <div class="card card-body mb-4">
            <div class="table-responsive tw-max-h-[60vh] overflow-scroll">

                <div wire:loading.flex class="tw-m-auto tw-w-full  justify-content-center align-items-center">
                    <div class="d-flex justify-content-center align-items-center gap-2">
                        <x-lucide-loader class="tw-w-10 tw-h-10 tw-animate-spin"/>
                        <p class="tw-ml-2 mb-0">Loading...</p>
                    </div>
                </div>

                <table class="table table-row-dashed table-row-gray-300 align-middle table-striped gy-2"
                       wire:loading.class="opacity-50">
                    <thead>
                    <tr class="text-uppercase fw-semibold  text-gray-800 border-bottom border-gray-200">
                        <th>Sales Order</th>
                        <th>Date</th>
                        <th>Payment Method</th>
                        <th>Amount</th>
                        <th>Customer Name</th>
                        <th>Cashier</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($payments as $item)
                        <tr>
                            <td>{{$item->saleOrder->invoice_number}}</td>
                            <td>{{$item->payment_date->format('d-m-Y')}}</td>
                            <td>{{ $item->paymentMethod->name }}</td>
                            <td>{{number_format($item->amount)}}</td>
                            <td>{{$item->saleOrder->customer->name}}</td>
                            <td>{{ $item->user?->name??'N/A' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center">
                                <p>
                                    No purchase orders found for the selected date range.
                                </p>
                            </td>
                        </tr>
                    @endforelse

                    </tbody>
                </table>
            </div>
        </div>
        <div>

            <div class="row">

                @foreach($paymentMethodsTotals as $item)
                    <div class="col-lg-3 my-1 col-md-4 col-6">
                        <div class="card card-body">
                            <div class="d-flex align-items-center gap-4">
                                <div>
                                    <div class="fw-bold">{{$item->payment_method}}:</div>
                                    <div class="text-gray-700">{{ number_format($item->total_amount) }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
                    <div class="col-lg-3 my-1 col-md-4 col-6">
                        <div class="card card-body">
                            <div class="d-flex align-items-center gap-4">
                                <div>
                                    <div class="fw-bold">Total Sales:</div>
                                    <div class="text-gray-700">{{ number_format($totalSales) }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
            </div>


        </div>
    </div>
    <!--end::Content-->
</div>
