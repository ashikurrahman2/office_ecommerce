@extends('backend.layouts.app')

@section('content')
<style>
    .dropdown-toggle::after {
        display: none;
    }
</style>

<div class="card">
    <div class="card-header row gutters-5">
        <div class="col">
            <h5 class="mb-md-0 h6">{{ translate('Edit Payment Method') }}</h5>
        </div>
    </div>

    <div class="card-body">
        <form action="{{ route('payment-methods.update', $paymentMethod->id) }}" method="POST" enctype="multipart/form-data">
            @csrf @method('PUT')

            <div class="form-group">
                <label>{{ translate('Type') }}</label>
                <select name="type" class="form-control" onchange="toggleFields(this.value)">
                    <option value="bank" {{ $paymentMethod->type == 'bank' ? 'selected' : '' }}>Bank</option>
                    <option value="mobile" {{ $paymentMethod->type == 'mobile' ? 'selected' : '' }}>Mobile</option>
                </select>
            </div>
                <div class="form-group">
                    <label>{{ translate('Name') }}</label>
                    <input type="text" name="bank_name" class="form-control" value="{{ $paymentMethod->bank_name }}">
                </div>
            <div id="bank-fields">
               
                <div class="form-group">
                    <label>{{ translate('Account Name') }}</label>
                    <input type="text" name="account_name" class="form-control" value="{{ $paymentMethod->account_name }}">
                </div>
                <div class="form-group">
                    <label>{{ translate('Branch') }}</label>
                    <input type="text" name="branch" class="form-control" value="{{ $paymentMethod->branch }}">
                </div>
                <div class="form-group">
                    <label>{{ translate('Routing No') }}</label>
                    <input type="text" name="routing_no" class="form-control" value="{{ $paymentMethod->routing_no }}">
                </div>
            </div>

            <div class="form-group">
                <label>{{ translate('Account Number') }}</label>
                <input type="text" name="account_number" class="form-control" value="{{ $paymentMethod->account_number }}">
            </div>

            <div class="form-group">
                <label>{{ translate('Image') }}</label>
               <div class="input-group" data-toggle="aizuploader" data-type="image">
                        <div class="input-group-prepend">
                            <div class="input-group-text bg-soft-secondary font-weight-medium">{{ translate('Browse')}}</div>
                        </div>
                        <div class="form-control file-amount">{{ translate('Choose File') }}</div>
                        <input type="hidden" name="image_path" class="selected-files" value="{{  $paymentMethod->image_path }}">
                    </div>
                    <div class="file-preview box sm">
                    </div>
            </div>

            <button type="submit" class="btn btn-success">{{ translate('Update') }}</button>
        </form>
    </div>
</div>

<script>
    function toggleFields(value) {
        const bankFields = document.getElementById('bank-fields');
        bankFields.style.display = value === 'mobile' ? 'none' : 'block';
    }
    document.addEventListener('DOMContentLoaded', function() {
        toggleFields('{{ $paymentMethod->type }}');
    });
</script>
@endsection
