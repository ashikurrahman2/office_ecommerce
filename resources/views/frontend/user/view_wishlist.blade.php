@extends('frontend.layouts.user_panel')

@section('panel_content')
    <div class="aiz-titlebar mb-4">
        <div class="row align-items-center">
            <div class="col-md-6">
                <b class="fs-20 fw-700 text-dark">{{ translate('Wishlist')}}</b>
            </div>
        </div>
    </div>

    @if (count($wishlists) > 0)
        @foreach($wishlists as $key => $wishlist)
            <div class="card mb-3" id="wishlist_{{ $wishlist->id }}">
                <div class="row g-0">
                    <div class="col-md-2">
                        <img src="{{ $wishlist->image }}" class="img-fluid rounded-start" alt="{{$wishlist->product_title }}">
                    </div>
                    <div class="col-md-10 align-self-center">
                        <h6 class="card-title">{{ $wishlist->product_title }}</h6>

                        <a href="{{ route('product', $wishlist->product_id) }}" class="btn btn-outline-primary btn-sm">Start Shopping</a>
                        
                        <a href="javascript:void(0)" class="btn btn-outline-primary btn-sm" onclick="removeFromWishlist({{ $wishlist->id }})" data-toggle="tooltip" data-title="{{ translate('Remove from wishlist') }}" data-placement="top">
                            <i class="la la-trash mr-2 fs-16"></i> Delete
                        </a>
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
        {{ $wishlists->links() }}
    </div>
@endsection


@section('script')
    <script type="text/javascript">
        function removeFromWishlist(id){
            $.post('{{ route('wishlists.remove') }}',{_token:'{{ csrf_token() }}', id:id}, function(data){
                $('#wishlist').html(data);
                $('#wishlist_'+id).hide();
                AIZ.plugins.notify('success', '{{ translate("Item has been renoved from wishlist") }}');
            })
        }
    </script>
@endsection
