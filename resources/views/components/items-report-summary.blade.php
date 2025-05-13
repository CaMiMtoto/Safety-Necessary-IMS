<div class="card card-body my-3 border border-gray-300">
    <h4>
        Closing Stock on {{date('d-m-Y')}}
    </h4>
    <p>
        Below is the summary of closing stock on {{date('d-m-Y')}}.
    </p>
    <div class="table-responsive tw-max-h-[60vh] overflow-y-scroll">
        <table class="table table-row-dashed table-row-gray-500 align-middle gs-5 ">
            <thead>
            <tr class="fw-bolder text-gray-800 bg-gray-200 text-uppercase">
                <th>Product Name</th>
                <th>Price</th>
                <th>Closing Stock</th>
            </tr>
            </thead>
            <tbody>
            @foreach($products as $item)
                <tr>
                    <td>{{$item->name}}</td>
                    <td>{{number_format($item->price)}}</td>
                    <td>{{number_format($item->stock_quantity)}}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
    <table class="table table-row-dashed table-row-gray-500 align-middle gs-5 ">
        <thead>
        <tr class="fw-bolder text-gray-800 bg-gray-200 text-uppercase">
            <th></th>
            <th>Price</th>
            <th>Quantity</th>
        </tr>
        </thead>
        <tbdoy>
            <tr>
                <th class="fw-semibold">Total Received</th>
                <td>{{number_format($totalReceivedAmount)}}</td>
                <td>{{number_format($totalReceived)}}</td>
            </tr>

            <tr>
                <th class="fw-semibold">Total Sales</th>
                <td>{{number_format($totalSalesAmount)}}</td>
                <td>{{number_format($totalSales)}}</td>
            </tr>
            <tr>
                <th class="fw-semibold">Balance</th>
                <td>{{number_format($totalReceivedAmount - $totalSalesAmount)}}</td>
                <td>{{number_format($totalReceived - $totalSales)}}</td>
            </tr>
        </tbdoy>
    </table>
</div>


