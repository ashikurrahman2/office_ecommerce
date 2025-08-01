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

    <!-- Tabs -->
    <div class="user-tabs d-flex">
        <a href="javascript:void(0)" class="tab-link active" onclick="switchTab(event, 'pending')">Pending
            ({{ $statusCounts->pending_count ?? 0 }})</a>
        <a href="javascript:void(0)" class="tab-link ml-3" onclick="switchTab(event, 'approved')">Approved
            ({{ $statusCounts->approved_count ?? 0 }})</a>
        <a href="javascript:void(0)" class="tab-link ml-3" onclick="switchTab(event, 'rejected')">Rejected
            ({{ $statusCounts->rejected_count ?? 0 }})</a>
        {{-- <input type="text" class="ml-auto form-control" placeholder="Order number" style="width: 200px;"> --}}
    </div>


    <!-- Tab Content -->
    <div id="pending" class="user-tab-content active">
        @if ($statusCounts->pending_count > 0)
            @foreach ($pendingRequestProducts as $key => $item)
                <div class="card card-container">
                    @if ($loop->first)
                        <div class="card-header d-none d-md-flex">
                            <div class="col-md-4">Product Description</div>
                            <div class="col-md-4">Warehouse Address</div>
                            <div class="col-md-4">Action</div>
                        </div>
                    @endif

                    <div class="card-body row">
                        <div class="col-md-4">
                            @php 
                                $images = json_decode($item->images); 
                            @endphp

                            @if (count($images) > 0)
                                @foreach ($images as $key2 => $image)
                                    @if ($key2 < 3)
                                        <img src="{{ asset('public/'.$image) }}" class="img-fluid img-thumbnail product-image mb-2 rounded-start" alt="{{ $item->product_title }}">
                                    @endif
                                @endforeach
                            @endif

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

                            <p class="mb-1">
                                <a class="text-primary product-details-link" data-toggle="collapse" href="#details-{{ $key }}"
                                    role="button" aria-expanded="false" aria-controls="collapseExample">Product details &#9662;
                                </a>
                            </p>

                            <div class="collapse" id="details-{{ $key }}">
                                <div style="width: 100%; overflow-wrap: break-word;">
                                    {{-- <span><strong>Order No:</strong> {{ $item->order_code }}</span><br> --}}
                                    <span><strong>Description:</strong> {{ $item->description }}</span><br>
                                    <span><strong>Price:</strong> {{ number_format($item->price, 2) }}</span><br>
                                    <span><strong>Quantity:</strong> {{ $item->quantity }}</span><br>
                                    <span><strong>Category:</strong> {{ $item->category_name }}</span><br>
                                    <span><strong>Product Weight:</strong> {{ $item->weight }} KG</span><br>
                                    <span><strong>Ship to:</strong>
                                        {{ \Carbon\Carbon::parse($item->ship_to)->format('Y-m-d') }}</span><br>
                                    <span><strong>Valid to:</strong>
                                        {{ \Carbon\Carbon::parse($item->valid_to)->format('Y-m-d') }}</span><br>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                           
                        </div>
                        <div class="col-md-4 text-center">
                            
                        </div>
                    </div>
                    <div class="card-footer d-flex justify-content-between align-items-center">
                        <div>
                            <p class="mb-1 fs-15"><strong>Tracking Code:</strong></p>
                        </div>
                        <div class="customs-shipping">
                            <p class="mb-1 fs-15">
                                <strong>Customs & Shipping:</strong> 
                                @if($item->status == 'approved')
                                    <span class="customs-shipping-value">2200 /-kg</span>  
                                @endif
                            </p>
                            
                        </div>
                    </div>
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
                            <div class="col-md-4">Product Description</div>
                            <div class="col-md-4">Warehouse Address</div>
                            <div class="col-md-4">Action</div>
                        </div>
                    @endif

                    <div class="card-body row">
                        <div class="col-md-4">
                            @php 
                                $images = json_decode($item->images); 
                            @endphp

                            @if (count($images) > 0)
                                @foreach ($images as $key => $image)
                                    @if ($key < 3)
                                        <img src="{{ asset('public/'.$image) }}" class="img-fluid img-thumbnail product-image mb-2 rounded-start" alt="{{ $item->product_title }}">
                                    @endif
                                @endforeach
                            @endif

                            <p class="mb-1"><strong>Title:</strong> <a href="{{ $item->product_link }}">{{ $item->product_title }}</a></p>
                            

                            <p class="mb-1">
                                <strong>Status:</strong>
                                    @if ($item->order_status == 'shipment_done')
                                        <span class="badge badge-primary status-style">Shipment Done</span>
                                    @elseif($item->order_status == 'goods_received_in_bangladesh')
                                        <span class="badge badge-success status-style">Goods Received in Bangladesh</span>
                                    @elseif($item->order_status == 'ready_to_deliver')
                                        <span class="badge badge-danger status-style">Ready to Deliver</span>
                                    @elseif($item->order_status == 'shipment_cancelled')
                                        <span class="badge badge-danger status-style">Shipment Cancelled</span>
                                    @endif
                            </p>

                            <p class="mb-1">
                                <a class="text-primary product-details-link" data-toggle="collapse" href="#details-{{ $key }}"
                                    role="button" aria-expanded="false" aria-controls="collapseExample">Product details &#9662;
                                </a>
                            </p>

                            <div class="collapse" id="details-{{ $key }}">
                                <div style="width: 100%; overflow-wrap: break-word;">
                                    {{-- <span><strong>Order No:</strong> {{ $item->order_code }}</span><br> --}}
                                    <span><strong>Description:</strong> {{ $item->description }}</span><br>
                                    <span><strong>Price:</strong> {{ number_format($item->price, 2) }}</span><br>
                                    <span><strong>Quantity:</strong> {{ $item->quantity }}</span><br>
                                    <span><strong>Category:</strong> {{ $item->category_name }}</span><br>
                                    <span><strong>Product Weight:</strong> {{ $item->weight }} KG</span><br>
                                    <span><strong>Ship to:</strong>
                                        {{ \Carbon\Carbon::parse($item->ship_to)->format('Y-m-d') }}</span><br>
                                    <span><strong>Valid to:</strong>
                                        {{ \Carbon\Carbon::parse($item->valid_to)->format('Y-m-d') }}</span><br>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            @if($item->status == 'approved')
                                <p class="mb-1"><span class="section-label">Name:</span> Frey</p>
                                <p class="mb-1"><span class="section-label">Mobile:</span> 17604702654</p>
                                <p class="mb-1"><span class="section-label">Address:</span> 广州市白云区槎路同雅东街59号. 同德仓库A3<br>
                                Baiyun District, Guangzhou City. 59 Tong Ya Dong Street, Xicha Road. Tongdecang A3</p>
                            @endif
                        </div>
                        <div class="col-md-4 text-center">
                            @if($item->status == 'approved')
                                @if($item->order_status == NULL)
                                <button class="btn-start start_shipment" data-route="{{route('ship_product_request.start_shipment', $item->id)}}">Start Shipping</button>
                                @endif

                                @if($item->order_status != 'shipment_done')
                                <button class="btn-cancel cancel_shipment" data-route="{{route('ship_product_request.cancel_shipment', $item->id)}}">Cancel</button>
                                @endif
                                @if($item->order_status == NULL)
                                <div class="countdown mt-2">
                                    <p class="mb-1">Your request will expire:</p>
                                    <p class="mb-1">Please start shipping and provide valid tracking code</p>
                                    <div>Days <strong>00</strong> Hours <strong>19</strong> Min <strong>31</strong> Sec <strong>46</strong></div>
                                </div>
                                @endif
                            @endif
                        </div>
                    </div>
                    <div class="card-footer d-flex justify-content-between align-items-center">
                        <div>
                            <p class="mb-1 fs-15"><strong>Tracking Code:</strong> 
                                @if($item->status == 'approved')
                                    {{ $item->order_code }}
                                @endif
                            </p>
                        </div>
                        <div class="customs-shipping">
                            <p class="mb-1 fs-15">
                                <strong>Customs & Shipping:</strong> 
                                @if($item->status == 'approved')
                                    <span class="customs-shipping-value">00 /-kg</span>  
                                @endif
                            </p>
                            
                        </div>
                    </div>
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
                        <h5 class="mb-0 h5 mt-3">{{ translate("There isn't anything added yet") }}</h5>
                    </div>
                </div>
            </div>
        @endif
    </div>

    <div id="rejected" class="user-tab-content">
        @if ($statusCounts->rejected_count > 0)
            @foreach ($rejectedRequestProducts as $key => $item)
                <div class="card card-container">
                    @if ($loop->first)
                        <div class="card-header d-none d-md-flex">
                            <div class="col-md-4">Product Description</div>
                            <div class="col-md-4">Warehouse Address</div>
                            <div class="col-md-4">Action</div>
                        </div>
                    @endif

                    <div class="card-body row">
                        <div class="col-md-4">
                            @php 
                                $images = json_decode($item->images); 
                            @endphp

                            @if (count($images) > 0)
                                @foreach ($images as $key => $image)
                                    @if ($key < 3)
                                        <img src="{{ asset('public/'.$image) }}" class="img-fluid img-thumbnail product-image mb-2 rounded-start" alt="{{ $item->product_title }}">
                                    @endif
                                @endforeach
                            @endif

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

                            <p class="mb-1">
                                <a class="text-primary product-details-link" data-toggle="collapse" href="#details-{{ $key }}"
                                    role="button" aria-expanded="false" aria-controls="collapseExample">Product details &#9662;
                                </a>
                            </p>

                            <div class="collapse" id="details-{{ $key }}">
                                <div style="width: 100%; overflow-wrap: break-word;">
                                    {{-- <span><strong>Order No:</strong> {{ $item->order_code }}</span><br> --}}
                                    <span><strong>Description:</strong> {{ $item->description }}</span><br>
                                    <span><strong>Price:</strong> {{ number_format($item->price, 2) }}</span><br>
                                    <span><strong>Quantity:</strong> {{ $item->quantity }}</span><br>
                                    <span><strong>Category:</strong> {{ $item->category_name }}</span><br>
                                    <span><strong>Product Weight:</strong> {{ $item->weight }} KG</span><br>
                                    <span><strong>Ship to:</strong>
                                        {{ \Carbon\Carbon::parse($item->ship_to)->format('Y-m-d') }}</span><br>
                                    <span><strong>Valid to:</strong>
                                        {{ \Carbon\Carbon::parse($item->valid_to)->format('Y-m-d') }}</span><br>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            @if($item->status == 'approved')
                                <p class="mb-1"><span class="section-label">Name:</span> Frey</p>
                                <p class="mb-1"><span class="section-label">Mobile:</span> 17604702654</p>
                                <p class="mb-1"><span class="section-label">Address:</span> 广州市白云区槎路同雅东街59号. 同德仓库A3<br>
                                Baiyun District, Guangzhou City. 59 Tong Ya Dong Street, Xicha Road. Tongdecang A3</p>
                            @endif
                        </div>
                        <div class="col-md-4 text-center">
                            @if($item->status == 'approved')
                                <button class="btn-start">Start Shipping</button>
                                <button class="btn-cancel">Cancel</button>
                                <div class="countdown mt-2">
                                    <p class="mb-1">Your request will expire:</p>
                                    <p class="mb-1">Please start shipping and provide valid tracking code</p>
                                    <div>Days <strong>00</strong> Hours <strong>19</strong> Min <strong>31</strong> Sec <strong>46</strong></div>
                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="card-footer d-flex justify-content-between align-items-center">
                        <div>
                            <p class="mb-1 fs-15"><strong>Tracking Code:</strong> 
                                @if($item->status == 'approved')
                                    {{ $item->order_code }}
                                @endif
                            </p>
                        </div>
                        <div class="customs-shipping">
                            <p class="mb-1 fs-15">
                                <strong>Customs & Shipping:</strong> 
                                @if($item->status == 'approved')
                                    <span class="customs-shipping-value">2200 /-kg</span>  
                                @endif
                            </p>
                            
                        </div>
                    </div>
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
                        <h5 class="mb-0 h5 mt-3">{{ translate("There isn't anything added yet") }}</h5>
                    </div>
                </div>
            </div>
        @endif
        
    </div>
@endsection



@section('script')
<script>
    $(document).on('click', '.start_shipment', function() {
        const routeUrl = $(this).data('route');
    
        // Confirmation using SweetAlert2 or basic confirmation
        Swal.fire({
            title: "Start Shipping?",
            text: "Are you sure you want to start shipping?",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Yes, start it!"
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
                            title: "Started!",
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
                            text: "There was an error starting the shipping.",
                            icon: "error"
                        });
                    }
                });
            }
        });
    });
    $(document).on('click', '.cancel_shipment', function() {
        const routeUrl = $(this).data('route');
    
        // Confirmation using SweetAlert2 or basic confirmation
        Swal.fire({
            title: "Cancel Shipping?",
            text: "Are you sure you want to cancel shipping?",
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
                            text: "There was an error cancelling the shipping.",
                            icon: "error"
                        });
                    }
                });
            }
        });
    });
</script>
    <script type="text/javascript">
        function cancelRequest(id) {
            $.post('{{ route('ship_product_request.cancel') }}', {
                _token: '{{ csrf_token() }}',
                id: id
            }, function(data) {
                AIZ.plugins.notify('success', data.message);
                // Reload the page after success
                location.reload();
            });
        }

        function deleteRequest(id) {
            $.post('{{ route('ship_product_request.destroy') }}', {
                _token: '{{ csrf_token() }}',
                id: id
            }, function(data) {
                AIZ.plugins.notify('success', data.message);
                // Reload the page after success
                location.reload();
            });
        }
    </script>
@endsection
