@extends('frontend.layouts.user_panel')

@section('panel_content')
    <!-- Order id -->
    <div class="aiz-titlebar mb-4">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h1 class="fs-20 fw-700 text-dark">{{ translate('Order id') }}: {{ $order->code }}</h1>
            </div>
        </div>
    </div>

    <!-- Order Summary -->
    <div class="card rounded-0 shadow-none border mb-4">
        <div class="card-header border-bottom-0">
            <h5 class="fs-16 fw-700 text-dark mb-0">{{ translate('Order Summary') }}</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-lg-6">
                    <table class="table-borderless table">
                        <tr>
                            <td class="w-50 fw-600 py-1">{{ translate('Order Code') }}:</td>
                            <td class="py-1">{{ $order->code }}</td>
                        </tr>
                        <tr>
                            <td class="w-50 fw-600 py-1">{{ translate('Customer') }}:</td>
                            <td class="py-1">
                                {{ json_decode($order->shipping_address)->name ?? $order->user?->name ?? 'N/A' }}
                            </td>
                        </tr>
                        <tr>
                            <td class="w-50 fw-600 py-1">{{ translate('Phone') }}:</td>
                            <td class="py-1" style="word-wrap: break-word; word-break: break-all;">
                                {{ json_decode($order->shipping_address)->phone ?? $order->user?->phone ?? 'N/A' }}
                            </td>
                        </tr>
                        <tr>
                            <td class="w-50 fw-600 py-1">{{ translate('Email') }}:</td>
                            <td class="py-1" style="word-wrap: break-word; word-break: break-all;">
                                {{ json_decode($order->shipping_address)->email ?? $order->user?->email ?? 'N/A' }}
                            </td>
                        </tr>

                        <tr>
                            <td class="w-50 fw-600 py-1">{{ translate('Shipping address') }}:</td>
                            <td class="py-1">
                            {{ json_decode($order->shipping_address)->address ?? 'N/A' }},
                            {{ json_decode($order->shipping_address)->city ?? 'N/A' }},
                            @if(isset(json_decode($order->shipping_address)->state)) 
                                {{ json_decode($order->shipping_address)->state }} - 
                            @else 
                                N/A - 
                            @endif
                            {{ json_decode($order->shipping_address)->postal_code ?? 'N/A' }},
                            {{ json_decode($order->shipping_address)->country ?? 'N/A' }}
                        </td>

                        </tr>
                    </table>
                </div>
                <div class="col-lg-6">
                    <table class="table-borderless table">
                        <tr>
                            <td class="w-50 fw-600 py-1">{{ translate('Order date') }}:</td>
                            <td class="py-1">{{ date('d-m-Y H:i A', $order->date) }}</td>
                        </tr>
                        <tr>
                            <td class="w-50 fw-600 py-1">{{ translate('Order status') }}:</td>
                           <td class="py-1">
                             <span class="badge badge-inline badge-warning">
                                  {{translate(ucfirst(str_replace('_', ' ', $order->status)))}}
                             </span>
                            </td>

                        </tr>
                        <tr>
                            <td class="w-50 fw-600 py-1">{{ translate('Total order amount') }}:</td>
                            <td class="py-1">{{ single_price($order->grand_total) }}
                            </td>
                        </tr>
                         <tr>
                            <td class="w-50 fw-600 py-1">{{ translate('Payment Status') }}:</td>
                            <td class="py-1"> @if ($order->payment_status == 'paid')
                                <span class="badge badge-inline badge-success">{{translate('Partially Paid')}}</span>
                            @elseif($order->payment_status == 'fullpaid')
                                <span class="badge badge-inline badge-success">{{translate('Paid')}}</span>
                            @else
                                <span class="badge badge-inline badge-primary">{{translate('Unpaid')}}</span>
                            @endif
                            </td>
                        </tr>
                        <tr>
                            <td class="w-50 fw-600 py-1">{{ translate('Payment method') }}:</td>
                            <td class="py-1">{{ translate(ucfirst(str_replace('_', ' ', $order->payment_type))) }}</td>
                        </tr>
                        <tr>
                            <td class="w-50 fw-600 py-1">{{ translate('Additional Info') }}</td>
                            <td class="py-1">{{ $order->additional_info }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Order Details -->
    <div class="row">
        <div class="col-md-9">
            <div class="card rounded-0 shadow-none border mt-2 mb-4">
                <div class="card-header border-bottom-0">
                    <h5 class="fs-16 fw-700 text-dark mb-0">{{ translate('Order Details') }}</h5>
                </div>
                <div class="card-body table-responsive">
                    <table class="aiz-table table">
                        <thead class="text-gray fs-12">
                            <tr>
                                <th class="pl-0">#</th>
                                <th>{{ translate('Product') }}</th>
                                <th data-breakpoints="md">{{ translate('Attribute') }}</th>
                                <th data-breakpoints="md">{{ translate('Quantity') }}</th>
                                 @if($order->order_type == 'ship_for_me')
                                        <th data-breakpoints="md">
                                        {{ translate('Weight') }}
                                    </th>
                                @endif
                                <th data-breakpoints="md">{{ translate('Price') }}</th>
                                <th data-breakpoints="md">{{ translate('Total') }}</th>
                            </tr>
                        </thead>
                        <tbody class="fs-14">
                             @php
                                    $subTotal = 0;
                                @endphp
                            @foreach ($order->orderDetails as $key => $orderDetail)
                              
                               
                                <tr>
                                    <td class="border-top-0 border-bottom pl-0">{{ $key+1 }}</td>
                                    <td class="border-top-0 border-bottom">
                                        <a href="{{ route('product', $orderDetail->product_id)}}" target="_blank" class="text-reset">
                                            {{ ucfirst($orderDetail->product_title) }}
                                        </a>
                                    </td>
                                    <td class="border-top-0 border-bottom">
                                        @if (!empty($orderDetail->variation))
                                            <ul class="list-unstyled">
                                                @foreach ($orderDetail->variation as $attribute)
                                                    <li><strong>{{ $attribute['name'] }}:</strong> {{ $attribute['value'] }}</li>
                                                @endforeach
                                            </ul>
                                        @endif
                                    </td>
                                    <td class="border-top-0 border-bottom">
                                        {{ $orderDetail->quantity }}
                                    </td>
                                     @if($order->order_type == 'ship_for_me')
                                         <td class="border-top-0 border-bottom pr-0">{{ single_price($orderDetail->weight) }}</td>
                                  @endif
                                    <td class="border-top-0 border-bottom pr-0">{{ single_price($orderDetail->price) }}</td>
                                    <td class="border-top-0 border-bottom pr-0">
                                           @if($orderDetail->product_id == 'ship_for_me')
                                        {{ single_price($orderDetail->price * $orderDetail->weight) }}
                                    @else
                                        {{ single_price($orderDetail->price * $orderDetail->quantity) }}
                                    @endif
                                    </td>
                                </tr>
                              
                                 @php
                                    if ($orderDetail->product_id == 'ship_for_me') {
                                        $subTotal += $orderDetail->price * $orderDetail->weight;
                                    } else {
                                        $subTotal += $orderDetail->price * $orderDetail->quantity;
                                    }
                                 @endphp
                                
                            @endforeach
                          
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Order Ammount -->
        <div class="col-md-3">
            <div class="card rounded-0 shadow-none border mt-2">
                <div class="card-header border-bottom-0">
                    <b class="fs-16 fw-700 text-dark">{{ translate('Order Ammount') }}</b>
                </div>
                <div class="card-body p-0">
                    <table class="table-borderless table">
                        <tbody>
                            <tr>
                                <td class="w-50 fw-600 py-1">{{ translate('Subtotal') }}</td>
                                <td class="text-right py-1">
                                    <span class="strong-600">{{ single_price($subTotal) }}</span>
                                </td>
                            </tr>
                            {{-- <tr>
                                <td class="w-50 fw-600 py-1">{{ translate('Shipping') }}</td>
                                <td class="text-right">
                                    <span class="text-italic">{{ single_price($order->orderDetails->sum('shipping_cost')) }}</span>
                                </td>
                            </tr>
                            <tr>
                                <td class="w-50 fw-600 py-1">{{ translate('Tax') }}</td>
                                <td class="text-right">
                                    <span class="text-italic">{{ single_price($order->orderDetails->sum('tax')) }}</span>
                                </td>
                            </tr>--}}
                            <tr>
                                <td class="w-50 fw-600 py-1">{{ translate('Coupon') }}</td>
                                <td class="text-right">
                                    <span class="text-italic">{{ single_price($order->coupon_discount) }}</span>
                                </td>
                            </tr> 
                            <tr>
                                <td class="w-50 fw-600 py-1">{{ translate('Total') }}</td>
                                <td class="text-right py-1">
                                    <strong>{{ single_price($order->grand_total) }}</strong>
                                </td>
                            </tr>
                             @if ($order->payment_status == 'paid')
                             <tr>
                                <td class="w-50 fw-600 py-1">{{ translate('Paid') }}</td>
                                <td class="text-right py-1">
                                     <strong>{{ single_price($order->grand_total-$order->grand_total * .5) }}</strong>
                                </td>
                            </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
             <div class="no-print text-right">
                <a href="{{ route('invoice.download', $order->id) }}" type="button" class="btn btn-icon btn-light"><i
                        class="las la-print"></i></a>
            </div>
            @if ($order->payment_status == 'unpaid')
                <button onclick="show_make_payment_modal({{ $order->combined_order_id }})"
                    class="btn btn-block btn-primary">{{ translate('Make Payment') }}</button>
            @endif
        </div>
    </div>
      @php
                        // get existing record if it exists, otherwise an empty model
                        $orderCost = \App\Models\OrderCost::where(['order_id' => $order->id])->first();
                        @endphp
                    @if($orderCost)
                     @php
                                $totalCost = ($orderCost->shipping_cost_per_kg * $orderCost->product_weight) + $orderCost->china_courier_charge_tk;
                            @endphp
                       <div class="clearfix float-right order-1 order-md-2 w-100 w-md-auto mt-3 mt-md-0">
                        <table class="table">
                            <tbody>
                                
                                        
                                    
                                        <tr>
                                            <td>
                                                <strong class="text-muted">{{ translate('Product Unpaid') }} :</strong>
                                            </td>
                                            <td >
                                                {{ single_price($order->grand_total-$order->grand_total * .5) }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <strong class="text-muted">{{ translate('Shiping Charge') }} :</strong>
                                            </td>
                                            <td >
                                                {{ single_price($totalCost) }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <strong class="text-muted">{{ translate('TOTAL') }} :</strong>
                                            </td>
                                            <td class="text-muted h5">
                                                {{ single_price(($order->grand_total-$order->grand_total * .5)+$totalCost) }}
                                            </td>
                                        </tr>
                                        
                            </tbody>
                        </table>

                        
                    </div>
                    <div class="card p-3 shadow-sm order-2 order-md-1 w-100 w-md-auto" style="flex: 1; max-width: 75%;" >
                        <div class="card-header">
                            <h5>Shiping Cost Summary</h5>
                        </div>
                        <div class="card-body">
                            <p><strong>China Courier Charge:</strong> {{ number_format($orderCost->china_courier_charge_tk, 2) }}</p>
                            <p><strong>Shipping Cost per KG:</strong> {{ number_format($orderCost->shipping_cost_per_kg, 2) }}</p>
                           
                            <p><strong>Product Weight (KG):</strong> {{ number_format($orderCost->product_weight, 2) }}</p>
                             <p><strong>Port:</strong> {{ ucfirst($orderCost->port) }}</p>

                           

                            <hr>
                            <p><strong>Total Cost:</strong> <span class="text-success">{{ number_format($totalCost, 2) }}</span></p>
                        </div>
                    </div>
                    @endif
@endsection
@section('modal')
    <!-- Wallet Recharge Modal -->
    @include('frontend.'.get_setting('homepage_select').'.partials.payment_modal')
    
    <!-- Offline Wallet Recharge Modal -->
    <div class="modal fade" id="offline_wallet_recharge_modal" tabindex="-1" role="dialog"
        aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">{{ translate('Offline Recharge Wallet') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
                </div>
                <div id="offline_wallet_recharge_modal_body"></div>
            </div>
        </div>
    </div>

    <!-- Product Review Modal -->
    <div class="modal fade" id="product-review-modal">
        <div class="modal-dialog">
            <div class="modal-content" id="product-review-modal-content">

            </div>
        </div>
    </div>

    <!-- Payment Modal -->
    <div class="modal fade" id="payment_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div id="payment_modal_body">

                </div>
            </div>
        </div>
    </div>
@endsection


@section('script')
    <script type="text/javascript">
        function show_make_payment_modal(order_id) {
                $('#payment_modal').modal('show');
                 $('#order_id_input').val(order_id);
          
        }

        function product_review(product_id) {
            $.post('{{ route('product_review_modal') }}', {
                _token: '{{ @csrf_token() }}',
                product_id: product_id
            }, function(data) {
                $('#product-review-modal-content').html(data);
                $('#product-review-modal').modal('show', {
                    backdrop: 'static'
                });
                AIZ.extra.inputRating();
            });
        }
    </script>
     <script type="text/javascript">
        function show_wallet_modal() {
            $('#wallet_modal').modal('show');
        }

        function show_make_wallet_recharge_modal() {
            $.post('{{ route('offline_wallet_recharge_modal') }}', {
                _token: '{{ csrf_token() }}'
            }, function(data) {
                $('#offline_wallet_recharge_modal_body').html(data);
                $('#offline_wallet_recharge_modal').modal('show');
            });
        }
    </script>
@endsection
