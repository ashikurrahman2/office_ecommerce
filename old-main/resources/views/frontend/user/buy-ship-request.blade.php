@extends('frontend.layouts.user_panel')

@section('panel_content')
<style>
    .card-container {
        max-width: 900px;
        margin: auto;
        border: 1px solid #ddd;
        margin-bottom: 1rem;
    }
    .card-container .card-header {
        /* font-weight: bold; */
        font-size: 1.1rem;
        padding: 1rem;
        background-color: #f9f9f9;
        border-bottom: 1px solid #ddd;
    }
    
    .product-image {
        width: 70px;
        height: 70px;
        margin-bottom: 0.5rem;
    }
    .btn-start {
        background-color: #4caf50;
        color: white;
        width: 100%;
        padding: 0.4rem;
        font-size: 1rem;
        border: none;
        margin-top: 0.5rem;
        border-radius: 5px;
    }
    .btn-cancel {
        background-color: #f73838;
        color: #fff;
        width: 100%;
        padding: 0.4rem;
        font-size: 1rem;
        border: none;
        margin-top: 0.5rem;
        border-radius: 5px;
    }
    .countdown {
        color: #666;
        font-size: 0.9rem;
        margin-top: 1rem;
        text-align: left;
    }
    .tracking-code {
        color: green;
        font-size: 1rem;
        font-weight: bold;
        cursor: pointer;
    }
    /* .customs-shipping {
        font-weight: bold;
        text-align: right;
        color: #007bff;
    } */
    .customs-shipping-value {
        background-color: #e0f3ff;
        color: #007bff;
        padding: 2px 8px;
        border-radius: 5px;
        font-size: 0.9rem;
        margin-left: 4px;
    }
    .product-details-link {
        color: #007bff;
        font-size: 0.9rem;
        text-decoration: none;
    }
    .section-label {
        font-weight: bold;
    }
</style>
    {{-- <div class="aiz-titlebar mb-4">
        <div class="row align-items-center">
            <div class="col-md-6">
                <b class="fs-20 fw-700 text-dark">{{ translate('Ship For Me')}}</b>
            </div>
        </div>
    </div> --}}

     <!-- Tabs -->
     <div class="user-tabs d-flex">
         <a href="javascript:void(0)" class="tab-link active" onclick="switchTab(event, 'pending')">Pending ({{ $statusCounts->pending_count ?? 0 }})</a>
         <a href="javascript:void(0)" class="tab-link ml-3" onclick="switchTab(event, 'approved')">Approved ({{ $statusCounts->approved_count ?? 0 }})</a>
        <a href="javascript:void(0)" class="tab-link ml-3" onclick="switchTab(event, 'rejected')">Rejected ({{ $statusCounts->rejected_count ?? 0 }})</a>
        {{-- <input type="text" class="ml-auto form-control" placeholder="Order number" style="width: 200px;"> --}}
    </div>

    
    <!-- Tab Content -->
    <div id="pending" class="user-tab-content active">
        {{-- <table class="table table-bordered text-center">
            <thead>
                <tr>
                    <th>Product Tile</th>
                    <th>Quantity</th>
                    <th>Details</th>
                    <th>Status</th>
                    <th>Customs & Shipping</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($pendingRequestProducts as $key => $item)
                    <tr>
                        <td>
                            <a href="{{$item->product_link}}">{{ $item->product_title }}</a>
                        </td>
                        <td>{{$item->quantity}}</td>
                        <td>{{$item->description}}</td>
                        <td>
                            @if($item->status == 'pending')
                                <span class="badge badge-primary">Pending</span>

                            @elseif($item->status == 'approved')
                                <span class="badge badge-success">Approved</span>
                            @else
                                <span class="badge badge-danger">Rejected</span>
                            @endif
                        </td>
                        
                        <td></td>
                        <td></td>
                    </tr>
                @endforeach
            </tbody>
        </table> --}}

        @if ($statusCounts->pending_count > 0)
            @foreach ($pendingRequestProducts as $key => $item)
                <div class="card card-container">
                    @if ($loop->first)
                        <div class="card-header d-none d-md-flex">
                            <div class="col-md-6">Product Description</div>
                            <div class="col-md-6">Details</div>
                            <!--<div class="col-md-4">Action</div>-->
                        </div>
                    @endif

                    <div class="card-body row">
                        <div class="col-md-6">

                            <div class="d-flex align-items-start">
                                <!-- Left side: Product Image -->
                                <div class="">
                                    <img src="{{ static_asset('assets/img/placeholder.jpg') }}" alt="{{ $item->product_title }}" class="img-fluid img-thumbnail product-image mb-2 rounded-start">
                                </div>
                            
                                <!-- Right side: Product Details -->
                                <div class="ml-3">
                                    <p class="mb-1"><strong>Title:</strong> <a href="{{ $item->product_link }}">{{ $item->product_title }}</a></p>
                            
                                    <p class="mb-1">
                                        <strong>Status:</strong>
                                        @if ($item->status == 'pending')
                                            <span class="badge badge-primary status-style">Pending</span>
                                        @elseif($item->status == 'approved')
                                            <span class="badge badge-success status-style">Approved</span>
                                        @else
                                            <span class="badge badge-danger status-style">Rejected</span>
                                        @endif
                                    </p>
                                </div>
                            </div>
                            
                        </div>
                        <div class="col-md-6">
                            <span><strong>Description:</strong> {{ $item->description }}</span><br>
                            <span><strong>Quantity:</strong> {{ $item->quantity }}</span><br>
                        </div>
                        <div class="col-md-4 text-center">
                            
                        </div>
                    </div>
                    <!--<div class="card-footer d-flex justify-content-between align-items-center">-->
                    <!--    <div>-->
                    <!--        <p class="mb-1 fs-15"><strong>Tracking Code:</strong></p>-->
                    <!--    </div>-->
                    <!--    <div class="customs-shipping">-->
                    <!--        <p class="mb-1 fs-15">-->
                    <!--            <strong>Customs & Shipping:</strong> -->
                    <!--            @if($item->status == 'approved')-->
                    <!--                <span class="customs-shipping-value">2200 /-kg</span>  -->
                    <!--            @endif-->
                    <!--        </p>-->
                    <!--    </div>-->
                    <!--</div>-->
                </div>
            @endforeach

            <!-- Pagination -->
            <div class="aiz-pagination">
                {{ $pendingRequestProducts->links() }}
            </div>
        @else
            <div class="row">
                <div class="col">
                    <div class="text-center bg-white p-4 border">
                        <img class="mw-100 h-200px" src="{{ static_asset('assets/img/nothing.svg') }}" alt="Image">
                        <h5 class="mb-0 h5 mt-3">{{ translate("There isn't anything added yet") }}</h5>
                    </div>
                </div>
            </div>
        @endif
    </div>
    <div id="approved" class="user-tab-content">
        @if ($statusCounts->approved_count > 0)
            @foreach ($approvedRequestProducts as $key => $item)
                <div class="card card-container">
                    @if ($loop->first)
                        <div class="card-header d-none d-md-flex">
                            <div class="col-md-6">Product Description</div>
                            <div class="col-md-6">Details</div>
                            <!--<div class="col-md-4">Action</div>-->
                        </div>
                    @endif

                    <div class="card-body row">
                        <div class="col-md-6">
                            <div class="d-flex align-items-start">
                                <!-- Left side: Product Image -->
                                <div class="">
                                    <img src="{{ static_asset('assets/img/placeholder.jpg') }}" alt="{{ $item->product_title }}" class="img-fluid img-thumbnail product-image mb-2 rounded-start">
                                </div>
                            
                                <!-- Right side: Product Details -->
                                <div class="ml-3">
                                    <p class="mb-1"><strong>Title:</strong> <a href="{{ $item->product_link }}">{{ $item->product_title }}</a></p>
                            
                                    <p class="mb-1">
                                        <strong>Status:</strong>
                                        @if ($item->status == 'pending')
                                            <span class="badge badge-primary status-style">Pending</span>
                                        @elseif($item->status == 'approved')
                                            <span class="badge badge-success status-style">Approved</span>
                                        @else
                                            <span class="badge badge-danger status-style">Rejected</span>
                                        @endif
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <span><strong>Description:</strong> {{ $item->description }}</span><br>
                            <span><strong>Quantity:</strong> {{ $item->quantity }}</span><br>
                        </div>
                        <!--<div class="col-md-4 text-center">-->
                        <!--    @if($item->status == 'approved')-->
                        <!--        @if($item->order_status == NULL)-->
                        <!--        <button class="btn-start start_order" data-route="{{route('buy_ship_product_request.start_order', $item->id)}}">Start Order</button>-->
                        <!--        @endif-->

                        <!--        @if($item->order_status != 'order_placement' && $item->order_status != 'order_cancelled')-->
                        <!--        <button class="btn-cancel cancel_order" data-route="{{route('buy_ship_product_request.cancel_order', $item->id)}}">Cancel</button>-->
                        <!--        @endif-->
                        <!--    @endif-->
                        <!--</div>-->
                    </div>
                    <!--<div class="card-footer d-flex justify-content-between align-items-center">-->
                    <!--    <div>-->
                    <!--        <p class="mb-1 fs-15"><strong>Tracking Code:</strong> -->
                    <!--            @if($item->status == 'approved')-->
                    <!--                {{ $item->order_code }}-->
                    <!--            @endif-->
                    <!--        </p>-->
                    <!--    </div>-->
                    <!--    <div class="customs-shipping">-->
                    <!--        <p class="mb-1 fs-15">-->
                    <!--            <strong>Customs & Shipping:</strong> -->
                    <!--            @if($item->status == 'approved')-->
                    <!--                <span class="customs-shipping-value">00 /-kg</span>  -->
                    <!--            @endif-->
                    <!--        </p>-->
                    <!--    </div>-->
                    <!--</div>-->
                </div>
            @endforeach
            
            <!-- Pagination -->
            <div class="aiz-pagination">
                {{ $approvedRequestProducts->links() }}
            </div>
        @else
            <div class="row">
                <div class="col">
                    <div class="text-center bg-white p-4 border">
                        <img class="mw-100 h-200px" src="{{ static_asset('assets/img/nothing.svg') }}" alt="Image">
                        <h5 class="mb-0 h5 mt-3">{{ translate("There isn't anything added yet")}}</h5>
                    </div>
                </div>
            </div>
        @endif
    </div>
   
    <div id="rejected" class="user-tab-content">
        @if ($statusCounts->rejected_count > 0)
            {{-- <table class="table table-bordered text-center">
                <thead>
                    <tr>
                        <th>Product Tile</th>
                        <th>Quantity</th>
                        <th>Details</th>
                        <th>Status</th>
                        <th>Customs & Shipping</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($rejectedRequestProducts as $key => $item)
                        <tr>
                            <td>
                                <a href="{{$item->product_link}}">{{ $item->product_title }}</a>
                            </td>
                            <td>{{$item->quantity}}</td>
                            <td>{{$item->description}}</td>
                            <td>
                                @if($item->status == 'pending')
                                <span class="badge bg-primary">Pending</span>
                                @elseif($item->status == 'approved')
                                <span class="badge bg-success">Approved</span>
                                @else
                                    <span class="badge bg-danger">Rejected</span>
                                @endif
                            </td>
                            
                            <td></td>
                            <td></td>
                        </tr>
                    @endforeach
                </tbody>
            </table> --}}
            @foreach ($rejectedRequestProducts as $key => $item)
                <div class="card card-container">
                    @if ($loop->first)
                        <div class="card-header d-none d-md-flex">
                            <div class="col-md-6">Product Description</div>
                            <div class="col-md-6">Details</div>
                            <!--<div class="col-md-4">Status</div>-->
                        </div>
                    @endif

                    <div class="card-body row">
                        <div class="col-md-6">
                            <div class="d-flex align-items-start">
                                <!-- Left side: Product Image -->
                                <div class="">
                                    <img src="{{ static_asset('assets/img/placeholder.jpg') }}" alt="{{ $item->product_title }}" class="img-fluid img-thumbnail product-image mb-2 rounded-start">
                                </div>
                            
                                <!-- Right side: Product Details -->
                                <div class="ml-3">
                                    <p class="mb-1"><strong>Title:</strong> <a href="{{ $item->product_link }}">{{ $item->product_title }}</a></p>
                            
                                    <p class="mb-1">
                                        <strong>Status:</strong>
                                        @if ($item->status == 'pending')
                                            <span class="badge badge-primary status-style">Pending</span>
                                        @elseif($item->status == 'approved')
                                            <span class="badge badge-success status-style">Approved</span>
                                        @else
                                            <span class="badge badge-danger status-style">Rejected</span>
                                        @endif
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <span><strong>Description:</strong> {{ $item->description }}</span><br>
                            <span><strong>Quantity:</strong> {{ $item->quantity }}</span><br>
                        </div>
                        
                    </div>
                    <!--<div class="card-footer d-flex justify-content-between align-items-center">-->
                    <!--    <div>-->
                    <!--        <p class="mb-1 fs-15"><strong>Tracking Code:</strong> -->
                    <!--            @if($item->status == 'approved')-->
                    <!--                {{ $item->order_code }}-->
                    <!--            @endif-->
                    <!--        </p>-->
                    <!--    </div>-->
                    <!--    <div class="customs-shipping">-->
                    <!--        <p class="mb-1 fs-15">-->
                    <!--            <strong>Customs & Shipping:</strong> -->
                    <!--            @if($item->status == 'approved')-->
                    <!--                <span class="customs-shipping-value">00 /-kg</span>  -->
                    <!--            @endif-->
                    <!--        </p>-->
                    <!--    </div>-->
                    <!--</div>-->
                </div>
            @endforeach

            <!-- Pagination -->
            <div class="aiz-pagination">
                {{ $rejectedRequestProducts->links() }}
            </div>
        @else
            <div class="row">
                <div class="col">
                    <div class="text-center bg-white p-4 border">
                        <img class="mw-100 h-200px" src="{{ static_asset('assets/img/nothing.svg') }}" alt="Image">
                        <h5 class="mb-0 h5 mt-3">{{ translate("There isn't anything added yet")}}</h5>
                    </div>
                </div>
            </div>
        @endif
       
    </div>


    {{-- @if (count($requestProducts) > 0)
    @foreach($requestProducts as $key => $item)
        <div class="card mb-3" id="request_{{ $item->id }}">
            <div class="card-body">
                <div class="row g-0">
                    <div class="col-md-6">
                        <h6 class="card-title">Product Name: {{ $item->product_title }}</h6>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
    @else
        <div class="row">
            <div class="col">
                <div class="text-center bg-white p-4 border">
                    <img class="mw-100 h-200px" src="{{ static_asset('assets/img/nothing.svg') }}" alt="Image">
                    <h5 class="mb-0 h5 mt-3">{{ translate("There isn't anything added yet")}}</h5>
                </div>
            </div>
        </div>
    @endif
    <!-- Pagination -->
    <div class="aiz-pagination">
        {{ $requestProducts->links() }}
    </div> --}}
@endsection


@section('script')
<script>
    $(document).on('click', '.start_order', function() {
        const routeUrl = $(this).data('route');
    
        // Confirmation using SweetAlert2 or basic confirmation
        Swal.fire({
            title: "Start Order?",
            text: "Are you sure you want to start order?",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Yes, Order it!"
        }).then((result) => {
            if (result.isConfirmed) {
                // AJAX request to start shipping
                $.ajax({
                    url: routeUrl,
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}' // Include CSRF token for Laravel
                    },
                    success: function(response) {
                        // Show success alert and update the UI
                        Swal.fire({
                            title: "Ordered!",
                            text: response.message,
                            icon: "success"
                        }).then(() => {
                            // Optionally reload the page or update the UI here
                            location.reload();
                        });
                    },
                    error: function(xhr) {
                        // Handle error
                        Swal.fire({
                            title: "Error!",
                            text: "There was an error starting the order.",
                            icon: "error"
                        });
                    }
                });
            }
        });
    });
    $(document).on('click', '.cancel_order', function() {
        const routeUrl = $(this).data('route');
    
        // Confirmation using SweetAlert2 or basic confirmation
        Swal.fire({
            title: "Cancel Order?",
            text: "Are you sure you want to cancel order?",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Yes, cancel it!"
        }).then((result) => {
            if (result.isConfirmed) {
                // AJAX request to cancel shipping
                $.ajax({
                    url: routeUrl,
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}' // Include CSRF token for Laravel
                    },
                    success: function(response) {
                        // Show success alert and update the UI
                        Swal.fire({
                            title: "Cancelled!",
                            text: response.message,
                            icon: "success"
                        }).then(() => {
                            // Optionally reload the page or update the UI here
                            location.reload();
                        });
                    },
                    error: function(xhr) {
                        // Handle error
                        Swal.fire({
                            title: "Error!",
                            text: "There was an error cancelling the order.",
                            icon: "error"
                        });
                    }
                });
            }
        });
    });
</script>
@endsection
