@extends(template() . 'layout.master')


@section('content')

    @include(template() . 'sections.banner')
    @if ($sections->sections != null)
        @foreach ($sections->sections as $sections)
            @include(template().'sections.' . $sections)
        @endforeach
    @endif

 <!-- Modal -->
    <div class="modal fade" id="infoModal" tabindex="-1" aria-labelledby="infoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="infoModalLabel">Deposit & Withdrawal Info</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="modalBody">
                <!-- Content will be updated dynamically -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        var now = new Date();
        var hours = now.getHours();
        var modalBody = document.getElementById("modalBody");

        if (hours >= 12 && hours < 20) {
            modalBody.innerHTML = `
                <p><strong>1. Deposit Timing:</strong> 09 AM to 10 PM</p>
                <p><strong>2. Withdrawal Timing:</strong> 12 PM to 8 PM</p>
                <p><strong>3. Receive in Your Account:</strong> Within 24 hours.</p>
                <p>4. If any issue, please contact our customer service.</p>
                <p>5. Referral Team Commission:</p>
                <ul>
                    <li>Level 1: 8%</li>
                    <li>Level 2: 5%</li>
                    <li>Level 3: 3%</li>
                </ul>
                <p><strong>6. Team Promotion:</strong> If anyone reaches 30 valid members in level one, he will become a regular employee of the company and will get a monthly salary based on his work.</p>
                <br>
                <p><strong>Join our WhatsApp group for exclusive updates, insights, and discussions on our project
                <a href="https://chat.whatsapp.com/DqO2GEjRnNR48GkzaBpXeU">Click Here</a></strong></p>
            `;
        } else {
            modalBody.innerHTML = `<p><strong>Withdrawals are only available between 12 PM and 8 PM. Please try again during this time.</strong></p>`;
        }

        var infoModal = new bootstrap.Modal(document.getElementById('infoModal'));
        infoModal.show();
    });
</script>


    <!--END MODAL-->
    <div class="modal fade" id="calculationModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">{{ __('Profit calculate') }}</h5>
                    <button type="button" class="close btn sp_btn_warning" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true" class="text-light">&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="profit">


                </div>
            </div>
        </div>
    @endsection


    @push('script')
        <script>
            'use strict';
            $(document).ready(function() {
                $(document).on('click', '#calculate-btn', function() {

                    let modal = $('#calculationModal');

                    $('.selectplan').text('');
                    $('.amount').text('');
                    let id = $('#plan').val();
                    let amount = $('#amount').val();
                    var url = "{{ route('user.investmentcalculate', ':id') }}";
                    url = url.replace(':id', id);
                    $.ajax({
                        type: 'GET',
                        url: url,
                        data: {
                            amount: amount,
                            selectplan: id
                        },
                        success: (data) => {
                            if (data.message) {
                                iziToast.error({
                                    message: data.message + ' ' + Number(data.amount)
                                        .toFixed(2),
                                    position: 'topRight',
                                });

                            } else {
                                $('#profit').html(data);
                                modal.modal('show');
                            }


                        },
                        error: (error) => {
                            if (typeof(error.responseJSON.errors.amount) !== "undefined") {
                                iziToast.error({
                                    message: error.responseJSON.errors.amount,
                                    position: 'topRight',
                                });
                            }
                            if (typeof(error.responseJSON.errors.selectplan) !== "undefined") {
                                iziToast.error({
                                    message: error.responseJSON.errors.selectplan,
                                    position: 'topRight',
                                });
                            }
                        }
                    })
                });



            });
        </script>
    @endpush
