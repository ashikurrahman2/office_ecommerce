@extends('backend.layouts.app')

@section('content')
    <div class="card">
        <form class="" action="" id="sort_orders" method="GET">
            <div class="card-header row gutters-5">
                <div class="col">
                    <h5 class="mb-md-0 h6">{{ translate('All Ship Request') }}</h5>
                </div>
            </div> 

           <div class="card-body">
            <table class="table table-responsive w-100 aiz-table mb-0">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>{{ translate('User') }}</th>
                        <th>{{ translate('Image') }}</th>
                        <th>{{ translate('Product') }}</th>
                        <th>{{ translate('Details') }}</th>
                        <th>{{ translate('Status') }}</th>
                        <th class="text-right" width="15%">{{ translate('Options') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($requestProducts as $key => $requestProduct)
                        <tr>
                            <td>{{ $key + 1 + ($requestProducts->currentPage() - 1) * $requestProducts->perPage() }}</td>
                            <td> 
                                <p class="mb-1"><strong>User Name:</strong> <span class="pl-2">{{ $requestProduct->user?->name }}</span></p>
                                <p class="mb-1"><strong>User Contact:</strong> <span class="contact-info">{{ $requestProduct->user?->phone }}</span>
                                <span class="contact-info">{{ $requestProduct->user?->email }}</span></p>
                            </td>
                            <td>
                                @php 
                                    $images = json_decode($requestProduct->images); 
                                @endphp
        
                                @if (count($images) > 0)
                                    <div style="display: flex; flex-wrap: nowrap; overflow: hidden;">
                                        @foreach ($images as $key => $image)
                                            @if ($key < 3)
                                                <img src="{{ asset('public/'.$image) }}" class="img-fluid img-thumbnail" alt="{{ $requestProduct->product_title }}" style="width: 100px; height: 100px; object-fit: cover; margin-right: 5px;">
                                            @endif
                                        @endforeach
                                    </div>
                                @endif
                            </td>
                            <td>
                              <h6 class="mt-2">Title: 
                                    @if($requestProduct->product_link)
                                       <a href="{{ $requestProduct->product_link }}" class="text-primary" target="_blank">{{ $requestProduct->product_title }}</a>
                                    @else
                                        <span>{{ $requestProduct->product_title }}</span>
                                    @endif
                                </h6>


                                <p class="mb-1"><strong>Category Name:</strong> <span class="pl-2">{{ $requestProduct->category_name }}</span></p>
                            </td>
                            <td>
                                <div style="width: 100%; overflow-wrap: break-word;">
                                    <span><strong>Price:</strong> {{ number_format($requestProduct->price, 2) }}</span><br>
                                    <span><strong>Quantity:</strong> {{ $requestProduct->quantity }}</span><br>
                                    <span><strong>Category:</strong> {{ $requestProduct->category_name }}</span><br>
                                    <span><strong>Product Weight:</strong> {{ $requestProduct->weight }} KG</span><br>
                                    <span><strong>Ship to:</strong> {{ \Carbon\Carbon::parse($requestProduct->ship_to)->format('Y-m-d') }}</span><br>
                                    <span><strong>Valid to:</strong> {{ \Carbon\Carbon::parse($requestProduct->valid_to)->format('Y-m-d') }}</span><br>
                                    <span><strong>Length*Width*Height:</strong> {{ $requestProduct->length*$requestProduct->width*$requestProduct->height }}</span><br>
                                    <span><strong>Description:</strong> {{ $requestProduct->description }}</span><br>
                                </div>
                            </td>
                            <td>
                                @if ($requestProduct->status == 'pending')
                                    <span class="badge badge-primary px-2 fs-12">In Review</span>
                                @elseif($requestProduct->status == 'approved')
                                    <span class="badge badge-success px-2 fs-12">Approved</span>
                                @else
                                    <span class="badge badge-danger px-2 fs-12">Rejected</span>
                                @endif
                            </td>
                            <td class="text-right">
                                @if($requestProduct->status == 'pending')
                               <a href="javascript:void(0)" 
                                   class="btn btn-block btn-success btn-sm add-warehouse" 
                                   data-href="{{ route('admin.ship_for_me.warehouse', $requestProduct->id) }}" 
                                   data-warehouse-details="{{ $requestProduct->warehouse_details }}">
                                   Add Warehouse
                                </a>
                                    <a href="javascript:void(0)" class="btn btn-block btn-success btn-sm confirm-approve" data-href="{{ route('admin.ship_for_me.approve', $requestProduct->id) }}">Approve</a>
                                    <a href="javascript:void(0)" class="btn btn-block btn-danger btn-sm confirm-cancel" data-href="{{ route('admin.ship_for_me.cancel', $requestProduct->id) }}">Cancel</a>
                                @else
                           <a href="javascript:void(0)" 
                               class="btn btn-block btn-success btn-sm add-warehouse" 
                               data-href="{{ route('admin.ship_for_me.warehouse', $requestProduct->id) }}" 
                               data-warehouse-details="{{ $requestProduct->warehouse_details }}">
                               Add Warehouse
                            </a>

                                    <a href="javascript:void(0)" class="btn btn-block btn-success btn-sm confirm-approve" data-href="{{ route('admin.ship_for_me.approve', $requestProduct->id) }}">Change to Approve</a>
                                    <a href="javascript:void(0)" class="btn btn-block btn-danger btn-sm confirm-delete" data-href="{{ route('admin.ship_for_me.delete', $requestProduct->id) }}">Delete</a>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        
            <div class="aiz-pagination">
                {{ $requestProducts->appends(request()->input())->links() }}
            </div>
        </div>

        </form>
    </div>

@endsection
@section('script')
    <script type="text/javascript">
        $(document).on("change", ".check-all", function() {
            if (this.checked) {
                // Iterate each checkbox
                $('.check-one:checkbox').each(function() {
                    this.checked = true;
                });
            } else {
                $('.check-one:checkbox').each(function() {
                    this.checked = false;
                });
            }
        });

        
        $(document).on('click', '.confirm-approve', function() {
            let url = $(this).data('href');
    
            Swal.fire({
                title: "Are you sure?",
                text: "You won't be able to revert this!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, Approve It!"
                }).then((result) => {
                if (result.isConfirmed) {

                    $.ajax({
                        url: url,
                        method: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}' 
                        },
                        success: function(response) {
                            Swal.fire({
                                title: "Approved!",
                                text: "Your file has been approved.",
                                icon: "success"
                            });
                            location.reload(); 
                        },
                        error: function(xhr) {
                            alert('There was an error processing your request.'); // Handle error
                        }
                    });
                }
            });
        });
        
       $(document).on('click', '.add-warehouse', function () {
    let url = $(this).data('href'); // Get the action URL
    let oldDetails = $(this).data('warehouse-details'); // Get the old warehouse details

    Swal.fire({
        title: 'Approve Request',
        html: `
            <div style="text-align: left;">
                <label for="warehouse_details">Warehouse Details:</label>
                <textarea id="warehouse_details" class="swal2-textarea" placeholder="Enter Warehouse Details">${oldDetails || ''}</textarea>
            </div>
        `,
        focusConfirm: false,
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Submit',
        preConfirm: () => {
            const warehouseDetails = Swal.getPopup().querySelector('#warehouse_details').value;

            if (!warehouseDetails) {
                Swal.showValidationMessage('Warehouse details are required!');
                return false;
            }

            return { warehouseDetails }; // Return the data
        }
    }).then((result) => {
        if (result.isConfirmed) {
            const { warehouseDetails } = result.value;

            // Make an AJAX POST request
            $.ajax({
                url: url,
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    warehouse_details: warehouseDetails
                },
                success: function (response) {
                    Swal.fire({
                        title: 'Added!',
                        text: 'Request has been changed with the provided details.',
                        icon: 'success'
                    }).then(() => {
                        location.reload(); // Reload page after success
                    });
                },
                error: function (xhr) {
                    Swal.fire({
                        title: 'Error!',
                        text: 'There was an error processing your request.',
                        icon: 'error'
                    });
                }
            });
        }
    });
});



        $(document).on('click', '.confirm-cancel', function() {
             let url = $(this).data('href');
            Swal.fire({
                title: "Are you sure?",
                text: "You won't be able to revert this!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, Cancel It!"
                }).then((result) => {
                if (result.isConfirmed) {

                    $.ajax({
                        url: url,
                        method: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}' 
                        },
                        success: function(response) {
                            Swal.fire({
                                title: "Cancelled!",
                                text: "Your file has been cancelled.",
                                icon: "success"
                            });
                            location.reload(); 
                        },
                        error: function(xhr) {
                            alert('There was an error processing your request.'); // Handle error
                        }
                    });
                }
            });
        });
         $(document).on('click', '.confirm-delete', function() {
            let url = $(this).data('href');
            
            Swal.fire({
                title: "Are you sure?",
                text: "You won't be able to revert this!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, Delete It!"
                }).then((result) => {
                if (result.isConfirmed) {

                    $.ajax({
                        url: url,
                        method: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}' 
                        },
                        success: function(response) {
                            Swal.fire({
                                title: "Deleted!",
                                text: "Your file has been deleted.",
                                icon: "success"
                            });
                            location.reload(); 
                        },
                        error: function(xhr) {
                            alert('There was an error processing your request.'); // Handle error
                        }
                    });
                }
            });
        });
        function bulk_delete() {
            var data = new FormData($('#sort_orders')[0]);
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "{{ route('bulk-order-delete') }}",
                type: 'POST',
                data: data,
                cache: false,
                contentType: false,
                processData: false,
                success: function(response) {
                    if (response == 1) {
                        location.reload();
                    }
                }
            });
        }
    </script>
    
@endsection
