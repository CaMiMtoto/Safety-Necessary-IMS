@extends('layouts.master')
@section('title', 'New Payment')
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
                            New Payment
                        </li>
                        <!--end::Item-->
                    </ul>
                    <!--end::Breadcrumb-->
                    <!--begin::Title-->
                    <h1 class="page-heading d-flex flex-column justify-content-center text-dark fw-bolder fs-1 lh-0">
                        New Payment
                    </h1>
                    <!--end::Title-->
                </div>
                <!--end::Page title-->
                <!--begin::Actions-->
                <a href="{{ route('admin.sales_payment.index') }}" class="btn btn-sm btn-light-primary "
                   id="addBtn">
                    <i class="bi bi-arrow-left fs-4"></i>
                    Go Back
                </a>
                <!--end::Actions-->
            </div>
        </div>
        <!--end::Toolbar-->
        <!--begin::Content-->
        <div>
            <p>
                Please enter the order number of the payment you want to create.
            </p>
            <div class="row">
                <div class="col-lg-6 col-md-8">
                    <form action="{{ route('admin.sale-orders.search') }}" class="" id="searchForm">
                        @csrf
                        <div class="mb-3">
                            <label for="order_number" class="form-label">
                                Order Number
                            </label>
                            <div class="d-flex gap-2">
                                <input type="text" class="form-control" id="order_number" name="order_number" required
                                       placeholder="Enter Order Number"/>
                                <button type="submit"
                                        class="btn btn-primary d-inline-flex align-items-center text-uppercase gap-2 fw-bold">
                                    <x-lucide-search class="tw-h-5 tw-w-5"/>
                                    Search
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div id="results">
                <div class="alert alert-info">
                    The results will be displayed here.
                </div>
            </div>
        </div>
        <!--end::Content-->
    </div>
@endsection
@push('scripts')
    <script>
        $(function () {

            $('#searchForm').on('submit', function (e) {
                e.preventDefault();
                //submit button
                let $btn = $(this).find('button[type="submit"]');
                const $innerHtml = $btn.html();
                $btn.attr('disabled', true).text('Searching...');
                //clear previous results
                $.ajax({
                    'url': $(this).attr('action'),
                    'type': 'GET',
                    data: $('form').serialize(),
                    success: function (response) {
                        $('#results').html(response);
                    },
                    complete: function () {
                        $btn.attr('disabled', false).html($innerHtml);
                    },
                    error: function (response) {
                        console.log(response);
                        Swal.fire({
                            title: 'Error!',
                            text: response.responseJSON?.message ?? 'An error occurred while processing your request.',
                            icon: 'error',
                            confirmButtonText: 'OK'
                        });
                    }
                });
            });
            $(document).on('submit', '#payment_form', function (e) {
                e.preventDefault();
                let $this = $(this);
                let url = $this.attr('action');
                let formData = new FormData(this);
                const $submitBtn = $this.find('button[type="submit"]');
                const $innerHtml = $submitBtn.html();
                $submitBtn.prop('disabled', true)
                    .html('<i class="fas fa-spinner fa-pulse"></i> Processing...');
                // remove the error text
                $this.find('.invalid-feedback').remove();
                // remove the error class
                $this.find('.is-invalid').removeClass('is-invalid');
                $.ajax({
                    url: url,
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function (response) {
                        Swal.fire({
                            title: 'Success!',
                            text: response.message,
                            icon: 'success',
                            confirmButtonText: 'OK'
                        }).then(() => {
                            window.location.href = response.redirect_url;
                        });
                    },
                    error: function (xhr) {

                        $submitBtn.prop('disabled', false)
                            .html($innerHtml);

                        console.log(xhr);
                        // check for validation errors
                        if (xhr.status === 422) {
                            let errors = xhr.responseJSON.errors;
                            $.each(errors, function (key, value) {
                                let $1 = $('#' + key);
                                $1.addClass('is-invalid');
                                // create span element under the input field with a class of invalid-feedback and add the error text returned by the validator
                                $1.parent().append('<span class="invalid-feedback">' + value[0] + '</span>');
                            });
                        } else {
                            Swal.fire({
                                title: 'Error!',
                                text: xhr.responseJSON?.message ?? 'An error occurred while processing your request.',
                                icon: 'error',
                                confirmButtonText: 'OK'
                            });
                        }
                    }
                });
            });
        });
    </script>
@endpush
