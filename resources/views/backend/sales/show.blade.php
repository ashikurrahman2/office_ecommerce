@extends('backend.layouts.app')

@section('content')

    <div class="card">
        <div class="card-header">
            <h1 class="h2 fs-16 mb-0">{{ translate('Order Details') }}</h1>
        </div>
        <div class="card-body">
            
            
            <div class="row gutters-5">
                <div class="col text-md-left text-center">
                    @if(json_decode($order->shipping_address))
                        <address>
                            <strong class="text-main">
                                {{ json_decode($order->shipping_address)->name }}
                            </strong>
                            {{ json_decode($order->shipping_address)->email }}<br>
                            {{ json_decode($order->shipping_address)->phone }}<br>
                            {{ json_decode($order->shipping_address)->address }}, {{ json_decode($order->shipping_address)->city }}, @if(isset(json_decode($order->shipping_address)->state)) {{ json_decode($order->shipping_address)->state }} - @endif {{ json_decode($order->shipping_address)->postal_code }}<br>
                            {{ json_decode($order->shipping_address)->country }}
                        </address>
                    @else
                       <address>
                            <strong class="text-main">
                                {{ $order->user->name ?? 'Deleted User' }}
                            </strong><br>
                            {{ $order->user->email ?? '' }}<br>
                            {{ $order->user->phone ?? '' }}<br>
                        </address>

                    @endif
                    @if ($order->manual_payment && is_array(json_decode($order->manual_payment_data, true)))
                        <br>
                        <strong class="text-main">{{ translate('Payment Information') }}</strong><br>
                        {{ translate('Name') }}: {{ json_decode($order->manual_payment_data)->name }},
                        {{ translate('Amount') }}:
                        {{ single_price(json_decode($order->manual_payment_data)->amount) }},
                        {{ translate('TRX ID') }}: {{ json_decode($order->manual_payment_data)->trx_id }}
                        <br>
                        <a href="{{ uploaded_asset(json_decode($order->manual_payment_data)->photo) }}" target="_blank">
                            <img src="{{ uploaded_asset(json_decode($order->manual_payment_data)->photo) }}" alt=""
                                height="100">
                        </a>
                    @endif
                </div>
                <div class="col-md-4 ml-auto">
                    <table>
                        <tbody>
                            <tr>
                                <td class="text-main text-bold">{{ translate('Order #') }}</td>
                                <td class="text-info text-bold text-right"> {{ $order->code }}</td>
                            </tr>

                            <tr>
                                <td class="text-main text-bold">{{ translate('Order Status') }}</td>
                                <td class="text-right">
                                    
                                 <span class="badge badge-inline badge-info">{{ translate(ucfirst(str_replace('_', ' ', $order->status))) }}</span> 
                                </td>
                            </tr>
                            <tr>
                                <td class="text-main text-bold">{{ translate('Order Date') }} </td>
                                <td class="text-right">{{ date('d-m-Y h:i A', $order->date) }}</td>
                            </tr>
                            <tr>
                                <td class="text-main text-bold">
                                    {{ translate('Total amount') }}
                                </td>
                                <td class="text-right">
                                    {{ single_price($order->grand_total) }}
                                </td>
                            </tr>
                            
                            <tr>
                                <td class="text-main text-bold">{{ translate('Payment method') }}</td>
                                <td class="text-right">
                                    {{ translate(ucfirst(str_replace('_', ' ', $order->payment_type))) }}</td>
                            </tr>
                            <tr>
                                <td class="text-main text-bold">{{ translate('Payment Status') }}:</td>
                                <td class="text-right"> @if ($order->payment_status == 'paid')
                                    <span class="badge badge-inline badge-success">{{translate('Partially Paid')}}</span>
                                @elseif($order->payment_status == 'fullpaid')
                                    <span class="badge badge-inline badge-success">{{translate('Paid')}}</span>
                                @else
                                    <span class="badge badge-inline badge-primary">{{translate('Unpaid')}}</span>
                                @endif
                                </td>
                            </tr>
                            <tr>
                                <td class="text-main text-bold">{{ translate('Additional Info') }}</td>
                                <td class="text-right">{{ $order->additional_info }}</td>
                            </tr>
                            <tr>
                                <td class="text-main text-bold">{{ translate('Tracking code') }}</td>
                                <td class="text-right">{{ $order->tracking_code }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <hr class="new-section-sm bord-no">
            <div class="row">
                <div class="col-lg-12 table-responsive">
                    <table class="table-bordered aiz-table invoice-summary table">
                        <thead>
                            <tr class="bg-trans-dark">
                                <th class="min-col">#</th>
                                <th width="10%">{{ translate('Photo') }}</th>
                                <th class="text-uppercase">{{ translate('Description') }}</th>
                                <th class="min-col text-uppercase text-center">
                                    {{ translate('Qty') }}
                                </th>
                              
                                 @if($order->order_type == 'ship_for_me')
                                        <th class="min-col text-uppercase text-center">
                                        {{ translate('Weight') }}
                                    </th>
                                @endif
                                <th class="min-col text-uppercase text-center">
                                    {{ translate('Price') }}</th>
                                <th class="min-col text-uppercase text-center">
                                    {{ translate('Total') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                             @php
                                $subTotal = 0;
                            @endphp
                            @foreach ($order->orderDetails as $key => $orderDetail)
                           
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>
        
                                         @if($order->order_type == 'ship_for_me')
                                            @php
                                                $images = json_decode($orderDetail->images, true); // First decode
                                                if (is_string($images)) {
                                                    $images = json_decode($images, true); // Decode again if it's a string
                                                }
                                                $images = is_array($images) ? $images : []; // Ensure it's always an array
                                            @endphp
                                        
                                            @foreach($images as $image)
                                                <img src="{{ asset('public/'.$image) }}" class="img-fluid img-thumbnail mb-2 rounded-start" alt="{{ $orderDetail->product_title }}">
                                            @endforeach
                                        @else
                                            <img height="50" src="{{ $orderDetail->image ? $orderDetail->image : asset('public/default-placeholder.png') }}">
                                        @endif
                                
                                    </td>
                                    <td>
                                        <strong>
                                            <a href="{{ route('product', $orderDetail->product_id) }}" target="_blank" class="text-muted">
                                                {{ $orderDetail->product_title }}
                                            </a>
                                        </strong>
                                        <small>
                                            @if (!empty($orderDetail->variation))
                                                <ul class="list-unstyled">
                                                    @foreach ($orderDetail->variation as $attribute)
                                                        <li><strong>{{ $attribute['name'] }}:</strong> {{ $attribute['value'] }}</li>
                                                    @endforeach
                                                </ul>
                                            @endif
                                        </small>
                                       
                                        <strong>
                                             @if (!empty($orderDetail->product_url))
                                             <strong>
                                             
                                          
                                              <ul class="list-unstyled">
                                                <a href="{{ route('product', $orderDetail->product_id) }}" target="_blank" class="text-muted">
                                               Click2import
                                            </a>
                                            <a href="{{  $orderDetail->product_url }}" class="text-primary" target="_blank" class="text-muted">
                                                1688
                                            </a>
                                            </ul>
                                           
                                        </strong>
                                             
                                            @endif
                                        </strong>
                                    </td>
                                    
                                    <td class="text-center">
                                        {{ $orderDetail->quantity }}
                                    </td>
                                     @if($orderDetail->product_id == 'ship_for_me')
                                       <td class="text-center">
                                        {{ $orderDetail->weight }}
                                     </td>
                                    @endif
                                    
                                    <td class="text-center">
                                        {{ single_price($orderDetail->price ) }}
                                    </td>
                                  <td class="text-center">
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
                                                <strong class="text-muted">{{ translate('Sub Total') }} :</strong>
                                            </td>
                                            <td>
                                                {{ single_price($subTotal) }}
                                            </td>
                                        </tr>
                                        
                                        <tr>
                                            <td>
                                                <strong class="text-muted">{{ translate('Coupon') }} :</strong>
                                            </td>
                                            <td>
                                                {{ single_price($order->coupon_discount) }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <strong class="text-muted">{{ translate('TOTAL') }} :</strong>
                                            </td>
                                            <td >
                                                {{ single_price($order->grand_total) }}
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
                                        @if($order->payment_status == 'paid')
                                        <tr>
                                            <td>
                                                <strong class="text-muted">{{ translate('Paid') }} :</strong>
                                            </td>
                                            <td >
                                               - {{ single_price($order->grand_total*.5) }}
                                            </td>
                                        </tr>
                                       
                                        <tr>
                                            <td>
                                                <strong class="text-muted">{{ translate('TOTAL') }} :</strong>
                                            </td>
                                            <td class="text-muted h5">
                                                {{ single_price(($order->grand_total * .5)+$totalCost) }}
                                            </td>
                                        </tr>
                                        @endif
                                        
                            </tbody>
                        </table>

                        <div class="no-print text-right">
                            <a href="{{ route('invoice.download', $order->id) }}" type="button" class="btn btn-icon btn-light">
                                <i class="las la-print"></i>
                            </a>
                        </div>
                    </div>
                    <div class="card p-3 shadow-sm order-2 order-md-1 w-100 w-md-auto" style="flex: 1; max-width: 85%;" >
                        <div class="card-header">
                            <h5>Order Cost Summary</h5>
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
                    @else
                     <form action="{{ route('order-cost.store') }}" method="POST" class="card p-4 shadow-sm" id="dualForm">
                            @csrf
                            <h5 class="mb-4 text-center">Shipping Cost Verification</h5>
                            <input type="hidden" name="order_id" value="{{ $order->id }}">

                            {{-- success flash --}}
                            @if(session('success'))
                                <div class="alert alert-success">{{ session('success') }}</div>
                            @endif

                            {{-- First Submission Section --}}
                            <div class="first-submission">
                            <p class="text-muted mb-4">Please fill out the shipping cost details below</p>

                            <div class="row">
                                {{-- product_cost --}}
                                <div class="mb-3 col-md-6">
                                    <label for="product_cost" class="form-label">Product Cost(RMB Rate)</label>
                                    <input type="number" step="0.01" class="form-control @error('product_cost') is-invalid @enderror" id="product_cost" name="product_cost" value="{{ old('product_cost') }}" required>
                                    @error('product_cost') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>

                                {{-- china_courier_charge --}}
                                <div class="mb-3 col-md-6">
                                    <label for="china_courier_charge" class="form-label">China Courier Charge(RMB Rate)</label>
                                    <input type="number" step="0.01" class="form-control @error('china_courier_charge') is-invalid @enderror" id="china_courier_charge" name="china_courier_charge" value="{{ old('china_courier_charge') }}">
                                    @error('china_courier_charge') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>

                                {{-- shipping_cost_per_kg --}}
                                <div class="mb-3 col-md-6">
                                    <label for="shipping_cost_per_kg" class="form-label">Shipping Cost (per kg/Tk)</label>
                                    <input type="number" step="0.01" class="form-control @error('shipping_cost_per_kg') is-invalid @enderror" id="shipping_cost_per_kg" name="shipping_cost_per_kg" value="{{ old('shipping_cost_per_kg') }}">
                                    @error('shipping_cost_per_kg') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>

                                {{-- port --}}
                                <div class="mb-4 col-md-6">
                                    <label for="port" class="form-label">Port</label><br>
                                    <select class="form-select aiz-selectpicker @error('port') is-invalid @enderror" id="port" name="port" required>
                                        <option value="" disabled {{ old('port') ? '' : 'selected' }}>-- select port --</option>
                                        <option value="guanzhou" {{ old('port') === 'guanzhou' ? 'selected' : '' }}>Guanzhou</option>
                                        <option value="hongkong" {{ old('port') === 'hongkong' ? 'selected' : '' }}>Hong Kong</option>
                                    </select>
                                    @error('port') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>

                            <button type="button" class="btn btn-primary w-100" id="firstSubmit">
                                <i class="bi bi-check-circle me-2"></i>Submit First Entry
                            </button>
                            </div>

                            {{-- Second Submission Section (initially hidden) --}}
                            <div class="second-submission mt-4 border-top pt-4" style="display: none;">
                                <h6 class="mb-3 text-primary">
                                    <i class="bi bi-shield-check me-2"></i>Verification Entry
                                </h6>
                                <p class="text-muted mb-3">Please re-enter the details to verify accuracy</p>

                                <div class="row">
                                    {{-- product_cost --}}
                                    <div class="mb-3 col-md-6">
                                        <label for="product_cost_verify" class="form-label">Product Cost</label>
                                        <input type="number" step="0.01" class="form-control" id="product_cost_verify" name="product_cost_verify" required>
                                        <div class="match-feedback text-success small mt-1" id="product_cost_match" style="display: none;">
                                            <i class="bi bi-check-circle-fill"></i> Matches first entry
                                        </div>
                                    </div>

                                    {{-- china_courier_charge --}}
                                    <div class="mb-3 col-md-6">
                                        <label for="china_courier_charge_verify" class="form-label">China Courier Charge</label>
                                        <input type="number" step="0.01" class="form-control" id="china_courier_charge_verify" name="china_courier_charge_verify">
                                        <div class="match-feedback text-success small mt-1" id="china_courier_match" style="display: none;">
                                            <i class="bi bi-check-circle-fill"></i> Matches first entry
                                        </div>
                                    </div>

                                    {{-- shipping_cost_per_kg --}}
                                    <div class="mb-3 col-md-6">
                                        <label for="shipping_cost_per_kg_verify" class="form-label">Shipping Cost (per kg)</label>
                                        <input type="number" step="0.01" class="form-control" id="shipping_cost_per_kg_verify" name="shipping_cost_per_kg_verify">
                                        <div class="match-feedback text-success small mt-1" id="shipping_cost_match" style="display: none;">
                                            <i class="bi bi-check-circle-fill"></i> Matches first entry
                                        </div>
                                    </div>

                                    {{-- port --}}
                                    <div class="mb-4 col-md-6">
                                        <label for="port_verify" class="form-label">Port</label><br>
                                        <select class="form-select aiz-selectpicker" id="port_verify" name="port_verify" required>
                                            <option value="" disabled selected>-- select port --</option>
                                            <option value="guanzhou">Guanzhou</option>
                                            <option value="hongkong">Hong Kong</option>
                                        </select>
                                        <div class="match-feedback text-success small mt-1" id="port_match" style="display: none;">
                                            <i class="bi bi-check-circle-fill"></i> Matches first entry
                                        </div>
                                    </div>
                                </div>

                                <div id="validationError" class="alert alert-danger mb-3" style="display: none;"></div>

                                <div class="d-flex justify-content-between">
                                    <button type="button" class="btn btn-outline-secondary" id="backToEdit">
                                        <i class="bi bi-arrow-left me-2"></i>Edit First Entry
                                    </button>
                                    <button type="submit" class="btn btn-success" id="finalSubmit">
                                        <i class="bi bi-check-lg me-2"></i>Confirm Submission
                                    </button>
                                </div>
                            </div>

                        </form>
                    @endif


                        <style>
                            .match-feedback { transition: all 0.3s ease; }
                            .first-submission {
                                transition: opacity 0.3s ease;
                            }
                            .second-submission {
                                background-color: #f8f9fa;
                                border-radius: 0.5rem;
                                padding: 1.5rem;
                            }
                        </style>

        </div>
    </div>
@endsection

@section('script')
    <script type="text/javascript">
        $('#update_payment_status').on('change', function() {
            var order_id = {{ $order->id }};
            var status = $('#update_payment_status').val();
            $.post('{{ route('orders.update_payment_status') }}', {
                _token: '{{ @csrf_token() }}',
                order_id: order_id,
                status: status
            }, function(data) {
                AIZ.plugins.notify('success', '{{ translate('Payment status has been updated') }}');
            });
        });
        $('#update_delivery_status').on('change', function() {
            var order_id = {{ $order->id }};
            var status = $('#update_delivery_status').val();
            $.post('{{ route('orders.update_delivery_status') }}', {
                _token: '{{ @csrf_token() }}',
                order_id: order_id,
                status: status
            }, function(data) {
                AIZ.plugins.notify('success', '{{ translate('Delivery status has been updated') }}');
            });
        });
        $('#update_tracking_code').on('change', function() {
            var order_id = {{ $order->id }};
            var tracking_code = $('#update_tracking_code').val();
            $.post('{{ route('orders.update_tracking_code') }}', {
                _token: '{{ @csrf_token() }}',
                order_id: order_id,
                tracking_code: tracking_code
            }, function(data) {
                AIZ.plugins.notify('success', '{{ translate('Order tracking code has been updated') }}');
            });
        });
    </script>
    <script>
document.addEventListener('DOMContentLoaded', function() {
    const firstSubmitBtn = document.getElementById('firstSubmit');
    const finalSubmitBtn = document.getElementById('finalSubmit');
    const backToEditBtn = document.getElementById('backToEdit');
    const firstSection = document.querySelector('.first-submission');
    const secondSection = document.querySelector('.second-submission');
    const validationError = document.getElementById('validationError');
    const form = document.getElementById('dualForm');

    // Store first submission values
    let firstSubmissionValues = {};

    // Field configuration for verification
    const verifyFields = [
        { first: 'product_cost', verify: 'product_cost_verify', match: 'product_cost_match' },
        { first: 'china_courier_charge', verify: 'china_courier_charge_verify', match: 'china_courier_match' },
        { first: 'shipping_cost_per_kg', verify: 'shipping_cost_per_kg_verify', match: 'shipping_cost_match' },
        { first: 'port', verify: 'port_verify', match: 'port_match' }
    ];

    // First submission handler
    firstSubmitBtn.addEventListener('click', function() {
        // Validate first form
        if (!validateFirstForm()) return;

        // Store first submission values
        verifyFields.forEach(field => {
            firstSubmissionValues[field.first] = document.getElementById(field.first).value;
        });

        // Show verification section
        firstSection.style.opacity = '0.5';
        firstSection.style.pointerEvents = 'none';
        secondSection.style.display = 'block';
        
        // Scroll to verification section
        secondSection.scrollIntoView({ behavior: 'smooth' });
    });

    // Back to edit handler
    backToEditBtn.addEventListener('click', function() {
        secondSection.style.display = 'none';
        firstSection.style.opacity = '1';
        firstSection.style.pointerEvents = 'auto';
        firstSection.scrollIntoView({ behavior: 'smooth' });
    });

    // Real-time matching indicators
    verifyFields.forEach(field => {
        const verifyElement = document.getElementById(field.verify);
        verifyElement.addEventListener('input', function() {
            checkFieldMatch(field);
        });
    });

    // Final submission handler
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        // Check if all verification fields match
        let errors = [];
        let allMatched = true;

        verifyFields.forEach(field => {
            const verifyValue = document.getElementById(field.verify).value;
            
            if (!verifyValue) {
                errors.push(`Please complete the ${field.verify.replace('_verify', '').replace('_', ' ')} field`);
                allMatched = false;
            } else if (firstSubmissionValues[field.first] !== verifyValue) {
                errors.push(`${field.verify.replace('_verify', '').replace('_', ' ')} does not match the first entry`);
                allMatched = false;
            }
        });

        if (!allMatched) {
            validationError.style.display = 'block';
            validationError.innerHTML = `
                <strong>Verification Errors:</strong>
                <ul class="mb-0">${errors.map(error => `<li>${error}</li>`).join('')}</ul>
            `;
            window.scrollTo({ top: secondSection.offsetTop - 20, behavior: 'smooth' });
            return;
        }

        // If validation passes, submit the form
        validationError.style.display = 'none';
        this.submit();
    });

    // Check if a single field matches
    function checkFieldMatch(field) {
        const verifyValue = document.getElementById(field.verify).value;
        const matchElement = document.getElementById(field.match);

        if (verifyValue && firstSubmissionValues[field.first] === verifyValue) {
            matchElement.style.display = 'block';
            return true;
        } else {
            matchElement.style.display = 'none';
            return false;
        }
    }

    // Validate first form
    function validateFirstForm() {
        let isValid = true;
        
        // Check required fields
        const productCost = document.getElementById('product_cost');
        const port = document.getElementById('port');
        
        if (!productCost.value) {
            productCost.classList.add('is-invalid');
            isValid = false;
        } else {
            productCost.classList.remove('is-invalid');
        }
        
        if (!port.value) {
            port.classList.add('is-invalid');
            isValid = false;
        } else {
            port.classList.remove('is-invalid');
        }
        
        return isValid;
    }
});
</script>

@endsection