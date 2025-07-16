@extends('frontend.layouts.user_panel')

@section('panel_content')
<style>
.filter-tab {
    background-color: transparent;
    border: none;
    font-size: 16px;
    font-weight: 500;
    color: #333;
    position: relative;
    cursor: pointer;
    text-align: center;
}

.filter-tab.active {
    font-weight: 700;
    color:  var(--primary) !important; /* Green color for active tab */
}

.filter-tab::after {
    content: '';
    display: block;
    height: 2px;
    width: 0%;
    background-color:  var(--primary) !important; /* Underline color */
    transition: width 0.3s ease;
    margin: 5px auto 0;
}

.filter-tab.active::after,
.filter-tab:hover::after {
    width: 100%; /* Underline spans full width */
}

</style>
    <div class="card shadow-none rounded-0 border">
        <div class="card-header border-bottom-0">
            <h5 class="mb-0 fs-20 fw-700 text-dark">{{ translate('Purchase History') }} (<span class="fs-15 fw-500">{{ucwords($filter)}}</span>)</h5>
        </div>
       
            <!-- Filter Buttons -->
           @if($filter == 'forward-parcel')
           
            <div class="d-flex justify-content-around align-items-center mb-3">
                <!-- Abroad to Bangladesh Button -->
                <button 
                    onclick="filterOrders('bangladesh')" 
                    class="filter-tab px-4 py-2 {{ request()->input('filter-status') == 'bangladesh' ? 'active' : '' }}">
                    {{ translate('Abroad To Bangladesh') }} ({{ $bangladeshCount ?? 0 }})
                </button>
            
                <!-- Bangladesh to You Button -->
                <button 
                    onclick="filterOrders('abroad')" 
                    class="filter-tab px-4 py-2 {{ request()->input('filter-status') == 'abroad' ? 'active' : '' }}">
                    {{ translate('Bangladesh To You') }} ({{ $abroadCount ?? 0 }})
                </button>
            </div>

        @endif
     
        <div class="card-body">
            <table class="table aiz-table mb-0">
                <thead class="text-gray fs-12">
                    <tr>
                        <th class="pl-0">{{ translate('Code')}}</th>
                        <th>{{ translate('Amount')}}</th>
                        <th data-breakpoints="md">{{ translate('Date')}}</th>
                        <th data-breakpoints="md">{{ translate('Payment Status')}}</th>
                        <th data-breakpoints="md">{{ translate('Order Status')}}</th>
                        <th data-breakpoints="md" class="text-right pr-0">{{ translate('Options')}}</th>
                    </tr>
                </thead>
                <tbody class="fs-14" id="orderTableBody">
                    @if($filter == 'all')
                    @foreach ($orders as $key => $order)
                        @if (count($order->orderDetails) > 0)
                            <tr>
                                <!-- Code -->
                                <td class="pl-0">
                                    <a href="{{route('purchase_history.details', encrypt($order->id))}}">{{ $order->code }}</a>
                                </td>
                                 <!-- Amount -->
                                <td class="fw-700">
                                    {{ single_price($order->grand_total) }}
                                </td>
                                <!-- Date -->
                                <td class="text-secondary">{{ date('d-m-Y', $order->date) }}</td>
                               
                                <!-- Payment Status -->
                                <td>
                                    @if ($order->payment_status == 'paid')
                                        <span class="badge badge-inline badge-success py-1 fs-12">{{translate('Paid')}}</span>
                                    @else
                                        <span class="badge badge-inline badge-danger  py-1 fs-12">{{translate('Unpaid')}}</span>
                                    @endif
                                </td>
                                <td>
                                 @if ($order->status == 'order_placement')
                                    <span class="badge badge-inline badge-primary py-1 fs-12">{{ translate('Order Placement') }}</span>
                                @elseif ($order->status == 'buying_goods')
                                    <span class="badge badge-inline badge-info py-1 fs-12">{{ translate('Buying Goods') }}</span>
                                @elseif ($order->status == 'goods_received_in_china_warehouse')
                                    <span class="badge badge-inline badge-warning py-1 fs-12">{{ translate('Goods Received in China Warehouse') }}</span>
                                {{-- @elseif ($order->status == 'shipment_done')
                                        <span class="badge badge-inline badge-secondary">{{ translate('Shipment Done') }}</span>--}}
                                     @elseif ($order->status == 'on_the_way')
                                        <span class="badge badge-inline badge-secondary">{{ translate('On the Way') }}</span>
                                    @elseif ($order->status == 'waiting_for_custom_formalities')
                                        <span class="badge badge-inline badge-warning">{{ translate('Waiting for Custom Formalities') }}</span>
                                @elseif ($order->status == 'goods_received_in_bangladesh')
                                    <span class="badge badge-inline badge-soft-primary py-1 fs-12">{{ translate('Goods Received in Bangladesh') }}</span>
                                 @elseif ($order->status == 'ready_to_deliver')
                                <span class="badge badge-inline badge-info py-1 fs-12">{{ translate('Ready to Deliver') }}</span>
                                @elseif ($order->status == 'delivered')
                                <span class="badge badge-inline badge-success py-1 fs-12">{{ translate('Delivered') }}</span>
                                
                                @else
                                    <span class="badge badge-inline badge-danger py-1 fs-12">{{ translate('Cancelled') }}</span>
                                @endif
                            </td>
                                <!-- Options -->
                                <td class="text-right pr-0">
                                    <!-- Re-order -->
                                    {{-- <a class="btn-soft-white rounded-3 btn-sm mr-1" href="{{ route('re_order', encrypt($order->id)) }}">
                                        {{ translate('Reorder') }}
                                    </a> --}}
                                    <!-- Cancel -->
                                    @if ($order->order_status == 'order_placement' && $order->payment_status == 'unpaid')
                                        <a href="javascript:void(0)" class="btn btn-soft-danger btn-icon btn-circle btn-sm hov-svg-white mt-2 mt-sm-0 confirm-delete" data-href="{{route('purchase_history.destroy', $order->id)}}" title="{{ translate('Cancel') }}">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="9.202" height="12" viewBox="0 0 9.202 12">
                                                <path id="Path_28714" data-name="Path 28714" d="M15.041,7.608l-.193,5.85a1.927,1.927,0,0,1-1.933,1.864H9.243A1.927,1.927,0,0,1,7.31,13.46L7.117,7.608a.483.483,0,0,1,.966-.032l.193,5.851a.966.966,0,0,0,.966.929h3.672a.966.966,0,0,0,.966-.931l.193-5.849a.483.483,0,1,1,.966.032Zm.639-1.947a.483.483,0,0,1-.483.483H6.961a.483.483,0,1,1,0-.966h1.5a.617.617,0,0,0,.615-.555,1.445,1.445,0,0,1,1.442-1.3h1.126a1.445,1.445,0,0,1,1.442,1.3.617.617,0,0,0,.615.555h1.5a.483.483,0,0,1,.483.483ZM9.913,5.178h2.333a1.6,1.6,0,0,1-.123-.456.483.483,0,0,0-.48-.435H10.516a.483.483,0,0,0-.48.435,1.6,1.6,0,0,1-.124.456ZM10.4,12.5V8.385a.483.483,0,0,0-.966,0V12.5a.483.483,0,1,0,.966,0Zm2.326,0V8.385a.483.483,0,0,0-.966,0V12.5a.483.483,0,1,0,.966,0Z" transform="translate(-6.478 -3.322)" fill="#d43533"/>
                                            </svg>
                                        </a>
                                    @endif
                                    <!-- Details -->
                                    <a href="{{route('purchase_history.details', encrypt($order->id))}}" class="btn btn-soft-info btn-icon btn-circle btn-sm hov-svg-white mt-2 mt-sm-0" title="{{ translate('Order Details') }}">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="12" height="10" viewBox="0 0 12 10">
                                            <g id="Group_24807" data-name="Group 24807" transform="translate(-1339 -422)">
                                                <rect id="Rectangle_18658" data-name="Rectangle 18658" width="12" height="1" transform="translate(1339 422)" fill="#3490f3"/>
                                                <rect id="Rectangle_18659" data-name="Rectangle 18659" width="12" height="1" transform="translate(1339 425)" fill="#3490f3"/>
                                                <rect id="Rectangle_18660" data-name="Rectangle 18660" width="12" height="1" transform="translate(1339 428)" fill="#3490f3"/>
                                                <rect id="Rectangle_18661" data-name="Rectangle 18661" width="12" height="1" transform="translate(1339 431)" fill="#3490f3"/>
                                            </g>
                                        </svg>
                                    </a>
                                    <!-- Invoice -->
                                    {{-- <a class="btn btn-soft-secondary-base btn-icon btn-circle btn-sm hov-svg-white mt-2 mt-sm-0" href="{{ route('invoice.download', $order->id) }}" title="{{ translate('Download Invoice') }}">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12.001" viewBox="0 0 12 12.001">
                                            <g id="Group_24807" data-name="Group 24807" transform="translate(-1341 -424.999)">
                                              <path id="Union_17" data-name="Union 17" d="M13936.389,851.5l.707-.707,2.355,2.355V846h1v7.1l2.306-2.306.707.707-3.538,3.538Z" transform="translate(-12592.95 -421)" fill="#f3af3d"/>
                                              <rect id="Rectangle_18661" data-name="Rectangle 18661" width="12" height="1" transform="translate(1341 436)" fill="#f3af3d"/>
                                            </g>
                                        </svg>
                                    </a> --}}
                                </td>
                            </tr>
                        @endif
                    @endforeach
                    @elseif($filter == 'forward-parcel')
                    @foreach ($orders as $order)
    @if (count($order->orderDetails) > 0)
    
        @php
            // Check order status based on filterStatus
            $status = $order->filterStatus === 'bangladesh' ? 'bangladesh' : 'abroad';
        @endphp
    
        <tr class="order-row" data-status="{{ $status }}">
            <!-- Code -->
         
                                <!-- Code -->
                                <td class="pl-0">
                                    <a href="{{route('purchase_history.details', encrypt($order->id))}}">{{ $order->code }}</a>
                                </td>
                               
                                <!-- Amount -->
                                <td class="fw-700">
                                    {{ single_price($order->grand_total) }}
                                </td>
                                <!-- Payment Status -->
                                 <!-- Date -->
                                <td class="text-secondary">{{ date('d-m-Y', $order->date) }}</td>
                                <td>
                                    @if ($order->payment_status == 'paid')
                                        <span class="badge badge-inline badge-success py-1 fs-12">{{translate('Paid')}}</span>
                                    @else
                                        <span class="badge badge-inline badge-danger py-1 fs-12">{{translate('Unpaid')}}</span>
                                    @endif
                                </td>
                                  <td>
                                 @if ($order->status == 'order_placement')
                                    <span class="badge badge-inline badge-primary py-1 fs-12">{{ translate('Order Placement') }}</span>
                                @elseif ($order->status == 'buying_goods')
                                    <span class="badge badge-inline badge-info py-1 fs-12">{{ translate('Buying Goods') }}</span>
                                @elseif ($order->status == 'goods_received_in_china_warehouse')
                                    <span class="badge badge-inline badge-warning py-1 fs-12">{{ translate('Goods Received in China Warehouse') }}</span>
                                @elseif ($order->status == 'shipment_done')
                                    <span class="badge badge-inline badge-secondary py-1 fs-12">{{ translate('Shipment Done') }}</span>
                                @elseif ($order->status == 'goods_received_in_bangladesh')
                                    <span class="badge badge-inline badge-soft-primary py-1 fs-12">{{ translate('Goods Received in Bangladesh') }}</span>
                                 @elseif ($order->status == 'ready_to_deliver')
                                <span class="badge badge-inline badge-info py-1 fs-12">{{ translate('Ready to Deliver') }}</span>
                                @elseif ($order->status == 'delivered')
                                <span class="badge badge-inline badge-success py-1 fs-12">{{ translate('Delivered') }}</span>
                                
                                @else
                                    <span class="badge badge-inline badge-danger py-1 fs-12">{{ translate('Cancelled') }}</span>
                                @endif
                            </td>
                                <!-- Options -->
                                <td class="text-right pr-0">
                                    <!-- Re-order -->
                                    {{-- <a class="btn-soft-white rounded-3 btn-sm mr-1" href="{{ route('re_order', encrypt($order->id)) }}">
                                        {{ translate('Reorder') }}
                                    </a> --}}
                                    
                                    <!-- Details -->
                                    <a href="{{route('purchase_history.details', encrypt($order->id))}}" class="btn btn-soft-info btn-icon btn-circle btn-sm hov-svg-white mt-2 mt-sm-0" title="{{ translate('Order Details') }}">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="12" height="10" viewBox="0 0 12 10">
                                            <g id="Group_24807" data-name="Group 24807" transform="translate(-1339 -422)">
                                                <rect id="Rectangle_18658" data-name="Rectangle 18658" width="12" height="1" transform="translate(1339 422)" fill="#3490f3"/>
                                                <rect id="Rectangle_18659" data-name="Rectangle 18659" width="12" height="1" transform="translate(1339 425)" fill="#3490f3"/>
                                                <rect id="Rectangle_18660" data-name="Rectangle 18660" width="12" height="1" transform="translate(1339 428)" fill="#3490f3"/>
                                                <rect id="Rectangle_18661" data-name="Rectangle 18661" width="12" height="1" transform="translate(1339 431)" fill="#3490f3"/>
                                            </g>
                                        </svg>
                                    </a>
                                </td>
                            
        </tr>
    @endif
@endforeach

                    @elseif($filter == 'delivered')
                    @foreach ($orders as $key => $order)
                        @if (count($order->orderDetails) > 0)
                            <tr>
                                <!-- Code -->
                                <td class="pl-0">
                                    <a href="{{route('purchase_history.details', encrypt($order->id))}}">{{ $order->code }}</a>
                                </td>
                              
                                <!-- Amount -->
                                <td class="fw-700">
                                    {{ single_price($order->grand_total) }}
                                </td>
                                  <!-- Date -->
                                <td class="text-secondary">{{ date('d-m-Y', $order->date) }}</td>
                                <!-- Payment Status -->
                                <td>
                                    @if ($order->payment_status == 'paid')
                                        <span class="badge badge-inline badge-success py-1 fs-12">{{translate('Paid')}}</span>
                                    @else
                                        <span class="badge badge-inline badge-danger py-1 fs-12">{{translate('Unpaid')}}</span>
                                    @endif
                                </td>
                                  <td>
                                 @if ($order->status == 'order_placement')
                                    <span class="badge badge-inline badge-primary py-1 fs-12">{{ translate('Order Placement') }}</span>
                                @elseif ($order->status == 'buying_goods')
                                    <span class="badge badge-inline badge-info py-1 fs-12">{{ translate('Buying Goods') }}</span>
                                @elseif ($order->status == 'goods_received_in_china_warehouse')
                                    <span class="badge badge-inline badge-warning py-1 fs-12">{{ translate('Goods Received in China Warehouse') }}</span>
                                @elseif ($order->status == 'shipment_done')
                                    <span class="badge badge-inline badge-secondary py-1 fs-12">{{ translate('Shipment Done') }}</span>
                                @elseif ($order->status == 'goods_received_in_bangladesh')
                                    <span class="badge badge-inline badge-soft-primary py-1 fs-12">{{ translate('Goods Received in Bangladesh') }}</span>
                                 @elseif ($order->status == 'ready_to_deliver')
                                <span class="badge badge-inline badge-info py-1 fs-12">{{ translate('Ready to Deliver') }}</span>
                                @elseif ($order->status == 'delivered')
                                <span class="badge badge-inline badge-success py-1 fs-12">{{ translate('Delivered') }}</span>
                                
                                @else
                                    <span class="badge badge-inline badge-danger py-1 fs-12">{{ translate('Cancelled') }}</span>
                                @endif
                            </td>
                                <!-- Options -->
                                <td class="text-right pr-0">
                                    <!-- Re-order -->
                                    {{-- <a class="btn-soft-white rounded-3 btn-sm mr-1" href="{{ route('re_order', encrypt($order->id)) }}">
                                        {{ translate('Reorder') }}
                                    </a> --}}
                                    
                                    <!-- Details -->
                                    <a href="{{route('purchase_history.details', encrypt($order->id))}}" class="btn btn-soft-info btn-icon btn-circle btn-sm hov-svg-white mt-2 mt-sm-0" title="{{ translate('Order Details') }}">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="12" height="10" viewBox="0 0 12 10">
                                            <g id="Group_24807" data-name="Group 24807" transform="translate(-1339 -422)">
                                                <rect id="Rectangle_18658" data-name="Rectangle 18658" width="12" height="1" transform="translate(1339 422)" fill="#3490f3"/>
                                                <rect id="Rectangle_18659" data-name="Rectangle 18659" width="12" height="1" transform="translate(1339 425)" fill="#3490f3"/>
                                                <rect id="Rectangle_18660" data-name="Rectangle 18660" width="12" height="1" transform="translate(1339 428)" fill="#3490f3"/>
                                                <rect id="Rectangle_18661" data-name="Rectangle 18661" width="12" height="1" transform="translate(1339 431)" fill="#3490f3"/>
                                            </g>
                                        </svg>
                                    </a>
                                    <!-- Invoice -->
                                    {{-- <a class="btn btn-soft-secondary-base btn-icon btn-circle btn-sm hov-svg-white mt-2 mt-sm-0" href="{{ route('invoice.download', $order->id) }}" title="{{ translate('Download Invoice') }}">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12.001" viewBox="0 0 12 12.001">
                                            <g id="Group_24807" data-name="Group 24807" transform="translate(-1341 -424.999)">
                                              <path id="Union_17" data-name="Union 17" d="M13936.389,851.5l.707-.707,2.355,2.355V846h1v7.1l2.306-2.306.707.707-3.538,3.538Z" transform="translate(-12592.95 -421)" fill="#f3af3d"/>
                                              <rect id="Rectangle_18661" data-name="Rectangle 18661" width="12" height="1" transform="translate(1341 436)" fill="#f3af3d"/>
                                            </g>
                                        </svg>
                                    </a> --}}
                                </td>
                            </tr>
                        @endif
                    @endforeach
                    @elseif($filter == 'cancelled')
                    @foreach ($orders as $key => $order)
                        @if (count($order->orderDetails) > 0)
                            <tr>
                                <!-- Code -->
                                <td class="pl-0">
                                    <a href="{{route('purchase_history.details', encrypt($order->id))}}">{{ $order->code }}</a>
                                </td>
                               
                                <!-- Amount -->
                                <td class="fw-700">
                                    {{ single_price($order->grand_total) }}
                                </td>
                                 <!-- Date -->
                                <td class="text-secondary">{{ date('d-m-Y', $order->date) }}</td>
                                <!-- Payment Status -->
                                <td>
                                    @if ($order->payment_status == 'paid')
                                        <span class="badge badge-inline badge-success py-1 fs-12">{{translate('Paid')}}</span>
                                    @else
                                        <span class="badge badge-inline badge-danger py-1 fs-12">{{translate('Unpaid')}}</span>
                                    @endif
                                </td>
                                  <td>
                                 @if ($order->status == 'order_placement')
                                    <span class="badge badge-inline badge-primary py-1 fs-12">{{ translate('Order Placement') }}</span>
                                @elseif ($order->status == 'buying_goods')
                                    <span class="badge badge-inline badge-info py-1 fs-12">{{ translate('Buying Goods') }}</span>
                                @elseif ($order->status == 'goods_received_in_china_warehouse')
                                    <span class="badge badge-inline badge-warning py-1 fs-12">{{ translate('Goods Received in China Warehouse') }}</span>
                                @elseif ($order->status == 'shipment_done')
                                    <span class="badge badge-inline badge-secondary py-1 fs-12">{{ translate('Shipment Done') }}</span>
                                @elseif ($order->status == 'goods_received_in_bangladesh')
                                    <span class="badge badge-inline badge-soft-primary py-1 fs-12">{{ translate('Goods Received in Bangladesh') }}</span>
                                 @elseif ($order->status == 'ready_to_deliver')
                                <span class="badge badge-inline badge-info py-1 fs-12">{{ translate('Ready to Deliver') }}</span>
                                @elseif ($order->status == 'delivered')
                                <span class="badge badge-inline badge-success py-1 fs-12">{{ translate('Delivered') }}</span>
                                
                                @else
                                    <span class="badge badge-inline badge-danger py-1 fs-12">{{ translate('Cancelled') }}</span>
                                @endif
                            </td>
                                <!-- Options -->
                                <td class="text-right pr-0">
                                    <!-- Re-order -->
                                    {{-- <a class="btn-soft-white rounded-3 btn-sm mr-1" href="{{ route('re_order', encrypt($order->id)) }}">
                                        {{ translate('Reorder') }}
                                    </a> --}}
                               
                                    <!-- Details -->
                                    <a href="{{route('purchase_history.details', encrypt($order->id))}}" class="btn btn-soft-info btn-icon btn-circle btn-sm hov-svg-white mt-2 mt-sm-0" title="{{ translate('Order Details') }}">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="12" height="10" viewBox="0 0 12 10">
                                            <g id="Group_24807" data-name="Group 24807" transform="translate(-1339 -422)">
                                                <rect id="Rectangle_18658" data-name="Rectangle 18658" width="12" height="1" transform="translate(1339 422)" fill="#3490f3"/>
                                                <rect id="Rectangle_18659" data-name="Rectangle 18659" width="12" height="1" transform="translate(1339 425)" fill="#3490f3"/>
                                                <rect id="Rectangle_18660" data-name="Rectangle 18660" width="12" height="1" transform="translate(1339 428)" fill="#3490f3"/>
                                                <rect id="Rectangle_18661" data-name="Rectangle 18661" width="12" height="1" transform="translate(1339 431)" fill="#3490f3"/>
                                            </g>
                                        </svg>
                                    </a>
                                    <!-- Invoice -->
                                    {{-- <a class="btn btn-soft-secondary-base btn-icon btn-circle btn-sm hov-svg-white mt-2 mt-sm-0" href="{{ route('invoice.download', $order->id) }}" title="{{ translate('Download Invoice') }}">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12.001" viewBox="0 0 12 12.001">
                                            <g id="Group_24807" data-name="Group 24807" transform="translate(-1341 -424.999)">
                                              <path id="Union_17" data-name="Union 17" d="M13936.389,851.5l.707-.707,2.355,2.355V846h1v7.1l2.306-2.306.707.707-3.538,3.538Z" transform="translate(-12592.95 -421)" fill="#f3af3d"/>
                                              <rect id="Rectangle_18661" data-name="Rectangle 18661" width="12" height="1" transform="translate(1341 436)" fill="#f3af3d"/>
                                            </g>
                                        </svg>
                                    </a> --}}
                                </td>
                            </tr>
                        @endif
                    @endforeach
                    @endif


                </tbody>
            </table>
            <!-- Pagination -->
            <div class="aiz-pagination mt-2">
                {{ $orders->links() }}
            </div>
        </div>
    </div>
@endsection

@section('modal')
    <!-- Delete modal -->
    @include('modals.delete_modal')

@endsection
@section('script')
<script>
function filterOrders(status) {
    // Get all order rows
    const rows = document.querySelectorAll('.order-row');

    // Loop through rows and toggle visibility based on the status
    rows.forEach(row => {
        if (row.dataset.status === status) {
            row.style.display = ''; // Show the row
        } else {
            row.style.display = 'none'; // Hide the row
        }
    });

    // Update the active class for buttons
    const buttons = document.querySelectorAll('.filter-tab');
    buttons.forEach(button => {
        button.classList.remove('active'); // Remove the active class from all buttons
    });

    // Add the active class to the clicked button
    const activeButton = document.querySelector(`.filter-tab[onclick="filterOrders('${status}')"]`);
    if (activeButton) {
        activeButton.classList.add('active');
    }
}

// Set a default filter on page load
document.addEventListener('DOMContentLoaded', () => {
    const status = 'bangladesh'; // Default filter
    filterOrders(status);
});


</script>

@endsection
