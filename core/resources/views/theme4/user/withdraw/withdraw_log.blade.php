@extends(template() . 'layout.master2')

@push('style')
    <style>
        .activebtn {
            background-color: #ffc451;
        }
    </style>
@endpush

@section('content2')
    <div class="dashboard-body-part">

        <div class="mobile-page-header">
            <h5 class="title">{{ __('Withdraw History') }}</h5>
            <a href="{{ route('user.dashboard') }}" class="back-btn"><i class="bi bi-arrow-left"></i> {{ __('Back') }}</a>
        </div>

        <div class="site-card">
            <div class="card-header d-flex flex-wrap align-items-center justify-content-between">
                <h5 class="mb-sm-0 mb-2">{{ __('Withdraw Log') }}</h5>
                <form action="" method="get">
                    <div class="row g-3">
                        <div class="col-auto">
                            <input type="text" name="trx" class="form-control form-control-sm"
                                placeholder="transaction id">
                        </div>
                        <div class="col-auto">
                            <input type="date" class="form-control form-control-sm" placeholder="Search User"
                                name="date">
                        </div>
                        <div class="col-auto">
                            <button type="submit" class="btn main-btn btn-sm">{{ __('Search') }}</button>
                        </div>
                    </div>
                </form>
            </div>
            <div class="card-header">
                <div class="text-center mb-4">
                    <div class="tab-btn-group">
                        <a class="tab-btn {{ Request::routeIs('user.withdraw.all') ? 'active' : '' }}"
                            href="{{ route('user.withdraw.all') }}">{{ __('All') }} <span
                                class="d-sm-inline-block d-none">{{ __('Withdraw') }}</span></a>

                        <a class="tab-btn {{ Request::routeIs('user.withdraw.pending') ? 'active' : '' }}"
                            href="{{ route('user.withdraw.pending') }}">{{ __('Pending') }} <span
                                class="d-sm-inline-block d-none">{{ __('Withdraw') }}</span></a>

                        <a class="tab-btn {{ Request::routeIs('user.withdraw.complete') ? 'active' : '' }}"
                            href="{{ route('user.withdraw.complete') }}">{{ __('Complete') }} <span
                                class="d-sm-inline-block d-none">{{ __('Withdraw') }}</span></a>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table site-table">
                        <thead>
                            <tr>
                                <th>{{ __('TRX') }}</th>
                                <th>{{ __('Date') }}</th>
                                <th>{{ __('Method Name') }}</th>
                                <th>{{ __('Withdraw Amount') }}</th>
                                <th>{{ __('Getable Amount') }}</th>
                                <th>{{ __('Charge Type') }}</th>
                                <th>{{ __('Charge') }}</th>
                                <th>{{ __('status') }}</th>
                                <th>{{ __('Action') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($withdrawlogs as $key => $withdrawlog)
                                <tr>
                                    <td data-caption="{{ __('Sl') }}">{{ $key + $withdrawlogs->firstItem() }}</td>
                                    <td data-caption="{{ __('Date') }}">
                                        {{ __($withdrawlog->created_at->format('d F Y')) }}</td>
                                    <td data-caption="{{ __('Method Name') }}">
                                        {{ __($withdrawlog->withdrawMethod->name) }}</td>
                                    <td data-caption="{{ __('Getable Amount') }}">
                                        {{ $general->currency_icon .
                                            '  ' .
                                            $withdrawlog->withdraw_amount +
                                            ($withdrawlog->withdrawMethod->charge_type === 'percent'
                                                ? ($withdrawlog->withdraw_amount * $withdrawlog->withdraw_charge) / 100
                                                : $withdrawlog->withdraw_amount) }}
                                    </td>
                                    <td>


                                        {{ $withdrawlog->withdraw_amount }}

                                    </td>
                                    <td data-caption="{{ __('Charge Type') }}">
                                        {{ ucwords($withdrawlog->withdrawMethod->charge_type) }}
                                    </td>
                                    <td data-caption="{{ __('Charge') }}">
                                        @if ($withdrawlog->withdrawMethod->charge_type == 'percent')
                                            {{ ($withdrawlog->withdraw_amount * $withdrawlog->withdraw_charge) / 100 . ' ' . $general->site_currency }}
                                        @else
                                            {{ number_format($withdrawlog->withdraw_charge, 2) . ' ' . @$general->site_currency }}
                                        @endif
                                    </td>
                                    <td data-caption="{{ __('status') }}">
                                        @if ($withdrawlog->status == 1)
                                            <span class="sp_badge sp_badge_success">{{ __('Success') }}</span>
                                        @elseif($withdrawlog->status == 2)
                                            <span class="sp_badge sp_badge_danger">{{ __('Rejected') }}</span>
                                        @else
                                            <span class="sp_badge sp_badge_warning">{{ __('Pending') }}</span>
                                        @endif
                                    </td>
                                    <td data-caption="{{ __('Action') }}">
                                        <button class="sp_view_btn details"
                                            data-user_data="{{ json_encode($withdrawlog->user_withdraw_prof) }}"
                                            data-withdraw="{{ $withdrawlog }}"><i class="far fa-eye"></i></button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td data-caption="{{ __('Status') }}" class="text-center" colspan="100%">
                                        {{ __('No Data Found') }}
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if ($withdrawlogs->hasPages())
                    {{ $withdrawlogs->links() }}
                @endif
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="details" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ __('Withdraw Details') }}</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true" class="text-white">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="withdraw-details">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm sp_btn_danger"
                        data-bs-dismiss="modal">{{ __('Close') }}</button>
                </div>
            </div>
        </div>
    </div>
@endsection


@push('script')
    <script>
        $(function() {
            'use strict'

            $('.details').on('click', function() {
                const modal = $('#details');

                let html = `

                    <ul class="list-group">
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                               {{ __('Withdraw Email') }}
                                <span>${$(this).data('user_data').email}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                {{ __('Withdraw Account Information') }}
                                <span>${$(this).data('user_data').account_information}</span>
                            </li>


                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                {{ __('Note For Withdraw') }}
                                <span>${$(this).data('user_data').note}</span>
                            </li>

                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                {{ __('Withdraw Transaction') }}
                                <span>${$(this).data('withdraw').transaction_id}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                {{ __('Reason Of Reject') }}
<span>${$(this).data('withdraw')?.reason_of_reject || 'None'}</span>
                            </li>
                        </ul>
                `;

                modal.find('.withdraw-details').html(html);

                modal.modal('show');
            })

        })
    </script>
@endpush
