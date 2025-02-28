@extends(template() . 'layout.master2')

@section('content2')
    <div class="dashboard-body-part">

        <div class="mobile-page-header">
            <h5 class="title">{{ __('Bind Your Account') }}</h5>
            <a href="{{ route('user.dashboard') }}" class="back-btn">
                <i class="bi bi-arrow-left"></i> {{ __('Back') }}
            </a>
        </div>

        <div class="row gy-4">
            <div class="col-xxl-8 col-lg-6">
                <div class="site-card">
                    <form action="{{ url('account/bind') }}" method="post">
                        @csrf
                        <div class="card-header">
                            <h5 class="mb-0">{{ __('Bind Your Account for Withdrawals') }}</h5>
                        </div>
                        <div class="card-body">
                            <div class="form-group">
                                <label for="withdraw_method_id">{{ __('Withdraw Method') }}</label>
                                <select name="withdraw_method_id" id="withdraw_method_id" class="select">
                                    <option value="">{{ __('Select Method') }}</option>
                                    @foreach ($withdrawMethods as $method)
                                        <option value="{{ $method->id }}"
                                            data-url="{{ route('user.withdraw.fetch', $method->id) }}">
                                            {{ $method->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="row appendData"></div>
                        </div>
                    </form>
                </div>
            </div>

            <div class="col-xxl-4 col-lg-6 withdraw-ins">
                <div class="site-card">
                    <div class="card-header">
                        <h5 class="mb-0">{{ __('Method Instructions') }}</h5>
                    </div>
                    <div class="card-body">
                        <p class="instruction">برائے مہربانی اپنا اکاؤنٹ بائنڈ کرتے وقت اپنا اکاؤنٹ نمبر اور اکاؤنٹ ہولڈر کا نام صحیح طریقے سے درج کریں۔ اگر غلطی سے کوئی غلط معلومات درج ہو جائیں تو برائے مہربانی کسٹمر سروس سے رابطہ کرکے اپنا اکاؤنٹ بائنڈ تبدیل کروائیں۔ شکریہ۔</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')

<script>
    $(function() {
        'use strict'

        $('select[name=withdraw_method_id]').on('change', function() {
            if ($(this).val() == '') {
                $('.appendData').addClass('d-none');
                $('.instruction').text('');
                return;
            }
            $('.appendData').removeClass('d-none');
            getData($('select[name=withdraw_method_id] option:selected').data('url'))
        })

        function getData(url) {
            $.ajax({
                url: url,
                method: "GET",
                success: function(response) {
                    $('.instruction').html(response.withdraw_instruction);
                    let html = `
                        <div class="col-md-12 mb-3 mt-3">
                            <label for="email">{{ __('Account Number') }} <span class="text-danger">*</span></label>
                            <input type="text" name="email" class="form-control" required>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label for="account_information">{{ __('Account Title') }}</label>
                            <textarea class="form-control" name="account_information" row="5" required></textarea>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label for="note">{{ __('Additional Note') }}</label>
                            <textarea class="form-control" name="note" row="5"></textarea>
                        </div>
                        <div class="col-md-12">
                            <button class="btn main-btn w-100" type="submit">{{ __('Bind Account') }}</button>
                        </div>
                    `;

                    $('.appendData').html(html);
                }
            })
        }
    });
</script>

@endpush
