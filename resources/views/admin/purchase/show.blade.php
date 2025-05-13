@extends('layouts.master')
@section('title', 'Order Details')
@section('content')
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
                        <a href="{{ route('admin.purchase-orders.index') }}">
                            Purchase Orders
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
                        Order Details
                    </li>
                    <!--end::Item-->
                </ul>
                <!--end::Breadcrumb-->
                <!--begin::Title-->
                <h1 class="page-heading d-flex flex-column justify-content-center text-dark fw-bolder fs-1 lh-0">
                    Order Details
                </h1>
                <!--end::Title-->
            </div>
            <!--end::Page title-->
            <!--begin::Actions-->
            <a href="{{ route('admin.purchase-orders.index') }}" class="btn btn-sm btn-light">
                <i class="bi bi-arrow-left fs-4"></i>
                Go Back
            </a>
            <!--end::Actions-->
        </div>
    </div>
    <!--end::Toolbar-->
    <div class="my-3">
        <div class="card">
            <!--begin::Body-->
            <div class="card-body p-lg-20">
                <!--begin::Layout-->
                <div class="d-flex flex-column flex-xl-row">
                    <!--begin::Content-->
                    <div class="flex-lg-row-fluid me-xl-18 mb-10 mb-xl-0">
                        <!--begin::Invoice 2 content-->
                        <div class="mt-n1">
                            <!--begin::Top-->
                            <div class="d-flex flex-stack pb-10">
                                <!--begin::Logo-->
                                <a href="#">
                                    <img alt="Logo" src="{{ asset('assets/media/logos/logo.png') }}" class="tw-w-32">
                                </a>
                                <!--end::Logo-->

                                <!--begin::Action-->
                                <a href="{{ route('admin.purchase-orders.print',$purchaseOrder->id) }}" target="_blank" class="btn btn-sm btn-danger">
                                    <i class="bi bi-file-pdf"></i>
                                    Print Order
                                </a>
                                <!--end::Action-->
                            </div>
                            <!--end::Top-->

                            <!--begin::Wrapper-->
                            <div class="m-0">
                                <!--begin::Label-->
                                <div class=" fs-3 text-gray-800 mb-8">Invoice # <strong>{{ $purchaseOrder->invoice_number }}</strong></div>
                                <!--end::Label-->

                                <!--begin::Row-->
                                <div class="row g-5 mb-11">
                                    <!--end::Col-->
                                    <div class="col-sm-6">
                                        <!--end::Label-->
                                        <div class="fw-semibold fs-7 text-gray-600 mb-1">Delivery Date:</div>
                                        <!--end::Label-->

                                        <!--end::Col-->
                                        <div class="fw-bold fs-6 text-gray-800">
                                            {{ $purchaseOrder->delivery_date->format('d M Y') }}
                                        </div>
                                        <!--end::Col-->
                                    </div>
                                    <!--end::Col-->

                                    <!--end::Col-->
                                    <div class="col-sm-6">
                                        <!--end::Label-->
                                        <div class="fw-semibold fs-7 text-gray-600 mb-1">Print Date: </div>
                                        <!--end::Label-->

                                        <!--end::Info-->
                                        <div class="fw-bold fs-6 text-gray-800 d-flex align-items-center flex-wrap">
                                            <span class="pe-2">{{ now()->format('d M Y, H:i:s') }}</span>
                                        </div>
                                        <!--end::Info-->
                                    </div>
                                    <!--end::Col-->
                                </div>
                                <!--end::Row-->

                                <!--begin::Row-->
                                <div class="row g-5 mb-12">
                                    <!--end::Col-->
                                    <div class="col-sm-6">
                                        <!--end::Label-->
                                        <div class="fw-semibold fs-7 text-gray-600 mb-1">Issue For:</div>
                                        <!--end::Label-->

                                        <!--end::Text-->
                                        <div class="fw-bold fs-6 text-gray-800">
                                            Global Engineering Agency
                                        </div>
                                        <!--end::Text-->

                                        <!--end::Description-->
                                        <div class="fw-semibold fs-7 text-gray-600">
                                            KG 33 Avenue Road Gakiriro Road<br>
                                            Umukindo house ,2nd floor front wing Kigali Gasabo
                                        </div>
                                        <!--end::Description-->
                                    </div>
                                    <!--end::Col-->

                                    <!--end::Col-->
                                    <div class="col-sm-6">
                                        <!--end::Label-->
                                        <div class="fw-semibold fs-7 text-gray-600 mb-1">Issued By:</div>
                                        <!--end::Label-->

                                        <!--end::Text-->
                                        <div class="fw-bold fs-6 text-gray-800">
                                            {{ optional($purchaseOrder->supplier)->name }}
                                        </div>
                                        <!--end::Text-->

                                        <!--end::Description-->
                                        <div class="fw-semibold fs-7 text-gray-600">
                                            {{ optional($purchaseOrder->supplier)->address }}
                                        </div>
                                        <!--end::Description-->
                                    </div>
                                    <!--end::Col-->
                                </div>
                                <!--end::Row-->


                                <!--begin::Content-->
                                <div class="flex-grow-1">
                                    <!--begin::Table-->
                                    <div class="table-responsive border-bottom mb-9">
                                        <table class="table mb-3">
                                            <thead>
                                            <tr class="border-bottom fs-6 fw-bold text-muted">
                                                <th class="min-w-175px pb-2">Product</th>
                                                <th class="min-w-70px text-end pb-2">Price</th>
                                                <th class="min-w-80px text-end pb-2">Qty</th>
                                                <th class="min-w-100px text-end pb-2">Total</th>
                                            </tr>
                                            </thead>

                                            <tbody>
                                            @foreach($purchaseOrder->items as $item)
                                                <tr class="fw-bold text-gray-700 fs-5 text-end">
                                                    <td class="d-flex align-items-center pt-6">
                                                        {{ $item->product->name }}
                                                    </td>

                                                    <td class="pt-6">
                                                        {{number_format($item->price, 0)}}
                                                    </td>
                                                    <td class="pt-6">
                                                        {{ $item->quantity }} {{ $item->product->stock_unit_measure }}
                                                    </td>
                                                    <td class="pt-6 text-dark fw-bolder">
                                                        {{ number_format($item->total, 0) }}
                                                    </td>
                                                </tr>
                                            @endforeach

                                            </tbody>
                                        </table>
                                    </div>
                                    <!--end::Table-->

                                    <!--begin::Container-->
                                    <div class="d-flex justify-content-end">
                                        <!--begin::Section-->
                                        <div class="mw-300px">


                                            <!--begin::Item-->
                                            <div class="d-flex flex-stack">
                                                <!--begin::Code-->
                                                <div class="fw-semibold pe-10 text-gray-600 fs-7">Total</div>
                                                <!--end::Code-->

                                                <!--begin::Label-->
                                                <div class="text-end fw-bold fs-6 text-gray-800">
                                                    {{ number_format($purchaseOrder->total, 0) }}
                                                </div>
                                                <!--end::Label-->
                                            </div>
                                            <!--end::Item-->
                                        </div>
                                        <!--end::Section-->
                                    </div>
                                    <!--end::Container-->
                                </div>
                                <!--end::Content-->
                            </div>
                            <!--end::Wrapper-->
                        </div>
                        <!--end::Invoice 2 content-->
                    </div>
                    <!--end::Content-->

                </div>
                <!--end::Layout-->
            </div>
            <!--end::Body-->
        </div>
    </div>
@endsection

@push('scripts')

@endpush
