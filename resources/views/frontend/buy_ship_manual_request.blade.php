@extends('frontend.layouts.app')
@section('content')

<div class="container">
    <div class="row py-5">
        <div class="col-12">
            <h2 class="text-center fw-600">CPL Manual Request.</h2>
            <h6 class="text-center fw-300">You can manage your manual requested products here</h6>
        </div>
    </div>
    <div class="row justify-content-center">
        <div class="col-md-8 col-12">
            @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            <form id="manual-request-form" class="form-default pb-5" role="form" action="{{ route('buy_ship_product_request.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                    <label for="product_link" class="fs-12 fw-700 text-dark">Product Link<span class="text-danger">*</span></label>
                    <input type="text" class="form-control rounded-0" value="{{ $keyword }}" placeholder="Enter Product Link" name="product_link">
                    @error('product_link')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
            
                <div class="form-group">
                    <label for="product_title" class="fs-12 fw-700 text-dark">Product Title</label>
                    <input type="text" class="form-control rounded-0" required placeholder="Enter Product Title" name="product_title" value="{{ old('product_title') }}">
                    @error('product_title')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="quantity" class="fs-12 fw-700 text-dark">Quantity<span class="text-danger">*</span></label>
                    <input type="number" class="form-control rounded-0" required placeholder="Min: 1" name="quantity" value="{{ old('quantity') }}">
                    @error('quantity')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
            
                <div class="form-group">
                    <label for="description" class="fs-12 fw-700 text-dark">Description<span class="text-danger">*</span></label>
                    <textarea class="form-control rounded-0" name="description" rows="2" placeholder="Enter product details like Color: Red, Size: XXL">{{ old('description') }}</textarea>
                    @error('description')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
                
                <!-- Submit Button -->
                <div class="mb-4 mt-4">
                    <button type="submit" class="btn btn-danger btn-block fw-700 fs-14 rounded-0 submit-button">Make Request</button>
                </div>
            </form>
        </div>
    </div>
</div>



@endsection

@section('script')
<script>
    $(document).ready(function() {
        $(document).on('submit', '#manual-request-form', function(e) {
            e.preventDefault(); 

            var submitButton = $('.submit-button');

                // Add spinner to the submit button
                if (submitButton.find('.spinner-border').length === 0) {
                    submitButton.prop('disabled', true).append(
                        '<div class="spinner-border" role="status" style="width: 1rem; height: 1rem; margin-left: 0.5rem;">' +
                        '<span class="sr-only">Loading...</span>' +
                        '</div>'
                    );
                }



            // Clear any previous error messages
            $('.text-danger').remove();

            $.ajax({
                url: $(this).attr('action'), // Form action URL
                type: 'POST',
                data: $(this).serialize(), // Serialize form data
                success: function(response) {
                   
                    AIZ.plugins.notify('success', response.message);

                    // Optionally clear the form or redirect
                    $('#manual-request-form')[0].reset();

                    if (response.redirect) {
                        window.location.href = response.redirect; // Redirect to the new page
                    }
                },
                error: function(xhr) {
                    // Handle validation errors
                    if (xhr.responseJSON.errors) {
                        $.each(xhr.responseJSON.errors, function(key, value) {
                            // Append error messages next to the respective fields
                            const input = $('[name="' + key + '"]');
                            input.after('<div class="text-danger">' + value[0] + '</div>');
                        });
                    } else {
                        // Handle general error messages
                        alert('An error occurred. Please try again.');
                    }
                },
                complete: function() {
                    // Re-enable the submit button and remove spinner
                    submitButton.prop('disabled', false).find('.spinner-border').remove();
                }
            });
        });
    });
</script>
@endsection