@extends('backend.layouts.app')

@section('content')

    <div class="row">
        <div class="col-lg-8 mx-auto">
            <div class="card">
    <div class="card-header">
        <h5 class="mb-0">{{ translate('Currency & Profit Settings') }}</h5>
    </div>

    <form action="{{ route('settings.currency_profit.update') }}" method="POST">
        @csrf
        <div class="card-body">
            <div class="form-group mb-3">
                <label>{{ translate('RMB Rate (CNY ➜ BDT)') }}</label>
                <input type="number" step="0.0001" name="rmb_rate" value="{{ old('rmb_rate', $rmbRate) }}"
                       class="form-control" required>
                <small class="text-muted">{{ translate('Example: 1 CNY = 12.50 BDT ⇒ enter 12.50') }}</small>
            </div>

            <div class="form-group mb-3">
                <label>{{ translate('Default Profit Percentage (%)') }}</label>
                <input type="number" step="0.01" name="profit_percent" value="{{ old('profit_percent', $profitPercent) }}"
                       class="form-control" required>
                <small class="text-muted">{{ translate('Example: enter 15 for 15%') }}</small>
            </div>
        </div>

        <div class="card-footer text-right">
            <button class="btn btn-primary" type="submit">
                {{ translate('Save Settings') }}
            </button>
        </div>
    </form>
</div>
        </div>
    </div>

@endsection
