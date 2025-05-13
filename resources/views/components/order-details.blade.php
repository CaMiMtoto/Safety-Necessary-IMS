@props([
    /** @var \App\Models\SaleOrder */
    'saleOrder'
])

<div {{ $attributes->class(['my-3']) }}>

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
                    <a href="{{ route('admin.sale-orders.print',$saleOrder->id) }}" target="_blank"
                       class="btn btn-sm btn-danger">
                        <i class="bi bi-file-pdf"></i>
                        Print Order
                    </a>
                    <!--end::Action-->
                </div>
                <!--end::Top-->

                <!--begin::Wrapper-->
                <div class="m-0">
                    <!--begin::Label-->
                    <div class=" fs-3 text-gray-800 mb-8">Invoice #
                        <strong>{{ $saleOrder->invoice_number }}</strong></div>
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
                                {{ $saleOrder->order_date->format('d M Y') }}
                            </div>
                            <!--end::Col-->
                        </div>
                        <!--end::Col-->

                        <!--end::Col-->
                        <div class="col-sm-6">
                            <!--end::Label-->
                            <div class="fw-semibold fs-7 text-gray-600 mb-1">Print Date:</div>
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
                                {{ $saleOrder->customer->name }}
                            </div>
                            <!--end::Text-->

                            <!--end::Description-->
                            <div class="fw-semibold fs-7 text-gray-600">
                                {{ $saleOrder->customer->address }}
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
                            <table class="table mb-3 table-striped table-hover">
                                <thead>
                                <tr class="border-bottom fs-6 fw-bold text-muted">
                                    <th class="min-w-175px pb-2">Product</th>
                                    <th class="min-w-70px  pb-2">Price</th>
                                    <th class="min-w-80px  pb-2">Qty</th>
                                    <th class="min-w-100px  pb-2">Total</th>
                                </tr>
                                </thead>

                                <tbody>
                                @foreach($saleOrder->items as $item)
                                    <tr class="">
                                        <td class="d-flex align-items-center pt-6">
                                            {{ $item->product->name }}
                                        </td>

                                        <td class="pt-6">
                                            {{number_format($item->price, 0)}}
                                        </td>
                                        <td class="pt-6">
                                            {{ number_format($item->quantity,2) }} {{ $item->product->unit_measure }}
                                        </td>
                                        <td class="pt-6 text-dark fw-bolder">
                                            {{ number_format($item->total, 0) }}
                                        </td>
                                    </tr>
                                @endforeach
                                <tr>
                                    <td colspan="3" class="text-end fw-bold text-gray-800 pt-6">Total</td>
                                    <td class=" fw-bold text-gray-800 pt-6">
                                        {{ number_format($saleOrder->total, 0) }}
                                    </td>
                                </tr>

                                </tbody>
                            </table>
                        </div>
                        <!--end::Table-->
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
