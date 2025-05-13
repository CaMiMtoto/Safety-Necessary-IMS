<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0"/>
    <meta http-equiv="X-UA-Compatible" content="ie=edge"/>
    <title>
        {{$saleOrder->customer->name}} - {{$saleOrder->created_at->format('d/m/Y')}}
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
@php
    $statusColors = [
        \App\Constants\Status::CANCELLED => 'red',
        \App\Constants\Status::DELIVERED => 'green',
        \App\Constants\Status::ORDER => 'blue',
        \App\Constants\Status::PARTIALLY_DELIVERED => 'purple',
        \App\Constants\Status::PAID => 'green',
    ];
    $color = $statusColors[$saleOrder->status] ?? 'silver';
@endphp
<div class="-tw-top-1 tw-p-10 " style="position: relative;">
    <!-- Watermark for Cancelled Invoice -->

    <div
        style="position: absolute; top: 0; right: 0; bottom: 0; left: 0; display: block; text-align: center; pointer-events: none; z-index: 20;">
    <span
        style="color: {{$color}}; font-size: 3.75rem; font-weight: bold; text-transform: uppercase; opacity: 0.5; transform: rotate(-45deg); position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%) rotate(-45deg);">
        {{$saleOrder->status}}
    </span>
    </div>


    <table class="table mb-5">
        <tr>
            <td>

                <img src="{{ asset('assets/media/logos/logo.png') }}"
                    class="" style="width: 200px" alt="Logo">
            </td>
            <td class="text-end">
                <h1 class="text-uppercase">
                    Sales Order
                </h1>
            </td>
        </tr>

    </table>

    <table class="table table-borderless">
        <tr>
            <td>
                <div class="tw-text-xs text-uppercase">Invoice #:</div>
                <strong class="small">{{ $saleOrder->invoice_number }}</strong>
            </td>
            <td>
                <div>
                    <div class="tw-text-xs text-uppercase">Order Date:</div>
                    <strong class="small">{{ $saleOrder->order_date->format('d M Y') }}</strong>
                </div>
            </td>
        </tr>
        <tr>

            <td colspan="w-50">
                <div class="tw-text-justify">
                    <div class="tw-text-xs text-uppercase tw-text-gray-600 tw-mb-1">Customer:</div>
                    <div class="tw-text-sm tw-text-gray-800">
                        <strong> {{ $saleOrder->customer->name }}</strong>
                    </div>
                    <div class="tw-text-xs tw-text-gray-600 tw-mb-1">
                        {{ $saleOrder->customer->address }}
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
            Below are the items that you have sold along with the total amount.
        </p>
    </div>
    <table class="table table-bordered border table-striped " style="border-color: #0c1e33 !important;">
        <thead class="tw-text-xs tw-text-gray-700 tw-uppercase">
        <tr class=" tw-border-b tw-text-xs tw-font-bold tw-text-gray-700">
            <th class="tw-min-w-[175px] tw-p-2">Product</th>
            <th class="tw-min-w-[70px] tw-text-end tw-p-2">Price</th>
            <th class="tw-min-w-[80px] tw-text-end tw-p-2">Qty</th>
            <th class="tw-min-w-[100px] tw-text-end tw-p-2">Total</th>
        </tr>
        </thead>

        <tbody>
        @foreach($saleOrder->items as $item)
            <tr class="small tw-text-xs border-bottom">
                <td class=" tw-p-2">
                    {{ $item->product->name }}
                </td>

                <td class=" tw-p-2">
                    {{number_format($item->price, 2)}}
                </td>
                <td class=" tw-p-2">
                    {{number_format( $item->quantity, 2)}} {{ $item->product->unit_measure }}
                </td>
                <td class=" tw-p-2">
                    {{ number_format($item->total, 0) }}
                </td>
            </tr>
        @endforeach

        </tbody>
        <tfoot>
        <tr>
            <td colspan="3" class="tw-text-end tw-font-bold tw-text-gray-800 tw-p-2">Total</td>
            <td class="tw-text-end tw-font-bold tw-text-gray-800 tw-p-2">
                {{ number_format($saleOrder->total, 0) }}
            </td>
        </tr>
        </tfoot>
    </table>

    <table class="table table-borderless">
        <tr>
            <td>
                <div class="tw-flex tw-justify-end tw-mt-4">

                    <div class="tw-text-sm tw-text-gray-800">
                        <strong>
                            Global Engineering Agency
                        </strong>
                    </div>
                    <div class="tw-text-xs tw-text-gray-600 tw-mb-1">
                        Kigali Gasabo <br/>
                        KG 33 Avenue Road Gakiriro Road<br>
                        Umukindo house ,Ground floor front wing.
                        {{--                        website--}}
                        <div class="tw-text-xs tw-text-gray-600 tw-mb-1">
                            <strong> Visit:</strong> <a class="text-muted text-decoration-none" href="https://globalengineeringagency.com/">
                                https://globalengineeringagency.com
                            </a>
                        </div>

                        {{--                        tel--}}
                        <div class="tw-text-xs tw-text-gray-600 tw-mb-1">
                            <strong> Tel:</strong> <a class="text-muted text-decoration-none" href="tel:+250 788 632 620">+250 788 632 620</a>
                        </div>
                        {{--                        email--}}
                        <div class="tw-text-xs tw-text-gray-600 tw-mb-1">
                            <strong> Email:</strong> <a class="text-muted text-decoration-none" href="mailto:fulluchris@gmail.com">fulluchris@gmail.com</a>


                        </div>
                        <div class="tw-text-xs tw-text-gray-600 tw-mb-1 text-uppercase">Issued By:</div>
                        <div class="tw-text-sm tw-text-gray-800">
                            <strong> {{ $saleOrder->doneBy?->name }}</strong>
                        </div>
                    </div>

                    <div>
                        <div class="tw-text-xs text-uppercase tw-text-gray-600">Printed At:</div>
                        <strong class="pe-2 small">{{ now()->format('d M Y, H:i:s') }}</strong>
                    </div>
            </td>
            <td class="text-end">
{{--                <img src="data:image/png;base64, {!! base64_encode($data) !!} " style="height: 150px" alt="QR"/>--}}
                {{--   <p class="small text-muted">
                       Scan the QR code to verify this receipt online.
                   </p>--}}
            </td>
        </tr>
    </table>


</div>
</body>
</html>
