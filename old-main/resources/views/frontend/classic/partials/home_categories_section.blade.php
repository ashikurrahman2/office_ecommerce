@if (get_setting('home_categories') != null)
    @php
        $home_categories = json_decode(get_setting('home_categories'));
        $categories = get_category($home_categories);
    @endphp

    @foreach ($rootCategories as $category_key => $category)
        @php
            $category_name = $category['Name'];
            $category_id = $category['Id'];
           $products = app(\App\Services\OtApiService::class)->searchItems($category_id, 0, 16, '');
           
            // Extract the products/items from the API response
            $items = $products['Result']['Items']['Items']['Content'] ?? [];
        @endphp

        <section class="@if ($category_key != 0) mt-4 @endif">
            <div class="container">
                <div class="row gutters-16">
                    <!-- Home category banner & name -->
                    <div class="col-xl-3 col-lg-4 col-md-5">
                        <div class="h-200px h-sm-250px h-md-340px">
                            <a href="" class="d-block h-100 w-100 w-xl-auto hov-scale-img overflow-hidden home-category-banner">
                                <span class="position-absolute h-100 w-100 overflow-hidden">
                                    <img src="{{ isset($category->coverImage->file_name) ? my_asset($category->coverImage->file_name) : static_asset('assets/img/placeholder.jpg') }}"
                                        alt="{{ $category_name }}"
                                        class="img-fit h-100 has-transition"
                                        onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';">
                                </span>
                                <span class="home-category-name fs-15 fw-600 text-white text-center">
                                    <span class="">{{ $category_name }}</span>
                                </span>
                            </a>
                        </div>
                    </div>

                    <!-- Product List for the Category -->
                    <div class="col-xl-9 col-lg-8 col-md-7">
                        <div class="row">
                            @if (count($items) > 0)
                                @foreach ($items as $product)
                                    <div class="col-md-4 col-sm-6">
                                        <div class="card mb-3">
                                            <!-- Product Image -->
                                            <img src="{{ $product['MainPictureUrl'] ?? static_asset('assets/img/placeholder.jpg') }}"
                                                class="card-img-top"
                                                alt="{{ $product['Title'] ?? 'Product Image' }}"
                                                onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';">

                                            <div class="card-body">
                                                <!-- Product Title -->
                                                <h5 class="card-title">{{ $product['Title'] ?? 'No Title Available' }}</h5>
                                                
                                                <!-- Product Price -->
                                                <p class="card-text">
                                                    Price: 
                                                    {{ $product['Price']['ConvertedPrice'] ?? 'N/A' }}
                                                    {{ $product['Price']['CurrencySign'] ?? '' }}
                                                </p>
                                                
                                                <!-- Vendor Name -->
                                                <p class="card-text">
                                                    Vendor: 
                                                    {{ $product['VendorName'] ?? 'N/A' }}
                                                </p>

                                                <!-- Product Link -->
                                                <a href="{{ $product['ExternalItemUrl'] ?? '#' }}" class="btn btn-primary">View Product</a>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <p>No products found in this category.</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </section>
    @endforeach
@endif




{{--@if (get_setting('home_categories') != null)
    @php

        $home_categories = json_decode(get_setting('home_categories'));
        $categories = get_category($home_categories);
    @endphp
    @foreach ($rootCategories as $category_key => $category)
        @php
            $category_name = $category['Name'];
            $category_id = $category['Id'];
            $products = app(\App\Services\OtApiService::class)->searchItems($category_id, 0, 20, '');
            
            $items = $products['Result']['Items']['Items']['Content'] ?? [];
        @endphp
        <section class="@if ($category_key != 0) mt-4 @endif" style="">
            <div class="container">
                <div class="row gutters-16">
                    <!-- Home category banner & name -->
                    <div class="col-xl-3 col-lg-4 col-md-5">
                        <div class="h-200px h-sm-250px h-md-340px">
                            <a href="" class="d-block h-100 w-100 w-xl-auto hov-scale-img overflow-hidden home-category-banner">
                                <span class="position-absolute h-100 w-100 overflow-hidden">
                                    <img src="{{ isset($category->coverImage->file_name) ? my_asset($category->coverImage->file_name) : static_asset('assets/img/placeholder.jpg') }}"
                                        alt="{{ $category_name }}"
                                        class="img-fit h-100 has-transition"
                                        onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';">
                                </span>
                                <span class="home-category-name fs-15 fw-600 text-white text-center">
                                    <span class="">{{ $category_name }}</span>
                                </span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    @endforeach

    <!-- @foreach ($rootCategories as $category_key => $category)
        @php
            $category_name = $category['Name'];
            $category_id = $category['Id'];
            $products = app(\App\Services\OtApiService::class)->searchItems($category_id, 0, 20, '');
            
            $items = $products['Result']['Items']['Items']['Content'] ?? [];
        @endphp
        <section class="@if ($category_key != 0) mt-4 @endif" style="">
            <div class="container">
                <div class="row gutters-16">
                    <div class="col-xl-3 col-lg-4 col-md-5">
                        <div class="h-200px h-sm-250px h-md-340px">
                            <a href="" class="d-block h-100 w-100 w-xl-auto hov-scale-img overflow-hidden home-category-banner">
                                <span class="position-absolute h-100 w-100 overflow-hidden">
                                    <img src="{{ isset($category['coverImage']['file_name']) ? my_asset($category['coverImage']['file_name']) : static_asset('assets/img/placeholder.jpg') }}"
                                        alt="{{ $category_name }}"
                                        class="img-fit h-100 has-transition"
                                        onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';">
                                </span>
                                <span class="home-category-name fs-15 fw-600 text-white text-center">
                                    <span class="">{{ $category_name }}</span>
                                </span>
                            </a>
                        </div>
                    </div>

                    <div class="col-xl-9 col-lg-8 col-md-7">
                        <div class="row">
                            @if (count($items) > 0)
                                @foreach ($items as $product)
                                <div class="col-md-4 col-sm-6">
                                        <div class="card mb-3">
                                            <img src="{{ isset($product['MainPictureUrl']) ? $product['MainPictureUrl'] : static_asset('assets/img/placeholder.jpg') }}"
                                                class="card-img-top"
                                                alt="{{ $product['Title'] ?? 'Product Image' }}"
                                                onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';">
                                            <div class="card-body">
                                                <h5 class="card-title">{{ $product['Title'] ?? 'Product Title' }}</h5>
                                                <p class="card-text">{{ Str::limit($product['Description'] ?? 'Product Description', 100) }}</p>
                                                <a href="{{ $product['DetailUrl'] ?? '#' }}" class="btn btn-primary">View Product</a>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <p>No products found in this category.</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </section>
    @endforeach -->
@endif
--}}