@extends('backend.layouts.app')

@section('content')
<div class="aiz-titlebar text-left mt-2 mb-3">
    <div class="row align-items-center">
        <div class="col-md-6">
            <h1 class="h3">{{translate('Category Information')}}</h1>
        </div>
        <div class="col-md-6 text-md-right">
            <a href="{{ route('categories.index') }}" class="btn btn-circle btn-info">
                 <span>{{translate('Category List')}}</span>
            </a>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header d-block d-md-flex">
        <h5 class="mb-0 h6">{{ translate('Update Category') }}</h5>
    </div>
    <div class="card-body">
        <form class="p-4" action="{{ route('categories.update', $category->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="form-group row">
                <label class="col-md-3 col-form-label">{{translate('Name')}}</label>
                <div class="col-md-9">
                    <input type="text" name="CustomName" value="{{ $category->CustomName }}" class="form-control" id="name" placeholder="{{translate('Name')}}" required>
                </div>
            </div>
         
            @if($category->ParentId == null)
            <div class="form-group row">
                <label class="col-md-3 col-form-label" for="signinSrEmail">{{translate('Banner')}} <small>({{ translate('200x200') }})</small></label>
                <div class="col-md-9">
                    <div class="input-group" data-toggle="aizuploader" data-type="image">
                        <div class="input-group-prepend">
                            <div class="input-group-text bg-soft-secondary font-weight-medium">{{ translate('Browse')}}</div>
                        </div>
                        <div class="form-control file-amount">{{ translate('Choose File') }}</div>
                        <input type="hidden" name="banner" class="selected-files" value="{{ $category->banner }}">
                    </div>
                    <div class="file-preview box sm">
                    </div>
                </div>
            </div>
            @endif

            @if($category->ParentId == null)
            
            @endif
            
            <div class="form-group mb-0 text-right">
                <button type="submit" class="btn btn-primary">{{translate('Save')}}</button>
            </div>
        </form>
 
    </div>
</div>


@endsection

@section('script')

<script type="text/javascript">
    function categoriesByType(val){
        $('select[name="parent_id"]').html('');
        AIZ.plugins.bootstrapSelect('refresh');
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type:"POST",
            url:'{{ route('categories.categories-by-type') }}',
            data:{
               digital: val
            },
            success: function(data) {
                $('select[name="parent_id"]').html(data);
                AIZ.plugins.bootstrapSelect('refresh');
            }
        });
    }
</script>

@endsection
