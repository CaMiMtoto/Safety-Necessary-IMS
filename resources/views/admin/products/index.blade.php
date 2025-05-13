@extends('layouts.master')
@section('title', 'Products')
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
                            Products
                        </li>
                        <!--end::Item-->
                        <!--begin::Item-->
                        <li class="breadcrumb-item">
                            <i class="bi bi-chevron-right fs-4 text-gray-700 mx-n1"></i>
                        </li>
                        <!--end::Item-->
                        <!--begin::Item-->
                        <li class="breadcrumb-item text-gray-700">
                            Manage Products
                        </li>
                        <!--end::Item-->
                    </ul>
                    <!--end::Breadcrumb-->
                    <!--begin::Title-->
                    <h1 class="page-heading d-flex flex-column justify-content-center text-dark fw-bolder fs-1 lh-0">
                        Products
                    </h1>
                    <!--end::Title-->
                </div>
                <!--end::Page title-->
                <!--begin::Actions-->
                <div>
                    <a href="{{ route('admin.products.excel-export') }}" target="_blank"
                       class="btn btn-sm btn-success px-4 py-3">
                        <i class="bi bi-file-excel fs-3"></i>
                        Export Excel
                    </a>
                    <button type="button" class="btn btn-sm btn-light-primary px-4 py-3" id="addBtn">
                        <i class="bi bi-plus fs-3"></i>
                        Add New
                    </button>
                </div>
                <!--end::Actions-->
            </div>
        </div>
        <!--end::Toolbar-->
        <!--begin::Content-->
        <div class="my-3">
            <div class="table-responsive">
                <table class="table ps-2 align-middle border rounded table-row-dashed fs-6 g-5" id="myTable">
                    <thead>
                    <tr class="text-start text-gray-800 fw-bold fs-7 text-uppercase">
                        <th>Created At</th>
                        <th>Name</th>
                        <th>Category</th>
                        <th>Price</th>
                        <th>Stock</th>
                        <th>Reorder</th>
                        <th>Options</th>
                    </tr>
                    </thead>
                </table>
            </div>
        </div>
        <!--end::Content-->
    </div>


    <div class="modal fade" tabindex="-1" id="myModal">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title">
                        Product
                    </h3>

                    <!--begin::Close-->
                    <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal"
                         aria-label="Close">
                        <i class="bi bi-x"></i>
                    </div>
                    <!--end::Close-->
                </div>

                <form action="{{ route('admin.products.store') }}" id="submitForm" method="post">
                    @csrf

                    <div class="modal-body">
                        <input type="hidden" id="id" name="id" value="0"/>

                        <div class="mb-3">
                            <label for="category_id" class="form-label">Category</label>
                            <select class="form-select" id="category_id" name="category_id">
                                <option value="">Select Category</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="name" class="form-label">Name</label>
                            <input type="text" class="form-control" id="name" name="name" placeholder=""/>
                        </div>
                        <div class="row">
                            <div class="mb-3 col-md-6">
                                <label for="price" class="form-label">Selling Price</label>
                                <input type="number" class="form-control" id="price" name="price" placeholder=""/>
                            </div>
                            <div class="mb-3 col-md-6">
                                <label for="unit_measure" class="form-label">Selling Unit Measure</label>
                                <input class="form-control" id="unit_measure" name="unit_measure" type="text"/>
                            </div>
                        </div>


                        <div class="row">
                            <div class="mb-3 col-md-6">
                                <label for="stock_unit_measure" class="form-label">Stock Unit Measure</label>
                                <input class="form-control" id="stock_unit_measure" name="stock_unit_measure"
                                       type="text"/>
                            </div>

                            <div class="mb-3 col-md-6">
                                <label for="reorder_level" class="form-label">
                                    Reorder Level
                                </label>
                                <input class="form-control" id="reorder_level" name="reorder_level" type="number"
                                       value="0"/>
                            </div>
                        </div>

                        <div class="row align-items-center">
                            <div class="my-3 col-md-6">
                                {{--                                sold_in_square_meters--}}
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" name="sold_in_square_meters"
                                           id="sold_in_square_meters"/>
                                    <label class="form-check-label text-dark" for="sold_in_square_meters">
                                        Sold in Square Meters
                                    </label>
                                </div>
                            </div>
                            <div class="mb-3 col-md-6" style="display: none" id="box_coverage_div">
                                <label for="box_coverage" class="form-label">
                                    Box Coverage (m²)
                                </label>
                                <input class="form-control" id="box_coverage" name="box_coverage" type="number"
                                       step="0.01"
                                       value="0"/>
                            </div>
                        </div>

                        {{--       <div class="mb-3">
                                   <label for="description" class="form-label">Description</label>
                                   <textarea class="form-control" id="description" name="description"></textarea>
                               </div>
       --}}

                    </div>

                    <div class="modal-footer bg-light">
                        <button type="submit" class="btn btn-primary">Save changes</button>
                        <button type="button" class="btn bg-secondary text-light-emphasis" data-bs-dismiss="modal">
                            Close
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script>
        $(function () {
            window.dt = $('#myTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{!! request()->fullUrl() !!}",
                language: {
                    loadingRecords: '&nbsp;',
                    processing: '<div class="spinner spinner-primary spinner-lg mr-15"></div> Processing...'
                },
                columns: [
                    {
                        data: 'created_at', name: 'created_at',
                        render: function (data) {
                            return moment(data).format('DD-MM-YYYY HH:mm:ss');
                        }
                    },
                    {data: 'name', name: 'name'},
                    {data: 'category.name', name: 'category.name'},
                    {
                        data: 'price', name: 'price',
                        render: function (data, type, row) {
                            return Number(data).toLocaleString();
                        }
                    },
                    {
                        data: 'stock_quantity', name: 'stock_quantity',
                        render: function (data, type, row) {
                            return data + ' ' + (row.stock_unit_measure === null ? '' : row.stock_unit_measure);
                        }
                    },
                    {
                        data: 'reorder_level', name: 'reorder_level',
                        render: function (data, type, row) {
                            const qty = row.stock_quantity;
                            const level = row.reorder_level;
                            let color = '';
                            if (qty <= 0) {
                                color = 'danger';
                            } else if (qty <= level) {
                                color = 'warning';
                            } else {
                                color = 'success';
                            }
                            return `<span class="badge bg-${color}-subtle fs-7 fw-bolder text-${color} rounded-pill">${data}</span>`;
                        }
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false,
                        class: 'text-center',
                        width: '15%'
                    },
                ],
                order: [[0, 'desc']],
                lengthMenu: [10, 25, 50, 100, 500, 1000, 2000, 3000, 5000],
            });

            $('#addBtn').click(function () {
                $('#myModal').modal('show');
            });
            $('#sold_in_square_meters').change(function () {
                if ($(this).is(':checked')) {
                    $('#box_coverage_div').show();
                } else {
                    $('#box_coverage_div').hide();
                }
            });
            $('#myModal').on('hidden.bs.modal', function () {
                $('#submitForm').trigger('reset');
                $('#id').val(0);
            });

            let submitForm = $('#submitForm');
            submitForm.submit(function (e) {
                e.preventDefault();
                let $this = $(this);
                let formData = new FormData(this);
                let id = $('#id').val();
                let url = $this.attr('action');
                let btn = $(this).find('[type="submit"]');
                btn.prop('disabled', true);
                btn.html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Saving...');
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
                    success: function (data) {
                        dt.ajax.reload();
                        $('#myModal').modal('hide');
                        Swal.fire({
                            icon: 'success',
                            title: 'Success!',
                            text: 'Record has been saved successfully.',
                            // showConfirmButton: false,
                            // timer: 1500
                        });

                    },
                    error: function (xhr) {
                        if (xhr.status === 422) {
                            let errors = xhr.responseJSON.errors;
                            $.each(errors, function (key, value) {
                                let $1 = $('#' + key);
                                $1.addClass('is-invalid');
                                // create span element under the input field with a class of invalid-feedback and add the error text returned by the validator
                                $1.parent().append('<span class="invalid-feedback">' + value[0] + '</span>');
                            });
                        }
                    },
                    complete: function () {
                        btn.prop('disabled', false);
                        btn.html('Save changes');
                    }
                });
            });

            $(document).on('click', '.js-edit', function (e) {
                e.preventDefault();
                let id = $(this).data('id');
                let url = $(this).attr('href')
                $.ajax({
                    url: url,
                    type: 'GET',
                    success: function (data) {
                        $('#id').val(data.id);
                        $('#name').val(data.name);
                        $('#category_id').val(data.category_id);
                        $('#price').val(data.price);
                        $('#stock_quantity').val(data.stock_quantity);
                        $('#description').val(data.description);
                        $('#unit_measure').val(data.unit_measure);
                        $('#reorder_level').val(data.reorder_level);
                        $('#sold_in_square_meters').prop('checked', data.sold_in_square_meters);
                        if (data.sold_in_square_meters) {
                            $('#box_coverage_div').show();
                        } else {
                            $('#box_coverage_div').hide();
                        }
                        $('#box_coverage').val(data.box_coverage);
                        $('#stock_unit_measure').val(data.stock_unit_measure);
                        $('#myModal').modal('show');
                    }
                });
            });
        });
    </script>
@endpush
