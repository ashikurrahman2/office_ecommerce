@php
    $coupon_details = json_decode($coupon->details);
@endphp
<div class="card-header mb-2">
    <h3 class="h6">{{translate('Edit Your All Product Coupon')}}</h3>
</div>

<div class="form-group row">
    <label class="col-lg-3 col-from-label">{{translate('Discount')}}</label>
    <div class="col-lg-7">
        <input type="number" lang="en" min="0" step="0.01" placeholder="{{translate('Discount')}}" name="discount" class="form-control" value="{{ $coupon->discount }}" required>
    </div>
    <div class="col-lg-2">
        <select class="form-control aiz-selectpicker" name="discount_type">
            <option value="amount" @if ($coupon->discount_type == 'amount') selected  @endif >{{translate('Amount')}}</option>
            <option value="percent" @if ($coupon->discount_type == 'percent') selected  @endif>{{translate('Percent')}}</option>
        </select>
    </div>
</div>
@php
  $start_date = date('m/d/Y', $coupon->start_date);
  $end_date = date('m/d/Y', $coupon->end_date);
@endphp
<div class="form-group row">
    <label class="col-sm-3 control-label" for="start_date">{{translate('Date')}}</label>
    <div class="col-sm-9">
      <input type="text" class="form-control aiz-date-range" value="{{ $start_date .' - '. $end_date }}" name="date_range" placeholder="{{ translate('Select Date') }}">
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function(){
        $('.aiz-selectpicker').selectpicker();
        $('.aiz-date-range').daterangepicker();
    });
</script>
