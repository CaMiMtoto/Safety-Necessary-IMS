<div class="card card-body my-3 border border-gray-300">
    <h6 class="mb-3">
        Item Report From <small class="text-muted">{{ $startDate }}</small> To <small
            class="text-muted">{{ $endDate }}</small> for <small class="text-muted">{{ $product->name }}</small>
    </h6>

    <h4>

    </h4>
    <div class="table-responsive">
        <table class="table border table-row-dashed table-row-gray-500 align-middle gs-5 ">
            <thead>
            <tr class="fw-bolder text-gray-800 bg-gray-200 text-uppercase">
                <th>Date</th>
                <th>Voucher Number</th>
                <th>Supplier</th>
                <th>Quantity</th>
                <th>Price</th>
                <th>Total</th>
            </tr>
            </thead>
            <tbody>
            @forelse($purchases as $item)
                <tr>
                    <td>{{$item->purchaseOrder->delivery_date->toDateString()}}</td>
                    <td>{{$item->purchaseOrder->invoice_number}}</td>
                    <td>{{$item->purchaseOrder->supplier?->name??'Unknown'}}</td>
                    <td>{{number_format($item->quantity)}}</td>
                    <td>{{number_format($item->price)}}</td>
                    <td>{{number_format($item->total)}}</td>
                </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center">
                            No purchases have been received yet for the selected period.
                        </td>
                    </tr>
                @endforelse
                <tr>
                    <th colspan="3" class="fw-bold">TOTAL RECEIVED</th>
                    <td>{{number_format($purchases->sum('quantity'))}}</td>
                    <td>{{number_format($purchases->sum('price'))}}</td>
                    <td>{{number_format($purchases->sum('total'))}}</td>
                </tr>
            </tbody>

        </table>
        <table class="table border table-row-dashed table-row-gray-500 align-middle gs-5 ">
            <thead>
            <tr class="fw-bolder text-gray-800 bg-gray-200 text-uppercase">
                <th>Date</th>
                <th>Order Number</th>
                <th>Customer</th>
                <th>Quantity</th>
                <th>Price</th>
                <th>Total</th>
                {{--                <th>Done By</th>--}}
            </tr>
            </thead>
            <tbody>
            @forelse($sales as $item)
                <tr>
                    <td>{{$item->saleOrder->order_date?->toDateString()}}</td>
                    <td>{{$item->saleOrder->invoice_number}}</td>
                    <td>{{$item->saleOrder->customer?->name??'Unknown'}}</td>
                    <td>{{number_format($item->quantity)}}</td>
                    <td>{{number_format($item->price)}}</td>
                    <td>{{number_format($item->total)}}</td>
                    {{--                    <td>{{$item->purchaseOrder->doneBy->name}}</td>--}}
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center">
                        No sales made yet for the selected period.
                    </td>
                </tr>
            @endforelse
            <tr>
                <th colspan="3" class="fw-bold">TOTAL SALES</th>
                <td>{{number_format($sales->sum('quantity'))}}</td>
                <td>{{number_format($sales->sum('price'))}}</td>
                <td>{{number_format($sales->sum('total'))}}</td>
            </tr>
            </tbody>

        </table>
        <table class="table border table-row-dashed table-row-gray-500 align-middle gs-5 ">
            <thead>
            <tr class="fw-bolder text-gray-800 bg-gray-200 text-uppercase">
                <th>Date</th>
                <th>Order Number</th>
                <th>Customer</th>
                <th>Payment Mode</th>
                <th>Amount</th>
            </tr>
            </thead>
            <tbody>
            @forelse($payments as $item)
                <tr>
                    <td>{{$item->payment_date?->toDateString()}}</td>
                    <td>{{$item->saleOrder->invoice_number}}</td>
                    <td>{{$item->saleOrder->customer?->name??'Unknown'}}</td>
                    <td>{{$item->paymentMethod->name}}
                    <td>{{number_format($item->amount)}}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center">
                        No payment made yet for the selected period.
                    </td>
                </tr>
            @endforelse
            <tr>
                <th colspan="4" class="fw-bold">TOTAL PAID</th>
                <td>{{number_format($payments->sum('amount'))}}</td>
            </tr>
            </tbody>

        </table>
    </div>
</div>
