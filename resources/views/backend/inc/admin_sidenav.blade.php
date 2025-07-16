<div class="aiz-sidebar-wrap">
    <div class="aiz-sidebar left c-scrollbar">
        <div class="aiz-side-nav-logo-wrap">
            <a href="{{ route('admin.dashboard') }}" class="d-block text-left">
                @if(get_setting('system_logo_white') != null)
                    <img class="mw-100" src="{{ uploaded_asset(get_setting('system_logo_white')) }}" class="brand-icon" alt="{{ get_setting('site_name') }}">
                @else
                    <img class="mw-100" src="{{ static_asset('assets/img/logo.png') }}" class="brand-icon" alt="{{ get_setting('site_name') }}">
                @endif
            </a>
        </div>
        <div class="aiz-side-nav-wrap">
            <div class="px-3 mb-3 position-relative">
                <input class="form-control bg-transparent rounded-2 form-control-sm text-white fs-14" type="text" name="" placeholder="{{ translate('Search in menu') }}" id="menu-search" onkeyup="menuSearch()">
                <span class="absolute-top-right pr-3 mr-3" style="margin-top: 10px;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16">
                        <path id="search_FILL0_wght200_GRAD0_opsz20" d="M176.921-769.231l6.255-6.255a5.99,5.99,0,0,0,1.733.949,5.687,5.687,0,0,0,1.885.329,5.317,5.317,0,0,0,3.9-1.608,5.31,5.31,0,0,0,1.609-3.9,5.322,5.322,0,0,0-1.608-3.9,5.306,5.306,0,0,0-3.9-1.611,5.321,5.321,0,0,0-3.9,1.609,5.312,5.312,0,0,0-1.611,3.9,5.554,5.554,0,0,0,.35,1.946,6.043,6.043,0,0,0,.929,1.672l-6.255,6.255Zm9.874-5.82a4.51,4.51,0,0,1-3.317-1.352,4.51,4.51,0,0,1-1.352-3.317,4.51,4.51,0,0,1,1.352-3.317,4.51,4.51,0,0,1,3.317-1.352,4.51,4.51,0,0,1,3.317,1.352,4.51,4.51,0,0,1,1.352,3.317,4.51,4.51,0,0,1-1.352,3.317A4.51,4.51,0,0,1,186.8-775.051Z" transform="translate(-176.307 785.231)" fill="#4e5767"/>
                    </svg>
                </span>
            </div>
            <ul class="aiz-side-nav-list" id="search-menu">
            </ul>
            <ul class="aiz-side-nav-list" id="main-menu" data-toggle="aiz-side-menu">
                
                <!--  Dashboard -->
                @can('admin_dashboard')
                    <li class="aiz-side-nav-item">
                        <a href="{{route('admin.dashboard')}}" class="aiz-side-nav-link">
                            <div class="aiz-side-nav-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16">
                                    <path id="_3d6902ec768df53cd9e274ca8a57e401" data-name="3d6902ec768df53cd9e274ca8a57e401" d="M18,12.286a1.715,1.715,0,0,0-1.714-1.714h-4a1.715,1.715,0,0,0-1.714,1.714v4A1.715,1.715,0,0,0,12.286,18h4A1.715,1.715,0,0,0,18,16.286Zm-8.571,0a1.715,1.715,0,0,0-1.714-1.714h-4A1.715,1.715,0,0,0,2,12.286v4A1.715,1.715,0,0,0,3.714,18h4a1.715,1.715,0,0,0,1.714-1.714Zm7.429,0v4a.57.57,0,0,1-.571.571h-4a.57.57,0,0,1-.571-.571v-4a.57.57,0,0,1,.571-.571h4a.57.57,0,0,1,.571.571Zm-8.571,0v4a.57.57,0,0,1-.571.571h-4a.57.57,0,0,1-.571-.571v-4a.57.57,0,0,1,.571-.571h4a.57.57,0,0,1,.571.571ZM9.429,3.714A1.715,1.715,0,0,0,7.714,2h-4A1.715,1.715,0,0,0,2,3.714v4A1.715,1.715,0,0,0,3.714,9.429h4A1.715,1.715,0,0,0,9.429,7.714Zm8.571,0A1.715,1.715,0,0,0,16.286,2h-4a1.715,1.715,0,0,0-1.714,1.714v4a1.715,1.715,0,0,0,1.714,1.714h4A1.715,1.715,0,0,0,18,7.714Zm-9.714,0v4a.57.57,0,0,1-.571.571h-4a.57.57,0,0,1-.571-.571v-4a.57.57,0,0,1,.571-.571h4a.57.57,0,0,1,.571.571Zm8.571,0v4a.57.57,0,0,1-.571.571h-4a.57.57,0,0,1-.571-.571v-4a.57.57,0,0,1,.571-.571h4a.57.57,0,0,1,.571.571Z" transform="translate(-2 -2)" fill="#575b6a" fill-rule="evenodd"/>
                                </svg>
                            </div>
                            <span class="aiz-side-nav-text">{{translate('Dashboard')}}</span>
                        </a>
                    </li>
                @endcan

                @can('view_product_categories')
                    <li class="aiz-side-nav-item">
                        <a href="{{route('categories.index')}}" class="aiz-side-nav-link">
                            <div class="aiz-side-nav-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16">
                                    <path id="ef567a7fa3ca8f4541f8ab7b62352aa6" d="M19,9.625a.638.638,0,0,0-.079-.307l-2.779-5A.614.614,0,0,0,15.606,4H6.394a.614.614,0,0,0-.536.318l-2.779,5A.638.638,0,0,0,3,9.625a2.5,2.5,0,0,0,1.231,2.153V18.75A1.24,1.24,0,0,0,5.462,20H9.08a1.24,1.24,0,0,0,1.231-1.25V16.058a.759.759,0,0,1,.615-.773.684.684,0,0,1,.534.176.706.706,0,0,1,.229.521V18.75A1.24,1.24,0,0,0,12.92,20h3.618a1.24,1.24,0,0,0,1.231-1.25V11.777A2.5,2.5,0,0,0,19,9.625Zm-1.239.149a1.23,1.23,0,0,1-2.453-.149.578.578,0,0,0-.017-.086.548.548,0,0,0-.006-.084L14.114,5.25h1.132ZM9.164,5.25h1.22V9.625a1.23,1.23,0,0,1-2.455.063Zm2.451,0h1.22l1.235,4.437a1.23,1.23,0,0,1-2.455-.062Zm-4.862,0H7.886l-1.169,4.2a.548.548,0,0,0-.006.084.578.578,0,0,0-.018.086,1.23,1.23,0,0,1-2.453.149Zm9.785,13.5H12.92V15.981a1.964,1.964,0,0,0-.635-1.446,1.9,1.9,0,0,0-1.482-.491A2,2,0,0,0,9.08,16.061V18.75H5.462V12.125a2.439,2.439,0,0,0,1.846-.848A2.419,2.419,0,0,0,11,11.261a2.419,2.419,0,0,0,3.692.016,2.439,2.439,0,0,0,1.846.848Z" transform="translate(-3 -4)" fill="#575b6a"/>
                                </svg>
                            </div>
                            <span class="aiz-side-nav-text">{{translate('Category')}}</span>
                        </a>
                    </li>
                @endcan
                <!-- Customers -->
                @canany(['view_all_customers','view_classified_products','view_classified_packages'])
                    <li class="aiz-side-nav-item">
                        <a href="{{ route('customers.index') }}" class="aiz-side-nav-link">
                            <div class="aiz-side-nav-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16">
                                    <path id="Path_40769" data-name="Path 40769" d="M8,10.667A2.667,2.667,0,1,1,10.667,8,2.667,2.667,0,0,1,8,10.667Zm0-4A1.333,1.333,0,1,0,9.333,8,1.333,1.333,0,0,0,8,6.667Zm4,8.667a4,4,0,1,0-8,0,.667.667,0,0,0,1.333,0,2.667,2.667,0,1,1,5.333,0,.667.667,0,0,0,1.333,0Zm0-10a2.667,2.667,0,1,1,2.667-2.667A2.667,2.667,0,0,1,12,5.333Zm0-4a1.333,1.333,0,1,0,1.333,1.333A1.333,1.333,0,0,0,12,1.333ZM16,10a4,4,0,0,0-4-4,.667.667,0,0,0,0,1.333A2.667,2.667,0,0,1,14.667,10,.667.667,0,1,0,16,10ZM4,5.333A2.667,2.667,0,1,1,6.667,2.667,2.667,2.667,0,0,1,4,5.333Zm0-4A1.333,1.333,0,1,0,5.333,2.667,1.333,1.333,0,0,0,4,1.333ZM1.333,10A2.667,2.667,0,0,1,4,7.333.667.667,0,0,0,4,6a4,4,0,0,0-4,4,.667.667,0,0,0,1.333,0Z" fill="#575b6a"/>
                                </svg>
                            </div>
                            <span class="aiz-side-nav-text">{{translate('Customers')}}</span>
                        </a>
                    </li>
                @endcanany
                {{-- Orders --}}
                @canany(['view_all_orders', 'view_inhouse_orders', 'view_seller_orders'])
                    <li class="aiz-side-nav-item">
                        <a href="{{ route('all_orders.index') }}" class="aiz-side-nav-link {{ areActiveRoutes(['all_orders.index', 'all_orders.show']) }}">
                            <div class="aiz-side-nav-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="15.997" height="16" viewBox="0 0 15.997 16">
                                    <g id="Layer_2" data-name="Layer 2" transform="translate(-2 -1.994)">
                                      <path id="Path_40726" data-name="Path 40726" d="M4.857,12.571H3.714A1.714,1.714,0,0,0,2,14.285V20.57a1.714,1.714,0,0,0,1.714,1.714H4.857A1.714,1.714,0,0,0,6.571,20.57V14.285a1.714,1.714,0,0,0-1.714-1.714Zm.571,8a.571.571,0,0,1-.571.571H3.714a.571.571,0,0,1-.571-.571V14.285a.571.571,0,0,1,.571-.571H4.857a.571.571,0,0,1,.571.571Zm5.142-6.284H9.427A1.714,1.714,0,0,0,7.713,16V20.57a1.714,1.714,0,0,0,1.714,1.714H10.57a1.714,1.714,0,0,0,1.714-1.714V16A1.714,1.714,0,0,0,10.57,14.285Zm.571,6.284a.571.571,0,0,1-.571.571H9.427a.571.571,0,0,1-.571-.571V16a.571.571,0,0,1,.571-.571H10.57a.571.571,0,0,1,.571.571ZM16.283,12H15.14a1.714,1.714,0,0,0-1.714,1.714V20.57a1.714,1.714,0,0,0,1.714,1.714h1.143A1.714,1.714,0,0,0,18,20.57V13.714A1.714,1.714,0,0,0,16.283,12Zm.571,8.57a.571.571,0,0,1-.571.571H15.14a.571.571,0,0,1-.571-.571V13.714a.571.571,0,0,1,.571-.571h1.143a.571.571,0,0,1,.571.571Z" transform="translate(0 -4.289)" fill="#575b6a"/>
                                      <path id="Path_40727" data-name="Path 40727" d="M17.947,2.548a.571.571,0,0,0-.366-.24l-1.588-.3a.571.571,0,1,0-.213,1.122l.093.018L11.233,5.932l-5.45-2.18a.572.572,0,1,0-.424,1.062L11.072,7.1a.571.571,0,0,0,.506-.041L16.68,4l-.067.354a.571.571,0,0,0,.457.668.579.579,0,0,0,.107.01.571.571,0,0,0,.56-.465l.3-1.588A.568.568,0,0,0,17.947,2.548Z" transform="translate(-1.286)" fill="#575b6a"/>
                                    </g>
                                </svg>
                            </div>
                            <span class="aiz-side-nav-text">{{ translate('Orders') }}</span>
                        </a>
                    </li>
                @endcanany

                {{-- Purchase --}}
                @can('view_purchase_orders')
                    <li class="aiz-side-nav-item">
                        <a href="{{ route('purchases.index') }}" class="aiz-side-nav-link">
                            <div class="aiz-side-nav-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="#575b6a" viewBox="0 0 24 24">
                                    <path d="M3 6l3 12h12l3-12H3zm3 0h12l-1 4H7L6 6z"/>
                                </svg>
                            </div>
                            <span class="aiz-side-nav-text">{{ translate('Purchase') }}</span>
                        </a>
                    </li>
                @endcan

                {{-- Tracking --}}
                @can('view_tracking_orders')
                    <li class="aiz-side-nav-item">
                        <a href="{{ route('tracking.index') }}" class="aiz-side-nav-link">
                            <div class="aiz-side-nav-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="#575b6a" viewBox="0 0 24 24">
                                    <path d="M12 2a10 10 0 100 20 10 10 0 000-20zm1 15h-2v-2h2v2zm0-4h-2V7h2v6z"/>
                                </svg>
                            </div>
                            <span class="aiz-side-nav-text">{{ translate('Tracking') }}</span>
                        </a>
                    </li>
                @endcan

                {{-- Shorting --}}
                @can('view_shorting_orders')
                    <li class="aiz-side-nav-item">
                        <a href="{{ route('shorting.index') }}" class="aiz-side-nav-link">
                            <div class="aiz-side-nav-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="#575b6a" viewBox="0 0 24 24">
                                    <path d="M3 4h18v2H3V4zm4 5h10v2H7V9zm-2 5h14v2H5v-2zm3 5h8v2H8v-2z"/>
                                </svg>
                            </div>
                            <span class="aiz-side-nav-text">{{ translate('Shorting') }}</span>
                        </a>
                    </li>
                @endcan

                {{-- Delivery --}}
                @can('view_delivery_orders')
                    <li class="aiz-side-nav-item">
                        <a href="{{ route('delivery.index') }}" class="aiz-side-nav-link">
                            <div class="aiz-side-nav-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="#575b6a" viewBox="0 0 24 24">
                                    <path d="M20 8h-3V4H3v16h18V8zm-5 10H5v-2h10v2zm0-4H5v-2h10v2zm5-4h-3V6h3v4z"/>
                                </svg>
                            </div>
                            <span class="aiz-side-nav-text">{{ translate('Delivery') }}</span>
                        </a>
                    </li>
                @endcan
              @canany(['in_house_product_sale_report','seller_products_sale_report','products_stock_report','product_wishlist_report','user_search_report','commission_history_report','wallet_transaction_report'])
                <li class="aiz-side-nav-item">
                    <a href="#" class="aiz-side-nav-link">
                        <div class="aiz-side-nav-icon">
                            <svg id="stats_3916778" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16">
                                <path id="Path_40739" data-name="Path 40739" d="M16,16H2a2,2,0,0,1-2-2V0H1.333V14A.667.667,0,0,0,2,14.667H16Z" fill="#575b6a"/>
                                <rect id="Rectangle_21340" data-name="Rectangle 21340" width="1.333" height="6" transform="translate(9.333 7.333)" fill="#575b6a"/>
                                <rect id="Rectangle_21341" data-name="Rectangle 21341" width="1.333" height="6" transform="translate(4 7.333)" fill="#575b6a"/>
                                <rect id="Rectangle_21342" data-name="Rectangle 21342" width="1.333" height="9.333" transform="translate(12 4)" fill="#575b6a"/>
                                <rect id="Rectangle_21343" data-name="Rectangle 21343" width="1.333" height="9.333" transform="translate(6.667 4)" fill="#575b6a"/>
                            </svg>
                        </div>
                        <span class="aiz-side-nav-text">{{ translate('Reports') }}</span>
                        <span class="aiz-side-nav-arrow"></span>
                    </a>
                    <ul class="aiz-side-nav-list level-2">
                        @can('profit_loss_report')
                            <li class="aiz-side-nav-item">
                                <a href="{{ route('sales_report.index') }}" class="aiz-side-nav-link {{ areActiveRoutes(['sales_report.index'])}}">
                                    <span class="aiz-side-nav-text">{{ translate('Profit &  Loss') }}</span>
                                </a
                            </li>
                        @endcan
                        @can('product_wishlist_report')
                            <li class="aiz-side-nav-item">
                                <a href="{{ route('wish_report.index') }}" class="aiz-side-nav-link {{ areActiveRoutes(['wish_report.index'])}}">
                                    <span class="aiz-side-nav-text">{{ translate('Products wishlist') }}</span>
                                </a>
                            </li>
                        @endcan
                       
                    </ul>
                </li>
                @endcanany
                {{-- Rate --}}
                @can('view_rates_profit')
                    <li class="aiz-side-nav-item">
                        <a href="{{ route('rates.index') }}" class="aiz-side-nav-link">
                            <div class="aiz-side-nav-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="#575b6a" viewBox="0 0 24 24">
                                    <path d="M3 17h18v2H3v-2zm0-5h12v2H3v-2zm0-5h8v2H3V7z"/>
                                </svg>
                            </div>
                            <span class="aiz-side-nav-text">{{ translate('Rate & Profit') }}</span>
                        </a>
                    </li>
                @endcan
 <!-- Staffs -->
                @canany(['view_all_staffs','view_staff_roles'])
                    <li class="aiz-side-nav-item">
                        <a href="#" class="aiz-side-nav-link">
                            <div class="aiz-side-nav-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16">
                                    <g id="Group_28314" data-name="Group 28314" transform="translate(-19299 2175)">
                                      <path id="Path_40774" data-name="Path 40774" d="M87.867,3.07H84.133V1.72A.716.716,0,0,0,83.422,1H80.578a.716.716,0,0,0-.711.72V3.07H76.133A2.149,2.149,0,0,0,74,5.229V14.84A2.149,2.149,0,0,0,76.133,17H87.867A2.149,2.149,0,0,0,90,14.84V5.229A2.149,2.149,0,0,0,87.867,3.07Zm-6.578-.63h1.422V3.79a.711.711,0,1,1-1.422,0Zm7.289,12.4a.716.716,0,0,1-.711.72H76.133a.716.716,0,0,1-.711-.72V5.229a.716.716,0,0,1,.711-.72h3.856a2.124,2.124,0,0,0,4.022,0h3.856a.716.716,0,0,1,.711.72Z" transform="translate(19225 -2176)" fill="#575b6a"/>
                                      <g id="Group_28312" data-name="Group 28312" transform="translate(19305.07 -2169.197)">
                                        <path id="Path_40775" data-name="Path 40775" d="M199.864,197.932a1.932,1.932,0,1,0-1.932,1.932A1.934,1.934,0,0,0,199.864,197.932Zm-1.932.644a.644.644,0,1,1,.644-.644A.645.645,0,0,1,197.932,198.576Z" transform="translate(-196 -196)" fill="#575b6a"/>
                                      </g>
                                      <g id="Group_28313" data-name="Group 28313" transform="translate(19303.779 -2165)">
                                        <path id="Path_40776" data-name="Path 40776" d="M160.508,316h-2.576A1.934,1.934,0,0,0,156,317.932v1.288a.644.644,0,1,0,1.288,0v-1.288a.645.645,0,0,1,.644-.644h2.576a.645.645,0,0,1,.644.644v1.288a.644.644,0,1,0,1.288,0v-1.288A1.934,1.934,0,0,0,160.508,316Z" transform="translate(-156 -316)" fill="#575b6a"/>
                                      </g>
                                    </g>
                                </svg>
                            </div>
                            <span class="aiz-side-nav-text">{{translate('Staffs')}}</span>
                            <span class="aiz-side-nav-arrow"></span>
                        </a>
                        <ul class="aiz-side-nav-list level-2">
                            @can('view_all_staffs')
                                <li class="aiz-side-nav-item">
                                    <a href="{{ route('staffs.index') }}" class="aiz-side-nav-link {{ areActiveRoutes(['staffs.index', 'staffs.create', 'staffs.edit'])}}">
                                        <span class="aiz-side-nav-text">{{translate('All staffs')}}</span>
                                    </a>
                                </li>
                            @endcan
                            @can('view_staff_roles')
                                <li class="aiz-side-nav-item">
                                    <a href="{{route('roles.index')}}" class="aiz-side-nav-link {{ areActiveRoutes(['roles.index', 'roles.create', 'roles.edit'])}}">
                                        <span class="aiz-side-nav-text">{{translate('Staff permissions')}}</span>
                                    </a>
                                </li>
                            @endcan
                        </ul>
                    </li>
                @endcanany
                {{-- Profit --}}
                 @can('view_manual_payment')
                    <li class="aiz-side-nav-item">
                        <a href="{{ route('payment-methods.index') }}" class="aiz-side-nav-link">
                            <div class="aiz-side-nav-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="#575b6a" viewBox="0 0 24 24">
                                    <path d="M4 4h16v2H4V4zm0 14h16v2H4v-2zm0-7h16v2H4v-2z"/>
                                </svg>
                            </div>
                            <span class="aiz-side-nav-text">{{ translate('Manual Payment Method') }}</span>
                        </a>
                    </li>
                @endcan 

                <!-- Sale -->
                {{-- @canany(['view_all_orders', 'view_inhouse_orders','view_seller_orders','view_pickup_point_orders'])
                    <li class="aiz-side-nav-item">
                        <a href="#" class="aiz-side-nav-link">
                            <div class="aiz-side-nav-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="15.997" height="16" viewBox="0 0 15.997 16">
                                    <g id="Layer_2" data-name="Layer 2" transform="translate(-2 -1.994)">
                                      <path id="Path_40726" data-name="Path 40726" d="M4.857,12.571H3.714A1.714,1.714,0,0,0,2,14.285V20.57a1.714,1.714,0,0,0,1.714,1.714H4.857A1.714,1.714,0,0,0,6.571,20.57V14.285a1.714,1.714,0,0,0-1.714-1.714Zm.571,8a.571.571,0,0,1-.571.571H3.714a.571.571,0,0,1-.571-.571V14.285a.571.571,0,0,1,.571-.571H4.857a.571.571,0,0,1,.571.571Zm5.142-6.284H9.427A1.714,1.714,0,0,0,7.713,16V20.57a1.714,1.714,0,0,0,1.714,1.714H10.57a1.714,1.714,0,0,0,1.714-1.714V16A1.714,1.714,0,0,0,10.57,14.285Zm.571,6.284a.571.571,0,0,1-.571.571H9.427a.571.571,0,0,1-.571-.571V16a.571.571,0,0,1,.571-.571H10.57a.571.571,0,0,1,.571.571ZM16.283,12H15.14a1.714,1.714,0,0,0-1.714,1.714V20.57a1.714,1.714,0,0,0,1.714,1.714h1.143A1.714,1.714,0,0,0,18,20.57V13.714A1.714,1.714,0,0,0,16.283,12Zm.571,8.57a.571.571,0,0,1-.571.571H15.14a.571.571,0,0,1-.571-.571V13.714a.571.571,0,0,1,.571-.571h1.143a.571.571,0,0,1,.571.571Z" transform="translate(0 -4.289)" fill="#575b6a"/>
                                      <path id="Path_40727" data-name="Path 40727" d="M17.947,2.548a.571.571,0,0,0-.366-.24l-1.588-.3a.571.571,0,1,0-.213,1.122l.093.018L11.233,5.932l-5.45-2.18a.572.572,0,1,0-.424,1.062L11.072,7.1a.571.571,0,0,0,.506-.041L16.68,4l-.067.354a.571.571,0,0,0,.457.668.579.579,0,0,0,.107.01.571.571,0,0,0,.56-.465l.3-1.588A.568.568,0,0,0,17.947,2.548Z" transform="translate(-1.286)" fill="#575b6a"/>
                                    </g>
                                </svg>
                            </div>
                            <span class="aiz-side-nav-text">{{translate('Sales')}}</span>
                            <span class="aiz-side-nav-arrow"></span>
                        </a>
                        <!--Submenu-->
                        <ul class="aiz-side-nav-list level-2">
                            @php
                                $canViewOrders = auth()->user()->can('view_all_orders') || 
                                                 auth()->user()->can('view_inhouse_orders') || 
                                                 auth()->user()->can('view_seller_orders');
                            @endphp
                            
                            @if ($canViewOrders)
                                <li class="aiz-side-nav-item">
                                    <a href="{{ route('all_orders.index') }}" class="aiz-side-nav-link {{ areActiveRoutes(['all_orders.index', 'all_orders.show'])}}">
                                        <span class="aiz-side-nav-text">{{translate('Orders')}}</span>
                                    </a>
                                </li>
                            @endif

                            @can('view_inhouse_orders')
                                <li class="aiz-side-nav-item">
                                    <a href="{{ route('admin.ship_for_me.index') }}" class="aiz-side-nav-link {{ areActiveRoutes(['admin.ship_for_me.index'])}}">
                                        <span class="aiz-side-nav-text">{{translate('Ship For Me')}}</span>
                                    </a>
                                </li>
                            @endcan
                            @can('view_seller_orders')
                                <li class="aiz-side-nav-item">
                                    <a href="{{ route('admin.buy_ship_for_me.index') }}" class="aiz-side-nav-link {{ areActiveRoutes(['admin.buy_ship_for_me.index'])}}">
                                        <span class="aiz-side-nav-text">{{translate('Buy & Ship')}}</span>
                                    </a>
                                </li>
                            @endcan
                           
                        </ul>
                    </li>
                @endcanany --}}

               

                

                <!-- Reports -->
              
                <!--  Uploads Files -->
                <li class="aiz-side-nav-item">
                    <a href="{{ route('uploaded-files.index') }}" class="aiz-side-nav-link {{ areActiveRoutes(['uploaded-files.create'])}}">
                        <div class="aiz-side-nav-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16">
                                <g id="layer1" transform="translate(-0.53 -0.53)">
                                  <path id="path3159" d="M3.386.53A2.862,2.862,0,0,0,.53,3.386V13.67a2.865,2.865,0,0,0,2.856,2.86H13.67a2.869,2.869,0,0,0,2.86-2.86V3.386A2.865,2.865,0,0,0,13.67.53Zm0,1.143H13.67a1.7,1.7,0,0,1,1.718,1.713V13.67a1.7,1.7,0,0,1-1.718,1.718H3.386A1.7,1.7,0,0,1,1.673,13.67V3.386A1.7,1.7,0,0,1,3.386,1.673ZM8.12,3.557,5.34,6.37a.572.572,0,0,0,0,.809.564.564,0,0,0,.81,0l1.8-1.824V10.8a.571.571,0,0,0,1.143,0V5.347l1.8,1.829a.571.571,0,0,0,.81-.806L8.935,3.557a.511.511,0,0,0-.815,0Zm-4.156,8.97a.571.571,0,0,0,0,1.143h9.128a.571.571,0,0,0,0-1.143Z" fill="#575b6a"/>
                                </g>
                            </svg>
                        </div>
                        <span class="aiz-side-nav-text">{{ translate('Uploaded Files') }}</span>
                    </a>
                </li>
                <!--Blog System-->
                @canany(['view_blogs','view_blog_categories'])
                    <li class="aiz-side-nav-item">
                        <a href="#" class="aiz-side-nav-link">
                            <div class="aiz-side-nav-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16">
                                    <path id="Path_40771" data-name="Path 40771" d="M9.688,16H3.75A3.754,3.754,0,0,1,0,12.25V3.75A3.754,3.754,0,0,1,3.75,0h8.5A3.754,3.754,0,0,1,16,3.75V9.734a.625.625,0,0,1-1.25,0V3.75a2.5,2.5,0,0,0-2.5-2.5H3.75a2.5,2.5,0,0,0-2.5,2.5v8.5a2.5,2.5,0,0,0,2.5,2.5H9.688a.625.625,0,0,1,0,1.25ZM12.875,3.938a.625.625,0,0,0-.625-.625H6.531a.625.625,0,0,0,0,1.25H12.25A.625.625,0,0,0,12.875,3.938Zm0,2.5a.625.625,0,0,0-.625-.625H3.75a.625.625,0,0,0,0,1.25h8.5A.625.625,0,0,0,12.875,6.438Zm-6.25,2.5A.625.625,0,0,0,6,8.313H3.75a.625.625,0,0,0,0,1.25H6A.625.625,0,0,0,6.625,8.938Zm-3.5-5.062a.781.781,0,1,0,.781-.781A.781.781,0,0,0,3.125,3.875ZM15.332,15.332a2.284,2.284,0,0,0,0-3.226L13.141,9.915a4.506,4.506,0,0,0-2.31-1.236L9.06,8.325a.625.625,0,0,0-.735.735l.354,1.771a4.506,4.506,0,0,0,1.236,2.31l2.191,2.191a2.281,2.281,0,0,0,3.226,0ZM10.586,9.9a3.259,3.259,0,0,1,1.671.894l2.191,2.191a1.031,1.031,0,1,1-1.458,1.458L10.8,12.257A3.26,3.26,0,0,1,9.9,10.586l-.17-.852Z" fill="#575b6a"/>
                                </svg>
                            </div>
                            <span class="aiz-side-nav-text">{{ translate('Blog System') }}</span>
                            <span class="aiz-side-nav-arrow"></span>
                        </a>
                        <ul class="aiz-side-nav-list level-2">
                            @can('view_blogs')
                                <li class="aiz-side-nav-item">
                                    <a href="{{ route('blog.index') }}" class="aiz-side-nav-link {{ areActiveRoutes(['blog.create', 'blog.edit'])}}">
                                        <span class="aiz-side-nav-text">{{ translate('All Posts') }}</span>
                                    </a>
                                </li>
                            @endcan
                            @can('view_blog_categories')
                                <li class="aiz-side-nav-item">
                                    <a href="{{ route('blog-category.index') }}" class="aiz-side-nav-link {{ areActiveRoutes(['blog-category.create', 'blog-category.edit'])}}">
                                        <span class="aiz-side-nav-text">{{ translate('Categories') }}</span>
                                    </a>
                                </li>
                            @endcan
                        </ul>
                    </li>
                @endcanany 

                <!-- marketing -->
                @canany(['view_all_flash_deals','send_newsletter','send_bulk_sms','view_all_subscribers','view_all_coupons'])
                    <li class="aiz-side-nav-item">
                        <a href="#" class="aiz-side-nav-link">
                            <div class="aiz-side-nav-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16">
                                    <g id="_8dbc7a38f7bdee3f0be2c44d010760a2" data-name="8dbc7a38f7bdee3f0be2c44d010760a2" transform="translate(0 -4.027)">
                                      <path id="Path_40740" data-name="Path 40740" d="M38.286,16.393a.555.555,0,0,1-.344-.119L34.032,13.2a.557.557,0,0,1-.213-.438v-5.1a.556.556,0,0,1,.212-.438l3.91-3.074a.557.557,0,0,1,.9.438V15.836a.556.556,0,0,1-.556.557Zm-3.354-3.9,2.8,2.2V5.73l-2.8,2.2Z" transform="translate(-25.364 0)" fill="#575b6a"/>
                                      <path id="Path_40741" data-name="Path 40741" d="M9.011,22.556H3.093a3.1,3.1,0,0,1,0-6.192H9.011a.557.557,0,0,1,.557.557V22A.557.557,0,0,1,9.011,22.556ZM3.093,17.478a1.982,1.982,0,0,0,0,3.964H8.455V17.478Z" transform="translate(0 -9.25)" fill="#575b6a"/>
                                      <path id="Path_40742" data-name="Path 40742" d="M10.2,31.9a1.895,1.895,0,0,1-1.847-1.5l-.974-5.455a.557.557,0,1,1,1.089-.229l.975,5.455a.777.777,0,1,0,1.521-.32l-.824-4.74a.557.557,0,1,1,1.089-.229l.824,4.74A1.894,1.894,0,0,1,10.2,31.9Zm8.487-7.6h-.862a.557.557,0,0,1,0-1.114h.862a1.105,1.105,0,0,0,1.1-1.105,1.106,1.106,0,0,0-1.1-1.105h-.862a.557.557,0,0,1,0-1.114h.862a2.22,2.22,0,0,1,1.566,3.79A2.2,2.2,0,0,1,18.683,24.3Z" transform="translate(-4.9 -11.875)" fill="#575b6a"/>
                                    </g>
                                </svg>
                            </div>
                            <span class="aiz-side-nav-text">{{ translate('Marketing') }}</span>
                            <span class="aiz-side-nav-arrow"></span>
                        </a>
                        <ul class="aiz-side-nav-list level-2">
                            @can('send_newsletter')
                                <li class="aiz-side-nav-item">
                                    <a href="{{route('newsletters.index')}}" class="aiz-side-nav-link">
                                        <span class="aiz-side-nav-text">{{ translate('Newsletters') }}</span>
                                    </a>
                                </li>
                            @endcan
                            @if (addon_is_activated('otp_system') && auth()->user()->can('send_bulk_sms'))
                                <li class="aiz-side-nav-item">
                                    <a href="{{route('sms.index')}}" class="aiz-side-nav-link">
                                        <span class="aiz-side-nav-text">{{ translate('Bulk SMS') }}</span>
                                        @if (env("DEMO_MODE") == "On")
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="14.001" viewBox="0 0 16 14.001" class="mx-2">
                                                <path id="Union_49" data-name="Union 49" d="M-19322,3342.5v-5a2.007,2.007,0,0,0-2-2v1.5a3,3,0,0,1-3,3h-4v-10h4a3,3,0,0,1,3,3v1.5a3,3,0,0,1,3,3v5a.506.506,0,0,1-.5.5A.5.5,0,0,1-19322,3342.5Zm-11-2V3339h-3a1,1,0,0,1-1-1,1,1,0,0,1,1-1h3v-7.5a.5.5,0,0,1,.5-.5.5.5,0,0,1,.5.5v11a.5.5,0,0,1-.5.5A.506.506,0,0,1-19333,3340.5Zm-3-7.5a1,1,0,0,1-1-1,1,1,0,0,1,1-1h3v2Z" transform="translate(19337 -3329)" fill="#f51350"/>
                                            </svg>
                                        @endif
                                    </a>
                                </li>
                            @endif
                            @can('view_all_subscribers')
                                <li class="aiz-side-nav-item">
                                    <a href="{{ route('subscribers.index') }}" class="aiz-side-nav-link">
                                        <span class="aiz-side-nav-text">{{ translate('Subscribers') }}</span>
                                    </a>
                                </li>
                            @endcan
                            @if (get_setting('coupon_system') == 1 && auth()->user()->can('view_all_coupons') )
                            <li class="aiz-side-nav-item">
                                <a href="{{route('coupon.index')}}" class="aiz-side-nav-link {{ areActiveRoutes(['coupon.index','coupon.create','coupon.edit'])}}">
                                    <span class="aiz-side-nav-text">{{ translate('Coupon') }}</span>
                                </a>
                            </li>
                            @endif
                        </ul>
                    </li>
                @endcanany

           
                 <!--OTP -->
                 @canany(['otp_configurations','sms_templates','sms_providers_configurations','send_bulk_sms'])
                     <li class="aiz-side-nav-item">
                         <a href="#" class="aiz-side-nav-link">
                             <div class="aiz-side-nav-icon">
                                 <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16">
                                     <path id="pin-code" d="M4.25,12.25a.625.625,0,0,1,.625.625h0a.625.625,0,1,1-1.25,0h0A.625.625,0,0,1,4.25,12.25Zm1.875.625h0a.625.625,0,1,0,1.25,0h0a.625.625,0,1,0-1.25,0Zm2.5,0h0a.625.625,0,1,0,1.25,0h0a.625.625,0,1,0-1.25,0Zm2.5,0h0a.625.625,0,1,0,1.25,0h0a.625.625,0,0,0-1.25,0Zm3-3.046a.625.625,0,0,0-.312,1.211,1.249,1.249,0,0,1,.937,1.211V13.5a1.251,1.251,0,0,1-1.25,1.25H2.5A1.251,1.251,0,0,1,1.25,13.5V12.25a1.257,1.257,0,0,1,.9-1.2.625.625,0,1,0-.354-1.2,2.518,2.518,0,0,0-1.284.888A2.478,2.478,0,0,0,0,12.25V13.5A2.5,2.5,0,0,0,2.5,16h11A2.5,2.5,0,0,0,16,13.5V12.25A2.5,2.5,0,0,0,14.125,9.829Zm-10.562-.7V5.749A1.877,1.877,0,0,1,5.437,3.874h.124V2.387a2.438,2.438,0,0,1,4.875,0V3.874h.126a1.877,1.877,0,0,1,1.875,1.875V9.124A1.877,1.877,0,0,1,10.562,11H5.437A1.877,1.877,0,0,1,3.562,9.124Zm3.249-5.25H9.187V2.387a1.189,1.189,0,0,0-2.375,0V3.874Zm-2,5.25a.626.626,0,0,0,.625.625h5.125a.626.626,0,0,0,.625-.625V5.749a.626.626,0,0,0-.625-.625H5.437a.626.626,0,0,0-.625.625ZM8,8.125A.625.625,0,0,0,8.625,7.5h0a.625.625,0,0,0-1.25,0h0A.625.625,0,0,0,8,8.125Z" transform="translate(0)" fill="#575b6a"/>
                                 </svg>
                             </div>
                             <span class="aiz-side-nav-text">{{translate('OTP System')}}</span>
                             @if (env("DEMO_MODE") == "On")
                                 <svg xmlns="http://www.w3.org/2000/svg" width="16" height="14.001" viewBox="0 0 16 14.001" class="mx-2">
                                     <path id="Union_49" data-name="Union 49" d="M-19322,3342.5v-5a2.007,2.007,0,0,0-2-2v1.5a3,3,0,0,1-3,3h-4v-10h4a3,3,0,0,1,3,3v1.5a3,3,0,0,1,3,3v5a.506.506,0,0,1-.5.5A.5.5,0,0,1-19322,3342.5Zm-11-2V3339h-3a1,1,0,0,1-1-1,1,1,0,0,1,1-1h3v-7.5a.5.5,0,0,1,.5-.5.5.5,0,0,1,.5.5v11a.5.5,0,0,1-.5.5A.506.506,0,0,1-19333,3340.5Zm-3-7.5a1,1,0,0,1-1-1,1,1,0,0,1,1-1h3v2Z" transform="translate(19337 -3329)" fill="#f51350"/>
                                 </svg>
                             @endif
                             <span class="aiz-side-nav-arrow"></span>
                         </a>
                         <ul class="aiz-side-nav-list level-2">
                             @can('otp_configurations')
                                 <li class="aiz-side-nav-item">
                                     <a href="{{ route('otp.configconfiguration') }}" class="aiz-side-nav-link">
                                         <span class="aiz-side-nav-text">{{translate('OTP Configurations')}}</span>
                                     </a>
                                 </li>
                             @endcan
                             @can('sms_templates')
                                 <li class="aiz-side-nav-item">
                                     <a href="{{route('sms-templates.index')}}" class="aiz-side-nav-link">
                                         <span class="aiz-side-nav-text">{{translate('SMS Templates')}}</span>
                                     </a>
                                 </li>
                             @endcan
                             @can('sms_providers_configurations')
                                 <li class="aiz-side-nav-item">
                                     <a href="{{route('otp_credentials.index')}}" class="aiz-side-nav-link">
                                         <span class="aiz-side-nav-text">{{translate('Set OTP Credentials')}}</span>
                                     </a>
                                 </li>
                             @endcan
                         </ul>
                     </li>
                 @endcanany
                 
                <!-- Website Setup -->
                @canany(['header_setup','footer_setup','view_all_website_pages','website_appearance','authentication_layout_settings'])
                    <li class="aiz-side-nav-item">
                        <a href="#" class="aiz-side-nav-link {{ areActiveRoutes(['website.footer', 'website.header'])}}" >
                            <div class="aiz-side-nav-icon">
                                <svg id="Group_28315" data-name="Group 28315" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16">
                                    <circle id="Ellipse_893" data-name="Ellipse 893" cx="0.625" cy="0.625" r="0.625" transform="translate(7.375 6.125)" fill="#575b6a"/>
                                    <path id="Path_40777" data-name="Path 40777" d="M13.5,0H2.5A2.5,2.5,0,0,0,0,2.5V11a2.5,2.5,0,0,0,2.5,2.5H7.375v1.25H5.5A.625.625,0,0,0,5.5,16h5a.625.625,0,0,0,0-1.25H8.625V12.875A.625.625,0,0,0,8,12.25H2.5A1.251,1.251,0,0,1,1.25,11V2.5A1.251,1.251,0,0,1,2.5,1.25h11A1.251,1.251,0,0,1,14.75,2.5V11a1.251,1.251,0,0,1-1.25,1.25h-3a.625.625,0,0,0,0,1.25h3A2.5,2.5,0,0,0,16,11V2.5A2.5,2.5,0,0,0,13.5,0Z" fill="#575b6a"/>
                                    <path id="Path_40778" data-name="Path 40778" d="M120.375,84.75a.625.625,0,0,0,.625-.625v-.688a3.107,3.107,0,0,0,1.1-.456l.487.487a.625.625,0,0,0,.884-.884l-.487-.487a3.108,3.108,0,0,0,.456-1.1h.688a.625.625,0,1,0,0-1.25h-.688a3.108,3.108,0,0,0-.456-1.1l.487-.487a.625.625,0,0,0-.884-.884l-.487.487a3.107,3.107,0,0,0-1.1-.456v-.688a.625.625,0,0,0-1.25,0v.688a3.108,3.108,0,0,0-1.1.456l-.487-.487a.625.625,0,0,0-.884.884l.487.487a3.108,3.108,0,0,0-.456,1.1h-.688a.625.625,0,0,0,0,1.25h.688a3.108,3.108,0,0,0,.456,1.1l-.487.487a.625.625,0,0,0,.884.884l.487-.487a3.107,3.107,0,0,0,1.1.456v.688A.625.625,0,0,0,120.375,84.75ZM118.5,80.375a1.875,1.875,0,1,1,1.875,1.875A1.877,1.877,0,0,1,118.5,80.375Z" transform="translate(-112.375 -73.625)" fill="#575b6a"/>
                                </svg>
                            </div>
                            <span class="aiz-side-nav-text">{{translate('Website Setup')}}</span>
                            <span class="aiz-side-nav-arrow"></span>
                        </a>
                        <ul class="aiz-side-nav-list level-2">
                            {{-- @can('select_homepage')
                                <li class="aiz-side-nav-item">
                                    <a href="{{ route('website.select-homepage') }}" class="aiz-side-nav-link">
                                        <span class="aiz-side-nav-text">{{translate('Select Homepage')}}</span>
                                    </a>
                                </li>
                            @endcan --}}
                            @can('edit_website_page')
                                <li class="aiz-side-nav-item">
                                    <a href="{{ route('custom-pages.edit', ['id'=>'home', 'lang'=>env('DEFAULT_LANGUAGE'), 'page'=>'home']) }}" 
                                        class="aiz-side-nav-link {{ (url()->current() == url('/admin/website/custom-pages/edit/home')) ? 'active' : '' }}">
                                        <span class="aiz-side-nav-text">{{translate('Homepage Settings')}}</span>
                                    </a>
                                </li>
                            @endcan
                            @can('authentication_layout_settings')
                                <li class="aiz-side-nav-item">
                                    <a href="{{ route('website.authentication-layout-settings') }}" class="aiz-side-nav-link">
                                        <span class="aiz-side-nav-text">{{translate('Authentication Layout & Settings')}}</span>
                                    </a>
                                </li>
                            @endcan
                            @can('header_setup')
                                <li class="aiz-side-nav-item">
                                    <a href="{{ route('website.header') }}" class="aiz-side-nav-link">
                                        <span class="aiz-side-nav-text">{{translate('Header')}}</span>
                                    </a>
                                </li>
                            @endcan
                            @can('footer_setup')
                                <li class="aiz-side-nav-item">
                                    <a href="{{ route('website.footer', ['lang'=>  App::getLocale()] ) }}" class="aiz-side-nav-link {{ areActiveRoutes(['website.footer'])}}">
                                        <span class="aiz-side-nav-text">{{translate('Footer')}}</span>
                                    </a>
                                </li>
                            @endcan
                            @can('view_all_website_pages')
                                <li class="aiz-side-nav-item">
                                    <a href="{{ route('website.pages') }}" class="aiz-side-nav-link {{ areActiveRoutes(['website.pages', 'custom-pages.create' ,'custom-pages.edit'])}}">
                                        <span class="aiz-side-nav-text">{{translate('Pages')}}</span>
                                    </a>
                                </li>
                            @endcan
                            @can('website_appearance')
                                <li class="aiz-side-nav-item">
                                    <a href="{{ route('website.appearance') }}" class="aiz-side-nav-link">
                                        <span class="aiz-side-nav-text">{{translate('Appearance')}}</span>
                                    </a>
                                </li>
                            @endcan
                        </ul>
                    </li>
                @endcanany

                <!-- Setup & Configurations -->
                @canany(['general_settings','features_activation','language_setup','currency_setup','vat_&_tax_setup',
                        'pickup_point_setup','smtp_settings','payment_methods_configurations','order_configuration','file_system_&_cache_configuration',
                        'social_media_logins','facebook_chat','facebook_comment','analytics_tools_configuration','google_recaptcha_configuration','google_map_setting',
                        'google_firebase_setting','shipping_configuration','shipping_country_setting','manage_shipping_states','manage_shipping_cities','manage_zones','manage_carriers'])
                    <li class="aiz-side-nav-item">
                        <a href="#" class="aiz-side-nav-link">
                            <div class="aiz-side-nav-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16">
                                    <path id="Path_40779" data-name="Path 40779" d="M7.688,16h.625a1.877,1.877,0,0,0,1.875-1.875V13.81a.209.209,0,0,1,.133-.191l.011,0a.209.209,0,0,1,.23.041l.223.223a1.875,1.875,0,0,0,2.652,0l.442-.442a1.875,1.875,0,0,0,0-2.652l-.223-.223a.209.209,0,0,1-.041-.23l0-.012a.209.209,0,0,1,.191-.133h.315A1.877,1.877,0,0,0,16,8.313V7.688a1.877,1.877,0,0,0-1.875-1.875H13.81a.209.209,0,0,1-.191-.133l0-.011a.209.209,0,0,1,.041-.23l.223-.223a1.875,1.875,0,0,0,0-2.652l-.442-.442a1.875,1.875,0,0,0-2.652,0l-.223.223a.21.21,0,0,1-.23.041l-.012,0a.209.209,0,0,1-.133-.191V1.875A1.877,1.877,0,0,0,8.312,0H7.687A1.877,1.877,0,0,0,5.812,1.875V2.19a.209.209,0,0,1-.133.191l-.012,0a.209.209,0,0,1-.23-.041l-.223-.223a1.875,1.875,0,0,0-2.652,0l-.442.442a1.875,1.875,0,0,0,0,2.652l.223.223a.209.209,0,0,1,.041.23l0,.011a.209.209,0,0,1-.191.133H1.875A1.877,1.877,0,0,0,0,7.687v.625a1.874,1.874,0,0,0,1.407,1.816.625.625,0,1,0,.312-1.211.624.624,0,0,1-.468-.605V7.688a.626.626,0,0,1,.625-.625H2.19a1.455,1.455,0,0,0,1.347-.906l0-.011a1.455,1.455,0,0,0-.312-1.591l-.223-.223a.625.625,0,0,1,0-.884l.442-.442a.625.625,0,0,1,.884,0l.223.223a1.456,1.456,0,0,0,1.593.311l.009,0A1.455,1.455,0,0,0,7.063,2.19V1.875a.626.626,0,0,1,.625-.625h.625a.626.626,0,0,1,.625.625V2.19a1.455,1.455,0,0,0,.906,1.347l.009,0a1.455,1.455,0,0,0,1.593-.311l.223-.223a.625.625,0,0,1,.884,0l.442.442a.625.625,0,0,1,0,.884l-.223.223a1.455,1.455,0,0,0-.311,1.593l0,.009a1.455,1.455,0,0,0,1.347.906h.315a.626.626,0,0,1,.625.625v.625a.626.626,0,0,1-.625.625H13.81a1.455,1.455,0,0,0-1.347.906l0,.009a1.455,1.455,0,0,0,.311,1.593l.223.223a.625.625,0,0,1,0,.884l-.442.442a.625.625,0,0,1-.884,0l-.223-.223a1.456,1.456,0,0,0-1.593-.311l-.009,0a1.455,1.455,0,0,0-.906,1.347v.315a.626.626,0,0,1-.625.625H7.688a.622.622,0,0,1-.6-.437.625.625,0,1,0-1.193.375A1.867,1.867,0,0,0,7.688,16ZM.536,15.433a1.829,1.829,0,0,1,0-2.586h0L4.589,8.811a3.234,3.234,0,0,1-.308-1.259,2.97,2.97,0,0,1,.9-2.141A4.228,4.228,0,0,1,8.13,4.255h.007a3.322,3.322,0,0,1,1.086.188A.625.625,0,0,1,9.47,5.473L7.964,7.01l.188.811L8.95,8,10.479,6.47a.625.625,0,0,1,1.034.24,3.472,3.472,0,0,1,.2,1.121,4.373,4.373,0,0,1-.8,2.556,3.047,3.047,0,0,1-2.49,1.3H8.417A3.414,3.414,0,0,1,7.159,11.4L3.122,15.433a1.829,1.829,0,0,1-2.586,0Zm6.876-5.311a2.1,2.1,0,0,0,1.007.316,1.818,1.818,0,0,0,1.487-.792,2.988,2.988,0,0,0,.528-1.361l-.843.845A.625.625,0,0,1,9.01,9.3L7.494,8.953a.625.625,0,0,1-.471-.468L6.669,6.959a.625.625,0,0,1,.162-.579l.823-.84A2.844,2.844,0,0,0,6.067,6.3,1.723,1.723,0,0,0,5.531,7.55a2.123,2.123,0,0,0,.342,1,.625.625,0,0,1-.065.809L1.419,13.731a.579.579,0,1,0,.819.818l4.368-4.361a.625.625,0,0,1,.806-.066Z" fill="#575b6a"/>
                                </svg>
                            </div>
                            <span class="aiz-side-nav-text">{{translate('Setup & Configurations')}}</span>
                            <span class="aiz-side-nav-arrow"></span>
                        </a>
                        <ul class="aiz-side-nav-list level-2">
                            {{-- @can('general_settings')
                                <li class="aiz-side-nav-item">
                                    <a href="{{route('general_setting.index')}}" class="aiz-side-nav-link">
                                        <span class="aiz-side-nav-text">{{translate('General Settings')}}</span>
                                    </a>
                                </li>
                            @endcan --}}
                            @can('features_activation')
                                <li class="aiz-side-nav-item">
                                    <a href="{{route('activation.index')}}" class="aiz-side-nav-link">
                                        <span class="aiz-side-nav-text">{{translate('Features activation')}}</span>
                                    </a>
                                </li>
                            @endcan
                            
                            {{-- 
                            @can('pickup_point_setup')
                                <li class="aiz-side-nav-item">
                                    <a href="{{route('pick_up_points.index')}}" class="aiz-side-nav-link {{ areActiveRoutes(['pick_up_points.index','pick_up_points.create','pick_up_points.edit'])}}">
                                        <span class="aiz-side-nav-text">{{translate('Pickup point')}}</span>
                                    </a>
                                </li>
                            @endcan --}}
                            @can('smtp_settings')
                                <li class="aiz-side-nav-item">
                                    <a href="{{ route('smtp_settings.index') }}" class="aiz-side-nav-link">
                                        <span class="aiz-side-nav-text">{{translate('SMTP Settings')}}</span>
                                    </a>
                                </li>
                            @endcan
                            @can('payment_methods_configurations')
                                <li class="aiz-side-nav-item">
                                    <a href="{{ route('payment_method.index') }}" class="aiz-side-nav-link">
                                        <span class="aiz-side-nav-text">{{translate('Payment Methods')}}</span>
                                    </a>
                                </li>
                            @endcan
                            @can('file_system_&_cache_configuration')
                                <li class="aiz-side-nav-item">
                                    <a href="{{ route('file_system.index') }}" class="aiz-side-nav-link">
                                        <span class="aiz-side-nav-text">{{translate('File System & Cache Configuration')}}</span>
                                    </a>
                                </li>
                            @endcan
                            @can('social_media_logins')
                                <li class="aiz-side-nav-item">
                                    <a href="{{ route('social_login.index') }}" class="aiz-side-nav-link">
                                        <span class="aiz-side-nav-text">{{translate('Social media Logins')}}</span>
                                    </a>
                                </li>
                            @endcan
                            @canany(['facebook_chat','facebook_comment'])
                                <li class="aiz-side-nav-item">
                                    <a href="javascript:void(0);" class="aiz-side-nav-link">
                                        <span class="aiz-side-nav-text">{{translate('Facebook')}}</span>
                                        <span class="aiz-side-nav-arrow"></span>
                                    </a>
                                    <ul class="aiz-side-nav-list level-3">
                                        @can('facebook_chat')
                                            <li class="aiz-side-nav-item">
                                                <a href="{{ route('facebook_chat.index') }}" class="aiz-side-nav-link">
                                                    <span class="aiz-side-nav-text">{{translate('Facebook Chat')}}</span>
                                                </a>
                                            </li>
                                        @endcan
                                        @can('facebook_comment')
                                            <li class="aiz-side-nav-item">
                                                <a href="{{ route('facebook-comment') }}" class="aiz-side-nav-link">
                                                    <span class="aiz-side-nav-text">{{translate('Facebook Comment')}}</span>
                                                </a>
                                            </li>
                                        @endcan
                                    </ul>
                                </li>
                            @endcanany
                            @canany(['analytics_tools_configuration','google_recaptcha_configuration','google_map_setting','google_firebase_setting'])
                                <li class="aiz-side-nav-item">
                                    <a href="javascript:void(0);" class="aiz-side-nav-link">
                                        <span class="aiz-side-nav-text">{{translate('Google')}}</span>
                                        <span class="aiz-side-nav-arrow"></span>
                                    </a>
                                    <ul class="aiz-side-nav-list level-3">
                                        @can('analytics_tools_configuration')
                                            <li class="aiz-side-nav-item">
                                                <a href="{{ route('google_analytics.index') }}" class="aiz-side-nav-link">
                                                    <span class="aiz-side-nav-text">{{translate('Analytics Tools')}}</span>
                                                </a>
                                            </li>
                                        @endcan
                                        @can('google_recaptcha_configuration')
                                            <li class="aiz-side-nav-item">
                                                <a href="{{ route('google_recaptcha.index') }}" class="aiz-side-nav-link">
                                                    <span class="aiz-side-nav-text">{{translate('Google reCAPTCHA')}}</span>
                                                </a>
                                            </li>
                                        @endcan
                                        @can('google_map_setting')
                                            <li class="aiz-side-nav-item">
                                                <a href="{{ route('google-map.index') }}" class="aiz-side-nav-link">
                                                    <span class="aiz-side-nav-text">{{translate('Google Map')}}</span>
                                                </a>
                                            </li>
                                        @endcan
                                        @can('google_firebase_setting')
                                            <li class="aiz-side-nav-item">
                                                <a href="{{ route('google-firebase.index') }}" class="aiz-side-nav-link">
                                                    <span class="aiz-side-nav-text">{{translate('Google Firebase')}}</span>
                                                </a>
                                            </li>
                                        @endcan
                                    </ul>
                                </li>
                            @endcanany
                            @canany(['shipping_configuration','shipping_country_setting','manage_shipping_states','manage_shipping_cities','manage_zones','manage_carriers'])
                                <li class="aiz-side-nav-item">
                                    <a href="javascript:void(0);" class="aiz-side-nav-link">
                                        <span class="aiz-side-nav-text">{{translate('Shipping')}}</span>
                                        <span class="aiz-side-nav-arrow"></span>
                                    </a>
                                    <ul class="aiz-side-nav-list level-3">
                                      {{--  @can('shipping_configuration')
                                            <li class="aiz-side-nav-item">
                                                <a href="{{route('shipping_configuration.index')}}" class="aiz-side-nav-link {{ areActiveRoutes(['shipping_configuration.index','shipping_configuration.edit','shipping_configuration.update'])}}">
                                                    <span class="aiz-side-nav-text">{{translate('Shipping Configuration')}}</span>
                                                </a>
                                            </li>
                                        @endcan --}}
                                        @can('shipping_configuration')
                                        <li class="aiz-side-nav-item">
                                            <a href="{{route('shipping_categories.index')}}" class="aiz-side-nav-link {{ areActiveRoutes(['shipping_categories.index', 'shipping_categories.create', 'shipping_categories.edit'])}}">
                                                <span class="aiz-side-nav-text">{{translate('Shipping Charge')}}</span>
                                            </a>
                                        </li>
                                          @endcan
                                        @can('shipping_country_setting')
                                            <li class="aiz-side-nav-item">
                                                <a href="{{route('countries.index')}}" class="aiz-side-nav-link {{ areActiveRoutes(['countries.index','countries.edit','countries.update'])}}">
                                                    <span class="aiz-side-nav-text">{{translate('Shipping Countries')}}</span>
                                                </a>
                                            </li>
                                        @endcan
                                        @can('manage_shipping_states')
                                            <li class="aiz-side-nav-item">
                                                <a href="{{route('states.index')}}" class="aiz-side-nav-link {{ areActiveRoutes(['states.index','states.edit','states.update'])}}">
                                                    <span class="aiz-side-nav-text">{{translate('Shipping States')}}</span>
                                                </a>
                                            </li>
                                        @endcan
                                        @can('manage_shipping_cities')
                                            <li class="aiz-side-nav-item">
                                                <a href="{{route('cities.index')}}" class="aiz-side-nav-link {{ areActiveRoutes(['cities.index','cities.edit','cities.update'])}}">
                                                    <span class="aiz-side-nav-text">{{translate('Shipping Cities')}}</span>
                                                </a>
                                            </li>
                                        @endcan
                                      {{--  @can('manage_zones')
                                            <li class="aiz-side-nav-item">
                                                <a href="{{route('zones.index')}}" class="aiz-side-nav-link {{ areActiveRoutes(['zones.index','zones.create','zones.edit'])}}">
                                                    <span class="aiz-side-nav-text">{{translate('Shipping Zones')}}</span>
                                                </a>
                                            </li>
                                        @endcan
                                        @can('manage_carriers')
                                            <li class="aiz-side-nav-item">
                                                <a href="{{route('carriers.index')}}" class="aiz-side-nav-link {{ areActiveRoutes(['carriers.index','carriers.create','carriers.edit'])}}">
                                                    <span class="aiz-side-nav-text">{{translate('Shipping Carrier')}}</span>
                                                </a>
                                            </li>
                                        @endcan --}}
                                    </ul>
                                </li>
                            @endif 
                        </ul>
                    </li>
                @endcanany

               

                <!-- System Update & Server Status -->
                {{-- @canany(['system_update','server_status'])
                    <li class="aiz-side-nav-item">
                        <a href="#" class="aiz-side-nav-link">
                            <div class="aiz-side-nav-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16">
                                    <g id="Group_28317" data-name="Group 28317" transform="translate(-19315.001 1976)">
                                      <g id="layer1" transform="translate(19314.471 -1976.53)">
                                        <path id="path3159" d="M3.386.53A2.862,2.862,0,0,0,.53,3.386V13.67a2.865,2.865,0,0,0,2.856,2.86H13.67a2.869,2.869,0,0,0,2.86-2.86V3.386A2.865,2.865,0,0,0,13.67.53Zm0,1.143H13.67a1.7,1.7,0,0,1,1.718,1.713V13.67a1.7,1.7,0,0,1-1.718,1.718H3.386A1.7,1.7,0,0,1,1.673,13.67V3.386A1.7,1.7,0,0,1,3.386,1.673Z" fill="#575b6a"/>
                                      </g>
                                      <g id="Group_28316" data-name="Group 28316" transform="translate(19317.551 -1973.449)">
                                        <g id="LWPOLYLINE" transform="translate(0 3.708)">
                                          <path id="Path_25666" data-name="Path 25666" d="M194.061,143.129a.436.436,0,0,0,0,.873h1.527a.436.436,0,0,0,0-.873Z" transform="translate(-193.625 -143.129)" fill="#575b6a"/>
                                        </g>
                                        <g id="LWPOLYLINE-2" data-name="LWPOLYLINE" transform="translate(3.663)">
                                          <path id="Path_25667" data-name="Path 25667" d="M199.926,137.186a.436.436,0,0,1,.872,0v1.527a.436.436,0,0,1-.872,0Z" transform="translate(-199.926 -136.75)" fill="#575b6a"/>
                                        </g>
                                        <g id="LWPOLYLINE-3" data-name="LWPOLYLINE" transform="translate(5.239 1.075)">
                                          <path id="Path_25668" data-name="Path 25668" d="M204.463,139.345a.436.436,0,1,0-.617-.617l-1.079,1.079a.436.436,0,1,0,.617.617Z" transform="translate(-202.638 -138.6)" fill="#575b6a"/>
                                        </g>
                                        <g id="LWPOLYLINE-4" data-name="LWPOLYLINE" transform="translate(1.097 1.075)">
                                          <path id="Path_25669" data-name="Path 25669" d="M195.64,139.345a.436.436,0,1,1,.617-.617l1.079,1.079a.436.436,0,1,1-.617.617Z" transform="translate(-195.512 -138.6)" fill="#575b6a"/>
                                        </g>
                                        <g id="LWPOLYLINE-5" data-name="LWPOLYLINE" transform="translate(1.097 5.261)">
                                          <path id="Path_25670" data-name="Path 25670" d="M195.64,147.008a.436.436,0,0,0,.617.617l1.079-1.079a.436.436,0,1,0-.617-.617Z" transform="translate(-195.512 -145.8)" fill="#575b6a"/>
                                        </g>
                                        <path id="Path_25671" data-name="Path 25671" d="M206.87,148.144,205,146.269l.864-.471a.436.436,0,0,0-.044-.786l-5.682-2.322a.436.436,0,0,0-.569.568l2.322,5.682a.436.436,0,0,0,.786.044l.471-.864,1.875,1.875a.437.437,0,0,0,.617,0l1.233-1.233A.437.437,0,0,0,206.87,148.144Zm-1.544.913-1.977-1.977a.436.436,0,0,0-.691.1l-.311.57-1.58-3.868,3.868,1.58-.57.311a.436.436,0,0,0-.174.591.467.467,0,0,0,.074.1l1.977,1.977Z" transform="translate(-196.099 -139.223)" fill="#575b6a"/>
                                      </g>
                                    </g>
                                </svg>
                            </div>
                            <span class="aiz-side-nav-text">{{translate('System')}}</span>
                            <span class="aiz-side-nav-arrow"></span>
                        </a>
                        <ul class="aiz-side-nav-list level-2">
                            @can('system_update')
                                <li class="aiz-side-nav-item">
                                    <a href="{{ route('system_update') }}" class="aiz-side-nav-link">
                                        <span class="aiz-side-nav-text">{{translate('Update')}}</span>
                                    </a>
                                </li>
                            @endcan
                            @can('server_status')
                            <li class="aiz-side-nav-item">
                                <a href="{{route('system_server')}}" class="aiz-side-nav-link">
                                    <span class="aiz-side-nav-text">{{translate('Server status')}}</span>
                                </a>
                            </li>
                            @endcan
                        </ul>
                    </li>
                @endcanany --}}

                <!-- Addon Manager -->
                {{-- @can('manage_addons')
                    <li class="aiz-side-nav-item">
                        <a href="{{route('addons.index')}}" class="aiz-side-nav-link {{ areActiveRoutes(['addons.index', 'addons.create'])}}">
                            <div class="aiz-side-nav-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16">
                                    <path id="_0339d7f72e6ebf7daea77d00de6c6177" data-name="0339d7f72e6ebf7daea77d00de6c6177" d="M12.2,17H9a.533.533,0,0,1-.533-.533v-1.6a1.067,1.067,0,1,0-2.133,0v1.6A.533.533,0,0,1,5.8,17H2.6A1.6,1.6,0,0,1,1,15.4V12.2a.533.533,0,0,1,.533-.533h1.6a1.067,1.067,0,1,0,0-2.133h-1.6A.533.533,0,0,1,1,9V5.8A1.6,1.6,0,0,1,2.6,4.2H5.267V3.133a2.133,2.133,0,1,1,4.267,0V4.2H12.2a1.6,1.6,0,0,1,1.6,1.6V8.467h1.067a2.133,2.133,0,1,1,0,4.267H13.8V15.4A1.6,1.6,0,0,1,12.2,17ZM9.533,15.933H12.2a.533.533,0,0,0,.533-.533V12.2a.533.533,0,0,1,.533-.533h1.6a1.067,1.067,0,1,0,0-2.133h-1.6A.533.533,0,0,1,12.733,9V5.8a.533.533,0,0,0-.533-.533H9a.533.533,0,0,1-.533-.533v-1.6a1.067,1.067,0,1,0-2.133,0v1.6a.533.533,0,0,1-.533.533H2.6a.533.533,0,0,0-.533.533V8.467H3.133a2.133,2.133,0,0,1,0,4.267H2.067V15.4a.533.533,0,0,0,.533.533H5.267V14.867a2.133,2.133,0,0,1,4.267,0Z" transform="translate(-1 -1)" fill="#575b6a"/>
                                </svg>
                            </div>
                            <span class="aiz-side-nav-text">{{translate('Addon Manager')}}</span>
                        </a>
                    </li>
                @endcan --}}
            </ul><!-- .aiz-side-nav -->
        </div><!-- .aiz-side-nav-wrap -->
    </div><!-- .aiz-sidebar -->
    <div class="aiz-sidebar-overlay"></div>
</div><!-- .aiz-sidebar -->
