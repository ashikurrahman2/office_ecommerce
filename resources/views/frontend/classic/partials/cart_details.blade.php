<div class="container">
    @auth
    @if( $carts && count($carts) > 0 )
        <div class="row">
            <div class="col-xxl-8 col-xl-10 mx-auto">
                <div class="border bg-white p-3 p-lg-4 text-left">
                    <div class="mb-4">
                        <!-- Headers -->
                        <div class="row gutters-5 d-none d-lg-flex border-bottom mb-3 pb-3 text-secondary fs-12">
                            <div class="col fw-600">{{ translate('Qty')}}</div>
                            <div class="col-md-5 fw-600">{{ translate('Product')}}</div>
                            <div class="col fw-600">{{ translate('Attribute')}}</div>
                            <div class="col fw-600">{{ translate('Price')}}</div>
                            <div class="col fw-600">{{ translate('Total')}}</div>
                            <div class="col-auto fw-600">{{ translate('Remove')}}</div>
                        </div>
                        <!-- Cart Items -->
                        <ul class="list-group list-group-flush">
                            @php
                                $total = $carts->sum(fn($cartItem) => $cartItem->price * $cartItem->quantity);
                            @endphp
                            @foreach ($carts as $key => $cartItem)
                            {{-- {{dd($cartItem)}} --}}
                                <li class="list-group-item px-0">
                                    <div class="row gutters-5 align-items-center">
                                        <!-- Quantity -->
                                        {{-- <div class="col order-1 order-md-0">
                                            <div class="input-group quantity-group">
                                                <button class="btn btn-sm btn-outline-secondary decrement-btn" type="button" style="padding: 0px 10px;">-</button>
                                                <input type="text" class="form-control text-center quantity-input p-0" value="{{ $cartItem->quantity }}" data-max="{{ $cartItem->stock }}" data-id="{{ $key }}" style="height: calc(0.5125rem + 1.2rem + 2px);">
                                                <button class="btn btn-sm btn-outline-secondary increment-btn" type="button"style="padding: 0px 10px;">+</button>
                                            </div>
                                        </div> --}}

                                        <div class="d-flex flex-column align-items-start aiz-plus-minus mr-2 ml-0">
                                            <button
                                                class="btn col-auto btn-icon btn-sm btn-circle btn-light"
                                                type="button" data-type="plus"
                                                data-field="quantity[{{ $cartItem['id'] }}]">
                                                <i class="las la-plus"></i>
                                            </button>
                                            <input type="number" name="quantity[{{ $cartItem['id'] }}]"
                                                class="col border-0 text-left px-0 flex-grow-1 fs-14 input-number"
                                                placeholder="1" value="{{ $cartItem['quantity'] }}"
                                                min="{{ $cartItem->min_quantity }}"
                                                max="{{ $cartItem->stock }}"
                                                onchange="updateQuantity({{ $cartItem['id'] }}, this)" style="padding-left:0.75rem !important;">
                                            <button
                                                class="btn col-auto btn-icon btn-sm btn-circle btn-light"
                                                type="button" data-type="minus"
                                                data-field="quantity[{{ $cartItem['id'] }}]">
                                                <i class="las la-minus"></i>
                                            </button>
                                        </div>
                                        <!-- Product Image & name -->
                                        <div class="col-md-5 d-flex align-items-center mb-2 mb-md-0">
                                            <span class="mr-2 ml-0">
                                                <img src="{{ $cartItem->image }}"
                                                    class="img-fit size-70px"
                                                    alt="{{ $cartItem->product_title  }}"
                                                    onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';">
                                            </span>
                                            <span class="fs-14">{{ $cartItem->product_title  }}</span>
                                        </div>
                                        <!-- Attribute -->
                                        <div class="col-md col-4 order-2 order-md-0 my-3 my-md-0">
                                            @if (!empty($cartItem->attributes))
                                                @php
                                                    $attributes = json_decode($cartItem->attributes, true);
                                                @endphp

                                                @if ($attributes)
                                                    <ul class="list-unstyled">
                                                        @foreach ($attributes as $attribute)
                                                            <li><strong>{{ $attribute['name'] }}:</strong> {{ $attribute['value'] }}</li>
                                                        @endforeach
                                                    </ul>
                                                @endif
                                            @endif
                                        </div>
                                        <!-- Price -->
                                        {{-- <div class="col-md col-4 order-2 order-md-0 my-3 my-md-0">
                                            <span class="opacity-60 fs-12 d-block d-md-none">{{ translate('Price')}}</span>
                                            <span class="fw-700 fs-14">{{ $cartItem->price }}</span>
                                        </div> --}}
                                        <div class="col-md col-4 order-2 order-md-0 my-3 my-md-0">
                                            <span class="fw-700 fs-14" id="item-price-{{ $cartItem['id'] }}">{{ $cartItem->price * $cartItem->quantity }}</span>
                                        </div>
                                        
                                        <!-- Total -->
                                        {{-- <div class="col-md col-5 order-4 order-md-0 my-3 my-md-0">
                                            <span class="opacity-60 fs-12 d-block d-md-none">{{ translate('Total')}}</span>
                                            <span class="fw-700 fs-16 text-danger">{{ $cartItem->price * $cartItem->quantity }}</span>
                                        </div> --}}
                                        <div class="col-md col-5 order-4 order-md-0 my-3 my-md-0">
                                            <span class="fw-700 fs-16 text-danger item-total-price" id="item-total-{{ $cartItem['id'] }}">
                                                {{ $cartItem->price * $cartItem->quantity }}
                                            </span>
                                        </div>
                                        <!-- Remove From Cart -->
                                        <div class="col-md-auto col-6 order-5 order-md-0 text-right">
                                            <a href="javascript:void(0)" onclick="removeFromCartView(event, {{ $cartItem->id }})" class="btn btn-icon btn-sm btn-soft-primary bg-soft-secondary-base hov-bg-danger btn-circle">
                                                <i class="las la-trash fs-16"></i>
                                            </a>
                                        </div>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    </div>

                    <!-- Subtotal -->
                    <div class="px-0 py-2 mb-4 border-top d-flex justify-content-between">
                        <span class="opacity-60 fs-14">{{translate('Subtotal')}}</span>
                        <span class="fw-700 fs-16" id="subtotal">{{ $total }}</span>
                    </div>
                    

                    <div class="row align-items-center">
                        <!-- Return to shop -->
                        <div class="col-md-6 text-center text-md-left order-1 order-md-0">
                            <a href="{{ route('home') }}" class="btn btn-link fs-14 fw-700 px-0">
                                <i class="las la-arrow-left fs-16"></i>
                                {{ translate('Return to shop')}}
                            </a>
                        </div>
                        <!-- Continue to Shipping -->
                        <div class="col-md-6 text-center text-md-right">
                            @if(Auth::check())
                                <a href="{{ route('checkout.shipping_info') }}" class="btn btn-danger fs-14 fw-700 rounded-0 px-4">
                                    {{ translate('Continue to Shipping')}}
                                </a>
                            @else
                                <button class="btn btn-danger fs-14 fw-700 rounded-0 px-4" onclick="showLoginModal()">{{ translate('Continue to Shipping')}}</button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @else
        <div class="row">
            <div class="col-xl-8 mx-auto">
                <div class="border bg-white p-4">
                    <!-- Empty cart -->
                    <div class="text-center p-3">
                        <i class="las la-frown la-3x opacity-60 mb-3"></i>
                        <h3 class="h4 fw-700">{{translate('Your Cart is empty')}}</h3>
                    </div>
                </div>
            </div>
        </div>
    @endif
    @endauth
    @guest
        <div class="row">
            <div class="col-xl-8 mx-auto">
                <div class="border bg-white p-4">
                    <!-- Empty cart -->
                    <div class="text-center p-3">
                        <i class="las la-frown la-3x opacity-60 mb-3"></i>
                        <h3 class="h4 fw-700">{{translate('Your Cart is empty')}}</h3>
                    </div>
                </div>
            </div>
        </div>
    @endguest
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Handle increment button
        document.querySelectorAll('.increment-btn').forEach(function (incrementButton) {
            incrementButton.addEventListener('click', function () {
                const input = this.closest('.quantity-group').querySelector('.quantity-input');
                const maxStock = parseInt(input.getAttribute('data-max'), 10);
                let currentQuantity = parseInt(input.value, 10);

                if (currentQuantity < maxStock) {
                    input.value = currentQuantity + 1;
                }
            });
        });

        // Handle decrement button
        document.querySelectorAll('.decrement-btn').forEach(function (decrementButton) {
            decrementButton.addEventListener('click', function () {
                const input = this.closest('.quantity-group').querySelector('.quantity-input');
                let currentQuantity = parseInt(input.value, 10);

                if (currentQuantity > 1) {
                    input.value = currentQuantity - 1;
                }
            });
        });

        // Handle manual input change in real-time with 'input' event
        document.querySelectorAll('.quantity-input').forEach(function (input) {
            input.addEventListener('input', function () {
                const maxStock = parseInt(input.getAttribute('data-max'), 10);
                let currentQuantity = parseInt(input.value, 10);

                if (currentQuantity > maxStock) {
                    alert(`The maximum quantity available is ${maxStock}.`);
                    input.value = maxStock;
                } else if (currentQuantity < 1 || isNaN(currentQuantity)) {
                    // Ensure quantity doesn't go below 1 or become invalid
                    input.value = 1;
                }
            });
        });
    });
</script>