@extends('backend.layout.master')

@section('content')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>{{ __($pageTitle) }}</h1>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="card">

                        <!-- Search Form -->
                        <div class="card-header">
                            <form method="GET" action="{{ route('admin.user.list') }}">
                                <div class="input-group">
                                    <input type="text" name="search" class="form-control"
                                        placeholder="Search by Email, Username, or Phone" value="{{ request('search') }}">
                                    <div class="input-group-append">
                                        <button class="btn btn-primary" type="submit">Search</button>
                                    </div>
                                </div>
                            </form>
                        </div>

                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Sl</th>
                                            <th>Full Name</th>
                                            <th>Phone</th>
                                            <th>Email</th>
                                            <th>Country</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($users as $key => $user)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $user->fullname }}</td>
                                                <td>{{ $user->phone }}</td>
                                                <td>{{ $user->email }}</td>
                                                <td>{{ $user->address->country ?? 'N/A' }}</td>
                                                <td>
                                                    @if ($user->status)
                                                        <span class='badge badge-success'>Active</span>
                                                    @else
                                                        <span class='badge badge-danger'>Inactive</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <a href="{{ route('admin.user.details', $user) }}" class="btn btn-sm btn-outline-primary">
                                                        <i class="fa fa-eye mr-2"></i>Details
                                                    </a>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td class="text-center" colspan="7">No Data Found</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Pagination -->
                        <div class="card-footer">
                            {{ $users->links() }}
                        </div>

                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
