<style>
    .view-seller a {
        white-space: nowrap;
        /* Prevent text from wrapping */
        overflow: hidden;
        text-overflow: ellipsis;
        display: inline-block;
        max-width: 100%;
        /* Adjust if needed */
    }
</style>

{{-- Update Design --}}
<div class="aiz-card-box h-auto bg-white hov-scale-img" style="border: 1px solid #e7e7e7;">
    <div class="position-relative h-120px h-sm-170px h-md-200px img-fit overflow-hidden">
        <!-- Image -->
        <a href="{{ route('product', $product['Id']) }}" target="_blank" class="d-block h-100">
            <img class="lazyload mx-auto img-fit has-transition"
                src="{{ $product['MainPictureUrl'] ?? static_asset('assets/img/placeholder.jpg') }}" alt=""
                title="" onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';">
        </a>
        <!-- wishlist icons -->
        <div class="absolute-top-right aiz-p-hov-icon">
            <a href="javascript:void(0)" class="hov-svg-white"
                onclick="addToWishList('{{ $product['Id'] }}', '{{ $product['Title'] }}', '{{ $product['MainPictureUrl'] }}')"
                data-toggle="tooltip" data-title="{{ translate('Add to wishlist') }}" data-placement="left">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="14.4" viewBox="0 0 16 14.4">
                    <g id="_51a3dbe0e593ba390ac13cba118295e4" data-name="51a3dbe0e593ba390ac13cba118295e4"
                        transform="translate(-3.05 -4.178)">
                        <path id="Path_32649" data-name="Path 32649"
                            d="M11.3,5.507l-.247.246L10.8,5.506A4.538,4.538,0,1,0,4.38,11.919l.247.247,6.422,6.412,6.422-6.412.247-.247A4.538,4.538,0,1,0,11.3,5.507Z"
                            transform="translate(0 0)" fill="{{ get_setting('base_color', '#ff0000') }}" />
                        <path id="Path_32650" data-name="Path 32650"
                            d="M11.3,5.507l-.247.246L10.8,5.506A4.538,4.538,0,1,0,4.38,11.919l.247.247,6.422,6.412,6.422-6.412.247-.247A4.538,4.538,0,1,0,11.3,5.507Z"
                            transform="translate(0 0)" fill="{{ get_setting('base_color', '#ff0000') }}" />
                    </g>
                </svg>
            </a>
        </div>
    </div>

    <div class="p-2 p-md-3 text-left">
        <!-- Product name -->
        <h3 class="fw-400 fs-16 text-truncate-2 lh-1-4 mb-0">
            <a href="{{ route('product', $product['Id']) }}" target="_blank" class="d-block text-reset hov-text-danger"
                title="{{ $product['Title'] ?? 'No Title' }}">{{ $product['Title'] ?? 'No Title' }}</a>
        </h3>

        <div class="fs-14 d-flex justify-content-between align-items-center mt-2">
            <!-- Price -->
            <div>
                @php
                    // Ensure the original price is numeric and exists
                    $originalPrice = (float) $product['Price']['ConvertedPriceWithoutSign'];
                    $discountedPrice = null;
                    // dd($globalCoupon);
                    // Check if global coupon is applied and price is valid
                    if (
                        $originalPrice !== null &&
                        isset($globalCoupon) &&
                        $globalCoupon->type === 'all_product_base' &&
                        $globalCoupon->status == 1
                    ) {
                        // Decode coupon details safely
                        $couponDetails = json_decode($globalCoupon->details, true) ?? [];
                        // dd($globalCoupon->type);

                        // Ensure discount values are numeric
                        $couponDiscount =
                            isset($globalCoupon->discount) && is_numeric($globalCoupon->discount)
                                ? (float) $globalCoupon->discount
                                : 0;
                        $maxDiscount =
                            isset($couponDetails['max_discount']) && is_numeric($couponDetails['max_discount'])
                                ? (float) $couponDetails['max_discount']
                                : 0;
                        $code = $globalCoupon->code;

                        // Calculate discount
                        $discount =
                            $globalCoupon->discount_type === 'percent'
                                ? $originalPrice * ($couponDiscount / 100) // Percentage discount
                                : $couponDiscount; // Flat discount

                        // Apply maximum discount limit
                        $discount = min($discount, $maxDiscount);
                        // Calculate final price (prevent negative prices)
                        $discountedPrice = max($originalPrice - $discount, 0);

                        // Calculate discount percentage
                        $discountPercentage = $originalPrice > 0 ? round(($discount / $originalPrice) * 100) : 0;
                    }
                @endphp

                <div class="d-flex flex-column">
                    <div>
                        <span class="fw-bold text-danger fs-5">
                            ৳
                            {{ isset($discountedPrice) ? number_format($discountedPrice, 2) : ($originalPrice ? number_format($originalPrice, 2) : 'N/A') }}
                        </span>

                        @if (isset($discountedPrice) && $discountedPrice < $originalPrice)
                            <span class="text-muted ms-2 fs-6"><del>৳
                                    {{ number_format($originalPrice, 2) }}</del></span>
                            <span class="badge bg-primary text-white ms-2">{{ $discountPercentage }}% Off</span>
                        @endif
                    </div>

                </div>

            </div>

            <!-- View Seller Button -->
            {{-- @if (!empty($product['VendorId']) && !empty($product['VendorName']))
                <div class="d-block d-md-none">
                    <a class="fs-14 text-danger" target="_blank"
                        href="{{ route('shop.visit', ['vendorId' => $product['VendorId'], 'vendorName' => $product['VendorName']]) }}">
                        View Seller
                    </a>
                </div>
            @endif --}}

            <!-- View Seller Button with Important -->
            {{-- @if (!empty($product['VendorId']) && !empty($product['VendorName']))
                <div class="d-block d-md-none view-seller"
                    style="max-width: 100%; white-space: nowrap; overflow: hidden; text-overflow: ellipsis !important;">
                    <a class="fs-14 text-danger" target="_blank"
                        href="{{ route('shop.visit', ['vendorId' => $product['VendorId'], 'vendorName' => $product['VendorName']]) }}">
                        {{ $product['VendorName'] }}
                    </a>
                </div>
            @endif --}}




        </div>
    </div>

    <!-- Card footer with seller name and view button -->
   
</div>
