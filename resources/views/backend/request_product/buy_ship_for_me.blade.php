@extends('backend.layouts.app')

@section('content')
    <div class="card">
        <form class="" action="" id="sort_orders" method="GET">
            <div class="card-header row gutters-5">
                <div class="col">
                    <h5 class="mb-md-0 h6">{{ translate('All Buy & Ship Request') }}</h5>
                </div>
            </div> 

            <div class="card-body">
                <table class="table table-responsive aiz-table mb-0">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>{{ translate('Name') }}</th>
                            <th>{{ translate('Contact') }}</th>
                            <th>{{ translate('Product') }}</th>
                            <th>{{ translate('Quantity') }}</th>
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
                                    {{ $requestProduct->name }}
                                </td>
                                <td class="contact-column">
                                    <span class="contact-info">{{ $requestProduct->email }}</span><br>
                                    <span class="contact-info">{{ $requestProduct->phone }}</span>
                                </td>
                                <td>
                                    <a href="{{ $requestProduct->product_link }}">{{ $requestProduct->product_title }}</a>
                                </td>
                                <td>
                                    {{ $requestProduct->quantity }}
                                </td>
                                <td>
                                    {{ $requestProduct->description }}
                                </td>
                                <td>
                                    @if ($requestProduct->status == 'pending')
                                        <span class="badge badge-primary px-2 fs-12">Pending</span>
                                    @elseif($requestProduct->status == 'approved')
                                        <span class="badge badge-success px-2 fs-12">Approved</span>
                                    @else
                                        <span class="badge badge-danger px-2 fs-12">Rejected</span>
                                    @endif
                                </td>
                                <td class="text-right">
                                    @if($requestProduct->status == 'pending')
                                        <a href="javascript:void(0)"  class="btn btn-block btn-success btn-sm confirm-approve" 
                                           data-href="{{ route('admin.buy_ship_for_me.approve', $requestProduct->id) }}" 
                                           data-quantity="{{ $requestProduct->quantity }}">Approve</a>
                                        <a href="javascript:void(0)" class="btn btn-block btn-danger btn-sm confirm-cancel" data-href="{{ route('admin.buy_ship_for_me.cancel', $requestProduct->id) }}">Cancel</a>
                                    @else
                                        <a href="javascript:void(0)" 
                                           class="btn btn-block btn-success btn-sm confirm-approve" 
                                           data-href="{{ route('admin.buy_ship_for_me.approve', $requestProduct->id) }}" 
                                           data-quantity="{{ $requestProduct->quantity }}">Change to Approve</a>

                                        <a href="javascript:void(0)" class="btn btn-block btn-danger btn-sm confirm-delete2" data-href="{{ route('admin.buy_ship_for_me.delete', $requestProduct->id) }}">Delete</a>
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

@section('modal')
    @include('modals.delete_modal')
    @include('modals.change_status_modal')
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
    let oldQuantity = $(this).data('quantity'); // Get the old quantity from the button

    Swal.fire({
        title: 'Approve Request',
        html: `
            <div style="text-align: right;">
                <label for="price">Price:</label>
                <input type="number" id="price" class="swal2-input" placeholder="Enter price" required>
                
                <label for="quantity">Quantity:</label>
                <input type="number" id="quantity" class="swal2-input" placeholder="Enter quantity" required value="${oldQuantity}">
            </div>
        `,
        focusConfirm: false,
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Submit',
        preConfirm: () => {
            const price = Swal.getPopup().querySelector('#price').value;
            const quantity = Swal.getPopup().querySelector('#quantity').value;

            if (!price || !quantity) {
                Swal.showValidationMessage('Both fields are required!');
                return false;
            }
            return { price, quantity };
        }
    }).then((result) => {
        if (result.isConfirmed) {
            const { price, quantity } = result.value;

            // Make an AJAX POST request
            $.ajax({
                url: url,
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    price: price,
                    quantity: quantity
                },
                success: function(response) {
                    Swal.fire({
                        title: 'Approved!',
                        text: 'Request has been approved with the new values.',
                        icon: 'success'
                    }).then(() => {
                        location.reload(); // Reload page after success
                    });
                },
                error: function(xhr) {
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
        
         $(document).on('click', '.confirm-delete2', function() {
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
