@extends('backend.layouts.app')

@section('content')

<div class="aiz-titlebar text-left mt-2 mb-3">
    {{-- <div class="row align-items-center">
        <div class="col-md-6">
            <h1 class="h3">{{translate('All Categories')}}</h1>
        </div>
        @can('add_product_category')
            <div class="col-md-6 text-md-right">
                <a href="{{ route('categories.create') }}" class="btn btn-circle btn-info">
                    <span>{{translate('Add New category')}}</span>
                </a>
            </div>
        @endcan
    </div> --}}
</div>
<div class="card">
    <div class="card-header d-block d-md-flex">
        <h5 class="mb-0 h6">{{ translate('Categories') }}</h5>
        <form class="" id="sort_categories" action="" method="GET">
            <div class="box-inline pad-rgt pull-left">
                <div class="" style="min-width: 200px;">
                    <input type="text" class="form-control" id="search" name="search"@isset($sort_search) value="{{ $sort_search }}" @endisset placeholder="{{ translate('Type name & Enter') }}">
                </div>
            </div>
        </form>
    </div>
    <div class="card-body">
        <table class="table aiz-table mb-0">
            <thead>
                <tr>
                    <th>#</th>
                    <th>{{translate('Image')}}</th>
                    <th>{{translate('Name')}}</th>
                    <th>{{ translate('Parent Category') }}</th>
                    <th>{{translate('Top')}}</th>
                    <th>{{translate('Show')}}</th>
                    <th width="10%" class="text-right">{{translate('Options')}}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($categories as $key => $category)
             
                    <tr>
                        <td>{{ ($key+1) + ($categories->currentPage() - 1)*$categories->perPage() }}</td>
                        <td>
                            @if($category->banner != null)
                                <img src="{{ uploaded_asset($category->banner) }}" alt="{{translate('Banner')}}" class="h-50px">
                            @else
                                —
                            @endif
                        </td>
                        <td>
                            {{ $category->CustomName }}
                        </td>
                        <td>
                            {{ $category->parentCategory ? $category->parentCategory->CustomName : translate('N/A') }}
                        </td>
                        
                        <td>
                            @if($category->ParentId == NULL && $category->IsShow ==1 )
                            <label class="aiz-switch aiz-switch-success mb-0">
                                <input type="checkbox" onchange="top_category(this)" data-categoryId="{{$category->CategoryId}}" data-id="{{ $category->id }}" @if($category->is_top == 1) checked @endif>
                                <span></span>
                            </label>
                            @else
                            -
                            @endif
                        </td>
                        <td>
                            <label class="aiz-switch aiz-switch-success mb-0">
                                <input type="checkbox" onchange="status_change(this)" data-categoryId="{{$category->CategoryId}}" data-id="{{ $category->id }}" @if($category->IsShow == 1) checked @endif>
                                <span></span>
                            </label>
                        </td>
                        <td class="text-right">
                            @can('edit_product_category')
                            <!-- Edit Category Button -->
                           <!-- Edit Button -->
                            <a class="btn btn-soft-primary btn-icon btn-circle btn-sm"
                            href="{{ route('categories.edit', ['id' => $category->id, 'lang' => env('DEFAULT_LANGUAGE')]) }}"
                            title="{{ translate('Edit') }}">
                            <i class="las la-edit"></i>
                            </a>

                            <!-- Add/Edit Shipping Cost Button -->
                            <button type="button"
                                class="btn btn-soft-primary btn-icon btn-circle btn-sm"
                                data-toggle="modal"
                                data-target="#shippingCostModal-{{ $category->id }}"
                                title="{{ translate('Add/Edit Shipping Cost') }}">
                            <i class="las la-shipping-fast"></i>
                            </button>

                            <div class="modal fade" id="shippingCostModal-{{ $category->id }}" tabindex="-1" role="dialog" aria-labelledby="shippingCostModalLabel" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <form method="POST" action="{{ route('categories.shipping_cost_store', $category->id) }}">
                                        @csrf
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Shipping Cost for {{ $category->name }}</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                            
                                            @php
                                                $cost = \App\Models\CategoryShippingCost::where('category_id', $category->id)->first();
                                            @endphp
                            
                                            <div class="modal-body">
                                                <div class="row">
                                                    <div class="col-md-6 mb-3">
                                                        <label for="air_cost">Air Cost</label>
                                                        <input type="number" step="0.01" name="air_cost" id="air_cost" class="form-control" value="{{ old('air_cost', $cost->air_cost ?? '') }}" required>
                                                    </div>
                            
                                                    <div class="col-md-6 mb-3">
                                                        <label for="airDeliveryTime">Air Delivery Time</label>
                                                        <select id="airDeliveryTime" name="air_delivery_time" required class="form-control ">
                                                            @foreach (['10 - 15','15 - 20','20 - 25','25 - 30','30 - 35','35 - 40','40 - 45','45 - 50','50 - 55','55 - 60','60 - 65','65 - 70','70 - 75','75 - 80','80 - 85','85 - 90','90 - 100'] as $range)
                                                                <option value="{{ $range }}" @selected(($cost->air_delivery_time ?? '') == $range)>{{ $range }} days</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                            
                                                    <div class="col-md-6 mb-3">
                                                        <label for="ship_cost">Ship Cost</label>
                                                        <input type="number" step="0.01" name="ship_cost" id="ship_cost" class="form-control" value="{{ old('ship_cost', $cost->ship_cost ?? '') }}" required>
                                                    </div>
                            
                                                    <div class="col-md-6 mb-3">
                                                        <label for="shipDeliveryTime">Ship Delivery Time</label>
                                                        <select id="shipDeliveryTime" name="ship_delivery_time" required class="form-control ">
                                                            @foreach (['10 - 15','15 - 20','20 - 25','25 - 30','30 - 35','35 - 40','40 - 45','45 - 50','50 - 55','55 - 60','60 - 65','65 - 70','70 - 75','75 - 80','80 - 85','85 - 90','90 - 100'] as $range)
                                                                <option value="{{ $range }}" @selected(($cost->ship_delivery_time ?? '') == $range)>{{ $range }} days</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                            
                                                    <div class="col-md-12 mb-3">
                                                        <label for="origin">Origin</label>
                                                        <select class="form-control " required name="origin" id="origin">
                                                            <option value="CN" @selected(($cost->origin ?? '') == 'CN')>China</option>
                                                            <option value="US" @selected(($cost->origin ?? '') == 'US')>USA</option>
                                                            <option value="AE" @selected(($cost->origin ?? '') == 'AE')>UAE</option>
                                                            <option value="IN" @selected(($cost->origin ?? '') == 'IN')>India</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                            
                                            <div class="modal-footer">
                                                <button type="submit" class="btn btn-success">Save</button>
                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            
                        @endcan
                        
                            @can('delete_product_category')
                                {{-- <a href="javascript:void(0)" class="btn btn-soft-danger btn-icon btn-circle btn-sm confirm-delete" data-href="{{route('categories.destroy', $category->id)}}" title="{{ translate('Delete') }}">
                                    <i class="las la-trash"></i>
                                </a> --}}
                            @endcan
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div class="aiz-pagination">
            {{ $categories->appends(request()->input())->links() }}
        </div>
    </div>
</div>

@endsection


@section('modal')
    @include('modals.delete_modal')
    <!-- Shipping Cost Modal -->


@endsection


@section('script')
    <script>
        function status_change(element) {
            const categoryId = $(element).data('categoryid');
            const id = $(element).data('id');
            const status = element.checked ? 1 : 0;
            
            $.ajax({
                url: "{{ route('categories.status_change') }}",
                type: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    id: id,
                    categoryId: categoryId,
                    status: status
                },
                success: function(response) {
                    if(response.success){
                        AIZ.plugins.notify('success', '{{ translate('Status updated successfully') }}');
                        location.reload();
                    }
                    else{
                        AIZ.plugins.notify('danger', '{{ translate('Something went wrong') }}');
                    }
                },
                error: function(xhr) {
                    alert("An error occurred while updating the status.");
                }
            });
        }
        function top_category(element) {
            const categoryId = $(element).data('categoryid');
            const id = $(element).data('id');
            const status = element.checked ? 1 : 0;
            
            $.ajax({
                url: "{{ route('categories.top_category') }}",
                type: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    id: id,
                    categoryId: categoryId,
                    status: status
                },
                success: function(response) {
                    if(response.success){
                        AIZ.plugins.notify('success', '{{ translate('Top category updated successfully') }}');
                        location.reload();
                    }
                    else{
                        AIZ.plugins.notify('danger', '{{ translate('Something went wrong') }}');
                    }
                },
                error: function(xhr) {
                    alert("An error occurred while updating the status.");
                }
            });
        }
    </script>
@endsection
