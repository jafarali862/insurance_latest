@extends('dashboard.layout.app')
@section('title', 'Edit Insurance Customer')

@section('content')
<div class="container-fluid">
    <!-- Action Buttons -->
    <div class="text-right mb-3">
        <button class="btn btn-danger" onclick="window.history.back()">
            <i class="fas fa-arrow-left"></i>
        </button>
        <button class="btn btn-warning" onclick="window.location.reload()">
            <i class="fas fa-sync-alt"></i>
        </button>
        <a href="{{ route('case.index') }}" class="btn btn-primary">
            <i class="fas fa-list"></i>
        </a>
    </div>

    <!-- Card -->
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">Edit Insurance Customer</h3>
                </div>

                <div id="successMessage" class="alert alert-success m-3" style="display: none;"></div>

                <form id="insuranceForm" action="{{ route('case.update', $customers->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="card-body">
                        <!-- Company -->
                        <div class="form-group">
                            <label for="company">Select Company <span class="text-danger">*</span></label>
                            <select id="company" name="company" class="form-control">
                                <option disabled>Select Company</option>
                                @foreach ($companies as $company)
                                    <option value="{{ $company->id }}" {{ $customers->company_id == $company->id ? 'selected' : '' }}>
                                        {{ $company->name }}
                                    </option>
                                @endforeach
                            </select>
                            <span id="company-error" class="text-danger"></span>
                        </div>

                        <!-- Name & Father's Name -->
                        <div class="form-group">
                            <label for="name">Name <span class="text-danger">*</span></label>
                            <input type="text" id="name" name="name" class="form-control" value="{{ $customers->name }}">
                            <span id="name-error" class="text-danger"></span>
                        </div>

                        <div class="form-group">
                            <label for="father_name">Father's Name <span class="text-danger">*</span></label>
                            <input type="text" id="father_name" name="father_name" class="form-control" value="{{ $customers->father_name }}">
                            <span id="father_name-error" class="text-danger"></span>
                        </div>

                        <!-- Phone -->
                        <div class="form-group">
                            <label for="phone">Phone <span class="text-danger">*</span></label>
                            <input type="text" id="phone" name="phone" class="form-control" value="{{ $customers->phone }}">
                            <span id="phone-error" class="text-danger"></span>
                        </div>

                        <div class="form-check mb-3">
                            <input type="checkbox" id="same_as_phone" class="form-check-input">
                            <label class="form-check-label text-primary" for="same_as_phone">
                                Emergency contact number same as phone
                            </label>
                        </div>

                        <!-- Emergency Contact -->
                        <div class="form-group">
                            <label for="emergency_contact_number">Emergency Contact Number <span class="text-danger">*</span></label>
                            <input type="text" id="emergency_contact_number" name="emergency_contact_number" class="form-control" value="{{ $customers->emergency_contact_number }}">
                            <span id="emergency_contact_number-error" class="text-danger"></span>
                        </div>

                        <!-- Email -->
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" id="email" name="email" class="form-control" value="{{ $customers->email }}">
                            <span id="email-error" class="text-danger"></span>
                        </div>

                        <!-- Addresses -->
                        <div class="form-group">
                            <label for="present_address">Present Address <span class="text-danger">*</span></label>
                            <textarea id="present_address" name="present_address" class="form-control" rows="3">{{ $customers->present_address }}</textarea>
                            <span id="present_address-error" class="text-danger"></span>
                        </div>

                        <div class="form-check mb-3">
                            <input type="checkbox" id="same_as_present" class="form-check-input">
                            <label class="form-check-label text-primary" for="same_as_present">
                                Present address same as permanent address
                            </label>
                        </div>

                        <div class="form-group">
                            <label for="permanent_address">Permanent Address <span class="text-danger">*</span></label>
                            <textarea id="permanent_address" name="permanent_address" class="form-control" rows="3">{{ $customers->permanent_address }}</textarea>
                            <span id="permanent_address-error" class="text-danger"></span>
                        </div>

                        <!-- Insurance Details -->
                        <div class="form-group">
                            <label for="insurance_type">Insurance Type <span class="text-danger">*</span></label>
                            <input type="text" id="insurance_type" name="insurance_type" class="form-control" value="{{ $customers->ins_type }}">
                            <span id="insurance_type-error" class="text-danger"></span>
                        </div>

                        <div class="form-group">
                            <label for="case_details">Case Details <span class="text-danger">*</span></label>
                            <textarea id="case_details" name="case_details" class="form-control" rows="3">{{ $customers->case_details }}</textarea>
                            <span id="case_details-error" class="text-danger"></span>
                        </div>

                        <!-- Crime Info -->
                        <div class="form-group">
                            <label for="crime_no">Crime Number <span class="text-danger">*</span></label>
                            <input type="text" id="crime_no" name="crime_no" class="form-control" value="{{ $customers->crime_number }}">
                            <span id="crime_no-error" class="text-danger"></span>
                        </div>

                        <div class="form-group">
                            <label for="police_station">Police Station <span class="text-danger">*</span></label>
                            <input type="text" id="police_station" name="police_station" class="form-control" value="{{ $customers->police_station }}">
                            <span id="police_station-error" class="text-danger"></span>
                        </div>

                        <!-- Policy Details -->
                        <div class="form-group">
                            <label for="policy_no">Policy No <span class="text-danger">*</span></label>
                            <input type="text" id="policy_no" name="policy_no" class="form-control" value="{{ $customers->policy_no }}">
                            <span id="policy_no-error" class="text-danger"></span>
                        </div>

                        <div class="row">
                            <div class="form-group col-md-6">
                                <label for="policy_start">Policy Start Date <span class="text-danger">*</span></label>
                                <input type="date" id="policy_start" name="policy_start" class="form-control" value="{{ $customers->policy_start }}">
                            </div>
                            <div class="form-group col-md-6">
                                <label for="policy_end">Policy End Date</label>
                                <input type="date" id="policy_end" name="policy_end" class="form-control" value="{{ $customers->policy_end }}">
                            </div>
                        </div>

                        <!-- File -->
                        <div class="form-group">
                            <label for="intimation_report">Intimation Report</label>
                            <input type="file" name="intimation_report" class="form-control-file">
                            @if (!empty($customers->intimation_report))
                                <div class="mt-2">
                                    <a href="{{ asset('storage/' . $customers->intimation_report) }}" target="_blank">View Existing Report</a>
                                </div>
                            @endif
                        </div>

                        <!-- Investigation Type -->
                        <div class="form-group">
                            <label for="investigation_type">Investigation Type <span class="text-danger">*</span></label>
                            <select name="investigation_type" id="investigation_type" class="form-control">
                                <option value="OD" {{ $customers->insurance_type == 'OD' ? 'selected' : '' }}>OD</option>
                                <option value="MACT" {{ $customers->insurance_type == 'MACT' ? 'selected' : '' }}>MACT</option>
                            </select>
                        </div>

                        <!-- Executives -->
                        <div class="form-group">
                            <label for="executive_1">Default Executive <span class="text-danger">*</span></label>
                            <select name="Default_Executive" id="executive_1" class="form-control">
                                <option disabled>Select the executive</option>
                                @foreach ($users as $user)
                                    <option value="{{ $user->id }}" {{ $user->id == $customers->meeting_id ? 'selected' : '' }}>
                                        {{ $user->name }} ({{ $user->place }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="row">
                            <div class="form-group col-md-6">
                                <label for="executive_2">Executive (Driver)</label>
                                <select name="executive_driver" class="form-control">
                                    <option disabled>Select the executive</option>
                                    @foreach ($users as $user)
                                        <option value="{{ $user->id }}" {{ $user->id == $customers->driver_id ? 'selected' : '' }}>
                                            {{ $user->name }} ({{ $user->place }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="executive_3">Executive (Garage)</label>
                                <select name="executive_garage" class="form-control">
                                    <option disabled>Select the executive</option>
                                    @foreach ($users as $user)
                                        <option value="{{ $user->id }}" {{ $user->id == $customers->garage_id ? 'selected' : '' }}>
                                            {{ $user->name }} ({{ $user->place }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="row">
                            <div class="form-group col-md-6">
                                <label for="executive_4">Executive (Spot)</label>
                                <select name="executive_spot" class="form-control">
                                    <option disabled>Select the executive</option>
                                    @foreach ($users as $user)
                                        <option value="{{ $user->id }}" {{ $user->id == $customers->spot_id ? 'selected' : '' }}>
                                            {{ $user->name }} ({{ $user->place }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="executive_5">Executive (Meeting)</label>
                                <select name="executive_meeting" class="form-control">
                                    <option disabled>Select the executive</option>
                                    @foreach ($users as $user)
                                        <option value="{{ $user->id }}" {{ $user->id == $customers->meeting_id ? 'selected' : '' }}>
                                            {{ $user->name }} ({{ $user->place }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        @if($customers->case_type=="MACT")
                        <div class="form-group">
                            <label for="executive_6">Executive (Accident Person)</label>
                            <select name="executive_accident_person" class="form-control">
                                <option disabled>Select the executive</option>
                                @foreach ($users as $user)
                                    <option value="{{ $user->id }}" {{ $user->id == $customers->accident_id ? 'selected' : '' }}>
                                        {{ $user->name }} ({{ $user->place }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        @endif

                        <!-- Date -->
                        <div class="form-group">
                            <label for="date">Investigation Date <span class="text-danger">*</span></label>
                            <input type="date" name="date" id="date" class="form-control" value="{{ $customers->case_date }}">
                        </div>

                        <!-- Other -->
                        <div class="form-group">
                            <label for="other">Other Details</label>
                            <textarea name="other" id="other" class="form-control" rows="4">{{ $customers->case_other }}</textarea>
                        </div>
                    </div>

                    <!-- Submit -->
                    <div class="card-footer text-left">
                        <button type="submit" class="btn btn-success">Update Customer</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>




    <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script> -->

@push('styles')
<!-- AdminLTE DataTables CSS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap4.min.css" />
@endpush


<!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> -->


 <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
   
    <script>
       
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

    let form = $(this)[0];
    let formData = new FormData(form);

    $.ajax({
        url: form.action,
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            if (response.success) {
                // $('#successMessage').text(response.success).show();

                $('#successMessage')
                .text(response.success)
                .fadeIn();

                // Scroll to the success message
                $('html, body').animate({
                scrollTop: $('#successMessage').offset().top - 100 // adjust offset as needed
                }, 500);

                $('#insuranceForm')[0].reset(); 
                $('.text-danger').text(''); 
                $('#same_as_phone').prop('checked', false); 
                $('#same_as_present').prop('checked', false); 

                if (response.redirect_url) {
                    setTimeout(function() {
                        window.location.href = response.redirect_url;
                    }, 2000);
                }
            }
        },
        error: function(xhr) {
            $('.text-danger').text(''); // Clear previous error messages

            if (xhr.responseJSON && xhr.responseJSON.errors) {
                $.each(xhr.responseJSON.errors, function(key, value) {
                    $('#' + key + '-error').text(value[0]);
                });
            } else if (xhr.responseJSON && xhr.responseJSON.message) {
                alert("Error: " + xhr.responseJSON.message);
            } else {
                alert("An unknown error occurred.");
                console.log(xhr.responseText);
            }
        }
    });
});


            

    //         $(document).ready(function() {
    //         $('#caseForm').on('submit', function(e) {
    //             e.preventDefault();

    //             $.ajax({
    //                 url: '{{ route('store.case') }}',
    //                 type: 'POST',
    //                 data: $(this).serialize(),
    //                 success: function(response) {
    //                     if (response.success) {
    //                         $('#successMessage').text(response.success).show();
    //                         $('#caseForm')[0].reset(); // Reset form fields
    //                         $('.text-danger').text(''); // Clear previous error messages
    //                     }
    //                 },
    //                 error: function(xhr) {
    //                     var errors = xhr.responseJSON.errors;
    //                     $('.text-danger').text(''); // Clear previous error messages
    //                     $.each(errors, function(key, value) {
    //                         $('#' + key + '-error').text(value);
    //                     });
    //                 }
    //             });
    //         });
    //     });
     
    //     var accident = $('#accident');
    //     var insPerson = <?php echo json_encode($insuranceCustomer); ?>;
    //         if (insPerson.insurance_type === 'MAC') 
    //         {
    //             accident.show();
    //         } else {
    //             accident.hide();
    //         }
    //         $('#sub-executive-group').hide();
    
    // function exe1(){
    //     var insPerson = <?php echo json_encode($insuranceCustomer); ?>;
    //     var exe1=$('#executive_1').val();
    //     var exe2=$('#executive_2');
    //     var exe3=$('#executive_3');
    //     var exe4=$('#executive_4');
    //     var exe5=$('#executive_5');
    //     var exe6=$('#executive_6');
    //     var accident=$('#accident');
    //     console.log(exe1);
    //     if(exe1!=''){
    //     $('#sub-executive-group').show();
    //     exe2.val(exe1);
    //     exe3.val(exe1);
    //     exe4.val(exe1);
    //     exe5.val(exe1);
    //     if (insPerson.insurance_type === 'MAC') 
    //         {
    //             accident.show();
    //             exe6.val(exe1);
    //         } else {
    //             accident.hide();
    //             exe6.val('');
    //         }

    //     }else{
    //         $('#sub-executive-group').hide(); 
    //     }
    // }
        
    </script>
@endsection
