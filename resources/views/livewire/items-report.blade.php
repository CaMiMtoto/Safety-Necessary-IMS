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
                        <a href="">Items Reports</a>
                    </li>
                    <!--end::Item-->
                    <!--begin::Item-->
                    <li class="breadcrumb-item">
                        <i class="bi bi-chevron-right fs-4 text-gray-700 mx-n1"></i>
                    </li>
                    <!--end::Item-->
                    <!--begin::Item-->
                    <li class="breadcrumb-item text-gray-700">
                        Items Reports
                    </li>
                    <!--end::Item-->
                </ul>
                <!--end::Breadcrumb-->
                <!--begin::Title-->
                <h1 class="page-heading d-flex flex-column justify-content-center text-dark fw-bolder fs-1 lh-0">
                    Items Reports
                </h1>
                <!--end::Title-->
            </div>
            <!--end::Page title-->
            <!--begin::Actions-->
            <div>
                <form action="{{route("admin.reports.print-sales")}}" target="_blank"
                      class="d-flex align-items-center gap-2">
                    <select class="form-select form-select-sm" wire:model.live="category">
                        <option value="summary">Summary</option>
                        <option value="audit">Audit</option>
                    </select>
                    @if($category==='summary')
                        <input type="text" class=" form-control form-control-sm" wire:model.live="productName"
                               name="product_name"
                               placeholder="Search Product ..."/>
                    @else
                        <select class="form-select form-select-sm" wire:model.live="productId">
                            <option value="">Select Product</option>
                            @foreach($products as $product)
                                <option value="{{ $product->id }}">{{ $product->name }}</option>
                            @endforeach
                        </select>
                    @endif


                    <input type="text" class="datepicker form-control form-control-sm" wire:model.live="startDate"
                           name="start_date"
                           placeholder="Start Date"/>
                    <input type="text" class="datepicker  form-control form-control-sm" wire:model.live="endDate"
                           name="end_date"
                           placeholder="End Date"/>
                    {{-- <button type="submit" class="btn btn-sm btn-danger flex-shrink-0">
                         View
                         <i class="bi bi-file-pdf"></i>
                     </button>--}}
                </form>
            </div>
            <!--end::Actions-->
        </div>
    </div>
    <!--end::Toolbar-->
    <!--begin::Content-->
    <div>
        <div wire:loading.flex class="tw-m-auto tw-w-full  justify-content-center align-items-center">
            <div class="d-flex justify-content-center align-items-center gap-2">
                <x-lucide-loader class="tw-w-10 tw-h-10 tw-animate-spin"/>
                <p class="tw-ml-2 mb-0">Loading...</p>
            </div>
        </div>

        @if($category=='summary')
            <x-items-report-summary
                :category="$category" :end-date="$endDate" :start-date="$startDate" :product-name="$productName"
            />

        @endif
        @if($category==='audit' && $productId!=null)
            <x-items-report-audit
                :category="$category" :end-date="$endDate" :start-date="$startDate" :product-id="$productId"
            />
        @elseif($category==='audit' && $productId==null)
            <div class="alert alert-warning">Please select a product to audit</div>
        @endif

    </div>
    <!--end::Content-->
</div>
