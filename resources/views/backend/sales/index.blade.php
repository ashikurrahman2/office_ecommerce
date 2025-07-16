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
                            <option value="purchase_complete" @if ($delivery_status == 'purchase_complete') selected @endif>{{ translate('Purchase Complete') }}</option>
                           <option value="goods_received_in_china_warehouse" @if ($delivery_status == 'goods_received_in_china_warehouse') selected @endif>
                                {{ translate('Goods Received in China Warehouse') }}
                            </option>
                            <option value="receive_in_china_airport" @if ($delivery_status == 'receive_in_china_airport') selected @endif>
                                {{ translate('Receive in China Airport') }}
                            </option>
                            <option value="receive_in_bangladesh_airport" @if ($delivery_status == 'receive_in_bangladesh_airport') selected @endif>
                                {{ translate('Receive in Bangladesh Airport') }}
                            </option>
                            <option value="shorting_complete" @if ($delivery_status == 'shorting_complete') selected @endif>
                                {{ translate('Shorting Complete') }}
                            </option>
                            <option value="delivered" @if ($delivery_status == 'delivered') selected @endif>
                                {{ translate('Delivered') }}
                            </option>
                           
                            <option value="order_cancelled" @if ($delivery_status == 'order_cancelled') selected @endif>{{ translate('Order Cancelled') }}</option>
                        </select>
                    </div>

               
                <div class="col-lg-2 ml-auto">
                    <select class="form-control aiz-selectpicker" name="payment_status" id="payment_status">
                        <option value="">{{ translate('Filter by Payment Status') }}</option>
                        <option value="paid"
                            @isset($payment_status) @if ($payment_status == 'paid') selected @endif @endisset>
                            {{ translate('Partially Paid') }}</option>
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
                            <th data-breakpoints="md">{{ translate('Payment Data') }}</th>
                            
                             <th data-breakpoints="md">{{ translate('Delivery Status') }}</th>
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
                                    @if (addon_is_activated('pos_system') && $order->order_from == 'pos')
                                        <span class="badge badge-inline badge-danger">{{ translate('POS') }}</span>
                                    @endif
                                </td>
                               
                                <td>
                                    @if ($order->user != null)
                                        {{ $order->user->name !== '' ? $order->user->name : $order->user->phone }}
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
                               <td>
                                @if ($order->manual_payment && is_array(json_decode($order->manual_payment_data, true)))
                                    @php
                                        $manual = json_decode($order->manual_payment_data);
                                    @endphp
                                    <button type="button"
                                            class="btn btn-sm btn-outline-primary"
                                            data-toggle="modal"
                                            data-target="#manualPaymentModal{{ $order->id }}">
                                        {{ translate('Show') }}
                                    </button>
                            
                                    <!-- Modal -->
                                    <div class="modal fade" id="manualPaymentModal{{ $order->id }}" tabindex="-1" role="dialog" aria-labelledby="manualPaymentLabel{{ $order->id }}" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="manualPaymentLabel{{ $order->id }}">{{ translate('Manual Payment Information') }}</h5>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="{{ translate('Close') }}">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <p><strong>{{ translate('Name') }}:</strong> {{ $manual->name }}</p>
                                                    <p><strong>{{ translate('Amount') }}:</strong> {{ single_price($manual->amount) }}</p>
                                                   
                            
                                                    @if (!empty($manual->photo))
                                                        <p><strong>{{ translate('Receipt') }}:</strong></p>
                                                        <a href="{{ uploaded_asset($manual->photo) }}" target="_blank">
                                                            <img src="{{ uploaded_asset($manual->photo) }}" alt="Receipt" class="img-fluid rounded border" style="max-height: 300px;">
                                                        </a>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    <span class="text-muted">{{ translate('N/A') }}</span>
                                @endif
                       
                               <td>
                                <span class="badge badge-inline badge-warning">
                                    {{translate(ucfirst(str_replace('_', ' ', $order->status)))}}
                                </span>
                                </td>
                               
                                {{-- <td>
                                    @if ($order->status == 'order_placement')
                                        <span class="badge badge-inline badge-primary">{{ translate('Order Placement') }}</span>
                                    @elseif ($order->status == 'buying_goods')
                                        <span class="badge badge-inline badge-info">{{ translate('Buying Goods') }}</span>
                                    @elseif ($order->status == 'goods_received_in_china_warehouse')
                                        <span class="badge badge-inline badge-warning">{{ translate('Goods Received in China Warehouse') }}</span>
                                  
                                     @elseif ($order->status == 'on_the_way')
                                        <span class="badge badge-inline badge-secondary">{{ translate('On the Way') }}</span>
                                    @elseif ($order->status == 'waiting_for_custom_formalities')
                                        <span class="badge badge-inline badge-warning">{{ translate('Waiting for Custom Formalities') }}</span>
                                    @elseif ($order->status == 'goods_received_in_bangladesh')
                                        <span class="badge badge-inline badge-success">{{ translate('Goods Received in Bangladesh') }}</span>
                                    @elseif ($order->status == 'ready_to_deliver')
                                        <span class="badge badge-inline badge-success">{{ translate('Ready to Deliver') }}</span>
                                    @elseif ($order->status == 'delivered')
                                    <span class="badge badge-inline badge-success">{{ translate('delivered') }}</span>

                                    @elseif ($order->status == 'order_cancelled')
                                        <span class="badge badge-inline badge-danger">{{ translate('Order Cancelled') }}</span>
                                    @endif
                                </td> --}}
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
                                     
                                @php
                              
                                    $payment_status = $order->payment_status;
                                
                                    $is_paid = in_array($payment_status, ['paid', 'fullpaid']); // check both
                                
                                    $icon = $is_paid ? 'la-check-circle' : 'la-times-circle';
                                    $color = $is_paid ? 'success' : 'danger';
                                    $title = $is_paid ? translate('Mark as Unpaid') : translate('Mark as Paid');
                                @endphp

                               
                                    @if (auth()->user()->can('update_order_payment_status') && $payment_status != 'fullpaid')
                                    
                                        <a href="javascript:void(0)" 
                                           class="btn btn-soft-{{ $color }} btn-icon btn-circle btn-sm payment-status-toggle"
                                           data-order-id="{{ $order->id }}"
                                           data-current-status="{{ $payment_status }}"
                                           title="{{ $title }}"
                                           onclick="confirmPaymentStatusChange(this)">
                                            <i class="las {{ $icon }}"></i>
                                        </a>
                                       
                                    @else
                                        <span class="badge bg-{{ $color }}">
                                            <i class="las {{ $icon }}"></i> {{ ucfirst($payment_status) }}
                                        </span>
                                    @endif
                               

                                    @if (addon_is_activated('pos_system') && $order->order_from == 'pos')
                                        <a class="btn btn-soft-success btn-icon btn-circle btn-sm"
                                            href="{{ route('admin.invoice.thermal_printer', $order->id) }}" target="_blank"
                                            title="{{ translate('Thermal Printer') }}">
                                            <i class="las la-print"></i>
                                        </a>
                                    @endif
                                    @can('view_order_details')
                                        @php
                                            $order_detail_route = route('orders.show', encrypt($order->id));
                                            if (Route::currentRouteName() == 'seller_orders.index') {
                                                $order_detail_route = route('seller_orders.show', encrypt($order->id));
                                            } elseif (Route::currentRouteName() == 'pick_up_point.index') {
                                                $order_detail_route = route('pick_up_point.order_show', encrypt($order->id));
                                            }
                                            if (Route::currentRouteName() == 'inhouse_orders.index') {
                                                $order_detail_route = route('inhouse_orders.show', encrypt($order->id));
                                            }
                                        @endphp
                                        <a class="btn btn-soft-primary btn-icon btn-circle btn-sm"
                                            href="{{ $order_detail_route }}" title="{{ translate('View') }}">
                                            <i class="las la-eye"></i>
                                        </a>
                                    @endcan
                               
                                
                                  

                                    @can('delete_order')
                                        <a href="#"
                                            class="btn btn-soft-danger btn-icon btn-circle btn-sm confirm-delete"
                                            data-href="{{ route('orders.destroy', $order->id) }}"
                                            title="{{ translate('Delete') }}">
                                            <i class="las la-trash"></i>
                                        </a>
                                    @endcan
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


    <div id="change-shipping-status-modal" class="modal fade">
        <div class="modal-dialog modal-sm modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title h6">{{ translate('Status Confirmation') }}</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                </div>
                <div class="modal-body text-center">
                    <p class="mt-1">{{ translate('Are you sure to change status?') }}</p>
                    <button type="button" class="btn btn-link mt-2" data-dismiss="modal">{{ translate('Cancel') }}</button>
                    <a href="javascript:void(0)" onclick="change_status()" class="btn btn-primary mt-2 status-change-button">{{ translate('Change') }}</a>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="payment-status-confirm-modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ translate('Confirm Status Change') }}</h5>
                <button type="button" class="close" data-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>{{ translate('Are you sure you want to change the payment status?') }}</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-sm btn-light" data-dismiss="modal">{{ translate('Cancel') }}</button>
                <button type="button" class="btn btn-sm btn-primary" id="confirm-payment-status-change">{{ translate('Confirm') }}</button>
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

    <script type="text/javascript">
    
  // Initialize click handler - using event delegation
$(document).on('click', '.payment-status-toggle', function(e) {
  
    e.preventDefault();
    var button = $(this);
   
    
    // Store data in a global object if AIZ is not available
    window.paymentStatusData = {
        orderId: button.data('order-id'),
        currentStatus: button.data('current-status'),
        button: button
    };
    
    // Check if AIZ exists and use its modal, otherwise use Bootstrap directly
    if (typeof AIZ !== 'undefined' && AIZ.plugins.confirm) {
        AIZ.plugins.confirm({
            title: '{{ translate("Confirm status change") }}',
            text: '{{ translate("Are you sure you want to change the payment status?") }}',
            type: 'warning',
            onConfirm: function() {
                updatePaymentStatus(button);
            }
        });
    } else {
        // Fallback to Bootstrap modal
    
        $('#payment-status-confirm-modal').modal('show');
    }
});

// Update function
function updatePaymentStatus(button) {
    var data = window.paymentStatusData;
    var newStatus = data.currentStatus == 'paid' ? 'unpaid' : 'paid';
    
   
    button.prop('disabled', true);
    
    $.post('{{ route('orders.update_payment_status') }}', {
        _token: '{{ @csrf_token() }}',
        order_id: data.orderId,
        status: newStatus
    })
    .done(function(data) {
       
        var icon = newStatus == 'paid' ? 'la-check-circle' : 'la-times-circle';
        var color = newStatus == 'paid' ? 'success' : 'danger';
        var title = newStatus == 'paid' ? '{{ translate("Mark as Unpaid") }}' : '{{ translate("Mark as Paid") }}';
        
        button.removeClass('btn-soft-success btn-soft-danger')
              .addClass('btn-soft-' + color)
              .attr('title', title)
              .data('current-status', newStatus);
        
        button.find('i').attr('class', 'las ' + icon);
        
        if (typeof AIZ !== 'undefined' && AIZ.plugins.notify) {
            AIZ.plugins.notify('success', '{{ translate('Payment status has been updated') }}');
        } else {
            alert('Status updated successfully');
        }
    })
    .fail(function(xhr, status, error) {
        console.error('Update failed:', error); // Debugging line
        if (typeof AIZ !== 'undefined' && AIZ.plugins.notify) {
            AIZ.plugins.notify('danger', '{{ translate('Update failed') }}');
        } else {
            alert('Update failed: ' + error);
        }
    })
    .always(function() {
        button.prop('disabled', false);
    });
}

// Confirmation modal handler (if using Bootstrap modal)
$(document).on('click', '#confirm-payment-status-change', function(e) {
    e.preventDefault();
   
    if (window.paymentStatusData && window.paymentStatusData.button) {
        updatePaymentStatus(window.paymentStatusData.button);
    }
    $('#payment-status-confirm-modal').modal('hide');
});
        $(document).on("change", ".check-all", function() {
            if (this.checked) {
                // Iterate each checkbox
                $('.check-one:checkbox').each(function() {
                    this.checked = true;
                });
            } else {
                $('.check-one:checkbox').each(function() {
                    this.checked = false;
                });
            }

        });

        $(document).on('click', '.confirm-alert', function() {
            selectedStatus = $(this).data('value');
            selectedOrderId = $(this).data('order-id');

            $('#change-shipping-status-modal').data('order-id', selectedOrderId);
            $('#change-shipping-status-modal').data('status', selectedStatus);

            // Show the modal
            $('#change-shipping-status-modal').modal('show');
        });

        function change_status() {
            const selectedOrderId = $('#change-shipping-status-modal').data('order-id');
            const selectedStatus = $('#change-shipping-status-modal').data('status');

            // Find the button that triggers the status change and disable it
            const submitButton = $('.status-change-button');

            // Add spinner to the submit button
            if (submitButton.find('.spinner-border').length === 0) {
                submitButton.prop('disabled', true).append(
                    '<div class="spinner-border" role="status" style="width: 1rem; height: 1rem; margin-left: 0.5rem;">' +
                    '<span class="sr-only">Loading...</span>' +
                    '</div>'
                );
            }

            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "{{ route('orders.change_shipping_status') }}",
                type: 'POST',
                data: {
                    order_id: selectedOrderId,
                    status: selectedStatus,
                },
                success: function(response) {
                    if (response.success) {
                        location.reload();
                    } else {
                        alert(response.message);
                    }
                },
                error: function(xhr) {
                    alert('An error occurred: ' + xhr.responseText);
                },
                complete: function() {
                    // Re-enable the submit button and remove spinner
                    submitButton.prop('disabled', false).find('.spinner-border').remove();
                }
            });
        }

        function bulk_delete() {
            var data = new FormData($('#sort_orders')[0]);
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "{{ route('bulk-order-delete') }}",
                type: 'POST',
                data: data,
                cache: false,
                contentType: false,
                processData: false,
                success: function(response) {
                    if (response == 1) {
                        location.reload();
                    }
                }
            });
        }
    </script>
@endsection
