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
@php
    $statusColors = [
        \App\Constants\Status::CANCELLED => 'red',
        \App\Constants\Status::DELIVERED => 'green',
        \App\Constants\Status::ORDER => 'blue',
        \App\Constants\Status::PARTIALLY_DELIVERED => 'purple',
        \App\Constants\Status::PAID => 'green',
    ];
    $color = $statusColors[$saleDelivery->delivery_status] ?? 'silver';
@endphp
<div class="-tw-top-1 tw-p-10 " style="position: relative;">
    <!-- Watermark for Cancelled Invoice -->

    <div
        style="position: absolute; top: 0; right: 0; bottom: 0; left: 0; display: block; text-align: center; pointer-events: none; z-index: 20;">
    <span
        style="color: {{$color}}; font-size: 1.75rem; font-weight: bold; text-transform: uppercase; opacity: 0.2; transform: rotate(-45deg); position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%) rotate(-45deg);">
        {{$saleDelivery->delivery_status}}
    </span>
    </div>


    <table class="table mb-5">
        <tr>
            <td>
                <img
                    src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('assets/media/logos/logo.png'))) }}"
                    class="tw-w-32" alt="Logo">
            </td>
            <td class="text-end">
                <h4 class="display-1 text-uppercase">
                    Delivery Note
                </h4>
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
                    <div class="tw-text-xs text-uppercase">Delivery Date:</div>
                    <strong class="small">{{ $saleDelivery->created_at->format('d M Y H:i:s') }}</strong>
                </div>
            </td>
        </tr>
        <tr>
            <td colspan="w-50">
                <div class="tw-text-justify">
                    <div class="tw-text-xs text-uppercase tw-text-gray-600 tw-mb-1">
                        Issued By:
                    </div>
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
        <tr>
            <td colspan="w-50">
                <div class="tw-text-justify">
                    <div class="tw-text-xs text-uppercase tw-text-gray-600 tw-mb-1">
                        Delivery Address:
                    </div>
                    <div class="tw-text-sm tw-text-gray-800">
                        <strong>
                            {{ $saleDelivery->delivery_address }}
                        </strong>
                    </div>
                </div>
            </td>
            <td colspan="w-50">
                <div class="tw-text-justify">
                    <div class="tw-text-xs text-uppercase tw-text-gray-600 tw-mb-1">
                        Delivered By:
                    </div>
                    <div class="tw-text-sm tw-text-gray-800">
                        <strong> {{ $saleDelivery->delivered_by }}</strong>
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
            Below are the items that you have delivered.
        </p>
    </div>
    <table class="table table-bordered border table-striped " style="border-color: #0c1e33 !important;">
        <thead class="tw-text-xs tw-text-gray-700 tw-uppercase">
        <tr class=" tw-border-b tw-text-xs tw-font-bold tw-text-gray-700">
            <th style="min-width: 200px!important;" class="tw-p-2">Product</th>
            <th style="min-width: 100px!important;" class="tw-p-2">Total QTY</th>
            <th style="min-width: 100px!important;" class="tw-text-end tw-p-2">To Deliver</th>
            <th style="min-width: 100px!important;" class="tw-text-end tw-p-2">Delivered</th>
            <th style="min-width: 100px!important;" class="tw-text-end tw-p-2">Remain</th>
        </tr>
        </thead>

        <tbody>
        @foreach($saleDelivery->items as $item)
            <tr class="small tw-text-xs border-bottom">
                <td class=" tw-p-2">
                    {{ $item->product->name }}
                </td>
                <td class=" tw-p-2">
                    {{ number_format($item->saleOrderItem->quantity,2) }} {{ $item->product->unit_measure }}
                    @if($item->product->sold_in_square_meters)
                        <br/>
                        <small
                            class="tw-text-xs text-muted">{{number_format($item->getBoxes($item->saleOrderItem->quantity),2)}}
                            Boxes</small>
                    @endif
                </td>
                <td class=" tw-p-2">
                    {{ number_format($item->quantity+$item->remaining,2) }} {{ $item->product->unit_measure }}
                    @if($item->product->sold_in_square_meters)
                        <br/>
                        <small class="tw-text-xs text-muted">
                            {{number_format($item->getBoxes($item->quantity+$item->remaining),2)}} Boxes
                        </small>
                    @endif
                </td>
                <td class=" tw-p-2">
                    {{number_format( $item->quantity,2) }} {{ $item->product->unit_measure }}
                    @if($item->product->sold_in_square_meters)
                        <br/>
                        <small class="tw-text-xs text-muted">
                            {{number_format($item->getBoxes($item->quantity),2)}} Boxes
                        </small>
                    @endif
                </td>
                <td class=" tw-p-2">
                    {{ number_format($item->remaining,2 )}} {{ $item->product->unit_measure }}
                    @if($item->product->sold_in_square_meters)
                        <br/>
                        <small class="tw-text-xs text-muted">
                            {{number_format($item->getBoxes($item->remaining),2)}} Boxes
                        </small>
                    @endif
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>

    <table class="table table-borderless">
        <tr>
            <td>

                <div>
                    <div class="tw-text-xs text-uppercase">Printed At:</div>
                    <strong class="pe-2 small">{{ now()->format('d M Y, H:i:s') }}</strong>
                </div>
                <div>
                    <div class="tw-text-xs text-uppercase">Issued By:</div>
                    <strong class="pe-2 small">
                        {{ $saleOrder->doneBy->name }}
                    </strong>
                </div>
            </td>

        </tr>
    </table>


</div>
</body>
</html>
