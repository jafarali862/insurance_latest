@extends('dashboard.layout.app')
@section('title', 'Edit User')

@section('content')
<div class="container-fluid">
    <h1 class="h3 mb-4 text-center">Edit User</h1>

    <div class="row justify-content-center">
        <div class="col-lg-8 col-md-10">
            <div class="card shadow">
                <div class="card-header bg-primary">
                    <h4 class="card-title m-0 text-white">Edit User</h4>
                </div>
                <div class="card-body">
                    <div id="successMessage" class="alert alert-success" style="display:none;"></div>
                    <form id="updateUser" action="{{ route('user.update') }}" method="POST" novalidate>
                        @csrf
                        <input type="hidden" name="id" value="{{ $user->id }}">

                        <div class="form-group">
                            <label for="name" class="font-weight-bold">Name <span class="text-danger">*</span></label>
                            <input type="text" id="name" name="name" 
                                class="form-control rounded-pill @error('name') is-invalid @enderror" 
                                value="{{ old('name', $user->name) }}" required>
                            <span class="text-danger" id="name-error"></span>
                        </div>

                        <div class="form-group">
                            <label for="phone" class="font-weight-bold">Phone <span class="text-danger">*</span></label>
                            <input type="tel" id="phone" name="phone" 
                                class="form-control rounded-pill @error('phone') is-invalid @enderror" 
                                value="{{ old('phone', $user->phone) }}" required>
                            <span class="text-danger" id="phone-error"></span>
                        </div>

                        <div class="form-group">
                            <label for="email" class="font-weight-bold">Email <span class="text-danger">*</span></label>
                            <input type="email" id="email" name="email" 
                                class="form-control rounded-pill @error('email') is-invalid @enderror" 
                                value="{{ old('email', $user->email) }}" required>
                            <span class="text-danger" id="email-error"></span>
                        </div>

                        <div class="form-group">
                            <label for="role" class="font-weight-bold">Role <span class="text-danger">*</span></label>
                            <select id="role" name="role" class="form-control rounded-pill" required>
                                <option value="2" {{ old('role', $user->role) == '2' ? 'selected' : '' }}>Sub Admin</option>
                                <option value="3" {{ old('role', $user->role) == '3' ? 'selected' : '' }}>Executive</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="status" class="font-weight-bold">Status <span class="text-danger">*</span></label>
                            <select id="status" name="status" class="form-control rounded-pill" required>
                                <option value="1" {{ old('status', $user->status) == '1' ? 'selected' : '' }}>Active</option>
                                <option value="0" {{ old('status', $user->status) == '0' ? 'selected' : '' }}>Inactive</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="password" class="font-weight-bold">Password</label>
                            <input type="password" id="password" name="password" class="form-control rounded-pill" placeholder="Leave blank to keep current password">
                            <span class="text-danger" id="password-error"></span>
                        </div>

                        <div class="d-flex justify-content-between">
                            <button type="submit" class="btn btn-primary rounded-pill px-4">
                                <i class="fas fa-save mr-1"></i> Update User
                            </button>
                            <a href="{{ route('user.list') }}" class="btn btn-secondary rounded-pill px-4">
                                <i class="fas fa-times mr-1"></i> Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script>
    $(function() {
        $('#updateUser').on('submit', function(e) {
            e.preventDefault();
            $.ajax({
                url: '{{ route('user.update') }}',
                method: 'POST',
                data: $(this).serialize(),
                success: function(response) {
                if (response.success) {
                $('#successMessage').text(response.success).show();
                $('.text-danger').text(''); // Clear error messages

                // Wait 3 seconds then reload the page
                // setTimeout(function() {
                // location.reload();
                // }, 3000);

                 setTimeout(function() {
                    // Redirect to the URL sent from backend
                    window.location.href = response.redirect_url;
                    }, 3000);

                }
                },
                error: function(xhr) {
                    var errors = xhr.responseJSON.errors;
                    $('.text-danger').text('');
                    $.each(errors, function(key, val) {
                        $('#' + key + '-error').text(val);
                    });
                }
            });
        });
    });
</script>
@endsection
