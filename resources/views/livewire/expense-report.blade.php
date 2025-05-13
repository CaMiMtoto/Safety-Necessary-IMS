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
                        <a href="">Expense Reports</a>
                    </li>
                    <!--end::Item-->
                    <!--begin::Item-->
                    <li class="breadcrumb-item">
                        <i class="bi bi-chevron-right fs-4 text-gray-700 mx-n1"></i>
                    </li>
                    <!--end::Item-->
                    <!--begin::Item-->
                    <li class="breadcrumb-item text-gray-700">
                        Expense Reports
                    </li>
                    <!--end::Item-->
                </ul>
                <!--end::Breadcrumb-->
                <!--begin::Title-->
                <h1 class="page-heading d-flex flex-column justify-content-center text-dark fw-bolder fs-1 lh-0">
                    Expense Reports
                </h1>
                <!--end::Title-->
            </div>
            <!--end::Page title-->
            <!--begin::Actions-->
            <div>
                <form action="{{route("admin.reports.print-sales")}}" target="_blank"
                      class="d-flex align-Expense-center gap-2">
                    <input type="text" class="datepicker form-control form-control-sm" wire:model.live="startDate"
                           name="start_date"
                           placeholder="Start Date"/>
                    <input type="text" class="datepicker  form-control form-control-sm" wire:model.live="endDate"
                           name="end_date"
                           placeholder="End Date"/>
                    <select name="$categoryId" id="$categoryId" wire:model.live="categoryId" class="form-select form-select-sm">
                        <option value="">All Categories</option>
                        @foreach($categories??[] as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                </form>
            </div>
            <!--end::Actions-->
        </div>
    </div>
    <!--end::Toolbar-->
    <!--begin::Content-->
    <div>
        <div wire:loading.flex class="tw-m-auto tw-w-full  justify-content-center align-Expense-center">
            <div class="d-flex justify-content-center align-Expense-center gap-2">
                <x-lucide-loader class="tw-w-10 tw-h-10 tw-animate-spin"/>
                <p class="tw-ml-2 mb-0">Loading...</p>
            </div>
        </div>
        <table class="table table-row-dashed table-row-gray-300 align-middle table-striped gy-2"
               wire:loading.class="opacity-50">
            <thead>
            <tr class="text-uppercase fw-semibold  text-gray-800 border-bottom border-gray-200">
                <th>Date</th>
                <th>Category</th>
                <th>Qty</th>
                <th>Amount</th>
                <th>Total</th>
                <th>Description</th>
            </tr>
            </thead>
            <tbody>
            @forelse($expenses as $item)
                <tr>
                    <td>{{$item->date->format('d-m-Y')}}</td>
                    <td>{{ $item->category->name }}</td>
                    <td>{{number_format($item->qty)}}</td>
                    <td>{{number_format($item->amount)}}</td>
                    <td>{{number_format($item->total)}}</td>
                    <td>{{ $item->description }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center">
                        <p>
                            No expenses found for the selected date range.
                        </p>
                    </td>
                </tr>
            @endforelse
            <tfoot>
            <tr class="text-uppercase fw-bold">
                <th colspan="2">Total</th>
                <th>{{number_format($expenses->sum('qty'))}}</th>
                <th>{{number_format($expenses->sum('amount'))}} RWF</th>
                <th>{{number_format($expenses->sum('total'))}} RWF</th>
                <th></th>
            </tr>
            </tfoot>

            </tbody>
        </table>




    </div>
    <!--end::Content-->
</div>
