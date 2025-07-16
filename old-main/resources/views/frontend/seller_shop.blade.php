@extends('frontend.layouts.app')

{{-- @section('meta_title'){{ $shop->meta_title }}@stop

@section('meta_description'){{ $shop->meta_description }}@stop --}}

@section('meta')
@endsection

@section('content')


    {{-- @php
        $followed_sellers = [];
        if (Auth::check()) {
            $followed_sellers = get_followed_sellers();
        }
    @endphp --}}
    {{-- 
    @if (!isset($type) || $type == 'top-selling' || $type == 'cupons')
        @if ($shop->top_banner)
            <!-- Top Banner -->
            <section class="h-160px h-md-200px h-lg-300px h-xl-100 w-100">
                <img class="d-block lazyload h-100 img-fit" 
                    src="{{ static_asset('assets/img/placeholder-rect.jpg') }}" 
                    data-src="{{ uploaded_asset($shop->top_banner) }}" alt="{{ env('APP_NAME') }} offer">
            </section>
        @endif
    @endif --}}

    <section class="border-top border-bottom" style="background: #fcfcfd;">
        <div class="container">
            <!-- Seller Info -->
            <div class="py-4">
                <div class="row justify-content-md-between align-items-center">
                    <div class="col-lg-5 col-md-6">
                        <div class="d-flex align-items-center">
                            <!-- Shop Logo -->
                            <a href="" class="overflow-hidden size-64px rounded-content"
                                style="border: 1px solid #e5e5e5;
                                box-shadow: 0px 10px 20px rgba(0, 0, 0, 0.06);">
                                <img class="lazyload h-64px  mx-auto" src="{{ static_asset('assets/img/placeholder.jpg') }}"
                                    data-src="{{ uploaded_asset('https://cbu01.alicdn.com/club/upload/pic/user/x/i/n/h/*Ezl3azhUczl57Fg4vF8yMmNLvmNuOFvLOQTT_s.jpeg') }}"
                                    onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';">
                            </a>
                            <div class="ml-3">
                                <h6>Seller Name</h6>
                                <!-- Shop Name & Verification Status -->
                                <a href="" class="text-dark d-block fs-16 fw-700">
                                    {{ $vendorName }}
                                    <span class="ml-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="17.5" height="17.5"
                                            viewBox="0 0 17.5 17.5">
                                            <g id="Group_25616" data-name="Group 25616"
                                                transform="translate(-537.249 -1042.75)">
                                                <path id="Union_5" data-name="Union 5"
                                                    d="M0,8.75A8.75,8.75,0,1,1,8.75,17.5,8.75,8.75,0,0,1,0,8.75Zm.876,0A7.875,7.875,0,1,0,8.75.875,7.883,7.883,0,0,0,.876,8.75Zm.875,0a7,7,0,1,1,7,7A7.008,7.008,0,0,1,1.751,8.751Zm3.73-.907a.789.789,0,0,0,0,1.115l2.23,2.23a.788.788,0,0,0,1.115,0l3.717-3.717a.789.789,0,0,0,0-1.115.788.788,0,0,0-1.115,0l-3.16,3.16L6.6,7.844a.788.788,0,0,0-1.115,0Z"
                                                    transform="translate(537.249 1042.75)" fill="#3490f3" />
                                            </g>
                                        </svg>
                                    </span>
                                </a>
                                <!-- Ratting -->
                                {{-- <div class="rating rating-mr-1 text-dark">
                                    {{ renderStarRating($shop->rating) }}
                                    <span class="opacity-60 fs-12">({{ $shop->num_of_reviews }}
                                        {{ translate('Reviews') }})</span>
                                </div> --}}
                                <!-- Address -->
                                {{-- <div class="location fs-12 opacity-70 text-dark mt-1">{{ $shop->address }}</div> --}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div id="vendor_wise_products">
        <section class="mb-2 mb-md-3 mt-2 mt-md-3" style="overflow-x:hidden">
            <div class="container px-1">
                <div class="row">
                    <div class="col-12">
                        <div class="text-left">
                            <div class="row gutters-5 flex-wrap align-items-center px-2">
                                <div class="col">
                                    <h1 class="fs-20 fs-md-24 fw-700 text-dark">
                                        {{ translate('Product Results') }}
                                    </h1>

                                    <!-- Showing X - Y of Z results message -->
                                    <p class="fs-14 fs-md-16 text-muted mt-2">
                                        Showing
                                        {{ ($products->currentPage() - 1) * $products->perPage() + 1 }}
                                        -
                                        {{ min($products->currentPage() * $products->perPage(), $products->total()) }}
                                        of {{ $products->total() }} for "{{ $vendorName }}"
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Check if products are available -->
                @if ($products->count() > 0)
                    <!-- Products Section -->
                    <div class="row mx-2">
                        @foreach ($products as $product)
                            <div class="col-5-custom position-relative px-0 has-transition hov-animate-outline">
                                <div class="px-1 mb-3 custom-product-box">
                                    @include(
                                        'frontend.' . get_setting('homepage_select') . '.partials.product_box',
                                        ['product' => $product]
                                    )
                                </div>
                            </div>
                        @endforeach
                    </div>
                    {{ $products->links('frontend.inc.custom') }} 
            </div>
        @else
            <!-- No products found message -->
            <div class="row">
                <div class="col-xl-8 mx-auto">
                    <div class="border bg-white p-4">
                        <!-- Empty Product -->
                        <div class="text-center p-3">
                            <i class="las la-frown la-3x opacity-60 mb-3"></i>
                            <h3 class="h4">{{ translate('No products found..!!') }}</h3>
                        </div>
                    </div>
                </div>
            </div>
            @endif
    </div>
    </section>
    </div>



    {{-- <!-- Banner Slider -->
        <section class="mt-3 mb-3">
            <div class="container">
                <div class="aiz-carousel mobile-img-auto-height" data-arrows="true" data-dots="false" data-autoplay="true">
                    @if ($shop->sliders != null)
                        @foreach (explode(',', $shop->sliders) as $key => $slide)
                            <div class="carousel-box w-100 h-140px h-md-300px h-xl-450px">
                                <img class="d-block lazyload h-100 img-fit" src="{{ static_asset('assets/img/placeholder-rect.jpg') }}" data-src="{{ uploaded_asset($slide) }}" alt="{{ $key }} offer">
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>
        </section>
        

        @if ($shop->banner_full_width_1)
            <!-- Banner full width 1 -->
            @foreach (explode(',', $shop->banner_full_width_1) as $key => $banner)
                <section class="container mb-3 mt-3">
                    <div class="w-100">
                        <img class="d-block lazyload h-100 img-fit" 
                            src="{{ static_asset('assets/img/placeholder-rect.jpg') }}" 
                            data-src="{{ uploaded_asset($banner) }}" alt="{{ env('APP_NAME') }} offer">
                    </div>
                </section>
            @endforeach
        @endif

        @if ($shop->banners_half_width)
            <!-- Banner half width -->
            <section class="container  mb-3 mt-3">
                <div class="row gutters-16">
                    @foreach (explode(',', $shop->banners_half_width) as $key => $banner)
                    <div class="col-md-6 mb-3 mb-md-0">
                        <div class="w-100">
                            <img class="d-block lazyload h-100 img-fit" 
                                src="{{ static_asset('assets/img/placeholder-rect.jpg') }}" 
                                data-src="{{ uploaded_asset($banner) }}" alt="{{ env('APP_NAME') }} offer">
                        </div>
                    </div>
                    @endforeach
                </div>
            </section>
        @endif --}}

@endsection

@section('script')
    <script type="text/javascript">
        function filter() {
            $('#search-form').submit();
        }

        function rangefilter(arg) {
            $('input[name=min_price]').val(arg[0]);
            $('input[name=max_price]').val(arg[1]);
            filter();
        }
    </script>
@endsection
