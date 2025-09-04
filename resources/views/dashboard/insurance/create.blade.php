@extends('dashboard.layout.app')
@section('title', 'Add New Insurance Customer')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-end mb-3">
        <button class="btn btn-danger mr-2" onclick="window.history.back()">
            <i class="fas fa-arrow-left"></i>
        </button>
        <button class="btn btn-warning mr-2" onclick="window.location.reload()">
            <i class="fas fa-sync-alt"></i>
        </button>
        <a href="{{ route('case.index') }}" class="btn btn-primary">
            <i class="fas fa-list"></i>
        </a>
    </div>

    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card card-primary shadow-sm">
                <div class="card-header">
                    <h4 class="card-title">Add New Insurance Customer</h4>
                </div>

                <div class="card-body">
                    <div id="successMessage" class="alert alert-success" style="display: none;"></div>

                    <form id="insuranceForm" action="" method="POST" enctype="multipart/form-data" novalidate>
                        @csrf
                        {{-- Company --}}
                        <div class="form-group">
                            <label for="company">Select Company <span class="text-danger">*</span></label>
                            <select id="company" name="company" class="form-control">
                                <option disabled selected>Select Company</option>
                                @foreach ($companies as $company)
                                    <option value="{{ $company->id }}">{{ $company->name }}</option>
                                @endforeach
                            </select>
                            <small id="company-error" class="text-danger"></small>
                        </div>

                        {{-- Name --}}
                        <div class="form-group">
                            <label for="name">Name <span class="text-danger">*</span></label>
                            <input type="text" id="name" name="name" class="form-control" placeholder="Enter name">
                            <small id="name-error" class="text-danger"></small>
                        </div>

                        {{-- Father's Name --}}
                        <div class="form-group">
                            <label for="father_name">Father's Name <span class="text-danger">*</span></label>
                            <input type="text" id="father_name" name="father_name" class="form-control" placeholder="Enter father's name">
                            <small id="father_name-error" class="text-danger"></small>
                        </div>

                        {{-- Phone --}}
                        <div class="form-group">
                            <label for="phone">Phone <span class="text-danger">*</span></label>
                            <input type="text" id="phone" name="phone" class="form-control" placeholder="Enter phone number">
                            <small id="phone-error" class="text-danger"></small>
                        </div>

                        {{-- Checkbox: Emergency contact same as phone --}}
                        <div class="form-check mb-3">
                            <input type="checkbox" id="same_as_phone" class="form-check-input">
                            <label for="same_as_phone" class="form-check-label text-primary">Emergency contact number same as phone</label>
                        </div>

                        {{-- Emergency Contact Number --}}
                        <div class="form-group">
                            <label for="emergency_contact_number">Emergency Contact Number <span class="text-danger">*</span></label>
                            <input type="text" id="emergency_contact_number" name="emergency_contact_number" class="form-control" placeholder="Enter emergency contact number">
                            <small id="emergency_contact_number-error" class="text-danger"></small>
                        </div>

                        {{-- Email --}}
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" id="email" name="email" class="form-control" placeholder="Enter email">
                            <small id="email-error" class="text-danger"></small>
                        </div>

                        {{-- Present Address --}}
                        <div class="form-group">
                            <label for="present_address">Present Address <span class="text-danger">*</span></label>
                            <textarea id="present_address" name="present_address" rows="3" class="form-control" placeholder="Enter present address"></textarea>
                            <small id="present_address-error" class="text-danger"></small>
                        </div>

                        {{-- Checkbox: Present address same as permanent --}}
                        <div class="form-check mb-3">
                            <input type="checkbox" id="same_as_present" class="form-check-input">
                            <label for="same_as_present" class="form-check-label text-primary">Present address same as permanent address</label>
                        </div>

                        {{-- Permanent Address --}}
                        <div class="form-group">
                            <label for="permanent_address">Permanent Address <span class="text-danger">*</span></label>
                            <textarea id="permanent_address" name="permanent_address" rows="3" class="form-control" placeholder="Enter permanent address"></textarea>
                            <small id="permanent_address-error" class="text-danger"></small>
                        </div>

                        {{-- Insurance Type --}}
                        <div class="form-group">
                            <label for="insurance_type">Insurance Type <span class="text-danger">*</span></label>
                            <input type="text" id="insurance_type" name="insurance_type" class="form-control" placeholder="Enter insurance type">
                            <small id="insurance_type-error" class="text-danger"></small>
                        </div>

                        {{-- Case Details --}}
                        <div class="form-group">
                            <label for="case_details">Case Details <span class="text-danger">*</span></label>
                            <textarea id="case_details" name="case_details" rows="3" class="form-control" placeholder="Enter case details"></textarea>
                            <small id="case_details-error" class="text-danger"></small>
                        </div>

                        {{-- Crime Number --}}
                        <div class="form-group">
                            <label for="crime_no">Crime Number <span class="text-danger">*</span></label>
                            <input type="text" id="crime_no" name="crime_no" class="form-control" placeholder="Enter Crime Number">
                            <small id="crime_no-error" class="text-danger"></small>
                        </div>

                        {{-- Police Station --}}
                        <div class="form-group">
                            <label for="police_station">Police Station <span class="text-danger">*</span></label>
                            <input type="text" id="police_station" name="police_station" class="form-control" placeholder="Enter Police Station">
                            <small id="police_station-error" class="text-danger"></small>
                        </div>

                        {{-- Policy No --}}
                        <div class="form-group">
                            <label for="policy_no">Policy No <span class="text-danger">*</span></label>
                            <input type="text" id="policy_no" name="policy_no" class="form-control" placeholder="Policy No">
                            <small id="policy_no-error" class="text-danger"></small>
                        </div>

                        {{-- Policy Start and End Dates --}}
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="policy_start">Policy Start Date <span class="text-danger">*</span></label>
                                <input type="date" id="policy_start" name="policy_start" class="form-control">
                                <small id="policy_start-error" class="text-danger"></small>
                            </div>

                            <div class="form-group col-md-6">
                                <label for="policy_end">Policy End Date</label>
                                <input type="date" id="policy_end" name="policy_end" class="form-control">
                                <small id="policy_end-error" class="text-danger"></small>
                            </div>
                        </div>

                        {{-- Intimation Report --}}
                        <div class="form-group">
                            <label for="intimation_report">Intimation Report</label>
                            <input type="file" id="intimation_report" name="intimation_report" class="form-control-file">
                            <small id="intimation_report-error" class="text-danger"></small>
                        </div>

                        {{-- Investigation Type --}}
                        <div class="form-group">
                            <label for="investigation_type">Investigation Type <span class="text-danger">*</span></label>
                            <select id="investigation_type" name="investigation_type" class="form-control" required>
                                <option disabled selected>Select the investigation type</option>
                                <option value="OD">OD</option>
                                <option value="MACT">MACT</option>
                            </select>
                            <small id="investigation_type-error" class="text-danger"></small>
                        </div>

                        {{-- Default Executive --}}
                        <div class="form-group">
                            <label for="executive_1">Select Default Executive <span class="text-danger">*</span></label>
                            <select id="executive_1" name="Default_Executive" class="form-control" required onchange="exe1()">
                                <option disabled selected>Select the executive</option>
                                @foreach ($users as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->place }})</option>
                                @endforeach
                            </select>
                            <small id="Default_Executive-error" class="text-danger"></small>
                        </div>

                        {{-- Sub Executives Group --}}
                        <div id="sub-executive-group" class="container mb-3">
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="executive_2">Select Executive (Driver)</label>
                                    <select id="executive_2" name="executive_driver" class="form-control">
                                        <option disabled selected>Select the executive</option>
                                        @foreach ($users as $user)
                                            <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->place }})</option>
                                        @endforeach
                                    </select>
                                    <small id="executive_driver-error" class="text-danger"></small>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="executive_3">Select Executive (Garage)</label>
                                    <select id="executive_3" name="executive_garage" class="form-control">
                                        <option disabled selected>Select the executive</option>
                                        @foreach ($users as $user)
                                            <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->place }})</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="executive_4">Select Executive (Spot)</label>
                                    <select id="executive_4" name="executive_spot" class="form-control">
                                        <option disabled selected>Select the executive</option>
                                        @foreach ($users as $user)
                                            <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->place }})</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="executive_5">Select Executive (Meeting)</label>
                                    <select id="executive_5" name="executive_meeting" class="form-control">
                                        <option disabled selected>Select the executive</option>
                                        @foreach ($users as $user)
                                            <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->place }})</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group col-md-6" id="accident">
                                    <label for="executive_6">Select Executive (Accident Person)</label>
                                    <select id="executive_6" name="executive_accident_person" class="form-control">
                                        <option disabled selected>Select the executive</option>
                                        @foreach ($users as $user)
                                            <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->place }})</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group col-md-6">
                                    <label for="date">Select Investigation Date <span class="text-danger">*</span></label>
                                    <input type="date" id="date" name="date" class="form-control" required>
                                    @error('date')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        {{-- Other Details --}}
                        <div class="form-group">
                            <label for="other_details">Other Details</label>
                            <textarea id="other_details" name="other_details" rows="3" class="form-control" placeholder="Enter any other details"></textarea>
                        </div>

                        {{-- Submit --}}
                        <div class="form-group d-flex justify-content-left">
                            <button type="submit" id="submitBtn" class="btn btn-primary btn-lg px-5" style="font-size: 1.2rem;">
                                Submit
                            </button>
                        </div>
                    </form>
                </div>
            </div> <!-- card -->
        </div> <!-- col-md-8 -->
    </div> <!-- row -->
</div> <!-- container-fluid -->



    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>


    @php
    $insPerson = $insuranceCustomer ?? null;
@endphp

<script>
$(document).ready(function() {
    const insPerson = @json($insPerson);

    // 1️⃣ Checkbox logic: copy phone/address values
    $('#same_as_phone').on('change', function() {
        $('#emergency_contact_number').val($(this).is(':checked') ? $('#phone').val() : '');
    });
    $('#same_as_present').on('change', function() {
        $('#permanent_address').val($(this).is(':checked') ? $('#present_address').val() : '');
    });

    // 2️⃣ Initial exec group & accident field visibility
    $('#sub-executive-group').hide();
    if (insPerson && insPerson.insurance_type === 'MAC') {
        $('#accident').show();
    } else {
        $('#accident').hide();
    }

    // 3️⃣ On executive_1 change
    window.exe1 = function() {
        const execVal = $('#executive_1').val();
        const execFields = ['#executive_2','#executive_3','#executive_4','#executive_5','#executive_6'];
        if (execVal) {
            $('#sub-executive-group').show();
            execFields.forEach((sel, index) => {
                if (index < 5) {
                    $(sel).val(execVal);
                }
            });
            if (insPerson && insPerson.insurance_type === 'MAC') {
                $('#accident').show();
                $('#executive_6').val(execVal);
            } else {
                $('#accident').hide();
                $('#executive_6').val('');
            }
        } else {
            $('#sub-executive-group').hide();
        }
    };

    // 4️⃣ AJAX form submit with file support
    $('#insuranceForm').on('submit', function(e) {
        e.preventDefault();
        let formData = new FormData(this);

        $.ajax({
            url: "{{ route('insurance.store') }}",
            type: 'POST',
            data: formData,
            processData: false, // 🚫 Important for FormData
            contentType: false, // 🚫 Important for FormData
            

            success: function(response) {
    if (response.success) {
        // Show success message
        $('#successMessage')
            .text(response.success)
            .fadeIn();

        // Scroll to the success message
        $('html, body').animate({
            scrollTop: $('#successMessage').offset().top - 100 // adjust offset as needed
        }, 500);

        // Reset form and clear error messages
        $('#insuranceForm')[0].reset();
        $('.text-danger').text('');

        // Uncheck checkboxes
        setTimeout(() => {
            $('#same_as_phone').prop('checked', false);
            $('#same_as_present').prop('checked', false);
        }, 10);

        // Reload the page after 5 seconds
        // setTimeout(() => {
        //     location.reload();
        // }, 5000);

        setTimeout(function() {
        // Redirect to the URL sent from backend
        window.location.href = response.redirect_url;
        }, 3000);

    }
},

            error: function(xhr) {
                const errors = xhr.responseJSON?.errors || {};
                $('.text-danger').text('');

                $.each(errors, (key, val) => {
                    $('#' + key + '-error').text(val[0] || val);
                });
            }
        });
    });
});
</script>


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


            // $('#insuranceForm').on('submit', function(e) 
            // {
            // e.preventDefault();

            // let formData = new FormData(this);

            // $.ajax({
            // url: "{{ route('insurance.store') }}",
            // type: 'POST',
            // data: formData,
            // success: function(response) {
            // if (response.success) {
            // $('#successMessage').text(response.success).show();
            // $('#insuranceForm')[0].reset(); // Reset form fields
            // $('.text-danger').text(''); // Clear previous error messages
            // $('#same_as_phone').prop('checked', false); // Uncheck the checkbox
            // $('#same_as_present').prop('checked', false); // Uncheck the checkbox
            // }
            // },
            // error: function(xhr) {
            // var errors = xhr.responseJSON.errors;
            // $('.text-danger').text(''); // Clear previous error messages
            // $.each(errors, function(key, value) {
            // $('#' + key + '-error').text(value);
            // });
            // }
            // });
            // });
            });


            // $(document).ready(function () {
            // $('#insuranceForm').on('submit', function (e) {
            // e.preventDefault();

            // // Create FormData object for file + form support
            // let formData = new FormData(this);

            // $.ajax({
            // url: "{{ route('insurance.store') }}",
            // type: 'POST',
            // data: formData,
            // processData: false,  // Don't process into query string
            // contentType: false,  // Let browser set correct multipart/form-data headers
            // success: function (response) {
            //     if (response.success) {
            //         $('#successMessage').text(response.success).show();
            //         $('#insuranceForm')[0].reset(); // Reset form fields
            //         $('.text-danger').text(''); // Clear previous error messages

            //         // Delay to override reset effect
            //         setTimeout(() => {
            //             $('#same_as_phone').prop('checked', false);
            //             $('#same_as_present').prop('checked', false);
            //         }, 10);
            //     }
            // },
            // error: function (xhr) {
            //     var errors = xhr.responseJSON.errors;
            //     $('.text-danger').text(''); // Clear previous error messages
            //     $.each(errors, function (key, value) {
            //         $('#' + key + '-error').text(value[0]);
            //     });
            // }
            // });
            // });
            // });

            

        // $(document).ready(function () {
        // $('#caseForm').on('submit', function (e) {
        // e.preventDefault();

        // let formData = new FormData(this); // 🔁 Use FormData

        // $.ajax({
        // url: '{{ route('store.case') }}',
        // type: 'POST',
        // data: formData,
        // processData: false,  // Required for file upload
        // contentType: false,  // Required for file upload
        // success: function (response) {
        // if (response.success) {
        // $('#successMessage').text(response.success).fadeIn();

        // $('#caseForm')[0].reset();  // Reset form
        // $('.text-danger').text('');

        // setTimeout(function () {
        //     $('#successMessage').fadeOut();
        //     location.reload(); // Reload the page
        // }, 8000);
        // }
        // },
        // error: function (xhr) {
        // let errors = xhr.responseJSON.errors;
        // $('.text-danger').text('');
        // $.each(errors, function (key, value) {
        // $('#' + key + '-error').text(value[0]);
        // });
        // }
        // });
        // });
        // });





            // $('#insuranceForm').on('submit', function(e) 
            // {
            // e.preventDefault();

            // let formData = new FormData(this); // ⚠️ this captures files as well

            // $.ajax({
            // url: "{{ route('insurance.store') }}",
            // type: 'POST',
            // data: formData,
            // processData: false,   // ⛔ Don't process data
            // contentType: false,   // ⛔ Don't set content type
            // success: function(response) {
            // if (response.success) {
            // $('#successMessage').text(response.success).show();
            // $('#insuranceForm')[0].reset(); 
            // $('.text-danger').text('');
            // $('#same_as_phone').prop('checked', false);
            // $('#same_as_present').prop('checked', false);
            // }
            // },
            // error: function(xhr) {
            // var errors = xhr.responseJSON.errors;
            // $('.text-danger').text('');
            // $.each(errors, function(key, value) {
            // $('#' + key + '-error').text(value);
            // });
            // }
            // });
            // });


        // $(document).ready(function() {
        //     $('#caseForm').on('submit', function(e) {
        //         e.preventDefault();

        //         $.ajax({
        //             url: '{{ route('store.case') }}',
        //             type: 'POST',
        //             data: $(this).serialize(),
        //             success: function(response) {
        //                 if (response.success) {
        //                     $('#successMessage').text(response.success).show();
        //                     $('#caseForm')[0].reset(); // Reset form fields
        //                     $('.text-danger').text(''); // Clear previous error messages
        //                 }
        //             },
        //             error: function(xhr) {
        //                 var errors = xhr.responseJSON.errors;
        //                 $('.text-danger').text(''); // Clear previous error messages
        //                 $.each(errors, function(key, value) {
        //                     $('#' + key + '-error').text(value);
        //                 });
        //             }
        //         });
        //     });
        // });
     
//         var accident = $('#accident');
//         var insPerson = <?php echo json_encode($insuranceCustomer); ?>;
//             if (insPerson.insurance_type === 'MAC') 
//             {
//                 accident.show();
//             } else {
//                 accident.hide();
//             }
//             $('#sub-executive-group').hide();
    
//     function exe1(){
//         var insPerson = <?php echo json_encode($insuranceCustomer); ?>;
//         var exe1=$('#executive_1').val();
//         var exe2=$('#executive_2');
//         var exe3=$('#executive_3');
//         var exe4=$('#executive_4');
//         var exe5=$('#executive_5');
//         var exe6=$('#executive_6');
//         var accident=$('#accident');
//         console.log(exe1);
//         if(exe1!=''){
//         $('#sub-executive-group').show();
//         exe2.val(exe1);
//         exe3.val(exe1);
//         exe4.val(exe1);
//         exe5.val(exe1);
//         if (insPerson.insurance_type === 'MAC') 
//             {
//                 accident.show();
//                 exe6.val(exe1);
//             } else {
//                 accident.hide();
//                 exe6.val('');
//             }

//         }else{
//             $('#sub-executive-group').hide(); 
//         }
//   }
    </script>
@endsection
