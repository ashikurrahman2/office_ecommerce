@php
    $user = auth()->user();
    $user_avatar = null;
    $carts = [];
    if ($user && $user->avatar_original != null) {
        $user_avatar = uploaded_asset($user->avatar_original);
    }
@endphp

<div class="aiz-user-sidenav-wrap position-relative z-1 rounded-0">
    {{-- <div class="aiz-user-sidenav overflow-auto c-scrollbar-light px-4 pb-4">
        <!-- Close button -->
        <div class="d-xl-none">
            <button class="btn btn-sm p-2 " data-toggle="class-toggle" data-backdrop="static"
                data-target=".aiz-mobile-side-nav" data-same=".mobile-side-nav-thumb">
                <i class="las la-times la-2x"></i>
            </button>
        </div>
        @php
            $user = auth()->user();
            $user_avatar = null;
            $carts = [];
            if ($user && $user->avatar_original != null) {
                $user_avatar = uploaded_asset($user->avatar_original);
            }
        @endphp
        <!-- Customer info -->
        <div class="p-4 text-center mb-4 border-bottom position-relative">
            <!-- Image -->
            <span class="avatar avatar-md mb-3">
                @if ($user->avatar_original != null)
                    <img src="{{ $user_avatar }}"
                        onerror="this.onerror=null;this.src='{{ static_asset('assets/img/avatar-place.png') }}';">
                @else
                    <img src="{{ static_asset('assets/img/avatar-place.png') }}" class="image rounded-circle"
                        onerror="this.onerror=null;this.src='{{ static_asset('assets/img/avatar-place.png') }}';">
                @endif
            </span>
            <!-- Name -->
            <h4 class="h5 fs-14 mb-1 fw-700 text-dark">{{ $user->name }}</h4>
            <!-- Phone -->
            @if ($user->phone != null)
                <div class="text-truncate opacity-60 fs-12">{{ $user->phone }}</div>
            <!-- Email -->
            @else
                <div class="text-truncate opacity-60 fs-12">{{ $user->email }}</div>
            @endif
        </div>

        <!-- Menus -->
        <div class="sidemnenu">
            <ul class="aiz-side-nav-list mb-3 pb-3 border-bottom" data-toggle="aiz-side-menu">
                
                <!-- Dashboard -->
                <li class="aiz-side-nav-item">
                    <a href="{{ route('dashboard') }}" class="aiz-side-nav-link {{ areActiveRoutes(['dashboard']) }}">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16">
                            <g id="Group_24768" data-name="Group 24768" transform="translate(3495.144 -602)">
                              <path id="Path_2916" data-name="Path 2916" d="M15.3,5.4,9.561.481A2,2,0,0,0,8.26,0H7.74a2,2,0,0,0-1.3.481L.7,5.4A2,2,0,0,0,0,6.92V14a2,2,0,0,0,2,2H14a2,2,0,0,0,2-2V6.92A2,2,0,0,0,15.3,5.4M10,15H6V9A1,1,0,0,1,7,8H9a1,1,0,0,1,1,1Zm5-1a1,1,0,0,1-1,1H11V9A2,2,0,0,0,9,7H7A2,2,0,0,0,5,9v6H2a1,1,0,0,1-1-1V6.92a1,1,0,0,1,.349-.76l5.74-4.92A1,1,0,0,1,7.74,1h.52a1,1,0,0,1,.651.24l5.74,4.92A1,1,0,0,1,15,6.92Z" transform="translate(-3495.144 602)" fill="#b5b5bf"/>
                            </g>
                        </svg>
                        <span class="aiz-side-nav-text ml-3">{{ translate('Dashboard') }}</span>
                    </a>
                </li>

                @php
                    $delivery_viewed = get_count_by_delivery_viewed();
                    $payment_status_viewed = get_count_by_payment_status_viewed();
                @endphp

                <!-- Purchase History -->
                <li class="aiz-side-nav-item">
                    <a href="{{ route('purchase_history.index') }}"
                        class="aiz-side-nav-link {{ areActiveRoutes(['purchase_history.index', 'purchase_history.details']) }}">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16">
                            <g id="Group_8109" data-name="Group 8109" transform="translate(-27.466 -542.963)">
                                <path id="Path_2953" data-name="Path 2953" d="M14.5,5.963h-4a1.5,1.5,0,0,0,0,3h4a1.5,1.5,0,0,0,0-3m0,2h-4a.5.5,0,0,1,0-1h4a.5.5,0,0,1,0,1" transform="translate(22.966 537)" fill="#b5b5bf"/>
                                <path id="Path_2954" data-name="Path 2954" d="M12.991,8.963a.5.5,0,0,1,0-1H13.5a2.5,2.5,0,0,1,2.5,2.5v10a2.5,2.5,0,0,1-2.5,2.5H2.5a2.5,2.5,0,0,1-2.5-2.5v-10a2.5,2.5,0,0,1,2.5-2.5h.509a.5.5,0,0,1,0,1H2.5a1.5,1.5,0,0,0-1.5,1.5v10a1.5,1.5,0,0,0,1.5,1.5h11a1.5,1.5,0,0,0,1.5-1.5v-10a1.5,1.5,0,0,0-1.5-1.5Z" transform="translate(27.466 536)" fill="#b5b5bf"/>
                                <path id="Path_2955" data-name="Path 2955" d="M7.5,15.963h1a.5.5,0,0,1,.5.5v1a.5.5,0,0,1-.5.5h-1a.5.5,0,0,1-.5-.5v-1a.5.5,0,0,1,.5-.5" transform="translate(23.966 532)" fill="#b5b5bf"/>
                                <path id="Path_2956" data-name="Path 2956" d="M7.5,21.963h1a.5.5,0,0,1,.5.5v1a.5.5,0,0,1-.5.5h-1a.5.5,0,0,1-.5-.5v-1a.5.5,0,0,1,.5-.5" transform="translate(23.966 529)" fill="#b5b5bf"/>
                                <path id="Path_2957" data-name="Path 2957" d="M7.5,27.963h1a.5.5,0,0,1,.5.5v1a.5.5,0,0,1-.5.5h-1a.5.5,0,0,1-.5-.5v-1a.5.5,0,0,1,.5-.5" transform="translate(23.966 526)" fill="#b5b5bf"/>
                                <path id="Path_2958" data-name="Path 2958" d="M13.5,16.963h5a.5.5,0,0,1,0,1h-5a.5.5,0,0,1,0-1" transform="translate(20.966 531.5)" fill="#b5b5bf"/>
                                <path id="Path_2959" data-name="Path 2959" d="M13.5,22.963h5a.5.5,0,0,1,0,1h-5a.5.5,0,0,1,0-1" transform="translate(20.966 528.5)" fill="#b5b5bf"/>
                                <path id="Path_2960" data-name="Path 2960" d="M13.5,28.963h5a.5.5,0,0,1,0,1h-5a.5.5,0,0,1,0-1" transform="translate(20.966 525.5)" fill="#b5b5bf"/>
                            </g>
                        </svg>
                        <span class="aiz-side-nav-text ml-3">{{ translate('Purchase History') }}</span>
                        @if ($delivery_viewed > 0 || $payment_status_viewed > 0)
                            <span class="badge badge-inline badge-success">{{ translate('New') }}</span>
                        @endif
                    </a>
                </li>

                <!-- Refund Requests -->
                @if (addon_is_activated('refund_request'))
                    <li class="aiz-side-nav-item">
                        <a href="{{ route('customer_refund_request') }}"
                            class="aiz-side-nav-link {{ areActiveRoutes(['customer_refund_request']) }}">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16">
                                <g id="Group_8107" data-name="Group 8107" transform="translate(-134.153 -539.823)">
                                    <path id="Path_2951" data-name="Path 2951" d="M119.549,4.47h2.033a.5.5,0,0,0,0-1h-3.24a.5.5,0,0,0-.5.5v3.24a.5.5,0,0,0,1,0V5.189a7,7,0,1,1-4.155-1.366.5.5,0,0,0,0-1,8,8,0,1,0,4.862,1.647" transform="translate(27.466 537)" fill="#b5b5bf"/>
                                    <path id="Path_2952" data-name="Path 2952" d="M120.688,9.323v-1a.5.5,0,0,0-1,0v1a2,2,0,0,0-2,2v.5a2,2,0,0,0,2,2h1a1,1,0,0,1,1,1v.5a1,1,0,0,1-1,1h-1a1,1,0,0,1-1-1,.5.5,0,1,0-1,0,2,2,0,0,0,2,2v1a.5.5,0,0,0,1,0v-1a2,2,0,0,0,2-2v-.5a2,2,0,0,0-2-2h-1a1,1,0,0,1-1-1v-.5a1,1,0,0,1,1-1h1a1,1,0,0,1,1,1,.5.5,0,0,0,1,0,2,2,0,0,0-2-2" transform="translate(21.965 534.5)" fill="#b5b5bf"/>
                                </g>
                            </svg>
                            <span class="aiz-side-nav-text ml-3">{{ translate('Refund Requests') }}</span>
                        </a>
                    </li>
                @endif
    
                <!-- Wishlist -->
                <li class="aiz-side-nav-item">
                    <a href="{{ route('wishlists.index') }}"
                        class="aiz-side-nav-link {{ areActiveRoutes(['wishlists.index']) }}">
                        <svg id="Group_8116" data-name="Group 8116" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="16" height="14" viewBox="0 0 16 14">
                            <defs>
                                <clipPath id="clip-path">
                                <rect id="Rectangle_1391" data-name="Rectangle 1391" width="16" height="14" fill="#b5b5bf"/>
                                </clipPath>
                            </defs>
                            <g id="Group_8115" data-name="Group 8115" clip-path="url(#clip-path)">
                                <path id="Path_2981" data-name="Path 2981" d="M14.682,1.318a4.5,4.5,0,0,0-6.364,0L8,1.636l-.318-.318A4.5,4.5,0,0,0,1.318,7.682l6.046,6.054a.9.9,0,0,0,1.273,0l6.045-6.054a4.5,4.5,0,0,0,0-6.364m-.707,5.657L8,12.959,2.025,6.975a3.5,3.5,0,0,1,4.95-4.95l.389.389a.9.9,0,0,0,1.273,0l.388-.389a3.5,3.5,0,0,1,4.95,4.95" transform="translate(0 0)" fill="#b5b5bf"/>
                            </g>
                        </svg>
                        <span class="aiz-side-nav-text ml-3">{{ translate('Wishlist') }}</span>
                    </a>
                </li>
                <!-- My Request Products -->
                <li class="aiz-side-nav-item">
                    <a href="{{ route('product_request.index') }}"
                        class="aiz-side-nav-link {{ areActiveRoutes(['product_request.index']) }}">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#b5b5bf" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" transform="translate(0 0)">
                            <rect x="1" y="3" width="15" height="13" />
                            <polygon points="16 8 20 8 23 11 23 16 16 16" />
                            <circle cx="5.5" cy="18.5" r="2.5" />
                            <circle cx="18.5" cy="18.5" r="2.5" />
                        </svg>
                        <span class="aiz-side-nav-text ml-3">{{ translate('Ship For Me') }}</span>
                    </a>
                </li>

                <!-- Conversations -->
                @if (get_setting('conversation_system') == 1)
                    @php
                        $conversation = get_non_viewed_conversations();
                    @endphp
                    <li class="aiz-side-nav-item">
                        <a href="{{ route('conversations.index') }}"
                            class="aiz-side-nav-link {{ areActiveRoutes(['conversations.index', 'conversations.show']) }}">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16">
                                <g id="Group_8134" data-name="Group 8134" transform="translate(1053.151 256.688)">
                                    <path id="Path_3012" data-name="Path 3012" d="M134.849,88.312h-8a2,2,0,0,0-2,2v5a2,2,0,0,0,2,2v3l2.4-3h5.6a2,2,0,0,0,2-2v-5a2,2,0,0,0-2-2m1,7a1,1,0,0,1-1,1h-8a1,1,0,0,1-1-1v-5a1,1,0,0,1,1-1h8a1,1,0,0,1,1,1Z" transform="translate(-1178 -341)" fill="#b5b5bf"/>
                                    <path id="Path_3013" data-name="Path 3013" d="M134.849,81.312h8a1,1,0,0,1,1,1v5a1,1,0,0,1-1,1h-.5a.5.5,0,0,0,0,1h.5a2,2,0,0,0,2-2v-5a2,2,0,0,0-2-2h-8a2,2,0,0,0-2,2v.5a.5.5,0,0,0,1,0v-.5a1,1,0,0,1,1-1" transform="translate(-1182 -337)" fill="#b5b5bf"/>
                                    <path id="Path_3014" data-name="Path 3014" d="M131.349,93.312h5a.5.5,0,0,1,0,1h-5a.5.5,0,0,1,0-1" transform="translate(-1181 -343.5)" fill="#b5b5bf"/>
                                    <path id="Path_3015" data-name="Path 3015" d="M131.349,99.312h5a.5.5,0,1,1,0,1h-5a.5.5,0,1,1,0-1" transform="translate(-1181 -346.5)" fill="#b5b5bf"/>
                                </g>
                            </svg>
                            <span class="aiz-side-nav-text ml-3">{{ translate('Conversations') }}</span>
                            @if (count($conversation) > 0)
                                <span class="badge badge-success">({{ count($conversation) }})</span>
                            @endif
                        </a>
                    </li>
                @endif

                <!-- My Wallet -->
                @if (get_setting('wallet_system') == 1)
                    <li class="aiz-side-nav-item">
                        <a href="{{ route('wallet.index') }}"
                            class="aiz-side-nav-link {{ areActiveRoutes(['wallet.index']) }}">
                            <svg id="Group_8103" data-name="Group 8103" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="16" height="16" viewBox="0 0 16 16">
                                <defs>
                                    <clipPath id="clip-path">
                                    <rect id="Rectangle_1386" data-name="Rectangle 1386" width="16" height="16" fill="#b5b5bf"/>
                                    </clipPath>
                                </defs>
                                <g id="Group_8102" data-name="Group 8102" clip-path="url(#clip-path)">
                                    <path id="Path_2936" data-name="Path 2936" d="M13.5,4H13V2.5A2.5,2.5,0,0,0,10.5,0h-8A2.5,2.5,0,0,0,0,2.5v11A2.5,2.5,0,0,0,2.5,16h11A2.5,2.5,0,0,0,16,13.5v-7A2.5,2.5,0,0,0,13.5,4M2.5,1h8A1.5,1.5,0,0,1,12,2.5V4H2.5a1.5,1.5,0,0,1,0-3M15,11H10a1,1,0,0,1,0-2h5Zm0-3H10a2,2,0,0,0,0,4h5v1.5A1.5,1.5,0,0,1,13.5,15H2.5A1.5,1.5,0,0,1,1,13.5v-9A2.5,2.5,0,0,0,2.5,5h11A1.5,1.5,0,0,1,15,6.5Z" fill="#b5b5bf"/>
                                </g>
                            </svg>
                            <span class="aiz-side-nav-text ml-3">{{ translate('My Wallet') }}</span>
                        </a>
                    </li>
                @endif


                @php
                    $support_ticket = DB::table('tickets')
                        ->where('client_viewed', 0)
                        ->where('user_id', Auth::user()->id)
                        ->count();
                @endphp

                <!-- Support Ticket -->
                <li class="aiz-side-nav-item">
                    <a href="{{ route('support_ticket.index') }}"
                        class="aiz-side-nav-link {{ areActiveRoutes(['support_ticket.index', 'support_ticket.show']) }}">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16.001" viewBox="0 0 16 16.001">
                            <g id="Group_24764" data-name="Group 24764" transform="translate(-316 -1066)">
                                <path id="Subtraction_184" data-name="Subtraction 184" d="M16427.109,902H16420a8.015,8.015,0,1,1,8-8,8.278,8.278,0,0,1-1.422,4.535l1.244,2.132a.81.81,0,0,1,0,.891A.791.791,0,0,1,16427.109,902ZM16420,887a7,7,0,1,0,0,14h6.283c.275,0,.414,0,.549-.111s-.209-.574-.34-.748l0,0-.018-.022-1.064-1.6A6.829,6.829,0,0,0,16427,894a6.964,6.964,0,0,0-7-7Z" transform="translate(-16096 180)" fill="#b5b5bf"/>
                                <path id="Union_12" data-name="Union 12" d="M16414,895a1,1,0,1,1,1,1A1,1,0,0,1,16414,895Zm.5-2.5V891h.5a2,2,0,1,0-2-2h-1a3,3,0,1,1,3.5,2.958v.54a.5.5,0,1,1-1,0Zm-2.5-3.5h1a.5.5,0,1,1-1,0Z" transform="translate(-16090.998 183.001)" fill="#b5b5bf"/>
                            </g>
                        </svg>
                        <span class="aiz-side-nav-text ml-3">{{ translate('Support Ticket') }}</span>
                        @if ($support_ticket > 0)
                            <span class="badge badge-inline badge-success">{{ $support_ticket }}</span>
                        @endif
                    </a>
                </li>
                
                <!-- Manage Profile -->
                <li class="aiz-side-nav-item">
                    <a href="{{ route('profile') }}" class="aiz-side-nav-link {{ areActiveRoutes(['profile']) }}">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16">
                            <g id="Group_8094" data-name="Group 8094" transform="translate(3176 -602)">
                              <path id="Path_2924" data-name="Path 2924" d="M331.144,0a4,4,0,1,0,4,4,4,4,0,0,0-4-4m0,7a3,3,0,1,1,3-3,3,3,0,0,1-3,3" transform="translate(-3499.144 602)" fill="#b5b5bf"/>
                              <path id="Path_2925" data-name="Path 2925" d="M332.144,20h-10a3,3,0,0,0,0,6h10a3,3,0,0,0,0-6m0,5h-10a2,2,0,0,1,0-4h10a2,2,0,0,1,0,4" transform="translate(-3495.144 592)" fill="#b5b5bf"/>
                            </g>
                        </svg>
                        <span class="aiz-side-nav-text ml-3">{{ translate('Manage Profile') }}</span>
                    </a>
                </li>

                <!-- Delete My Account -->
                <li class="aiz-side-nav-item">
                    <a href="javascript:void(0)" onclick="account_delete_confirm_modal('{{ route('account_delete') }}')" class="aiz-side-nav-link">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16">
                            <g id="Group_25000" data-name="Group 25000" transform="translate(-240.535 -537)">
                            <path id="Path_2961" data-name="Path 2961" d="M221.069,0a8,8,0,1,0,8,8,8,8,0,0,0-8-8m0,15a7,7,0,1,1,7-7,7,7,0,0,1-7,7" transform="translate(27.466 537)" fill="#b5b5bf"/>
                            <rect id="Rectangle_18942" data-name="Rectangle 18942" width="8" height="1" rx="0.5" transform="translate(244.535 544.5)" fill="#b5b5bf"/>
                            </g>
                        </svg>
                        <span class="aiz-side-nav-text ml-3">{{ translate('Delete My Account') }}</span>
                    </a>
                </li>
            </ul>
        
            <!-- logout -->
            <a href="{{ route('logout') }}" class="btn btn-primary btn-block fs-14 fw-700 mb-5 mb-md-0" style="border-radius: 25px;">{{ translate('Sign Out') }}</a>
        </div>
    </div> --}}

    
    <!-- Sidebar -->
    <div class="user-sidebar p-3">
        <div class="pb-3 border-0">
            <h6 class="card-header bg-white pb-2 px-0">Dashboard</h6>
            <div class="card-body py-2">
                <a href="{{ route('dashboard') }}" class="pb-1 {{ areActiveRoutes(['dashboard']) }}">Dashboard</a>
            </div>
            


            <h6 class="card-header bg-white pb-2 px-0">Buy & Ship For Me</h6>
            <div class="card-body py-2">
                <a href="{{route('buy_ship_product_request.index')}}" class="pb-1 {{ areActiveRoutes(['buy_ship_product_request.index']) }}">My Request</a>
                <a href="{{ route('purchase_history.index') }}" class="pb-1 {{ areActiveRoutes(['purchase_history.index', 'purchase_history.details']) }}">My Order</a>
                <a href="{{ route('purchase_history.forward_parcel') }}" class="pb-1 {{ areActiveRoutes(['purchase_history.forward_parcel']) }}">My Forwarding Parcel</a>
                <a href="{{ route('purchase_history.delivered') }}" class="pb-1 {{ areActiveRoutes(['purchase_history.delivered']) }}">My Delivered Order</a>
                <a href="{{ route('purchase_history.cancelled') }}" class="pb-1 {{ areActiveRoutes(['purchase_history.cancelled']) }}">My Cancelled Order</a>
                {{-- <a href="#" class="pb-1">Action Needed</a> --}}
            </div>

            <h6 class="card-header bg-white pb-2 px-0">Ship For Me</h6>
            <div class="card-body py-2">
                <a href="{{ route('ship_product_request.index') }}" class="pb-1 {{ areActiveRoutes(['ship_product_request.index']) }}">My Request</a>
                <a href="{{ route('ship_product_request.forward_parcel') }}" class="pb-1 {{ areActiveRoutes(['ship_product_request.forward_parcel']) }}">My Forwarding Parcel</a>
                <a href="{{ route('ship_product_request.delivered') }}" class="pb-1 {{ areActiveRoutes(['ship_product_request.delivered']) }}">My Delivered Order</a>
            </div>

            <h6 class="card-header bg-white pb-2 px-0">My Wallet</h6>
            <div class="card-body py-2">
                <a href="{{ route('wallet.balance') }}" class="pb-1 {{ areActiveRoutes(['wallet.balance']) }}">My Balance</a>
                <a href="{{ route('wallet.index') }}" class="pb-1 {{ areActiveRoutes(['wallet.index']) }}">My Wallet History</a>
                {{-- <a href="#" class="pb-1">Refunds</a> --}}
            </div>
            <h6 class="card-header bg-white pb-2 px-0">Profile</h6>
            <div class="card-body py-2">
                <a href="{{ route('profile') }}" class="pb-1 {{ areActiveRoutes(['profile']) }}">Information</a>
                <a href="{{ route('security') }}" class="pb-1 {{ areActiveRoutes(['security']) }}">Security</a>
            </div>
        </div>

        <a href="{{ route('logout') }}" class="btn btn-danger btn-block fs-14">Logout</a>

        {{-- <div class="card pb-3 border-0">
            <h5 class="card-header pb-3 px-0">Ship For Me</h5>
            <div class="card-body py-2">
                <a href="#" class="pb-1">My Request</a>
                <a href="#" class="pb-1">My Forwarding Parcel</a>
                <a href="#" class="pb-1">My Delivered Order</a>
            </div>
        </div>

        <div class="card pb-3 border-0">
            <h5 class="card-header pb-3 px-0">My Wallet</h5>
            <div class="card-body py-2">
                <a href="#" class="pb-1">My Balance</a>
                <a href="#" class="pb-1">My Wallet History</a>
                <a href="#" class="pb-1">Refunds</a>
            </div>
        </div> --}}
    </div>
</div>
