@extends('client.layout.main')

@section('content')
<section class="user-dashboard-section section-b-space">
    <div class="container-fluid-lg">
        <div class="row g-4">

            <!-- Sidebar trái -->
            <div class="col-xxl-3 col-lg-4">
                @include('client.account.sidebar')
            </div>

            <!-- Nội dung -->
            <div class="col-xxl-9 col-lg-8">
                <div class="dashboard-right-sidebar">
                    <div class="dashboard-profile">
                        <div class="title d-flex justify-content-between align-items-center">
                            <h2>Change Password</h2>
                        </div>

                        @if (session('success'))
                            <div class="alert alert-success">{{ session('success') }}</div>
                        @endif

                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form action="{{ route('account.updatePassword') }}" method="POST" class="dashboard-bg-box p-4 rounded shadow-sm">
                            @csrf

                            <div class="mb-3">
                                <label for="current_password" class="form-label">Current Password</label>
                                <input type="password" name="current_password" class="form-control" required>
                            </div>

                            <div class="mb-3">
                                <label for="new_password" class="form-label">New Password</label>
                                <input type="password" name="new_password" class="form-control" required minlength="8">
                            </div>

                            <div class="mb-3">
                                <label for="new_password_confirmation" class="form-label">Confirm New Password</label>
                                <input type="password" name="new_password_confirmation" class="form-control" required minlength="8">
                            </div>

                            <div class="mt-3">
                                <button type="submit" class="btn btn-primary">Update Password</button>
                                <a href="{{ route('account.index') }}" class="btn btn-outline-secondary">Cancel</a>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
