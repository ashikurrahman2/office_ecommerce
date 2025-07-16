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
                    <h5 class="mb-md-0 h6">{{ translate('All Orders') }}</h5>
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
                        <select class="form-control aiz-selectpicker" name="order_type" id="order_type">
                            <option value="">{{ translate('Filter by Order Type') }}</option>
                            <option value="ship_for_me" @if ($order_type == 'ship_for_me') selected @endif>{{ translate('Ship for Me') }}</option>
                            <option value="buy_ship" @if ($order_type == 'buy_ship') selected @endif>{{ translate('Buy Ship') }}</option>
                            <option value="normal" @if ($order_type == 'normal') selected @endif>{{ translate('Normal') }}</option>
                        </select>
                    </div>

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
                            <th data-breakpoints="md">{{ translate('Num. of Products') }}</th>
                            <th data-breakpoints="md">{{ translate('Customer') }}</th>
                            {{-- <th data-breakpoints="md">{{ translate('Seller') }}</th> --}}
                            <th data-breakpoints="md">{{ translate('Amount') }}</th>
                            {{-- <th data-breakpoints="md">{{ translate('Delivery Status') }}</th> --}}
                            <th data-breakpoints="md">{{ translate('Type') }}</th>
                            <th data-breakpoints="md">{{ translate('Payment method') }}</th>
                            <th data-breakpoints="md">{{ translate('Payment Status') }}</th>
                            <th data-breakpoints="md">{{ translate('Status') }}</th>
                            @if (addon_is_activated('refund_request'))
                                <th>{{ translate('Refund') }}</th>
                            @endif
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
                                    {{ count($order->orderDetails) }}
                                </td>
                                <td>
                                    @if ($order->user != null)
                                        {{ $order->user->name }}
                                    @else
                                        Guest ({{ $order->guest_id }})
                                    @endif
                                </td>
                                {{-- <td>
                                    @if ($order->shop)
                                        {{ $order->shop->name }}
                                    @else
                                        {{ translate('Inhouse Order') }}
                                    @endif
                                </td> --}}
                                <td>
                                    {{ single_price($order->grand_total) }}
                                </td>
                                {{-- <td>
                                    {{ translate(ucfirst(str_replace('_', ' ', $order->delivery_status))) }}
                                </td> --}}
                                <td>
                                    @if ($order->order_type == 'ship_for_me')
                                        <span class="badge badge-inline badge-success">{{ translate('Ship For Me') }}</span>
                                    @elseif($order->order_type == 'buy_ship')
                                        <span class="badge badge-inline badge-info">{{ translate('Buy & Ship') }}</span>
                                         @else
                                        <span class="">{{ translate('Orders') }}</span>
                                    @endif
                                </td>
                                <td>
                                    {{ translate(ucfirst(str_replace('_', ' ', $order->payment_type))) }}
                                </td>
                               
                                <td>
                                    @if ($order->payment_status == 'paid')
                                        <span class="badge badge-inline badge-success">{{ translate('Paid') }}</span>
                                    @else
                                        <span class="badge badge-inline badge-danger">{{ translate('Unpaid') }}</span>
                                    @endif
                                </td>
                                <td>
                                    @if ($order->status == 'order_placement')
                                        <span class="badge badge-inline badge-primary">{{ translate('Order Placement') }}</span>
                                    @elseif ($order->status == 'buying_goods')
                                        <span class="badge badge-inline badge-info">{{ translate('Buying Goods') }}</span>
                                    @elseif ($order->status == 'goods_received_in_china_warehouse')
                                        <span class="badge badge-inline badge-warning">{{ translate('Goods Received in China Warehouse') }}</span>
                                   {{-- @elseif ($order->status == 'shipment_done')
                                        <span class="badge badge-inline badge-secondary">{{ translate('Shipment Done') }}</span>--}}
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
                               
                                
                                    <div class="d-inline-block mb-2 mb-md-0">
                                        <button class="btn btn-soft-info btn-icon btn-circle btn-sm" type="button" data-toggle="dropdown" title="{{ translate('Status') }}">
                                            <i class="las la-sync-alt"></i>
                                        </button>
                                        
                                        <div class="dropdown-menu dropdown-menu-right">
                                            <a class="dropdown-item confirm-alert {{ $order->status == 'order_placement' ? 'active' : '' }}" 
                                               data-target="#change-shipping-status-modal" 
                                               href="javascript:void(0)"  
                                               data-order-id="{{ $order->id }}" 
                                               data-value="order_placement">
                                                {{ translate('Order Placement') }}
                                            </a>
                                            <a class="dropdown-item confirm-alert {{ $order->status == 'buying_goods' ? 'active' : '' }}" 
                                               data-target="#change-shipping-status-modal" 
                                               href="javascript:void(0)"  
                                               data-order-id="{{ $order->id }}" 
                                               data-value="buying_goods">
                                                {{ translate('Buying Goods') }}
                                            </a>
                                            <a class="dropdown-item confirm-alert {{ $order->status == 'goods_received_in_china_warehouse' ? 'active' : '' }}" 
                                               data-target="#change-shipping-status-modal" 
                                               href="javascript:void(0)"  
                                               data-order-id="{{ $order->id }}" 
                                               data-value="goods_received_in_china_warehouse">
                                                {{ translate('Goods Received in China Warehouse') }}
                                            </a>
                                            <!--<a class="dropdown-item confirm-alert {{ $order->status == 'shipment_done' ? 'active' : '' }}" -->
                                            <!--   data-target="#change-shipping-status-modal" -->
                                            <!--   href="javascript:void(0)"  -->
                                            <!--   data-order-id="{{ $order->id }}" -->
                                            <!--   data-value="shipment_done">-->
                                            <!--    {{ translate('Shipment Done') }}-->
                                            <!--</a>-->
                                            <a class="dropdown-item confirm-alert {{ $order->status == 'on_the_way' ? 'active' : '' }}" 
                                               data-target="#change-shipping-status-modal" 
                                               href="javascript:void(0)"  
                                               data-order-id="{{ $order->id }}" 
                                               data-value="on_the_way">
                                                {{ translate('On the Way') }}
                                            </a>
                                            
                                            <a class="dropdown-item confirm-alert {{ $order->status == 'waiting_for_custom_formalities' ? 'active' : '' }}" 
                                               data-target="#change-shipping-status-modal" 
                                               href="javascript:void(0)"  
                                               data-order-id="{{ $order->id }}" 
                                               data-value="waiting_for_custom_formalities">
                                                {{ translate('Waiting for Custom Formalities') }}
                                            </a>
                                            <a class="dropdown-item confirm-alert {{ $order->status == 'goods_received_in_bangladesh' ? 'active' : '' }}" 
                                                data-target="#change-shipping-status-modal" 
                                                href="javascript:void(0)"  
                                                data-order-id="{{ $order->id }}" 
                                                data-value="goods_received_in_bangladesh">
                                                 {{ translate('Goods Received in Bangladesh') }}
                                             </a>
                                            <a class="dropdown-item confirm-alert {{ $order->status == 'ready_to_deliver' ? 'active' : '' }}" 
                                               data-target="#change-shipping-status-modal" 
                                               href="javascript:void(0)"  
                                               data-order-id="{{ $order->id }}" 
                                               data-value="ready_to_deliver">
                                                {{ translate('Ready to Deliver') }}
                                            </a>
                                            <a class="dropdown-item confirm-alert {{ $order->status == 'delivered' ? 'active' : '' }}" 
                                               data-target="#change-shipping-status-modal" 
                                               href="javascript:void(0)"  
                                               data-order-id="{{ $order->id }}" 
                                               data-value="delivered">
                                                {{ translate('Delivered') }}
                                            </a>
                                            <a class="dropdown-item confirm-alert {{ $order->status == 'order_cancelled' ? 'active' : '' }}" 
                                               data-target="#change-shipping-status-modal" 
                                               href="javascript:void(0)"  
                                               data-order-id="{{ $order->id }}" 
                                               data-value="order_cancelled">
                                                {{ translate('Order Cancel') }}
                                            </a>
                                            
                                        </div>
                                    </div>

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
