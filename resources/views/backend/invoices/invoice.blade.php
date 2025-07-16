<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{  translate('INVOICE') }}</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta charset="UTF-8">
	<style media="all">
        @page {
			margin: 0;
			padding:0;
		}
		body{
			font-size: 0.875rem;
            font-family: '<?php echo  $font_family ?>';
            font-weight: normal;
            direction: <?php echo  $direction ?>;
            text-align: <?php echo  $text_align ?>;
			padding:0;
			margin:0; 
		}
		.gry-color *,
		.gry-color{
			color:#000;
		}
		table{
			width: 100%;
		}
		table th{
			font-weight: normal;
		}
		table.padding th{
			padding: .25rem .7rem;
		}
		table.padding td{
			padding: .25rem .7rem;
		}
		table.sm-padding td{
			padding: .1rem .7rem;
		}
		.border-bottom td,
		.border-bottom th{
			border-bottom:1px solid #eceff4;
		}
		.text-left{
			text-align:<?php echo  $text_align ?>;
		}
		.text-right{
			text-align:<?php echo  $not_text_align ?>;
		}
	</style>
</head>
<body>
	<div>

		@php
			$logo = get_setting('header_logo');
		@endphp

		<div style="background: #eceff4;padding: 1rem;">
			<table>
				<tr>
					<td>
						@if($logo != null)
							<img src="{{ uploaded_asset($logo) }}" height="30" style="display:inline-block;">
						@else
							<img src="{{ static_asset('assets/img/logo.png') }}" height="30" style="display:inline-block;">
						@endif
					</td>
					<td style="font-size: 1.5rem;" class="text-right strong">{{  translate('INVOICE') }}</td>
				</tr>
			</table>
			<table>
				<tr>
					<td style="font-size: 1rem;" class="strong">{{ get_setting('site_name') }}</td>
					<td class="text-right"></td>
				</tr>
				<tr>
					<td class="gry-color small">{{ get_setting('contact_address') }}</td>
					<td class="text-right"></td>
				</tr>
				<tr>
					<td class="gry-color small">{{  translate('Email') }}: {{ get_setting('contact_email') }}</td>
					<td class="text-right small"><span class="gry-color small">{{  translate('Order ID') }}:</span> <span class="strong">{{ $order->code }}</span></td>
				</tr>
				<tr>
					<td class="gry-color small">{{  translate('Phone') }}: {{ get_setting('contact_phone') }}</td>
					<td class="text-right small"><span class="gry-color small">{{  translate('Order Date') }}:</span> <span class=" strong">{{ date('d-m-Y', $order->date) }}</span></td>
				</tr>
				<tr>
					<td class="gry-color small"></td>
					<td class="text-right small">
                        <span class="gry-color small">
                            {{  translate('Payment method') }}:
                        </span> 
                        <span class="strong">
                            {{ translate(ucfirst(str_replace('_', ' ', $order->payment_type))) }}
                        </span>
                    </td>
				</tr>
			</table>

		</div>

		<div style="padding: 1rem;padding-bottom: 0">
            <table>
				@php
					$shipping_address = json_decode($order->shipping_address);
				@endphp
				<tr><td class="strong small gry-color">{{ translate('Bill to') }}:</td></tr>
				<tr><td class="strong">{{ $shipping_address->name }}</td></tr>
				<tr><td class="gry-color small">{{ $shipping_address->address }}, {{ $shipping_address->city }},  @if(isset(json_decode($order->shipping_address)->state)) {{ json_decode($order->shipping_address)->state }} - @endif {{ $shipping_address->postal_code }}, {{ $shipping_address->country }}</td></tr>
				<tr><td class="gry-color small">{{ translate('Email') }}: {{ $shipping_address->email }}</td></tr>
				<tr><td class="gry-color small">{{ translate('Phone') }}: {{ $shipping_address->phone }}</td></tr>
			</table>
		</div>

	    <div style="padding: 1rem;">
			<table class="padding text-left small border-bottom">
				<thead>
	                <tr class="gry-color" style="background: #eceff4;">
	                    <th width="35%" class="text-left">{{ translate('Product Name') }}</th>
	                    <th width="10%" class="text-left">{{ translate('Qty') }}</th>
	                     @if($order->order_type == 'ship_for_me')
                                        <th width="10%" class="text-left">
                                        {{ translate('Weight') }}
                                    </th>
                                @endif
	                    <th width="15%" class="text-right">{{ translate('Unit Price') }}</th>
	                    <th width="15%" class="text-right">{{ translate('Total') }}</th>
	                </tr>
				</thead>
				<tbody class="strong">
	                @php $subTotal = 0; @endphp
					@foreach ($order->orderDetails as $key => $orderDetail)
						<tr>
							<td class="border-top-0 border-bottom">
								{{ ucfirst($orderDetail->product_title) }}
								<br>
								@if (!empty($orderDetail->variation))
									<ul class="list-unstyled">
										@foreach ($orderDetail->variation as $attribute)
											<li><strong>{{ $attribute['name'] }}:</strong> {{ $attribute['value'] }}</li>
										@endforeach
									</ul>
								@endif
							</td>
							
							<td class="border-top-0 border-bottom">{{ $orderDetail->quantity }}</td>
							 @if($order->order_type == 'ship_for_me')
                                         <td class="border-top-0 border-bottom pr-0 text-right">{{ single_price($orderDetail->weight) }}</td>
                                  @endif
							<td class="border-top-0 border-bottom pr-0 text-right">{{ single_price($orderDetail->price) }}</td>
							<td class="border-top-0 border-bottom pr-0 text-right">
							    @if($orderDetail->product_id == 'ship_for_me')
                                        {{ single_price($orderDetail->price * $orderDetail->weight) }}
                                    @else
                                        {{ single_price($orderDetail->price * $orderDetail->quantity) }}
                                    @endif</td>

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
	        <table class="text-right sm-padding small strong">
	        	<thead>
	        		<tr>
	        			<th width="60%"></th>
	        			<th width="40%"></th>
	        		</tr>
	        	</thead>
		        <tbody>
			        <tr>
			            <td class="text-left">
                            @php
                                $removedXML = '<?xml version="1.0" encoding="UTF-8"?>';
                            @endphp
                            {!! str_replace($removedXML,"", QrCode::size(100)->generate($order->code)) !!}
			            </td>
			            <td>
					        <table class="text-right sm-padding small strong">
						        <tbody>
							        <tr>
							            <th class="gry-color text-left">{{ translate('Sub Total') }}</th>
							            <td class="currency">{{ single_price($subTotal) }}</td>
							        </tr>
				                    <tr class="border-bottom">
							            <th class="gry-color text-left">{{ translate('Coupon Discount') }}</th>
							            <td class="currency">{{ single_price($order->coupon_discount) }}</td>
							        </tr>
							        <tr>
							            <th class="gry-color text-left">{{ translate('Products Total') }}</th>
							            <td class="currency">{{ single_price($order->grand_total) }}</td>
							        </tr>
							         @if($order->payment_status=='paid')
							         <tr>
                                        <th class="gry-color text-left">
                                            {{ translate('Paid') }} :
                                        </th>
                                        <td >
                                           - {{ single_price($order->grand_total*.5) }}
                                        </td>
                                    </tr>
                                    
                                      <tr>
							            <th class="gry-color text-left">{{ translate('Due') }}</th>
							            <td class="currency">{{ single_price($order->grand_total*.5) }}</td>
							        </tr>
							        @endif
						        </tbody>
						    </table>
			            </td>
			        </tr>
		        </tbody>
		    </table>
		</div>

@if(!empty($orderCost) && $order->payment_status=='paid' )
    @php

        $shippingCostCalc = $orderCost->product_weight * $orderCost->shipping_cost_per_kg;

        $totalCost        = $orderCost->china_courier_charge_tk + $shippingCostCalc + ($order->grand_total*.5);
    @endphp

    <div class="card p-3 shadow-sm order-2 order-md-1 w-100 w-md-auto" style="flex: 1; max-width: 85%;">
        <div class="card-header " style="padding: 1rem;">
            <h5>Shiping Cost Details </h5>
        </div>

        <div class="card-body" style="padding: 1rem;">
            <table class="padding text-left small border-bottom w-100">
                <thead>
                    <tr class="gry-color" style="background: #eceff4;">
                        <th width="55%" class="text-left">{{ translate('Details') }}</th>
                        <th width="45%" class="text-right">{{ translate('Value') }}</th>
                    </tr>
                </thead>

                <tbody class="strong">
                    <tr>
                        <td class="border-top-0 border-bottom">{{ translate('China Courier Charge') }}</td>
                        <td class="border-top-0 border-bottom text-right">
                            {{ number_format($orderCost->china_courier_charge_tk, 2) }}
                        </td>
                    </tr>

                    <tr>
                        <td class="border-bottom">
                            {{ translate('Shipping Cost') }}
                            ({{ number_format($orderCost->product_weight, 2) }}
                            × {{ number_format($orderCost->shipping_cost_per_kg, 2) }})
                        </td>
                        <td class="border-bottom text-right">
                            {{ number_format($shippingCostCalc, 2) }}
                        </td>
                    </tr>

                    <tr>
                        <td class="border-bottom">{{ translate('Product Due') }}</td>
                        <td class="border-bottom text-right">
                             {{ single_price($order->grand_total*.5) }}
                        </td>
                    </tr>
                     
                </tbody>

                <tfoot>
                    <tr>
                        <td class="text-right">
                            <strong>{{ translate('Grand Total:') }}</strong>
                        </td>
                        <td class="text-right text-success">
                            <strong>{{ number_format($totalCost, 2) }}</strong>
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
@endif
@if(!empty($orderCost) && $order->payment_status=='fullpaid' )
    @php

        $shippingCostCalc = $orderCost->product_weight * $orderCost->shipping_cost_per_kg;

        $totalCost        = $orderCost->china_courier_charge_tk + $shippingCostCalc ;
    @endphp

    <div class="card p-3 shadow-sm order-2 order-md-1 w-100 w-md-auto" style="flex: 1; max-width: 85%;">
        <div class="card-header " style="padding: 1rem;">
            <h5>Shiping Cost Details </h5>
        </div>

        <div class="card-body" style="padding: 1rem;">
            <table class="padding text-left small border-bottom w-100">
                <thead>
                    <tr class="gry-color" style="background: #eceff4;">
                        <th width="55%" class="text-left">{{ translate('Details') }}</th>
                        <th width="45%" class="text-right">{{ translate('Value') }}</th>
                    </tr>
                </thead>

                <tbody class="strong">
                    <tr>
                        <td class="border-top-0 border-bottom">{{ translate('China Courier Charge') }}</td>
                        <td class="border-top-0 border-bottom text-right">
                            {{ number_format($orderCost->china_courier_charge_tk, 2) }}
                        </td>
                    </tr>

                    <tr>
                        <td class="border-bottom">
                            {{ translate('Shipping Cost') }}
                            ({{ number_format($orderCost->product_weight, 2) }}
                            × {{ number_format($orderCost->shipping_cost_per_kg, 2) }})
                        </td>
                        <td class="border-bottom text-right">
                            {{ number_format($shippingCostCalc, 2) }}
                        </td>
                    </tr>

                    
                     
                </tbody>

                <tfoot>
                    <tr>
                        <td class="text-right">
                            <strong>{{ translate('Grand Total:') }}</strong>
                        </td>
                        <td class="text-right text-success">
                            <strong>{{ number_format($totalCost, 2) }}</strong>
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
@endif



</body>
</html>

