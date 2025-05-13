@extends('layouts.master')
@section('title', 'Create Order')
@section('styles')
@endsection
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
                        <!--begin::Item-->
                        <li class="breadcrumb-item text-gray-700">
                            New Order
                        </li>
                        <!--end::Item-->
                    </ul>
                    <!--end::Breadcrumb-->
                    <!--begin::Title-->
                    <h1 class="page-heading d-flex flex-column justify-content-center text-dark fw-bolder fs-1 lh-0">
                        New Order
                    </h1>
                    <!--end::Title-->
                </div>
                <!--end::Page title-->
                <!--begin::Actions-->
                <a href="{{ route('admin.sale-orders.index') }}" class="btn btn-sm btn-light-primary "
                   id="addBtn">
                    <i class="bi bi-arrow-left fs-4"></i>
                    Go Back
                </a>
                <!--end::Actions-->
            </div>
        </div>
        <!--end::Toolbar-->
        <!--begin::Content-->
        <form action="{{ route('admin.sale-orders.store') }}" method="post" class="my-3" id="submitForm">
            @csrf

            @if($errors->count() >0)
                <div class="alert alert-danger">
                    <ul>
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="order_date" class="form-label">Order Date</label>
                        <input type="date" class="form-control" id="order_date" required
                               max="{{ now()->format('Y-m-d') }}"
                               name="order_date">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="customer_id" class="form-label">Customer</label>
                        <select class="selectize" id="customer_id" name="customer_id" required>
                            <option value="">Select Customer</option>
                            @foreach($customers as $item)
                                <option value="{{ $item->id }}">{{ $item->name }} - {{ $item->address }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <div class="my-3">
                <h4>Items</h4>
                <p>
                    Below are the items you want to order. You can add multiple items to the order.
                </p>
            </div>
            <table class="table table-borderless table-hover table-striped" id="orderTable">
                <thead>
                <tr class="text-uppercase fw-semibold">
                    <th>Product</th>
                    <th class="tw-w-52">Price</th>
                    <th class="tw-w-44">Quantity</th>
                    <th class="tw-w-52">Total</th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td>
                        <select class="selectize js-product" name="product_ids[]" required>
                            <option value="">Select Product</option>
                            @foreach($products as $product)
                                <option value="{{ $product->id }}" data-id="{{ $product->id }}"
                                        data-quantity="{{ $product->actual_qty }}"
                                        data-price="{{ $product->price }}">
                                    {{ $product->name }}
                                    ({{ $product->actual_qty }} {{$product->unit_measure}})
                                </option>
                            @endforeach
                        </select>
                    </td>
                    <td>
                        <input type="number" class="form-control  js-price tw-w-44" required
                               name="prices[]"/>
                    </td>
                    <td>
                        <input type="number" class="form-control  js-qty tw-w-32" required step="0.01"
                               name="quantities[]"/>
                    </td>
                    <td>
                        <input type="text" disabled class="form-control  js-total" name="total"/>
                    </td>
                    <td>
                        <button class="btn btn-icon  btn-light-danger js-remove">
                            <i class="bi bi-trash fs-2"></i>
                        </button>
                    </td>
                </tr>
                </tbody>
                <tfoot>
                <tr>
                    <td colspan="4" class="text-end">
                        Total : <span class="fw-bold" id="totalAmount">0</span>
                    </td>
                </tr>
                </tfoot>
            </table>

            <div class="row">
                <div class="col-md-4">
                    <div class="mb-3">
                        <label for="payment_method_id" class="form-label">
                            Payment Method
                        </label>
                        <select class="form-select" id="payment_method_id" name="payment_method_id" required>
                            <option value="">Select Payment Method</option>
                            @foreach($paymentMethods as $item)
                                <option value="{{ $item->id }}">{{ $item->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="mb-3">
                        <label for="payment_date" class="form-label">
                            Payment Date
                        </label>
                        <input type="date" class="form-control" id="payment_date"  required name="payment_date">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="mb-3">
                        <label for="amount_paid" class="form-label">Total Amount Paid</label>
                        <input type="number" class="form-control" id="amount_paid" required name="amount">
                    </div>
                </div>
            </div>

            <div class="my-3 d-flex justify-content-between align-items-center">
                <button class="btn btn-sm btn-light-primary" id="addRow" type="button">
                    <i class="bi bi-plus"></i>
                    New Row
                </button>
                <button class="btn btn-sm btn-primary" type="submit">
                    <i class="bi bi-save"></i>
                    Save Order
                </button>
            </div>


        </form>
        <!--end::Content-->
    </div>
@endsection
@push('scripts')
    <script>
        $(function () {


            $('#addRow').on('click', function () {
                let uniqueId = new Date().getTime();
                let row = `
                    <tr>
                        <td>
                            <select class="selectize js-product" name="product_ids[]" required id="product_ids_${uniqueId}">
                                <option value="">Select Product</option>
                                @foreach($products as $product)
                <option value="{{ $product->id }}" data-id="{{ $product->id }}" data-quantity="{{ $product->actual_qty }}"
                                                                            data-price="{{ $product->price }}" >{{ $product->name }} ({{ $product->actual_qty }} {{$product->unit_measure}})
                                                </option>
                                @endforeach
                </select>
            </td>
             <td>
                <input type="number" class="form-control  js-price tw-w-44" name="prices[]" required/>
            </td>
            <td>
                <input type="number" class="form-control  js-qty  tw-w-32" nin="0" name="quantities[]" required step="0.01"/>
            </td>
            <td>
                <input type="text" disabled class="form-control  js-total" name="total"/>
            </td>
            <td>
                <button class="btn btn-sm btn-icon  btn-light-danger js-remove">
                    <i class="bi bi-trash fs-2"></i>
                </button>
            </td>
        </tr>
`;
                $('table tbody').append(row);
                $('#product_ids_' + uniqueId).selectize();
            });

            let $table = $('table');
            function calculateTotal(){
                let total = 0;
                $table.find('.js-total').each(function () {
                    let valueWithCommas = $(this).val();
                    const realValue = valueWithCommas.replace(/,/g, '');
                    total += Number(realValue);
                });
                $('#totalAmount').text(total.toLocaleString());
                // update amount_paid max value
                $('#amount_paid'). attr('max', total);
            }
            $table.on('click', '.js-remove', function () {
                $(this).closest('tr').remove();
                calculateTotal();
            });

            $table.on('change', '.js-qty, .js-price', function () {
                let qty = $(this).closest('tr').find('.js-qty').val();
                let price = $(this).closest('tr').find('.js-price').val();
                let total = qty * price;
                $(this).closest('tr').find('.js-total').val(Number(total).toLocaleString());
                calculateTotal();
            });

            $table.on('change', '.js-qty', function () {
                let total = $(this).val();
                // validate the qty with the selected product quantity
                let $productElement = $(this).closest('tr').find('.js-product');
                let productQuantity = $productElement.find(':selected').data('quantity');
                console.log(total, productQuantity);
                if (total > productQuantity) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Quantity exceeds available stock!',
                    });
                    $(this).val('').trigger('change');
                }
                calculateTotal();
            });

            $table.on('change', '.js-product', function () {
                let $row = $(this).closest('tr');

                // Get the price of the selected product and set it in the price input field
                let price = $(this).find(':selected').data('price');
                $row.find('.js-price').val(price);

                // Get the selected product ID
                let productId = $(this).val();
                if (!productId) {
                    return;
                }

                // Check for duplicate products (exclude the current row)
                let duplicateFound = false;
                $table.find('.js-product').not(this).each(function () {
                    if (Number($(this).val()) === Number(productId)) {
                        duplicateFound = true;
                        return false; // exit loop
                    }
                });

                if (duplicateFound) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Product already selected!',
                    });
                    // Optionally reset the current product selection to prevent duplicates
                    $(this).val('').trigger('change');
                }
            });

            $('#submitForm').on('submit', function (e) {
                e.preventDefault();

                const amountPaid = $('#amount_paid').val();
                let totalAmountToPay = 0;

                // Loop through each row and calculate the total
                $('#orderTable tr').each(function () {
                    const quantity = $(this).find('.js-qty').val();
                    const price = $(this).find('.js-price').val();

                    if (quantity && price) {
                        const itemTotal = Number(quantity) * Number(price);
                        totalAmountToPay += itemTotal;
                    }
                });

                console.log(amountPaid);
                console.log(totalAmountToPay);
                // Validate the amount paid
                if (amountPaid > totalAmountToPay) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: "Please enter a valid amount paid! Total amount must be equal to or less than the total amount to pay."
                    });
                    return;
                }

                // Disable the submit button and change its text
                const submitBtn = $(this).find('[type="submit"]');
                submitBtn.attr('disabled', true);
                submitBtn.html('Saving...');

                // Submit the form
                e.target.submit();
            });



        });
    </script>
@endpush
