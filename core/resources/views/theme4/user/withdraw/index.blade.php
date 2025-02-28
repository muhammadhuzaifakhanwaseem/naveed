@extends(template() . 'layout.master2')

@section('content2')
    <div class="dashboard-body-part">

        <div class="mobile-page-header">
            <h5 class="title">{{ __('Withdraw') }}</h5>
            <a href="{{ url('user/dashboard') }}" class="back-btn"><i class="bi bi-arrow-left"></i> {{ __('Back') }}</a>
        </div>

        <div class="row gy-4">
            <div class="col-xxl-8 col-lg-6">
                <div class="site-card">
                    <form action="{{ url('withdraw') }}" method="post">
                        @csrf
                        <div class="card-header">
                            <h5 class="mb-0">
                                {{ __('Current Balance: ') }}
                                <span
                                    class="color-change">{{ number_format(auth()->user()->balance, 2) . ' ' . $general->site_currency }}</span>
                            </h5>
                        </div>
                        <div class="card-body">
                            @if (!$accountBind)
                                <div class="alert alert-danger">
                                    {{ __('You need to bind an account before withdrawing.') }}
                                    <a href="{{ url('account/bind') }}"
                                        class="btn btn-sm btn-primary">{{ __('Bind Now') }}</a>
                                </div>
                            @else
                            <div class="form-group">
                                <label for="">{{ __('Withdraw Amount') }}</label>
                                <input type="text" name="amount" class="form-control amount" required>
                                <p class="text-muted small">
                                    Min Amount: <strong>{{ number_format($accountBind->withdrawMethod->min_amount, 0) }}</strong> /
                                    Max Amount: <strong>{{ number_format($accountBind->withdrawMethod->max_amount, 0) }}</strong>
                                </p>
                            </div>


                                <div class="form-group">
                                    <label for="">{{ __('Withdraw Method') }}</label>
                                    <input type="text" class="form-control text-black"
                                        value="{{ $accountBind->withdrawMethod->name }}" disabled>
                                </div>

                                <div class="form-group">
                                    <label>{{ __('Withdraw Charge') }}</label>
                                    <input type="text" class="form-control charge text-black"
                                        value="{{ number_format($accountBind->withdrawMethod->charge, 2) }}" required
                                        disabled>
                                </div>

                                <div class="form-group">
                                    <label for="">{{ __('Final Withdraw Amount') }}</label>
                                    <input type="text" name="final_amo" class="form-control final_amo" required readonly>
                                </div>
                                <div class="form-group">
                                    <label for="">{{ __('Account Number') }}</label>
                                    <input type="text" class="form-control" readonly
                                        value="{{ $accountBind->email }}">
                                </div>
                                <div class="form-group">
                                    <label for="">{{ __('Account Information') }}</label>
                                    <textarea class="form-control" readonly>
                                        {{ $accountBind->account_information }}
                                    </textarea>
                                </div>

                                <button class="btn main-btn w-100 mt-3" type="submit">{{ __('Withdraw Now') }}</button>
                            @endif
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script>
        $(document).ready(function() {
            'use strict';

            $('.amount').on('keyup', function() {
                let withdrawChargeType = "{{ $accountBind->withdrawMethod->charge_type }}";
                let charge = parseFloat("{{ $accountBind->withdrawMethod->charge }}");
                let amount = parseFloat($(this).val());

                if (isNaN(amount) || amount <= 0) {
                    $('.final_amo').val(0);
                    return;
                }

                let totalAmount = (withdrawChargeType === "percent") ?
                    amount + (charge * amount / 100) :
                    amount + charge;

                $('.final_amo').val(totalAmount.toFixed(2));
            });

            // Prevent withdrawal outside allowed hours
            $('form').on('submit', function(e) {
                let now = new Date();
                let currentHour = now.getHours();

                if (currentHour < 12 || currentHour >= 20) {
                    e.preventDefault();
                    alert("Withdrawals are only available between 12 PM and 8 PM.");
                }
            });
        });
    </script>
@endpush
