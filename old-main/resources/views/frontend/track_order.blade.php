@extends('frontend.layouts.app')

@section('content')
    <section class="pt-4 mb-4">
        <div class="container text-center">
            <div class="row">
                <div class="col-lg-6 text-center text-lg-left">
                    <h1 class="fw-700 fs-20 fs-md-24 text-dark">{{ translate('Track Order') }}</h1>
                </div>
                <div class="col-lg-6">
                    <ul class="breadcrumb bg-transparent p-0 justify-content-center justify-content-lg-end">
                        <li class="breadcrumb-item has-transition opacity-50 hov-opacity-100">
                            <a class="text-reset" href="{{ route('home') }}">{{ translate('Home') }}</a>
                        </li>
                        <li class="text-dark fw-600 breadcrumb-item">
                            "{{ translate('Track Order') }}"
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </section>
    <section class="mb-5">
        <div class="container text-left">
            <div class="row">
                <div class="col-xxl-5 col-xl-6 col-lg-8 mx-auto">
                    <form class="" action="{{ route('orders.track') }}" method="GET" enctype="multipart/form-data">
                        <div class="bg-white border rounded-0">
                            <div class="fs-15 fw-600 p-3 border-bottom text-center">
                                {{ translate('Check Your Order Status')}}
                            </div>
                            <div class="form-box-content p-3">
                                <div class="form-group">
                                    <input type="text" class="form-control rounded-0 mb-3" placeholder="{{ translate('Order Code')}}" name="order_code" value="{{$orderCode ?? ''}}" required>
                                </div>
                                <div class="text-right">
                                    <button type="submit" class="btn btn-primary rounded-0 w-150px">{{ translate('Track Order')}}</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            @isset($order)
                <div class="bg-white border rounded-0 mt-5">
                    <div class="fs-15 fw-600 p-3">
                        {{ translate('Order Summary')}}
                    </div>
                    <div class="p-3">
                        <div class="row">
                            <div class="col-lg-6">
                                <table class="table table-borderless">
                                    <tr>
                                        <td class="w-50 fw-600 py-1">{{ translate('Order Code')}}:</td>
                                        <td class="py-1">{{ $order->code }}</td>
                                    </tr>
                                    <tr>
                                        <td class="w-50 fw-600 py-1">{{ translate('Customer')}}:</td>
                                         @if ($order->shipping_address)
                                            @php
                                                $shipping = json_decode($order->shipping_address);
                                            @endphp
                                            @if ($shipping && property_exists($shipping, 'name'))
                                                <td class="py-1">{{ $shipping->name }}</td>
                                            @endif
                                        @endif

                                    </tr>
                                    <tr>
                                        <td class="w-50 fw-600 py-1">{{ translate('Email')}}:</td>
                                      
                                       @if ($order->user && $order->user->email)
                                            <td class="py-1">{{ $order->user->email }}</td>
                                        @endif

                                    </tr>
                                    <tr>
                                        <td class="w-50 fw-600 py-1">{{ translate('Shipping address')}}:</td>
                                       @if ($order->shipping_address)
                                            @php
                                                $shipping = json_decode($order->shipping_address);
                                            @endphp
                                            @if ($shipping && property_exists($shipping, 'address') && property_exists($shipping, 'city') && property_exists($shipping, 'country'))
                                                <td class="py-1">{{ $shipping->address }}, {{ $shipping->city }}, {{ $shipping->country }}</td>
                                            @endif
                                        @endif

                                    </tr>
                                </table>
                                
                            </div>
                            <div class="col-lg-6">
                                <table class="table table-borderless">
                                    <tr>
                                        <td class="w-50 fw-600 py-1">{{ translate('Order date')}}:</td>
                                        <td class="py-1">{{ date('d-m-Y H:i A', $order->date) }}</td>
                                    </tr>
                                    <tr>
                                        <td class="w-50 fw-600 py-1">{{ translate('Total order amount')}}:</td>
                                        <td class="py-1">{{ single_price($order->grand_total) }}</td>
                                    </tr>
                                    <tr>
                                        <td class="w-50 fw-600 py-1">{{ translate('Payment method')}}:</td>
                                        <td class="py-1">{{ translate(ucfirst(str_replace('_', ' ', $order->payment_type))) }}</td>
                                    </tr>
                                    <tr>
                                        <td class="w-50 fw-600 py-1">{{ translate('Status')}}:</td>
                                        <td class="py-1">{{ translate(ucwords(str_replace('_', ' ', $order->status))) }}</td>
                                    </tr>
                                    @if ($order->tracking_code)
                                        <tr>
                                            <td class="w-50 fw-600 py-1">{{ translate('Tracking code')}}:</td>
                                            <td class="py-1">{{ $order->tracking_code }}</td>
                                        </tr>
                                    @endif
                                </table>
                            </div>
                        </div>
                    </div>

                    <div class="text-center p-3">
                        <h2 class="fs-20 fw-700">{{ translate('Shipping Flow Chart') }}</h2>
                    </div>
                   <div class="row pb-5">
                        <div class="col-xl-10 mx-auto">
                            <div class="row gutters-5 sm-gutters-10 d-flex align-items-stretch">
                                <!-- Step 1: Order Placement -->
                                <div class="col-12 col-md-2 text-center {{ in_array($order->status, ['order_placement', 'buying_goods', 'goods_received_in_china_warehouse', 'shipment_done', 'ready_to_deliver', 'goods_received_in_bangladesh']) ? 'done-red' : 'opacity-50' }}">
                                    <div class="border border-bottom-6px p-2 {{ in_array($order->status, ['order_placement', 'buying_goods', 'goods_received_in_china_warehouse', 'shipment_done', 'ready_to_deliver', 'goods_received_in_bangladesh']) ? 'text-danger border-red' : '' }}" style="height: 100%;">
                                        <img src="{{ static_asset('assets/img/Order Placement.png') }}" class="lazyload w-100px h-auto mx-auto has-transition" alt="Order Placement">
                                        <h3 class="fs-14 fw-600">{{ translate('1. Order Placement') }}</h3>
                                    </div>
                                </div>
                                
                                <!-- Repeat similar structure for other steps -->
                                <div class="col-12 col-md-2 text-center {{ in_array($order->status, ['buying_goods', 'goods_received_in_china_warehouse', 'shipment_done', 'ready_to_deliver', 'goods_received_in_bangladesh']) ? 'done-red' : 'opacity-50' }}">
                                    <div class="border border-bottom-6px p-2 {{ in_array($order->status, ['buying_goods', 'goods_received_in_china_warehouse', 'shipment_done', 'ready_to_deliver', 'goods_received_in_bangladesh']) ? 'text-danger border-red' : '' }}" style="height: 100%;">
                                        <img src="{{ static_asset('assets/img/Buying Goods.png') }}" class="lazyload w-100px h-auto mx-auto has-transition" alt="Buying Goods">
                                        <h3 class="fs-14 fw-600">{{ translate('2. Buying Goods') }}</h3>
                                    </div>
                                </div>
                    
                                <!-- Continue for the other steps similarly -->
                                <div class="col-12 col-md-2 text-center {{ in_array($order->status, ['goods_received_in_china_warehouse', 'shipment_done', 'ready_to_deliver', 'goods_received_in_bangladesh']) ? 'done-red' : 'opacity-50' }}">
                                    <div class="border border-bottom-6px p-2 {{ in_array($order->status, ['goods_received_in_china_warehouse', 'shipment_done', 'ready_to_deliver', 'goods_received_in_bangladesh']) ? 'text-danger border-red' : '' }}" style="height: 100%;">
                                        <img src="{{ static_asset('assets/img/Goods Received in China warehouse.png') }}" class="lazyload w-100px h-auto mx-auto has-transition" alt="Product Received in China/QC">
                                        <h3 class="fs-14 fw-600">{{ translate('3. Goods Received in China Warehouse') }}</h3>
                                    </div>
                                </div>
                    
                                <!-- Repeat for steps 4, 5, and 6 -->
                                <div class="col-12 col-md-2 text-center {{ in_array($order->status, ['shipment_done', 'ready_to_deliver', 'goods_received_in_bangladesh']) ? 'done-red' : 'opacity-50' }}">
                                    <div class="border border-bottom-6px p-2 {{ in_array($order->status, ['shipment_done', 'ready_to_deliver', 'goods_received_in_bangladesh']) ? 'text-danger border-red' : '' }}" style="height: 100%;">
                                        <img src="{{ static_asset('assets/img/Shipment Done.png') }}" class="lazyload w-100px h-auto mx-auto has-transition" alt="Shipment Done">
                                        <h3 class="fs-14 fw-600">{{ translate('4. Shipment Done') }}</h3>
                                    </div>
                                </div>
                    
                                <div class="col-12 col-md-2 text-center {{ in_array($order->status, ['goods_received_in_bangladesh', 'ready_to_deliver']) ? 'done-red' : 'opacity-50' }}">
                                    <div class="border border-bottom-6px p-2 {{ in_array($order->status, ['goods_received_in_bangladesh', 'ready_to_deliver']) ? 'text-danger border-red' : '' }}" style="height: 100%;">
                                        <img src="{{ static_asset('assets/img/Goods Received in Bangladesh.png') }}" class="lazyload w-100px h-auto mx-auto has-transition" alt="Goods Received in Bangladesh">
                                        <h3 class="fs-14 fw-600">{{ translate('5. Goods Received in Bangladesh') }}</h3>
                                    </div>
                                </div>
                    
                                <div class="col-12 col-md-2 text-center {{ $order->status == 'ready_to_deliver' ? 'done-red' : 'opacity-50' }}">
                                    <div class="border border-bottom-6px p-2 {{ $order->status == 'ready_to_deliver' ? 'text-danger border-red' : '' }}" style="height: 100%;">
                                        <img src="{{ static_asset('assets/img/Ready to deliver.png') }}" class="lazyload w-100px h-auto mx-auto has-transition" alt="Ready to deliver">
                                        <h3 class="fs-14 fw-600">{{ translate('6. Ready to deliver') }}</h3>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

                <div class="bg-white border rounded-0 mt-4">
                    <div class="p-3">
                        <table class="table table-bordered">
                            <thead>
                                <tr class="bg-trans-dark">
                                    <th width="10%">{{ translate('Photo') }}</th>
                                    <th class="text-uppercase">{{ translate('Description') }}</th>
                                    <th class="min-col text-uppercase text-center">
                                        {{ translate('Qty') }}
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($order->orderDetails as $key => $orderDetail)
                                    <tr>
                                        <td>
                                            <a href="" target="_blank">
                                                <img height="50" src="{{ $orderDetail->image ? $orderDetail->image : asset('public/assets/img/placeholder.jpg') }}">
                                            </a>
                                        </td>
                                        <td>
                                            <strong>
                                                <a href="{{ route('product', $orderDetail->product_id) }}" target="_blank" class="text-muted">
                                                    {{ $orderDetail->product_title }}
                                                </a>
                                            </strong>
                                            
                                            @if (!empty($orderDetail->variation))
                                                <ul class="list-unstyled">
                                                    @foreach ($orderDetail->variation as $attribute)
                                                        <li><strong>{{ $attribute['name'] }}:</strong> {{ $attribute['value'] }}</li>
                                                    @endforeach
                                                </ul>
                                            @endif
                                                
                                        </td>
                                        
                                        <td class="text-center">
                                            {{ $orderDetail->quantity }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                @else
                @if($errorMessage)
                    <div class="border bg-white p-4 mt-4">
                        <div class="text-center p-3">
                            <i class="las la-frown la-3x opacity-60 mb-3"></i>
                            <h3 class="h4 fw-700">{{$errorMessage}}</h3>
                        </div>
                    </div>
                @endif
            @endisset
          
        </div>
    </section>
@endsection
