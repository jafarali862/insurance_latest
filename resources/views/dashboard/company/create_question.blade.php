@extends('dashboard.layout.app')
@section('title', 'Add Questionnaire')

@push('styles')
    <!-- Optional: AdminLTE custom styles can go here -->
@endpush

@section('content')
<div class="container-fluid">
    <div class="row mb-3">
        <div class="col text-right">
            <button class="btn btn-danger" onclick="window.history.back()">
                <i class="fas fa-arrow-left"></i>
            </button>
            <button class="btn btn-warning" onclick="window.location.reload()">
                <i class="fas fa-sync-alt"></i>
            </button>
            <a href="{{ route('company.list') }}" class="btn btn-primary">
                <i class="fas fa-list"></i>
            </a>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card card-primary shadow-sm">
                <div class="card-header">
                    <h4 class="card-title font-weight-bold mb-0">Add Questionnaire</h4>
                </div>
                <div class="card-body">
                        <div id="successMessage" class="alert alert-success" style="display: none;"></div>
                    <form action="{{ route('companies.store_question') }}" method="POST" id="companyForm" novalidate>
                        @csrf

                        <div class="form-group">
                            <label for="question">Question <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="question" name="question" required>
                            <small id="question-error" class="text-danger"></small>
                        </div>

                        <div class="form-group">
                            <label for="input_type">Input Type <span class="text-danger">*</span></label>
                            
                            <select class="form-control" id="input_type" name="input_type" required>
                                <option value="" disabled selected>Select Type</option>
                                <option value="text">Text</option>
                                <option value="textarea">Textarea</option>
                                <option value="select">Select</option>
                                <option value="file">File</option>
                                <option value="date">Date</option>
                            </select>
                            <small id="input_type-error" class="text-danger"></small>
                        </div>

                        <div class="form-group" id="fileTypeContainer" style="display: none;">
                            <label for="file_type">File Type <span class="text-danger">*</span></label>
                            <select class="form-control" id="file_type" name="file_type">
                                <option value="" disabled selected>Select File Type</option>
                                <option value="image">Image</option>
                                <option value="audio">Audio</option>
                            </select>
                            <small id="file_type-error" class="text-danger"></small>
                        </div>

                        <div class="form-group">
                            <label for="data_category">Data Category <span class="text-danger">*</span></label>
                            <select class="form-control" id="data_category" name="data_category" required>
                                <option value="" disabled selected>Select Data Category</option>
                                <option value="garage_data">Garage Data</option>
                                <option value="spot_data">Spot Data</option>
                                <option value="owner_data">Owner Data</option>
                                <option value="driver_data">Driver Data</option>
                                <option value="accident_person_data">Accident Person Data</option>
                            </select>
                            <small id="data_category-error" class="text-danger"></small>
                        </div>

                        <button type="submit" class="btn btn-primary">Add Question</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

<script>
$(document).ready(function() {
    $('#companyForm').on('submit', function(e) {
        e.preventDefault();
        $('.text-danger').text(''); // Clear error messages
        $.ajax({
            url: '{{ route('companies.store_question') }}',
            type: 'POST',
            data: $(this).serialize(),
            success: function(response) {
            if (response.success) {
            $('#successMessage').text(response.success).show();
            $('#companyForm')[0].reset(); // Reset form fields
            $('.text-danger').text(''); // Clear previous error messages

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
                if(xhr.status === 422) {
                    let errors = xhr.responseJSON.errors;
                    $.each(errors, function(key, value) {
                        $('#' + key + '-error').text(value[0]);
                    });
                } else {
                    alert('An error occurred. Please try again.');
                }
            }
        });
    });

    $('#input_type').on('change', function() {
        const fileTypeContainer = $('#fileTypeContainer');
        if ($(this).val() === 'file') {
            fileTypeContainer.show();
            $('#file_type').attr('required', 'required');
        } else {
            fileTypeContainer.hide();
            $('#file_type').removeAttr('required');
            $('#file_type-error').text('');
        }
    });
});
</script>
@endpush
