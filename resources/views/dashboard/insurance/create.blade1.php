@extends('dashboard.layout.app')
@section('title', 'Add New Insurance Customer')
@section('content')
    <div class="container-fluid">
        <div class="text-right">
            <button class="btn btn-danger mr-2 mb-2" onclick="window.history.back()"><i class="fa fa-arrow-left"
                    aria-hidden="true"></i></button>
            <button class="btn btn-warning mr-2 mb-2" onclick="window.location.reload()"><i class="fa fa-spinner"
                    aria-hidden="true"></i></button>
            <a href="{{ route('case.index') }}" class="btn btn-primary mr-2 mb-2">
                <i class="fa fa-list" aria-hidden="true"></i>
            </a>
        </div>
        <div class="row">
            <div class="col-md-8 offset-md-2">
                <div class="card mb-5">
                    <div class="card-header">
                        <h4>Add New Insurance Customer</h4>
                    </div>
                    <div id="successMessage" class="alert alert-success mt-3" style="display: none;"></div>
                    <div class="card-body">
                        <form id="insuranceForm" action="{{ route('insurance.store') }}" method="POST">
                            @csrf
                            
                            <div class="form-group">
                                <label for="company">Select Company</label> <span class="text-danger">*</span>
                                <select id="company" name="company" class="form-control">
                                    <option disabled selected>Select Company</option>
                                    @foreach ($companies as $company)
                                        <option value="{{$company->id}}">{{$company->name}}</option>
                                    @endforeach
                                </select>
                                <span id="company-error" class="text-danger"></span>
                            </div>

                            <div class="form-group">
                                <label for="name">Name</label> <span class="text-danger">*</span>
                                <input type="text" id="name" name="name" class="form-control" placeholder="Enter name">
                                <span id="name-error" class="text-danger"></span>
                            </div>

                            <div class="form-group">
                                <label for="father_name">Father's Name</label> <span class="text-danger">*</span>
                                <input type="text" id="father_name" name="father_name" class="form-control" placeholder="Enter father's name">
                                <span id="father_name-error" class="text-danger"></span>
                            </div>

                            <div class="form-group">
                                <label for="phone">Phone</label> <span class="text-danger">*</span>
                                <input type="text" id="phone" name="phone" class="form-control" placeholder="Enter phone number">
                                <span id="phone-error" class="text-danger"></span>
                            </div>

                            <div class="form-check">
                                <input type="checkbox" id="same_as_phone" class="form-check-input">
                                <label for="same_as_phone" class="form-check-label text-primary">Emergency contact number same as phone</label>
                            </div><br>

                            <div class="form-group"> <span class="text-danger">*</span>
                                <label for="emergency_contact_number">Emergency Contact Number</label>
                                <input type="text" id="emergency_contact_number" name="emergency_contact_number" class="form-control" placeholder="Enter emergency contact number">
                                <span id="emergency_contact_number-error" class="text-danger"></span>
                            </div>

                            <div class="form-group">
                                <label for="email">Email</label>
                                <input type="email" id="email" name="email" class="form-control" placeholder="Enter email">
                                <span id="email-error" class="text-danger"></span>
                            </div>

                            <div class="form-group"> <span class="text-danger">*</span>
                                <label for="present_address">Present Address</label>
                                <textarea id="present_address" name="present_address" class="form-control" rows="3" placeholder="Enter present address"></textarea>
                                <span id="present_address-error" class="text-danger"></span>
                            </div>

                            <div class="form-check">
                                <input type="checkbox" id="same_as_present" class="form-check-input">
                                <label for="same_as_present" class="form-check-label text-primary">Present address same as permanent address</label>
                            </div><br>

                            <div class="form-group"> <span class="text-danger">*</span>
                                <label for="permanent_address">Permanent Address</label>
                                <textarea id="permanent_address" name="permanent_address" class="form-control" rows="3" placeholder="Enter permanent address"></textarea>
                                <span id="permanent_address-error" class="text-danger"></span>
                            </div>

                            <div class="form-group"> <span class="text-danger">*</span>
                                <label for="insurance_type">Insurance Type</label>
                                <input type="text" id="insurance_type" name="insurance_type" class="form-control" placeholder="Enter insurance type">
                                <span id="insurance_type-error" class="text-danger"></span>
                            </div>

                            <div class="form-group"> <span class="text-danger">*</span>
                                <label for="case_details">Case Details</label>
                                <textarea id="case_details" name="case_details" class="form-control" rows="3" placeholder="Enter case details"></textarea>
                                <span id="case_details-error" class="text-danger"></span>
                            </div>

                            <button type="submit" class="btn btn-primary">Submit</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script>
        $(document).ready(function() {
            // Handle checkbox for emergency contact number
            $('#same_as_phone').on('change', function() {
                if ($(this).is(':checked')) {
                    $('#emergency_contact_number').val($('#phone').val());
                } else {
                    $('#emergency_contact_number').val('');
                }
            });

            // Handle checkbox for addresses
            $('#same_as_present').on('change', function() {
                if ($(this).is(':checked')) {
                    $('#permanent_address').val($('#present_address').val());
                } else {
                    $('#permanent_address').val('');
                }
            });

            // Handle form submission
            $('#insuranceForm').on('submit', function(e) {
                e.preventDefault();

                $.ajax({
                    url: '{{ route('insurance.store') }}',
                    type: 'POST',
                    data: $(this).serialize(),
                    success: function(response) {
                        if (response.success) {
                            $('#successMessage').text(response.success).show();
                            $('#insuranceForm')[0].reset(); // Reset form fields
                            $('.text-danger').text(''); // Clear previous error messages
                            $('#same_as_phone').prop('checked', false); // Uncheck the checkbox
                            $('#same_as_present').prop('checked', false); // Uncheck the checkbox
                        }
                    },
                    error: function(xhr) {
                        var errors = xhr.responseJSON.errors;
                        $('.text-danger').text(''); // Clear previous error messages
                        $.each(errors, function(key, value) {
                            $('#' + key + '-error').text(value);
                        });
                    }
                });
            });
        });
    </script>
@endsection
