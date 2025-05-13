<div>
    @if(is_null($saleOrder))
        <div class="alert alert-danger">
            Oops! Unable to find sales matching your search.
        </div>
    @elseif(strtolower($saleOrder->status)==strtolower(\App\Constants\Status::CANCELLED))
        <div class="alert alert-warning">
            Sale order has been cancelled.
        </div>
    @elseif(strtolower($saleOrder->payment_status)==strtolower(\App\Constants\Status::PAID))
        <div class="alert alert-success">
            Sale order has been paid. no further action required.
        </div>
    @else
        <div class="card card-body">
            <h4>
                Sale Order #{{ $saleOrder->invoice_number}}
            </h4>
            <p>
                Below is the details of the sale order.
            </p>
            <form action="{{ route('admin.sales_payment.store',$saleOrder->id) }}" id="payment_form"
                  enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <div class="col-lg-6">
                        <div class="mb-3">
                            <label for="amount_to_pay" class="form-label">Amount To Pay:</label>
                            <input type="text" class="form-control" id="amount_to_pay" name="amount_to_pay"
                                   value="{{ number_format($amountToPay) }}" disabled/>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="mb-3">
                            <label for="amount_paid" class="form-label">Amount Paid:</label>
                            <input type="text" class="form-control" id="amount_paid" name="amount_paid"
                                   value="{{ number_format($amountPaid) }}" disabled/>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="mb-3">
                            <label for="remaining_balance" class="form-label">Remaining Balance:</label>
                            <input type="text" class="form-control" id="remaining_balance" name="remaining_balance"
                                   value="{{ number_format($remaining) }}" disabled/>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="mb-3">
                            <label for="amount" class="form-label">Amount:</label>
                            <input type="text" class="form-control" id="amount" name="amount"/>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="mb-3">
                            <label for="payment_date" class="form-label">Payment Date:</label>
                            <input type="date" class="form-control" id="payment_date" name="payment_date"
                                   value="{{ date('Y-m-d') }}"/>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="mb-3">
                            <label for="payment_method_id" class="form-label">Payment Method:</label>
                            <select class="form-select" id="payment_method_id" name="payment_method_id">
                                <option value="">Select Payment Method</option>
                                @foreach(\App\Models\PaymentMethod::query()->get() as $paymentMethod)
                                    <option value="{{ $paymentMethod->id }}">{{ $paymentMethod->name }}</option>
                                @endforeach
                            </select>

                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="mb-3">
                            <label for="reference" class="form-label">Reference #:</label>
                            <input type="text" class="form-control" id="reference" name="reference"/>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="mb-3">
                            <label for="attachment" class="form-label">Attachment:</label>
                            <input type="file" class="form-control" id="attachment" name="attachment"/>
                        </div>
                    </div>

                </div>

                <button type="submit" class="btn btn-primary">Save Payment</button>
            </form>
        </div>
    @endif
</div>
