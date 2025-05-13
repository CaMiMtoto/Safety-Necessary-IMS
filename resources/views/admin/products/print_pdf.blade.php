<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0"/>
    <meta http-equiv="X-UA-Compatible" content="ie=edge"/>
    <title>
        Products - {{now()->format('d/m/Y')}}
    </title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        @font-face {
            font-family: 'Figtree';
            src: url({{ storage_path('fonts/Figtree-Regular.ttf') }}) format("truetype");
            font-weight: 400;
            /*font-style: normal;*/
        }

        @page {
            margin: 0;
        }

        html, body {
            font-family: 'Figtree', sans-serif !important;
        }

    </style>
    <x-pdf-styles/>
</head>
<body class="tw-font-sans tw-antialiased">
<div class="-tw-top-1 tw-p-10">
    <table class="table">
        <tr>
            <td>
                <!--begin::Logo-->
                <img
                    src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('assets/media/logos/logo.png'))) }}"
                    class="tw-w-32" alt="Logo">
                <!--end::Logo-->
                <div class="tw-text-xs tw-text-gray-600 tw-mb-1">
                    KG 33 Avenue Road Gakiriro Road<br>
                    Umukindo house ,2nd floor front wing Kigali Gasabo
                </div>
            </td>
            <td class="text-end">
                <div>
                    <div class="tw-text-xs">Date:</div>
                    <strong class="pe-2 small">{{ now()->format('d M Y, H:i:s') }}</strong>
                </div>
            </td>
        </tr>

    </table>
    <hr/>
    <div class="text-center">
        <strong>Products</strong>
    </div>
    <hr/>

    <table class="table table-bordered border table-striped">
        <thead class="tw-text-xs tw-text-gray-700 tw-uppercase tw-bg-gray-50">
        <tr class=" tw-border-b tw-text-xs tw-font-bold tw-text-gray-700">
            <th class="tw-min-w-[175px] tw-p-2">Name</th>
            <th class="tw-min-w-[175px] tw-p-2">Category</th>
            <th class="tw-min-w-[80px] tw-text-end tw-p-2">Stock Qty</th>
            <th class="tw-min-w-[70px] tw-text-end tw-p-2">Default Price</th>
            <th class="tw-min-w-[70px] tw-text-end tw-p-2">Selling Unit</th>
            <th class="tw-min-w-[70px] tw-text-end tw-p-2">Stock Unit</th>
        </tr>
        </thead>

        <tbody>
        @foreach($products as $item)
            <tr class="small tw-text-xs border-bottom">
                <td class=" tw-p-2">{{ $item->name }}</td>
                <td class=" tw-p-2">{{ $item->category->name }}</td>
                <td class=" tw-p-2">{{ number_format($item->stock_quantity) }}</td>
                <td class=" tw-p-2">{{number_format($item->price, 0)}}</td>
                <td class=" tw-p-2">{{ $item->unit_measure }}</td>
                <td class=" tw-p-2">{{ $item->stock_unit_measure }}</td>
            </tr>
        @endforeach

        </tbody>
        <tfoot>
        <tr>
            <td colspan="3" class="tw-text-end tw-font-bold tw-text-gray-800 tw-p-2">Total</td>
            <td class="tw-text-end tw-font-bold tw-text-gray-800 tw-p-2">
                {{ number_format($products->count(), 0) }}
            </td>
        </tr>
        </tfoot>
    </table>
</div>
</body>
</html>
