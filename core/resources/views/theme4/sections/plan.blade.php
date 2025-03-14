@php
$content = content('plan.content');
$plans = App\Models\Plan::where('status', 1)
->latest()
->get();
@endphp

<section class="plan-section sp_pt_120 sp_pb_120 sp_separator_bg">
    <div class="plan-section-el">
        <img src="{{ getFile('elements', 'el-1.png') }}" alt="image">
    </div>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-7 text-center">
                <div class="sp_site_header  wow fadeInUp" data-wow-duration="0.3s" data-wow-delay="0.3s">
                    <h2 class="sp_site_title">{{ __(@$content->data->title) }}</h2>
                </div>
            </div>
        </div>

        <div class="row gy-4 items-wrapper justify-content-center">
            @forelse ($plans as $plan)
            @php
            $plan_exist = App\Models\Payment::where('plan_id', $plan->id)
            ->where('user_id', Auth::id())
            ->where('next_payment_date', '!=', null)
            ->where('payment_status', 1)
            ->count();
            @endphp

            <div class="col-xl-4 col-md-6">
                <div class="plan-item">
                    <div class="plan-name-area">
                        <h3 class="plan-name mb-2" style="font-size: 25px;">{{ $plan->plan_name }}</h3>
                        <span class="plan-status">{{ __('Every') }} {{ $plan->time->name }}</span>
                    </div>
                    <div class="plan-fatures">
                        <ul class="plan-list">
                            @if ($plan->amount_type == 0)
                            <li>
                                <span class="caption">{{ __('Minimum') }} </span>
                                <span class="details"> {{ number_format($plan->minimum_amount, 2) . ' ' . @$general->site_currency }}</span>
                            </li>
                            <li>
                                <span class="caption">{{ __('Maximum') }} </span>
                                <span class="details"> {{ number_format($plan->maximum_amount, 2) . ' ' . @$general->site_currency }}</span>
                            </li>
                            @else
                            <li>
                                <span class="caption">{{ __('Amount') }} </span>
                                <span class="details"> {{ number_format($plan->amount, 2) . ' ' . @$general->site_currency }}</span>
                            </li>
                            @endif

                            @if ($plan->return_for == 1)
                            <li>
                                <span class="caption">{{ __('For') }} </span>
                                <span class="details"> {{ $plan->how_many_time }} {{ __('Times Accumulated Profit') }}</span>
                            </li>
                            @else
                            <li>
                                <span class="caption">{{ __('For') }} </span>
                                <span class="details"> {{ __('Lifetime') }}</span>
                            </li>
                            @endif

                            @if ($plan->capital_back == 1)
                            <li>
                                <span class="caption">{{ __('Capital Back') }} </span>
                                <span class="details"> {{ __('YES') }}</span>
                            </li>
                            @else
                            <li>
                                <span class="caption">{{ __('Capital Back') }} </span>
                                <span class="details"> {{ __('NO') }}</span>
                            </li>
                            @endif
                        </ul>

                        <div class="plan-rio">
                            <h6>{{ __('Profit') }}</h6>
                            <p class="plan-amount">
                                {{ number_format($plan->return_interest, 2) }} @if ($plan->interest_status == 'percentage')
                                {{ '%' }}
                                @else
                                {{ @$general->site_currency }}
                                @endif
                            </p>
                        </div>

                        <h6 class="mt-4 mb-3">{{ __('Affiliate Bonus') }}</h6>
                        <ul class="plan-referral">
                            @if($plan->referrals)
                                @foreach ($plan->referrals->level as $key => $value)
                                    <div class="single-referral">
                                        <span>{{$plan->referrals->commision[$key]}} %</span>
                                        <p>{{$value}}</p>
                                    </div>
                                @endforeach
                            @endif
                        </ul>

                    </div>
                    <div class="plan-action">
    @if ($plan->lock == 1)
        <a class="main-btn plan-btn w-100 disabled" href="#">
            <span>{{ __('Plan Locked') }}</span>
        </a>
    @elseif ($plan_exist >= $plan->invest_limit)
        <a class="main-btn plan-btn w-100 disabled" href="#">
            <span>{{ __('Max Limit Exceeded') }}</span>
        </a>
    @else
        <a class="main-btn plan-btn w-100" href="{{ route('user.gateways', $plan->id) }}">
            <span>{{ __('Invest Now') }}</span>
        </a>
        @auth
            <button class="main-btn2 bg-white sp_text_dark balance w-100 mt-2" data-plan="{{ $plan }}" data-url="">
                <span>{{ __('Invest Using Balance') }}</span>
            </button>
        @endauth
    @endif
</div>

                </div>
            </div>
            @empty
            @endforelse
        </div>
    </div>
</section>

<div class="calculate-area">
    <div class="calculator"><img src="{{ getFile('elements', 'calculator.png') }}" alt="image"></div>
    <div class="container">
        <div class="row gy-4 align-items-end">
            <div class="col-lg-4 col-md-6">
                <label class="mbl-h text-white">{{ __('Amount') }}</label>
                <input type="text" class="form-control" name="amount" id="amount" placeholder="{{ __('Enter amount') }}">
            </div>
            <div class="col-lg-5 col-md-6">
                <label class="mbl-h text-white">{{ __('Investment Plan') }}</label>
                <select class="select" name="selectplan" id="plan">
                    <option selected disabled class="sp_text_secondary">{{ __('Select a plan') }}</option>
                    @forelse ($plans as $item)
                    <option value="{{ $item->id }}">{{ $item->plan_name }}</option>
                    @empty
                    @endforelse
                </select>
            </div>
            <div class="col-lg-3">
                <a href="#0" id="calculate-btn" class="main-btn w-100"> <span>{{ __('Calculate Earning') }}</span></a>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="invest" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form action="{{route('user.investmentplan.submit')}}" method="post">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{__('Invest Now')}}</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="container-fluid">
                        <div class="form-group">
                            <label for="">{{ __('Invest Amount') }}</label>
                            <input type="text" name="amount" class="form-control">
                            <input type="hidden" name="plan_id" class="form-control">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{__('Close')}}</button>
                    <button type="submit" class="btn main-btn"><span>{{__('Invest Now')}}</span></button>
                </div>
            </div>
        </form>
    </div>
</div>

@push('script')
<script>
    $(function() {
        'use strict'

        $('.balance').on('click', function() {
            const modal = $('#invest');
            modal.find('input[name=plan_id]').val($(this).data('plan').id);
            modal.modal('show')
        })
    })
</script>
@endpush