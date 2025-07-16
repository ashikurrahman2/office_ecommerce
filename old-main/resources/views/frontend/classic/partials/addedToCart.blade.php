
<div class="modal-body px-4 py-2 c-scrollbar-light">
    <!-- Item added to your cart -->
    <div class="text-center text-success mb-4">
        <svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" viewBox="0 0 36 36">
            <g id="Group_23957" data-name="Group 23957" transform="translate(-6269 7766)">
              <path id="Path_28713" data-name="Path 28713" d="M12.8,32.8a3.6,3.6,0,1,0,3.6,3.6A3.584,3.584,0,0,0,12.8,32.8ZM2,4V7.6H5.6l6.471,13.653-2.43,4.41A3.659,3.659,0,0,0,9.2,27.4,3.6,3.6,0,0,0,12.8,31H34.4V27.4H13.565a.446.446,0,0,1-.45-.45.428.428,0,0,1,.054-.216L14.78,23.8H28.19a3.612,3.612,0,0,0,3.15-1.854l6.435-11.682A1.74,1.74,0,0,0,38,9.4a1.8,1.8,0,0,0-1.8-1.8H9.587L7.877,4H2ZM30.8,32.8a3.6,3.6,0,1,0,3.6,3.6A3.584,3.584,0,0,0,30.8,32.8Z" transform="translate(6267 -7770)" fill="#85b567"/>
              <rect id="Rectangle_18068" data-name="Rectangle 18068" width="9" height="3" rx="1.5" transform="translate(6284.343 -7757.879) rotate(45)" fill="#fff"/>
              <rect id="Rectangle_18069" data-name="Rectangle 18069" width="3" height="13" rx="1.5" transform="translate(6295.657 -7760.707) rotate(45)" fill="#fff"/>
            </g>
        </svg>
        <h3 class="fs-28 fw-500">{{ translate('Item added to your cart!')}}</h3>
    </div>

    <!-- Product Info -->
@if (isset($carts) && count($carts) > 0)
    <ul class="h-250px overflow-auto c-scrollbar-light list-group list-group-flush mx-1">
        @foreach ($carts as $key => $cartItem)
            <li class="list-group-item py-1 border-0 hov-scale-img">
                <span class="d-flex align-items-center">
                    <!-- Link to Product -->
                   
                        <!-- Product Image -->
                         <img src="{{ $cartItem->image }}"
                             class="mr-4 lazyload size-90px img-fit rounded-0"
                             alt="{{ $cartItem->product_title }}"
                             onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';">
                        
                        <!-- Product Details -->
                        <span class="minw-0 pl-2 flex-grow-1">
                            <span class="fw-700 fs-13 text-dark mb-2 text-truncate-2" 
                                  title="{{ $cartItem->product_title }}">
                                {{ $cartItem->product_title }}
                            </span>
                            <span class="fs-14 fw-400 text-secondary">
                                {{ $cartItem->quantity }}x
                            </span>
                            <span class="fs-14 fw-400 text-secondary">
                                {{ number_format($cartItem->price, 2) }}
                            </span>
                        </span>
                        
                    <!-- Remove Button -->
                    
                </span>
            </li>
        @endforeach
    </ul>
@else
    <div class="text-center p-3">
        <i class="las la-frown la-3x opacity-60 mb-3"></i>
        <h3 class="h6 fw-700">{{ translate('Your Cart is empty') }}</h3>
    </div>
@endif


<div class="py-3 text-center border-top mx-4">
    <div class="row gutters-10 justify-content-center">
         <div class="col-sm-6">
            <button class="btn btn-warning mb-3 mb-sm-0 btn-block rounded-0 text-white" data-dismiss="modal">{{ translate('Back to shopping')}}</button>
        </div>
        <div class="col-sm-6">
            <a href="{{ route('cart') }}" class="btn btn-primary mb-3 mb-sm-0 btn-block rounded-0">{{ translate('Proceed to Checkout')}}</a>
        </div>
    </div>
</div>



   
</div>
