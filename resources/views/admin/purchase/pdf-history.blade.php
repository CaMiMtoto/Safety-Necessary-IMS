<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0"/>
    <meta http-equiv="X-UA-Compatible" content="ie=edge"/>
    <title>
        Stock In Reports
    </title>
    <style>
        @font-face {
            font-family: 'Figtree';
            src: url({{ storage_path('fonts/Figtree-Regular.ttf') }}) format("truetype");
            font-weight: 400;
            /*font-style: normal;*/
        }

        @page {
            margin: 0;
            padding: 0;
        }

        html, body {
            font-family: 'Figtree', sans-serif !important;
        }

        .tw-min-w-\[175px\] {
            min-width: 175px;
        }

        .tw-min-w-\[70px\] {
            min-width: 70px;
        }

        .tw-min-w-\[80px\] {
            min-width: 80px;
        }

        .tw-min-w-\[100px\] {
            min-width: 100px;
        }


    </style>
    <x-pdf-styles/>
</head>
<body class="tw-font-sans tw-antialiased">
<div class="-tw-top-1 tw-p-10 " style="position: relative;">

    <table class="table mb-5" style="width: 100% !important;">
        <tr>
            <td class="w-50">
                <img
                    src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('assets/media/logos/logo.png'))) }}"
                    class="tw-w-32" alt="Logo">
            </td>
            <td class="w-50 text-end">
                <h4 class="display-1 text-uppercase" style="text-align: right;color: #0C1A82">
                    Stock In Report
                </h4>
            </td>
        </tr>

    </table>

    <table class="table table-borderless " style="width: 100% !important;">
        <tr>
            <td style="vertical-align: top;width: 50%!important;" colspan="w-50">
                <div class="">
                    <div class="tw-text-sm tw-text-gray-800">
                        <strong>
                            Global Engineering Agency
                        </strong>
                    </div>
                    <div class="tw-text-xs tw-text-gray-600 tw-mb-1">
                        KG 33 Avenue Road Gakiriro Road<br>
                        Umukindo house ,2nd floor front wing Kigali Gasabo
                    </div>
                </div>
            </td>
            <td style="vertical-align: top;width: 50%!important;" colspan="w-50">
                <div class="">
                    <div class="tw-text-xs tw-text-gray-600 tw-mb-1">
                        <strong>Start Date:</strong> {{ $startDate }}, <strong>End Date:</strong> {{ $endDate }}<br>
                        <strong>Total Amount:</strong> {{ number_format($data->sum('total'),0) }}
                    </div>
                </div>
            </td>
        </tr>
    </table>
    <div>
        <div class="tw-text-xs tw-text-gray-700 tw-uppercase ">
            <strong>Items</strong>
        </div>
        <p class="tw-text-xs text-muted">
            Below are the items that you have purchased.
        </p>
    </div>
    <table class="table table-bordered border table-striped "
           style="border:1px dashed #e0ebfc !important;width: 100% !important;">
        <thead class="tw-text-xs tw-text-gray-700 tw-uppercase">
        <tr class=" tw-border-b tw-text-xs tw-font-bold tw-text-gray-700">
            <th style="text-align: left" class="tw-p-2">Product</th>
            <th style="text-align: left" class="tw-p-2">Qty</th>
            <th style="text-align: left" class="tw-p-2">Price</th>
            <th style="text-align: left" class="tw-p-2">Total</th>
            <th style="text-align: left" class="tw-p-2">Date</th>
            <th style="text-align: left" class="tw-p-2">Supplier</th>
        </tr>
        </thead>

        <tbody>
        @foreach($data as $index=> $item)
            <tr class="small tw-text-xs border-bottom"
                style="background-color:{{$index%2==0?'#f2f2f2':'#fff'}} !important">
                <td class=" tw-p-2">{{ $item->product->name }}</td>
                <td class=" tw-p-2">  {{number_format( $item->quantity, 2)}} {{ $item->product->stock_unit_measure }}</td>
                <td class=" tw-p-2">{{number_format($item->price, 0)}}</td>
                <td class=" tw-p-2">{{number_format($item->total, 0)}}</td>
                <td class=" tw-p-2">{{ $item->purchaseOrder->delivery_date->format('d M Y') }}</td>
                <td class=" tw-p-2">{{ $item->purchaseOrder->supplier?->name??'N/A' }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>

    <table class="table table-borderless">
        <tr>
            <td>

                <div>
                    <div class="tw-text-xs text-uppercase">Printed At:</div>
                    <strong class="pe-2 tw-text-xs">{{ now()->format('d M Y, H:i:s') }}</strong>
                </div>
                <div>
                    <div class="tw-text-xs text-uppercase">Issued By:</div>
                    <strong class="pe-2 tw-text-xs">
                        {{ auth()->user()->name }}
                    </strong>
                </div>
            </td>

        </tr>
    </table>
</div>
</body>
</html>
