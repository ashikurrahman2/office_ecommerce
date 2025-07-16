@extends('frontend.layouts.user_panel')

@section('panel_content')
    <!-- Wallet Recharge History -->
    <div class="card rounded-0 shadow-none border">
        <div class="card-header border-bottom-0">
            <h5 class="mb-0 fs-20 fw-700 text-dark text-center text-md-left">{{ translate('Wallet recharge history') }}</h5>
        </div>
        <div class="card-body py-0">
            <table class="table aiz-table mb-4">
                <thead class="text-gray fs-12">
                    <tr>
                        <th class="pl-0">#</th>
                        <th data-breakpoints="lg">{{ translate('Date') }}</th>
                        <th>{{ translate('Amount') }}</th>
                        <th data-breakpoints="lg">{{ translate('Payment Method') }}</th>
                        <th class="text-right pr-0">{{ translate('Status') }}</th>
                    </tr>
                </thead>
                <tbody class="fs-14">
                    @foreach ($wallets as $key => $wallet)
                        <tr>
                            <td class="pl-0">{{ sprintf('%02d', ($key+1)) }}</td>
                            <td>{{ date('d-m-Y', strtotime($wallet->created_at)) }}</td>
                            <td class="fw-700">{{ single_price($wallet->amount) }}</td>
                            <td>{{ ucfirst(str_replace('_', ' ', $wallet->payment_method)) }}</td>
                            <td class="text-right pr-0">
                                @if ($wallet->offline_payment)
                                    @if ($wallet->approval)
                                        <span class="badge badge-inline badge-success p-3 fs-12" style="border-radius: 25px; min-width: 80px !important;">{{ translate('Approved') }}</span>
                                    @else
                                        <span class="badge badge-inline badge-info p-3 fs-12" style="border-radius: 25px; min-width: 80px !important;">{{ translate('Pending') }}</span>
                                    @endif
                                @else
                                    N/A
                                @endif
                            </td>
                        </tr>
                    @endforeach

                </tbody>
            </table>
            <!-- Pagination -->
            <div class="aiz-pagination mb-4">
                {{ $wallets->links() }}
            </div>
        </div>
    </div>
@endsection

@section('modal')
    <!-- Wallet Recharge Modal -->
    @include('frontend.'.get_setting('homepage_select').'.partials.wallet_modal')
    
    <!-- Offline Wallet Recharge Modal -->
    <div class="modal fade" id="offline_wallet_recharge_modal" tabindex="-1" role="dialog"
        aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">{{ translate('Offline Recharge Wallet') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
                </div>
                <div id="offline_wallet_recharge_modal_body"></div>
            </div>
        </div>
    </div>

@endsection

@section('script')
    <script type="text/javascript">
        function show_wallet_modal() {
            $('#wallet_modal').modal('show');
        }

        function show_make_wallet_recharge_modal() {
            $.post('{{ route('offline_wallet_recharge_modal') }}', {
                _token: '{{ csrf_token() }}'
            }, function(data) {
                $('#offline_wallet_recharge_modal_body').html(data);
                $('#offline_wallet_recharge_modal').modal('show');
            });
        }
    </script>
@endsection
