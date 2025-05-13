@extends('layouts.master')
@section('title', 'Purchase Orders')
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
                        All Orders
                    </li>
                    <!--end::Item-->
                </ul>
                <!--end::Breadcrumb-->
                <!--begin::Title-->
                <h1 class="page-heading d-flex flex-column justify-content-center text-dark fw-bolder fs-1 lh-0">
                    All Orders
                </h1>
                <!--end::Title-->
            </div>
            <!--end::Page title-->
            <!--begin::Actions-->
            <a href="{{ route('admin.purchase-orders.create') }}" class="btn btn-sm btn-light-primary "
               id="addBtn">
                <i class="bi bi-plus"></i>
                New Order
            </a>
            <!--end::Actions-->
        </div>
    </div>
    <!--end::Toolbar-->
    <div class="my-3">

        <div class="card-header border-bottom-0 align-items-lg-center flex-column flex-lg-row py-5 gap-2 gap-md-5 px-0">
            <!--begin::Card title-->
            <div class="card-title px-0">
                <!--begin::Search-->
                <div>
                    <h4> Filters</h4>
                    <p class="text-muted tw-text-xs">
                        Filter the products by date and supplier.
                    </p>
                </div>
                <!--end::Search-->
            </div>
            <!--end::Card title-->
            <!--begin::Card toolbar-->
            <form action="{{ request()->fullUrl() }}" method="get" autocomplete="off"
                  class="card-toolbar flex-row-fluid justify-content-lg-end gap-5">
                <div class="w-100 w-lg-auto">
                    <input type="text" class="form-control datepicker form-control-sm" name="start_date"
                           value="{{request('start_date')}}"
                           placeholder="Start Date"/>
                </div>
                <div class="w-100 w-lg-auto">
                    <input type="text" class="form-control datepicker form-control-sm" name="end_date"
                           value="{{request('end_date')}}"
                           placeholder="End Date"/>
                </div>
                <div class="w-100 w-lg-auto">
                    <!--begin::Select2-->
                    <select class="form-select form--solid form-select-sm" name="supplier_id">
                        <option value="">Supplier </option>
                        @foreach($suppliers as $item)
                            <option value="{{$item->id}}" {{ request()->get('supplier_id')==$item->id?'selected':'' }}>
                                {{$item->name}}
                            </option>
                        @endforeach
                    </select>
                    <!--end::Select2-->
                </div>
                <div class="btn-group btn-group-sm">
                    <button class="btn btn-primary btn-sm" type="submit">
                        <i class="bi bi-funnel"></i>
                        Filter
                    </button>
                    <a href="{{ route('admin.reports.purchases-export') }}" class="btn btn-success btn-sm js-export"
                       type="button">
                        Export
                        <i class="bi bi-filetype-xlsx"></i>
                    </a>
                </div>
            </form>
            <!--end::Card toolbar-->
        </div>

        <div class="table-responsive">
            <table class="table ps-2 align-middle border rounded table-row-dashed fs-6 g-5" id="myTable">
                <thead>
                <tr class="text-start text-gray-800 fw-bold fs-7 text-uppercase">
                    <th>Date</th>
                    <th>Invoice #</th>
                    <th>Supplier</th>
                    <th>Items</th>
                    <th>Options</th>
                </tr>
                </thead>
            </table>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function () {
            window.dt = $('#myTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{!! request()->fullUrl() !!}',
                columns: [
                    {data: 'delivery_date', name: 'delivery_date',
                        render: function (data) {
                            return moment(data).format('MM/DD/YYYY');
                        }
                    },
                    {data: 'invoice_number', name: 'invoice_number'},
                    {data: 'supplier.name', name: 'supplier.name'},
                    {data: 'items_count', name: 'items_count', orderable: false, searchable: false},
                    {data: 'action', name: 'action', orderable: false, searchable: false},
                ],
                order: [[0, 'desc']]
            });
        });
    </script>
@endpush
