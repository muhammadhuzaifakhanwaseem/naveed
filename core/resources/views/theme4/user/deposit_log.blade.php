@extends(template() . 'layout.master2')


@section('content2')
    <div class="dashboard-body-part">

        <div class="mobile-page-header">
            <h5 class="title">{{ __('Deposit History') }}</h5>
            <a href="{{ route('user.dashboard') }}" class="back-btn"><i class="bi bi-arrow-left"></i> {{ __('Back') }}</a>
        </div>

        <div class="site-card">
            <div class="card-header d-flex flex-wrap align-items-center justify-content-between">
                <h5 class="mb-sm-0 mb-2">{{ __('Deposit Log') }}</h5>
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

            <div class="card-body">
                <div class="table-responsive">
                    <table class="table site-table">
                        <thead>
                            <tr>
                                <th>{{ __('Trx') }}</th>
                                <th>{{ __('User') }}</th>
                                <th>{{ __('Gateway') }}</th>
                                <th>{{ __('Amount') }}</th>
                                <th>{{ __('Currency') }}</th>
                                <th>{{ __('Charge') }}</th>
                                <th>{{ __('Payment Date') }}</th>
                                <th>{{ __('Proof') }}</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse($transactions as $key => $transaction)
                                <tr>
                                    <td data-caption="{{ __('Trx') }}">{{ $transaction->transaction_id }}</td>
                                    <td data-caption="{{ __('User') }}">
                                        {{ @$transaction->user->fname . ' ' . @$transaction->user->lname }}</td>
                                    <td data-caption="{{ __('Gateway') }}">
                                        {{ @$transaction->gateway->gateway_name ?? 'Account Transfer' }}</td>
                                    <td data-caption="{{ __('Amount') }}">{{ $transaction->amount }}</td>
                                    <td data-caption="{{ __('Currency') }}">{{ $general->site_currency }}</td>
                                    <td data-caption="{{ __('Charge') }}">
                                        {{ $transaction->charge . ' ' . $transaction->currency }}</td>

                                    <td data-caption="{{ __('Payment Date') }}">
                                        {{ $transaction->created_at->format('Y-m-d') }}</td>
                                    <td data-caption="{{ __('Proof') }}">
                                        @if (!empty($transaction->payment_proof) && is_array($transaction->payment_proof))
                                            @foreach ($transaction->payment_proof as $proof)
                                                @if (is_array($proof) && isset($proof['file']))
                                                    <img src="{{ getFile('manual_payment', $proof['file']) }}"
                                                        alt="Payment Proof" class="w-50"
                                                        style="cursor: pointer; max-width: 80px;"
                                                        onclick="showProofModal('{{ getFile('manual_payment', $proof['file']) }}')">
                                                @endif
                                            @endforeach
                                        @else
                                            {{ __('No Proof') }}
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td class="text-center no-data-table" colspan="100%">
                                        {{ __('No users Found') }}
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                    @if ($transactions->hasPages())
                        {{ $transactions->links() }}
                    @endif
                </div>
            </div>
        </div>
    </div>
    <!-- Modal HTML -->
    <div id="proofModal" class="modal fade" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Payment Proof</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center">
                    <img id="proofImage" src="" alt="Payment Proof" class="img-fluid" style="max-width: 100%; height: auto; border-radius: 8px;">
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript for Modal -->
    <script>
        function showProofModal(imageSrc) {
            document.getElementById('proofImage').src = imageSrc;
            var proofModal = new bootstrap.Modal(document.getElementById('proofModal'));
            proofModal.show();
        }
    </script>
@endsection
