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
                        <a href="">Purhcase Order</a>
                    </li>
                    <!--end::Item-->
                    <!--begin::Item-->
                    <li class="breadcrumb-item">
                        <i class="bi bi-chevron-right fs-4 text-gray-700 mx-n1"></i>
                    </li>
                    <!--end::Item-->
                    <!--begin::Item-->
                    <li class="breadcrumb-item text-gray-700">
                        History
                    </li>
                    <!--end::Item-->
                </ul>
                <!--end::Breadcrumb-->
                <!--begin::Title-->
                <h1 class="page-heading d-flex flex-column justify-content-center text-dark fw-bolder fs-1 lh-0">
                    Purchase Order History
                </h1>
                <!--end::Title-->
            </div>
            <!--end::Page title-->
            <!--begin::Actions-->
            <div>
                <form action="{{route("admin.reports.purchase-orders.history.export")}}" target="_blank"
                      class="d-flex align-items-center gap-2">
                    <input type="text" class="datepicker form-control form-control-sm" wire:model.live="startDate"
                           name="start_date"
                           placeholder="Start Date"/>
                    <input type="text" class="datepicker  form-control form-control-sm" wire:model.live="endDate"
                           name="end_date"
                           placeholder="End Date"/>
                    <select class="form-select form-select-sm" name="product_id" wire:model.live="productId">
                        <option value="">All Products</option>
                        @foreach($products as $item)
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
                    <th>Purchase Order</th>
                    <th>Date</th>
                    <th>Item Name</th>
                    <th>Price</th>
                    <th>Quantity</th>
                    <th>Total</th>
                    <th>Supplier</th>
                </tr>
                </thead>
                <tbody>
                @forelse($purchases as $item)
                    <tr>
                        <td>{{$item->purchaseOrder->invoice_number}}</td>
                        <td>{{$item->purchaseOrder->delivery_date->format('d-m-Y')}}</td>
                        <td>{{$item->product->name}}</td>
                        <td>{{number_format($item->price,2)}}</td>
                        <td>{{number_format($item->quantity,2)}}</td>
                        <td>{{number_format($item->total,2)}}</td>
                        <td>{{optional($item->purchaseOrder->supplier)->name}}</td>
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
        <div>
            {{--            Totals--}}
            <div class="d-flex justify-content-end align-items-center mt-5 px-4">
                <div class="d-flex align-items-center">
                    <span class="fw-bold">Total Purchase:</span>
                    <span class="text-gray-700 ms-2">{{ number_format($total_purchase) }}</span>
                </div>
            </div>
        </div>
    </div>
    <!--end::Content-->
</div>
