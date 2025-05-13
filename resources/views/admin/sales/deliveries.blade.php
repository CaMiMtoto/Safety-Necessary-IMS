@extends('layouts.master')
@section('title', 'Sales Order Deliveries')
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
                        <a href="{{ route('admin.sale-orders.index') }}">
                            Sales Orders
                        </a>
                    </li>
                    <!--end::Item-->
                    <!--begin::Item-->
                    <li class="breadcrumb-item">
                        <i class="bi bi-chevron-right fs-4 text-gray-700 mx-n1"></i>
                    </li>
                    <!--end::Item-->
                    <li class="breadcrumb-item text-gray-700 fw-bold lh-1">
                        <a href="{{ route('admin.sale-orders.show',$saleOrder->id) }}">
                            Sales Order #{{ $saleOrder->invoice_number }}
                        </a>
                    </li>
                    <!--begin::Item-->
                    <li class="breadcrumb-item">
                        <i class="bi bi-chevron-right fs-4 text-gray-700 mx-n1"></i>
                    </li>
                    <!--end::Item-->
                    <!--begin::Item-->
                    <li class="breadcrumb-item text-gray-700">
                        Deliveries
                    </li>
                    <!--end::Item-->
                </ul>
                <!--end::Breadcrumb-->
                <!--begin::Title-->
                <div class="page-heading d-flex gap-2 align-items-center text-dark fw-bolder">
                    <h1>Sales Order Deliveries</h1> <span
                        class="badge bg-{{ $saleOrder->statusColor}}-subtle rounded-pill text-{{ $saleOrder->statusColor}}  fw-bolder">{{
                    $saleOrder->status }}</span>
                </div>
                <!--end::Title-->
            </div>
            <!--end::Page title-->
            @if($saleOrder->status !=\App\Constants\Status::DELIVERED)
                <!--begin::Actions-->
                <button type="button" class="btn btn-sm btn-light-primary " id="addBtn">
                    <i class="bi bi-plus"></i>
                    New Delivery
                </button>
                <!--end::Actions-->
            @endif
            <a href="{{ route('admin.sale-orders.print',$saleOrder->id) }}" class="btn btn-danger btn-sm" target="_blank">
                Print
            </a>
        </div>
    </div>
    <!--end::Toolbar-->
    <div class="my-3">
        @if($saleOrder->status == \App\Constants\Status::DELIVERED)
            <div class="alert alert-success d-inline-flex align-items-center gap-3 w-100" role="alert">
                <i class="bi bi-check-circle text-success fs-1"></i> This order has been fully delivered
            </div>
        @elseif ($saleOrder->status == \App\Constants\Status::CANCELLED)
            <div class="alert alert-danger d-inline-flex align-items-center" role="alert">
                This order has been cancelled
            </div>
        @endif

        <div class="card card-body mb-10 border-secondary">
            <h5>
                Order Details
            </h5>
            <div class="row">
                <div class="col-lg-6 tw-mb-3">
                    <div class=" tw-text-lg tw-text-gray-800">
                        <div class="tw-font-semibold tw-text-gray-600 tw-mb-1"> Invoice #</div>
                        <strong>{{ $saleOrder->invoice_number }}</strong>
                    </div>
                </div>
                <div class="col-lg-6 tw-mb-3">
                    <!--end::Label-->
                    <div class="tw-font-semibold tw-text-gray-600 tw-mb-1">Order Date:</div>
                    <!--end::Label-->

                    <!--end::Col-->
                    <div class="tw-font-bold tw-text-sm tw-text-gray-800">
                        {{ $saleOrder->order_date->format('d M Y') }}
                    </div>
                    <!--end::Col-->
                </div>
                <div class="col-lg-6 tw-mb-3">
                    <!--end::Label-->
                    <div class="tw-font-semibold tw-text-gray-600 tw-mb-1">Issue By:</div>
                    <!--end::Label-->
                    <!--end::Text-->
                    <div class="tw-font-bold tw-text-sm tw-text-gray-800">
                        Global Engineering Agency
                    </div>
                    <!--end::Text-->

                    <!--end::Description-->
                    <div class="tw-font-semibold tw-text-gray-600 tw-mb-1">
                        KG 33 Avenue Road Gakiriro Road<br>
                        Umukindo house ,2nd floor front wing Kigali Gasabo
                    </div>
                    <!--end::Description-->
                </div>
                <div class="col-lg-6 tw-mb-3">
                    <!--end::Label-->
                    <div class="tw-font-semibold tw-text-gray-600 tw-mb-1">Issued For:</div>
                    <!--end::Label-->

                    <!--end::Text-->
                    <div class="tw-font-bold tw-text-sm tw-text-gray-800">
                        {{ $saleOrder->customer->name }}
                    </div>
                    <!--end::Text-->

                    <!--end::Description-->
                    <div class="tw-font-semibold tw-text-gray-600 tw-mb-1">
                        {{ $saleOrder->customer->address }}
                    </div>
                    <!--end::Description-->
                </div>
                <div class="col-lg-6 tw-mb-3">
                    <!--end::Label-->
                    <div class="tw-font-semibold tw-text-gray-600 tw-mb-1">Total Items:</div>
                    <!--end::Label-->

                    <!--end::Info-->
                    <div class="tw-font-bold tw-text-sm tw-text-gray-800">
                    <span class="pe-2">
                        {{ $saleOrder->items->count() }} items
                    </span>
                    </div>
                    <!--end::Info-->
                </div>
                <div class="col-lg-6 tw-mb-3">
                    <!--end::Label-->
                    <div class="tw-font-semibold tw-text-gray-600 tw-mb-1">Total Quantities:</div>
                    <!--end::Label-->

                    <!--end::Info-->
                    <div class="tw-font-bold tw-text-sm tw-text-gray-800">
                    <span class="pe-2">
                        {{ $saleOrder->items->sum('quantity') }} items To be delivered
                    </span>
                    </div>
                    <!--end::Info-->
                </div>
            </div>
        </div>
        <h5>
            Deliveries Details
        </h5>
        <p>
            All deliveries made for this order are listed below with the delivery date, delivered by and delivery
            address.
        </p>
        <div class=" mb-10 ">

            <ol class="tw-relative border-start tw-border-gray-200 tw-list-none">
                @foreach($saleOrder->deliveries->sortByDesc('created_at') as $delivery)
                    <li class="tw-mb-10 ms-4">
                        <div
                            class="tw-absolute tw-w-3 tw-h-3 tw-bg-gray-200 tw-rounded-full tw-mt-1.5 -tw-start-1.5 tw-border tw-border-white "></div>
                        <time class="tw-mb-1 tw-text-sm tw-font-normal tw-leading-none tw-text-gray-400 ">
                            {{ $delivery->created_at->toDateTimeString() }}
                        </time>
                        <div class="d-flex justify-content-between align-items-center">
                            <h3 class="tw-text-lg ">
                                {{ $delivery->delivered_by }} delivered items at {{ $delivery->delivery_address }}
                            </h3>
                          {{--  <a href="{{route('admin.sale-deliveries.print', $delivery->id)}}"
                               target="_blank"
                               class="btn btn-sm btn-light-danger fw-bolder">
                                <i class="bi bi-file-pdf-fill"></i>
                                Delivery Note
                            </a>--}}
                        </div>
                        <p class="tw-mb-4 tw-text-base tw-font-normal tw-text-gray-500 ">

                        </p>
                        <div class="table-responsive">
                            <table
                                class="table table-striped table-condensed table-row-dashed table-row-gray-400 fs-6 g-5">
                                <thead>
                                <tr class="text-start text-gray-800 fw-bold fs-7 text-uppercase">
                                    <th>Product</th>
                                    <th>Total QTY</th>
                                    <th>To Deliver</th>
                                    <th>Delivered</th>
                                    <th>Remain</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($delivery->items as $item)
                                    <tr>
                                        <td>{{ $item->product->name }}</td>
                                        <td>
                                            {{ number_format($item->saleOrderItem->quantity,2) }} {{ $item->product->unit_measure }}
                                            @if($item->product->sold_in_square_meters)
                                                <br/>
                                                <small
                                                    class="tw-text-xs text-muted">&approx;{{number_format($item->getBoxes($item->saleOrderItem->quantity),2)}}
                                                    Boxes
                                                </small>
                                            @endif
                                        </td>
                                        <td>{{ number_format($item->quantity+$item->remaining,2) }} {{ $item->product->unit_measure }}
                                            @if($item->product->sold_in_square_meters)
                                                <br/>
                                                <small class="tw-text-xs text-muted">
                                                    &approx; {{number_format($item->getBoxes($item->quantity+$item->remaining),2)}}
                                                    Boxes
                                                </small>
                                            @endif
                                        </td>
                                        <td>{{ number_format($item->quantity,2) }} {{ $item->product->unit_measure }}
                                            @if($item->product->sold_in_square_meters)
                                                <br/>
                                                <small class="tw-text-xs text-muted">
                                                    &approx; {{number_format($item->getBoxes($item->quantity),2)}} Boxes
                                                </small>
                                            @endif
                                        </td>
                                        <td>{{ number_format($item->remaining,2 )}} {{ $item->product->unit_measure }}
                                            @if($item->product->sold_in_square_meters)
                                                <br/>
                                                <small class="tw-text-xs text-muted">
                                                    &approx; {{number_format($item->getBoxes($item->remaining),2)}} Boxes
                                                </small>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>

                    </li>
                @endforeach
            </ol>


            <div class="modal fade" tabindex="-1" id="myModal">
                <div class="modal-dialog modal-dialog-centered modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h3 class="modal-title">
                                New Delivery
                            </h3>

                            <!--begin::Close-->
                            <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal"
                                 aria-label="Close">
                                <i class="bi bi-x"></i>
                            </div>
                            <!--end::Close-->
                        </div>

                        <form action="{{ route('admin.sale-deliveries.store',$saleOrder->id) }}" id="submitForm"
                              method="post">
                            @csrf

                            <div class="modal-body">
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label for="delivery_address" class="form-label">Delivery Address</label>
                                            <input type="text" class="form-control" id="delivery_address"
                                                   name="delivery_address" required/>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label for="delivered_by" class="form-label">Delivered By</label>
                                            <input type="text" class="form-control" id="delivered_by"
                                                   name="delivered_by"
                                                   required/>
                                        </div>
                                    </div>
                                </div>
                                <h4>
                                    Delivery Items
                                </h4>
                                <p>
                                    Enter the quantity of each product to be delivered
                                </p>
                                <div class="table-responsive">
                                    <table
                                        class="table table-striped table-condensed table-row-gray-300 table-row-dashed fs-6 g-2 tw-align-middle"
                                        id="myTable">
                                        <thead>
                                        <tr class="text-start text-gray-800 fw-bold fs-7 text-uppercase">
                                            <th>Product</th>
                                            <th>Delivered</th>
                                            <th>Remaining</th>
                                            <th>Quantity</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($saleOrder->items->where('remaining', '>', 0) as $item)
                                            <tr>
                                                <td>
                                                    {{ $item->product->name }}
                                                </td>
                                                <td>
                                                    {{ $item->delivered }} {{ $item->product->unit_measure }}
                                                </td>
                                                <td>
                                                    {{ $item->remaining }} {{ $item->product->unit_measure }}
                                                </td>
                                                <td>
                                                    <input type="hidden" name="items[]" value="{{ $item->id }}"/>
                                                    <input type="hidden" name="remainings[]"
                                                           value="{{ $item->remaining }}"/>
                                                    <input type="number" class="form-control form-control-sm"
                                                           name="quantities[]" required value="{{ $item->remaining }}"
                                                           step="0.01"
                                                           max="{{ $item->remaining }}" min="0"/>
                                                </td>
                                            </tr>

                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>

                            </div>

                            <div class="modal-footer bg-light">
                                <button type="submit" class="btn btn-primary">Save changes</button>
                                <button type="button" class="btn bg-secondary text-light-emphasis"
                                        data-bs-dismiss="modal">
                                    Close
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        $(document).ready(function () {
            $('#addBtn').click(function () {
                $('#myModal').modal('show');
            });
            $('#submitForm').submit(function (e) {
                e.preventDefault();
                let form = $(this);
                let url = form.attr('action');
                let data = form.serialize();
                $.ajax({
                    url: url,
                    type: 'POST',
                    data: data,
                    success: function (response) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Success',
                            text: 'Delivery created successfully',
                        });
                        $('#myModal').modal('hide');
                        window.location.reload();
                    },
                    error: function (response) {
                        // check if the response status is 422
                        if (response.status === 422) {
                            let errors = response.responseJSON.errors;
                            let error = response.responseJSON.error;
                            if (error) {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error',
                                    text: error,
                                });
                                return;
                            }
                            let message = '';
                            for (let key in errors) {
                                message += errors[key][0] + '<br>';
                            }
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                html: message,
                            });
                            return;
                        }

                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'An error occurred while creating the delivery',
                        });
                    }
                });
            });
        });
    </script>
@endpush
