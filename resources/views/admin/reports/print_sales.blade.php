<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0"/>
    <meta http-equiv="X-UA-Compatible" content="ie=edge"/>
    <title>
        Sales Reports
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
                <img src="{{ asset('assets/media/logos/logo.png') }}"
                     class="" style="width: 200px" alt="Logo">
            </td>
            <td class="w-50 text-end">
                <h4 class="display-1 text-uppercase" style="text-align: right;color: #0C1A82">
                    Sales Report
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
                        <strong>Status:</strong> {{ $status??'All' }}<br>
                        <strong>Total Sales:</strong> {{ number_format($totalSales,0) }} <br/>
                        <strong>Total Expenses:</strong> {{ number_format($totalExpenses,0) }} <br/>
                        <strong>Net Profit:</strong> {{ number_format($netProfit,0) }} <br/>
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
            Below are the items that you have sold.
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
            <th style="text-align: left" class="tw-p-2">Customer</th>
            <th style="text-align: left" class="tw-p-2">Done By</th>

        </tr>
        </thead>

        <tbody>
        @foreach($data as $index=> $item)
            <tr class="small tw-text-xs border-bottom"
                style="background-color:{{$index%2==0?'#f2f2f2':'#fff'}} !important">
                <td class=" tw-p-2">{{ $item->product->name }}</td>
                <td class=" tw-p-2">  {{number_format( $item->quantity, 2)}} {{ $item->product->unit_measure }}</td>
                <td class=" tw-p-2">{{number_format($item->price, 0)}}</td>
                <td class=" tw-p-2">{{number_format($item->total, 0)}}</td>
                <td class=" tw-p-2">{{ $item->saleOrder->created_at->format('d M Y') }}</td>
                <td class=" tw-p-2">{{ $item->saleOrder->customer?->name }}</td>
                <td class=" tw-p-2">{{ $item->saleOrder->doneBy?->name }}</td>
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
