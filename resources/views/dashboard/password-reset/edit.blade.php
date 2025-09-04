@extends('dashboard.layout.app')
@section('title', 'Password Edit')

@section('content')
    <div class="container-fluid">
        <div class="text-right">
            <button class="btn btn-danger mr-2 mb-2" onclick="window.history.back()">
                <i class="fa fa-arrow-left" aria-hidden="true"></i>
            </button>
            <button class="btn btn-warning mr-2 mb-2" onclick="window.location.reload()">
                <i class="fa fa-spinner" aria-hidden="true"></i>
            </button>
        </div>

        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">Password Reset Request Details</h5>
            </div>
            <div class="card-body">
                <h6 class="px-2">User Name: <span class="text-primary">{{ $request->user_name }}</span></h6>
                <h6 class="px-2 py-3">Request Date: <span class="text-primary">{{ $request->request_date }}</span></h6>

                <div class="form-row mt-3">
                    <label for="passwordToggle" class="col-form-label h6 col-12">New Password:</label>
                    <div class="col-12 col-sm-3">
                        <div class="input-group">
                            <input type="password" class="form-control" id="passwordToggle" value="{{ $request->password }}"
                                readonly>
                            <div class="input-group-append">
                                <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                                    <i class="fa fa-eye" id="eyeIcon"></i>
                                </button>
                            </div>
                        </div>
                        <div class="d-flex align-items-center mt-4">
                            <h6 class="mr-2 mb-0">Change Status:</h6>
                            <button class="btn btn-success mr-3 ml-3" id="showForm"><i class="fa fa-check-circle"
                                    aria-hidden="true"></i></button>
                            {{-- <button class="btn btn-danger" id="rejectRequest"><i class="fa fa-times"
                                    aria-hidden="true"></i></button> --}}
                        </div>
                    </div>
                </div>

                <div class="row justify-content-center">
                    <div class="col-sm-4 col-12">
                        @if (session('error'))
                            <div class="alert alert-danger">{{ session('error') }}</div>
                        @endif

                        <form id="passwordResetForm" action="{{ route('password.reset.update', $request->id) }}"
                            method="POST" style="display: none">
                            @csrf
                            <div class="form-group mt-3">
                                <input type="hidden" name="user_id" value="{{ $request->user_id }}" required>
                                <label for="newPassword">Enter New Password <span class="text-danger">*</span></label>
                                <input type="password" class="form-control" id="newPassword" name="new_password" required>

                                <label for="confirmPassword" class="mt-2">Enter Confirm Password <span
                                        class="text-danger">*</span></label>
                                <input type="password" class="form-control" id="confirmPassword"
                                    name="new_password_confirmation" required>
                            </div>

                            <div class="text-center">
                                <button type="submit" class="btn btn-success">Update Password</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Reject Confirmation Modal -->
    <div class="modal fade" id="rejectConfirmationModal" tabindex="-1" role="dialog"
        aria-labelledby="rejectConfirmationLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="rejectConfirmationLabel">Confirm Rejection</h5>
                </div>
                <div class="modal-body">
                    Are you sure you want to reject this password reset request?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" id="confirmReject">Reject</button>
                </div>
            </div>
        </div>
    </div>



    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        document.getElementById('showForm').addEventListener('click', function() {
            const form = document.getElementById('passwordResetForm');
            form.style.display = form.style.display === 'none' || form.style.display === '' ? 'block' : 'none';
        });

        document.getElementById('togglePassword').addEventListener('click', function() {
            const passwordField = document.getElementById('passwordToggle');
            const eyeIcon = document.getElementById('eyeIcon');
            const isPasswordVisible = passwordField.type === 'text';

            passwordField.type = isPasswordVisible ? 'password' : 'text';
            eyeIcon.classList.toggle('fa-eye', isPasswordVisible);
            eyeIcon.classList.toggle('fa-eye-slash', !isPasswordVisible);
        });

        $(document).ready(function() {
            $('#passwordResetForm').on('submit', function(e) {
                e.preventDefault(); // Prevent the default form submission

                $.ajax({
                    url: $(this).attr('action'),
                    type: 'POST',
                    data: $(this).serialize(),
                    success: function(response) {
                        if (response.success) {
                            // Display success message or redirect
                            alert(response.message);
                            // Optionally, redirect to a different page
                            // window.location.href = '/some-page';
                        } else {
                            // Display error message
                            alert(response.message);
                        }
                    },
                    error: function(xhr) {
                        // Handle validation errors
                        const errors = xhr.responseJSON.errors;
                        let errorMessage = '';
                        for (const error in errors) {
                            errorMessage += errors[error].join(', ') + '\n';
                        }
                        alert(errorMessage || 'An error occurred. Please try again.');
                    }
                });
            });
        });
    </script>


@endsection
