@extends('layouts.master')
@section('title', 'User Registrations')
@section('content')
    <div class="main-content">
        <div class="page-content">
            <div class="container-fluid">

                <!-- Page Title -->
                <div class="row mb-4">
                    <div class="col-12">
                        <div class="page-title-box shadow-sm border-0">
                            <h4 class="mb-0 fs-20">
                                <i class="las la-paper-plane text-primary me-2"></i>User Registration Form
                                {{-- <small class="text-muted fs-14 ms-2">(Tickets raised by you)</small> --}}
                            </h4>
                        </div>
                    </div>
                </div>

                <!-- Toast -->
                <div class="toast-container position-fixed top-0 end-0 p-3" style="z-index: 9999;">
                    <div id="mainToast" class="toast align-items-center text-white border-0" role="alert">
                        <div class="d-flex">
                            <div class="toast-body" id="toastMessage"></div>
                            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
                        </div>
                    </div>
                </div>

                <div id="success-message"></div>

                <form id="registerForm" method="POST">
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="mb-0">PERSONAL DETAIL</h5>
                        </div>
    
                        <div class="card-body">
                            <div class="row g-3">
    
                                <!-- Username -->
                                <div class="col-md-4">
                                    <label class="form-label">Username</label>
                                    <input type="text" class="form-control" name="user_name">
                                </div>
    
                                <!-- Sponsor -->
                                <div class="col-md-4">
                                    <label class="form-label">Sponsor</label>
                                    <input type="text" class="form-control" name="sponsor">
                                </div>
    
                                <!-- First Name -->
                                <div class="col-md-4">
                                    <label class="form-label">First Name</label>
                                    <input type="text" class="form-control" name="first_name">
                                </div>
    
                                <!-- Last Name -->
                                <div class="col-md-4">
                                    <label class="form-label">Last Name</label>
                                    <input type="text" class="form-control" name="last_name">
                                </div>
    
                                <!-- Email -->
                                <div class="col-md-4">
                                    <label class="form-label">Email</label>
                                    <input type="email" class="form-control" name="email">
                                </div>
    
                                <!-- Phone -->
                                <div class="col-md-4">
                                    <label class="form-label">Phone</label>
                                    <input type="text" class="form-control" name="phone">
                                </div>
    
                                <!-- Password -->
                                <div class="col-md-4">
                                    <label class="form-label">Password</label>
                                    <input type="password" class="form-control" name="password">
                                </div>
    
                                <!-- Confirm Password -->
                                <div class="col-md-4">
                                    <label class="form-label">Confirm Password</label>
                                    <input type="password" class="form-control" name="password_confirmation">
                                </div>
    
                            </div>
                        </div>
                        <div class="card-footer text-end">
                            <button type="submit" class="btn btn-primary">Update Profile</button>
                        </div>
                    </div>
                </form>


            </div>
        </div>
    </div>  

@endsection

@push('scripts')
    <script>
        function showToast(message, type = 'success') {

            let toastEl = $('#mainToast');

            toastEl.removeClass('bg-success bg-danger bg-warning bg-info');

            if (type === 'success') {
                toastEl.addClass('bg-success');
            } else if (type === 'error') {
                toastEl.addClass('bg-danger');
            } else if (type === 'warning') {
                toastEl.addClass('bg-warning');
            } else {
                toastEl.addClass('bg-info');
            }

            $('#toastMessage').text(message);

            let toast = new bootstrap.Toast(document.getElementById('mainToast'));
            toast.show();
        }

        $('#registerForm').on('submit', function(e) {
            e.preventDefault();

            // Clear previous errors
            $('.text-danger').remove();

            let formData = new FormData(this);

            $.ajax({
                url: 'http://127.0.0.1:8000/api/user-register',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                headers: {
                    Accept: 'application/json'
                },
                beforeSend: function() {
                    $('button[type="submit"]').prop('disabled', true).text('Please Wait...');
                },
                success: function(response) {

                    showToast(response.message, 'success');

                    $('#registerForm')[0].reset();
                },
                error: function(xhr) {

                    if (xhr.status === 422) {

                        let errors = xhr.responseJSON.errors;

                        $.each(errors, function(field, messages) {

                            $('[name="' + field + '"]')
                                .after('<small class="text-danger d-block">' + messages[0] + '</small>');

                        });

                    } else {

                         let message = xhr.responseJSON?.message || 'Something went wrong.';                        

                        showToast(message, 'error');
                    }
                },
                complete: function() {
                    $('button[type="submit"]').prop('disabled', false).text('Register');
                }
            });
        });
    </script>
@endpush