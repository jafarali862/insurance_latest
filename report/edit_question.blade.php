@extends('dashboard.layout.app')
@section('title', 'Edit Company')

@section('content')
<div class="container-fluid">
    <h1 class="h3 mb-4 text-center text-primary font-weight-bold">Edit Questionnaire</h1>

    <div class="row justify-content-center">
        <div class="col-lg-8 col-md-10">
            <div class="card card-primary shadow-sm rounded-lg">
                <div class="card-header">
                    <h6 class="m-0 font-weight-bold text-white">Edit Questionnaire</h6>
                </div>
                <div class="card-body">
                    <div id="successMessage" class="alert alert-success" style="display: none;"></div>

                    <form action="{{ route('questions.update_question', $question->id) }}" method="POST" id="updateQuestionForm" novalidate>
                        @csrf
                        @method('PUT')

                        <div class="form-group">
                            <label for="question">Question <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="question" name="question" value="{{ old('question', $question->question) }}" required>
                            <small id="question-error" class="text-danger"></small>
                        </div>

                        <div class="form-group">
                            <label for="input_type">Input Type <span class="text-danger">*</span></label>
                            <select class="form-control" id="input_type" name="input_type">
    <!-- <option value="" disabled {{ old('input_type', $question->input_type ?? '') == '' ? 'selected' : '' }}>
        -- Select Input Type --
    </option> -->
    <option value="{{ $question->input_type }}" selected disabled>
        {{ ucfirst($question->input_type) }}
    </option>

</select>

                            <small id="input_type-error" class="text-danger"></small>
                        </div>

                        <div class="form-group">
                            <label for="data_category">Data Category <span class="text-danger">*</span></label>
                            <select class="form-control" id="data_category" name="data_category" required>
                                @foreach(['garage_data', 'spot_data', 'owner_data', 'driver_data', 'accident_person_data'] as $cat)
                                    <option value="{{ $cat }}" {{ old('data_category', $question->data_category) == $cat ? 'selected' : '' }}>
                                        {{ ucfirst(str_replace('_', ' ', $cat)) }}
                                    </option>
                                @endforeach
                            </select>
                            <small id="data_category-error" class="text-danger"></small>
                        </div>

                        <button type="submit" class="btn btn-success">Update</button>
                        <a href="{{ route('questions.index_question') }}" class="btn btn-secondary">Cancel</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script>
$(document).ready(function() {
    $('#updateQuestionForm').on('submit', function(e) {
        e.preventDefault();
        $('.text-danger').text('');
        $('#successMessage').hide().text('');

        const form = $(this);
        const actionUrl = form.attr('action');

        $.ajax({
            url: actionUrl,
            type: 'POST',
            data: form.serialize(),
            success: function(response) {
            if (response.success) {
            // Show the message
            $('#successMessage').text(response.success).fadeIn();

            // Wait 3 seconds, then redirect
            setTimeout(function() {
            window.location.href = response.redirect_url;
            }, 3000);
            }
            },

            error: function(xhr) {
                if (xhr.status === 422) {
                    let errors = xhr.responseJSON.errors;
                    $.each(errors, function(field, messages) {
                        $('#' + field + '-error').text(messages[0]);
                    });
                } else {
                    alert('An unexpected error occurred.');
                }
            }
        });
    });
});
</script>
@endpush
@endsection
