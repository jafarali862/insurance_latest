@extends('dashboard.layout.app')
@section('title', 'Add User')
@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-end mb-3 gap-2">
        <button class="btn btn-danger mr-2" onclick="window.history.back()">
            <i class="fas fa-arrow-left"></i>
        </button>
        <button class="btn btn-warning mr-2" onclick="window.location.reload()">
            <i class="fas fa-sync-alt"></i>
        </button>
        <a href="{{ route('user.list') }}" class="btn btn-primary">
            <i class="fas fa-users"></i>
        </a>
    </div>

    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card card-primary shadow">
                <div class="card-header">
                    <h3 class="card-title">Add User</h3>
                </div>
                <div class="card-body">
                    <div id="successMessage" class="alert alert-success" style="display: none;"></div>

                    <form id="addUser" action="{{ route('user.store') }}" method="POST" novalidate>
                        @csrf
                        <div class="form-group">
                            <label for="name">Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" placeholder="Enter name" required>
                            <span class="error invalid-feedback" id="name-error"></span>
                            <x-error name="name" />
                        </div>

                        <div class="form-group">
                            <label for="email">Email address <span class="text-danger">*</span></label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" placeholder="Enter email" required>
                            <span class="error invalid-feedback" id="email-error"></span>
                            <x-error name="email" />
                        </div>

                        <div class="form-group">
                            <label for="contact_number">Contact Number <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('phone') is-invalid @enderror" id="contact_number" name="phone" placeholder="Enter contact number" required>
                            <span class="error invalid-feedback" id="phone-error"></span>
                            <x-error name="phone" />
                        </div>

                        <div class="form-group">
                            <label for="place">Place <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('place') is-invalid @enderror" id="place" name="place" placeholder="Enter place" required>
                            <span class="error invalid-feedback" id="place-error"></span>
                            <x-error name="place" />
                        </div>

                        <div class="form-group">
                            <label for="role">Role <span class="text-danger">*</span></label>
                            <select id="role" name="role" class="form-control @error('role') is-invalid @enderror" required>
                                <option value="" disabled selected>Select user role</option>
                                <option value="3">Executive</option>
                                <option value="2">Sub Admin</option>
                            </select>
                            <span class="error invalid-feedback" id="role-error"></span>
                            <x-error name="role" />
                        </div>

                        <div class="form-group">
                            <label for="password">Password <span class="text-danger">*</span></label>
                            <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" required>
                            <span class="error invalid-feedback" id="password-error"></span>
                            <x-error name="password" />
                        </div>

                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save mr-1"></i> Add User
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- jQuery CDN already included if AdminLTE setup, else include below -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

<script>
    $(document).ready(function() {
        $('#addUser').on('submit', function(e) {
            e.preventDefault();

            $.ajax({
                url: '{{ route('user.store') }}',
                type: 'POST',
                data: $(this).serialize(),
                success: function(response) {
                if (response.success) {
                    $('#successMessage').text(response.success).show();
                    $('#addUser')[0].reset();            // Reset form fields
                    $('.error').text('').hide();         // Clear previous error messages
                    $('.form-control').removeClass('is-invalid');

                    // setTimeout(function() {
                    //     location.reload();                // Reload page after 3 seconds
                    // }, 3000);

                    setTimeout(function() {
                    // Redirect to the URL sent from backend
                    window.location.href = response.redirect_url;
                    }, 3000);

                }
                },

                error: function(xhr) {
                    var errors = xhr.responseJSON.errors;
                    $('.error').text('').hide();
                    $('.form-control').removeClass('is-invalid');
                    $.each(errors, function(key, value) {
                        $('#' + key + '-error').text(value).show();
                        $('#' + key).addClass('is-invalid');
                    });
                }
            });
        });
    });
</script>
@endsection
