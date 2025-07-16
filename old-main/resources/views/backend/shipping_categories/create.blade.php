@extends('backend.layouts.app')

@section('content')
<div class="row">
    <div class="col-lg-8 mx-auto">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0 h6">{{translate('Create Category For Shipping')}}</h5>
            </div>
            <div class="card-body">
                <form class="form-horizontal" action="{{ route('shipping_categories.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                

                    <div class="form-group row">
                        <label class="col-md-3 col-form-label">{{translate('Parent Category')}}</label>
                        <div class="col-md-9">
                            <select class="select2 form-control aiz-selectpicker" name="parent_id" data-toggle="select2" data-placeholder="Choose ..." data-live-search="true">
                                <option value="0">{{ translate('No Parent') }}</option>
                        
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name}}</option>
                                    {{-- @foreach ($category->childrenCategories as $childCategory)
                                        @include('categories.child_category', ['child_category' => $childCategory])
                                    @endforeach --}}
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-3 col-form-label">{{translate('Name')}}</label>
                        <div class="col-md-9">
                            <input type="text" placeholder="{{translate('Name')}}" name="name" class="form-control" required>
                        </div>
                    </div>
                
                
                    <!-- Air Shipping Details -->
                    <div id="airShipping" class="shipping-details">
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label">{{translate('Cost (Air)/ KG')}}</label>
                            <div class="col-md-9">
                                <input type="text" placeholder="0.00" name="air_cost" class="form-control" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label">{{translate('Approximate Delivery Time (Air)')}}</label>
                            <div class="col-md-9">
                                <select id="airDeliveryTime" name="air_delivery_time" required class="form-control aiz-selectpicker mb-2 mb-md-0">
                                    <option value="10 - 15">10 - 15 days</option>
                                    <option value="15 - 20">15 - 20 days</option>
                                    <option value="20 - 25">20 - 25 days</option>
                                    <option value="25 - 30">25 - 30 days</option>
                                    <option value="30 - 35">30 - 35 days</option>
                                    <option value="35 - 40">35 - 40 days</option>
                                    <option value="40 - 45">40 - 45 days</option>
                                    <option value="45 - 50">45 - 50 days</option>
                                    <option value="50 - 55">50 - 55 days</option>
                                    <option value="55 - 60">55 - 60 days</option>
                                    <option value="60 - 65">60 - 65 days</option>
                                    <option value="65 - 70">65 - 70 days</option>
                                    <option value="70 - 75">70 - 75 days</option>
                                    <option value="75 - 80">75 - 80 days</option>
                                    <option value="80 - 85">80 - 85 days</option>
                                    <option value="85 - 90">85 - 90 days</option>
                                    <option value="90 - 100">90 - 100 days</option>
                                </select>
                            </div>
                        </div>
                    </div>
                
                    <!-- Ship Shipping Details -->
                    <div id="shipShipping" class="shipping-details">
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label">{{translate('Cost (Ship)/ KG')}}</label>
                            <div class="col-md-9">
                                <input type="text" placeholder="0.00" name="ship_cost" class="form-control" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label">{{translate('Approximate Delivery Time (Ship)')}}</label>
                            <div class="col-md-9">
                                <select id="shipDeliveryTime" name="ship_delivery_time" required class="form-control aiz-selectpicker mb-2 mb-md-0">
                                    <option value="10 - 15">10 - 15 days</option>
                                    <option value="15 - 20">15 - 20 days</option>
                                    <option value="20 - 25">20 - 25 days</option>
                                    <option value="25 - 30">25 - 30 days</option>
                                    <option value="30 - 35">30 - 35 days</option>
                                    <option value="35 - 40">35 - 40 days</option>
                                    <option value="40 - 45">40 - 45 days</option>
                                    <option value="45 - 50">45 - 50 days</option>
                                    <option value="50 - 55">50 - 55 days</option>
                                    <option value="55 - 60">55 - 60 days</option>
                                    <option value="60 - 65">60 - 65 days</option>
                                    <option value="65 - 70">65 - 70 days</option>
                                    <option value="70 - 75">70 - 75 days</option>
                                    <option value="75 - 80">75 - 80 days</option>
                                    <option value="80 - 85">80 - 85 days</option>
                                    <option value="85 - 90">85 - 90 days</option>
                                    <option value="90 - 100">90 - 100 days</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-md-3 col-form-label">{{translate('Origin')}}</label>
                            <div class="col-md-9">
                                <select class="form-control aiz-selectpicker mb-2 mb-md-0" required name="origin">
                                    <option disabled="">Select Origin</option>
                                    <option value="CN">China</option>
                                    <option value="US">USA</option>
                                    <option value="AE">UAE</option>
                                    <option value="IN">India</option>
                                </select>
                            </div>
                        </div>
                    </div>
                
                    <div class="form-group mb-0 text-right">
                        <button type="submit" class="btn btn-primary">{{translate('Save')}}</button>
                    </div>
                </form>
                
            </div>
        </div>
    </div>
</div>

@endsection

@section('script')
@endsection
