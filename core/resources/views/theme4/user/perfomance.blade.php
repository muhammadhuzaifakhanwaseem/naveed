{{-- @extends(template() . 'layout.master2')

@section('content2')
    <div class="dashboard-body-part">

        <div class="mobile-page-header">
            <h5 class="title">{{ __('Perfomance Chart') }}</h5>
            <a href="{{ route('user.dashboard') }}" class="back-btn"><i class="bi bi-arrow-left"></i> {{ __('Back') }}</a>
        </div>

        <!-- Level 1 Details -->
        <div class="site-card">
            <div class="card-header d-flex flex-wrap align-items-center justify-content-between">
                <h5 class="mb-sm-0 mb-2">{{ __('Level 1 Details') }}</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table site-table">
                        <thead>
                            <tr class="bg-yellow">
                                <th scope="col">{{ __('Total Users') }}</th>
                                <th scope="col">{{ __('Total Invested Members') }}</th>
                                <th scope="col">{{ __('Total Invest Amount') }}</th>
                                <th scope="col">{{ __('Total Active Plans') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td >{{ $levelOneUsersCount }}</td>
                                <td>{{ $totalInvestedMembersLevel1 }}</td>
                                <td>{{ $totalInvestedAmountLevel1 }}</td>
                                <td>{{ $totalPlansLevel1 }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Level 2 Details -->
        <div class="site-card mt-4">
            <div class="card-header d-flex flex-wrap align-items-center justify-content-between">
                <h5 class="mb-sm-0 mb-2">{{ __('Level 2 Details') }}</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table site-table">
                        <thead>
                            <tr class="bg-green">
                                <th scope="col">{{ __('Total Users') }}</th>
                                <th scope="col">{{ __('Total Invested Members') }}</th>
                                <th scope="col">{{ __('Total Invest Amount') }}</th>
                                <th scope="col">{{ __('Total Active Plans') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>{{ $levelTwoUsersCount }}</td>
                                <td>{{ $totalInvestedMembersLevel2 }}</td>
                                <td>{{ $totalInvestedAmountLevel2 }}</td>
                                <td>{{ $totalPlansLevel2 }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
@endsection --}}




@extends(template() . 'layout.master2')


@section('content2')
    <div class="dashboard-body-part">

        <div class="mobile-page-header">
            <h5 class="title">{{ __('Perfomance Chart') }}</h5>
            <a href="{{ route('user.dashboard') }}" class="back-btn"><i class="bi bi-arrow-left"></i> {{ __('Back') }}</a>
        </div>

        <div class="site-card">
            <div class="card-header d-flex flex-wrap align-items-center justify-content-between">
                <h5 class="mb-sm-0 mb-2">{{ __('Level 1 Detalis') }}</h5>
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table class="table site-table">
                        <thead>
                            <tr>
                                <th>{{ __('Total Users') }}</th>
                                <th>{{ __('Total Invested Members') }}</th>
                                <th>{{ __('Total Invest Amount') }}</th>
                                <th>{{ __('Total Active Plans') }}</th>
                            </tr>
                        </thead>

                        <tbody>
                            <tr>
                                <td data-caption="{{ __('Total Users') }}">{{ $levelOneUsersCount }}</td>
                                <td data-caption="{{ __('Total Invested Members') }}">{{ $totalInvestedMembersLevel1 }}
                                </td>
                                <td data-caption="{{ __('Total Invest Amount') }}">{{  number_format($totalInvestedAmountLevel1) }}</td>
                                <td data-caption="{{ __('Total Active Plans') }}">{{ $totalPlansLevel1 }}</td>
                            </tr>

                        </tbody>
                    </table>
                </div>  
            </div>
        </div>
        <div class="site-card">
            <div class="card-header d-flex flex-wrap align-items-center justify-content-between">
                <h5 class="mb-sm-0 mb-2">{{ __('Level 2 Detalis') }}</h5>
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table class="table site-table">
                        <thead>
                            <tr>
                                <th>{{ __('Total Users') }}</th>
                                <th>{{ __('Total Invested Members') }}</th>
                                <th>{{ __('Total Invest Amount') }}</th>
                                <th>{{ __('Total Active Plans') }}</th>
                            </tr>
                        </thead>

                        <tbody>
                            <tr>
                                <td data-caption="{{ __('Total Users') }}">{{ $levelTwoUsersCount }}</td>
                                <td data-caption="{{ __('Total Invested Members') }}">{{ $totalInvestedMembersLevel2 }}
                                </td>
                                <td data-caption="{{ __('Total Invest Amount') }}">{{  number_format($totalInvestedAmountLevel2) }}</td>
                                <td data-caption="{{ __('Total Active Plans') }}">{{ $totalPlansLevel2 }}</td>
                            </tr>

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="site-card">
            <div class="card-header d-flex flex-wrap align-items-center justify-content-between">
                <h5 class="mb-sm-0 mb-2">{{ __('Level 3 Detalis') }}</h5>
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table class="table site-table">
                        <thead>
                            <tr>
                                <th>{{ __('Total Users') }}</th>
                                <th>{{ __('Total Invested Members') }}</th>
                                <th>{{ __('Total Invest Amount') }}</th>
                                <th>{{ __('Total Active Plans') }}</th>
                            </tr>
                        </thead>

                        <tbody>
                            <tr>
                                <td data-caption="{{ __('Total Users') }}">{{ $levelThreeUsersCount }}</td>
                                <td data-caption="{{ __('Total Invested Members') }}">{{ $totalInvestedMembersLevel3 }}
                                </td>
                                <td data-caption="{{ __('Total Invest Amount') }}">{{  number_format($totalInvestedAmountLevel3) }}</td>
                                <td data-caption="{{ __('Total Active Plans') }}">{{ $totalPlansLevel3 }}</td>
                            </tr>

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
