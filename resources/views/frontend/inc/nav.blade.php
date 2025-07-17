<!-- Top Bar -->
<div class="d-none d-lg-block top-navbar bg-white z-1035 h-35px h-sm-auto" style="border-bottom: 1px solid #eee;">
    <div class="container">
        <div class="row">
            <div class="col-lg-6 col">
                <ul class="list-inline d-flex justify-content-between justify-content-lg-start align-items-center mb-0">

                    <li class="list-inline-item border-right pr-2">
                        <a href="/information"
                            class="text-dark fs-11 d-inline-block py-2 btn btn-light border-0">
                            <i class="fa fa-headset mr-2"></i>
                            <span>Support</span>
                        </a>
                    </li>

                    <li class="list-inline-item pr-2">
                            <a class="icon-bg" href="{{ route('orders.track') }}"><i class="fa-solid fa-location-dot mr-2"> </i>{{ translate('Track Order') }}</a>
                    </li>
                    {{-- <li class="list-inline-item pr-2">
                           <a class="text-dark fs-11 d-inline-block py-2 clearAll" href="javascript:void(0)">{{ translate('Cache Clear') }}</a>
                       </li> --}}
                </ul>
            </div>

            <div class="col-6 text-right d-none d-lg-block">
                <ul class="list-inline colored mb-0 h-100 d-flex justify-content-end align-items-center">
                    <li class="list-inline-item border-right pr-2">
                        <a href="{{ get_setting('wishlist_link') }}" target="_blank" class="text-danger wishlist">
                            <i class="fa fa-heart fs-14 mr-2"></i>
                            <span>Wishlist</span>
                        </a>
                    </li>

                    <li class="list-inline-item border-right pr-2">
                        <a href="{{ get_setting('c2i_live_link') }}" target="_blank"
                            class="btn btn-white text-dark font-weight-bold border-0 d-inline-block py-2">
                            <i class="fa fa-broadcast-tower text-danger fs-16 mr-2"></i>
                            <span>ShipCart Live</span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>

<header class="@if (get_setting('header_stikcy') == 'on') sticky-top @endif z-1020 bg-white">
    <!-- Search Bar -->
    <div class="position-relative logo-bar-area border-bottom border-md-nonea z-1025">
        <div class="container">
            <div class="d-flex align-items-center">
                <!-- top menu sidebar button -->
                {{-- <button type="button" class="btn d-lg-none mr-3 mr-sm-4 p-0 active" data-toggle="class-toggle"
                        data-target=".aiz-top-menu-sidebar">
                        <svg id="Component_43_1" data-name="Component 43 – 1" xmlns="http://www.w3.org/2000/svg"
                            width="16" height="16" viewBox="0 0 16 16">
                            <rect id="Rectangle_19062" data-name="Rectangle 19062" width="16" height="2"
                                transform="translate(0 7)" fill="#919199" />
                            <rect id="Rectangle_19063" data-name="Rectangle 19063" width="16" height="2"
                                fill="#919199" />
                            <rect id="Rectangle_19064" data-name="Rectangle 19064" width="16" height="2"
                                transform="translate(0 14)" fill="#919199" />
                        </svg>

                    </button> --}}


                <!-- Categoty Menu Button -->
                {{-- <div class="d-none d-md-block  align-items-center btn-primary  p-2 rounded " id="category-menu-bar"
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
                            <span class="fw-700 fs-14 text-white mr-3 d-none d-md-block">{{ translate('Categories') }}</span>

                        </div>
                        <!-- <i class="las la-angle-down text-white" id="category-menu-bar-icon" style="font-size: 1.2rem;"></i> -->
                    </div>

                </div> --}}
                <!-- Header Logo -->
                <div class="col-auto pl-0 pr-1 d-flex align-items-center">
                    <a class="d-block py-5px mr-lg-3 ml-0" href="{{ route('home') }}">
                        @php
                            $header_logo = get_setting('header_logo');
                        @endphp
                        @if ($header_logo != null)
                            <img src="{{ uploaded_asset($header_logo) }}" alt="{{ env('APP_NAME') }}"
                                class="mw-100 h-50px h-md-40px pl-md-3" height="400" width="235">
                        @else
                            <img src="{{ static_asset('assets/img/logo.jpg') }}" alt="{{ env('APP_NAME') }}"
                                class="mw-100 h-30px h-md-40px" height="40">
                        @endif
                    </a>
                </div>

                <!-- Search field -->
                <div class="flex-grow-1 d-flex align-items-center bg-white mx-xl-5">
                    <div class="position-relative flex-grow-1 my-1 px-lg-0">
                       <form id="searchForm" action="{{ route('search') }}" method="GET"
    class="stop-propagation mb-0" enctype="multipart/form-data">
    <div class="input-group" style="overflow: hidden; border: 1px solid #007bff; border-radius: 60px;">

        <!-- Country dropdown container (static name for now) -->
        {{-- <div class="input-group-prepend">
            <select class="form-select border-0 bg-light pl-3"
                style="min-width: 90px; font-weight: 600;">
                <option selected>All</option>
                <option>Chaina</option>
                <option>USA</option>
                <option>UK</option>
                <option>UAE</option>
            </select>
        </div> --}}

        <!-- Keyword Input -->
        <input type="text" class="form-control border-0 fs-14 hov-animate-outline"
            name="keyword"
            @isset($keyword) value="{{ $keyword }}" @endisset
            placeholder="{{ translate('Search for Products or paste link') }}"
            autocomplete="off" aria-label="Search" id="searchInput">

        <!-- Image Upload Input (Hidden) -->
        <input type="file" id="imageInput" name="image"
            accept="image/jpeg, image/png, image/jpg" style="display: none;">

        <!-- Camera button -->
        <button type="button" class="btn btn-light border-0"
            onclick="triggerImageUpload()"
            style="margin-top: 1px; margin-bottom: 1px; padding: 0 10px; margin-left: -40px; z-index: 999;">
            <i class="fas fa-camera" style="font-size: 18px; color: #555;"></i>
        </button>

        <!-- Search Button -->
        <button class="btn btn-primary search-button" type="submit" id="button-addon2">
            <svg id="Group_723" data-name="Group 723" xmlns="http://www.w3.org/2000/svg"
                width="20.001" height="20" viewBox="0 0 20.001 20">
                <path id="Path_3090" data-name="Path 3090"
                    d="M9.847,17.839a7.993,7.993,0,1,1,7.993-7.993A8,8,0,0,1,9.847,17.839Zm0-14.387a6.394,6.394,0,1,0,6.394,6.394A6.4,6.4,0,0,0,9.847,3.453Z"
                    transform="translate(-1.854 -1.854)" fill="#fff" />
                <path id="Path_3091" data-name="Path 3091"
                    d="M24.4,25.2a.8.8,0,0,1-.565-.234l-6.15-6.15a.8.8,0,0,1,1.13-1.13l6.15,6.15A.8.8,0,0,1,24.4,25.2Z"
                    transform="translate(-5.2 -5.2)" fill="#fff" />
            </svg>
        </button>
    </div>
</form>


                        <div id="suggestions"
                            style="display: none; position: absolute; width: 100%; background: #fff; border: 1px solid #ddd; z-index: 10;">
                        </div>

                        <div class="typed-search-box stop-propagation document-click-d-none d-none bg-white rounded shadow-lg position-absolute left-0 top-100 w-100"
                            style="min-height: 200px">
                            <div class="search-preloader absolute-top-center">
                                <div class="dot-loader">
                                    <div></div>
                                    <div></div>
                                    <div></div>
                                </div>
                            </div>
                            <div class="search-nothing d-none p-3 text-center fs-16"></div>
                            <div id="search-content" class="text-left"></div>
                        </div>
                    </div>
                </div>

{{-- Download app QR and Language --}}
<div class="d-flex align-items-center position-relative">
    <!-- Download the App with QR -->
    <div class="position-relative me-3" id="downloadAppWrapper">
        <a href="javascript:void(0)" class="d-flex align-items-center text-dark text-decoration-none" id="downloadAppBtn">
            <i class="fas fa-qrcode me-2" style="margin-right: 3px;"></i> Download the App
        </a>

        <!-- QR Dropdown -->
<div id="qrDropdown" class="position-absolute bg-white border shadow p-3" style="top: 100%; left: 0; display: none; z-index: 999; min-width: 360px; border-radius: 12px;">
    <div class="d-flex align-items-center">
        <!-- QR Code -->
        <img src="https://api.qrserver.com/v1/create-qr-code/?data=https://example.com/app&size=100x100" 
             alt="QR Code" class="img-fluid me-3" style="width: 100px; height: 100px; border-radius: 8px;">

        <!-- Text and Buttons -->
        <div class="flex-grow-1">
            <strong class="d-block mb-2 ml-2" style="font-size: 16px;">Download the ShipCart app</strong>
            <p class="ml-2">Scan the QR code to download</p>

            <!-- Buttons -->
            <div class="d-flex mt-2 ml-1" style="gap: 8px;">
                <!-- Apple Store Button -->
                <a href="#" class="d-flex align-items-center text-white" style="background-color: #000; padding: 6px 10px; border-radius: 16px; font-size: 14px; text-decoration: none;">
                    <i class="fab fa-apple me-2"></i> Apple Store
                </a>

                <!-- Google Play Button -->
                <a href="#" class="d-flex align-items-center text-white" style="background-color: #000; padding: 6px 10px; border-radius: 16px; font-size: 14px; text-decoration: none;">
                    <i class="fab fa-google-play me-2"></i> Google Play
                </a>
            </div>
        </div>
    </div>
</div>

</div>

    <!-- EN/BDT Dropdown (added ms-3 for left margin) -->
    <div class="dropdown ms-3 ml-2rem">
        <button class="btn btn-outline-secondary dropdown-toggle" type="button" id="languageCurrencyDropdown" data-bs-toggle="dropdown" aria-expanded="false">
            EN / BDT
        </button>
        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="languageCurrencyDropdown">
            <li><a class="dropdown-item" href="javascript:void(0)">EN / BDT</a></li>
            <li><a class="dropdown-item" href="javascript:void(0)">EN / USD</a></li>
            <li><a class="dropdown-item" href="javascript:void(0)">BN / BDT</a></li>
        </ul>
    </div>
</div>




<!-- Script -->
<script>
    const downloadBtn = document.getElementById('downloadAppBtn');
    const qrBox = document.getElementById('qrDropdown');
    const wrapper = document.getElementById('downloadAppWrapper');

    wrapper.addEventListener('mouseenter', () => {
        qrBox.style.display = 'block';
    });

    wrapper.addEventListener('mouseleave', () => {
        qrBox.style.display = 'none';
    });
</script>





                <!-- <ul class="list-inline h-100 d-none d-xl-flex justify-content-end align-items-center" style=" margin-bottom: -8px;">
                        <li class="list-inline-item p-1" id="wishlist">
                            <a href="{{ route('wishlists.index') }}" class="d-flex align-items-center fs-12" data-toggle="tooltip" data-title="{{ translate('Wishlist') }}" data-placement="top">
                                <span class="position-relative d-inline-block">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="14.4" viewBox="0 0 16 14.4">
                                        <g id="_51a3dbe0e593ba390ac13cba118295e4" data-name="51a3dbe0e593ba390ac13cba118295e4" transform="translate(-3.05 -4.178)">
                                            <path id="Path_32649" data-name="Path 32649" d="M11.3,5.507l-.247.246L10.8,5.506A4.538,4.538,0,1,0,4.38,11.919l.247.247,6.422,6.412,6.422-6.412.247-.247A4.538,4.538,0,1,0,11.3,5.507Z" transform="translate(0 0)" fill="#919199"/>
                                            <path id="Path_32650" data-name="Path 32650" d="M11.3,5.507l-.247.246L10.8,5.506A4.538,4.538,0,1,0,4.38,11.919l.247.247,6.422,6.412,6.422-6.412.247-.247A4.538,4.538,0,1,0,11.3,5.507Z" transform="translate(0 0)" fill="{{ get_setting('base_color', '#ff0000') }}"/>
                                        </g>
                                    </svg>
                                    @if (Auth::check() && count(Auth::user()->wishlists) > 0)
<span class="badge badge-primary badge-inline badge-pill absolute-top-right--10px">{{ count(Auth::user()->wishlists) }}</span>
@endif
                                </span>
                            </a>
                        </li>
                        @if (!isAdmin())
                            @auth
                                    <li class="list-inline-item p-1 mr-3 dropdown">
                                        <a class="dropdown-toggle no-arrow text-secondary fs-12" data-toggle="dropdown"
                                            href="javascript:void(0);" role="button" aria-haspopup="false"
                                            aria-expanded="false">
                                            <span class="">
                                                <span class="position-relative d-inline-block">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="14.668" height="16"
                                                        viewBox="0 0 14.668 16">
                                                        <path id="_26._Notification" data-name="26. Notification"
                                                            d="M8.333,16A3.34,3.34,0,0,0,11,14.667H5.666A3.34,3.34,0,0,0,8.333,16ZM15.06,9.78a2.457,2.457,0,0,1-.727-1.747V6a6,6,0,1,0-12,0V8.033A2.457,2.457,0,0,1,1.606,9.78,2.083,2.083,0,0,0,3.08,13.333H13.586A2.083,2.083,0,0,0,15.06,9.78Z"
                                                            transform="translate(-0.999)" fill="{{ get_setting('base_color', '#ff0000') }}" />
                                                    </svg>
                                                    @if (Auth::check() && count($user->unreadNotifications) > 0)
    <span
                                                            class="badge badge-primary badge-inline badge-pill absolute-top-right--10px">{{ count($user->unreadNotifications) }}</span>
    @endif
                                                </span>
                                        </a>
                                        
                                        <div class="dropdown-menu dropdown-menu-right dropdown-menu-lg py-0 rounded-0">
                                            <div class="p-3 bg-light border-bottom">
                                                <h6 class="mb-0">{{ translate('Notifications') }}</h6>
                                            </div>
                                            <div class="px-3 c-scrollbar-light overflow-auto " style="max-height:300px;">
                                                <ul class="list-group list-group-flush">
                                                    @forelse($user->unreadNotifications as $notification)
    <li class="list-group-item">
                                                            @if ($notification->type == 'App\Notifications\OrderNotification')
    @if ($user->user_type == 'customer')
    <a href="{{ route('purchase_history.details', encrypt($notification->data['order_id'])) }}"
                                                                        class="text-secondary fs-12">
                                                                        <span class="ml-2">
                                                                            {{ translate('Order code ') }}
                                                                            {{ $notification->data['order_code'] }}
                                                                            {{ translate('has been ' . ucfirst(str_replace('_', ' ', $notification->data['status']))) }}
                                                                        </span>
                                                                    </a>
@elseif ($user->user_type == 'seller')
    <a href="{{ route('seller.orders.show', encrypt($notification->data['order_id'])) }}"
                                                                        class="text-secondary fs-12">
                                                                        <span class="ml-2">
                                                                            {{ translate('Order code ') }}
                                                                            {{ $notification->data['order_code'] }}
                                                                            {{ translate('has been ' . ucfirst(str_replace('_', ' ', $notification->data['status']))) }}
                                                                        </span>
                                                                    </a>
    @endif
    @endif
                                                        </li>
                                                @empty
                                                        <li class="list-group-item">
                                                            <div class="py-4 text-center fs-16">
                                                                {{ translate('No notification found') }}
                                                            </div>
                                                        </li>
    @endforelse
                                                </ul>
                                            </div>
                                            <div class="text-center border-top">
                                                <a href="{{ route('all-notifications') }}"
                                                    class="text-secondary fs-12 d-block py-2">
                                                    {{ translate('View All Notifications') }}
                                                </a>
                                            </div>
                                        </div>
                                    </li>
                            @endauth
                        @endif
                    </ul> -->
                <!-- <ul class="list-inline h-100 d-none d-xl-flex justify-content-end align-items-center" style=" margin-bottom: -8px;">
                        
                        <li class="list-inline-item p-1" id="shopping-cart">
                            <a href="{{ route('wishlists.index') }}" class="d-flex align-items-center fs-12" data-toggle="tooltip" data-title="{{ translate('Shopping Cart') }}" data-placement="top">
                                <span class="position-relative d-inline-block">
                                    <i class="fa fa-shopping-cart fs-16 text-danger mr-2"></i>
                                    <span class="badge badge-primary badge-inline badge-pill absolute-top-right--10px">
                                        3
                                    </span>
                                </span>
                            </a>
                        </li>
                    </ul> -->

                <!-- <ul class="list-inline h-100 d-none d-xl-flex justify-content-end align-items-center" style=" margin-bottom: -8px;">
                        <!-- Shopping Cart Section -->
                <!-- <li class="list-inline-item p-1" id="shopping-cart">                            -->
                <!-- Cart dropdown inside the shopping cart -->
                <div class="d-none d-xl-block align-self-stretch ml-5 mr-0 has-transition bg-black-10"
                    data-hover="dropdown">
                    <div style="background-color:unset !important;" class="nav-cart-box dropdown bg-red h-100" id="cart_items" style="width: max-content;">
                        @include('frontend.' . get_setting('homepage_select') . '.partials.cart')
                    </div>
                </div>

                <!-- </li>
                    </ul> -->


                <div class="d-none d-xl-block ml-auto mr-0" style="margin-bottom: -8px;">
                    @auth
                        <span class="d-flex align-items-center nav-user-info @if (isAdmin()) ml-2 @endif"
                            id="nav-user-info" style="padding:5px 0px">
                            <!-- Image -->
                            <span class="size-40px rounded-circle overflow-hidden border border-transparent nav-user-img">
                                @if ($user->avatar_original != null)
                                    <img src="{{ $user_avatar }}" class="img-fit h-100"
                                        alt="{{ translate('avatar') }}"
                                        onerror="this.onerror=null;this.src='{{ static_asset('assets/img/avatar-place.png') }}';">
                                @else
                                    <img src="{{ static_asset('assets/img/avatar-place.png') }}" class="image img-fit"
                                        alt="{{ translate('avatar') }}"
                                        onerror="this.onerror=null;this.src='{{ static_asset('assets/img/avatar-place.png') }}';">
                                @endif
                            </span>
                            <!-- Name -->
                            <h4 class="h5 fs-14 fw-700 text-dark ml-2 mb-0">{{ $user->name }}</h4>
                        </span>
                    @else
                        <!--Login & Registration -->
                        <span class="d-flex align-items-center ml-2">
                            <!-- Image -->
                            <span
                                class="size-30px rounded-circle overflow-hidden border d-flex align-items-center justify-content-center nav-user-img">
                                <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15"
                                    viewBox="0 0 19.902 20.012">
                                    <path id="fe2df171891038b33e9624c27e96e367"
                                        d="M15.71,12.71a6,6,0,1,0-7.42,0,10,10,0,0,0-6.22,8.18,1.006,1.006,0,1,0,2,.22,8,8,0,0,1,15.9,0,1,1,0,0,0,1,.89h.11a1,1,0,0,0,.88-1.1,10,10,0,0,0-6.25-8.19ZM12,12a4,4,0,1,1,4-4A4,4,0,0,1,12,12Z"
                                        transform="translate(-2.064 -1.995)" fill="#91919b" />
                                </svg>
                            </span>
                            <a href="{{ route('user.login') }}"
                                class="text-reset opacity-60 hov-opacity-100 hov-text-danger fs-12 d-inline-block border-right border-soft-light border-width-2 pr-2 ml-3">{{ translate('Login') }}</a>
                            <a href="{{ route('user.registration') }}"
                                class="text-reset opacity-60 hov-opacity-100 hov-text-danger fs-12 d-inline-block py-2 pl-2">{{ translate('Registration') }}</a>
                        </span>
                    @endauth
                </div>
            </div>
        </div>

        <!-- Loged in user Menus -->
        <div class="hover-user-top-menu position-absolute top-100 left-0 right-0 z-3">
            <div class="container">
                <div class="position-static float-right">
                    <div class="aiz-user-top-menu bg-white rounded-0 border-top shadow-sm" style="width:220px;">
                        <ul class="list-unstyled no-scrollbar mb-0 text-left">
                            @if (isAdmin())
                                <li class="user-top-nav-element border border-top-0" data-id="1">
                                    <a href="{{ route('admin.dashboard') }}"
                                        class="text-truncate text-dark px-4 fs-14 d-flex align-items-center hov-column-gap-1">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                            viewBox="0 0 16 16">
                                            <path id="Path_2916" data-name="Path 2916"
                                                d="M15.3,5.4,9.561.481A2,2,0,0,0,8.26,0H7.74a2,2,0,0,0-1.3.481L.7,5.4A2,2,0,0,0,0,6.92V14a2,2,0,0,0,2,2H14a2,2,0,0,0,2-2V6.92A2,2,0,0,0,15.3,5.4M10,15H6V9A1,1,0,0,1,7,8H9a1,1,0,0,1,1,1Zm5-1a1,1,0,0,1-1,1H11V9A2,2,0,0,0,9,7H7A2,2,0,0,0,5,9v6H2a1,1,0,0,1-1-1V6.92a1,1,0,0,1,.349-.76l5.74-4.92A1,1,0,0,1,7.74,1h.52a1,1,0,0,1,.651.24l5.74,4.92A1,1,0,0,1,15,6.92Z"
                                                fill="#b5b5c0" />
                                        </svg>
                                        <span
                                            class="user-top-menu-name has-transition ml-3">{{ translate('Dashboard') }}</span>
                                    </a>
                                </li>
                            @else
                                <li class="user-top-nav-element border border-top-0" data-id="1">
                                    <a href="{{ route('dashboard') }}"
                                        class="text-truncate text-dark px-4 fs-14 d-flex align-items-center hov-column-gap-1">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                            viewBox="0 0 16 16">
                                            <path id="Path_2916" data-name="Path 2916"
                                                d="M15.3,5.4,9.561.481A2,2,0,0,0,8.26,0H7.74a2,2,0,0,0-1.3.481L.7,5.4A2,2,0,0,0,0,6.92V14a2,2,0,0,0,2,2H14a2,2,0,0,0,2-2V6.92A2,2,0,0,0,15.3,5.4M10,15H6V9A1,1,0,0,1,7,8H9a1,1,0,0,1,1,1Zm5-1a1,1,0,0,1-1,1H11V9A2,2,0,0,0,9,7H7A2,2,0,0,0,5,9v6H2a1,1,0,0,1-1-1V6.92a1,1,0,0,1,.349-.76l5.74-4.92A1,1,0,0,1,7.74,1h.52a1,1,0,0,1,.651.24l5.74,4.92A1,1,0,0,1,15,6.92Z"
                                                fill="#b5b5c0" />
                                        </svg>
                                        <span
                                            class="user-top-menu-name has-transition ml-3">{{ translate('Dashboard') }}</span>
                                    </a>
                                </li>
                            @endif

                            @if (isCustomer())
                                <li class="user-top-nav-element border border-top-0" data-id="1">
                                    <a href="{{ route('purchase_history.index') }}"
                                        class="text-truncate text-dark px-4 fs-14 d-flex align-items-center hov-column-gap-1">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                            viewBox="0 0 16 16">
                                            <g id="Group_25261" data-name="Group 25261"
                                                transform="translate(-27.466 -542.963)">
                                                <path id="Path_2953" data-name="Path 2953"
                                                    d="M14.5,5.963h-4a1.5,1.5,0,0,0,0,3h4a1.5,1.5,0,0,0,0-3m0,2h-4a.5.5,0,0,1,0-1h4a.5.5,0,0,1,0,1"
                                                    transform="translate(22.966 537)" fill="#b5b5bf" />
                                                <path id="Path_2954" data-name="Path 2954"
                                                    d="M12.991,8.963a.5.5,0,0,1,0-1H13.5a2.5,2.5,0,0,1,2.5,2.5v10a2.5,2.5,0,0,1-2.5,2.5H2.5a2.5,2.5,0,0,1-2.5-2.5v-10a2.5,2.5,0,0,1,2.5-2.5h.509a.5.5,0,0,1,0,1H2.5a1.5,1.5,0,0,0-1.5,1.5v10a1.5,1.5,0,0,0,1.5,1.5h11a1.5,1.5,0,0,0,1.5-1.5v-10a1.5,1.5,0,0,0-1.5-1.5Z"
                                                    transform="translate(27.466 536)" fill="#b5b5bf" />
                                                <path id="Path_2955" data-name="Path 2955"
                                                    d="M7.5,15.963h1a.5.5,0,0,1,.5.5v1a.5.5,0,0,1-.5.5h-1a.5.5,0,0,1-.5-.5v-1a.5.5,0,0,1,.5-.5"
                                                    transform="translate(23.966 532)" fill="#b5b5bf" />
                                                <path id="Path_2956" data-name="Path 2956"
                                                    d="M7.5,21.963h1a.5.5,0,0,1,.5.5v1a.5.5,0,0,1-.5.5h-1a.5.5,0,0,1-.5-.5v-1a.5.5,0,0,1,.5-.5"
                                                    transform="translate(23.966 529)" fill="#b5b5bf" />
                                                <path id="Path_2957" data-name="Path 2957"
                                                    d="M7.5,27.963h1a.5.5,0,0,1,.5.5v1a.5.5,0,0,1-.5.5h-1a.5.5,0,0,1-.5-.5v-1a.5.5,0,0,1,.5-.5"
                                                    transform="translate(23.966 526)" fill="#b5b5bf" />
                                                <path id="Path_2958" data-name="Path 2958"
                                                    d="M13.5,16.963h5a.5.5,0,0,1,0,1h-5a.5.5,0,0,1,0-1"
                                                    transform="translate(20.966 531.5)" fill="#b5b5bf" />
                                                <path id="Path_2959" data-name="Path 2959"
                                                    d="M13.5,22.963h5a.5.5,0,0,1,0,1h-5a.5.5,0,0,1,0-1"
                                                    transform="translate(20.966 528.5)" fill="#b5b5bf" />
                                                <path id="Path_2960" data-name="Path 2960"
                                                    d="M13.5,28.963h5a.5.5,0,0,1,0,1h-5a.5.5,0,0,1,0-1"
                                                    transform="translate(20.966 525.5)" fill="#b5b5bf" />
                                            </g>
                                        </svg>
                                        <span
                                            class="user-top-menu-name has-transition ml-3">{{ translate('Purchase History') }}</span>
                                    </a>
                                </li>

                                @if (get_setting('conversation_system') == 1)
                                    <li class="user-top-nav-element border border-top-0" data-id="1">
                                        <a href="{{ route('conversations.index') }}"
                                            class="text-truncate text-dark px-4 fs-14 d-flex align-items-center hov-column-gap-1">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                viewBox="0 0 16 16">
                                                <g id="Group_25263" data-name="Group 25263"
                                                    transform="translate(1053.151 256.688)">
                                                    <path id="Path_3012" data-name="Path 3012"
                                                        d="M134.849,88.312h-8a2,2,0,0,0-2,2v5a2,2,0,0,0,2,2v3l2.4-3h5.6a2,2,0,0,0,2-2v-5a2,2,0,0,0-2-2m1,7a1,1,0,0,1-1,1h-8a1,1,0,0,1-1-1v-5a1,1,0,0,1,1-1h8a1,1,0,0,1,1,1Z"
                                                        transform="translate(-1178 -341)" fill="#b5b5bf" />
                                                    <path id="Path_3013" data-name="Path 3013"
                                                        d="M134.849,81.312h8a1,1,0,0,1,1,1v5a1,1,0,0,1-1,1h-.5a.5.5,0,0,0,0,1h.5a2,2,0,0,0,2-2v-5a2,2,0,0,0-2-2h-8a2,2,0,0,0-2,2v.5a.5.5,0,0,0,1,0v-.5a1,1,0,0,1,1-1"
                                                        transform="translate(-1182 -337)" fill="#b5b5bf" />
                                                    <path id="Path_3014" data-name="Path 3014"
                                                        d="M131.349,93.312h5a.5.5,0,0,1,0,1h-5a.5.5,0,0,1,0-1"
                                                        transform="translate(-1181 -343.5)" fill="#b5b5bf" />
                                                    <path id="Path_3015" data-name="Path 3015"
                                                        d="M131.349,99.312h5a.5.5,0,1,1,0,1h-5a.5.5,0,1,1,0-1"
                                                        transform="translate(-1181 -346.5)" fill="#b5b5bf" />
                                                </g>
                                            </svg>
                                            <span
                                                class="user-top-menu-name has-transition ml-3">{{ translate('Conversations') }}</span>
                                        </a>
                                    </li>
                                @endif

                                @if (get_setting('wallet_system') == 1)
                                    <li class="user-top-nav-element border border-top-0" data-id="1">
                                        <a href="{{ route('wallet.index') }}"
                                            class="text-truncate text-dark px-4 fs-14 d-flex align-items-center hov-column-gap-1">
                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                xmlns:xlink="http://www.w3.org/1999/xlink" width="16"
                                                height="16" viewBox="0 0 16 16">
                                                <defs>
                                                    <clipPath id="clip-path1">
                                                        <rect id="Rectangle_1386" data-name="Rectangle 1386"
                                                            width="16" height="16" fill="#b5b5bf" />
                                                    </clipPath>
                                                </defs>
                                                <g id="Group_8102" data-name="Group 8102"
                                                    clip-path="url(#clip-path1)">
                                                    <path id="Path_2936" data-name="Path 2936"
                                                        d="M13.5,4H13V2.5A2.5,2.5,0,0,0,10.5,0h-8A2.5,2.5,0,0,0,0,2.5v11A2.5,2.5,0,0,0,2.5,16h11A2.5,2.5,0,0,0,16,13.5v-7A2.5,2.5,0,0,0,13.5,4M2.5,1h8A1.5,1.5,0,0,1,12,2.5V4H2.5a1.5,1.5,0,0,1,0-3M15,11H10a1,1,0,0,1,0-2h5Zm0-3H10a2,2,0,0,0,0,4h5v1.5A1.5,1.5,0,0,1,13.5,15H2.5A1.5,1.5,0,0,1,1,13.5v-9A2.5,2.5,0,0,0,2.5,5h11A1.5,1.5,0,0,1,15,6.5Z"
                                                        fill="#b5b5bf" />
                                                </g>
                                            </svg>
                                            <span
                                                class="user-top-menu-name has-transition ml-3">{{ translate('My Wallet') }}</span>
                                        </a>
                                    </li>
                                @endif
                                <li class="user-top-nav-element border border-top-0" data-id="1">
                                    <a href="{{ route('support_ticket.index') }}"
                                        class="text-truncate text-dark px-4 fs-14 d-flex align-items-center hov-column-gap-1">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16.001"
                                            viewBox="0 0 16 16.001">
                                            <g id="Group_25259" data-name="Group 25259"
                                                transform="translate(-316 -1066)">
                                                <path id="Subtraction_184" data-name="Subtraction 184"
                                                    d="M16427.109,902H16420a8.015,8.015,0,1,1,8-8,8.278,8.278,0,0,1-1.422,4.535l1.244,2.132a.81.81,0,0,1,0,.891A.791.791,0,0,1,16427.109,902ZM16420,887a7,7,0,1,0,0,14h6.283c.275,0,.414,0,.549-.111s-.209-.574-.34-.748l0,0-.018-.022-1.064-1.6A6.829,6.829,0,0,0,16427,894a6.964,6.964,0,0,0-7-7Z"
                                                    transform="translate(-16096 180)" fill="#b5b5bf" />
                                                <path id="Union_12" data-name="Union 12"
                                                    d="M16414,895a1,1,0,1,1,1,1A1,1,0,0,1,16414,895Zm.5-2.5V891h.5a2,2,0,1,0-2-2h-1a3,3,0,1,1,3.5,2.958v.54a.5.5,0,1,1-1,0Zm-2.5-3.5h1a.5.5,0,1,1-1,0Z"
                                                    transform="translate(-16090.998 183.001)" fill="#b5b5bf" />
                                            </g>
                                        </svg>
                                        <span
                                            class="user-top-menu-name has-transition ml-3">{{ translate('Support Ticket') }}</span>
                                    </a>
                                </li>
                            @endif
                            <li class="user-top-nav-element border border-top-0" data-id="1">
                                <a href="{{ route('logout') }}"
                                    class="text-truncate text-dark px-4 fs-14 d-flex align-items-center hov-column-gap-1">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="15.999"
                                        viewBox="0 0 16 15.999">
                                        <g id="Group_25503" data-name="Group 25503"
                                            transform="translate(-24.002 -377)">
                                            <g id="Group_25265" data-name="Group 25265"
                                                transform="translate(-216.534 -160)">
                                                <path id="Subtraction_192" data-name="Subtraction 192"
                                                    d="M12052.535,2920a8,8,0,0,1-4.569-14.567l.721.72a7,7,0,1,0,7.7,0l.721-.72a8,8,0,0,1-4.567,14.567Z"
                                                    transform="translate(-11803.999 -2367)" fill="#d43533" />
                                            </g>
                                            <rect id="Rectangle_19022" data-name="Rectangle 19022" width="1"
                                                height="8" rx="0.5" transform="translate(31.5 377)"
                                                fill="#d43533" />
                                        </g>
                                    </svg>
                                    <span
                                        class="user-top-menu-name text-danger has-transition ml-3">{{ translate('Logout') }}</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>


        
    </div>

    <!-- Menu Bar -->
    <div class="d-none d-lg-block position-relative " style="box-shadow: 0 8px 12px -12px rgba(0, 0, 0, 0.75)">

        <!-- Categoty Menus -->
        <div class="hover-category-menu position-absolute w-100 top-100 left-0 right-0 z-3" id="click-category-menu"
            style="display: none;">
            <div class="container">
                <div class="d-flex position-relative">
                    <div class="position-static">
                        @include('frontend.' . get_setting('homepage_select') . '.partials.category_menu')
                    </div>
                </div>
            </div>
        </div>
    </div>

</header>




<!-- Top Menu Sidebar -->
<div class="aiz-top-menu-sidebar collapse-sidebar-wrap sidebar-xl sidebar-left d-lg-none z-1035">
    <div class="overlay overlay-fixed dark c-pointer" data-toggle="class-toggle" data-target=".aiz-top-menu-sidebar"
        data-same=".hide-top-menu-bar"></div>
    <div class="collapse-sidebar c-scrollbar-light text-left">
        <button type="button" class="btn btn-sm p-4 hide-top-menu-bar" data-toggle="class-toggle"
            data-target=".aiz-top-menu-sidebar">
            <i class="las la-times la-2x text-danger"></i>
        </button>
        @auth
            <span class="d-flex align-items-center nav-user-info pl-4">
                <!-- Image -->
                <span class="size-40px rounded-circle overflow-hidden border border-transparent nav-user-img">
                    @if ($user->avatar_original != null)
                        <img src="{{ $user_avatar }}" class="img-fit h-100" alt="{{ translate('avatar') }}"
                            onerror="this.onerror=null;this.src='{{ static_asset('assets/img/avatar-place.png') }}';">
                    @else
                        <img src="{{ static_asset('assets/img/avatar-place.png') }}" class="image img-fit"
                            alt="{{ translate('avatar') }}"
                            onerror="this.onerror=null;this.src='{{ static_asset('assets/img/avatar-place.png') }}';">
                    @endif
                </span>
                <!-- Name -->
                <h4 class="h5 fs-14 fw-700 text-dark ml-2 mb-0">{{ $user->name }}</h4>
            </span>
        @else
            <!--Login & Registration -->
            <span class="d-flex align-items-center nav-user-info pl-4">
                <!-- Image -->
                <span
                    class="size-40px rounded-circle overflow-hidden border d-flex align-items-center justify-content-center nav-user-img">
                    <svg xmlns="http://www.w3.org/2000/svg" width="19.902" height="20.012" viewBox="0 0 19.902 20.012">
                        <path id="fe2df171891038b33e9624c27e96e367"
                            d="M15.71,12.71a6,6,0,1,0-7.42,0,10,10,0,0,0-6.22,8.18,1.006,1.006,0,1,0,2,.22,8,8,0,0,1,15.9,0,1,1,0,0,0,1,.89h.11a1,1,0,0,0,.88-1.1,10,10,0,0,0-6.25-8.19ZM12,12a4,4,0,1,1,4-4A4,4,0,0,1,12,12Z"
                            transform="translate(-2.064 -1.995)" fill="#91919b" />
                    </svg>
                </span>
                <a href="{{ route('user.login') }}"
                    class="text-reset opacity-60 hov-opacity-100 hov-text-danger fs-12 d-inline-block border-right border-soft-light border-width-2 pr-2 ml-3">{{ translate('Login') }}</a>
                <a href="{{ route('user.registration') }}"
                    class="text-reset opacity-60 hov-opacity-100 hov-text-danger fs-12 d-inline-block py-2 pl-2">{{ translate('Registration') }}</a>
            </span>
        @endauth
        <hr>
        <ul class="mb-0 pl-3 pb-3 h-100">
            {{-- @php
                    $nav_txt_color = get_setting('header_nav_menu_text') == 'light' || get_setting('header_nav_menu_text') == null ? 'text-white' : 'text-dark';
                @endphp --}}

            {{-- <li class="mr-0">
                    <a href="" class="fs-13 px-3 py-3 w-100 d-inline-block fw-700 text-dark header_menu_links active">
                        Test
                    </a>
                </li> --}}
            <li class="pb-1 fs-15 px-3 fw-600">
                <a href="{{ route('buy_and_ship') }}" class="text-dark">
                    {{ translate('Buy & Ship For Me') }}
                </a>
            </li>
            <li class="pb-1 fs-15 px-3 fw-600">
                <a href="{{ route('ship_for_me') }}" class="text-dark">
                    {{ translate('Ship For Me') }}
                </a>
            </li>
            <li class="pb-1 fs-15 px-3 fw-600">
                <a href="{{ route('cost_calculator') }}" class="text-dark">
                    {{ translate('Cost Calculator') }}
                </a>
            </li>
            <li class="pb-1 fs-15 px-3 fw-600">
                <a href="{{ route('blog') }}" class="text-dark">
                    {{ translate('Blog') }}
                </a>
            </li>

            <hr>
            <li class="mr-0 fs-16 px-3 fw-800">
                Categories
            </li>
            <hr>

            @foreach (get_categories() as $key => $category)
                <li class="mr-0 category-nav-element-for-mobile" data-id="{{ $category['ExternalId'] }}">
                    <a href="javascript:void(0)"
                        class="fs-15 px-3 py-2 w-100 d-inline-flex justify-content-between align-items-center fw-600 text-dark header_menu_links load-subcategories"
                        data-categoryid="{{ $category['CategoryId'] }}" data-id="{{ $category['ExternalId'] }}">
                        <span>{{ $category['Name'] }}</span>
                        <i class="las la-plus"></i>
                    </a>
                    <div class="sub-categories-menu pl-4" style="display: none;">
                        <!-- Subcategories will be dynamically loaded here -->
                    </div>
                </li>
            @endforeach
            @auth
                @if (isAdmin())
                    <hr>
                    <li class="mr-0">
                        <a href="{{ route('admin.dashboard') }}"
                            class="fs-13 px-3 py-1 w-100 d-inline-block fw-700 text-dark header_menu_links">
                            {{ translate('My Account') }}
                        </a>
                    </li>
                @else
                    <hr>
                    <li class="mr-0">
                        <a href="{{ route('dashboard') }}"
                            class="fs-13 px-3 py-1 w-100 d-inline-block fw-700 text-dark header_menu_links
                                {{ areActiveRoutes(['dashboard'], ' active') }}">
                            {{ translate('My Account') }}
                        </a>
                    </li>
                @endif
                @if (isCustomer())
                    <li class="mr-0">
                        <a href="{{ route('all-notifications') }}"
                            class="fs-13 px-3 py-1 w-100 d-inline-block fw-700 text-dark header_menu_links
                                {{ areActiveRoutes(['all-notifications'], ' active') }}">
                            {{ translate('Notifications') }}
                        </a>
                    </li>
                    <li class="mr-0">
                        <a href="{{ route('wishlists.index') }}"
                            class="fs-13 px-3 py-1 w-100 d-inline-block fw-700 text-dark header_menu_links
                                {{ areActiveRoutes(['wishlists.index'], ' active') }}">
                            {{ translate('Wishlist') }}
                        </a>
                    </li>
                @endif
                <hr>
                <li class="mr-0">
                    <a href="{{ route('logout') }}"
                        class="fs-13 px-3 py-1 w-100 d-inline-block fw-700 text-danger header_menu_links">
                        {{ translate('Logout') }}
                    </a>
                </li>
            @endauth
        </ul>
        <br>
        <br>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="order_details" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
        <div class="modal-content">
            <div id="order-details-modal-body">

            </div>
        </div>
    </div>
</div>

@section('script')
    <script type="text/javascript">
        function show_order_details(order_id) {
            $('#order-details-modal-body').html(null);

            if (!$('#modal-size').hasClass('modal-lg')) {
                $('#modal-size').addClass('modal-lg');
            }

            $.post('{{ route('orders.details') }}', {
                _token: AIZ.data.csrf,
                order_id: order_id
            }, function(data) {
                $('#order-details-modal-body').html(data);
                $('#order_details').modal();
                $('.c-preloader').hide();
                AIZ.plugins.bootstrapSelect('refresh');
            });
        }
    </script>
@endsection
