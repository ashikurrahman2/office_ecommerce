@extends('backend.layouts.app')

@section('content')
    <div class="aiz-titlebar text-left mt-2 mb-3">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h1 class="h3">{{ translate('All Shipping Categories') }}</h1>
            </div>
            @can('add_product_category')
                <div class="col-md-6 text-md-right">
                    <a href="{{ route('shipping_categories.create') }}" class="btn btn-circle btn-info">
                        <span>{{ translate('Add New category') }}</span>
                    </a>
                </div>
            @endcan
        </div>
    </div>
    


    <div class="card">
        <div class="card-header d-block d-md-flex">
            <h5 class="mb-0 h6">{{ translate('Categories') }}</h5>
            <form class="" id="sort_categories" action="" method="GET">
                <div class="box-inline pad-rgt pull-left">
                    <div class="" style="min-width: 200px;">
                        <input type="text" class="form-control" id="search"
                            name="search"@isset($sort_search) value="{{ $sort_search }}" @endisset
                            placeholder="{{ translate('Type name & Enter') }}">
                    </div>
                </div>
            </form>
        </div>
        <div class="card-body">
            <table class="table aiz-table mb-0">
                <thead>
                    <tr>
                         <th>{{ translate('Category') }}</th>
                        <th>{{ translate('Parent Category') }}</th>
                       
                        <th>{{ translate('Air Cost') }}</th>
                        <th>{{ translate('Air Delivery Time') }}</th>
                        <th>{{ translate('Ship Cost') }}</th>
                        <th>{{ translate('Ship Delivery Time') }}</th>
                        <th>{{ translate('Origin') }}</th>
                        <th>{{ translate('Action') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($categories as $category)
                        <tr>
                           <td>
                                {{ ucwords($category->name) }}
                            </td>
                            <td>
                                @php
                                    $parent = \App\Models\ShippingCategory::where('id', $category->parent_id)->first();
                                @endphp
                                @if ($parent != null)
                                    {{ ucwords($parent->name) }}
                                @endif
                            </td>
                            
                            <td>{{ $category->air_cost }}</td>
                            <td>{{ $category->air_delivery_time }}</td>
                            <td>{{ $category->ship_cost }}</td>
                            <td>{{ $category->ship_delivery_time }}</td>
                            <td>{{ $category->origin }}</td>
                            <td>
                                @can('edit_product_category')
                                    <a class="btn btn-soft-primary btn-icon btn-circle btn-sm"
                                        href="{{ route('shipping_categories.edit',  ['id' => $category->id, 'page' => request()->query('page', 1)]) }}" title="{{ translate('Edit') }}">
                                        <i class="las la-edit"></i>
                                    </a>
                                @endcan
                                @can('delete_product_category')
                                    <a href="#" class="btn btn-soft-danger btn-icon btn-circle btn-sm confirm-delete"
                                        data-href="{{ route('shipping_categories.destroy', $category->id) }}"
                                        title="{{ translate('Delete') }}">
                                        <i class="las la-trash"></i>
                                    </a>
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
@endsection

{{-- 
@section('script')
    <script type="text/javascript">
        function update_featured(el) {
            if (el.checked) {
                var status = 1;
            } else {
                var status = 0;
            }
            $.post('{{ route('categories.featured') }}', {
                _token: '{{ csrf_token() }}',
                id: el.value,
                status: status
            }, function(data) {
                if (data == 1) {
                    AIZ.plugins.notify('success', '{{ translate('Featured categories updated successfully') }}');
                } else {
                    AIZ.plugins.notify('danger', '{{ translate('Something went wrong') }}');
                }
            });
        }
    </script>
@endsection --}}
