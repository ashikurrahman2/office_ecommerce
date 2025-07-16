@extends('frontend.layouts.app')
@section('content')
        @php $lang = get_system_language()->code;  @endphp
        <!-- Sliders -->
        <style>
            .home-banner-area .col-lg-3.d-lg-block video {
                height: 100%;
                /* Ensure the video fills the entire column */
                max-height: 280px;
                /* Match the slider height */
                object-fit: cover;
                /* Maintain aspect ratio without stretching */
            }


            /* Ensure the container is positioned for absolute children */
            .advertisement-box {
                position: relative;
                overflow: hidden;
            }

            /* Make sure image doesn't cover the button */
            .advertisement-box img {
                display: block;
                width: 100%;
                height: auto;
                z-index: 1;
                position: relative;
            }

            /* Floating button container */
            .ad-btn-link {
                position: absolute;
                top: 50%;
                left: 10px;
                transform: translateY(-50%);
                z-index: 2;
                /* Bring above the image */
            }

            /* Perfect small circle button */
            .small-button {
                width: 22px;
                height: 22px;
                border-radius: 50%;
                padding: 0;
                font-size: 12px;
                line-height: 1;
                display: flex;
                align-items: center;
                justify-content: center;
            }

            /* Custom Styles for HOT Keywords Button */
            .hot-keywords button {
                color: var(--primary)
                /* Pink text */
                border: 1px solid #d3d3d3;
                /* Light gray border */
                background-color: transparent;
                padding: 2px 5px;
                font-size: 14px;
                text-align: left;
                display: inline-block;
                width: auto;
                transition: background-color 0.2s, color 0.2s;
            }

            .hot-keywords button:hover {
                background-color: #fce4ec;
                /* Light pink background on hover */
                color: var(--secondary);
                /* Darker pink text on hover */
                border-color: #d3d3d3;
            }



            .hot-keywords .text-muted {
                color: #6c757d;
                /* Gray color for shortcut text */
                font-size: 16px;
                font-weight: 500;
            }

            /* Reduced Gap between Heading and Shortcut */
            .hot-keywords h5 {
                color: var(--primary);
                font-size: 18px;
                font-weight: 700;
                margin-bottom: 0;
                display: inline-block;
            }

            .hot-keywords span {
                color: #6c757d;
                font-size: 16px;
                font-weight: 500;
                display: inline-block;
                vertical-align: middle;
                margin: 0;
            }

            h5 {
                margin-bottom: 0.25rem;
                /* smaller bottom margin */
            }

            .new-keywords h5 {
                color: #4380f1;
                /* Pink text for headings */
                font-size: 18px;
                font-weight: 700;
                margin-bottom: 0;
                display: inline-block;
            }

            .new-keywords span {
                color: #6c757d;
                /* Gray color for shortcut text */
                font-size: 16px;
                font-weight: 500;
                margin-left: 8px;
                /* Reduced gap */
                display: inline-block;
                vertical-align: middle;
            }

            span {
                margin-left: 0.25rem;
                /* slight indent */
                font-size: 0.9rem;
                /* color: #6c757d; */
                /* gray */
            }

            .buttontag button {
                color: var(--primary);
                /* Light gray border */
                background-color: transparent;
                padding: 2px 5px;
                font-size: 14px;
                text-align: left;
                display: inline-block;
                width: auto;
                transition: background-color 0.2s, color 0.2s;
                border: 1px solid var(--primary);
            }

            .buttontag button:hover {
                background-color: var(--primary);
                color: #ffffff;

            }
            .buttontag h4 {
                font-size: 1rem;
            }

            .hr-pink {
                border: none;
                height: 2px;
                background-color: var(--primary);
                margin-top: 0;
                margin-bottom: 1.5rem;
            }

            .hr-blue {
                border: none;
                height: 2px;
                background-color: var(--primary);
                margin-top: 0;
                margin-bottom: 1.5rem;
            }

            .your-card-wrapper,
            .your-card-wrapper .card,
            .your-card-wrapper .card-body {
                border: none !important;
                box-shadow: none !important;
                background: transparent !important;
            }
        </style>


        <div class="home-banner-area mt-2" style="overflow-x:hidden">
            <div class="container">
                <div class="row">

                    {{-- <!-- Sidebar image (Visible on desktop only) -->
                    <div class="col-lg-3 d-none d-lg-block">
                        <iframe class="h-md-280px" width="100%" src="https://drive.google.com/file/d/1qjKkps2fZta9j8b7DqHsgFoGQ_8j1cOS/preview" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen></iframe>
                    </div> --}}

                    <!-- Sidebar video (Visible on desktop only) -->
                    {{--  <div class="col-lg-3 d-none d-lg-block">
                        <iframe class="h-md-280px" width="100%"
                                src="https://drive.google.com/file/d/{{ $googleDriveFileId }}/preview"
                                frameborder="0" allow="autoplay; encrypted-media" allowfullscreen></iframe>
                    </div> 
                    <div class="col-lg-3 d-none d-lg-block">
                        <video class="h-md-280px w-100" autoplay muted loop>
                            <source src="{{ static_asset('assets/video/CPLExpressbdBannerside.mp4') }}" type="video/mp4">
                            Your browser does not support the video tag.
                        </video>
                    </div>
                    --}}
                    <div class="col-lg-3 d-none d-lg-block  rounded-lg-custom pl-3" bis_skin_checked="1">
                        <img class="img-fluid d-block w-100 rounded-lg-custom rounded" src="public/uploads/all/OuPFuFjrH0C2Tgqs3m8LAz1Uosn49XD3TAtKxzgN.jpg" style="height: 98%">
                    </div>

                    <!-- Sliders (Always visible) -->
                    <div class="col-lg-9 col-md-12 col-sm-12">
                        <div class="d-flex">
                            <!-- Sliders -->
                            <div class="h-100 w-100">
                                @if (get_setting('home_slider_images') != null)
                                    <div class="aiz-carousel dots-inside-bottom" data-autoplay="true" data-infinite="true"
                                        data-arrows="true">
                                        @php
                                            $decoded_slider_images = json_decode(
                                                get_setting('home_slider_images', null, $lang),
                                                true,
                                            );
                                            $sliders = get_slider_images($decoded_slider_images);
                                        @endphp
                                        @foreach ($sliders as $key => $slider)
                                            <div class="carousel-box">
                                                <a href="{{ json_decode(get_setting('home_slider_links'), true)[$key] }}">
                                                    <!-- Image -->
                                                    <img class=" rounded img-fluid d-block w-100 mw-100 overflow-hidden h-100"
                                                        src="{{ $slider ? my_asset($slider->file_name) : static_asset('assets/img/placeholder.jpg') }}"
                                                        alt="{{ env('APP_NAME') }} promo"
                                                        onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder-rect.jpg') }}';">
                                                </a>
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>


                </div>
            </div>
        </div>

        <!-- Main Row for Top Categories, Entrepreneur Row, and Advertisement -->
        <div class="mb-2 mb-md-3 mt-2 mt-md-3">
            <section class="container">
                <div class="row">
                    <!-- Left Column (Top Categories + Featured Products) -->
                    <div class="col-md-10">
                        <!-- Top Categories and Entrepreneur Row Section -->
                        <div class="mb-3">
                            <section class="container">
                                <div class="row">
                                    <!-- First Top Categories Section -->
                                    <div class="col-12 col-md-6 p-0 ">
                                        <div id="top_category_1" class="h-100 rounded border p-3">
                                            <div class="pb-2">
                                                <h3 class="fs-16 fs-md-20 fw-700 mb-2 mb-sm-0">
                                                    <span class="">{{ translate('Top Categories') }}</span>
                                                </h3>
                                            </div>

                                            {{-- <div class="rounded border" style="border: 1px solid #e0e0e0;"> --}}
                                            <!-- Light Border Color -->
                                            <div class="c-scrollbar-light overflow-hidden ">
                                                <div class="h-100 d-flex flex-column justify-content-center">
                                                    <div class="top-category-slick aiz-carousel" data-items="4"
                                                        data-xxl-items="4" data-xl-items="4" data-lg-items="4" data-md-items="5"
                                                        data-sm-items="3" data-xs-items="3" data-arrows="true" data-dots="false"
                                                        data-autoplay="true" data-infinite="true" data-autoplay-speed="1500">
                                                        @foreach (get_categories() as $key => $category)
                                                            <div class="carousel-box h-100 p-2 text-center">
                                                                <a href="{{ route('category_wise_products', $category['ExternalId']) }}?name={{ urlencode($category['Name']) }}"
                                                                    class="h-100 overflow-hidden hov-scale-img mx-auto"
                                                                    title="{{ ucwords($category->CustomName) }}">
                                                                    <!-- Circular Image -->
                                                                    <div class="img rounded-circle overflow-hidden mx-auto"
                                                                        style="width: 70px; height: 70px; background: #f9f9f9;">
                                                                        <img class="lazyload img-fit m-auto has-transition rounded-circle"
                                                                            src="{{ uploaded_asset($category->banner) ?? static_asset('assets/img/placeholder.jpg') }}"
                                                                            alt="{{ $category->CustomName }}"
                                                                            style="width: 100%; height: 100%; object-fit: cover;">
                                                                    </div>
                                                                    <!-- Title -->
                                                                    <div class="fs-14 fs-sm-12 mt-2 text-center"
                                                                        style="max-height: 38px; overflow: hidden;">
                                                                        <span
                                                                            class="d-block text-dark fw-700 text-break">{{ $category->CustomName ?? '..' }}</span>
                                                                    </div>
                                                                </a>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            </div>
                                            {{-- </div> --}}
                                        </div>
                                    </div>

<!-- Second Section (Become an Entrepreneur) -->
<div class="col-12 col-md-6 px-0 mt-2 mt-md-0 px-md-1">
    <div id="top_category_2" class="h-100 rounded border p-3 position-relative" style="min-height: 200px; max-height: 250px;">
        <div class="pb-2">
            <h3 class="fs-16 fs-md-18 fw-700 mb-2 mb-sm-0 text-center">
                <span>{{ translate('Become an Entrepreneur') }}</span>
            </h3>
        </div>

        <div class="c-scrollbar-light overflow-hidden">
            <div class="h-100 d-flex flex-column justify-content-center">
                <div class="top-category-slick aiz-carousel" data-items="4"
                    data-xxl-items="4" data-xl-items="4" data-lg-items="3" data-md-items="3"
                    data-sm-items="2" data-xs-items="2" data-arrows="false" data-dots="false"
                    data-autoplay="true" data-infinite="true" data-autoplay-speed="1500">
                    @foreach (get_categories() as $key => $category)
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Floating Text and Button - Responsive -->
        <div class="position-absolute text-center w-100 px-2"
            style="top: 50%; left: 50%; transform: translate(-50%, -50%);">
            <p class="fs-14 fs-md-14 mb-2 text-dark fw-bold">Click2Import is here to stand by your side</p>
            <a href="#learn-more" class="btn btn-primary px-3 px-md-4 py-2 fs-12 fs-md-14 fw-bold mt-2">
                Click Here to Learn More
            </a>
        </div>
    </div>
</div>

                                </div>
                            </section>
                        </div>

                        <!-- Featured Products Section -->
                        <div id="section_featured">
                            <section class="mb-2 mb-md-3 mt-2 mt-md-3">
                                <div class="container pl-0">
                                    <div class="d-flex mb-2 mb-md-3 align-items-baseline justify-content-between">
                                        <h3 class="fs-16 fs-md-20 fw-700 mb-2 mb-sm-0 d-flex align-items-center">
                                            <!-- Pink shopping bag icon -->
                                            <i class="las la-shopping-bag mr-2" style="color: #5E00B9; font-size: 1.4em;"></i>
                                            <span>{{ translate("Today's Shopping Suggestion") }}</span>
                                        </h3>
                                        <div class="d-flex">
                                            <a type="button" class="arrow-prev slide-arrow link-disable text-secondary mr-2"
                                                onclick="clickToSlide('slick-prev','section_featured')">
                                                <i class="las la-angle-left fs-20 fw-600"></i>
                                            </a>
                                            <a type="button" class="arrow-next slide-arrow text-secondary ml-2"
                                                onclick="clickToSlide('slick-next','section_featured')">
                                                <i class="las la-angle-right fs-20 fw-600"></i>
                                            </a>
                                        </div>
                                    </div>
                                    <div class="px-sm-3">
                                        <div class="aiz-carousel sm-gutters-16 arrow-none" data-items="5" data-xl-items="5"
                                            data-lg-items="4" data-md-items="3" data-sm-items="2" data-xs-items="2"
                                            data-arrows='true' data-infinite='false'>
                                            @foreach ($featuredProducts as $key => $product)
                                                <div class=" position-relative px-0 has-transition">
                                                    <div class="pl-0 ml-1 mb-3 custom-product-box">
                                                        @include(
                                                            'frontend.' .
                                                            get_setting('homepage_select') .
                                                            '.partials.product_box',
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

                        <!-- Machinery & Equipment Section -->
                        <section class="mb-4 mt-3">
                            <div id="category_wise_products">
                                @foreach ($categoriesWithProducts as $category_key => $category)
                                    @php
                                        $categoryName = $category['id'] === 'otc-10' ? 'Bag\'s' : '';
                                    @endphp
                                    <section class="mb-2 mb-md-3 mt-2 mt-md-3">
                                        <div class="container px-1">
                                            <!-- Top Section -->
                                            <div
                                                class="mb-3 mb-md-4 border-bottom align-items-baseline justify-content-between">
                                                <!-- Title -->
                                                <h3 class="fs-16 fs-md-20 fw-700 mb-2 mb-sm-0 pb-3">
                                                    <span class="">{{ $categoryName}}</span>
                                                </h3>
                                                <!-- Links -->
                                                <div class="justify-content-between align-items-center mb-3">
                                                    <form
                                                        action="{{ route('category_wise_products', ['category' => $category['id']]) }}"
                                                        method="POST">
                                                        @csrf
                                                        <input type="hidden" name="subcategoryId"
                                                            value="{{ $category['subcategoryId'] }}">
                                                        <button class="btn btn-sm btn-outline-primary active me-2">Brakes</button>
                                                        <button class="btn btn-sm btn-outline-primary me-2">Molds</button>
                                                        <button class="btn btn-sm btn-outline-primary">Connectors</button>
                                                    </form>
                                                </div>
                                            </div>

                                            <!-- Products Section -->
                                            <div class="row px-2">
                                                @php $productCount = 0; @endphp
                                                @foreach ($category['items'] as $product)
                                                    @if ($productCount < 12)
                                                        <!-- 2 rows * 6 products each = 12 -->
                                                        <div
                                                            class="col-6 col-md-2 position-relative px-0 has-transition hov-animate-outline">
                                                            <div class="px-1 mb-3 custom-product-box">
                                                                @include(
                                                                    'frontend.' .
                                                                    get_setting('homepage_select') .
                                                                    '.partials.product_box',
                                                                    ['product' => $product]
                                                                )
                                                            </div>
                                                        </div>
                                                        @php $productCount++; @endphp
                                                    @else
                                                        @break
                                                    @endif
                                                @endforeach
                                            </div>
                                        </div>
                                    </section>
                                @endforeach
                            </div>
                        </section>



                    </div>



                    <!-- Right Column for Advertisement Pictures -->
                    <div class="col-md-2 d-none d-md-block">
                        @php
                            $homeBanner3Images = get_setting('home_banner3_images', null, $lang);
                            $homeBanner3Links = json_decode(get_setting('home_banner3_links'), true);
                        @endphp

                        @if ($homeBanner3Images != null)
                            @php $banner_3_images = json_decode($homeBanner3Images); @endphp

                            <div class="advertisement-container">
                                @foreach ($banner_3_images as $key => $image)
                                    <div class="advertisement-box mb-3 position-relative">
                                        <a href="{{ $homeBanner3Links[$key] ?? '#' }}" class="d-block position-relative">
                                            <img src="{{ static_asset('assets/img/placeholder-rect.jpg') }}"
                                                data-src="{{ uploaded_asset($image) }}"
                                                alt="Advertisement {{ $key + 1 }}"
                                                class="img-fluid w-100 d-block lazyload"
                                                onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder-rect.jpg') }}';">

                                            <!-- Floating Button on the Left Center -->
                                            <span class="ad-btn-link">
                                                <button class="btn btn-primary small-button">
                                                    <i class="las la-angle-right"></i>
                                                </button>
                                            </span>
                                        </a>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>

                </div>
            </section>
        </div>

        <!-- HOT TREND Section -->
        <section class="mb-4 mt-3">
            <div class="container">
                <div class="card">

                    <h3 class="fs-20 fw-700 mb-3">HOT TREND</h3>
                    <hr class="mb-4 hr-pink">


                    <div class="row">
                        <!-- Column 1: Keywords -->
                        <div class="col-2 d-none d-lg-flex flex-column justify-content-between hot-keywords" bis_skin_checked="1">
                            <!-- Heading Rows with Shortcut at the Top -->
                            <div class="hot-keywords">
                                <div class="mb-2">
                                    <h5 class="fw-700 mb-1">Trending Now</h5>
                                    <span class="text-muted fw-500 ms-1">Shortcut ></span>
                                </div>

                                <div class="mb-3">
                                    <h5 class=" fw-700 mb-1">New Arrivals</h5>
                                    <span class="text-muted fw-500 ms-1">Shortcut ></span>
                                </div>
                            </div>
                         <!-- Hot Keywords Section at the Bottom -->
                                <div class="buttontag">
                                    <h4 class="fw-700 mb-2">HOT Keywords:</h4>
                                    <div class="flex-column gap-1 mb-2 text-start">
                                        <button class="btn text-start buttontag">#one piece</button>
                                    </div>
                                    <div class="flex-column gap-1 mb-2 text-start">
                                        <button class="btn text-start buttontag">#blouse/shirt</sbutton>
                                    </div>
                                    <div class="flex-column gap-1 mb-2 text-start">
                                        <button class="btn text-start buttontag">#t-shirt</button>
                                    </div>
                                    <div class="flex-column gap-1 mb-2 text-start">
                                        <button class="btn text-start buttontag">#skirt</button>
                                    </div>
                                    <div class="flex-column gap-1 mb-2 text-start">
                                        <button class="btn text-start buttontag">#sneakers</button>
                                    </div>
                                    <div class="flex-column gap-1 mb-2 text-start">
                                        <button class="btn text-start buttontag">#bag</button>
                                    </div>
                                </div>
                            </div>

                        <!-- Column 2: Featured Product with Slider, wrapped inside card body -->
                        <div class="col-3 d-none d-md-block">
                            <div class="card-body text-center"> <!-- Card body wrapper -->
                                <div class="c-scrollbar-light overflow-hidden">
                                    <div class="d-flex flex-column justify-content-center">
                                        <div class="top-category-slick aiz-carousel" data-items="1" data-dots="true"
                                            data-autoplay="true" data-infinite="true" data-autoplay-speed="1500">
                                            @foreach (get_categories() as $key => $category)
                                                <div class="carousel-box text-center">
                                                    <a href="{{ route('category_wise_products', $category['ExternalId']) }}?name={{ urlencode($category['Name']) }}"
                                                        class="overflow-hidden hov-scale-img mx-auto"
                                                        title="{{ ucwords($category->CustomName) }}">
                                                        <div class="carousel-boxtext-center">
                                                            <a href="{{ route('category_wise_products', $category['ExternalId']) }}?name={{ urlencode($category['Name']) }}" class="h-100 overflow-hidden hov-scale-img mx-auto"
                                                                title="{{ ucwords($category->CustomName) }}">         
                                                                
                                                                <img src="{{ static_asset('uploads/all/FFBhQHIvPrI4XjOhyUTifzndEs4BsuU0uEYIYdbs.jpg') }}" alt="{{ env('APP_NAME') }} promo" style="height: 37.7rem; width: 100%; object-fit: cover;">
                                                            </a>
                                                        
                                                        </div>

                                                    </a>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>

                                    <!-- Pink Button below image, same width as image -->
                                    <button class="btn btn-pink text-white w-100 mt-3" style="height: 40px; font-size: 14px;">
                                        Explore More
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Column 3: Product List with Slider -->

                        <div class="col-12 col-md-7">
                            <div class="aiz-carousel product-slider" data-items="1" data-dots="true" data-autoplay="false"
                                data-infinite="false" data-autoplay-speed="1500">
                                @foreach ($categoriesWithProducts as $category_key => $category)
                                    <div class="custom-product-box">
                                        <div class="row">
                                            @php $productCount = 0; @endphp
                                            @foreach ($category['items'] as $product)
                                                @if ($productCount < 6)
                                                    <!-- 2 rows * 3 products each = 6  top-->
                                                        <div class="col-6 col-md-4 position-relative has-transition p">
                                                            <div class="px-1 mb-3 custom-product-box"
                                                                style="max-width: 100%;">
                                                                @include(
                                                                    'frontend.' .
                                                                    get_setting('homepage_select') .
                                                                    '.partials.product_box',
                                                                    ['product' => $product]
                                                                )
                                                            </div>
                                                        </div>
                                                    @php $productCount++; @endphp
                                                @endif
                                            @endforeach
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>


                    </div>

                    <div class="mt-4">
                        <hr class="mb-4 hr-blue">

                        <div class="row">
                            <!-- Column 2: Keywords -->
                            <div class="col-2 d-none d-lg-flex flex-column justify-content-between hot-keywords">
                            <!-- Heading Rows with Shortcut at the Bottom -->
                            <div class="hot-keywords">
                                <div class="mb-2">
                                    <hlass="fw-700 mb-1">Trending Now</hlass=>
                                    <span class="text-muted fw-500 ms-1">Shortcut ></span>
                                </div>

                                <div class="mb-3">
                                    <h5 class=" fw-700 mb-1">New Arrivals</h5>
                                    <span class="text-muted fw-500 ms-1">Shortcut ></span>
                                </div>
                            </div>

                                <!-- Hot Keywords Section at the Bottom -->
                                <div class="buttontag">
                                    <h4 class="fw-700 mb-2">HOT Keywords:</h4>
                                  <div class="flex-column gap-1 mb-2 text-start">
                                        <button class="btn text-start buttontag">#bag</button>
                                    </div>
                                    <div class="flex-column gap-1 mb-2 text-start">
                                        <button class="btn text-start buttontag">#shoes</button>
                                    </div>
                                    <div class="flex-column gap-1 mb-2 text-start">
                                        <button class="btn text-start buttontag">#watch</button>
                                    </div>
                                    <div class="flex-column gap-1 mb-2 text-start">
                                        <button class="btn text-start buttontag">#onepiece</button>
                                    </div>
                                    <div class="flex-column gap-1 mb-2 text-start">
                                        <button class="btn text-start buttontag">#blouse/shirt</button>
                                    </div>
                                </div>
                            </div>
                            

                            <!-- Column 2: Featured Product with Slider, wrapped inside card body -->
                        <div class="col-3 d-none d-md-block">
                            <div class="card-body text-center"> <!-- Card body wrapper -->
                                <div class="c-scrollbar-light overflow-hidden">
                                    <div class="h-100 d-flex flex-column justify-content-center">
                                        <div class="top-category-slick aiz-carousel" data-items="1" data-dots="true"
                                            data-autoplay="true" data-infinite="true" data-autoplay-speed="1500">
                                            @foreach (get_categories() as $key => $category)
                                                <div class="carousel-box h-100 text-center">
                                                    <a href="{{ route('category_wise_products', $category['ExternalId']) }}?name={{ urlencode($category['Name']) }}"
                                                        class="h-100 overflow-hidden hov-scale-img mx-auto"
                                                        title="{{ ucwords($category->CustomName) }}">
                                                        <div class="carousel-box h-100 text-center">
                                                            <a href="{{ route('category_wise_products', $category['ExternalId']) }}?name={{ urlencode($category['Name']) }}" class="h-100 overflow-hidden hov-scale-img mx-auto"
                                                                title="{{ ucwords($category->CustomName) }}">
                                                                <img src="public/uploads/all/80GQwXrUIpSgUgqHSnekxJgQqw1Uf5ktcuXPx7mH.jpg" alt="{{ ucwords($category->CustomName) }}" style="height: 37.7rem; width: 100%; object-fit: cover;">
                                                            </a>
                                                        </div>

                                                    </a>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>

                                    <!-- Pink Button below image, same width as image -->
                                    <button class="btn btn-pink text-white w-100 mt-3" style="height: 40px; font-size: 14px;">
                                        Explore More
                                    </button>
                                </div>
                            </div>
                        </div>

                            <!-- Column 3: Product List with Slider -->
                            <div class="col-12 col-md-7">
                                <div class="aiz-carousel product-slider" data-items="1" data-dots="true"
                                    data-autoplay="false" data-infinite="false" data-autoplay-speed="1500">
                                    @foreach ($categoriesWithProducts as $category_key => $category)
                                        <div class="carousel-box">
                                            <div class="row gx-2 gy-2">
                                                @php $productCount = 0; @endphp
                                                @foreach ($category['items'] as $product)
                                                    @if ($productCount < 6)
                                                        <!-- 2 rows * 3 products each = 6 bottom -->
                                                        <div class="col-6 col-md-4 position-relative has-transition p">
                                                            <div class="px-1 mb-3 custom-product-box"
                                                                style="max-width: 100%;">
                                                                @include(
                                                                    'frontend.' .
                                                                    get_setting('homepage_select') .
                                                                    '.partials.product_box',
                                                                    ['product' => $product]
                                                                )
                                                            </div>
                                                        </div>
                                                        @php $productCount++; @endphp
                                                    @endif
                                                @endforeach
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>





        {{-- <div id="category_wise_products">
            @foreach ($categoriesWithProducts as $category_key => $category)
                @php
                    $categoryName =
                        $category['id'] === 'otc-10'
                            ? 'Bag\'s'
                            : ($category['id'] === 'otc-18'
                                ? 'Snekers'
                                : ($category['id'] === 'otc-13'
                                    ? 'Shirt\'s'
                                    : ''));
                @endphp
                <section class="mb-2 mb-md-3 mt-2 mt-md-3">
                    <div class="container px-1">
                        <!-- Top Section -->
                        <div class="d-flex p-2 mb-3 mb-md-4 border-bottom align-items-baseline justify-content-between">
                            <!-- Title -->
                            <h3 class="fs-16 fs-md-20 fw-700 mb-2 mb-sm-0">
                                <span class="">{{ $categoryName }}</span>
                            </h3>
                            <!-- Links -->
                            <div class="d-flex">
                                <form action="{{ route('category_wise_products', ['category' => $category['id']]) }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="subcategoryId" value="{{ $category['subcategoryId'] }}">
                                    <button type="submit" class="btn text-danger fs-16 fs-md-16 fw-700 hov-text-danger animate-underline-primary">
                                        View All 
                                    </button>
                                </form>
                            </div>

                        </div>
                        <!-- Products Section -->



                        <!-- Products Section -->
                        <div class="row mx-2">
                            @foreach ($category['items'] as $product)
                                <div class="col-5-custom position-relative px-0 has-transition hov-animate-outline">
                                    <div class="px-1 mb-3 custom-product-box">
                                        @include('frontend.'.get_setting('homepage_select').'.partials.product_box',['product' => $product])
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </section>
            @endforeach
        </div> --}}

        <!-- Banner Section 3 -->
        {{-- @php $homeBanner3Images = get_setting('home_banner3_images', null, $lang);   @endphp
        @if ($homeBanner3Images != null)
            <div class="mb-2 mb-md-3 mt-2 mt-md-3" style="overflow-x:hidden">
                <div class="container">
                    @php
                        $banner_3_imags = json_decode($homeBanner3Images);
                        $data_md = count($banner_3_imags) >= 2 ? 2 : 1;
                    @endphp
                    <div class="aiz-carousel overflow-hidden arrow-inactive-none arrow-dark arrow-x-15"
                        data-items="{{ count($banner_3_imags) }}" data-xxl-items="{{ count($banner_3_imags) }}"
                        data-xl-items="{{ count($banner_3_imags) }}" data-lg-items="{{ $data_md }}"
                        data-md-items="{{ $data_md }}" data-sm-items="1" data-xs-items="1" data-arrows="true"
                        data-dots="false">
                        @foreach ($banner_3_imags as $key => $value)
                            <div class="carousel-box overflow-hidden hov-scale-img">
                                <a href="{{ json_decode(get_setting('home_banner3_links'), true)[$key] }}"
                                    class="d-block text-reset overflow-hidden">
                                    <img src="{{ static_asset('assets/img/placeholder-rect.jpg') }}"
                                        data-src="{{ uploaded_asset($value) }}" alt="{{ env('APP_NAME') }} promo"
                                        class="img-fluid lazyload w-100 has-transition"
                                        onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder-rect.jpg') }}';">
                                </a>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif --}}

        {{-- <div id="category_wise_products2">
            @foreach ($categoriesWithProductsSection2 as $category_key => $category)
                @php
                    $categoryName =
                        $category['id'] === 'otc-23'
                            ? 'Accessories'
                            : ($category['id'] === 'otc-19'
                                ? 'Cosmetics'
                                    : '');
                @endphp
                <section class="mb-2 mb-md-3 mt-2 mt-md-3">
                    <div class="container px-1">
                        <!-- Top Section -->
                        <div class="d-flex p-2 mb-3 mb-md-4 border-bottom align-items-baseline justify-content-between">
                            <!-- Title -->
                            <h3 class="fs-16 fs-md-20 fw-700 mb-2 mb-sm-0">
                                <span class="">{{ $categoryName }}</span>
                            </h3>
                            <!-- Links -->
                            <div class="d-flex">
                                <!--<a class="text-danger fs-16 fs-md-16 fw-700 hov-text-danger animate-underline-primary"-->
                                <!--    href="{{ route('category_wise_products', $category['subcategoryId']) }}">View All</a>-->
                                 <form action="{{ route('category_wise_products', ['category' => $category['id']]) }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="subcategoryId" value="{{ $category['subcategoryId'] }}">
                                    <button type="submit" class="btn text-danger fs-16 fs-md-16 fw-700 hov-text-danger animate-underline-primary">
                                        View All 
                                    </button>
                                </form>

                            </div>
                        </div>
                        <!-- Products Section -->



                        <!-- Products Section -->
                        <div class="row mx-2">
                            @foreach ($category['items'] as $product)
                                <div class="col-5-custom position-relative px-0 has-transition hov-animate-outline">
                                    <div class="px-1 mb-3 custom-product-box">
                                        @include('frontend.'.get_setting('homepage_select').'.partials.product_box',['product' => $product])
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </section>
            @endforeach
    </div> 

        @php
            $homeBanner1Images = get_setting('home_banner1_images', null, $lang);
        @endphp
        @if ($homeBanner1Images != null)
            <div class="mb-2 mb-md-3 mt-2 mt-md-3" style="overflow-x:hidden">
                <div class="container">
                    @php
                        $banner_1_imags = json_decode($homeBanner1Images);
                        $homeBannerLinks = json_decode(get_setting('home_banner1_links'), true);
                    @endphp
                    <div class="row mx-n1 mx-lg-n2">
                        @foreach ($banner_1_imags as $key => $value)
                            @if ($key < 2)
                                <!-- First Two Images -->
                                <div class="col-6 col-lg-3 px-1 px-lg-2">
                                    <a href="{{ $homeBannerLinks[$key] }}" class="d-block text-reset overflow-hidden">
                                        <img src="{{ static_asset('assets/img/placeholder-rect.jpg') }}"
                                            data-src="{{ uploaded_asset($value) }}" alt="{{ env('APP_NAME') }} promo"
                                            class="img-fluid lazyload w-100 has-transition"
                                            onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder-rect.jpg') }}';">
                                    </a>
                                </div>
                            @elseif ($key == 2)
                                <!-- Last Image -->
                                <div class="col-12 col-lg-6 mt-2 mt-lg-0 px-1 px-lg-2">
                                    <a href="{{ $homeBannerLinks[$key] }}" class="d-block text-reset overflow-hidden">
                                        <img src="{{ static_asset('assets/img/placeholder-rect.jpg') }}"
                                            data-src="{{ uploaded_asset($value) }}" alt="{{ env('APP_NAME') }} promo"
                                            class="img-fluid lazyload w-100 has-transition"
                                            onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder-rect.jpg') }}';">
                                    </a>
                                </div>
                            @endif
                        @endforeach
                    </div>


                </div>
            </div>
        @endif
    --}}


        <section class="mb-2 mb-md-3 mt-md-3 d-none d-md-block">
            <div class="container">
                <!-- Top Section -->
                <div class="text-center">
                    <h3 class="fw-700 mb-sm-0">
                        <span class="">{{ translate('How Click2Import Works') }}</span>
                    </h3>
                </div>

                <div class="bg-white py-3 d-flex justify-content-center flex-wrap">

                    <!-- Oinal images with text for larger screens -->
                    <div class="icon-box text-center mb-3 mx-2">
                        <img src="{{ static_asset('assets/img/Order Placement blue.png') }}"
                            class="lazyload w-100px h-auto mx-auto has-transition" alt="Order Placement">
                        <div class="fs-16 mt-2 text-wrap">
                            <span class="text-dark fw-700">Order Placement</span>
                        </div>
                    </div>
                    <div class="icon-box text-center mb-3 mx-2">
                        <img src="{{ static_asset('assets/img/Buying Goods.png') }}"
                            class="lazyload w-100px h-auto mx-auto has-transition" alt="Buying Goods">
                        <div class="fs-16 mt-2 text-wrap">
                            <span class="text-dark fw-700">Buying Goods</span>
                        </div>
                    </div>
                    <div class="icon-box text-center mb-3 mx-2">
                        <img src="{{ static_asset('assets/img/Goods Received in China warehouse.png') }}"
                            class="lazyload w-100px h-auto mx-auto has-transition" alt="Goods Received in China warehouse">
                        <div class="fs-16 mt-2 text-wrap">
                            <span class="text-dark fw-700">Goods Received in China Warehouse</span>
                        </div>
                    </div>
                    <div class="icon-box text-center mb-3 mx-2">
                        <img src="{{ static_asset('assets/img/Shipment Done.png') }}"
                            class="lazyload w-100px h-auto mx-auto has-transition" alt="Shipment Done">
                        <div class="fs-16 mt-2 text-wrap">
                            <span class="text-dark fw-700">Shipment Done</span>
                        </div>
                    </div>
                    <div class="icon-box text-center mb-3 mx-2">
                        <img src="{{ static_asset('assets/img/Goods Received in Bangladesh.png') }}"
                            class="lazyload w-100px h-auto mx-auto has-transition" alt="Goods Received in Bangladesh">
                        <div class="fs-16 mt-2 text-wrap">
                            <span class="text-dark fw-700">Goods Received in Bangladesh</span>
                        </div>
                    </div>
                    <div class="icon-box text-center mb-3 mx-2">
                        <img src="{{ static_asset('assets/img/Ready to deliver.png') }}"
                            class="lazyload w-100px h-auto mx-auto has-transition" alt="Ready to deliver">
                        <div class="fs-16 mt-2 text-wrap">
                            <span class="text-dark fw-700">Ready to Deliver</span>
                        </div>
                    </div>

                </div>
            </div>
        </section>
@endsection
