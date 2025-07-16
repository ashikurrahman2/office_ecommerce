@extends('frontend.layouts.app')
@section('content')
    <style>
        .selected {
            color: white !important;
            /*background: #f73838 !important;*/
            border-color: #f73838 !important;
        }

        .highlight {
            background: #c2cbb9 !important;
        }

        .tab-content .tab-pane {
            display: none;
        }

        .tab-content .tab-pane.active {
            display: block;
        }

        .attribute-name {
            white-space: normal;
            word-wrap: break-word;
        }

        .attribute-name,
        .configured-item-row td {
            max-width: 120px;
            /* Adjust as needed */
            overflow: hidden;
            text-overflow: ellipsis;

        }

        .video-thumbnail-container {
            position: relative;
        }

        .play-button-overlay {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            font-size: 30px;
            color: white;
            background-color: rgba(0, 0, 0, 0.5);
            /* Semi-transparent black background */
            border-radius: 50%;
            padding: 10px;
            display: flex;
            justify-content: center;
            align-items: center;
            cursor: pointer;
        }

        .play-button-overlay i {
            font-size: 24px;
        }
    </style>
    <section class="pt-3">


        <div class="container">
            <div class="bg-white py-3">
                <div class="row">
                    <!-- Product Image Gallery -->
                    <div class="col-xl-4 col-lg-4 mb-4">

                        <div class="sticky-top z-3 row gutters-10">
                            <div class="col-12">
                                <div class="aiz-carousel product-gallery arrow-inactive-transparent arrow-lg-none"
                                    data-nav-for='.product-gallery-thumb' data-fade='true' data-auto-height='true'
                                    data-arrows='true'>
                                    @foreach ($detailedProduct['Pictures'] as $picture)
                                        @if (!empty($picture['Large']['Url']))
                                            <div class="carousel-box img-zoom rounded-0">
                                                <img class="main-product-image img-fluid h-auto lazyload mx-auto"
                                                    src="{{ static_asset('assets/img/placeholder.jpg') }}"
                                                    data-src="{{ $picture['Large']['Url'] }}"
                                                    onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';">
                                            </div>
                                        @endif
                                    @endforeach
                                    @if (!empty($detailedProduct['Videos']))
                                        @foreach ($detailedProduct['Videos'] as $video)
                                            @if (!empty($video['Url']))
                                                <div class="carousel-box video-container rounded-0">
                                                    <video controls class="w-100 h-auto mx-auto">
                                                        <source src="{{ $video['Url'] }}" type="video/mp4">
                                                        Your browser does not support the video tag.
                                                    </video>
                                                </div>
                                            @endif
                                        @endforeach
                                    @endif
                                </div>
                            </div>

                            <!-- Thumbnail Images -->
                            <div class="col-12 mt-3 d-none d-lg-block">
                                <div class="aiz-carousel half-outside-arrow product-gallery-thumb" data-items='7'
                                    data-nav-for='.product-gallery' data-focus-select='true' data-arrows='true'
                                    data-vertical='false' data-auto-height='true'>

                                    @foreach ($detailedProduct['Pictures'] as $picture)
                                        @if (!empty($picture['Small']['Url']))
                                            <div class="carousel-box c-pointer rounded-0">
                                                <img class="lazyload mw-100 size-60px mx-auto border p-1"
                                                    src="{{ static_asset('assets/img/placeholder.jpg') }}"
                                                    data-src="{{ $picture['Small']['Url'] }}"
                                                    onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';">
                                            </div>
                                        @endif
                                    @endforeach
                                    @if (!empty($detailedProduct['Videos']))
                                        @foreach ($detailedProduct['Videos'] as $video)
                                            @if (!empty($video['PreviewUrl']))
                                                <div class="carousel-box c-pointer rounded-0 video-thumbnail-container">
                                                    <img class="lazyload mw-100 size-60px mx-auto border p-1"
                                                        src="{{ static_asset('assets/img/placeholder.jpg') }}"
                                                        data-src="{{ $video['PreviewUrl'] }}"
                                                        onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';">
                                                    <!-- Play Button Overlay -->
                                                    <div class="play-button-overlay">
                                                        <i class="fas fa-play"></i>
                                                    </div>
                                                </div>
                                            @else
                                                <!-- Fallback to First Image if PreviewUrl is empty -->
                                                @if (!empty($detailedProduct['Pictures'][0]['Small']['Url']))
                                                    <div class="carousel-box c-pointer rounded-0 video-thumbnail-container">
                                                        <img class="lazyload mw-100 size-60px mx-auto border p-1"
                                                            src="{{ static_asset('assets/img/placeholder.jpg') }}"
                                                            data-src="{{ $detailedProduct['Pictures'][0]['Small']['Url'] }}"
                                                            onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';">
                                                        <!-- Play Button Overlay -->
                                                        <div class="play-button-overlay">
                                                            <i class="fas fa-play"></i>
                                                        </div>
                                                    </div>
                                                @endif
                                            @endif
                                        @endforeach
                                    @endif
                                </div>
                            </div>

                        </div>
                    </div>



                    <!-- Product Details -->
                    <div class="col-xl-5 col-lg-5 text-start">
                        @php
                            $couponDetails = [];
                            if (
                                isset($globalCoupon) &&
                                $globalCoupon->type === 'all_product_base' &&
                                $globalCoupon->status == 1
                            ) {
                                $couponDetails = json_decode($globalCoupon->details, true) ?? [];
                                $discount = $globalCoupon->discount;
                                $discountType = $globalCoupon->discount_type;
                                $endDate = \Carbon\Carbon::createFromTimestamp($globalCoupon->end_date)->format(
                                    'F d, Y',
                                );
                                $remainingTime = \Carbon\Carbon::now()->diff(
                                    \Carbon\Carbon::createFromTimestamp($globalCoupon->end_date),
                                );
                            }
                        @endphp


                        @if (!empty($couponDetails))
                            <div class="card pt-2 pl-2 bg-primary text-white">
                                <h5 class="fw-bold mb-0">{{ $discount }}% Flat Discount</h5>
                                <p class="mb-0">Offer Ends on: <span class="text-warning">{{ $endDate }}</span></p>
                                <div id="countdown" class="d-flex ">
                                    <div class="bg-white text-dark rounded p-2 m-2 text-center">
                                        <h6 class="mb-0" id="days">--</h6>
                                        <small>Days</small>
                                    </div>
                                    <div class="bg-white text-dark rounded p-2 m-2 text-center">
                                        <h6 class="mb-0" id="hours">--</h6>
                                        <small>Hours</small>
                                    </div>
                                    <div class="bg-white text-dark rounded p-2 m-2 text-center">
                                        <h6 class="mb-0" id="minutes">--</h6>
                                        <small>Min</small>
                                    </div>
                                    <div class="bg-white text-dark rounded p-2 m-2 text-center">
                                        <h6 class="mb-0" id="seconds">--</h6>
                                        <small>Sec</small>
                                    </div>
                                </div>
                            </div>
                            <script>
                                document.addEventListener('DOMContentLoaded', function() {
                                    let endDate = new Date("{{ \Carbon\Carbon::createFromTimestamp($globalCoupon->end_date) }}").getTime();

                                    function updateCountdown() {
                                        let now = new Date().getTime();
                                        let distance = endDate - now;

                                        if (distance < 0) {
                                            document.getElementById('countdown').innerHTML = '<p class="text-danger">Offer has expired</p>';
                                            return;
                                        }

                                        let days = Math.floor(distance / (1000 * 60 * 60 * 24));
                                        let hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                                        let minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                                        let seconds = Math.floor((distance % (1000 * 60)) / 1000);

                                        document.getElementById('days').innerText = days;
                                        document.getElementById('hours').innerText = hours;
                                        document.getElementById('minutes').innerText = minutes;
                                        document.getElementById('seconds').innerText = seconds;
                                    }

                                    // Update the countdown every second
                                    setInterval(updateCountdown, 1000);
                                    updateCountdown();
                                });
                            </script>
                        @endif

                        <div class="card-body p-1">
                            <h2 class="fs-16 fw-500 text-dark">
                                {{ $detailedProduct['Title'] }}
                            </h2>

                            <div class="d-flex align-items-center">
                                <!-- Review -->
                                @if (isset($featuredValues['rating']))
                                    <div class="col-6 p-0">
                                        <span class="rating rating-mr-1">
                                            {{ renderStarRating($featuredValues['rating']) }} <span
                                                class="fs-16 fw-700">{{ $featuredValues['rating'] }}</span>
                                        </span>
                                        <br>

                                    </div>
                                @endif
                                @if (isset($featuredValues['SalesInLast30Days']))
                                    <div class="col-6 p-0">
                                        <span class="">
                                            Montly Sales: {{ $featuredValues['SalesInLast30Days'] }}
                                        </span>
                                    </div>
                                @endif
                            </div>
                        </div>

                        @php
                            $minQuantity = isset($detailedProduct['FirstLotQuantity'])
                                ? $detailedProduct['FirstLotQuantity']
                                : (isset($detailedProduct['QuantityRanges'][0]['MinQuantity'])
                                    ? $detailedProduct['QuantityRanges'][0]['MinQuantity']
                                    : 0);

                        @endphp

                        {{-- @if (isset($detailedProduct['QuantityRanges']))
                            <div class="card-body p-1">
                                <div class="d-flex text-center align-items-center" style="background-color: #f5f7f3;">
                                    @foreach ($detailedProduct['QuantityRanges'] as $key => $range)
                                        <div class="col py-3 ranges range-{{ $key + 1 }}"
                                            id="range-{{ $key + 1 }}">
                                            <strong class="fs-18 range-price" style="color: #5d742a;"
                                                data-price="{{ $range['Price']['ConvertedPriceWithoutSign'] }}">
                                                {{ $range['Price']['ConvertedPriceWithoutSign'] . $range['Price']['CurrencySign'] }}
                                            </strong>
                                            <div>
                                                @if ($key == 0)
                                                    <span>{{ $range['MinQuantity'] }} -
                                                        {{ $detailedProduct['QuantityRanges'][$key + 1]['MinQuantity'] - 1 }}
                                                        pieces</span>
                                                @elseif($key == count($detailedProduct['QuantityRanges']) - 1)
                                                    <span>≥ {{ $range['MinQuantity'] }} pieces</span>
                                                @else
                                                    <span>{{ $range['MinQuantity'] }} -
                                                        {{ $detailedProduct['QuantityRanges'][$key + 1]['MinQuantity'] - 1 }}
                                                        pieces</span>
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif --}}
                        @if (isset($detailedProduct['QuantityRanges']))
                            <div class="card-body p-1">
                                <div class="d-flex text-center align-items-center" style="background-color: #f5f7f3;">
                                    @foreach ($detailedProduct['QuantityRanges'] as $key => $range)
                                        <div class="col py-3 ranges range-{{ $key + 1 }}"
                                            id="range-{{ $key + 1 }}">
                                            <strong class="fs-18 range-price" style="color: #5d742a;"
                                                data-price="{{ $range['Price']['ConvertedPriceWithoutSign'] }}">
                                                {{ $range['Price']['ConvertedPriceWithoutSign'] . $range['Price']['CurrencySign'] }}
                                            </strong>
                                            <div>
                                                @if ($key == 0)
                                                    <span>{{ $range['MinQuantity'] }} -
                                                        {{ $detailedProduct['QuantityRanges'][$key + 1]['MinQuantity'] - 1 }}
                                                        pieces</span>
                                                @elseif($key == count($detailedProduct['QuantityRanges']) - 1)
                                                    <span>≥ {{ $range['MinQuantity'] }} pieces</span>
                                                @else
                                                    <span>{{ $range['MinQuantity'] }} -
                                                        {{ $detailedProduct['QuantityRanges'][$key + 1]['MinQuantity'] - 1 }}
                                                        pieces</span>
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif


                        <!-- Attribute Selection -->
                        @if ($isMultiple == 'yes')
                            <div class="card-body p-1">
                                <div class="attribute-selection ">
                                    <p class="property_name text-dark"
                                        data-property-name="{{ $propertyNames['first_property'] }}"
                                        style="font-size: 1rem; line-height: 1.5rem;">
                                        {{ $propertyNames['first_property'] }}: <span class="property-name"></span></p>

                                    @foreach ($detailedProduct['Attributes'] as $attribute)
                                        @if ($attribute['PropertyName'] === $propertyNames['first_property'])
                                            @if (isset($attribute['MiniImageUrl']))
                                                <img src="{{ $attribute['MiniImageUrl'] ?? 'default-image.jpg' }}"
                                                    alt="{{ $attribute['Value'] }}"
                                                    class="property-option img-thumbnail mr-3 mb-3"
                                                    data-vid="{{ $attribute['Vid'] }}" data-pid="{{ $attribute['Pid'] }}"
                                                    data-property-value="{{ $attribute['Value'] }}" data-toggle="tooltip"
                                                    data-placement="top" title="{{ $attribute['Value'] ?? '' }}"
                                                    style="width: 60px; height: 60px; cursor: pointer;">
                                            @else
                                                <button class="btn btn-outline-basic btn-sm property-option mr-2 mb-2"
                                                    data-vid="{{ $attribute['Vid'] }}"
                                                    data-pid="{{ $attribute['Pid'] }}"
                                                    data-property-value="{{ $attribute['Value'] }}" data-toggle="tooltip"
                                                    data-placement="top"
                                                    title="{{ $attribute['Value'] ?? '' }}">{{ $attribute['Value'] ?? '' }}</button>
                                            @endif
                                        @endif
                                    @endforeach
                                </div>

                                <!-- Size, Price, Stock, and Quantity Table -->
                                <div class="d-flex flex-wrap align-items-center">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th class="attribute-name">
                                                    {{ ucwords($propertyNames['second_property']) }}
                                                </th>
                                                <th>Price</th>
                                                <th>Stock</th>
                                                <th>Quantity</th>
                                            </tr>
                                        </thead>
                                        <tbody id="sizeTable" class="product-detail-table">

                                        </tbody>
                                    </table>


                                </div>
                            </div>
                        @else
                            <div class="card-body p-1">
                                <div class="d-flex flex-wrap align-items-center">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th class="attribute-name">
                                                    {{ ucwords($propertyNames['first_property']['PropertyName']) }}</th>
                                                <th>Price</th>
                                                <th>Stock</th>
                                                <th>Quantity</th>
                                            </tr>
                                        </thead>
                                        <tbody id="configuredItemsTable" class="product-detail-table">
                                            @foreach ($detailedProduct['ConfiguredItems'] as $index => $configuredItem)
                                                @if (!empty($configuredItem['Configurators']))
                                                    <tr class="configured-item-row"
                                                        @if ($index >= 3) style="display: none;" @endif
                                                        data-weight="{{ $detailedProduct['ActualWeightInfo']['Weight'] }}"
                                                        data-product-id="{{ $detailedProduct['Id'] }}"
                                                        data-min-quantity="{{ $minQuantity }}"
                                                        data-product-name="{{ $detailedProduct['Title'] }}">
                                                        <!-- Dynamic Attributes -->
                                                        <td>
                                                            @foreach ($configuredItem['Configurators'] as $configurator)
                                                                @php
                                                                    // Find the matching attribute based on Vid and PropertyName
                                                                    $attribute = collect(
                                                                        $detailedProduct['Attributes'],
                                                                    )->firstWhere('Vid', $configurator['Vid']);
                                                                @endphp
                                                                @if ($attribute)
                                                                    <div class="attribute-item">
                                                                        @if ($attribute['PropertyName'] === $propertyNames['first_property']['PropertyName'])
                                                                            @if (isset($attribute['MiniImageUrl']))
                                                                                <!-- Display Image for Color Attribute -->
                                                                                <img src="{{ $attribute['MiniImageUrl'] ?? 'default-image.jpg' }}"
                                                                                    alt="{{ $attribute['Value'] }}"
                                                                                    class="single-property-option c-pointer property_name img-thumbnail"
                                                                                    data-property-name="{{ $propertyNames['first_property']['PropertyName'] }}"
                                                                                    data-large-image-url="{{ $attribute['ImageUrl'] ?? '' }}"
                                                                                    data-image-url="{{ $attribute['MiniImageUrl'] ?? '' }}"
                                                                                    data-vid="{{ $attribute['Vid'] }}"
                                                                                    data-pid="{{ $attribute['Pid'] }}"
                                                                                    data-property-value="{{ $attribute['Value'] }}"
                                                                                    data-toggle="tooltip"
                                                                                    data-placement="top"
                                                                                    title="{{ $attribute['Value'] ?? '' }}"
                                                                                    style="width: 60px; height: 60px;">
                                                                            @else
                                                                                <span class="single-property-option"
                                                                                    data-vid="{{ $attribute['Vid'] }}"
                                                                                    data-pid="{{ $attribute['Pid'] }}"
                                                                                    data-image-url="{{ $detailedProduct['Pictures'][0]['Large']['Url'] }}"
                                                                                    data-property-value="{{ $attribute['Value'] }}">
                                                                                    {{ $attribute['Value'] }}</span>
                                                                            @endif
                                                                        @else
                                                                            <!-- Display Text for Other Attributes (e.g., Size) -->
                                                                            <span class="single-property-option"
                                                                                data-vid="{{ $attribute['Vid'] }}"
                                                                                data-pid="{{ $attribute['Pid'] }}"
                                                                                data-property-value="{{ $attribute['Value'] }}">{{ $attribute['PropertyName'] }}:
                                                                                {{ $attribute['Value'] }}</span>
                                                                        @endif
                                                                    </div>
                                                                @endif
                                                            @endforeach
                                                        </td>

                                                        <!-- Price -->
                                                        {{-- <td
                                                            data-price={{ isset($configuredItem['Price']['ConvertedPrice']) ? str_replace(' ', '', $configuredItem['Price']['ConvertedPrice']) : 0 }}>
                                                            {{ isset($configuredItem['Price']['ConvertedPrice']) ? str_replace(' ', '', $configuredItem['Price']['ConvertedPrice']) : 0 }}
                                                        </td> --}}
                                                        {{-- <td
                                                            data-price="{{ isset($configuredItem['Price']['ConvertedPrice']) ? str_replace(' ', '', $configuredItem['Price']['ConvertedPrice']) : 0 }}">
                                                            <span class="product-price">
                                                                {{ isset($configuredItem['Price']['ConvertedPrice']) ? str_replace(' ', '', $configuredItem['Price']['ConvertedPrice']) : 0 }}
                                                            </span>
                                                        </td> --}}
                                                        <td
                                                            data-price="{{ isset($configuredItem['Price']['ConvertedPrice'])
                                                                ? str_replace(' ', '', $configuredItem['Price']['ConvertedPrice'])
                                                                : 0 }}">
                                                            <span class="product-price">
                                                                {{ isset($configuredItem['Price']['ConvertedPrice'])
                                                                    ? str_replace(' ', '', $configuredItem['Price']['ConvertedPrice'])
                                                                    : 0 }}
                                                            </span>
                                                        </td>



                                                        <!-- Stock -->
                                                        <td data-stock="{{ $configuredItem['Quantity'] ?? 0 }}">
                                                            {{ $configuredItem['Quantity'] ?? 0 }}
                                                        </td>

                                                        <!-- Quantity Input with Increment and Decrement -->
                                                        <td>
                                                            <div class="input-group quantity-group">
                                                                <button
                                                                    class="btn btn-outline-secondary decrement-btn px-2"
                                                                    type="button">-</button>
                                                                <input type="text"
                                                                    class="form-control text-center quantity-input"
                                                                    value="0" style=" padding: 0;">
                                                                <button
                                                                    class="btn btn-outline-secondary increment-btn px-2"
                                                                    type="button">+</button>
                                                            </div>
                                                        </td>

                                                    </tr>
                                                @endif
                                            @endforeach

                                        </tbody>

                                    </table>

                                    <!-- View All/Show Less Button -->
                                    <div class="text-center viewButtonSection d-none">
                                        <button type="button"
                                            class="btn btn-link text-decoration-none toggleViewBtn">View All</button>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <div class="">
                            <p>Freight Forwarding Agent Process?</p>
                            <h6>Approximate Weight: <span
                                    class="total_weight">{{ $detailedProduct['ActualWeightInfo']['Weight'] }} </span> KG
                                (আনুমানিক)
                            </h6>
                            <span class="text-danger">* বিঃ দ্রঃ প্রদত্ত ওজন ১০০ ভাগ সঠিক নয়। বাংলাদেশ ওয়ারহাউজে পণ্য রিসিভ
                                হওয়ার পর সঠিক ওজন জানিয়ে দেওয়া হবে ।</span>
                        </div>

                        <!-- Share -->
                        <div class="row no-gutters d-none d-md-block">
                            <div class="col-sm-2">
                                <div class="text-secondary fs-14 fw-400 mt-2">{{ translate('Share') }}</div>
                            </div>
                            <div class="col-sm-10">
                                <div class="aiz-share"></div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-3 col-lg-3">
                        <div class="card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-auto mr-auto">
                                        <p><span class="total_quantity">0.00</span> Pieces</p>
                                    </div>
                                    <div class="col-auto">
                                        <h6><span class="fw-700">৳</span><span class="sub_total_price">0.00</span></h6>
                                    </div>
                                </div>
                                {{-- <p>Domestic Shipping Charge: <span class="fw-700">৳</span><span class="shpping_charge">0.00</span></p> --}}

                                @php
                                    $cost = $shippingCost?->air_cost ?? 0.0;
                                    $deliveryTime = $shippingCost?->air_delivery_time ?? 0;
                                @endphp

                                <p class="mb-1">
                                    Shipping Charge:
                                    <span class="fw-700">৳</span>
                                    <span class="shpping_charge">{{ number_format($cost, 2) }} /kg</span>
                                </p>
                                <p>
                                    Delivery Time:
                                    <span class="delivery_days">{{ $deliveryTime }}</span> days
                                </p>


                                <hr>
                                <h5>Total: <span class="fw-700">৳</span><span class="total_price">0.00</span></h5>
                                <p class="text-danger"><small>China courier charge + China to Bangladesh shipping charge,
                                        will be added later.</small></p>

                                <button type="button"
                                    class="btn btn-danger btn-block add-to-cart fw-600 add-to-cart min-w-150px rounded-1"
                                    id="addToCartBtn"
                                    @if (Auth::check()) onclick="addToCart()" 
                                        @else 
                                            onclick="showLoginModal()" @endif
                                    disabled>
                                    <i class="las la-shopping-bag"></i> {{ translate('Add to Cart') }}
                                </button>
                            </div>
                        </div>


                        <div class="card p-3" style="border: 1px dashed red; border-radius: 5px;">
                            <button type="button" class="btn btn-white fs-16" data-toggle="modal"
                                data-target="#shippingChargeList">
                                Click here to view the shipping charges based on categories
                            </button>
                        </div>

                        <p class="text-danger">**After the product arrives in Bangladesh, the final shipping charge will be
                            determined based on
                            the product's category.</p>


                        <div class="card">
                            <div class="card-body">
                                <div class="seller-details mt-3">
                                    <p><strong>Seller Details</strong></p>
                                    <p>Name: <strong>{{ $detailedProduct['VendorName'] }}</strong></p>
                                    <a href="{{ route('shop.visit', ['vendorId' => $detailedProduct['VendorId'], 'vendorName' => $detailedProduct['VendorName']]) }}"
                                        class="btn btn-block btn-danger">
                                        View Store
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <div id="section_featured">
        <section class="mb-2 mb-md-3 mt-2 mt-md-3">
            <div class="container">
                <!-- Top Section -->
                <div class="d-flex mb-2 mb-md-3 align-items-baseline justify-content-between">
                    <!-- Title -->
                    <h3 class="fs-16 fs-md-20 fw-700 mb-2 mb-sm-0">
                        <span class="">{{ $detailedProduct['VendorName'] }}</span>
                    </h3>
                    <!-- Links -->
                    <div class="d-flex">
                        <a type="button" class="arrow-prev slide-arrow link-disable text-secondary mr-2"
                            onclick="clickToSlide('slick-prev','section_featured')"><i
                                class="las la-angle-left fs-20 fw-600"></i></a>
                        <a type="button" class="arrow-next slide-arrow text-secondary ml-2"
                            onclick="clickToSlide('slick-next','section_featured')"><i
                                class="las la-angle-right fs-20 fw-600"></i></a>
                    </div>
                </div>
                <!-- Products Section -->
                <div class="px-sm-3">
                    <div class="aiz-carousel sm-gutters-16 arrow-none" data-items="6" data-xl-items="5"
                        data-lg-items="4" data-md-items="3" data-sm-items="2" data-xs-items="2" data-arrows='true'
                        data-infinite='false'>
                        @foreach ($SimilerProducts as $key => $product)
                            <div class=" position-relative px-0 has-transition">
                                <div class="px-1 mb-3 custom-product-box">
                                    @include(
                                        'frontend.' . get_setting('homepage_select') . '.partials.product_box',
                                        ['product' => $product]
                                    )
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </section>
    </div>
    <section class="mb-4">
        <div class="container">
            <nav>
                <div class="nav nav-tabs" id="nav-tab" role="tablist">
                    <button class="nav-link active" id="nav-home-tab" data-toggle="tab" data-target="#nav-home"
                        type="button" role="tab" aria-controls="nav-home"
                        aria-selected="true">Specification</button>
                    <button class="nav-link" id="nav-profile-tab" data-toggle="tab" data-target="#nav-profile"
                        type="button" role="tab" aria-controls="nav-profile" aria-selected="false">Product
                        Details</button>
                    <button class="nav-link" id="nav-contact-tab" data-toggle="tab" data-target="#nav-contact"
                        type="button" role="tab" aria-controls="nav-contact" aria-selected="false">Reviews</button>
                </div>
            </nav>
            <div class="tab-content" id="nav-tabContent">
                <div class="tab-pane fade show active" id="nav-home" role="tabpanel" aria-labelledby="nav-home-tab">
                    <div class="mt-4">
                        <table class="table table-bordered">
                            <tbody>
                                @foreach ($groupedAttributes as $propertyName => $values)
                                    <tr>
                                        <td><strong>{{ $propertyName }}</strong></td>
                                        <td>{{ implode(', ', $values) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="tab-pane fade" id="nav-profile" role="tabpanel" aria-labelledby="nav-profile-tab">
                    <style>
                        .product-description *,
                        .product-description *[style*="width"] {
                            width: 100% !important;
                            max-width: 100% !important;
                            box-sizing: border-box !important;
                        }
                    </style>

                    <div class="mt-4 product-description" style="overflow-x:hidden; width: 100%;">
                        {!! $detailedProduct['Description'] !!}
                    </div>


                </div>
                <div class="tab-pane fade" id="nav-contact" role="tabpanel" aria-labelledby="nav-contact-tab">
                    <div class="mt-4">
                        <div class="bg-white border mb-4">
                            <div class="p-3 p-sm-4">
                                <h3 class="fs-16 fw-700 mb-0">
                                    <span class="mr-4">{{ translate('Reviews & Ratings') }}</span>
                                </h3>
                            </div>


                            <!-- Reviews -->
                            {{-- @include('frontend.product_details.reviews') --}}
                            <div class="py-3 reviews-area">
                                <ul class="list-group list-group-flush">
                                    @foreach ($reviewsContent as $key => $review)
                                        <li class="media list-group-item d-flex px-3 px-md-4 border-0">
                                            <!-- Review User Image -->
                                            <span class="avatar avatar-md mr-3">
                                                <img class="lazyload"
                                                    src="{{ static_asset('assets/img/placeholder.jpg') }}"
                                                    onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';"
                                                    data-src="{{ static_asset('assets/img/placeholder.jpg') }}">
                                            </span>
                                            <div class="media-body text-left">
                                                <!-- Review User Name -->
                                                <h3 class="fs-15 fw-600 mb-0">{{ $review['UserName'] }}
                                                </h3>
                                                <!-- Review Date -->
                                                <div class="opacity-60 mb-1">
                                                    {{ date('d-m-Y', strtotime($review['CreatedTime'])) }}
                                                </div>
                                                <!-- Review ratting -->
                                                <span class="rating rating-mr-1">
                                                    {{ renderStarRating($review['Rating']) }}

                                                </span>
                                                <!-- Review Comment -->
                                                <p class="comment-text mt-2 fs-14">
                                                    {{ $review['Text'] }}
                                                </p>

                                            </div>
                                        </li>
                                    @endforeach
                                </ul>


                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </section>


    <!-- Shipping Charge Modal -->
    <div class="modal fade" id="shippingModal" tabindex="-1" role="dialog" aria-labelledby="shippingModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="shippingModalLabel">Shipping Charge Information</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p class="mb-1"><strong>Approximate Weight:</strong> <span id="modalWeight">0.00</span> KG</p>
                    <p class="mb-1"><strong>Total Quantity:</strong> <span id="modalQuantity">0</span></p>
                    <p class="mb-1"><strong>Approximate Shipping Cost: ৳</strong><span
                            id="totalShippingCost">0.00</span></p>

                    <div class="form-row mt-4">
                        <div class="form-group col-md-6">
                            <label for="category">Select a Category</label>
                            <select class="form-control selectpicker" id="category" data-live-search="true"
                                onchange="categoryInfo()">
                                <!-- Options will be populated dynamically -->
                            </select>
                        </div>
                    </div>


                    <div class="shipping-options">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Shipping Type</th>
                                    <th>Cost</th>
                                    <th>Delivery Time</th>
                                </tr>
                            </thead>
                            <tbody id="shippingOptionsBody">

                            </tbody>
                        </table>
                    </div>


                    <button type="button" class="btn btn-block btn-danger mt-3" id="applyShippingCharge">Apply</button>
                    <p class="text-note text-danger mt-2">*Delivery time will start counting after the product is received
                        in the China warehouse</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Shipping Charge List Modal -->
    <div class="modal fade" id="shippingChargeList" data-backdrop="static" data-keyboard="false" tabindex="-1"
        role="dialog" aria-labelledby="shippingChargeListLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="shippingChargeListLabel">Shipping Charge Information</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <!-- Search Input Field -->
                    <div class="mb-3">
                        <input type="text" id="categorySearch" class="form-control" placeholder="Search Categories">
                    </div>

                    <table class="table table-bordered mb-0">
                        @foreach ($shippingCategories as $shippingCategory)
                            @php
                                // Fetch children of the current category
                                $children = $shippingCategories->where('parent_id', $shippingCategory->id);
                            @endphp

                            @if ($children->isNotEmpty())
                                <!-- Show parent with children as a group -->
                                <thead class="category-group" data-category="{{ strtolower($shippingCategory->name) }}">
                                    <tr>
                                        <th colspan="2" class="bg-light">
                                            <h6 class="mb-0">{{ ucfirst($shippingCategory->name) }}</h6>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($children as $child)
                                        <tr class="category-item" data-category="{{ strtolower($child->name) }}">
                                            <td class="fs-14 w-60">{{ $child->name }}</td>
                                            <td class="fs-14 w-40">
                                                <span>Ship Cost: {{ $child->ship_cost }} tk/kg</span> /
                                                <span>Air Cost: {{ $child->air_cost }} tk/kg</span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            @elseif ($shippingCategory->parent_id == 0 && $shippingCategories->where('parent_id', $shippingCategory->id)->isEmpty())
                                <!-- Show parent shippingCategories without children as individual rows -->
                                <thead class="category-group" data-category="{{ strtolower($shippingCategory->name) }}">
                                    <tr>
                                        <th colspan="2" class="bg-light">
                                            <h6 class="mb-0">{{ ucfirst($shippingCategory->name) }}</h6>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr class="category-item" data-category="{{ strtolower($shippingCategory->name) }}">
                                        <td class="fs-14 w-60">{{ $shippingCategory->name }}</td>
                                        <td class="fs-14 w-40">
                                            <span>Ship Cost: {{ $shippingCategory->ship_cost }} tk/kg</span> /
                                            <span>Air Cost: {{ $shippingCategory->air_cost }} tk/kg</span>
                                        </td>
                                    </tr>
                                </tbody>
                            @endif
                        @endforeach
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript for Search Functionality -->
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const searchInput = document.getElementById("categorySearch");

            searchInput.addEventListener("keyup", function() {
                let filter = searchInput.value.toLowerCase();
                let categoryGroups = document.querySelectorAll(".category-group");
                let categoryItems = document.querySelectorAll(".category-item");

                categoryGroups.forEach(group => {
                    let groupCategory = group.getAttribute("data-category");
                    let matchingItems = [...group.nextElementSibling.children].filter(item => {
                        return item.getAttribute("data-category").includes(filter);
                    });

                    if (groupCategory.includes(filter) || matchingItems.length > 0) {
                        group.style.display = "";
                        matchingItems.forEach(item => item.style.display = "");
                    } else {
                        group.style.display = "none";
                        matchingItems.forEach(item => item.style.display = "none");
                    }
                });

                categoryItems.forEach(item => {
                    let itemCategory = item.getAttribute("data-category");
                    item.style.display = itemCategory.includes(filter) ? "" : "none";
                });
            });
        });
    </script>

@endsection


@section('script')
    <script type="text/javascript">
        $(document).ready(function() {
            getVariantPrice();
        });

        function CopyToClipboard(e) {
            var url = $(e).data('url');
            var $temp = $("<input>");
            $("body").append($temp);
            $temp.val(url).select();
            try {
                document.execCommand("copy");
                AIZ.plugins.notify('success', '{{ translate('Link copied to clipboard') }}');
            } catch (err) {
                AIZ.plugins.notify('danger', '{{ translate('Oops, unable to copy') }}');
            }
            $temp.remove();
            // if (document.selection) {
            //     var range = document.body.createTextRange();
            //     range.moveToElementText(document.getElementById(containerid));
            //     range.select().createTextRange();
            //     document.execCommand("Copy");

            // } else if (window.getSelection) {
            //     var range = document.createRange();
            //     document.getElementById(containerid).style.display = "block";
            //     range.selectNode(document.getElementById(containerid));
            //     window.getSelection().addRange(range);
            //     document.execCommand("Copy");
            //     document.getElementById(containerid).style.display = "none";

            // }
            // AIZ.plugins.notify('success', 'Copied');
        }

        function show_chat_modal() {
            @if (Auth::check())
                $('#chat_modal').modal('show');
            @else
                $('#login_modal').modal('show');
            @endif
        }

        // Pagination using ajax
        $(window).on('hashchange', function() {
            if (window.history.pushState) {
                window.history.pushState('', '/', window.location.pathname);
            } else {
                window.location.hash = '';
            }
        });

        $(document).ready(function() {
            $(document).on('click', '.product-queries-pagination .pagination a', function(e) {
                getPaginateData($(this).attr('href').split('page=')[1], 'query', 'queries-area');
                e.preventDefault();
            });
        });

        $(document).ready(function() {
            $(document).on('click', '.product-reviews-pagination .pagination a', function(e) {
                getPaginateData($(this).attr('href').split('page=')[1], 'review', 'reviews-area');
                e.preventDefault();
            });
        });

        function getPaginateData(page, type, section) {
            $.ajax({
                url: '?page=' + page,
                dataType: 'json',
                data: {
                    type: type
                },
            }).done(function(data) {
                $('.' + section).html(data);
                location.hash = page;
            }).fail(function() {
                alert('Something went worng! Data could not be loaded.');
            });
        }
        // Pagination end

        function showImage(photo) {
            $('#image_modal img').attr('src', photo);
            $('#image_modal img').attr('data-src', photo);
            $('#image_modal').modal('show');
        }

        function bid_modal() {
            @if (isCustomer() || isSeller())
                $('#bid_for_detail_product').modal('show');
            @elseif (isAdmin())
                AIZ.plugins.notify('warning', '{{ translate('Sorry, Only customers & Sellers can Bid.') }}');
            @else
                $('#login_modal').modal('show');
            @endif
        }


        function showSizeChartDetail(id, name) {
            $('#size-chart-show-modal .modal-title').html('');
            $('#size-chart-show-modal .modal-body').html('');
            if (id == 0) {
                AIZ.plugins.notify('warning', '{{ translate('Sorry, There is no size guide found for this product.') }}');
                return false;
            }
            $.ajax({
                type: "GET",
                url: "{{ route('size-charts-show', '') }}/" + id,
                data: {},
                success: function(data) {
                    $('#size-chart-show-modal .modal-title').html(name);
                    $('#size-chart-show-modal .modal-body').html(data);
                    $('#size-chart-show-modal').modal('show');
                }
            });
        }
    </script>

    <script>
        let attributes = @json($detailedProduct['Attributes']);
        let configuredItems = @json($detailedProduct['ConfiguredItems']);
        let propertyName = @json($propertyNames);
        let quantityRanges = @json($detailedProduct['QuantityRanges'] ?? null);
        let actualWeightInfo = @json($detailedProduct['ActualWeightInfo'] ?? null);
        let productId = @json($detailedProduct['Id'] ?? null);
        let productTitle = @json($detailedProduct['Title'] ?? null);
        let minQuantity = @json($minQuantity ?? null);
        let ExternalItemUrl = @json($detailedProduct['ExternalItemUrl'] ?? null);
        console.log(minQuantity);
        // edit by prosit
        function initializeToggle() {
            const toggleViewBtn = document.querySelector('.toggleViewBtn');
            const configuredRows = document.querySelectorAll('.configured-item-row');
            const viewButtonSection = document.querySelector('.viewButtonSection');

            // Check if elements exist to avoid errors
            if (!toggleViewBtn || !viewButtonSection || configuredRows.length === 0) return;

            // Initially hide rows beyond the first 3
            configuredRows.forEach((row, index) => {
                if (index > 2) {
                    row.style.display = 'none';
                }
            });

            // Show the button only if there are more than 3 rows
            if (configuredRows.length > 3) {
                viewButtonSection.classList.remove('d-none');
                toggleViewBtn.textContent = 'View All'; // Set initial button text
            }

            toggleViewBtn.addEventListener('click', function() {
                const anyRowVisible = Array.from(configuredRows).slice(3).some(row => row.style.display !== 'none');

                // Toggle visibility of configured item rows
                configuredRows.forEach((row, index) => {
                    if (index > 2) {
                        row.style.display = anyRowVisible ? 'none' : 'table-row';
                    }
                });

                // Update button text based on actual row visibility
                toggleViewBtn.textContent = anyRowVisible ? 'View All' : 'Show Less';
            });
        }


        //okkkk      
        document.querySelectorAll('.single-property-option').forEach(function(option) {
            const propertyOptions = document.querySelectorAll(".single-property-option");
            const mainImage = document.querySelector(".main-product-image"); // Select by class
            const productGallery = $(".product-gallery"); // Carousel
            const thumbnails = document.querySelectorAll(".product-gallery-thumb .c-pointer img");

            let originalImageSrc = mainImage.getAttribute("data-src"); // Store the original image src

            // Function to reset the carousel to its original state
            function resetCarousel() {
                productGallery.slick("slickGoTo", 0); // Reset to first image
            }

            // Click event for property options (Change main image + first image in carousel)
            propertyOptions.forEach(option => {
                option.addEventListener("click", function() {
                    resetCarousel();
                    let newImageUrl = this.getAttribute(
                        "data-large-image-url"); // Get new image from property option

                    if (mainImage) {
                        mainImage.src = newImageUrl;
                        mainImage.setAttribute("data-src", newImageUrl);
                    }
                });
            });

            // Click event for carousel thumbnails (Reset first image back to original)
            thumbnails.forEach(thumb => {
                thumb.addEventListener("click", function() {
                    mainImage.src = originalImageSrc; // Reset first image to original
                    mainImage.setAttribute("data-src", originalImageSrc);
                });
            });
        });
        let selectedQuantities = {};

        document.querySelectorAll('.property-option').forEach(function(option) {
            const propertyOptions = document.querySelectorAll(".property-option");
            const mainImage = document.querySelector(".main-product-image"); // Select by class
            const productGallery = $(".product-gallery"); // Carousel
            const thumbnails = document.querySelectorAll(".product-gallery-thumb .c-pointer img");

            let originalImageSrc = mainImage.getAttribute("data-src"); // Store the original image src

            // Function to reset the carousel to its original state
            function resetCarousel() {
                productGallery.slick("slickGoTo", 0); // Reset to first image
            }

            // Click event for property options (Change main image + first image in carousel)
            propertyOptions.forEach(option => {
                option.addEventListener("click", function() {
                    resetCarousel();
                    let newImageUrl = this.getAttribute(
                        "src"); // Get new image from property option

                    if (mainImage) {
                        mainImage.src = newImageUrl;
                        mainImage.setAttribute("data-src", newImageUrl);
                    }
                });
            });

            // Click event for carousel thumbnails (Reset first image back to original)
            thumbnails.forEach(thumb => {
                thumb.addEventListener("click", function() {
                    mainImage.src = originalImageSrc; // Reset first image to original
                    mainImage.setAttribute("data-src", originalImageSrc);
                });
            });


            option.addEventListener('click', function() {
                // Remove 'selected' class from all options to reset
                document.querySelectorAll('.property-option').forEach(opt => {
                    opt.classList.remove('selected');
                });

                // Add 'selected' class to the clicked option
                this.classList.add('selected');

                // Get the selected Vid and Pid from the clicked option
                let selectedValueID = this.getAttribute('data-vid');
                let selectedPropertyID = this.getAttribute('data-pid');

                let itemPropertyValue = this.getAttribute('data-property-value');
                let itemselectedImage = this.src;
                // console.log(itemselectedImage);

                // Find matching configured items
                let matchingItems = configuredItems.filter(item =>
                    item.Configurators.some(configurator =>
                        configurator.Pid === selectedPropertyID && configurator.Vid === selectedValueID
                    )
                );

                showMatchingRows(matchingItems, itemPropertyValue, itemselectedImage);
            });



        });
        // Event listener for increment and decrement buttons

        document.addEventListener('DOMContentLoaded', function() {
            populateTable(configuredItems); // Populate table with all attributes on page load
        });



        function populateTable(items) {
            const sizeTableBody = document.getElementById('sizeTable');
            sizeTableBody.innerHTML = ''; // Clear previous entries

            items.forEach(item => {
                item.Configurators.forEach(configurator => {
                    let attribute = attributes.find(attr =>
                        attr.Pid === configurator.Pid && attr.Vid === configurator.Vid
                    );

                    if (attribute && attribute.PropertyName === propertyName.second_property) {
                        // Create a new row
                        let row = document.createElement('tr');
                        row.classList.add('configured-item-row', 'd-none'); // Add 'd-none' class initially
                        row.setAttribute('data-item-id', item.Id); // Set product ID

                        row.setAttribute('data-weight', actualWeightInfo
                            .Weight); // Store weight in a data attribute
                        row.setAttribute('data-product-id', productId);
                        row.setAttribute('data-product-title', productTitle);
                        row.setAttribute('data-min-quantity', minQuantity);
                        row.setAttribute('data-property-value', minQuantity);




                        // Create and populate cells
                        let attributeCell = document.createElement('td');

                        // Check if MiniImageUrl is available
                        if (attribute.MiniImageUrl) {
                            // If MiniImageUrl exists, create an image element
                            let img = document.createElement('img');
                            img.src = attribute.MiniImageUrl;
                            img.alt = attribute.Value; // Use the value as alt text
                            img.style.width = '50px'; // Adjust width as needed

                            // Add tooltip attributes
                            img.setAttribute('data-toggle', 'tooltip');
                            img.setAttribute('data-placement', 'top');
                            img.setAttribute('title', attribute.Value ||
                                ''); // Tooltip text from the value

                            // Append the image to the cell
                            attributeCell.appendChild(img);
                            attributeCell.setAttribute('data-attribute-value', attribute
                                .Value); // Store price in a data attribute
                            attributeCell.setAttribute('data-image-src', attribute
                                .MiniImageUrl); // Store price in a data attribute
                        } else {
                            // If no image, just display the value as text
                            attributeCell.textContent = attribute.Value;
                            attributeCell.setAttribute('data-attribute-value', attribute
                                .Value);
                        }

                        let priceCell = document.createElement('td');
                        // Clean and format the price
                        let convertedPrice = item.Price.ConvertedPrice.replace(/\s/g, ''); // Remove spaces

                        // Set text content with the currency sign and cleaned-up price
                        priceCell.textContent = `${convertedPrice}`;

                        // Set data-price attribute with cleaned-up price
                        priceCell.setAttribute('data-price', convertedPrice);



                        let stockCell = document.createElement('td');
                        stockCell.textContent = item.Quantity; // Stock value
                        stockCell.setAttribute('data-stock', item.Quantity);

                        let quantityCell = document.createElement('td');

                        // Check stock quantity
                        const stockQuantity = item.Quantity ||
                            0; // Get the stock quantity
                        if (stockQuantity === 0) {
                            // Show "Out of stock" if stock is 0
                            quantityCell.innerHTML =
                                '<span class="out-of-stock">Out of stock</span>';
                        } else {
                            // Create the quantity input group if stock is available
                            quantityCell.innerHTML = `
                                    <div class="input-group quantity-group">
                                        <button class="btn btn-outline-secondary decrement-btn px-2" type="button">-</button>
                                        <input type="text" class="form-control text-center quantity-input" value="0" style="padding: 0;">
                                        <button class="btn btn-outline-secondary increment-btn px-2" type="button">+</button>
                                    </div>
                                `;
                        }

                        // Append cells to the row
                        row.appendChild(attributeCell);
                        row.appendChild(priceCell);
                        row.appendChild(stockCell);
                        row.appendChild(quantityCell);

                        // Append the row to the table body
                        sizeTableBody.appendChild(row);
                    }
                });
            });

            // setTimeout(() => {
            //     initializeToggle(); // Reinitialize toggle functionality
            // }, 100);
        }

        function showMatchingRows(matchingItems, itemPropertyValue, itemselectedImage) {
            // Hide all rows first
            document.querySelectorAll('.configured-item-row').forEach(row => {
                row.classList.add('d-none');
            });

            // Show only rows that match the criteria
            matchingItems.forEach((item, index) => {
                if (item.Id) { // Ensure Id exists
                    item.Configurators.forEach(configurator => {
                        let matchingRow = Array.from(document.querySelectorAll('.configured-item-row'))
                            .find(row => {
                                let productId = row.getAttribute('data-item-id');
                                return productId && productId === item.Id; // Compare using Id
                            });

                        if (matchingRow) {
                            matchingRow.setAttribute('data-item-property', itemPropertyValue);
                            matchingRow.setAttribute('data-selectedImage', itemselectedImage);

                            // Show only the first 3 items initially
                            if (index < 3) {
                                matchingRow.classList.remove('d-none');
                            } else {
                                matchingRow.classList.add('d-none');
                            }
                        }
                    });
                }
            });

            // Clear any existing "View All" or "See Less" button
            const existingButton = document.querySelector('.view-all-button, .see-less-button');
            if (existingButton) {
                existingButton.remove();
            }

            // Add a "View All" button if there are more than 3 matching items
            if (matchingItems.length > 3) {
                const viewAllButton = document.createElement('button');
                viewAllButton.textContent = 'View All';
                viewAllButton.classList.add('btn', 'btn-link', 'text-decoration-none', 'view-all-button');
                viewAllButton.addEventListener('click', () => {
                    // Show all matching items
                    matchingItems.forEach(item => {
                        if (item.Id) {
                            item.Configurators.forEach(configurator => {
                                let matchingRow = Array.from(document.querySelectorAll(
                                    '.configured-item-row')).find(row => {
                                    let productId = row.getAttribute('data-item-id');
                                    return productId && productId === item.Id;
                                });

                                if (matchingRow) {
                                    matchingRow.classList.remove('d-none');
                                }
                            });
                        }
                    });

                    // Replace "View All" button with "See Less" button
                    viewAllButton.remove();
                    addSeeLessButton(matchingItems);
                });

                // Append the button to a container (you can change the selector as needed)
                document.querySelector('.product-detail-table').appendChild(viewAllButton);
            }
        }

        function addSeeLessButton(matchingItems) {
            const seeLessButton = document.createElement('button');
            seeLessButton.textContent = 'See Less';
            seeLessButton.classList.add('btn', 'btn-link', 'text-decoration-none', 'see-less-button');
            seeLessButton.addEventListener('click', () => {
                // Hide all rows except the first 3
                matchingItems.forEach((item, index) => {
                    if (item.Id) {
                        item.Configurators.forEach(configurator => {
                            let matchingRow = Array.from(document.querySelectorAll(
                                '.configured-item-row')).find(row => {
                                let productId = row.getAttribute('data-item-id');
                                return productId && productId === item.Id;
                            });

                            if (matchingRow) {
                                if (index >= 3) {
                                    matchingRow.classList.add('d-none');
                                }
                            }
                        });
                    }
                });

                // Replace "See Less" button with "View All" button
                seeLessButton.remove();
                showMatchingRows(matchingItems); // Re-run the function to add the "View All" button
            });

            // Append the button to a container (you can change the selector as needed)
            document.querySelector('.product-detail-table').appendChild(seeLessButton);
        }


        document.addEventListener('DOMContentLoaded', function() {

            const firstPropertyOption = document.querySelector('.property-option');
            if (firstPropertyOption) {
                firstPropertyOption.click(); // Simulate a click on the first property
            }


            const sizeTableBody = document.querySelector('.product-detail-table');

            sizeTableBody.addEventListener('click', function(event) {
                if (event.target.classList.contains('increment-btn') || event.target.classList.contains(
                        'decrement-btn')) {
                    const inputGroup = event.target.closest('.input-group');
                    const quantityInput = inputGroup.querySelector('.quantity-input');
                    const stockCell = inputGroup.closest('tr').querySelector(
                        'td:nth-child(3)'); // Assuming stock is in the 3rd column
                    const stockQuantity = parseInt(stockCell.textContent) || 0; // Get stock quantity

                    let currentQuantity = parseInt(quantityInput.value) || 0; // Get current quantity

                    if (event.target.classList.contains('increment-btn')) {
                        if (currentQuantity < stockQuantity) {
                            currentQuantity++; // Increment quantity only if less than stock
                        }
                    } else if (event.target.classList.contains('decrement-btn')) {
                        if (currentQuantity > 0) {
                            currentQuantity--; // Decrement quantity only if greater than 0
                        }
                    }

                    // Update the input field with the new quantity
                    quantityInput.value = currentQuantity;

                    // Highlight the correct range and update the price
                    updateTotalQuantity();
                    updateSummary(); // Call the function to update summary on quantity change
                }
            });

            // Allow manual entry of quantities
            sizeTableBody.addEventListener('input', function(event) {
                if (event.target.classList.contains('quantity-input')) {
                    let currentQuantity = parseInt(event.target.value) || 0;
                    updateTotalQuantity();
                    updateSummary(); // Call the function to update summary on quantity change
                }
            });

            // Function to calculate total quantity and highlight the corresponding range
            function updateTotalQuantity() {
                const quantityInputs = document.querySelectorAll('.quantity-input');
                let totalQuantity = 0;

                // Sum up all quantities
                quantityInputs.forEach(input => {
                    totalQuantity += parseInt(input.value) || 0;
                });

                // Highlight the correct range based on the total quantity
                highlightRange(totalQuantity);

                // Update the total quantity display
                document.querySelector('.total_quantity').textContent = totalQuantity;

                // Enable or disable the "Add to Cart" button based on total quantity and minQuantity
                const addToCartBtn = document.getElementById('addToCartBtn');

                // Check if quantityRanges is defined and has elements
                // Check if minQuantity is a valid number (and greater than 0)
                if (minQuantity > 0) {
                    // Enable or disable the "Add to Cart" button based on total quantity and minQuantity
                    if (totalQuantity >= minQuantity) {
                        addToCartBtn.disabled =
                            false; // Enable if total quantity is greater than or equal to minQuantity
                    } else {
                        addToCartBtn.disabled = true; // Disable if total quantity is less than minQuantity
                    }
                } else {
                    // Fallback if minQuantity is invalid or 0
                    addToCartBtn.disabled = totalQuantity === 0; // Disable if totalQuantity is 0
                }

            }


            // Function to highlight the correct range and update the price
            function highlightRange(currentQuantity) {
                const rangeElements = document.querySelectorAll('.ranges'); // Assuming 3 ranges
                let highlightedPrice = null;

                rangeElements.forEach((rangeElement, index) => {
                    const minQuantity = quantityRanges[index].MinQuantity;
                    const maxQuantity = quantityRanges[index + 1] ? quantityRanges[index + 1].MinQuantity -
                        1 : Infinity;

                    if (currentQuantity >= minQuantity && currentQuantity <= maxQuantity) {
                        rangeElement.classList.add('highlight');
                        highlightedPrice = rangeElement.querySelector('.range-price').getAttribute(
                            'data-price');
                    } else {
                        rangeElement.classList.remove('highlight');
                    }
                });

                // Update all price cells with the highlighted price if it exists
                if (highlightedPrice !== null) {
                    const priceCells = document.querySelectorAll('.product-detail-table td[data-price]');
                    priceCells.forEach(cell => {
                        cell.textContent = highlightedPrice; // Update price in all price cells
                        cell.setAttribute('data-price',
                            highlightedPrice); // Update the data-price attribute
                    });
                }
            }

            // Function to update subtotal price
            function updateSummary() {
                const quantityInputs = document.querySelectorAll('.quantity-input');
                let totalQuantity = 0;
                let subTotalPrice = 0;
                let totalWeight = 0;

                quantityInputs.forEach(input => {
                    const currentQuantity = parseInt(input.value) || 0;
                    const priceCell = input.closest('tr').querySelector('td[data-price]');
                    const itemPrice = parseFloat(priceCell.getAttribute('data-price')) || 0;
                    const weight = parseFloat(input.closest('tr').getAttribute('data-weight')) || 0;

                    totalQuantity += currentQuantity;
                    subTotalPrice += currentQuantity * itemPrice;
                    totalWeight += currentQuantity * weight;
                });

                // Update the total quantity and subtotal price in the respective classes
                document.querySelector('.total_quantity').textContent =
                    totalQuantity; // Update total quantity display
                document.querySelector('.sub_total_price').textContent = subTotalPrice.toFixed(
                    2); // Update subtotal price display
                document.querySelector('.total_price').textContent = (subTotalPrice).toFixed(
                    2); // Update total price (without shipping)
                document.querySelector('.total_weight').textContent = totalWeight.toFixed(
                    2); // Update total weight display
            }

            // Automatically select the first Property option on page load
            function setDefaultSelectedProperty() {
                const firstPropertyOption = document.querySelector('.property-option');
                if (firstPropertyOption) {
                    firstPropertyOption.click(); // Programmatically trigger the click event
                }
            }

            // Call the function to set the default selected Property
            setDefaultSelectedProperty();

            // Initialize the toggle on page load
            initializeToggle();
        });
    </script>

    <!-- Add to Cart -->
    <script>
        const addToCartBtn = document.getElementById('addToCartBtn');
        const addToCartRoute = '{{ route('cart.addToCart') }}';
        const category = '{{ route('category_api') }}';

        function openShippingModal() {
            const shippingCategories = @json($shippingCategories);
            const totalWeight = document.querySelector('.total_weight').textContent.trim() || '0 KG';
            const totalQuantity = document.querySelector('.total_quantity').textContent.trim() || '0';

            document.getElementById('modalWeight').textContent = totalWeight;
            document.getElementById('modalQuantity').textContent = totalQuantity;

            const categorySelect = document.getElementById('category');
            categorySelect.innerHTML = ''; // Clear existing options

            // Add a default option
            const defaultOption = document.createElement('option');
            defaultOption.value = '';
            defaultOption.textContent = 'Select One';
            defaultOption.disabled = true;
            defaultOption.selected = true;
            categorySelect.appendChild(defaultOption);

            // Populate category options
            shippingCategories.forEach(category => {
                const option = document.createElement('option');
                option.value = category.id;
                option.textContent = category.name;
                categorySelect.appendChild(option);
            });

            // Refresh the selectpicker to enable live search
            $('.selectpicker').selectpicker('refresh');

            // Show the modal
            $('#shippingModal').modal('show');
        }


        function categoryInfo() {
            const categoryId = document.getElementById('category').value;


            if (categoryId) {
                // AJAX request to get shipping cost and delivery time
                $.ajax({
                    url: category, // Adjust this URL to match your route
                    type: 'GET',
                    data: {
                        category_id: categoryId
                    }, // Send the selected category ID
                    success: function(response) {
                        // Assuming the response contains the shipping info
                        const {
                            air_cost,
                            air_delivery_time,
                            ship_cost,
                            ship_delivery_time
                        } = response;

                        // Clear the existing rows in the shipping options table
                        const shippingOptionsBody = document.getElementById('shippingOptionsBody');
                        shippingOptionsBody.innerHTML = ''; // Clear previous entries

                        // Create a new row for Air Shipping
                        const airRow = document.createElement('tr');
                        const airRadioCell = document.createElement('td');
                        airRadioCell.innerHTML = `<div class="form-check">
                                <input class="form-check-input" type="radio" name="shippingOption" value="air" id="airShipping" checked>
                                <label class="form-check-label" for="airShipping">Air</label>
                            </div>`;
                        const airCostCell = document.createElement('td');
                        airCostCell.textContent = `${air_cost}/ kg`; // Set the air shipping cost
                        const airDeliveryTimeCell = document.createElement('td');
                        airDeliveryTimeCell.textContent = air_delivery_time; // Set the air delivery time

                        airRow.appendChild(airRadioCell);
                        airRow.appendChild(airCostCell);
                        airRow.appendChild(airDeliveryTimeCell);
                        shippingOptionsBody.appendChild(airRow);

                        // Create a new row for Standard Shipping
                        const shipRow = document.createElement('tr');
                        const shipRadioCell = document.createElement('td');
                        shipRadioCell.innerHTML = `<div class="form-check">
                                <input class="form-check-input" type="radio" name="shippingOption" value="ship" id="shipShipping">
                                <label class="form-check-label" for="shipShipping">Ship</label>
                            </div>`;
                        const shipCostCell = document.createElement('td');
                        shipCostCell.textContent = `${ship_cost}/ kg`; // Set the standard shipping cost
                        const shipDeliveryTimeCell = document.createElement('td');
                        shipDeliveryTimeCell.textContent = ship_delivery_time; // Set the standard delivery time

                        shipRow.appendChild(shipRadioCell);
                        shipRow.appendChild(shipCostCell);
                        shipRow.appendChild(shipDeliveryTimeCell);
                        shippingOptionsBody.appendChild(shipRow);

                        // Add event listeners to calculate shipping cost
                        document.querySelectorAll('input[name="shippingOption"]').forEach((radio) => {
                            radio.addEventListener('change', calculateShippingCost);
                        });

                        // Call to calculate shipping cost on load
                        calculateShippingCost();
                    },
                    error: function(error) {
                        console.error('Error fetching shipping info:', error);
                        alert('Failed to retrieve shipping information. Please try again later.');
                    }
                });
            }
        }

        function calculateShippingCost() {
            const totalQuantity = parseInt(document.getElementById('modalQuantity').textContent.trim());
            const airCost = parseFloat(document.querySelector('input[value="air"]').closest('tr').cells[1].textContent
                .trim().split(' ')[0]) || 0;
            const shipCost = parseFloat(document.querySelector('input[value="ship"]').closest('tr').cells[1].textContent
                .trim().split(' ')[0]) || 0;

            let totalCost = 0;
            let deliveryTime = 0;
            let shippingCost = 0;

            const selectedOption = document.querySelector('input[name="shippingOption"]:checked');

            if (selectedOption) {
                if (selectedOption.value === 'air') {
                    totalCost = totalQuantity * airCost;
                    shippingCost = document.querySelector('input[value="air"]').closest('tr').cells[1].textContent;
                    deliveryTime = document.querySelector('input[value="air"]').closest('tr').cells[2].textContent;
                } else if (selectedOption.value === 'ship') {
                    totalCost = totalQuantity * shipCost;
                    shippingCost = document.querySelector('input[value="ship"]').closest('tr').cells[1].textContent;
                    deliveryTime = document.querySelector('input[value="ship"]').closest('tr').cells[2].textContent;
                }
            }

            document.getElementById('totalShippingCost').textContent = totalCost;
            return {
                totalCost,
                shippingCost,
                deliveryTime
            };
        }

        function addToCart() {
            @if (Auth::check() && Auth::user()->user_type != 'customer')
                AIZ.plugins.notify('warning',
                    "{{ translate('Please Login as a customer to add products to the Cart.') }}");
                return false;
            @endif




            if (checkAddToCartValidity()) {

                const shippingCharge = parseFloat(document.querySelector('.shpping_charge')?.textContent.trim()) || 0;

                if (shippingCharge <= 0) {
                    openShippingModal();
                    return;
                }

                var isMultiple = @json($isMultiple);

                let itemVid, itemPid, itemPropertyValue, selectedImage;
                const cartItems = [];
                const quantityInputs = document.querySelectorAll('.quantity-input');

                // Check for multiple or single item selection
                if (isMultiple == 'yes') {
                    // Multiple case: Get the selected property-option
                    const selectedElement = document.querySelector('.property-option.selected');
                    if (!selectedElement) {
                        alert('Please select an option.');
                        return;
                    }

                    // Extract necessary values from the selected element
                    itemVid = selectedElement.getAttribute('data-vid');
                    itemPid = selectedElement.getAttribute('data-pid');
                    // itemPropertyValue = selectedElement.getAttribute('data-property-value');
                    //  selectedImage = selectedElement.src;
                } else {
                    // Single case: Handle each row independently
                    const selectedElement = document.querySelector('.single-property-option');
                    if (!selectedElement) {
                        alert('Please select an option.');
                        return;
                    }

                    // Extract necessary values from the single property element
                    itemVid = selectedElement.getAttribute('data-vid');
                    itemPid = selectedElement.getAttribute('data-pid');
                    // itemPropertyValue = singleSelectedElement.getAttribute('data-property-value');
                    // selectedImage = singleSelectedElement.getAttribute('data-image-url') || singleSelectedElement.src;
                }

                // Iterate over the quantity inputs
                quantityInputs.forEach(input => {
                    const currentQuantity = parseInt(input.value) || 0;
                    if (currentQuantity > 0) {


                        const row = input.closest('tr');

                        // Extract attribute data from the table rows
                        const attributeName1 = document.querySelector('.attribute-name').innerText.trim();
                        const attributeCell = row.querySelector('td[data-attribute-value]');
                        var attributeValue1 = attributeCell ? attributeCell.getAttribute('data-attribute-value') :
                            null;

                        const priceCell = row.querySelector('td[data-price]');
                        const itemPrice = parseFloat(priceCell.getAttribute('data-price')) || 0;
                        const itemWeight = parseFloat(row.getAttribute('data-weight')) || 0;
                        const minQty = row.getAttribute('data-min-quantity');
                        itemPropertyValue = row.getAttribute('data-item-property');
                        selectedImage = row.getAttribute('data-selectedImage');
                        //console.log(itemPropertyValue);
                        // Check if the row has an image, or fallback to the selected image
                        // const imageCell = row.querySelector('td[data-image-src]');
                        // const rowImage = imageCell ? imageCell.getAttribute('data-image-src') : selectedImage;

                        const stockCell = row.querySelector('td[data-stock]');
                        const stock = stockCell.getAttribute('data-stock');


                        var rowImage = '';
                        let attributes = [];
                        let singlePropertyOption = row.querySelector('.single-property-option');
                        if (singlePropertyOption) {
                            // itemVid = singlePropertyOption.getAttribute('data-vid');
                            // itemPid = singlePropertyOption.getAttribute('data-pid');
                            itemPropertyValue = singlePropertyOption.getAttribute('data-property-value');
                            rowImage = singlePropertyOption.getAttribute('data-image-url') || singlePropertyOption
                                .src;
                            attributeValue1 = singlePropertyOption.getAttribute('data-property-value') || '';

                            attributes.push({
                                name: attributeName1,
                                value: attributeValue1
                            });
                        } else {
                            // Check if the row has an image, or fallback to the selected image
                            const imageCell = row.querySelector('td[data-image-src]');
                            rowImage = imageCell ? imageCell.getAttribute('data-image-src') : selectedImage;

                            // Include both attributes if singlePropertyOption does not exist
                            attributes.push({
                                name: attributeName1,
                                value: attributeValue1
                            }, {
                                name: document.querySelector('.property_name').getAttribute(
                                    'data-property-name').trim(),
                                value: itemPropertyValue
                            });
                        }


                        cartItems.push({
                            itemId: productId,
                            itemTitle: productTitle,
                            quantity: currentQuantity,
                            min_quantity: minQty,
                            itemWeight: itemWeight,
                            price: itemPrice,
                            vid: itemVid,
                            pid: itemPid,
                            attributes: attributes,
                            image: rowImage,
                            stock: stock,
                            ExternalItemUrl: ExternalItemUrl,
                        });
                    }
                });

                if (cartItems.length > 0) {
                    $.ajax({
                        type: "POST",
                        url: addToCartRoute,
                        data: {
                            items: cartItems, // Pass the cart items to the server
                            _token: $('meta[name="csrf-token"]').attr('content') // Get CSRF token from the meta tag
                        },
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(data) {

                            // Handle the success response
                            updateNavCart(data.nav_cart_view, data.cart_count);
                            resetValues();
                            AIZ.plugins.notify('success', data.message);
                            // Display the modal with response content
                            $('#addToCart-modal-body').html(null); // Clear previous modal content
                            $('.c-preloader').hide();
                            $('#addToCart-modal-body').html(data.modal_view); // Set new content
                            $('#modal-size').addClass('modal-lg'); // Optional: Adjust modal size if needed
                            $('#addToCart').modal('show'); // Show the modal

                            // Reinitialize any plugins for dynamic content inside the modal
                            AIZ.extra.plusMinus();
                            AIZ.plugins.slickCarousel();
                        },
                        error: function(error) {
                            alert('There was an error adding items to the cart.');
                        }
                    });
                } else {
                    alert('No items selected.');
                }
            }


        }

        document.getElementById('applyShippingCharge').addEventListener('click', function() {
            const {
                shippingCost,
                deliveryTime
            } = calculateShippingCost();

            // Update shipping charge and delivery time in the DOM
            document.querySelector('.shpping_charge').textContent = shippingCost;
            document.querySelector('.delivery_days').textContent = deliveryTime;

            // Close the modal
            $('#shippingModal').modal('hide');
        });

        function resetValues() {
            // Reset quantity inputs to 0
            const quantityInputs = document.querySelectorAll('.quantity-input');
            quantityInputs.forEach(input => {
                input.value = 0;
            });

            // Reset related values to 0
            document.querySelector('.total_price').textContent = '0.00';
            document.querySelector('.total_quantity').textContent = '0';
            document.querySelector('.sub_total_price').textContent = '0.00';
            // document.querySelector('.shpping_charge').textContent = '0.00';
            // document.querySelector('.delivery_days').textContent = '0';
        }
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const discount = parseFloat('{{ $discount ?? 0 }}');
            const discountType = '{{ $discountType ?? '' }}';

            function calculateDiscountedPrice(price) {
                if (discountType === 'amount') {
                    return Math.max(price - discount, 0);
                } else if (discountType === 'percent') {
                    return Math.max(price - (price * (discount / 100)), 0);
                }
                return price;
            }

            // Initialize Discounted Prices on Page Load
            document.querySelectorAll('[data-price]').forEach((priceElement) => {
                const basePrice = parseFloat(priceElement.getAttribute('data-price'));
                const discountedPrice = calculateDiscountedPrice(basePrice).toFixed(2);

                priceElement.setAttribute('data-base-price', basePrice);
                priceElement.setAttribute('data-price', discountedPrice);

                // Display price with strikethrough if discounted
                if (discountedPrice < basePrice) {
                    priceElement.innerHTML =
                        `<div class='product-price'><del>${basePrice.toFixed(2)}</del> ${discountedPrice}</div>`;
                    console.log(basePrice, discountedPrice);

                } else {
                    priceElement.innerHTML = `<div class='product-price'>${basePrice.toFixed(2)}</div>`;
                }
            });

            // Quantity Change Handler
            document.querySelectorAll('.quantity-input').forEach((input) => {
                input.addEventListener('input', function() {
                    updatePriceOnQuantityChange(this);
                });

                input.closest('.quantity-group').querySelectorAll('.decrement-btn, .increment-btn').forEach(
                    button => {
                        button.addEventListener('click', function() {
                            const inputField = this.closest('.quantity-group').querySelector(
                                '.quantity-input');
                            let quantity = parseInt(inputField.value) || 0;
                            quantity = this.classList.contains('decrement-btn') ? quantity :
                                quantity;
                            if (quantity < 0) quantity = 0;
                            inputField.value = quantity;
                            updatePriceOnQuantityChange(inputField);
                        });
                    });
            });

            function updatePriceOnQuantityChange(input) {
                const quantity = parseInt(input.value) || 0;
                const row = input.closest('tr');
                const priceElement = row.querySelector('[data-price]');
                const basePrice = parseFloat(priceElement.getAttribute('data-base-price'));
                const discountedPrice = calculateDiscountedPrice(basePrice).toFixed(2);
                const totalPrice = (discountedPrice * quantity).toFixed(2);

                // Display price with strikethrough if discounted
                if (discountedPrice < basePrice) {
                    priceElement.innerHTML =
                        `<div class='product-price'><del>${basePrice.toFixed(2)}</del> ${discountedPrice}</div>`;
                    console.log(basePrice, discountedPrice);
                } else {
                    priceElement.innerHTML = `<div class='product-price'>${basePrice.toFixed(2)}</div>`;
                }

                // Update Total Price Display (if exists)
                const totalElement = row.querySelector('.total-price');
                if (totalElement) {
                    totalElement.textContent = totalPrice;
                }
            }
        });
    </script>

    {{-- <script>
        document.addEventListener('DOMContentLoaded', function() {
            const discount = parseFloat('{{ $discount ?? 0 }}');
            const discountType = '{{ $discountType ?? '' }}';

            function calculateDiscountedPrice(price) {
                if (discountType === 'amount') {
                    return Math.max(price - discount, 0);
                } else if (discountType === 'percent') {
                    return Math.max(price - (price * (discount / 100)), 0);
                }
                return price;
            }

            // Function to update prices and apply strikethrough
            function triggerDiscountedPriceUpdate() {
                document.querySelectorAll('[data-price]').forEach((priceElement) => {
                    const basePrice = parseFloat(priceElement.getAttribute('data-base-price') ||
                        priceElement.getAttribute('data-price'));
                    const discountedPrice = calculateDiscountedPrice(basePrice).toFixed(2);

                    // Apply strikethrough and discounted price
                    if (discountedPrice < basePrice) {
                        priceElement.innerHTML =
                            `<div class="product-price">
                            <del class="base-price">${basePrice.toFixed(2)}</del> 
                            <span class="discounted-price">${discountedPrice}</span>
                        </div>`;
                    } else {
                        priceElement.innerHTML =
                            `<div class="product-price">
                            <span class="base-price">${basePrice.toFixed(2)}</span>
                        </div>`;
                    }

                    // Update total price if quantity is present
                    const row = priceElement.closest('tr');
                    if (row) {
                        const quantityInput = row.querySelector('.quantity-input');
                        if (quantityInput) {
                            const quantity = parseInt(quantityInput.value) || 0;
                            const totalElement = row.querySelector('.total-price');
                            if (totalElement) {
                                totalElement.textContent = (discountedPrice * quantity).toFixed(2);
                            }
                        }
                    }
                });
            }

            // Initialize on page load
            triggerDiscountedPriceUpdate();

            // Trigger on quantity change
            document.querySelectorAll('.quantity-input').forEach((input) => {
                input.addEventListener('input', function() {
                    triggerDiscountedPriceUpdate();
                });

                input.closest('.quantity-group')?.querySelectorAll('.decrement-btn, .increment-btn')
                    .forEach(button => {
                        button.addEventListener('click', function() {
                            const inputField = this.closest('.quantity-group').querySelector(
                                '.quantity-input');
                            let quantity = parseInt(inputField.value) || 0;
                            quantity = this.classList.contains('decrement-btn') ? quantity - 1 :
                                quantity + 1;
                            if (quantity < 0) quantity = 0;
                            inputField.value = quantity;
                            triggerDiscountedPriceUpdate();
                        });
                    });
            });
        });
    </script> --}}

@endsection
