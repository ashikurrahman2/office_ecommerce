@extends('frontend.layouts.app')
@section('content')
    <section class="mb-4 pt-3">
        <div class="container">
            <div class="bg-white py-3">
                <div class="card border-0 shadow-none">
                    <div class="card-body text-center"> <!-- Center all text inside this div -->
                        
                        <!-- Centering and styling the heading and subheading -->
                        <h2 class="text-center fw-600">You will find it, we will buy it.</h2>
                        <h6 class="text-center fw-300 mb-4">Did you find your products? Enter the URL of the products page below and hit enter!</h6>
                        
                        <!-- Search field container with flexbox to center it -->
                        <div class="d-flex justify-content-center">
                            <div class="flex-grow-1 d-flex align-items-center bg-white mx-xl-5" style="max-width: 600px;"> <!-- Restricting max width -->
                                <div class="position-relative flex-grow-1 px-3 px-lg-0">
                                    <form action="{{ route('search') }}" method="GET" class="stop-propagation mb-0">
                                        <div class="input-group">
                                            <input type="text" class="form-control border border-soft-light fs-14 hov-animate-outline" 
                                                   name="keyword"
                                                   @isset($keyword) value="{{ $keyword }}" @endisset
                                                   placeholder="{{ translate('Paste product link or Search from million of products...') }}" autocomplete="off" aria-label="Search">
                                            <button class="btn btn-danger search-button" type="submit" id="button-addon2">
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

                                  <!-- Add images after the form -->
                                    <div class="d-flex justify-content-center mt-3">
                                        <div class="d-flex justify-content-around align-items-center" style="width: 100%; max-width: 600px;">
                                            <!--<img src="{{ static_asset('assets/img/buy-ship-1.png') }}" class="img-fluid" alt="Image 1" style="max-width: 20%;">-->
                                            <!--<img src="{{ static_asset('assets/img/buy-ship-2.png') }}" class="img-fluid" alt="Image 2" style="max-width: 20%;">-->
                                            <img src="{{ static_asset('assets/img/buy-ship-3.png') }}" class="img-fluid" alt="Image 3" style="max-width: 20%;">
                                            <!--<img src="{{ static_asset('assets/img/buy-ship-4.png') }}" class="img-fluid" alt="Image 4" style="max-width: 20%;">-->
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div> <!-- End of search field container -->
                        
                    </div>
                </div>
                <div class="card border-0 shadow-none pt-3">
                    <img src="{{ static_asset('assets/img/Buy & Ship for me Landing Page.png') }}" alt="">
                </div>
            </div>
        </div>
        <div class="container py-5">
            <!-- Top Section -->
            <div class="text-center">
                <h3 class="fw-700 mb-sm-0">
                    <span class="">{{ translate('How CPL Express Works') }}</span>
                </h3>
            </div>

            <div class="bg-white py-3 d-flex justify-content-center flex-wrap">
                <!-- Flowchart for mobile -->
                <div class="flowchart-box">
                    <img src="{{ static_asset('assets/img/Flowchart.gif') }}" class="lazyload w-100 h-auto"
                        alt="Flowchart for Mobile">
                </div>

                <!-- Original images with text for larger screens -->
                <div class="icon-box text-center mb-3 mx-2">
                    <img src="{{ static_asset('assets/img/Order Placement.png') }}"
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
            
        <div class="container py-5">
            <h2 class="text-center mb-4">FAQ Section</h2>
            <hr>
            <div class="row">
                <div class="col-md-6">
                    <div class="faq-item">
                        <div class="faq-header" data-toggle="faq-body" data-target="#faq1">
                            What Is CPL Express - Ship For Me?
                            <span class="faq-toggle-icon">+</span>
                        </div>
                        <div class="faq-body" id="faq1">
                            CPL Express-Buy & Ship for me is one of the key services provided by CPL Express. It provides you hassle free purchase and shipping service on your desired product directly from the worldwide top ranked marketplaces. This service also provides the overall tracking facility of your shipped product. Your order will proceed with two parties connected with CPL Express; one is Buying Agent and the other one Freight Forwarding Agent.

                        </div>
                    </div>
                    <div class="faq-item">
                        <div class="faq-header" data-toggle="faq-body" data-target="#faq2">
                            What’s your store(1688,Alibaba etc) conversion rate?
                            <span class="faq-toggle-icon">+</span>
                        </div>
                        <div class="faq-body" id="faq2">
                            There’s no fixed conversion rate. When you purchase a product the price shown will include all the charges. But if we update any charges, we will notify you before processing the order through SMS or Email.
                        </div>
                    </div>
                    <div class="faq-item">
                        <div class="faq-header" data-toggle="faq-body" data-target="#faq3">
                            What is CPL Express Buying Agent?
                            <span class="faq-toggle-icon">+</span>
                        </div>
                        <div class="faq-body" id="faq3">
                            When you purchase a product from a particular Market-place, a CPL Express Buying Agent will be assigned. He will Purchase the product and forward to the CPL Express Forwarding Agent. This CPL Express Buying agent will also communicate with the seller directly about anything related to purchase and send the product to the warehouse.

                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="faq-item">
                        <div class="faq-header" data-toggle="faq-body" data-target="#faq4">
                            What is CPL Express Freight Forwarding Agent?
                            <span class="faq-toggle-icon">+</span>
                        </div>
                        <div class="faq-body" id="faq4">
                            CPL Express Freight Forwarding Agent is directly connected with CPL Express. Selecting them, you can enjoy the Freight(Shipping) service from multiple Freight agents. The Freight Agents have their own warehouse (China,USA etc). MovedOn Freight Agent is responsible for receiving products from the warehouse, clearing them from customs then sending products to CPL Express warehouse.

                        </div>
                    </div>
                    <div class="faq-item">
                        <div class="faq-header" data-toggle="faq-body" data-target="#faq5">
                            How Do I contact If I face a problem?
                            <span class="faq-toggle-icon">+</span>
                        </div>
                        <div class="faq-body" id="faq5">
                            When you submit your first order you will be connected with a Dedicated Order Handler within 24 to 48 hours. This person will be handling your order and assist you with all the queries regarding your purchase. Besides, you can connect with the CPL Express Facebook page, Email or the helpline number given.

                        </div>
                    </div>
                    <div class="faq-item">
                        <div class="faq-header" data-toggle="faq-body" data-target="#faq6">
                            What payment methods does CPL Express accept?
                            <span class="faq-toggle-icon">+</span>
                        </div>
                        <div class="faq-body" id="faq6">
                            We accept payment through Bkash, Rocket, Debit & Credit cards, Bank deposit etc. We also accept office payments, you can clear the payment coming to our office.

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
@endsection


@section('script')
<script>
    $(document).ready(function() {
        $('.faq-header').click(function() {
            var target = $(this).data('target');
            $(target).slideToggle();
            $(this).toggleClass('active');
            $(this).find('.faq-toggle-icon').text($(this).hasClass('active') ? '-' : '+');
        });
    });
</script>
@endsection
