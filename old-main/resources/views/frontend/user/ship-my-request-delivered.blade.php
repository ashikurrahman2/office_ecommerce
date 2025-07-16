@extends('frontend.layouts.user_panel')

@section('panel_content')
<style>
    .card-container {
        max-width: 900px;
        margin: auto;
        border: 1px solid #ddd;
        margin-bottom: 1rem;
    }
    .card-container .card-header {
        /* font-weight: bold; */
        font-size: 1.1rem;
        padding: 1rem;
        background-color: #f9f9f9;
        border-bottom: 1px solid #ddd;
    }
    
    .product-image {
        width: 70px;
        height: 70px;
        margin-bottom: 0.5rem;
    }
    .btn-start {
        background-color: #4caf50;
        color: white;
        width: 100%;
        padding: 0.4rem;
        font-size: 1rem;
        border: none;
        margin-top: 0.5rem;
        border-radius: 5px;
    }
    .btn-cancel {
        background-color: #f73838;
        color: #fff;
        width: 100%;
        padding: 0.4rem;
        font-size: 1rem;
        border: none;
        margin-top: 0.5rem;
        border-radius: 5px;
    }
    .countdown {
        color: #666;
        font-size: 0.9rem;
        margin-top: 1rem;
        text-align: left;
    }
    .tracking-code {
        color: green;
        font-size: 1rem;
        font-weight: bold;
        cursor: pointer;
    }
    /* .customs-shipping {
        font-weight: bold;
        text-align: right;
        color: #007bff;
    } */
    .customs-shipping-value {
        background-color: #e0f3ff;
        color: #007bff;
        padding: 2px 8px;
        border-radius: 5px;
        font-size: 0.9rem;
        margin-left: 4px;
    }
    .product-details-link {
        color: #007bff;
        font-size: 0.9rem;
        text-decoration: none;
    }
    .section-label {
        font-weight: bold;
    }
</style>
 <div class="card shadow-none rounded-0 border">
        <div class="card-header border-bottom-0">
            <h5 class="mb-0 fs-20 fw-700 text-dark">{{ translate('Ship For Me') }} (<span class="fs-15 fw-500">Delivered</span>)</h5>
        </div>
         <div class="card-body">
            <table class="table aiz-table mb-0">
                <thead class="text-gray fs-12">
                    <tr>
                        <th class="pl-0">{{ translate('Code')}}</th>
                        <th>{{ translate('Amount')}}</th>
                        <th data-breakpoints="md">{{ translate('Date')}}</th>
                        <th data-breakpoints="md">{{ translate('Payment Status')}}</th>
                        <th data-breakpoints="md" class="text-right pr-0">{{ translate('Options')}}</th>
                    </tr>
                </thead>
                <tbody class="fs-14">
                @if (count($requestProducts) > 0)
                    @foreach($requestProducts as $order)
                  
          <!-- Code --><tr>
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
                 </tbody>
                </table>
            <!-- Pagination -->
            <div class="aiz-pagination mt-2">
                {{ $requestProducts->links() }}
            </div>
        </div>
</div>

        @endforeach
    @else
        <div class="row">
            <div class="col">
                <div class="text-center bg-white p-4 border">
                    <img class="mw-100 h-200px" src="{{ static_asset('assets/img/nothing.svg') }}" alt="Image">
                    <h5 class="mb-0 h5 mt-3">{{ translate("There isn't anything added yet") }}</h5>
                </div>
            </div>
        </div>
    @endif
@endsection