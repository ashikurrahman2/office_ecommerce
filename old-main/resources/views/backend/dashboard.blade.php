@extends('backend.layouts.app')

@section('content')
    @if(auth()->user()->can('smtp_settings') && env('MAIL_USERNAME') == null && env('MAIL_PASSWORD') == null)
        <div class="">
            <div class="alert alert-info d-flex align-items-center">
                {{translate('Please Configure SMTP Setting to work all email sending functionality')}},
                <a class="alert-link ml-2" href="{{ route('smtp_settings.index') }}">{{ translate('Configure Now') }}</a>
            </div>
        </div>
    @endif
    @can('admin_dashboard')
        <div class="row gutters-10">
            <div class="col">
               <div class="row gutters-10">
                    <div class="col-4">
                        <div class="bg-grad-2 text-white rounded-lg mb-4 overflow-hidden">
                            <div class="px-3 pt-3">
                                <div class="opacity-50">
                                    <span class="fs-16 d-block">{{ translate('Total') }}</span>
                                    {{ translate('Customer') }}
                                </div>
                                <div class="h1 fw-700 mb-3">
                                    {{ \App\Models\User::where('user_type', 'customer')->where('email_verified_at', '!=', null)->count() }}
                                </div>
                            </div>
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320">
                                <path fill="rgba(255,255,255,0.3)" fill-opacity="1" d="M0,128L34.3,112C68.6,96,137,64,206,96C274.3,128,343,224,411,250.7C480,277,549,235,617,213.3C685.7,192,754,192,823,181.3C891.4,171,960,149,1029,117.3C1097.1,85,1166,43,1234,58.7C1302.9,75,1371,149,1406,186.7L1440,224L1440,320L1405.7,320C1371.4,320,1303,320,1234,320C1165.7,320,1097,320,1029,320C960,320,891,320,823,320C754.3,320,686,320,617,320C548.6,320,480,320,411,320C342.9,320,274,320,206,320C137.1,320,69,320,34,320L0,320Z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="bg-grad-3 text-white rounded-lg mb-4 overflow-hidden">
                            <div class="px-3 pt-3">
                                <div class="opacity-50">
                                    <span class="fs-16 d-block">{{ translate('Total') }}</span>
                                    {{ translate('Order') }}
                                </div>
                                <div class="h1 fw-700 mb-3">{{ \App\Models\Order::count() }}</div>
                            </div>
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320">
                                <path fill="rgba(255,255,255,0.3)" fill-opacity="1" d="M0,128L34.3,112C68.6,96,137,64,206,96C274.3,128,343,224,411,250.7C480,277,549,235,617,213.3C685.7,192,754,192,823,181.3C891.4,171,960,149,1029,117.3C1097.1,85,1166,43,1234,58.7C1302.9,75,1371,149,1406,186.7L1440,224L1440,320L1405.7,320C1371.4,320,1303,320,1234,320C1165.7,320,1097,320,1029,320C960,320,891,320,823,320C754.3,320,686,320,617,320C548.6,320,480,320,411,320C342.9,320,274,320,206,320C137.1,320,69,320,34,320L0,320Z"></path>
                            </svg>
                        </div>
                    </div>
                     <!-- Grand Total of Paid Orders -->
                    <div class="col-4">
                        <div class="bg-grad-4 text-white rounded-lg mb-4 overflow-hidden">
                            <div class="px-3 pt-3">
                                <div class="opacity-50">
                                    <span class="fs-16 d-block">{{ translate('Total') }}</span>
                                    {{ translate('Paid Orders Grand Total') }}
                                </div>
                                <div class="h1 fw-700 mb-3">{{ \App\Models\Order::where('payment_status', 'paid')->sum('grand_total') }}</div>
                            </div>
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320">
                                <path fill="rgba(255,255,255,0.3)" fill-opacity="1" d="M0,128L34.3,112C68.6,96,137,64,206,96C274.3,128,343,224,411,250.7C480,277,549,235,617,213.3C685.7,192,754,192,823,181.3C891.4,171,960,149,1029,117.3C1097.1,85,1166,43,1234,58.7C1302.9,75,1371,149,1406,186.7L1440,224L1440,320L1405.7,320C1371.4,320,1303,320,1234,320C1165.7,320,1097,320,1029,320C960,320,891,320,823,320C754.3,320,686,320,617,320C548.6,320,480,320,411,320C342.9,320,274,320,206,320C137.1,320,69,320,34,320L0,320Z"></path>
                            </svg>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <!-- Normal Order Count -->
                    <div class="col-4">
                        <div class="bg-grad-1 text-white rounded-lg mb-4 overflow-hidden">
                            <div class="px-3 pt-3">
                                <div class="opacity-50">
                                    <span class="fs-16 d-block">{{ translate('Total') }}</span>
                                    {{ translate('Normal Orders') }}
                                </div>
                                <div class="h1 fw-700 mb-3">{{ \App\Models\Order::where('order_type', 'normal')->count() }}</div>
                            </div>
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320">
                                <path fill="rgba(255,255,255,0.3)" fill-opacity="1" d="M0,128L34.3,112C68.6,96,137,64,206,96C274.3,128,343,224,411,250.7C480,277,549,235,617,213.3C685.7,192,754,192,823,181.3C891.4,171,960,149,1029,117.3C1097.1,85,1166,43,1234,58.7C1302.9,75,1371,149,1406,186.7L1440,224L1440,320L1405.7,320C1371.4,320,1303,320,1234,320C1165.7,320,1097,320,1029,320C960,320,891,320,823,320C754.3,320,686,320,617,320C548.6,320,480,320,411,320C342.9,320,274,320,206,320C137.1,320,69,320,34,320L0,320Z"></path>
                            </svg>
                        </div>
                    </div>
                    
                    <!-- Buy & Ship Order Count -->
                    <div class="col-4">
                        <div class="bg-grad-2 text-white rounded-lg mb-4 overflow-hidden">
                            <div class="px-3 pt-3">
                                <div class="opacity-50">
                                    <span class="fs-16 d-block">{{ translate('Total') }}</span>
                                    {{ translate('Buy & Ship Orders') }}
                                </div>
                                <div class="h1 fw-700 mb-3">{{ \App\Models\Order::where('order_type', 'buy_ship')->count() }}</div>
                            </div>
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320">
                                <path fill="rgba(255,255,255,0.3)" fill-opacity="1" d="M0,128L34.3,112C68.6,96,137,64,206,96C274.3,128,343,224,411,250.7C480,277,549,235,617,213.3C685.7,192,754,192,823,181.3C891.4,171,960,149,1029,117.3C1097.1,85,1166,43,1234,58.7C1302.9,75,1371,149,1406,186.7L1440,224L1440,320L1405.7,320C1371.4,320,1303,320,1234,320C1165.7,320,1097,320,1029,320C960,320,891,320,823,320C754.3,320,686,320,617,320C548.6,320,480,320,411,320C342.9,320,274,320,206,320C137.1,320,69,320,34,320L0,320Z"></path>
                            </svg>
                        </div>
                    </div>
                    
                    <!-- Ship For Me Order Count -->
                    <div class="col-4">
                        <div class="bg-grad-3 text-white rounded-lg mb-4 overflow-hidden">
                            <div class="px-3 pt-3">
                                <div class="opacity-50">
                                    <span class="fs-16 d-block">{{ translate('Total') }}</span>
                                    {{ translate('Ship For Me Orders') }}
                                </div>
                                <div class="h1 fw-700 mb-3">{{ \App\Models\Order::where('order_type', 'ship_for_me')->count() }}</div>
                            </div>
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320">
                                <path fill="rgba(255,255,255,0.3)" fill-opacity="1" d="M0,128L34.3,112C68.6,96,137,64,206,96C274.3,128,343,224,411,250.7C480,277,549,235,617,213.3C685.7,192,754,192,823,181.3C891.4,171,960,149,1029,117.3C1097.1,85,1166,43,1234,58.7C1302.9,75,1371,149,1406,186.7L1440,224L1440,320L1405.7,320C1371.4,320,1303,320,1234,320C1165.7,320,1097,320,1029,320C960,320,891,320,823,320C754.3,320,686,320,617,320C548.6,320,480,320,411,320C342.9,320,274,320,206,320C137.1,320,69,320,34,320L0,320Z"></path>
                            </svg>
                        </div>
                    </div>
                    
                </div>
            </div>
            
            
        </div>
        
        <div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">{{ translate('Recent 5 Orders') }}</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered mb-0">
                        <thead>
                            <tr>
                                <th>{{ translate('Order Code') }}</th>
                                <th>{{ translate('Item Count') }}</th>
                                <th>{{ translate('Customer') }}</th>
                                <th>{{ translate('Grand Total') }}</th>
                                <th>{{ translate('Order Type') }}</th>
                                <th>{{ translate('Payment Type') }}</th>
                                <th>{{ translate('Payment Status') }}</th>
                                <th>{{ translate('Order Status') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach (\App\Models\Order::latest()->take(5)->get() as $order)
                                <tr>
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
                                    <td>
                                        {{ single_price($order->grand_total) }}
                                    </td>
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
                                        @elseif ($order->status == 'on_the_way')
                                            <span class="badge badge-inline badge-secondary">{{ translate('On the Way') }}</span>
                                        @elseif ($order->status == 'waiting_for_custom_formalities')
                                            <span class="badge badge-inline badge-warning">{{ translate('Waiting for Custom Formalities') }}</span>
                                        @elseif ($order->status == 'goods_received_in_bangladesh')
                                            <span class="badge badge-inline badge-success">{{ translate('Goods Received in Bangladesh') }}</span>
                                        @elseif ($order->status == 'ready_to_deliver')
                                            <span class="badge badge-inline badge-success">{{ translate('Ready to Deliver') }}</span>
                                        @elseif ($order->status == 'delivered')
                                            <span class="badge badge-inline badge-success">{{ translate('Delivered') }}</span>
                                        @elseif ($order->status == 'order_cancelled')
                                            <span class="badge badge-inline badge-danger">{{ translate('Order Cancelled') }}</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
    @endcan

@endsection
@section('script')
@endsection
