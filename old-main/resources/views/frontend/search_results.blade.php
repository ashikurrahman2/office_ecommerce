@extends('frontend.layouts.app')

@section('content')
    <section class="mb-4 pt-4">
        <div class="container sm-px-0 pt-2 px-1">
            <form id="search-form" action="{{ route('search') }}" method="GET">
                <div class="row gutters-5 flex-wrap align-items-center px-2">
                    <div class="col container">
                        <h1 class="fs-20 fs-md-24 fw-700 text-dark">
                            {{ translate('Search result for ') }}"{{ $keyword }}"
                        </h1>
                        <input type="hidden" name="keyword" value="{{ $keyword }}">

<!-- Filter Toggle Button (Visible only on mobile) -->
<div class="d-md-none text-end mb-3">
    <button class="btn btn-primary w-100 mt-3 mt-md-0" type="button" id="toggle-filter">
        Filter
    </button>
</div>

<!-- Min and Max Price Inputs -->
<div class="filter-section">
    <div class="row mt-3 align-items-end">
        <!-- Min Price Input -->
        <div class="col-md-4">
            <label for="min_price" class="form-label">Min Price</label>
            <input type="number" id="min_price" name="min_price" class="form-control" placeholder="Min Price" value="{{ request('min_price') }}">
        </div>

        <!-- Max Price Input -->
        <div class="col-md-4">
            <label for="max_price" class="form-label">Max Price</label>
            <input type="number" id="max_price" name="max_price" class="form-control" placeholder="Max Price" value="{{ request('max_price') }}">
        </div>

        <!-- Filter Button -->
        <div class="col-md-4">
            <button type="submit" class="btn btn-primary w-100 mt-3 mt-md-0">Filter</button>
        </div>
    </div>
</div>




                        <!-- Filter Button -->
                        

                        <!-- Showing X - Y of Z results message -->
                        <p class="fs-14 fs-md-16 text-muted mt-2">
                            Showing 
                            {{ ($products->currentPage() - 1) * $products->perPage() + 1 }} 
                            - 
                            {{ min($products->currentPage() * $products->perPage(), $products->total()) }} 
                            of {{ $products->total() }} for "{{ $keyword }}"
                        </p>
                    </div>
                </div>

                <!-- Check if products are available -->
                @if ($products->count() > 0)
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
                    <div class="">
                        <div class="col-xl-8 mx-auto">
                            <div class="border bg-white p-4">
                                <div class="text-center p-3">
                                    <i class="las la-frown la-3x opacity-60 mb-3"></i>
                                    <h3 class="h4">{{ translate('No products found..!!') }}</h3>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
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
        
 
     document.addEventListener('DOMContentLoaded', function () {
        const toggleButton = document.getElementById('toggle-filter');
        const filterSection = document.querySelector('.filter-section');

        // Initially hide the filter section on smaller screens
        if (window.innerWidth < 768) {
            filterSection.style.display = 'none';
        }

        // Add event listener for toggle button
        toggleButton.addEventListener('click', function () {
            if (filterSection.style.display === 'none' || filterSection.style.display === '') {
                filterSection.style.display = 'block';
                toggleButton.classList.add('btn-primary'); // Add active class
                toggleButton.classList.remove('btn-outline-primary'); // Remove default class
            } else {
                filterSection.style.display = 'none';
                toggleButton.classList.add('btn-outline-primary'); // Revert to default class
                toggleButton.classList.remove('btn-primary'); // Remove active class
            }
        });

        // Ensure the filter section is always visible on larger screens
        window.addEventListener('resize', function () {
            if (window.innerWidth >= 768) {
                filterSection.style.display = 'block';
                toggleButton.classList.add('btn-outline-primary'); // Default class for large screens
                toggleButton.classList.remove('btn-primary'); // Remove toggled state class
            } else if (filterSection.style.display !== 'block') {
                filterSection.style.display = 'none';
            }
        });
    });
    </script>
@endsection
