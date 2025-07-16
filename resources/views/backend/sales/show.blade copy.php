@extends('backend.layouts.app')

@section('content')

    <div class="card">
        <div class="card-header">
            <h1 class="h2 fs-16 mb-0">{{ translate('Order Details') }}</h1>
        </div>
        <div class="card-body">
            <div class="row gutters-5">
                <div class="col text-md-left text-center">
                </div>
                @php
                    $delivery_status = $order->delivery_status;
                    $payment_status = $order->payment_status;
                    $admin_user_id = App\Models\User::where('user_type', 'admin')->first()->id;
                @endphp
                 
                <div class="col-md-3 ml-auto">
                    
                    <div class="row">
                        <div class="col-md-12">
                            <label for="update_payment_status">{{ translate('Payment Status') }}</label>
                    @if (auth()->user()->can('update_order_payment_status'))
                        <select class="form-control aiz-selectpicker mb-3" data-minimum-results-for-search="Infinity"
                            id="update_payment_status">
                            <option value="unpaid" @if ($payment_status == 'unpaid') selected @endif>
                                {{ translate('Unpaid') }}
                            </option>
                            <option value="paid" @if ($payment_status == 'paid') selected @endif>
                                {{ translate('Paid') }}
                            </option>
                        </select>
                    @else
                        <input type="text" class="form-control" value="{{ $payment_status }}" disabled>
                    @endif
                    
                    
                            
                        </div>
                    </div>
                    
                </div>
            </div>
            <div class="mb-3">
                @php
                    $removedXML = '<?xml version="1.0" encoding="UTF-8"?>';
                @endphp
                {!! str_replace($removedXML, '', QrCode::size(100)->generate($order->code)) !!}
            </div>
            <div class="row gutters-5">
                <div class="col text-md-left text-center">
                    @if(json_decode($order->shipping_address))
                        <address>
                            <strong class="text-main">
                                {{ json_decode($order->shipping_address)->name }}
                            </strong><br>
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
                                     @if ($order->status == 'order_placement')
                                        <span class="badge badge-inline badge-primary">{{ translate('Order Placement') }}</span>
                                    @elseif ($order->status == 'buying_goods')
                                        <span class="badge badge-inline badge-info">{{ translate('Buying Goods') }}</span>
                                    @elseif ($order->status == 'goods_received_in_china_warehouse')
                                        <span class="badge badge-inline badge-warning">{{ translate('Goods Received in China Warehouse') }}</span>
                                    @elseif ($order->status == 'shipment_done')
                                        <span class="badge badge-inline badge-secondary">{{ translate('Shipment Done') }}</span>
                                    @elseif ($order->status == 'goods_received_in_bangladesh')
                                        <span class="badge badge-inline badge-success">{{ translate('Goods Received in Bangladesh') }}</span>
                                    @else
                                        <span class="badge badge-inline badge-success">{{ translate('Ready to Deliver') }}</span>
                                    @endif
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
                                <td class="text-main text-bold">{{ translate('Additional Info') }}</td>
                                <td class="text-right">{{ $order->additional_info }}</td>
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
                                              <ul class="list-unstyled">
                                            <a href="{{  $orderDetail->product_url }}" class="text-primary" target="_blank" class="text-muted">
                                                External Url
                                            </a>
                                            </ul>
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
          <div class="d-flex justify-content-between align-items-start flex-wrap mt-3">
    @php
        $shippingCosts = \App\Models\ShippingCost::where('order_id', $order->id)->get();
    @endphp

    <!-- Right Section -->
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
                            <td class="text-muted h5">
                                {{ single_price($order->grand_total) }}
                            </td>
                        </tr>
            </tbody>
        </table>

        <div class="no-print text-right">
            <a href="{{ route('invoice.download', $order->id) }}" type="button" class="btn btn-icon btn-light">
                <i class="las la-print"></i>
            </a>
        </div>
    </div>

    <!-- Form Section -->
    <form action="{{ route('shipping-cost.store') }}" method="POST" 
          class="card p-3 shadow-sm order-2 order-md-1 w-100 w-md-auto" style="flex: 1; max-width: 85%;" >
        @csrf
        <h5 class="mb-4">Add/Update Shipping Cost</h5>
        <input type="hidden" name="order_id" value="{{ $order->id }}">

        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <!-- Dynamic Items Section -->
        <div id="items-container" class="mb-3">
            @foreach ($shippingCosts as $index => $cost)
                <div class="row item-row">
                    <div class="col-md-2 form-group">
                        <label for="item_name_{{ $index }}">Item Name:</label>
                        <input type="text" id="item_name_{{ $index }}" name="items[{{ $index }}][name]" 
                               class="form-control" value="{{ $cost->name }}" required>
                    </div>
                    <div class="col-md-2 form-group">
                        <label for="type_{{ $index }}">Type:</label>
                        <select id="type_{{ $index }}" name="items[{{ $index }}][type]" 
                                class="form-control type-select" required>
                            <option value="variable" {{ $cost->type === 'variable' ? 'selected' : '' }}>Variable</option>
                            <option value="fixed" {{ $cost->type === 'fixed' ? 'selected' : '' }}>Fixed</option>
                            <option value="discount" {{ $cost->type === 'discount' ? 'selected' : '' }}>Discount</option>
                        </select>
                    </div>
                    <div class="col-md-2 form-group weight-group" 
                         style="display: {{ $cost->type === 'fixed' || $cost->type === 'discount' ? 'none' : '' }}">
                        <label for="weight_{{ $index }}">Weight (kg):</label>
                        <input type="number" id="weight_{{ $index }}" name="items[{{ $index }}][weight]" 
                               class="form-control weight-input" value="{{ $cost->weight }}" placeholder="Enter weight">
                    </div>
                    <div class="col-md-2 form-group cost-per-kg-group" 
                         style="display: {{ $cost->type === 'fixed' || $cost->type === 'discount' ? 'none' : '' }}">
                        <label for="cost_per_kg_{{ $index }}">Cost per Kg:</label>
                        <input type="number" id="cost_per_kg_{{ $index }}" name="items[{{ $index }}][cost_per_kg]" 
                               class="form-control cost-per-kg-input" value="{{ $cost->cost_per_kg }}" 
                               placeholder="Enter cost per kg">
                    </div>
                    <div class="col-md-2 form-group">
                        <label for="total_cost_{{ $index }}">Total:</label>
                        <input type="number" id="total_cost_{{ $index }}" name="items[{{ $index }}][total_cost]" 
                               class="form-control total-cost-input" value="{{ $cost->total_shipping_cost }}" 
                               placeholder="Enter {{ $cost->type === 'discount' ? 'discount' : 'total cost' }}" 
                               {{ $cost->type === 'variable' ? 'readonly' : '' }}>
                    </div>
                    <div class="col-md-2 form-group d-flex align-items-center">
                        <button type="button" class="btn btn-danger btn-sm delete-row">Delete</button>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Add New Item Button -->
        <button type="button" id="addItemButton" class="btn btn-secondary mb-3">Add New Item</button>

        <!-- Submit Button -->
        <div class="form-group d-flex justify-content-between align-items-center">
            <div class="total-shipping-cost" style="flex: 1;">
                <label>Grand Total Shipping Cost:</label>
                <span id="totalShippingCost" style="font-weight: bold;">0.00</span>
            </div>
            <div>
                <button type="submit" class="btn btn-primary">Save Shipping Cost</button>
            </div>
        </div>
    </form>
</div>

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
    document.addEventListener('DOMContentLoaded', () => {
        const itemsContainer = document.getElementById('items-container');
        const totalShippingCostSpan = document.getElementById('totalShippingCost');
        const addItemButton = document.getElementById('addItemButton');
        let itemIndex = {{ count($shippingCosts) }};

        function calculateRowTotal(row) {
            const type = row.querySelector('.type-select').value;
            let totalCost = 0;

            if (type === 'variable') {
                const weight = parseFloat(row.querySelector('.weight-input').value) || 0;
                const costPerKg = parseFloat(row.querySelector('.cost-per-kg-input').value) || 0;
                totalCost = weight * costPerKg;
                row.querySelector('.total-cost-input').value = totalCost.toFixed(2);
            } else if (type === 'fixed' || type === 'discount') {
                totalCost = parseFloat(row.querySelector('.total-cost-input').value) || 0;
            }

            return type === 'discount' ? -totalCost : totalCost;
        }

        function calculateTotalShippingCost() {
            let grandTotal = 0;

            document.querySelectorAll('.item-row').forEach(row => {
                grandTotal += calculateRowTotal(row);
            });

            totalShippingCostSpan.textContent = grandTotal.toFixed(2);
        }

        addItemButton.addEventListener('click', () => {
            const newItemRow = document.createElement('div');
            newItemRow.classList.add('row', 'item-row');
            newItemRow.innerHTML = `
                <div class="col-md-2 form-group">
                    <label for="item_name_${itemIndex}">Item Name:</label>
                    <input type="text" id="item_name_${itemIndex}" name="items[${itemIndex}][name]" class="form-control" placeholder="Enter item name" required>
                </div>
                <div class="col-md-2 form-group">
                    <label for="type_${itemIndex}">Type:</label>
                    <select id="type_${itemIndex}" name="items[${itemIndex}][type]" class="form-control type-select" required>
                        <option value="variable">Variable</option>
                        <option value="fixed">Fixed</option>
                        <option value="discount">Discount</option>
                    </select>
                </div>
                <div class="col-md-2 form-group weight-group">
                    <label for="weight_${itemIndex}">Weight (kg):</label>
                    <input type="number" id="weight_${itemIndex}" name="items[${itemIndex}][weight]" class="form-control weight-input" placeholder="Enter weight">
                </div>
                <div class="col-md-2 form-group cost-per-kg-group">
                    <label for="cost_per_kg_${itemIndex}">Cost per Kg:</label>
                    <input type="number" id="cost_per_kg_${itemIndex}" name="items[${itemIndex}][cost_per_kg]" class="form-control cost-per-kg-input" placeholder="Enter cost per kg">
                </div>
                <div class="col-md-2 form-group">
                    <label for="total_cost_${itemIndex}">Total Cost:</label>
                    <input type="number" id="total_cost_${itemIndex}" name="items[${itemIndex}][total_cost]" class="form-control total-cost-input" placeholder="Enter total cost" readonly>
                </div>
                <div class="col-md-2 form-group d-flex align-items-center">
                    <button type="button" class="btn btn-danger btn-sm delete-row">Delete</button>
                </div>
            `;
            itemsContainer.appendChild(newItemRow);
            itemIndex++;
            attachRowListeners(newItemRow);
        });

        function attachRowListeners(row) {
            const typeSelect = row.querySelector('.type-select');
            const weightGroup = row.querySelector('.weight-group');
            const costPerKgGroup = row.querySelector('.cost-per-kg-group');
            const totalCostInput = row.querySelector('.total-cost-input');

            typeSelect.addEventListener('change', () => {
                if (typeSelect.value === 'fixed' || typeSelect.value === 'discount') {
                    weightGroup.style.display = 'none';
                    costPerKgGroup.style.display = 'none';
                    totalCostInput.readOnly = false;
                } else {
                    weightGroup.style.display = '';
                    costPerKgGroup.style.display = '';
                    totalCostInput.readOnly = true;
                }
                calculateTotalShippingCost();
            });

            row.querySelectorAll('input, select').forEach(input => {
                input.addEventListener('input', calculateTotalShippingCost);
            });

            row.querySelector('.delete-row').addEventListener('click', () => {
                row.remove();
                calculateTotalShippingCost();
            });
        }

        document.querySelectorAll('.item-row').forEach(row => attachRowListeners(row));
        calculateTotalShippingCost();
    });
</script>
  {{--  <script>
    document.addEventListener('DOMContentLoaded', () => {
        const weightInput = document.getElementById('weight');
        const costPerKgInput = document.getElementById('cost_per_kg');
        const courierFeeInput = document.getElementById('courier_fee');
        const discountInput = document.getElementById('discount');
        const totalShippingCostSpan = document.getElementById('totalShippingCost');

        function calculateTotalShippingCost() {
            const weight = parseFloat(weightInput.value) || 0;
            const costPerKg = parseFloat(costPerKgInput.value) || 0;
            const courierFee = parseFloat(courierFeeInput.value) || 0;
            const discount = parseFloat(discountInput.value) || 0;

            const totalCost = weight * costPerKg + courierFee - discount;
            totalShippingCostSpan.textContent = totalCost.toFixed(2);
        }

        // Attach event listeners to inputs
        [weightInput, costPerKgInput, courierFeeInput, discountInput].forEach(input => {
            input.addEventListener('input', calculateTotalShippingCost);
        });

        // Initial calculation
        calculateTotalShippingCost();
    });
</script>--}}

@endsection