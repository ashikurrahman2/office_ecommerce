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

@include('frontend.partials.product_loader')

<div id="productContainer">
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
                    {{-- Modify code comment --}}
                    {{-- <div class="col-lg-3 d-none d-lg-block  rounded-lg-custom pl-3" bis_skin_checked="1">
                        <img class="img-fluid d-block w-100 rounded-lg-custom rounded" src="public/uploads/all/OuPFuFjrH0C2Tgqs3m8LAz1Uosn49XD3TAtKxzgN.jpg" style="height: 98%">
                    </div> --}}

                    {{-- I modify this code --}}

                  <div class="d-none d-md-block  align-items-center btn-primary  p-2 rounded" style= margin-left:1rem; id="category-menu-bar"
                    style="cursor: pointer;">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center">
                            <span class="circle mr-1">
                                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12"
                                    viewBox="0 0 15 14">
                                    <g id="Group_29240" data-name="Group 29240" transform="translate(-18 -18)">
                                        <rect id="Rectangle_21398" data-name="Rectangle 21398" width="15"
                                            height="2" transform="translate(18 24)" fill="#fff"></rect>
                                        <rect id="Rectangle_21399" data-name="Rectangle 21399" width="15"
                                            height="2" transform="translate(18 18)" fill="#fff"></rect>
                                        <rect id="Rectangle_21400" data-name="Rectangle 21400" width="15"
                                            height="2" transform="translate(18 30)" fill="#fff"></rect>
                                    </g>
                                </svg>
                            </span>
                            <span class="fw-700 fs-14 text-white mr-3 d-none d-md-block">{{ translate('All Categories') }}</span>

                        </div>
                        <!-- <i class="las la-angle-down text-white" id="category-menu-bar-icon" style="font-size: 1.2rem;"></i> -->
                    </div>

                </div>                  


<!-- 🟦 Menu Section -->
<div class="px-xl-5 mt-3">
    <div class style="display: flex; gap: 2rem;"> <!-- gap-4 → gap-5 -->

        <!-- SuperDeals (red & bold) -->
        <a href="#" class="text-danger fw-bold" style="font-size: 17px; text-decoration: none;">
            SuperDeals
        </a>

        <!-- Other menu items -->
        <a href="#" class="text-dark" style="font-size: 17px; font-weight: 500; text-decoration: none;">
            ShipCart Business
        </a>
        <a href="#" class="text-dark" style="font-size: 17px; font-weight: 500; text-decoration: none;">
            Home & Garden
        </a>
        <a href="#" class="text-dark" style="font-size: 17px; font-weight: 500; text-decoration: none;">
            Hair Extensions & Wigs
        </a>
        <a href="#" class="text-dark" style="font-size: 17px; font-weight: 500; text-decoration: none;">
            Men's Clothing
        </a>
        <a href="#" class="text-dark" style="font-size: 17px; font-weight: 500; text-decoration: none;">
            Accessories
        </a>

        <!-- More with down arrow -->
  <!-- 🔻 Dropdown Menu with Hover -->
<div class="dropdown dropdown-hover">
    <a class="text-dark dropdown-toggle" href="#" role="button"
       data-bs-toggle="dropdown" aria-expanded="false"
       style="font-size: 17px; font-weight: 500; text-decoration: none;">
        More
    </a>
    <ul class="dropdown-menu">
        <li><a class="dropdown-item" href="#">Toys & Hobbies</a></li>
        <li><a class="dropdown-item" href="#">Beauty & Health</a></li>
        <li><a class="dropdown-item" href="#">Sports & Outdoors</a></li>
    </ul>
</div>
<style>
/* Enable dropdown on hover */
.dropdown-hover:hover .dropdown-menu {
    display: block;
    margin-top: 0; /* prevent jump */
}

/* Prevent flickering when hovering between link and menu */
.dropdown-hover .dropdown-toggle::after {
    transition: transform 0.2s;
}
</style>


    </div>
</div>





                    <!-- Sliders (Always visible) -->
                  <div class="col-12 mt-2">
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
                                                        onerror="this.onerror=null; this.src='{{ static_asset('assets/img/placeholder-rect.jpg') }}';">
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
                                                <p class="fs-14 fs-md-14 mb-2 text-dark fw-bold">ShipCart is here to stand by your side</p>
                                                <a href="/blog" class="btn btn-primary px-3 px-md-4 py-2 fs-12 fs-md-14 fw-bold mt-2">
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
                                   <div class="text-center mt-2">
    <span class="fs-24 fw-700">{{ translate("Today's deals") }}</span>
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
                                        $categoryName =
                                        $category['id'] === 'otc-10'
                                            ? '\'s'
                                            : ($category['id'] === 'otc-18'
                                                ? 'SBagnekers'
                                                : ($category['id'] === 'otc-13'
                                                    ? 'Shirt\'s'
                                                    : ''));
                                    @endphp
                                    <section class="mb-2 mb-md-3 mt-2 mt-md-3">
                                        <div class="container px-1">
                                            <!-- Top Section -->
                                            <div
                                                class="mb-3 mb-md-4 text-center">
                                                
                                                <!-- Title -->
                                                <h3 class="fs-16 fs-md-20 fw-700 mb-2 mb-sm-0 pb-3">
                                                    {{-- <span class="text-center">{{ $categoryName}}</span> --}}
                                                    <span class="fs-24 fw-700">More to love</span>
                                                </h3>
                                                <!-- Links -->
                                                {{-- <div class="justify-content-between align-items-center mb-3">
                                                    <form
                                                        action="{{ route('category_wise_products', ['category' => $category['id']]) }}"
                                                        method="POST">
                                                        @csrf
                                                        <input type="hidden" name="subcategoryId"
                                                            value="{{ $category['subcategoryId'] }}">
                                                        <button class="btn btn-sm btn-outline-primary active me-2">New</button>
                                                    
                                                        <button class="btn btn-sm btn-outline-primary">Best Buy</button>
                                                    </form>
                                                </div> --}}
                                            </div>

                                            <!-- Products Section -->
                                            <div class="row px-2">
                                                @php $productCount = 0; @endphp
                                                @foreach ($category['items'] as $product)
                                                    @if ($productCount < 10)
                                                        <!-- 2 rows * 6 products each = 12 -->
                                                        <div
                                                            class="col-5-custom position-relative px-0 has-transition hov-animate-outline">
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
                                    {{-- <div class="advertisement-box mb-3 position-relative">
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
                                    </div> --}}
                                @endforeach
                            </div>
                        @endif
                    </div>

                </div>
            </section>


            <div class="view-more-wrapper">
  <a href="#" class="view-more-btn">View More</a>
</div>
        </div>

        <!-- HOT TREND Section -->
        {{-- <section class="mb-4 mt-3">
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
    
                                    <!--<div class="mb-3">-->
                                    <!--    <h5 class=" fw-700 mb-1">New Arrivals</h5>-->
                                    <!--    <span class="text-muted fw-500 ms-1">Shortcut ></span>-->
                                    <!--</div>-->
                                </div>
                        
                                <div class="buttontag mb-5">
                                    <h4 class="fw-700 mb-2">HOT Keywords:</h4>
                                   
                                    <div class="flex-column gap-1 mb-2 text-start">
                                        <button type="button" class="btn text-start buttontag" onclick="submitSearchTag('summer dress')">#summer dress</button>
                                    </div>
                                    <div class="flex-column gap-1 mb-2 text-start">
                                        <button type="button" class="btn text-start buttontag" onclick="submitSearchTag('smartwatch')">#smartwatch</button>
                                    </div>
                                    <div class="flex-column gap-1 mb-2 text-start">
                                        <button type="button" class="btn text-start buttontag" onclick="submitSearchTag('wireless earbuds')">#wireless earbuds</button>
                                    </div>
                                    <div class="flex-column gap-1 mb-2 text-start">
                                        <button type="button" class="btn text-start buttontag" onclick="submitSearchTag('portable blender')">#portable blender</button>
                                    </div>
                                    <div class="flex-column gap-1 mb-2 text-start">
                                        <button type="button" class="btn text-start buttontag" onclick="submitSearchTag('led strip lights')">#led strip lights</button>
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
                                    <!--<div class="mb-2">-->
                                    <!--    <h5 class="fw-700 mb-1">Trending Now</h5>-->
                                    <!--    <span class="text-muted fw-500 ms-1">Shortcut ></span>-->
                                    <!--</div>-->
    
                                    <div class="mb-3">
                                        <h5 class=" fw-700 mb-1">New Arrivals</h5>
                                        <span class="text-muted fw-500 ms-1">Shortcut ></span>
                                    </div>
                                
                            </div>

                                <!-- Hot Keywords Section at the Bottom -->
                                <div class="buttontag mb-5">
                                    <h4 class="fw-700 mb-2">HOT Keywords:</h4>
                                      <div class="flex-column gap-1 mb-2 text-start">
                                        <button type="button" class="btn text-start buttontag" onclick="submitSearchTag('baby clothes')">#baby clothes</button>
                                    </div>
                                    <div class="flex-column gap-1 mb-2 text-start">
                                        <button type="button" class="btn text-start buttontag" onclick="submitSearchTag('skin care')">#skin care</button>
                                    </div>
                                    <div class="flex-column gap-1 mb-2 text-start">
                                        <button type="button" class="btn text-start buttontag" onclick="submitSearchTag('kitchen tools')">#kitchen tools</button>
                                    </div>
                                    <div class="flex-column gap-1 mb-2 text-start">
                                        <button type="button" class="btn text-start buttontag" onclick="submitSearchTag('hair extensions')">#hair extensions</button>
                                    </div>
                                    <div class="flex-column gap-1 mb-2 text-start">
                                        <button type="button" class="btn text-start buttontag" onclick="submitSearchTag('kids toys')">#kids toys</button>
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
                                   @foreach (array_reverse($categoriesWithProducts) as $category_key => $category)
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
        </section> --}}





       

        <!-- Banner Section 3 -->
       

    

        {{-- <section class="mb-2 mb-md-3 mt-md-3 d-none d-md-block">
            <div class="container">
                <!-- Top Section -->
                <div class="text-center">
                    <h3 class="fw-700 mb-sm-0">
                        <span class="">{{ translate('How ShipCart Works') }}</span>
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
        </section> --}}
 <!-- View More Button Centered & Higher -->
{{-- <div class="view-more-wrapper">
  <a href="#" class="view-more-btn">View More</a>
</div> --}}

<style>
  .view-more-wrapper {
    text-align: center;
    margin-top: 10px; /* কম রাখলে বাটন উপরে থাকবে */
  }

  .view-more-btn {
    background-color: black;
    color: white;
    padding: 10px 25px;
    border-radius: 12px;
    text-decoration: none;
    font-weight: 600;
    display: inline-block;
  }

  .view-more-btn:hover {
    background-color: #333;
  }
</style>

</div>

{{-- <div class="text-center mt-6">
  <a href="#" class="btn text-white bg-black rounded px-4 py-2">
    View More
  </a>
</div> --}}



<script>
    function submitSearchTag(tagValue) {
        const input = document.getElementById('searchInput');
        const form = document.getElementById('searchForm');
        if (input && form) {
            input.value = tagValue;
            form.submit();
            showGlobalLoader();
        }
    }
</script>

@endsection
