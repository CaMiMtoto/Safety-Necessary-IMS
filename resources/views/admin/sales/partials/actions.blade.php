<div class="dropdown">
    <button class="btn btn-light btn-sm btn-icon dropdown-toggle" type="button" id="dropdownMenuButton"
            data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <i class="bi bi-three-dots"></i>
    </button>
    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
        <a class="dropdown-item" href="{{ route('admin.sale-orders.show', $saleOrder->id) }}">Details</a>
        <a class="dropdown-item" href="{{route('admin.sale-orders.print', $saleOrder->id)}}" target="_blank">Print</a>
        @if($saleOrder->status != \App\Constants\Status::CANCELLED && auth()->user()->can(\App\Constants\Permission::MANAGE_SALES_DELIVERY))
            <a class="dropdown-item" href="{{route('admin.sale-deliveries.index', $saleOrder->id)}}">Deliveries</a>
        @endif
        @if(/*$saleOrder->status == \App\Constants\Status::ORDER &&*/ auth()->user()->can(\App\Constants\Permission::CANCEL_SALES_ORDERS))
            <a class="dropdown-item js-cancel"
               href="{{route('admin.sale-orders.cancel', $saleOrder->id)}}">Cancel</a>
            <a class="dropdown-item js-edit" href="{{ route('admin.sale-orders.edit', $saleOrder->id) }}">Edit</a>
        @endif
        {{--        <a class="dropdown-item js-delete" href="{{ route(route('admin.sale-orders.destroy', $saleOrder->id)) }}">Delete</a>--}}
    </div>
</div>
