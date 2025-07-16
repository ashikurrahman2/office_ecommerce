@extends('backend.layouts.app')


@section('content')
    <style>
        .dropdown-toggle::after {
            display: none; /* Hides the dropdown arrow */
        }
    </style>
    <div class="card">
        <form class="" action="" id="sort_orders" method="GET">
            <div class="card-header row gutters-5">
                <div class="col">
                    <h5 class="mb-md-0 h6">{{ translate('Purchase') }}</h5>
                </div>

                @can('delete_order')
                    <div class="dropdown mb-2 mb-md-0">
                        <button class="btn border dropdown-toggle" type="button" data-toggle="dropdown">
                            {{ translate('Bulk Action') }}
                        </button>
                        <div class="dropdown-menu dropdown-menu-right">
                            <a class="dropdown-item confirm-alert" href="javascript:void(0)"  data-target="#bulk-delete-modal">
                                {{ translate('Delete selection') }}</a>
                        </div>
                    </div>
                @endcan
                    <div class="col-lg-2 ml-auto">
                        <select class="form-control aiz-selectpicker" name="delivery_status" id="delivery_status">
                            <option value="">{{ translate('Filter by Delivery Status') }}</option>
                            <option value="order_placement" @if ($delivery_status == 'order_placement') selected @endif>{{ translate('Order Placement') }}</option>
                            <option value="buying_goods" @if ($delivery_status == 'buying_goods') selected @endif>{{ translate('Buying Goods') }}</option>
                            <option value="goods_received_in_china_warehouse" @if ($delivery_status == 'goods_received_in_china_warehouse') selected @endif>
                                {{ translate('Goods Received in China Warehouse') }}</option>
                            <option value="shipment_done" @if ($delivery_status == 'shipment_done') selected @endif>{{ translate('Shipment Done') }}</option>
                            <option value="goods_received_in_bangladesh" @if ($delivery_status == 'goods_received_in_bangladesh') selected @endif>
                                {{ translate('Goods Received in Bangladesh') }}</option>
                            <option value="ready_to_deliver" @if ($delivery_status == 'ready_to_deliver') selected @endif>{{ translate('Ready to Deliver') }}</option>
                            <option value="delivered" @if ($delivery_status == 'delivered') selected @endif>{{ translate('Delivered') }}</option>
                            <option value="order_cancelled" @if ($delivery_status == 'order_cancelled') selected @endif>{{ translate('Order Cancelled') }}</option>
                        </select>
                    </div>

               
                <div class="col-lg-2 ml-auto">
                    <select class="form-control aiz-selectpicker" name="payment_status" id="payment_status">
                        <option value="">{{ translate('Filter by Payment Status') }}</option>
                        <option value="paid"
                            @isset($payment_status) @if ($payment_status == 'paid') selected @endif @endisset>
                            {{ translate('Paid') }}</option>
                        <option value="unpaid"
                            @isset($payment_status) @if ($payment_status == 'unpaid') selected @endif @endisset>
                            {{ translate('Unpaid') }}</option>
                    </select>
                </div>
                <div class="col-lg-2">
                    <div class="form-group mb-0">
                        <input type="text" class="aiz-date-range form-control" value="{{ $date }}"
                            name="date" placeholder="{{ translate('Filter by date') }}" data-format="DD-MM-Y"
                            data-separator=" to " data-advanced-range="true" autocomplete="off">
                    </div>
                </div>
                <div class="col-lg-2">
                    <div class="form-group mb-0">
                        <input type="text" class="form-control" id="search"
                            name="search"@isset($sort_search) value="{{ $sort_search }}" @endisset
                            placeholder="{{ translate('Type Order code & hit Enter') }}">
                    </div>
                </div>
                <div class="col-auto">
                    <div class="form-group mb-0">
                        <button type="submit" class="btn btn-primary">{{ translate('Filter') }}</button>
                    </div>
                </div> 
            </div>

            <div class="card-body">
                <table class="table aiz-table mb-0">
                    <thead>
                        <tr>
                            <!--<th>#</th>-->
                            @if (auth()->user()->can('delete_order'))
                                <th>
                                    <div class="form-group">
                                        <div class="aiz-checkbox-inline">
                                            <label class="aiz-checkbox">
                                                <input type="checkbox" class="check-all">
                                                <span class="aiz-square-check"></span>
                                            </label>
                                        </div>
                                    </div>
                                </th>
                            @else
                                <th data-breakpoints="lg">#</th>
                            @endif

                            <th>{{ translate('Order Code') }}</th>
                            <th data-breakpoints="md">{{ translate('Customer') }}</th>
                            <th data-breakpoints="md">{{ translate('Date') }}</th>
                            <th data-breakpoints="md">{{ translate('Total Amount') }}</th>
                            <th data-breakpoints="md">{{ translate('Paid') }}</th>
                            <th data-breakpoints="md">{{ translate('Due') }}</th>
                            <th data-breakpoints="md">{{ translate('Payment method') }}</th>
                            <th class="text-right" width="15%">{{ translate('Action') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($orders as $key => $order)
                            <tr>
                                @if (auth()->user()->can('delete_order'))
                                    <td>
                                        <div class="form-group">
                                            <div class="aiz-checkbox-inline">
                                                <label class="aiz-checkbox">
                                                    <input type="checkbox" class="check-one" name="id[]"
                                                        value="{{ $order->id }}">
                                                    <span class="aiz-square-check"></span>
                                                </label>
                                            </div>
                                        </div>
                                    </td>
                                @else
                                    <td>{{ $key + 1 + ($orders->currentPage() - 1) * $orders->perPage() }}</td>
                                @endif
                                <td>
                                    {{ $order->code }}
                                    @if ($order->viewed == 0)
                                        <span class="badge badge-inline badge-info">{{ translate('New') }}</span>
                                    @endif
                                   <strong>
                                             @if (!empty($order->product_url))
                                          
                                              <ul class="list-unstyled">
                                                <a href="{{ route('product', $order->product_id) }}" target="_blank" class="text-muted">
                                               EzShop
                                            </a>
                                            <a href="{{  $order->product_url }}" class="text-primary" target="_blank" class="text-muted">
                                                1688
                                            </a>
                                            </ul>
                                            @endif
                                        </strong>

                                </td>
                               
                                <td>
                                    @if ($order->user != null)
                                        {{ $order->user->name }}
                                    @else
                                        Guest ({{ $order->guest_id }})
                                    @endif
                                </td>
                                <td>
                                  {{ date('d-m-Y h:i A', $order->date) }}
                                </td>
                                <td>
                                    {{ single_price($order->total_amount) }}
                                </td>
                                <td>
                                    {{ single_price($order->paid_amount) }}
                                </td>
                                 <td>
                                    {{ single_price($order->due_amount) }}
                                </td>
                                <td>
                                    {{ translate(ucfirst(str_replace('_', ' ', $order->payment_type))) }}
                                </td>
                               
                               
                               
                                @if (addon_is_activated('refund_request'))
                                    <td>
                                        @if (count($order->refund_requests) > 0)
                                            {{ count($order->refund_requests) }} {{ translate('Refund') }}
                                        @else
                                            {{ translate('No Refund') }}
                                        @endif
                                    </td>
                                @endif
                                <td class="text-right">
                                   
                                
                                    

                                   <a href="javascript:void(0)"
                                    class="btn btn-sm btn-danger confirm-alert"
                                    data-toggle="modal"
                                    data-target="#tracking-code-modal"
                                    data-order-id="{{ $order->id }}"
                                    
                                    <i class="fa fa-truck"></i> Add Weight
                                    </a>
                                   
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <div class="aiz-pagination">
                    {{ $orders->appends(request()->input())->links() }}
                </div>

            </div>
        </form>
    </div>

<!-- Tracking Code Modal -->
<div id="tracking-code-modal" class="modal fade">
  <div class="modal-dialog modal-sm modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title h6">Add Weight</h4>
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
      </div>

      <div class="modal-body">
        <input type="hidden" name="order_id" id="tracking_order_id">
        <input type="number" name="tracking_code" id="tracking_code_input" class="form-control" placeholder="Weight">
        <div class="text-right mt-3">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
          <button type="button" onclick="save_tracking_code()" class="btn btn-primary">Save</button>
        </div>
      </div>
    </div>
  </div>
</div>



@endsection

@section('modal')
    <!-- Delete modal -->
    @include('modals.delete_modal')
    <!-- Bulk Delete modal -->
    @include('modals.bulk_delete_modal')
    {{-- @include('modals.change_status_modal') --}}
@endsection

@section('script')
   <script>

$(document).on('click', '[data-toggle="modal"][data-target="#tracking-code-modal"]', function () {
    var orderId = $(this).data('order-id');
    var trackingCode = $(this).data('tracking-code');
console.log(orderId,trackingCode)
    $('#tracking_order_id').val(orderId);
    $('#tracking_code_input').val(trackingCode);

    $('#tracking-code-modal').modal('show'); // manually trigger modal in case it's not opening due to dropdown
});

function save_tracking_code() {
    var orderId = $('#tracking_order_id').val();
    var trackingCode = $('#tracking_code_input').val();

    if (!orderId || !trackingCode) {
        alert('Weight is required.');
        return;
    }

    $.ajax({
        url: '{{ route("orders.update_weight") }}', // Update with your actual route
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        data: {
            order_id: orderId,
            weight: trackingCode,
        },
        success: function(response) {
            $('#tracking-code-modal').modal('hide');
            location.reload();
        },
        error: function(xhr) {
            alert('Error: ' + xhr.responseText);
        }
    });
}
</script>



@endsection
