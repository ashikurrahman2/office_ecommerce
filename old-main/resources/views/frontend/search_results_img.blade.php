@extends('frontend.layouts.app')



@section('content')
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
                            <img src="{{ $imageUrl }}" alt="Search Result Image" style="margin-left: 10px; width: 60px; height: 60px;">
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
