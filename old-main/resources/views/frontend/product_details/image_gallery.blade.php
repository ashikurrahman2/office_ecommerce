<div class="sticky-top z-3 row gutters-10">

    <!-- Gallery Images -->
<div class="col-12">
    <div class="aiz-carousel product-gallery arrow-inactive-transparent arrow-lg-none"
         data-nav-for='.product-gallery-thumb' data-fade='true' data-auto-height='true' data-arrows='true'>
        
        @foreach($detailedProduct['Pictures'] as $picture)
            @if(!empty($picture['Large']['Url']))
                <div class="carousel-box img-zoom rounded-0">
                    <img class="img-fluid h-auto lazyload mx-auto"
                         src="{{ static_asset('assets/img/placeholder.jpg') }}"
                         data-src="{{ $picture['Large']['Url'] }}"
                         onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';">
                </div>
            @endif
        @endforeach
        
    </div>
</div>

<!-- Thumbnail Images -->
<div class="col-12 mt-3 d-none d-lg-block">
    <div class="aiz-carousel half-outside-arrow product-gallery-thumb"
         data-items='7' data-nav-for='.product-gallery' data-focus-select='true' data-arrows='true' data-vertical='false' data-auto-height='true'>
        
        @foreach($detailedProduct['Pictures'] as $picture)
            @if(!empty($picture['Small']['Url']))
                <div class="carousel-box c-pointer rounded-0">
                    <img class="lazyload mw-100 size-60px mx-auto border p-1"
                         src="{{ static_asset('assets/img/placeholder.jpg') }}"
                         data-src="{{ $picture['Small']['Url'] }}"
                         onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';">
                </div>
            @endif
        @endforeach
        
    </div>
</div>







    {{-- <div class="col-12">
        <div class="aiz-carousel product-gallery arrow-inactive-transparent arrow-lg-none"
             data-nav-for='.product-gallery-thumb' data-fade='true' data-auto-height='true' data-arrows='true'>
            
            @foreach($detailedProduct['Pictures'] as $picture)
                @if(!empty($picture['Large']['Url']))
                    <div class="carousel-box img-zoom rounded-0">
                        <img class="img-fluid h-auto lazyload mx-auto"
                             src="{{ static_asset('assets/img/placeholder.jpg') }}"
                             data-src="{{ $picture['Large']['Url'] }}"
                             onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';">
                    </div>
                @endif
            @endforeach
            
        </div>
    </div>
    
    
    <!-- Thumbnail Images -->
    <div class="col-12 mt-3 d-none d-lg-block">
        <div class="aiz-carousel half-outside-arrow product-gallery-thumb"
             data-items='7' data-nav-for='.product-gallery' data-focus-select='true' data-arrows='true' data-vertical='false' data-auto-height='true'>
            
            @foreach($detailedProduct['Pictures'] as $picture)
                @if(!empty($picture['Small']['Url']))
                    <div class="carousel-box c-pointer rounded-0">
                        <img class="lazyload mw-100 size-60px mx-auto border p-1"
                             src="{{ static_asset('assets/img/placeholder.jpg') }}"
                             data-src="{{ $picture['Small']['Url'] }}"
                             onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';">
                    </div>
                @endif
            @endforeach
            
        </div>
    </div> --}}
    

</div>
