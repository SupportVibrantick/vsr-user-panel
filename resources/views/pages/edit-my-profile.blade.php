@extends('layouts.master')
@section("title", "Update Profile")
@section("content")
<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            {{-- @if($user && $user->profile_update_count >= 1)
                <div class="alert alert-warning">
                    <strong>(YOU UPDATED YOUR PROFILE {{ $user->profile_update_count }} TIME, SECOND TIME YOU CAN UPDATE YOUR PROFILE BY ADMIN)</strong>
                </div>
            @else
                <div class="alert alert-info">
                    <strong>Note:</strong> 
                    You can update your profile only <b>1 time</b>. Fill in carefully!
                </div>
            @endif --}}
            {{-- @dump($user['sponsor']['user_name']) --}}

            <form action="{{ route('user.profile.update') }}" method="POST">
                @csrf

                <!-- PERSONAL DETAIL -->
                <div class="card mb-4">
                    <div class="card-header"><h5 class="mb-0">PERSONAL DETAIL</h5></div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label">First Name</label>
                                <input type="text" class="form-control" name="first_name" value="{{ $user['first_name'] }}">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Last Name</label>
                                <input type="text" name="last_name" class="form-control" value="{{ old('last_name', $user['last_name']) }}">
                            </div>
                            
                            <div class="col-md-4">
                                <label class="form-label">Email</label>
                                <input type="email" class="form-control bg-light" value="{{ $user['email'] }}" disabled readonly>
                            </div>
                             
                            <div class="col-md-4">
                                <label class="form-label">Mobile</label>
                                <input type="text" name="phone" class="form-control" value="{{ $user['phone'] }}">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Status</label>
                                <input type="text" class="form-control bg-light" value="{{ $user['is_active'] == 1 ? 'Active' : 'Inactive' }}" readonly>
                            </div>
                             
                        </div>

                    </div>
                    <div class="card-footer text-end">
                        <button type="submit" class="btn btn-primary">
                            Update Profile
                        </button>
                    </div>
                </div>
            </form>


                <!-- ADMISSION DETAIL (Read Only) -->
                <div class="card mb-4">
                    <div class="card-header"><h5 class="mb-0">ADMISSION DETAIL</h5></div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label">UserName</label>
                                <input type="text" class="form-control" value="{{ $user['user_name'] }}" readonly>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">GW No.</label>
                                <input type="text" class="form-control" value="{{ $user['track_id'] }}" readonly>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Sponsor ID</label>
                                <input type="text" class="form-control" value="{{ $user['sponsor']['user_name'] ?? 'N/A' }}" readonly>
                                {{-- @if($user->sponsor)
                                    <small class="text-muted">{{ $user->sponsor->first_name ?? '' }} {{ $user->sponsor->last_name ?? '' }}</small>
                                @endif --}}
                            </div>
                            {{-- <div class="col-md-4">
                                <label class="form-label">Upline ID</label>
                                <input type="text" class="form-control" value="{{ $user->upline->user_name ?? 'N/A' }}" readonly>
                                @if($user->upline)
                                    <small class="text-muted">{{ $user->upline->first_name }} {{ $user->upline->last_name }}</small>
                                @endif
                            </div> --}}
                            <div class="col-md-4">
                                <label class="form-label">Date of Joining</label>
                                <input type="text" class="form-control" value="{{ date('d-m-Y',strtotime($user['created_at'])) }}" readonly>
                            </div>
                             
                        </div>
                    </div>
                </div>

                <div class="card mb-4">
                    <div class="card-header"><h5 class="mb-0">Update Password</h5></div>
                    <div class="card-body">
                       <form action="{{ route('user.change-password.update') }}" method="POST">
                            @csrf
                            
                            <div class="mb-3">
                                <label for="old_password" class="form-label">Old Password</label>
                                <input type="password" 
                                        name="old_password" 
                                        id="old_password" 
                                        class="form-control @error('old_password') is-invalid @enderror"
                                        placeholder="Old New Password"
                                        value="{{ old('old_password') }}"
                                        required>
                                @error('old_password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="new_password" class="form-label">New Password</label>
                                <input type="password" 
                                        name="new_password" 
                                        id="new_password" 
                                        class="form-control @error('new_password') is-invalid @enderror"
                                        placeholder="Enter New Password"
                                        required>
                                @error('new_password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                {{-- <div class="form-text">
                                    Minimum 6 characters required
                                </div> --}}
                            </div>

                            <div class="mb-3">
                                <label for="new_password_confirmation" class="form-label">Confirm Password</label>
                                <input type="password" 
                                        name="new_password_confirmation" 
                                        id="new_password_confirmation" 
                                        class="form-control"
                                        placeholder="Enter Confirm Password"
                                        required>
                            </div>
                            <button type="submit" class="btn btn-primary">
                                <i class="las la-key me-2"></i>Change Password
                            </button> 
                        </form>
                    </div>
                    
                </div>

                <!-- BANK DETAIL -->
                {{-- <div class="card mb-4">
                    <div class="card-header"><h5 class="mb-0">BANK DETAIL</h5></div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label">Bank Name</label>
                                <input type="text" name="bank_name" class="form-control" value="{{ old('bank_name', $user->bank_name) }}">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Branch Name</label>
                                <input type="text" name="branch_name" class="form-control" value="{{ old('branch_name', $user->branch_name) }}">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Account Type</label>
                                <input type="text" name="account_type" class="form-control" value="{{ old('account_type', $user->account_type) }}">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Account Number</label>
                                <input type="text" name="account_number" class="form-control" value="{{ old('account_number', $user->account_number) }}">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Account Holder Name</label>
                                <input type="text" name="account_holder_name" class="form-control" value="{{ old('account_holder_name', $user->account_holder_name) }}">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">IFSC Code</label>
                                <input type="text" name="ifsc_code" class="form-control" value="{{ old('ifsc_code', $user->ifsc_code) }}">
                            </div>
                        </div>
                    </div>
                </div> --}}

                 
        </div>
    </div>
</div>
@endsection