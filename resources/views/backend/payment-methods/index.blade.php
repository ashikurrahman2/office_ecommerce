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
            <h5 class="mb-md-0 h6">{{ translate('Payment Methods') }}</h5>
        </div>
        <div class="col text-right">
            <a href="{{ route('payment-methods.create') }}" class="btn btn-primary">{{ translate('Add New') }}</a>
        </div>
    </div>

    <div class="card-body">
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>#</th>
                    <th>{{ translate('Type') }}</th>
                    <th>{{ translate('Bank Name') }}</th>
                    <th>{{ translate('Account Name') }}</th>
                    <th>{{ translate('Account Number') }}</th>
                    <th>{{ translate('Branch') }}</th>
                    <th>{{ translate('Routing No') }}</th>
                    <th>{{ translate('Image') }}</th>
                    <th>{{ translate('Action') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($paymentMethods as $key => $method)
                    <tr>
                        <td>{{ $key + 1 }}</td>
                        <td>{{ ucfirst($method->type) }}</td>
                        <td>{{ $method->bank_name }}</td>
                        <td>{{ $method->account_name }}</td>
                        <td>{{ $method->account_number }}</td>
                        <td>{{ $method->branch }}</td>
                        <td>{{ $method->routing_no }}</td>
                        <td>
                            @if($method->image_path)
                            
                                <img  src="{{ uploaded_asset($method->image_path) ?? static_asset('assets/img/placeholder.jpg') }}" width="50" height="50">
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('payment-methods.edit', $method->id) }}" class="btn btn-sm btn-primary">{{ translate('Edit') }}</a>
                            <form action="{{ route('payment-methods.destroy', $method->id) }}" method="POST" style="display:inline-block;">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-danger" onclick="return confirm('{{ translate('Are you sure?') }}')">{{ translate('Delete') }}</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
