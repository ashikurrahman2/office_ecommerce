@extends('frontend.layouts.app')



@section('content')
                         <style>
    .crop-wrapper {
        position: relative;
        width: 70px;
        height: 70px;
        cursor: pointer;
        border: 1px solid #ccc;
        border-radius: 6px;
        overflow: hidden;
        transition: box-shadow 0.2s ease;
    }

    .crop-wrapper:hover {
        box-shadow: 0 0 8px rgba(0, 0, 0, 0.25);
    }

    .crop-wrapper img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .crop-label {
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        background-color: rgba(0, 0, 0, 0.65);
        color: white;
        font-size: 11px;
        text-align: center;
        padding: 2px 0;
        font-weight: 500;
        pointer-events: none;
    }
</style>

    <section class="mb-4 pt-4">
        <div class="container sm-px-0 pt-2 px-1">
            <form class="" id="search-form" action="" method="GET">

               <div class="row gutters-5 flex-wrap align-items-center px-2">
                    <div class="col">
                        <div class="d-flex align-items-center">
                            <h1 class="fs-18 fs-md-24 fw-700 text-dark">
                                {{ translate('Search result for ') }} {{ $keyword2 }} :
                            </h1>
                            <!-- Display the image next to the heading -->
  

                        <div class="crop-wrapper" id="openCropperWrapper" title="Click to crop this image">
                            <img id="openCropper" src="{{ $imageUrl }}" alt="Search Result Image">
                            <div class="crop-label">Crop</div>
                        </div>

                        </div>
                        <input type="hidden" name="keyword" value="{{ $keyword2 }}">
                
                        <!-- Showing X - Y of Z results message -->
                        <p class="fs-14 fs-md-16 text-muted mt-2">
                            Showing 
                            {{ ($products->currentPage() - 1) * $products->perPage() + 1 }} 
                            - 
                            {{ min($products->currentPage() * $products->perPage(), $products->total()) }} 
                            of {{ $products->total() }} for {{ $keyword2 }}
                        </p>
                    </div>
                </div>

                @include('frontend.partials.product_loader')
                
                <div id="productContainer">
                <!-- Check if products are available -->
                @if ($products->count() > 0)
                    <!-- Products Section -->
                    <div class="row mx-2">
                        @foreach ($products as $product)
                            <div class="col-5-custom position-relative px-0 has-transition hov-animate-outline">
                                <div class="px-1 mb-3 custom-product-box">
                                    @include('frontend.'.get_setting('homepage_select').'.partials.product_box',['product' => $product])
                                </div>
                            </div>
                        @endforeach
                    </div>
                    {{ $products->links('frontend.inc.custom') }} 
                </div>

                @else
                    <!-- No products found message -->
                    <div class="row">
                        <div class="col-xl-8 mx-auto">
                            <div class="border bg-white p-4">
                                <!-- Empty Product -->
                                <div class="text-center p-3">
                                    <i class="las la-frown la-3x opacity-60 mb-3"></i>
                                    <h3 class="h4">{{ translate('No products found..!!') }}</h3>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
                </div>
                {{-- <div class="row mx-2">
                    @foreach ($products as $product)
                        <div class="col-5-custom position-relative px-0 has-transition hov-animate-outline">
                            <div class="px-1 mb-3 custom-product-box">
                                @include('frontend.'.get_setting('homepage_select').'.partials.product_box',['product' => $product])
                            </div>
                        </div>
                    @endforeach
                </div>
                {{ $products->links() }} --}}
            </form>
        </div>
    </section>
<!-- Crop Modal -->
<!-- Cropper Modal (Smaller Size) -->
<div class="modal fade" id="cropperModal" tabindex="-1" aria-labelledby="cropperModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-md modal-dialog-centered"> <!-- Changed from modal-lg to modal-md -->
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Crop Image</h5>
       <button type="button" class="btn btn-sm btn-light" data-bs-dismiss="modal" aria-label="Close" style="border: none; background: none;">
    <i class="fa fa-times fa-lg text-secondary"></i>
</button>

      </div>
      <div class="modal-body text-center">
        <!-- Limit size of the cropping area -->
        <img id="cropImage" style="max-width: 100%; max-height: 300px; object-fit: contain;">
      </div>
      <div class="modal-footer">
        <button type="button" id="cropBtn" class="btn btn-primary">Crop & Search</button>
      </div>
    </div>
  </div>
</div>
<form id="cropSubmitForm" method="POST" action="{{ route('search.image') }}" enctype="multipart/form-data" style="display: none;">
    @csrf
    <input type="file" name="image" id="croppedImageInput">
</form>

<!-- Include CSS & JS only once (ideally in your layout) -->
<!--<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">-->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<link href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css" rel="stylesheet">
<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js"></script>

<script>
    let cropper;
    const cropModal = new bootstrap.Modal(document.getElementById('cropperModal'));
    const cropImage = document.getElementById('cropImage');
    const openCropper = document.getElementById('openCropper');

    openCropper.addEventListener('click', function () {
        cropImage.src = this.src;
        cropModal.show();

        cropModal._element.addEventListener('shown.bs.modal', function () {
            if (cropper) cropper.destroy();
            cropper = new Cropper(cropImage, {
                aspectRatio: NaN,
                viewMode: 1,
            });
        }, { once: true });
    });

    document.getElementById('cropBtn').addEventListener('click', function () {
    if (!cropper) return;

    cropModal.hide(); // hide modal
    showGlobalLoader(); // show loader

    cropper.getCroppedCanvas().toBlob(function (blob) {
        const file = new File([blob], 'cropped.jpg', { type: 'image/jpeg' });
        const dt = new DataTransfer();
        dt.items.add(file);
        document.getElementById('croppedImageInput').files = dt.files;

        document.getElementById('cropSubmitForm').submit();
    });
});

</script>


@endsection

@section('script')
    <script type="text/javascript">
        function filter(){
            $('#search-form').submit();
        }
        function rangefilter(arg){
            $('input[name=min_price]').val(arg[0]);
            $('input[name=max_price]').val(arg[1]);
            filter();
        }
        
        
    </script>
    

@endsection
