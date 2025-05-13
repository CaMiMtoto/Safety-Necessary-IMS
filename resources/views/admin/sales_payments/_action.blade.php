<div class="dropdown">
    <button class="btn btn-icon dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
        <x-lucide-ellipsis-vertical class="tw-h-5 tw-w-5"/>
    </button>
    <ul class="dropdown-menu dropdown-menu-end">
        <li>
            <a class="dropdown-item" href="{{ route('admin.sales_payment.show',$payment->id) }}">Details</a></li>
        <li>
            <a class="dropdown-item js-cancel" href="{{ route('admin.sales_payment.cancel',$payment->id) }}">Cancel</a>
        </li>
{{--        <li><a class="dropdown-item" href="{{ route('admin.sales_payment.cancel',$payment->id) }}">Print</a></li>--}}
        @if($payment->attachment)
            <li><a class="dropdown-item" href="{{ $payment->attachmentUrl }}" target="_blank">Attachment</a></li>
        @endif

    </ul>
</div>
