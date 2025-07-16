@extends('backend.layouts.app')


@section('content')
    <style>
        .dropdown-toggle::after {
            display: none; /* Hides the dropdown arrow */
        }
    </style>
  <div class="card">
    <form action="" id="sort_orders" method="GET">
        <div class="card-header row gutters-5">
            <div class="col">
                <h5 class="mb-md-0 h6">{{ translate('Profit Report') }}</h5>
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
                    <input type="text" class="form-control" id="search" name="search"
                        @isset($sort_search) value="{{ $sort_search }}" @endisset
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
                        <th>#</th>
                        <th>{{ translate('Order Code') }}</th>
                        <th>{{ translate('Customer') }}</th>
                        <th>{{ translate('Date') }}</th>
                        <th>{{ translate('Product Total') }}</th>
                        <th>{{ translate('Product Cost') }}</th>
                        <th>{{ translate('Profit') }}</th>
                        <th>{{ translate('China Courier Charge') }}</th>
                        <th>{{ translate('Shipping Cost') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($orders as $key => $order)
                        <tr>
                            <td>{{ $key + 1 + ($orders->currentPage() - 1) * $orders->perPage() }}</td>

                            <td>
                                {{ $order->code }}
                                @if ($order->viewed == 0)
                                    <span class="badge badge-inline badge-info">{{ translate('New') }}</span>
                                @endif
                            </td>

                            <td>
                                @if ($order->user)
                                    {{ $order->user->name !== '' ? $order->user->name : $order->user->phone }}
                                @else
                                    {{ translate('Guest') }} ({{ $order->guest_id }})
                                @endif
                            </td>

                            <td>{{ date('d-m-Y h:i A', $order->date) }}</td>

                            <td>{{ single_price($order->grand_total) }}</td>
                            <td>{{ single_price($order->total_amount_cost) }}</td>
                            <td>{{ single_price($order->grand_total - $order->total_amount_cost) }}</td>
                            <td>{{ single_price($order->china_courier_charge) }}</td>
                            <td>{{ single_price($order->shipping_cost) }}</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="4" class="text-right">{{ translate('Total') }}:</th>
                        <th>{{ single_price($orders->sum('grand_total')) }}</th>
                        <th>{{ single_price($orders->sum('total_amount_cost')) }}</th>
                        <th>{{ single_price($orders->sum('grand_total') - $orders->sum('total_amount_cost')) }}</th>
                        <th>{{ single_price($orders->sum('china_courier_charge')) }}</th>
                        <th>{{ single_price($orders->sum('shipping_cost')) }}</th>
                    </tr>
                </tfoot>

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
