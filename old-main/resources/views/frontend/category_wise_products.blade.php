@extends('frontend.layouts.app')

{{-- @section('meta_title'){{ $shop->meta_title }}@stop

@section('meta_description'){{ $shop->meta_description }}@stop --}}

@section('meta')
    <!-- Schema.org markup for Google+ -->
    {{-- <meta itemprop="name" content="{{ $shop->meta_title }}">
    <meta itemprop="description" content="{{ $shop->meta_description }}">
    <meta itemprop="image" content="{{ uploaded_asset($shop->logo) }}">

    <!-- Twitter Card data -->
    <meta name="twitter:card" content="website">
    <meta name="twitter:site" content="@publisher_handle">
    <meta name="twitter:title" content="{{ $shop->meta_title }}">
    <meta name="twitter:description" content="{{ $shop->meta_description }}">
    <meta name="twitter:creator" content="@author_handle">
    <meta name="twitter:image" content="{{ uploaded_asset($shop->meta_img) }}">

    <!-- Open Graph data -->
    <meta property="og:title" content="{{ $shop->meta_title }}" />
    <meta property="og:type" content="website" />
    <meta property="og:url" content="" />
    <meta property="og:image" content="{{ uploaded_asset($shop->logo) }}" />
    <meta property="og:description" content="{{ $shop->meta_description }}" />
    <meta property="og:site_name" content="{{ $vendorName }}" /> --}}
@endsection

@section('content')


    <section class="border-top border-bottom" style="background: #fcfcfd;">
        <div class="container">
            <div class="py-4">
                <div class="row justify-content-center align-items-center">
                    <div class="col-lg-12 text-center">
                        <div class="align-items-center">
                            <h2 class="text-dark"> {{ $subCategoryName }}</h2>
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
                                        of {{ $products->total() }} for "{{ $subCategoryName }}"
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
                                    @include('frontend.'.get_setting('homepage_select').'.partials.product_box',['product' => $product])
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
                                    <h3 class="h4">{{ translate('No products found for ') }} "{{ $subCategoryName }}"</h3>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
        </section>
    </div>
        
    {{-- <div class="row">
        <div class="col-xl-8 mx-auto">
            <div class="border bg-white p-4">
                <!-- Empty cart -->
                <div class="text-center p-3">
                    <i class="las la-frown la-3x opacity-60 mb-3"></i>
                    <h3 class="h4 fw-700">{{translate('Your Cart is empty')}}</h3>
                </div>
            </div>
        </div>
    </div> --}}
        
        {{-- <!-- Banner Slider -->
        <section class="mt-3 mb-3">
            <div class="container">
                <div class="aiz-carousel mobile-img-auto-height" data-arrows="true" data-dots="false" data-autoplay="true">
                    @if ($shop->sliders != null)
                        @foreach (explode(',',$shop->sliders) as $key => $slide)
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
            @foreach (explode(',',$shop->banner_full_width_1) as $key => $banner)
                <section class="container mb-3 mt-3">
                    <div class="w-100">
                        <img class="d-block lazyload h-100 img-fit" 
                            src="{{ static_asset('assets/img/placeholder-rect.jpg') }}" 
                            data-src="{{ uploaded_asset($banner) }}" alt="{{ env('APP_NAME') }} offer">
                    </div>
                </section>
            @endforeach
        @endif

        @if($shop->banners_half_width)
            <!-- Banner half width -->
            <section class="container  mb-3 mt-3">
                <div class="row gutters-16">
                    @foreach (explode(',',$shop->banners_half_width) as $key => $banner)
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
        function filter(){
            $('#search-form').submit();
        }
        
        function rangefilter(arg){
            $('input[name=min_price]').val(arg[0]);
            $('input[name=max_price]').val(arg[1]);
            filter();
        }
    </script>
@endsection
