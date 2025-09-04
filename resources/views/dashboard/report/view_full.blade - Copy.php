@extends('dashboard.layout.app')
@section('title', 'Report Request Detail')
@section('content')
<div class="container-fluid">
    <div class="row align-items-center mb-3">
        <!-- Heading -->
        <div class="col-md-6">
            <h3 class="mb-0">Report Details for {{ $report->customer_name }}</h3>
        </div>

        <!-- Search Form -->
        <div class="col-md-6">
            <form action="" method="GET" class="row gx-2 justify-content-end">
                <div class="col-auto">
                    <input type="text" name="search" placeholder="Search..." value="" class="form-control">
                </div>
                <div class="col-auto">
                    <button type="submit" class="btn btn-primary">Search</button>
                </div>       
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h5>General Information</h5>
        </div>
        <div class="card-body">
            <p><strong>Date:</strong> {{ $report->date }}</p>
            <p><strong>Type:</strong> {{ $report->type }}</p>
        </div>
    </div>

    <hr>

    @if (session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
    @endif


    @if (session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
    @endif

        <h5>Garage Data</h5>
        @if ($report->garage_reassign_status == 1)
        <form action="{{ route('garage.re.assign') }}" method="POST" class="ajax-form">
            @csrf
            <input type="hidden" name="id" required value="{{ $report->id }}">
            <button type="submit" class="btn btn-danger">Re-Assign</button>
        </form>
        @endif
        @if ($report->garage_reassign_status == 0)
        <span class="badge badge-danger">Pending</span>
        @endif

      
            @foreach ($garageData as $index => $garage)
            <div class="card mb-3">
            <div class="card-body">
            <div class='row'>
            <div class="col-md-8"></div>
            <div class="col-md-2">
            <a class='btn btn-danger' href="{{url('/garage/'.$garage->id)}}">Authorize Upload data</a>
            </div>
            <div class="col-md-2">
            <button class="btn btn-primary" onclick="editFieldGarage({{$garage->id}})">Edit Garage Data</button></p>
            </div>
            </div>

            <!-- Checkbox above each entry -->

            <div class="form-check">
                <input class="form-check-input" type="radio" id="selectGarage{{ $garage->id }}" name="selectGarage" value="{{ $garage->id }}-{{$report->case_id }}">
                <label class="form-check-label" for="selectGarage{{ $garage->id }}">
                    Select this garage data
                </label>
            </div>

            <h6>Entry {{ $index + 1 }}</h6>
            
            <!-- <p><strong>Garage Gate Entry:</strong> @foreach($garageData as $garage)
                {{ $garage->garage_gate_entry }}@if (!$loop->last), @endif
            @endforeach</p> -->

        <form method="POST" action="{{ route('garage.text.update_new') }}" enctype="multipart/form-data">
        @csrf

        {{-- Loop through each Question --}}
        @foreach ($garageQuestions as $qIndex => $question)
        @php
        $columnName = $question->column_name;
        $inputType = $question->input_type;
        @endphp

        <div class="mb-2 p-1">
        <div class="d-flex align-items-start flex-wrap mb-2">
        <div class="me-4" style="min-width: 200px;">
        <strong>{{ $question->question }}:</strong>
        </div>

        {{-- Radio buttons for each garage --}}
        @foreach ($garageData as $garage)
        @php
        $value = $garage->$columnName ?? 'N/A';
        $radioId = "radio_{$columnName}_{$garage->id}";
        @endphp

        <div class="form-check form-check-inline me-3">
        <input type="radio"
        class="form-check-input toggle-edit"
        name="selected_field"
        value="{{ $columnName }}:{{ $garage->id }}"
        id="{{ $radioId }}"
        data-target="#fieldWrapper_{{ $columnName }}_{{ $garage->id }}">
        <label class="form-check-label" for="{{ $radioId }}">
        {{ $value }}
        </label>
        </div>
        @endforeach
        </div>

        {{-- Editable input for each garage field (hidden until selected) --}}
        @foreach ($garageData as $garage)
        @php
        $value = $garage->$columnName ?? '';
        $wrapperId = "fieldWrapper_{$columnName}_{$garage->id}";
        @endphp

        <div class="d-none field-wrapper" id="{{ $wrapperId }}">
        @if ($inputType === 'textarea')
        <textarea name="field_value[{{ $garage->id }}][{{ $columnName }}]" class="form-control mb-2">{{ $value }}</textarea>

        @elseif ($inputType === 'file')
        @if ($value)
        <p>
            <a href="{{ asset('storage/' . $value) }}" target="_blank">View Existing File</a>
        </p>
        @endif
        <input type="file" name="field_value[{{ $garage->id }}][{{ $columnName }}]" class="form-control mb-2">

        @elseif ($inputType === 'select')
        <select name="field_value[{{ $garage->id }}][{{ $columnName }}]" class="form-select mb-2">
        <option value="Yes" {{ $value == 'Yes' ? 'selected' : '' }}>Yes</option>
        <option value="No" {{ $value == 'No' ? 'selected' : '' }}>No</option>
        </select>

        @else
        <input type="{{ $inputType }}" name="field_value[{{ $garage->id }}][{{ $columnName }}]" value="{{ $value }}" class="form-control mb-2">
        @endif

        <button type="submit" class="btn btn-sm btn-primary">Update</button>
        </div>
        @endforeach
        </div>
        @endforeach
        </form>

        

        <form method="POST" action="{{ route('garage.text.update_new') }}">
        @csrf

        <div class="row align-items-center">
        <div class="col-md-2">
        <p class="mb-0"><strong>Garage Gate Entry:</strong></p>
        </div>

        <div class="col-md-10">
        <div class="row">
        @foreach($garageData as $index => $garage)
        <div class="col-md-3 mb-2">
        <div class="form-check d-flex align-items-center">
        <input class="form-check-input me-2 toggle-gate" type="radio" name="selected_gate" value="{{ $garage->id }}" data-input-id="gateInput{{ $index }}" id="gateCheck{{ $index }}">
        <label class="form-check-label me-2" for="gateCheck{{ $index }}">
        {{ $garage->garage_gate_entry }}
        </label>
        <input type="text" id="gateInput{{ $index }}" name="garage_gate_entry[{{ $garage->id }}]" class="form-control form-control-sm garage-input-gate d-none" value="{{ $garage->garage_gate_entry }}">
        </div>
        </div>
        @endforeach
        </div>
        </div>
        </div>

        <div class="mt-3 d-none" id="updateGateButtonWrapper">
        <button type="submit" class="btn btn-success">Update Garage Gate Entry</button>
        </div>
        </form>


<!-- JavaScript to handle radio toggle and input visibility -->

            <!-- <p><strong>Garage Job Card:</strong> {{ $garage->garage_job_card }}</p> -->

            <form method="POST" action="{{ route('garage.text.update_new') }}">
            @csrf

            <div class="row align-items-center">
            <div class="col-md-2">
            <p class="mb-0"><strong>Garage Job Card:</strong></p>
            </div>

            <div class="col-md-10">
            <div class="row">
            @foreach($garageData as $index => $garage)
            <div class="col-md-3 mb-2">
            <div class="form-check d-flex align-items-center">
            <input class="form-check-input me-2 toggle-job" type="radio" name="selected_job" value="{{ $garage->id }}" data-input-id="jobInput{{ $index }}" id="jobCheck{{ $index }}">
            <label class="form-check-label me-2" for="jobCheck{{ $index }}">
            {{ $garage->garage_job_card }}
            </label>
            <input type="text" id="jobInput{{ $index }}" name="garage_job_card[{{ $garage->id }}]" class="form-control form-control-sm garage-input-job d-none" value="{{ $garage->garage_job_card }}">
            </div>
            </div>
            @endforeach
            </div>
            </div>
            </div>

            <div class="mt-3 d-none" id="updateJobButtonWrapper">
            <button type="submit" class="btn btn-success">Update Garage Job Card</button>
            </div>
            </form>


            <p><strong>Executive Name:</strong> {{ $garage->executive_name }}</p>
            <!-- <p><strong>Is Fitness Particular Collected:</strong> {{ $garage->is_fitness_particular_collected ? 'Yes' : 'No' }}</p> -->


            <form method="POST" action="{{ route('garage.text.update_new') }}">
            @csrf

            <div class="row align-items-center">
            <div class="col-md-2">
            <p class="mb-0"><strong>Is Fitness Particular Collected:</strong></p>
            </div>

            <div class="col-md-10">
            <div class="row">
            @foreach($garageData as $index => $garage)
            <div class="col-md-3 mb-2">
            <div class="form-check d-flex align-items-center">
            <input class="form-check-input me-2 toggle-fitness" type="radio" name="selected_fitness" value="{{ $garage->id }}" data-input-id="jobInput{{ $index }}" id="fitnessCheck{{ $index }}">
            <label class="form-check-label me-2" for="fitnessCheck{{ $index }}">
            {{ $garage->is_fitness_particular_collected }}
            </label>

            {{-- Select input for fitness collection (hidden until selected) --}}
            <select id="fitnessSelect{{ $index }}"
            name="is_fitness_particular_collected[{{ $garage->id }}]"
            class="form-select form-select-sm garage-select-fitness d-none">
            <option value="1" {{ $garage->is_fitness_particular_collected ? 'selected' : '' }}>Yes</option>
            <option value="0" {{ !$garage->is_fitness_particular_collected ? 'selected' : '' }}>No</option>
            </select>
            </div>
            </div>
            @endforeach
            </div>
            </div>
            </div>

            {{-- Submit Button --}}
            <div class="mt-3 d-none" id="fitnessButton">
            <button type="submit" class="btn btn-success">Update Garage</button>
            </div>
            </form>



            <!-- <p><strong>Is Permit Particular Collected:</strong> {{ $garage->is_permit_particular_collected ? 'Yes' : 'No' }}</p> -->


            <form method="POST" action="{{ route('garage.text.update_new') }}">
            @csrf

            <div class="row align-items-center">
            <div class="col-md-2">
            <p class="mb-0"><strong>Is Permit Particular Collected:</strong></p>
            </div>

            <div class="col-md-10">
            <div class="row">
            @foreach($garageData as $index => $garage)
            <div class="col-md-3 mb-2">
            <div class="form-check d-flex align-items-center">
            <input class="form-check-input me-2 toggle-permit" type="radio" name="selected_permits" value="{{ $garage->id }}" data-input-id="jobInput{{ $index }}" id="permitCheck{{ $index }}">
            <label class="form-check-label me-2" for="permitCheck{{ $index }}">
            {{ $garage->is_permit_particular_collected }}
            </label>

            {{-- Select input for fitness collection (hidden until selected) --}}
            <select id="permitSelect{{ $index }}"
            name="is_permit_particular_collected[{{ $garage->id }}]"
            class="form-select form-select-sm garage-select-permit d-none">
            <option value="1" {{ $garage->is_permit_particular_collected ? 'selected' : '' }}>Yes</option>
            <option value="0" {{ !$garage->is_permit_particular_collected ? 'selected' : '' }}>No</option>
            </select>
            </div>
            </div>
            @endforeach
            </div>
            </div>
            </div>

            {{-- Submit Button --}}
            <div class="mt-3 d-none" id="permitButton">
            <button type="submit" class="btn btn-success">Update Garage</button>
            </div>
            </form>


            <form method="POST" action="{{ route('garage.text.update_new') }}">
            @csrf

            <div class="row align-items-center">
            <div class="col-md-2">
            <p class="mb-0"><strong>Is DL Particular Collected:</strong></p>
            </div>

            <div class="col-md-10">
            <div class="row">
            @foreach($garageData as $index => $garage)
            <div class="col-md-3 mb-2">
            <div class="form-check d-flex align-items-center">
            <input class="form-check-input me-2 toggle-particular" type="radio" name="selected_particulars" value="{{ $garage->id }}" data-input-id="jobInput{{ $index }}" id="particularCheck{{ $index }}">
            <label class="form-check-label me-2" for="particularCheck{{ $index }}">
            {{ $garage->is_dl_particular_collected }}
            </label>

            {{-- Select input for fitness collection (hidden until selected) --}}
            <select id="particularSelect{{ $index }}"
            name="is_dl_particular_collected[{{ $garage->id }}]"
            class="form-select form-select-sm garage-select-particular d-none">
            <option value="1" {{ $garage->is_dl_particular_collected ? 'selected' : '' }}>Yes</option>
            <option value="0" {{ !$garage->is_dl_particular_collected ? 'selected' : '' }}>No</option>
            </select>
            </div>
            </div>
            @endforeach
            </div>
            </div>
            </div>

            {{-- Submit Button --}}
            <div class="mt-3 d-none" id="particularButton">
            <button type="submit" class="btn btn-success">Update Garage</button>
            </div>
            </form>


            <!-- <p><strong>Is DL Particular Collected:</strong> {{ $garage->is_dl_particular_collected ? 'Yes' : 'No' }}</p> -->
            <!-- <p><strong>Is Any Motor Occurrence:</strong> {{ $garage->is_any_motor_occurance ? 'Yes' : 'No' }}</p> -->

            <form method="POST" action="{{ route('garage.text.update_new') }}">
            @csrf

            <div class="row align-items-center">
            <div class="col-md-2">
            <p class="mb-0"><strong>Is Any Motor Occurrence:</strong></p>
            </div>

            <div class="col-md-10">
            <div class="row">
            @foreach($garageData as $index => $garage)
            <div class="col-md-3 mb-2">
            <div class="form-check d-flex align-items-center">
            <input class="form-check-input me-2 toggle-motor" type="radio" name="selected_motors" value="{{ $garage->id }}" data-input-id="jobInput{{ $index }}" 
            id="motorCheck{{ $index }}">
            <label class="form-check-label me-2" for="motorCheck{{ $index }}">
            {{ $garage->is_any_motor_occurance }}
            </label>

            {{-- Select input for fitness collection (hidden until selected) --}}
            <select id="motorSelect{{ $index }}"
            name="is_any_motor_occurance[{{ $garage->id }}]"
            class="form-select form-select-sm garage-select-motor d-none">
            <option value="1" {{ $garage->is_any_motor_occurance ? 'selected' : '' }}>Yes</option>
            <option value="0" {{ !$garage->is_any_motor_occurance ? 'selected' : '' }}>No</option>
            </select>
            </div>
            </div>
            @endforeach
            </div>
            </div>
            </div>

            {{-- Submit Button --}}
            <div class="mt-3 d-none" id="motorButton">
            <button type="submit" class="btn btn-success">Update Garage</button>
            </div>
            </form>


            <p><strong>No Vehicle Involved in Accident:</strong> {{ $garage->no_vehicle_involved_acident }}</p>
            <p><strong>Damage Consist with Accident:</strong> {{ $garage->damage_consist_with_accident }}</p>
            <p><strong>Victim Occupant or Pillion:</strong> {{ $garage->victim_occupant_or_pillion == 1 ? 'Occupant' : 'Pillion' }}</p>
            <p><strong>Victim Employee Insured:</strong> {{ $garage->victim_employee_insured == 1 ? 'Yes' : 'No' }}</p>
            <p><strong>Victim Owner or Employee:</strong> {{ $garage->victim_owner_or_employee == 1 ? 'Owner' : 'Employee' }}</p>
            <p><strong>Victim Travel on Load Body:</strong> {{ $garage->victim_travel_on_load_body ? 'Yes' : 'No' }}</p>
            <p><strong>Vehicle Involved in Other Accident:</strong> {{ $garage->vehicle_involve_other_accident ? 'Yes' : 'No' }}</p>
            <p><strong>Gross Vehicle Weight:</strong> {{ $garage->gross_vehicle_weight }}</p>
            <p><strong>Date Vehicle Registered:</strong> {{ $garage->date_vehicle_reg }}</p>
            <p><strong>Vehicle Registration Validity:</strong> {{ $garage->vehicle_reg_validity }}</p>
            <p><strong>Issuing Authority Vehicle:</strong> {{ $garage->issuing_authority_vehicle }}</p>
            <p><strong>Fitness Validity Opposite Vehicle:</strong> {{ $garage->fitness_validity_opposite_vehicle }}</p>
            <p><strong>Fitness Validity Vehicle:</strong> {{ $garage->fitness_validity_vehicle }}</p>
            <p><strong>Permit No Vehicle:</strong> {{ $garage->permit_no_vehicle }}</p>
            <p><strong>Permit No Opposite Vehicle:</strong> {{ $garage->permit_no_opposit_vehicle }}</p>
            <p><strong>Issuing Authority Opposite Vehicle:</strong> {{ $garage->issuing_authority__opposite_vehicle }}</p>
            <p><strong>Type Permit Vehicle:</strong> {{ $garage->type_permit_vehicle }}</p>
            <p><strong>Type Permit Opposite Vehicle:</strong> {{ $garage->type_permit_opposite_vehicle }}</p>
            <p><strong>Authorized Route Vehicle:</strong> {{ $garage->authorized_route_vehicle }}</p>
            <p><strong>Authorized Route Opposite Vehicle:</strong> {{ $garage->authorized_route_opposit_vehicle }}</p>
            <p><strong>Permit Validity Vehicle:</strong> {{ $garage->permit_validity_vehicle }}</p>
            <p><strong>Permit Validity Opposite Vehicle:</strong> {{ $garage->permit_validity_opposite_vehicle }}</p>
            <p><strong>Is Break In:</strong>@if($garage->is_break_in==1) Yes @else No @endif </p>
            <p><strong>Break In Inspection Date:</strong> {{ $garage->break_in_ins_date }}</p>
            <p><strong>Odometer Reading at Break In:</strong> {{ $garage->odometer_readng_break_in }}</p>
            <p><strong>Any Discrepancy Found at Break In:</strong> @if($garage->is_any_discre_found_break_in ==1) Yes @else No @endif</p>
            <p><strong>Is Accused Eligible to Drive:</strong> @if($garage->is_accused_elgible_drive) Yes @else No @endif</p>
            <p><strong>Travel From To:</strong> {{ $garage->travel_from_to }}</p>
            <p><strong>Clue About Accident:</strong> {{ $garage->clue_aout_accident }}</p>
            <p><strong>Car Scrap Found on Spot:</strong>@if($garage->car_scrap_found_on_spot==1) Yes @else No @endif</p>
            <p><strong>Garage Name:</strong> {{ $garage->garage_name }}</p>
            <p><strong>Odometer Reading from Service History:</strong> {{ $garage->odometer_reading_service_hist }}</p>
            <p><strong>Third Party Vehicle Details:</strong> {{ $garage->tp_vehicle_details }}</p>
            <p><strong>Any Discrepancy in Garage Entry Job Card:</strong>@if($garage->is_any_disc_in_garage_entry_job_crd==1) Yes @else No @endif</p>
            <p><strong>Investigation Referral Reason 1:</strong> {{ $garage->investi_referal_reson_1 }}</p>
            <p><strong>Referral Findings 1:</strong> {{ $garage->referal_findings_1 }}</p>
            <p><strong>Investigation Referral Reason 2:</strong> {{ $garage->investi_referal_rason2 }}</p>
            <p><strong>Referral Findings 2:</strong> {{ $garage->referal_findings_2 }}</p>

            <h5>Download Data Section</h5>

            @if(isset($garage->garage_downloads) )

            @foreach(json_decode($garage->garage_downloads) as $file)
            @php

            $extension = pathinfo($file, PATHINFO_EXTENSION);

            $label = '';
            switch (strtolower($extension)) {
            case 'jpg':
            case 'jpeg':
            case 'png':
            case 'gif':
            case 'svg':
            $label = 'garage image';
            break;
            case 'pdf':
            case 'zip':
            $label = 'garage report';
            break;
            case 'aac':
            case 'mp3':
            case 'wav':
            $label = 'garage voice';
            break;
            case 'mp4':
            case 'avi':
            case 'mov':
            case 'wmv':
            case 'mkv':
            $label = 'garage video file';
            break;
            default:
            $label = 'unknown file';
            }
            @endphp

            <ul>
            <li>
            <a href="{{ asset('storage/' . $file) }}" target="_blank" download>{{ $label }}</a>
            </li>
            </ul>

            @endforeach
            @else
            <p>No files available for download.</p>
            @endif

          
            <div id="edit-form-garage{{$garage->id}}" style="display:none;">
                <h3 class="text-center">Update Garage Data</h3>
                <form method="POST" action="{{ route('garage.text.update', $garage->id) }}" class="p-4 border rounded bg-light">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label for="driver-name" class="form-label">Garage Gate Entry:</label>
                        <input type="text" name="garage_gate_entry" id="driver-name-input" class="form-control" value="{{ $garage->garage_gate_entry }}">
                    </div>


                    <div class="mb-3">
                        <label for="vehicle-type" class="form-label">Garage Job Card:</label>
                        <input type="text" name="garage_job_card" id="vehicle-type-input" class="form-control" value="{{ $garage->garage_job_card }}">
                    </div>

                    <div class="mb-3">
                        <label for="is_fitness_particular_collected" class="form-label">Is Fitness Particular Collected:</label>
                        <select name="is_fitness_particular_collected" id="is_fitness_particular_collected" class="form-select">
                            <option value="1" {{ $garage->is_fitness_particular_collected == 1 ? 'selected' : '' }}>Yes</option>
                            <option value="0" {{ $garage->is_fitness_particular_collected == 0 ? 'selected' : '' }}>No</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="is_permit_particular_collected" class="form-label">Is Permit Particular Collected:</label>
                        <select name="is_permit_particular_collected" id="is_permit_particular_collected" class="form-select">
                            <option value="1" {{ $garage->is_permit_particular_collected == 1 ? 'selected' : '' }}>Yes</option>
                            <option value="0" {{ $garage->is_permit_particular_collected == 0 ? 'selected' : '' }}>No</option>
                        </select>
                    </div>
                                                                                                                                                                                                                               
                    <div class="mb-3">
                        <label for="is_dl_particular_collected" class="form-label">Is DL Particular Collected:</label>
                        <select name="is_dl_particular_collected" id="is_dl_particular_collected" class="form-select">
                            <option value="1" {{ $garage->is_dl_particular_collected == 1 ? 'selected' : '' }}>Yes</option>
                            <option value="0" {{ $garage->is_dl_particular_collected == 0 ? 'selected' : '' }}>No</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="is_any_motor_occurance" class="form-label">Is Any Motor Occurrence:</label>
                        <select name="is_any_motor_occurance" id="is_any_motor_occurance" class="form-select">
                            <option value="1" {{ $garage->is_any_motor_occurance == 1 ? 'selected' : '' }}>Yes</option>
                            <option value="0" {{ $garage->is_any_motor_occurance == 0 ? 'selected' : '' }}>No</option>
                        </select>
                    </div>


                    <div class="mb-3">
                        <label for="no_vehicle_involved_acident" class="form-label">No Vehicle Involved Accident:</label>
                        <input type="text" name="no_vehicle_involved_acident" id="no_vehicle_involved_acident" class="form-control" {{ $garage->no_vehicle_involved_acident  }}>
                    </div>

                    <div class="mb-3">
                        <label for="damage_consist_with_accident" class="form-label">Damage Consist with Accident:</label>
                        <input type="text" name="damage_consist_with_accident" id="damage_consist_with_accident" class="form-control" {{ $garage->damage_consist_with_accident  }}>
                    </div>

                    <div class="mb-3">
                        <label for="victum_occupant_or_pillion" class="form-label">Victim Occupant or Pillion:</label>
                        <select name="victum_occupant_or_pillion" id="victum_occupant_or_pillion" class="form-select">
                            <option value="1" {{ $garage->victim_occupant_or_pillion == 1 ? 'selected' : '' }}>Occupant</option>
                            <option value="0" {{ $garage->victim_occupant_or_pillion == 0 ? 'selected' : '' }}>Pillion</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="victim_employee_insured" class="form-label">Victim Employee of Insured Perason:</label>
                        <select name="victim_employee_insured" id="victim_employee_insured" class="form-select">
                            <option value="1" {{ $garage->victim_employee_insured == 1 ? 'selected' : '' }}>Yes</option>
                            <option value="0" {{ $garage->victim_employee_insured == 0 ? 'selected' : '' }}>No</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="victim_owner_or_employee" class="form-label">Victim Owner or Employee:</label>
                        <select name="victim_owner_or_employee" id="victim_owner_or_employee" class="form-select">
                            <option value="1" {{ $garage->victim_owner_or_employee == 1 ? 'selected' : '' }}>Owner</option>
                            <option value="0" {{ $garage->victim_owner_or_employee == 0 ? 'selected' : '' }}>Employee</option>
                        </select>
                    </div>


                    <div class="mb-3">
                        <label for="victim_travel_on_load_body" class="form-label">Victim Travel on Load Body:</label>
                        <input type="checkbox" name="victim_travel_on_load_body" id="victim_travel_on_load_body" class="form-check-input" value="1" {{ $garage->victim_travel_on_load_body ? 'checked' : '' }}>
                    </div>

                    <div class="mb-3">
                        <label for="vehicle_involve_other_accident" class="form-label">Vehicle Involved in Other Accident:</label>
                        <input type="checkbox" name="vehicle_involve_other_accident" id="vehicle_involve_other_accident" class="form-check-input" value="1" {{ $garage->vehicle_involve_other_accident ? 'checked' : '' }}>
                    </div>

                    <div class="mb-3">
                        <label for="gross_vehicle_weight" class="form-label">Gross Vehicle Weight:</label>
                        <input type="text" name="gross_vehicle_weight" id="gross_vehicle_weight" class="form-control" value="{{ $garage->gross_vehicle_weight }}">
                    </div>

                    <div class="mb-3">
                        <label for="date_vehicle_reg" class="form-label">Date Vehicle Registered:</label>
                        <input type="date" name="date_vehicle_reg" id="date_vehicle_reg" class="form-control" value="{{ $garage->date_vehicle_reg }}">
                    </div>

                    <div class="mb-3">
                        <label for="vehicle_reg_validity" class="form-label">Vehicle Registration Validity:</label>
                        <input type="date" name="vehicle_reg_validity" id="vehicle_reg_validity" class="form-control" value="{{ $garage->vehicle_reg_validity }}">
                    </div>

                    <div class="mb-3">
                        <label for="issuing_authority_vehicle" class="form-label">Issuing Authority Vehicle:</label>
                        <input type="text" name="issuing_authority_vehicle" id="issuing_authority_vehicle" class="form-control" value="{{ $garage->issuing_authority_vehicle }}">
                    </div>

                    <div class="mb-3">
                        <label for="fitness_validity_opposite_vehicle" class="form-label">Fitness Validity Opposite Vehicle:</label>
                        <input type="date" name="fitness_validity_opposite_vehicle" id="fitness_validity_opposite_vehicle" class="form-control" value="{{ $garage->fitness_validity_opposite_vehicle }}">
                    </div>

                    <div class="mb-3">
                        <label for="fitness_validity_vehicle" class="form-label">Fitness Validity Vehicle:</label>
                        <input type="date" name="fitness_validity_vehicle" id="fitness_validity_vehicle" class="form-control" value="{{ $garage->fitness_validity_vehicle }}">
                    </div>

                    <div class="mb-3">
                        <label for="permit_no_vehicle" class="form-label">Permit No Vehicle:</label>
                        <input type="text" name="permit_no_vehicle" id="permit_no_vehicle" class="form-control" value="{{ $garage->permit_no_vehicle }}">
                    </div>

                    <div class="mb-3">
                        <label for="permit_no_opposit_vehicle" class="form-label">Permit No Opposite Vehicle:</label>
                        <input type="text" name="permit_no_opposit_vehicle" id="permit_no_opposit_vehicle" class="form-control" value="{{ $garage->permit_no_opposit_vehicle }}">
                    </div>

                    <div class="mb-3">
                        <label for="issuing_authority__opposite_vehicle" class="form-label">Issuing Authority Opposite Vehicle:</label>
                        <input type="text" name="issuing_authority__opposite_vehicle" id="issuing_authority__opposite_vehicle" class="form-control" value="{{ $garage->issuing_authority__opposite_vehicle }}">
                    </div>

                    <div class="mb-3">
                        <label for="type_permit_vehicle" class="form-label">Type Permit Vehicle:</label>
                        <input type="text" name="type_permit_vehicle" id="type_permit_vehicle" class="form-control" value="{{ $garage->type_permit_vehicle }}">
                    </div>

                    <div class="mb-3">
                        <label for="type_permit_opposite_vehicle" class="form-label">Type Permit Opposite Vehicle:</label>
                        <input type="text" name="type_permit_opposite_vehicle" id="type_permit_opposite_vehicle" class="form-control" value="{{ $garage->type_permit_opposite_vehicle }}">
                    </div>

                    <div class="mb-3">
                        <label for="authorized_route_vehicle" class="form-label">Authorized Route Vehicle:</label>
                        <input type="text" name="authorized_route_vehicle" id="authorized_route_vehicle" class="form-control" value="{{ $garage->authorized_route_vehicle }}">
                    </div>

                    <div class="mb-3">
                        <label for="authorized_route_opposit_vehicle" class="form-label">Authorized Route Opposite Vehicle:</label>
                        <input type="text" name="authorized_route_opposit_vehicle" id="authorized_route_opposit_vehicle" class="form-control" value="{{ $garage->authorized_route_opposit_vehicle }}">
                    </div>

                    <div class="mb-3">
                        <label for="permit_validity_vehicle" class="form-label">Permit Validity Vehicle:</label>
                        <input type="date" name="permit_validity_vehicle" id="permit_validity_vehicle" class="form-control" value="{{ $garage->permit_validity_vehicle }}">
                    </div>

                    <div class="mb-3">
                        <label for="permit_validity_opposite_vehicle" class="form-label">Permit Validity Opposite Vehicle:</label>
                        <input type="date" name="permit_validity_opposite_vehicle" id="permit_validity_opposite_vehicle" class="form-control" value="{{ $garage->permit_validity_opposite_vehicle }}">
                    </div>

                    <div class="mb-3">
                        <label for="is_break_in" class="form-label">Is Break In:</label>
                        <select name="is_break_in" id="is_break_in" class="form-control">
                            <option value="1" {{ $garage->is_break_in == 1 ? 'selected' : '' }}>Yes</option>
                            <option value="0" {{ $garage->is_break_in == 0 ? 'selected' : '' }}>No</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="break_in_ins_date" class="form-label">Break In Inspection Date:</label>
                        <input type="date" name="break_in_ins_date" id="break_in_ins_date" class="form-control" value="{{ $garage->break_in_ins_date }}">
                    </div>

                    <div class="mb-3">
                        <label for="odometer_readng_break_in" class="form-label">Odometer Reading at Break In:</label>
                        <input type="number" name="odometer_readng_break_in" id="odometer_readng_break_in" class="form-control" value="{{ $garage->odometer_readng_break_in }}">
                    </div>

                    <div class="mb-3">
                        <label for="is_any_discre_found_break_in" class="form-label">Any Discrepancy Found at Break In:</label>
                        <select name="is_any_discre_found_break_in" id="is_any_discre_found_break_in" class="form-control">
                            <option value="1" {{ $garage->is_any_discre_found_break_in == '1' ? 'selected' : '' }}>Yes</option>
                            <option value="0" {{ $garage->is_any_discre_found_break_in == '0' ? 'selected' : '' }}>No</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="is_accused_elgible_drive" class="form-label">Is Accused Eligible to Drive:</label>
                        <select name="is_accused_elgible_drive" id="is_accused_elgible_drive" class="form-control">
                            <option value="1" {{ $garage->is_accused_elgible_drive == 1 ? 'selected' : '' }}>Yes</option>
                            <option value="0" {{ $garage->is_accused_elgible_drive == 0 ? 'selected' : '' }}>No</option>
                        </select>
                    </div>


                    <div class="mb-3">
                        <label for="travel_from_to" class="form-label">Travel From To:</label>
                        <input type="text" name="travel_from_to" id="travel_from_to" class="form-control" value="{{ $garage->travel_from_to }}">
                    </div>

                    <div class="mb-3">
                        <label for="clue_aout_accident" class="form-label">Clue About Accident:</label>
                        <input type="text" name="clue_aout_accident" id="clue_aout_accident" class="form-control" value="{{ $garage->clue_aout_accident }}">
                    </div>

                    <div class="mb-3">
                        <label for="car_scrap_found_on_spot" class="form-label">Car Scrap Found on Spot:</label>
                        <select name="car_scrap_found_on_spot" id="car_scrap_found_on_spot" class="form-control">
                            <option value="1" {{ $garage->car_scrap_found_on_spot == '1' ? 'selected' : '' }}>Yes</option>
                            <option value="0" {{ $garage->car_scrap_found_on_spot == '0' ? 'selected' : '' }}>No</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="garage_name" class="form-label">Garage Name:</label>
                        <input type="text" name="garage_name" id="garage_name" class="form-control" value="{{ $garage->garage_name }}">
                    </div>

                    <div class="mb-3">
                        <label for="odometer_reading_service_hist" class="form-label">Odometer Reading from Service History:</label>
                        <input type="text" name="odometer_reading_service_hist" id="odometer_reading_service_hist" class="form-control" value="{{ $garage->odometer_reading_service_hist }}">
                    </div>

                    <div class="mb-3">
                        <label for="tp_vehicle_details" class="form-label">Third Party Vehicle Details:</label>
                        <input type="text" name="tp_vehicle_details" id="tp_vehicle_details" class="form-control" value="{{ $garage->tp_vehicle_details }}">
                    </div>

                    <div class="mb-3">
                        <label for="is_any_disc_in_garage_entry_job_crd" class="form-label">Any Discrepancy in Garage Entry Job Card:</label>
                        <select name="is_any_disc_in_garage_entry_job_crd" id="is_any_disc_in_garage_entry_job_crd" class="form-control">
                            <option value="1" {{ $garage->is_any_disc_in_garage_entry_job_crd == '1' ? 'selected' : '' }}>Yes</option>
                            <option value="0" {{ $garage->is_any_disc_in_garage_entry_job_crd == '0' ? 'selected' : '' }}>No</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="investi_referal_reson_1" class="form-label">Investigation Referral Reason 1:</label>
                        <input type="text" name="investi_referal_reson_1" id="investi_referal_reson_1" class="form-control" value="{{ $garage->investi_referal_reson_1 }}">
                    </div>

                    <div class="mb-3">
                        <label for="referal_findings_1" class="form-label">Referral Findings 1:</label>
                        <input type="text" name="referal_findings_1" id="referal_findings_1" class="form-control" value="{{ $garage->referal_findings_1 }}">
                    </div>

                    <div class="mb-3">
                        <label for="investi_referal_rason2" class="form-label">Investigation Referral Reason 2:</label>
                        <input type="text" name="investi_referal_rason2" id="investi_referal_rason2" class="form-control" value="{{ $garage->investi_referal_rason2 }}">
                    </div>

                    <div class="mb-3">
                        <label for="referal_findings_2" class="form-label">Referral Findings 2:</label>
                        <input type="text" name="referal_findings_2" id="referal_findings_2" class="form-control" value="{{ $garage->referal_findings_2 }}">
                    </div>


                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary">Save Changes</button>
                    </div>
                </form>
            </div>

            @if ($garage->garage_gate_entry_images)
            <h6>Garage Gate Entry Images</h6>
            <div>
                @foreach (json_decode($garage->garage_gate_entry_images) as $image)
                <input type="checkbox" id="gateImage-{{ $garage->id }}-{{ $loop->index }}-{{$report->case_id}}" name="selectedGateImages[]" value="{{ asset('storage/' . $image) }}">
                <a href="{{ asset('storage/' . $image) }}" data-lightbox="garage-gate-entry" data-title="Garage Gate Entry Image">
                    <img src="{{ asset('storage/' . $image) }}" alt="Garage Gate Entry Image" class="img-fluid mb-2" style="max-width: 200px;">
                </a>
                @endforeach
            </div>
            @endif

            <form id="update-form" action="{{ route('garage.update.gate_entry_image', $garage->id) }}" method="POST" enctype="multipart/form-data">
                @csrf

                <label for="files">Update Garage Gate Entry Image :</label>
                <input type="file" name="garage_gate_entry_images[]" multiple>
                @error('garage_gate_entry_images')
                <div class="alert alert-danger">{{ $message }}</div>
                @enderror
                <button type="submit" class="btn btn-success mt-3">Submit</button>
            </form>

            @if ($garage->garage_job_card_images)
            <h6>Garage Job Card Images</h6>
            <div>
                @foreach (json_decode($garage->garage_job_card_images) as $image)
                <input type="checkbox" id="jobCardImage-{{ $garage->id }}-{{ $loop->index }}-{{$report->case_id}}" name="selectedJobCardImages[]" value="{{ asset('storage/' . $image) }}">
                <a href="{{ asset('storage/' . $image) }}" data-lightbox="garage-job-card" data-title="Garage Job Card Image">
                    <img src="{{ asset('storage/' . $image) }}" alt="Garage Job Card Image" class="img-fluid mb-2" style="max-width: 200px;">
                </a>
                @endforeach
            </div>
            @endif

            <form id="update-form" action="{{ route('garage.update.job_card_image', $garage->id) }}" method="POST" enctype="multipart/form-data">
                @csrf

                <label for="files">Update Garage Job Card Images :</label>
                <input type="file" name="garage_job_card_images[]" multiple>
                @error('garage_job_card_images')
                <div class="alert alert-danger">{{ $message }}</div>
                @enderror
                <button type="submit" class="btn btn-success mt-3">Submit</button>
            </form>

            @if ($garage->vehicle_images)
            <h6>Vehicle Images:</h6>
            <div>
                @foreach (json_decode($garage->vehicle_images) as $image)
                <input type="checkbox" id="vehicleImage-{{ $garage->id }}-{{ $loop->index }}-{{$report->case_id}}" name="selectedVehicleImages[]" value="{{ asset('storage/' . $image) }}">
                <a href="{{ asset('storage/' . $image) }}" data-lightbox="vehicle" data-title="Vehicle Image">
                    <img src="{{ asset('storage/' . $image) }}" alt="Vehicle Image" class="img-fluid mb-2" style="max-width: 200px;">
                </a>
                @endforeach
            </div>
            @endif

            <form id="update-form" action="{{ route('garage.update.vehicle_images', $garage->id) }}" method="POST" enctype="multipart/form-data">
                @csrf

                <label for="files">Update Vehicle Images :</label>
                <input type="file" name="vehicle_images[]" multiple>
                @error('vehicle_images')
                <div class="alert alert-danger">{{ $message }}</div>
                @enderror
                <button type="submit" class="btn btn-success mt-3">Submit</button>
            </form>

            @if ($garage->tow_vehical_report)
            <h6>Tow Vehical Report</h6>
            <div>
                @foreach (json_decode($garage->tow_vehical_report) as $image)
                <input type="checkbox" id="towVehicleReport-{{ $garage->id }}-{{ $loop->index }}-{{$report->case_id}}" name="selectedTowReports[]" value="{{ asset('storage/' . $image) }}">
                <a href="{{ asset('storage/' . $image) }}" data-lightbox="vehicle" data-title="Vehicle Image">
                    <img src="{{ asset('storage/' . $image) }}" alt="Tow Vehicle Image" class="img-fluid mb-2" style="max-width: 200px;">
                </a>
                @endforeach
            </div>
            @endif

            <form id="update-form" action="{{ route('garage.update.tow_vehical_report', $garage->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <label for="files">Update Tow Vehicle Report :</label>
                <input type="file" name="tow_vehical_report[]" multiple>
                @error('tow_vehical_report')
                <div class="alert alert-danger">{{ $message }}</div>
                @enderror
                <button type="submit" class="btn btn-success mt-3">Submit</button>
            </form>

            @if ($garage->garage_voice)
            <h6>Garage Voice</h6>
            @foreach (json_decode($garage->garage_voice) as $voice)
            <audio controls>
                <source src="{{ asset('storage/' . $voice) }}" type="audio/mpeg">
                Your browser does not support the audio tag.
            </audio>
            @endforeach
            @endif

            <form id="update-form" action="{{ route('garage.update.garage_voice', $garage->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <label for="files">Update Garage Voice :</label>
                <input type="file" name="garage_voice[]" multiple>
                @error('garage_voice')
                <div class="alert alert-danger">{{ $message }}</div>
                @enderror
                <button type="submit" class="btn btn-success mt-3">Submit</button>
            </form>
        </div>
    </div>
    @endforeach



    <hr>

    <h5>Driver Data</h5>
    @if($report->driver_reassign_status == 1)
    <form action="{{ route('driver.re.assign') }}" method="POST" class="ajax-form">
        @csrf
        <input type="hidden" name="id" required value="{{ $report->id }}">
        <button type="submit" class="btn btn-danger">Re-Assign</button>
    </form>
    @endif
    @if ($report->driver_reassign_status == 0)
    <span class="badge badge-danger">Pending</span>
    @endif
    @foreach ($driverData as $index => $driver)
    <div class="card mb-3">
        <div class="card-body">

            <div class='row'>
                <div class="col-md-8"></div>
                <div class="col-md-2">
                    <a class='btn btn-danger' href="{{url('/driver/'.$driver->id)}}">Authorize Upload data</a>
                </div>
                <div class="col-md-2">
                    <button class="btn btn-primary" onclick="editFieldDriver({{$driver->id}})">Edit Driver Data</button></p>
                </div>
            </div>

            <!-- Checkbox above each entry -->
            <div class="form-check">
                <input class="form-check-input" type="radio" id="selectDriver{{ $driver->id }}" value="{{ $driver->id }}-{{$report->case_id}}">
                <label class="form-check-label" for="selectDriver{{ $driver->id }}">
                    Select this driver data
                </label>
            </div>

            <h6>Entry {{ $index + 1 }}</h6>
            <p><strong>Driver Name:</strong> {{ $driver->driver_name }}</p>
            <p><strong>Driver Age:</strong> {{ $driver->driver_age }}</p>
            <p><strong>Driver Date of Birth:</strong> {{ $driver->driver_dob }}</p>
            <p><strong>Executive Name:</strong> {{ $driver->executive_name }}</p>
            <p><strong>Vehical Type:</strong> {{ $driver->vehical_type }}</p>
            <p><strong>RC Reg Date:</strong> {{ $driver->rc_reg_date }}</p>
            <p><strong>RC Exp Date:</strong> {{ $driver->rc_exp_date }}</p>
            <p><strong>Driver Licence Exp Date:</strong> {{ $driver->dl_exp_date }}</p>
            <p><strong>Driver Version of Accident:</strong> {{ $driver->rider_version_accident }}</p>
            <p><strong>Driver Licence and RTO Detailes:</strong> {{ $driver->dl_and_rto }}</p>
            <p><strong>Vehicle Authorized to Drive:</strong> @if($driver->vehicle_authorized_drive == 1) Yes @else No @endif</p>
            <p><strong>Others:</strong> {{ $driver->others }}</p>
            <p><strong>Seating capacity including Driver:</strong> {{ $driver->seating_capacity }}</p>
            <p><strong>Name of Opposite Vehicle Insurer:</strong> {{ $driver->opp_vehicle_insurer }}</p>
            <p><strong>Opposite Vehicle Registration No:</strong> {{ $driver->opp_vehicle_reg_no }}</p>
            <p><strong>Opposite Vehicle Make & Model:</strong> {{ $driver->opp_vehicle_made_make }}</p>
            <p><strong>Opposite Vehicle Insured Name:</strong> {{ $driver->opp_insured_name }}</p>
            <p><strong>Opposite party Insured Policy No:</strong> {{ $driver->opp_insured_policy_no }}</p>
            <p><strong>Opposite Party Insurance Start Date:</strong> {{ $driver->opp_party_insurance_start_date }}</p>
            <p><strong>Opposite Paerty Insurance End Date:</strong> {{ $driver->opp_party_insurance_end_date }}</p>
            <p><strong>Address of Opposing Party:</strong> {{ $driver->addres_opp_party }}</p>
            <p><strong>Opposite party RC Transferred To:</strong> {{ $driver->opp_rc_transfered_to }}</p>
            <p><strong>Opposite party RC Transfer Date:</strong> {{ $driver->opp_rc_transfered_date }}</p>
            <p><strong>Owner RC Transferred To:</strong> {{ $driver->owner_rc_transfered_to }}</p>
            <p><strong>Owner RC Transfer Date:</strong> {{ $driver->owner_rc_transfered_date }}</p>
            <p><strong>Opposite Vehicle Seating Capacity:</strong> {{ $driver->opp_vehicle_seating_capacity }}</p>
            <p><strong>Opposite Vehicle Engine No:</strong> {{ $driver->opp_vehicle_eng_no }}</p>
            <p><strong>Opposite Vehicle Chassis No:</strong> {{ $driver->opp_vehicle_chassis_no }}</p>
            <p><strong>Owner Permit No:</strong> {{ $driver->owner_permit_no }}</p>
            <p><strong>Opposing Party Permit No:</strong> {{ $driver->opp_party_permit_no }}</p>
            <p><strong>Owner Authorized State:</strong> {{ $driver->owner_authorized_state }}</p>
            <p><strong>Opposing Party Authorized State:</strong> {{ $driver->opp_party_authorized_state }}</p>
            <p><strong>Owner Permit Validity:</strong> {{ $driver->owner_permit_period_validity }}</p>
            <p><strong>Opposing Party Permit Validity:</strong> {{ $driver->opp_party_permit_period_validity }}</p>
            <p><strong>Owner Permit Verified:</strong> {{ $driver->owner_permit_verified ? 'Yes' : 'No' }}</p>
            <p><strong>Opposing Party Permit Verified:</strong> {{ $driver->opp_party_permit_verified ? 'Yes' : 'No' }}</p>
            <p><strong>Driver Badge Validity From:</strong> {{ $driver->badge_val_from }}</p>
            <p><strong>Driver Badge Validity To:</strong> {{ $driver->badge_val_to }}</p>

            <h5>Download Data Section</h5>


            @if(isset($driver->driver_downloads) )

            @foreach(json_decode($driver->driver_downloads) as $file)
            @php

            $extension = pathinfo($file, PATHINFO_EXTENSION);

            $label = '';
            switch (strtolower($extension)) {
            case 'jpg':
            case 'jpeg':
            case 'png':
            case 'gif':
            case 'svg':
            $label = 'driver image';
            break;
            case 'pdf':
            case 'zip':
            $label = 'driver report';
            break;
            case 'aac':
            case 'mp3':
            case 'wav':
            $label = 'driver voice';
            break;
            case 'mp4':
            case 'avi':
            case 'mov':
            case 'wmv':
            case 'mkv':
            $label = 'driver video file';
            break;
            default:
            $label = 'unknown file';
            }
            @endphp

            <ul>

                <li>
                    <a href="{{ asset('storage/' . $file) }}" target="_blank" download>{{ $label }}</a>
                </li>

            </ul>

            @endforeach
            @else
            <p>No files available for download.</p>
            @endif


            <div id="edit-form{{$driver->id}}" style="display:none;">
                <h3 class="text-center">Update Driver Data</h3>
                <form method="POST" action="{{ route('driver.text.update', $driver->id) }}" class="p-4 border rounded bg-light">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label for="driver-name" class="form-label">Driver Name:</label>
                        <input type="text" name="driver_name" id="driver-name-input" class="form-control" value="{{ $driver->driver_name }}">
                    </div>

                    <div class="mb-3">
                        <label for="driver-name" class="form-label">Driver Age:</label>
                        <input type="number" name="driver_age" id="driver-name-input" class="form-control" value="{{ $driver->driver_age }}">
                    </div>

                    <div class="mb-3">
                        <label for="driver-name" class="form-label">Driver Date Of Birth:</label>
                        <input type="date" name="driver_dob" id="driver-name-input" class="form-control" value="{{ $driver->driver_dob }}">
                    </div>

                    <div class="mb-3">
                        <label for="vehicle-type" class="form-label">Vehicle Type:</label>
                        <input type="text" name="vehical_type" id="vehicle-type-input" class="form-control" value="{{ $driver->vehical_type }}">
                    </div>

                    <div class="mb-3">
                        <label for="rc-reg-date" class="form-label">RC Reg Date:</label>
                        <input type="date" name="rc_reg_date" id="rc-reg-date-input" class="form-control" value="{{ $driver->rc_reg_date }}">
                    </div>

                    <div class="mb-3">
                        <label for="rc-exp-date" class="form-label">RC Exp Date:</label>
                        <input type="date" name="rc_exp_date" id="rc-exp-date-input" class="form-control" value="{{ $driver->rc_exp_date }}">
                    </div>

                    <div class="mb-3">
                        <label for="dl-exp-date" class="form-label">Driver Licence Exp Date:</label>
                        <input type="date" name="dl_exp_date" id="dl-exp-date-input" class="form-control" value="{{ $driver->dl_exp_date }}">
                    </div>

                    <div class="mb-3">
                        <label for="rider-version-accident" class="form-label">Driver Version of Accident:</label>
                        <input type="text" name="rider_version_accident" id="rider-version-accident-input" class="form-control" value="{{ $driver->rider_version_accident }}">
                    </div>

                    <div class="mb-3">
                        <label for="dl-and-rto" class="form-label">Driver Licence and RTO Details:</label>
                        <input type="text" name="dl_and_rto" id="dl-and-rto-input" class="form-control" value="{{ $driver->dl_and_rto }}">
                    </div>

                    <div class="mb-3">
                        <label for="vehicle-authorized-drive" class="form-label">Vehicle Authorized to Drive:</label>
                        <select name="vehicle_authorized_drive" id="vehicle-authorized-drive-input" class="form-select">
                            <option value="1" {{ $driver->vehicle_authorized_drive == 1 ? 'selected' : '' }}>Yes</option>
                            <option value="0" {{ $driver->vehicle_authorized_drive == 0 ? 'selected' : '' }}>No</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="others" class="form-label">Others:</label>
                        <textarea name="others" id="others-input" class="form-control">{{ $driver->others }}</textarea>
                    </div>

                    <div class="mb-3">
                        <label for="others" class="form-label">Seating capacity including Driver:</label>
                        <input type="number" name="seating_capacity" id="others-input" class="form-control" value="{{$driver->seating_capacity}}">
                    </div>

                    <div class="mb-3">
                        <label for="opp_vehicle_insurer" class="form-label">Opposite Vehicle Insurer:</label>
                        <input type="text" name="opp_vehicle_insurer" id="opp_vehicle_insurer" class="form-control" value="{{ $driver->opp_vehicle_insurer ?? '' }}">
                    </div>

                    <div class="mb-3">
                        <label for="opp_vehicle_reg_no" class="form-label">Opposite Vehicle Registration No:</label>
                        <input type="text" name="opp_vehicle_reg_no" id="opp_vehicle_reg_no" class="form-control" value="{{ $driver->opp_vehicle_reg_no ?? '' }}">
                    </div>

                    <div class="mb-3">
                        <label for="opp_vehicle_made_make" class="form-label">Opposite Vehicle Make & Model:</label>
                        <input type="text" name="opp_vehicle_made_make" id="opp_vehicle_made_make" class="form-control" value="{{ $driver->opp_vehicle_made_make ?? '' }}">
                    </div>

                    <div class="mb-3">
                        <label for="opp_insured_name" class="form-label">Opposite party Insured Name:</label>
                        <input type="text" name="opp_insured_name" id="opp_insured_name" class="form-control" value="{{ $driver->opp_insured_name ?? '' }}">
                    </div>

                    <div class="mb-3">
                        <label for="opp_insured_policy_no" class="form-label">Opposite Insured Policy No:</label>
                        <input type="text" name="opp_insured_policy_no" id="opp_insured_policy_no" class="form-control" value="{{ $driver->opp_insured_policy_no ?? '' }}">
                    </div>

                    <div class="mb-3">
                        <label for="opp_party_insurance_start_date" class="form-label">Opposite party Insurance Start Date:</label>
                        <input type="date" name="opp_party_insurance_start_date" id="opp_party_insurance_start_date" class="form-control" value="{{ $driver->opp_party_insurance_start_date ?? '' }}">
                    </div>

                    <div class="mb-3">
                        <label for="opp_party_insurance_end_date" class="form-label">Opposite party Insurance End Date:</label>
                        <input type="date" name="opp_party_insurance_end_date" id="opp_party_insurance_end_date" class="form-control" value="{{ $driver->opp_party_insurance_end_date ?? '' }}">
                    </div>

                    <div class="mb-3">
                        <label for="addres_opp_party" class="form-label">Address of Opposing Party:</label>
                        <input type="text" name="addres_opp_party" id="addres_opp_party" class="form-control" value="{{ $driver->addres_opp_party ?? '' }}">
                    </div>

                    <div class="mb-3">
                        <label for="opp_rc_transfered_to" class="form-label">Opposite party RC Transferred To:</label>
                        <input type="text" name="opp_rc_transfered_to" id="opp_rc_transfered_to" class="form-control" value="{{ $driver->opp_rc_transfered_to ?? '' }}">
                    </div>

                    <div class="mb-3">
                        <label for="opp_rc_transfered_date" class="form-label">Opposite party RC Transfer Date:</label>
                        <input type="date" name="opp_rc_transfered_date" id="opp_rc_transfered_date" class="form-control" value="{{ $driver->opp_rc_transfered_date ?? '' }}">
                    </div>

                    <div class="mb-3">
                        <label for="owner_rc_transfered_to" class="form-label">Owner RC Transferred To:</label>
                        <input type="text" name="owner_rc_transfered_to" id="owner_rc_transfered_to" class="form-control" value="{{ $driver->owner_rc_transfered_to ?? '' }}">
                    </div>

                    <div class="mb-3">
                        <label for="owner_rc_transfered_date" class="form-label">Owner RC Transfer Date:</label>
                        <input type="date" name="owner_rc_transfered_date" id="owner_rc_transfered_date" class="form-control" value="{{ $driver->owner_rc_transfered_date ?? '' }}">
                    </div>

                    <div class="mb-3">
                        <label for="opp_vehicle_seating_capacity" class="form-label">Opposite Vehicle Seating Capacity:</label>
                        <input type="number" name="opp_vehicle_seating_capacity" id="opp_vehicle_seating_capacity" class="form-control" value="{{ $driver->opp_vehicle_seating_capacity ?? '' }}">
                    </div>

                    <div class="mb-3">
                        <label for="opp_vehicle_eng_no" class="form-label">Opposite Vehicle Engine No:</label>
                        <input type="text" name="opp_vehicle_eng_no" id="opp_vehicle_eng_no" class="form-control" value="{{ $driver->opp_vehicle_eng_no ?? '' }}">
                    </div>

                    <div class="mb-3">
                        <label for="opp_vehicle_chassis_no" class="form-label">Opposite Vehicle Chassis No:</label>
                        <input type="text" name="opp_vehicle_chassis_no" id="opp_vehicle_chassis_no" class="form-control" value="{{ $driver->opp_vehicle_chassis_no ?? '' }}">
                    </div>

                    <div class="mb-3">
                        <label for="owner_permit_no" class="form-label">Owner Permit No:</label>
                        <input type="text" name="owner_permit_no" id="owner_permit_no" class="form-control" value="{{ $driver->owner_permit_no ?? '' }}">
                    </div>

                    <div class="mb-3">
                        <label for="opp_party_permit_no" class="form-label">Opposing Party Permit No:</label>
                        <input type="text" name="opp_party_permit_no" id="opp_party_permit_no" class="form-control" value="{{ $driver->opp_party_permit_no ?? '' }}">
                    </div>

                    <div class="mb-3">
                        <label for="owner_authorized_state" class="form-label">Owner Authorized State:</label>
                        <input type="text" name="owner_authorized_state" id="owner_authorized_state" class="form-control" value="{{ $driver->owner_authorized_state ?? '' }}">
                    </div>

                    <div class="mb-3">
                        <label for="opp_party_authorized_state" class="form-label">Opposing Party Authorized State:</label>
                        <input type="text" name="opp_party_authorized_state" id="opp_party_authorized_state" class="form-control" value="{{ $driver->opp_party_authorized_state ?? '' }}">
                    </div>

                    <div class="mb-3">
                        <label for="owner_permit_period_validity" class="form-label">Owner Permit Period of Validity:</label>
                        <input type="number" name="owner_permit_period_validity" id="owner_permit_period_validity" class="form-control" value="{{ $driver->owner_permit_period_validity ?? '' }}">
                    </div>

                    <div class="mb-3">
                        <label for="opp_party_permit_period_validity" class="form-label">Opposite Party Permit Period of Validity:</label>
                        <input type="number" name="opp_party_permit_period_validity" id="opp_party_permit_period_validity" class="form-control" value="{{ $driver->opp_party_permit_period_validity ?? '' }}">
                    </div>

                    <div class="mb-3">
                        <label for="owner_permit_verified" class="form-label">Owner Permit Verified:</label>
                        <select name="owner_permit_verified" id="vehicle-authorized-drive-input" class="form-select">
                            <option value="1" {{ $driver->owner_permit_verified == 1 ? 'selected' : '' }}>Yes</option>
                            <option value="0" {{ $driver->owner_permit_verified == 0 ? 'selected' : '' }}>No</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="opp_party_permit_verified" class="form-label">Opposite Party Permit Verified:</label>
                        <select name="opp_party_permit_verified" id="vehicle-authorized-drive-input" class="form-select">
                            <option value="1" {{ $driver->opp_party_permit_verified == 1 ? 'selected' : '' }}>Yes</option>
                            <option value="0" {{ $driver->opp_party_permit_verified == 0 ? 'selected' : '' }}>No</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="dl-exp-date" class="form-label">Driver Badge Validity From:</label>
                        <input type="date" name="badge_val_from" id="dl-exp-date-input" class="form-control" value="{{ $driver->badge_val_from }}">
                    </div>
                    <div class="mb-3">
                        <label for="dl-exp-date" class="form-label">Driver Badge Validity To:</label>
                        <input type="date" name="badge_val_to" id="dl-exp-date-input" class="form-control" value="{{ $driver->badge_val_to }}">
                    </div>


                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary">Save Changes</button>
                    </div>
                </form>
            </div>

            <div class='row '>
                @if ($driver->ration_card)
                <h6>Ration Card Images</h6>

                @foreach (json_decode($driver->ration_card) as $image)
                <div class='col-md-2'>
                    <input type="checkbox" id="rationCardImage-{{ $driver->id }}-{{ $loop->index }}-{{$report->case_id}}" name="selectedRationCardImages[]" value="{{ asset('storage/' . $image) }}">
                    <a href="{{ asset('storage/' . $image) }}" data-lightbox="driver" data-title="Driver Image">
                        <img src="{{ asset('storage/' . $image) }}" alt="Ration card Image" class="img-fluid mb-2" style="max-width: 200px;">
                    </a>

                </div>
                @endforeach

                @endif

                <form id="update-form" action="{{ route('driver.update.ration.card', $driver->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <label for="files">Update Ration Cards :</label>
                    <input type="file" name="updated_images[]" multiple>
                    @error('updated_images')
                    <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                    <button type="submit" class="btn btn-success mt-3">Submit</button>
                </form>

            </div>



            <div class='row'>
                @if ($driver->vehicle_permit)
                <h6>Vehicle Permit</h6>

                @foreach (json_decode($driver->vehicle_permit) as $image)
                <div class="col-md-2">
                    <input type="checkbox" id="vehiclePermitImage-{{ $driver->id }}-{{ $loop->index }}-{{$report->case_id}}" name="selectedVehiclePermitImages[]" value="{{ asset('storage/' . $image) }}">
                    <a href="{{ asset('storage/' . $image) }}" data-lightbox="driver" data-title="Driver Image">
                        <img src="{{ asset('storage/' . $image) }}" alt="Ration card Image" class="img-fluid mb-2" style="max-width: 200px;">
                    </a>
                </div>
                @endforeach

                @endif

                <form id="update-form" action="{{ route('driver.update.vehicle.permit', $driver->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <label for="files">Update Vehicle Permit:</label>
                    <input type="file" name="updated_images_permit[]" multiple>
                    @error('updated_images_permit')
                    <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                    <button type="submit" class="btn btn-success mt-3">Submit</button>
                </form>
            </div>

            <div class='row'>
                @if ($driver->video_call)
                <h6>Video Call</h6>

                @foreach (json_decode($driver->video_call) as $video)
                <div class="col-md-2">
                    <video width="200" controls>
                        <source src="{{asset('storage/' . $video)}}" type="video/mp4">
                        Your browser does not support the video tag.
                    </video>
                </div>
                @endforeach
                @endif

                <form id="update-form" action="{{ route('driver.update.video_call', $driver->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <label for="files">Update video call:</label>
                    <input type="file" name="updated_video_call[]" multiple>

                    @error('updated_video_call')
                    <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                    <button type="submit" class="btn btn-success mt-3">Submit</button>
                </form>

            </div>
            <div class="row">
                @if ($driver->assets_pic)
                <h6>Assets Pictures</h6>

                @foreach (json_decode($driver->assets_pic) as $image)
                <div class="col-md-2">

                    <input type="checkbox" id="assetPicImage-{{ $driver->id }}-{{ $loop->index }}-{{$report->case_id}}" name="selectedAssetPicImages[]" value="{{ asset('storage/' . $image) }}">
                    <a href="{{ asset('storage/' . $image) }}" data-lightbox="driver" data-title="Driver Image">
                        <img src="{{ asset('storage/' . $image) }}" alt="asset Image" class="img-fluid mb-2" style="max-width: 200px;">
                    </a>
                </div>
                @endforeach

                @endif

                <form id="update-form" action="{{ route('driver.update.assets_pic', $driver->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @error('updated_images_permit')
                    <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                    <label for="files">Update Vehicle Asset Pic:</label>
                    <input type="file" name="updated_assets_pic[]" multiple>
                    <button type="submit" class="btn btn-success mt-3">Submit</button>
                </form>
            </div>

            <div class="row">
                @if ($driver->driver_image)
                <h6>Driver Images</h6>

                @foreach (json_decode($driver->driver_image) as $image)
                <div class="col-md-2">
                    <input type="checkbox" id="driverImage-{{ $driver->id }}-{{ $loop->index }}-{{$report->case_id}}" name="selectedDriverImages[]" 
                    value="{{ asset('storage/' . $image) }}">
                    <a href="{{ asset('storage/' . $image) }}" data-lightbox="driver" data-title="Driver Image">
                        <img src="{{ asset('storage/' . $image) }}" alt="Driver Image" class="img-fluid mb-2" style="max-width: 200px;">
                    </a>
                </div>
                @endforeach

                @endif

                <form id="update-form" action="{{ route('driver.update.driver_image', $driver->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <label for="files">Update Vehicle driver image:</label>
                    <input type="file" name="updated_driver_image[]" multiple>
                    @error('updated_driver_image')
                    <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                    <button type="submit" class="btn btn-success mt-3">Submit</button>
                </form>
            </div>

            <div class='row'>
                @if ($driver->driving_licence_images)
                <h6>Driver Licence Images</h6>

                @foreach (json_decode($driver->driving_licence_images) as $image)
                <div class='col-md-2'>

                    <input type="checkbox" id="driverLicImage-{{ $driver->id }}-{{ $loop->index }}-{{$report->case_id}}" name="selectedDriverLicImages[]" 
                    value="{{ asset('storage/' . $image) }}">
                    <a href="{{ asset('storage/' . $image) }}" data-lightbox="driving-licence" data-title="Driving Licence Image">
                        <img src="{{ asset('storage/' . $image) }}" alt="Driving Licence Image" class="img-fluid mb-2" style="max-width: 200px;">
                    </a>
                </div>
                @endforeach

                @endif


                <form id="update-form" action="{{ route('driver.update.driver_licence_images', $driver->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <label for="files">Update Vehicle driving Licence image:</label>
                    <input type="file" name="updated_driving_licence_images[]" multiple>
                    @error('updated_driving_licence_images')
                    <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                    <button type="submit" class="btn btn-success mt-3">Submit</button>
                </form>

            </div>
            <div class="row">
                @if ($driver->rc_images)
                <h6>Driver Rc Images</h6>
                @foreach (json_decode($driver->rc_images) as $image)
                <div class="col-md-2">
                    <input type="checkbox" id="driverRcImage-{{ $driver->id }}-{{ $loop->index }}-{{$report->case_id}}" name="selectedDriverRCImages[]" value="{{ asset('storage/' . $image) }}">
                    <a href="{{ asset('storage/' . $image) }}" data-lightbox="rc" data-title="RC Image">
                        <img src="{{ asset('storage/' . $image) }}" alt="RC Image" class="img-fluid mb-2" style="max-width: 200px;">
                    </a>
                </div>
                @endforeach
                @endif

                <form id="update-form" action="{{ route('driver.update.rc_images', $driver->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <label for="files">Update RC images:</label>
                    <input type="file" name="updated_rc_images[]" multiple>
                    @error('updated_rc_images')
                    <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                    <button type="submit" class="btn btn-success mt-3">Submit</button>
                </form>
            </div>
            <div class="row">
                @if ($driver->driver_aadhaar_card_images)
                <h6>Driver Aadhar Card</h6>
                @foreach (json_decode($driver->driver_aadhaar_card_images) as $image)
                <div class="col-md-2">
                    <input type="checkbox" id="driverAadharImage-{{ $driver->id }}-{{ $loop->index }}-{{$report->case_id}}" name="selectedDriverAadharImages[]" value="{{ asset('storage/' . $image) }}">
                    <a href="{{ asset('storage/' . $image) }}" data-lightbox="aadhaar-card" data-title="Driver Aadhaar Card Image">
                        <img src="{{ asset('storage/' . $image) }}" alt="Driver Aadhaar Card Image" class="img-fluid mb-2" style="max-width: 200px;">
                    </a>
                </div>
                @endforeach
                @endif

                <form id="update-form" action="{{ route('driver.update.driver_aadhaar_card_images', $driver->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <label for="files">Update Driver Aadhaar Card_images:</label>
                    <input type="file" name="updated_driver_aadhaar_card_images[]" multiple>
                    @error('updated_driver_aadhaar_card_images')
                    <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                    <button type="submit" class="btn btn-success mt-3">Submit</button>
                </form>
            </div>
            <div class='row'>
                @if ($driver->co_passenger_dl)
                <h6>Co-passenger Driving Licence</h6>

                @foreach (json_decode($driver->co_passenger_dl) as $image)
                <div class="col-md-2">
                    <input type="checkbox" id="coDriverAadharImage-{{ $driver->id }}-{{ $loop->index }}-{{$report->case_id}}" name="selectedCoDriverAadharImages[]" value="{{ asset('storage/' . $image) }}">
                    <a href="{{ asset('storage/' . $image) }}" data-lightbox="aadhaar-card" data-title="Driver Aadhaar Card Image">
                        <img src="{{ asset('storage/' . $image) }}" alt="Driver Aadhaar Card Image" class="img-fluid mb-2" style="max-width: 200px;">
                    </a>
                </div>
                @endforeach
                @endif

                <form id="update-form" action="{{ route('driver.update.co_passenger_dl', $driver->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <label for="files">Update Co-Passenger Driving Licence:</label>
                    <input type="file" name="updated_co_passenger_dl[]" multiple>
                    @error('updated_co_passenger_dl')
                    <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                    <button type="submit" class="btn btn-success mt-3">Submit</button>
                </form>


            </div>

            <div class="row">
                @if ($driver->co_passenger_aadhar)
                <h6>Co-passenger Aadhar card</h6>

                @foreach (json_decode($driver->co_passenger_aadhar) as $image)
                <div class="col-md-2">
                    <input type="checkbox" id="coPassengerDriverAadharImage-{{ $driver->id }}-{{ $loop->index }}-{{$report->case_id}}" name="selectedCoPassengerDriverAadharImages[]" value="{{ asset('storage/' . $image) }}">
                    <a href="{{ asset('storage/' . $image) }}" data-lightbox="aadhaar-card" data-title="Driver Aadhaar Card Image">
                        <img src="{{ asset('storage/' . $image) }}" alt="Driver Aadhaar Card Image" class="img-fluid mb-2" style="max-width: 200px;">
                    </a>
                </div>
                @endforeach
                @endif

                <form id="update-form" action="{{ route('driver.update.co_passenger_aadhar', $driver->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <label for="files">Update Co-Passenger Aadhar:</label>
                    <input type="file" name="updated_co_passenger_aadhar[]" multiple>
                    @error('co_passenger_aadhar')
                    <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                    <button type="submit" class="btn btn-success mt-3">Submit</button>
                </form>
            </div>

            <div class="row">
                @if ($driver->driver_voice)
                <h6>Driver Voice</h6>
                @foreach (json_decode($driver->driver_voice) as $voice)
                <div class="col-md-3">
                    <audio controls>
                        <source src="{{ asset('storage/' . $voice) }}" type="audio/mpeg">
                        Your browser does not support the audio tag.
                    </audio>
                </div>
                @endforeach
                @endif

                <form id="update-form" action="{{ route('driver.update.driver_voice', $driver->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <label for="files">Update driver Voice:</label>
                    <input type="file" name="updated_driver_voice[]" multiple>
                    @error('updated_driver_voice')
                    <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                    <button type="submit" class="btn btn-success mt-3">Submit</button>
                </form>

            </div>


        </div>
    </div>
    @endforeach

    <hr>

    <h5>Spot Data</h5>
    @if ($report->spot_reassign_status == 1)
    <form action="{{ route('spot.re.assign') }}" method="POST" class="ajax-form">
        @csrf
        <input type="hidden" name="id" required value="{{ $report->id }}">
        <button type="submit" class="btn btn-danger">Re-Assign</button>
    </form>
    @endif
    @if ($report->spot_reassign_status == 0)
    <span class="badge badge-danger">Pending</span>
    @endif
    @foreach ($spotData as $index => $spot)
    <div class="card mb-3">
        <div class="card-body">

            <div class='row'>
                <div class="col-md-8"></div>
                <div class="col-md-2">
                    <a class='btn btn-danger' href="{{url('/spot/'.$spot->id)}}">Authorize Upload data</a>
                </div>
                <div class="col-md-2">
                    <button class="btn btn-primary" onclick="editFieldSpot({{$spot->id}})">Edit Spot Data</button></p>
                </div>
            </div>

            <!-- Checkbox above each entry -->
            <div class="form-check">
                <input class="form-check-input" type="radio" id="selectSpot{{ $spot->id }}" value="{{ $spot->id }}-{{$report->case_id}}">
                <label class="form-check-label" for="selectSpot{{ $spot->id }}">
                    Select this spot data
                </label>
            </div>

            <h6>Entry {{ $index + 1 }}</h6>
            <p><strong>Spot Address:</strong> {{ $spot->spot_address }}</p>
            <p><strong>Executive Name:</strong> {{ $spot->executive_name }}</p>
            <p><strong>Date of Investigation:</strong> {{ $spot->investigation_date  }}</p>
            <p><strong>Date of Submission of Investigation:</strong> {{$spot->investigation_submission_date }}</p>
            <p><strong>OP (MV) No:</strong> {{ $spot->op_no }}</p>
            <p><strong>Advocate Name:</strong> {{$spot->advocate_name }}</p>
            <p><strong>Court:</strong> {{$spot->court }}</p>
            <p><strong>Case Title:</strong> {{$spot->case_title }}</p>
            <p><strong>Case/Claim Filled Under Section:</strong> {{ $spot->case_claim }}</p>
            <p><strong>Cause of Loss:</strong> {{ $spot->cause_loss}}</p>
            <p><strong>OD Claim, if any, made. If so, details(Ascertained from Insured) :</strong> {{$spot->od_claim }}</p>
            <p><strong>Road Details :</strong> {{$spot->road_details }}</p>
            <p><strong>Investigation Bill No:</strong> {{$spot->investigation_bill_no}}</p>
            <p><strong>Investigation Report:</strong> {{$spot->investigation_report}}</p>
            <p><strong>Loss Minimization Sheet:</strong> {{$spot->loss_minimization_sheet}}</p>
            <p><strong>Policy Copy:</strong> {{$spot->policy_copy}}</p>
            <p><strong>Driving License Extract:</strong> {{$spot->driving_license_extract}}</p>
            <p><strong>Permit Copy:</strong> {{$spot->permit_copy}}</p>
            <p><strong>Fitness Certificate Copy:</strong> {{$spot->fitness_certificate_copy}}</p>
            <p><strong>R.C. Book:</strong> {{$spot->rc_book}}</p>
            <p><strong>Insured Statement:</strong> {{$spot->insured_statement}}</p>
            <p><strong>Claimant Statement:</strong> {{$spot->claimant_statement}}</p>
            <p><strong>Seizure Memo Copy:</strong> {{$spot->seizure_memo_copy}}</p>
            <p><strong>Arrest Memo Copy:</strong> {{$spot->arrest_memo_copy}}</p>
            <p><strong>MVI Report:</strong> {{$spot->mvi_report}}</p>
            <p><strong>Age Proof:</strong> {{$spot->age_proof}}</p>
            <p><strong>Income Proof:</strong> {{$spot->income_proof}}</p>
            <p><strong>Occupation Proof:</strong> {{$spot->occupation_proof}}</p>
            <p><strong>Photograph (Family or Nominees/Legal Heir):</strong> {{$spot->family_photograph}}</p>
            <p><strong>Spot Panchnama Copy:</strong> {{$spot->spot_panchnama_copy}}</p>
            <p><strong>Accident Site Map:</strong> {{$spot->accident_site_map}}</p>
            <p><strong>Inquest Panchnama:</strong> {{$spot->inquest_panchnama}}</p>
            <p><strong>Paper Cutting:</strong> {{$spot->paper_cutting}}</p>
            <p><strong>F.I.R:</strong> {{$spot->fir}}</p>
            <p><strong>Final Report/Charge Sheet:</strong> {{$spot->final_report_charge_sheet}}</p>
            <p><strong>Death Certificate:</strong> {{$spot->death_certificate}}</p>
            <p><strong>Post Mortem Report:</strong> {{$spot->post_mortem_report}}</p>
            <p><strong>Viscera Report (if preserved):</strong> {{$spot->viscera_report}}</p>
            <p><strong>O.D. Status:</strong> {{$spot->od_status}}</p>
            <p><strong>T.P. Vehicle Insurance Details:</strong> {{$spot->tp_vehicle_insurance_details}}</p>
            <p><strong>T.P. Driving License:</strong> {{$spot->tp_driving_license}}</p>
            <p><strong>T.P. Insurer Confirmation (No claim T.P./O.D.):</strong> {{$spot->tp_insurer_confirmation}}</p>
            <p><strong>Notice U/s 134(c):</strong> {{$spot->notice_u_s_134c}}</p>
            <h5>Download Data Section</h5>


            @if(isset($spot->spot_downloads) )

            @foreach(json_decode($spot->spot_downloads) as $file)
            @php

            $extension = pathinfo($file, PATHINFO_EXTENSION);

            $label = '';
            switch (strtolower($extension)) {
            case 'jpg':
            case 'jpeg':
            case 'png':
            case 'gif':
            case 'svg':
            $label = 'spot image';
            break;
            case 'pdf':
            case 'zip':
            $label = 'spot report';
            break;
            case 'aac':
            case 'mp3':
            case 'wav':
            $label = 'spot voice';
            break;
            case 'mp4':
            case 'avi':
            case 'mov':
            case 'wmv':
            case 'mkv':
            $label = 'spot video file';
            break;
            default:
            $label = 'unknown file';
            }
            @endphp

            <ul>

                <li>
                    <a href="{{ asset('storage/' . $file) }}" target="_blank" download>{{ $label }}</a>
                </li>

            </ul>

            @endforeach
            @else
            <p>No files available for download.</p>
            @endif


            <div id="edit-form-spot{{$spot->id}}" style="display:none;">
                <h3 class="text-center">Update Spot Data</h3>
                <form method="POST" action="{{ route('spot.text.update', $spot->id) }}" class="p-4 border rounded bg-light">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label for="driver-name" class="form-label">Spot Address:</label>
                        <input type="text" name="spot_address" id="driver-name-input" class="form-control" value="{{ $spot->spot_address }}">
                    </div>

                    <div class="mb-3">
                        <label for="driver-name" class="form-label">Date of entrustment of the Investigation:</label>
                        <input type="date" name="investigation_date" id="driver-name-input" class="form-control" value="{{ $spot->investigation_date }}">
                    </div>

                    <div class="mb-3">
                        <label for="driver-name" class="form-label">Date of submission of the Investigation Report:</label>
                        <input type="date" name="investigation_submission_date" id="driver-name-input" class="form-control" value="{{ $spot->investigation_submission_date }}">
                    </div>

                    <div class="mb-3">
                        <label for="driver-name" class="form-label">OP (MV) no:</label>
                        <input type="text" name="op_no" id="driver-name-input" class="form-control" value="{{ $spot->op_no }}">
                    </div>

                    <div class="mb-3">
                        <label for="driver-name" class="form-label">Advocate Name:</label>
                        <input type="text" name="advocate_name" id="driver-name-input" class="form-control" value="{{ $spot->advocate_name }}">
                    </div>

                    <div class="mb-3">
                        <label for="driver-name" class="form-label">Court:</label>
                        <input type="text" name="court" id="driver-name-input" class="form-control" value="{{ $spot->court }}">
                    </div>

                    <div class="mb-3">
                        <label for="driver-name" class="form-label">Case Title(Name of the 1st Claimant Vs. Name
                            of the 1st Respondent):</label>
                        <input type="text" name="case_title" id="driver-name-input" class="form-control" value="{{ $spot->case_title }}">
                    </div>

                    <div class="mb-3">
                        <label for="driver-name" class="form-label">Case/Claim filed under Section:</label>
                        <input type="text" name="case_claim" id="driver-name-input" class="form-control" value="{{ $spot->case_claim }}">
                    </div>

                    <div class="mb-3">
                        <label for="driver-name" class="form-label">Cause of Loss (e.g., Death/Injury, TPPD):</label>
                        <input type="text" name="cause_loss" id="driver-name-input" class="form-control" value="{{ $spot->cause_loss }}">
                    </div>

                    <div class="mb-3">
                        <label for="driver-name" class="form-label">OD Claim, if any, made. If so, details
                            (Ascertained from Insured) :</label>
                        <input type="text" name="od_claim" id="driver-name-input" class="form-control" value="{{ $spot->od_claim }}">
                    </div>

                    <div class="mb-3">
                        <label for="driver-name" class="form-label">Road Details:</label>
                        <input type="text" name="road_details" id="driver-name-input" class="form-control" value="{{ $spot->road_details }}">
                    </div>

                    <!---other details--->

                    <div class="mb-3">
                        <label for="investigation_bill" class="form-label">Investigation Bill</label>
                        <select id="investigation_bill" name="investigation_bill_no" class="form-select">
                            <option value="Yes" @if( $spot->investigation_bill_no == 'Yes') selected @endif>Yes</option>
                            <option value="No" @if( $spot->investigation_bill_no == 'No') selected @endif>No</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="investigation_report" class="form-label">Investigation Report</label>
                        <select id="investigation_report" name="investigation_report" class="form-select">
                            <option value="Yes" @if($spot->investigation_report == 'Yes') selected @endif>Yes</option>
                            <option value="No" @if($spot->investigation_report == 'No') selected @endif>No</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="loss_minimization_sheet" class="form-label">Loss Minimization Sheet</label>
                        <select id="loss_minimization_sheet" name="loss_minimization_sheet" class="form-select">
                            <option value="Yes" @if($spot->loss_minimization_sheet == 'Yes') selected @endif>Yes</option>
                            <option value="No" @if($spot->loss_minimization_sheet == 'No') selected @endif>No</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="policy_copy" class="form-label">Policy Copy</label>
                        <select id="policy_copy" name="policy_copy" class="form-select">
                            <option value="Yes" @if($spot->policy_copy == 'Yes') selected @endif>Yes</option>
                            <option value="No" @if($spot->policy_copy == 'No') selected @endif>No</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="driving_license_extract" class="form-label">Driving License Extract</label>
                        <select id="driving_license_extract" name="driving_license_extract" class="form-select">
                            <option value="Yes" @if($spot->driving_license_extract == 'Yes') selected @endif>Yes</option>
                            <option value="No" @if($spot->driving_license_extract == 'No') selected @endif>No</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="permit_copy" class="form-label">Permit Copy</label>
                        <select id="permit_copy" name="permit_copy" class="form-select">
                            <option value="Yes" @if($spot->permit_copy == 'Yes') selected @endif>Yes</option>
                            <option value="No" @if($spot->permit_copy == 'No') selected @endif>No</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="fitness_certificate_copy" class="form-label">Fitness Certificate Copy</label>
                        <select id="fitness_certificate_copy" name="fitness_certificate_copy" class="form-select">
                            <option value="Yes" @if($spot->fitness_certificate_copy == 'Yes') selected @endif>Yes</option>
                            <option value="No" @if($spot->fitness_certificate_copy == 'No') selected @endif>No</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="rc_book" class="form-label">R.C. Book</label>
                        <select id="rc_book" name="rc_book" class="form-select">
                            <option value="Yes" @if($spot->rc_book == 'Yes') selected @endif>Yes</option>
                            <option value="No" @if($spot->rc_book == 'No') selected @endif>No</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="insured_statement" class="form-label">Insured Statement</label>
                        <select id="insured_statement" name="insured_statement" class="form-select">
                            <option value="Yes" @if($spot->insured_statement == 'Yes') selected @endif>Yes</option>
                            <option value="No" @if($spot->insured_statement == 'No') selected @endif>No</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="claimant_statement" class="form-label">Claimant Statement</label>
                        <select id="claimant_statement" name="claimant_statement" class="form-select">
                            <option value="Yes" @if($spot->claimant_statement == 'Yes') selected @endif>Yes</option>
                            <option value="No" @if($spot->claimant_statement == 'No') selected @endif>No</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="seizure_memo" class="form-label">Seizure Memo Copy</label>
                        <select id="seizure_memo" name="seizure_memo_copy" class="form-select">
                            <option value="Yes" @if($spot->seizure_memo_copy == 'Yes') selected @endif>Yes</option>
                            <option value="No" @if($spot->seizure_memo_copy == 'No') selected @endif>No</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="arrest_memo" class="form-label">Arrest Memo Copy</label>
                        <select id="arrest_memo" name="arrest_memo_copy" class="form-select">
                            <option value="Yes" @if($spot->arrest_memo_copy == 'Yes') selected @endif>Yes</option>
                            <option value="No" @if($spot->arrest_memo_copy == 'No') selected @endif>No</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="mvi_report" class="form-label">MVI Report</label>
                        <select id="mvi_report" name="mvi_report" class="form-select">
                            <option value="Yes" @if($spot->mvi_report == 'Yes') selected @endif>Yes</option>
                            <option value="No" @if($spot->mvi_report == 'No') selected @endif>No</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="age_proof" class="form-label">Age Proof</label>
                        <select id="age_proof" name="age_proof" class="form-select">
                            <option value="Yes" @if($spot->age_proof == 'Yes') selected @endif>Yes</option>
                            <option value="No" @if($spot->age_proof == 'No') selected @endif>No</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="income_proof" class="form-label">Income Proof</label>
                        <select id="income_proof" name="income_proof" class="form-select">
                            <option value="Yes" @if($spot->income_proof == 'Yes') selected @endif>Yes</option>
                            <option value="No" @if($spot->income_proof == 'No') selected @endif>No</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="occupation_proof" class="form-label">Occupation Proof</label>
                        <select id="occupation_proof" name="occupation_proof" class="form-select">
                            <option value="Yes" @if($spot->occupation_proof == 'Yes') selected @endif>Yes</option>
                            <option value="No" @if($spot->occupation_proof == 'No') selected @endif>No</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="photograph" class="form-label">Photograph (Family or Nominees/Legal Heir)</label>
                        <select id="photograph" name="family_photograph" class="form-select">
                            <option value="Yes" @if($spot->family_photograph == 'Yes') selected @endif>Yes</option>
                            <option value="No" @if($spot->family_photograph == 'No') selected @endif>No</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="spot_panchnama" class="form-label">Spot Panchnama Copy</label>
                        <select id="spot_panchnama" name="spot_panchnama_copy" class="form-select">
                            <option value="Yes" @if($spot->spot_panchnama_copy == 'Yes') selected @endif>Yes</option>
                            <option value="No" @if($spot->spot_panchnama_copy == 'No') selected @endif>No</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="fir" class="form-label">F.I.R</label>
                        <select id="fir" name="fir" class="form-select">
                            <option value="Yes" @if($spot->fir == 'Yes') selected @endif>Yes</option>
                            <option value="No" @if($spot->fir == 'No') selected @endif>No</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="final_report" class="form-label">Final Report/Charge Sheet</label>
                        <select id="final_report" name="final_report_charge_sheet" class="form-select">
                            <option value="Yes" @if($spot->final_report_charge_sheet == 'Yes') selected @endif>Yes</option>
                            <option value="No" @if($spot->final_report_charge_sheet == 'No') selected @endif>No</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="death_certificate" class="form-label">Death Certificate</label>
                        <select id="death_certificate" name="death_certificate" class="form-select">
                            <option value="Yes" @if($spot->death_certificate == 'Yes') selected @endif>Yes</option>
                            <option value="No" @if($spot->death_certificate == 'No') selected @endif>No</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="post_mortem_report" class="form-label">Post Mortem Report</label>
                        <select id="post_mortem_report" name="post_mortem_report" class="form-select">
                            <option value="Yes" @if($spot->post_mortem_report == 'Yes') selected @endif>Yes</option>
                            <option value="No" @if($spot->post_mortem_report == 'No') selected @endif>No</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="viscera_report" class="form-label">Viscera Report (if preserved)</label>
                        <select id="viscera_report" name="viscera_report" class="form-select">
                            <option value="Yes" @if($spot->viscera_report == 'Yes') selected @endif>Yes</option>
                            <option value="No" @if($spot->viscera_report == 'No') selected @endif>No</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="od_status" class="form-label">O.D. Status</label>
                        <select id="od_status" name="od_status" class="form-select">
                            <option value="Yes" @if($spot->od_status == 'Yes') selected @endif>Yes</option>
                            <option value="No" @if($spot->od_status == 'No') selected @endif>No</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="tp_vehicle_insurance_details" class="form-label">T.P. Vehicle Insurance Details</label>
                        <select id="tp_vehicle_insurance_details" name="tp_vehicle_insurance_details" class="form-select">
                            <option value="Yes" @if($spot->tp_vehicle_insurance_details == 'Yes') selected @endif>Yes</option>
                            <option value="No" @if($spot->tp_vehicle_insurance_details == 'No') selected @endif>No</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="tp_driving_license" class="form-label">T.P. Driving License</label>
                        <select id="tp_driving_license" name="tp_driving_license" class="form-select">
                            <option value="Yes" @if($spot->tp_driving_license == 'Yes') selected @endif>Yes</option>
                            <option value="No" @if($spot->tp_driving_license == 'No') selected @endif>No</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="tp_driving_license" class="form-label">Accident Site Map</label>
                        <select id="tp_driving_license" name="accident_site_map" class="form-select">
                            <option value="Yes" @if($spot->accident_site_map == 'Yes') selected @endif>Yes</option>
                            <option value="No" @if($spot->accident_site_map == 'No') selected @endif>No</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="tp_driving_license" class="form-label">Inquest Panchnama</label>
                        <select id="tp_driving_license" name="inquest_panchnama" class="form-select">
                            <option value="Yes" @if($spot->inquest_panchnama == 'Yes') selected @endif>Yes</option>
                            <option value="No" @if($spot->inquest_panchnama == 'No') selected @endif>No</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="tp_driving_license" class="form-label">Paper Cutting </label>
                        <select id="tp_driving_license" name="paper_cutting" class="form-select">
                            <option value="Yes" @if($spot->paper_cutting == 'Yes') selected @endif>Yes</option>
                            <option value="No" @if($spot->paper_cutting == 'No') selected @endif>No</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="tp_insurer_confirmation" class="form-label">T.P. Insurer Confirmation (No claim T.P./OD)</label>
                        <select id="tp_insurer_confirmation" name="tp_insurer_confirmation" class="form-select">
                            <option value="Yes" @if($spot->tp_insurer_confirmation == 'Yes') selected @endif>Yes</option>
                            <option value="No" @if($spot->tp_insurer_confirmation == 'No') selected @endif>No</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="notice_134" class="form-label">Notice U/s 134(c)</label>
                        <select id="notice_134" name="notice_u_s_134c" class="form-select">
                            <option value="Yes" @if($spot->notice_u_s_134c == 'Yes') selected @endif>Yes</option>
                            <option value="No" @if($spot->notice_u_s_134c == 'No') selected @endif>No</option>
                        </select>
                    </div>


                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary">Save Changes</button>
                    </div>
                </form>
            </div>




            <h6>Spot Images</h6>
            @if ($spot->spot_images)
            <div>
                @foreach (json_decode($spot->spot_images) as $image)
                <input type="checkbox" id="spotImage-{{ $spot->id }}-{{ $loop->index }}-{{$report->case_id}}" name="spotImages[]" value="{{ asset('storage/' . $image) }}">
                <a href="{{ asset('storage/' . $image) }}" data-lightbox="spot" data-title="Spot Image">
                    <img src="{{ asset('storage/' . $image) }}" alt="Spot Image" class="img-fluid mb-2" style="max-width: 200px;">
                </a>
                @endforeach
            </div>
            @endif

            <form id="update-form" action="{{ route('spot.update.spot_images', $spot->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <label for="files">Update Spot Images :</label>
                <input type="file" name="spot_images[]" multiple>
                @error('spot_images')
                <div class="alert alert-danger">{{ $message }}</div>
                @enderror
                <button type="submit" class="btn btn-success mt-3">Submit</button>
            </form>

            @if ($spot->spot_voice)
            <h6>Spot Voice</h6>
            @foreach (json_decode($spot->spot_voice) as $voice)
            <audio controls>
                <source src="{{ asset('storage/' . $voice) }}" type="audio/mpeg">
                Your browser does not support the audio tag.
            </audio>
            @endforeach
            @endif

            <form id="update-form" action="{{ route('spot.update.spot_voice', $spot->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <label for="files">Update Spot Voice :</label>
                <input type="file" name="spot_voice[]" multiple>
                @error('spot_voice')
                <div class="alert alert-danger">{{ $message }}</div>
                @enderror
                <button type="submit" class="btn btn-success mt-3">Submit</button>
            </form>

        </div>
    </div>
    @endforeach

    <hr>

    <h5>Owner Data</h5>
    @if ($report->owner_reassign_status == 1)
    <form action="{{ route('owner.re.assign') }}" method="POST" class="ajax-form">
        @csrf
        <input type="hidden" name="id" required value="{{ $report->id }}">
        <button type="submit" class="btn btn-danger">Re-Assign</button>
    </form>
    @endif
    @if ($report->owner_reassign_status == 0)
    <span class="badge badge-danger">Pending</span>
    @endif
    @foreach ($ownerData as $index => $owner)
    <div class="card mb-3">
        <div class="card-body">
            <div class='row'>
                <div class="col-md-8"></div>
                <div class="col-md-2">
                    <a class='btn btn-danger' href="{{url('/owner/'.$owner->id)}}">Authorize Upload data</a>
                </div>
                <div class="col-md-2">
                    <button class="btn btn-primary" onclick="editFieldOwner({{$owner->id}})">Edit Owner Data</button></p>
                </div>
            </div>

            <div class="form-check">
                <input class="form-check-input" type="radio" id="selectOwner{{ $owner->id }}" value="{{ $owner->id }}-{{$report->case_id}}">
                <label class="form-check-label" for="selectOwner{{ $owner->id }}">
                    Select this owner data
                </label>
            </div>

            <h6>Entry {{ $index + 1 }}</h6>
            <p><strong>Executive Name:</strong> {{ $owner->executive_name }}</p>
            <p><strong>Insured persion's Version of Accident:</strong> {{ $owner->insured_version_acc }}</p>
            <p><strong>Insured Occupation:</strong> {{ $owner->insured_occupation }}</p>
            <p><strong>Rc Owner Name:</strong> {{ $owner->rc_owner_name }}</p>
            <p><strong>Vehicle No:</strong> {{ $owner->vehicle_no }}</p>
            <p><strong>Vehicle Made And make:</strong> {{ $owner->made_make }}</p>
            <p><strong>Engine No:</strong> {{ $owner->engine_no}}</p>
            <p><strong>Vehicle Chassis No:</strong> {{ $owner->chass_no }}</p>
            <p><strong>Class of Vehicle:</strong> {{ $owner->class_of_vehicle }}</p>
            <p><strong>Detailes of opposite Vehicle:</strong> {{ $owner->details_opp_vehicle}}</p>
            <p><strong>Vehicle Permit Detailes:</strong> {{ $owner->permit_details }}</p>
            <p><strong>Detailes of opposite vehicle:</strong> {{ $owner->details_opp_vehicle }}</p>
            <p><strong>Owner of Opposite Vehicle:</strong> {{ $owner->owner_opp_vehicle }}</p>
            <p><strong>Fitness Validity From:</strong> {{ $owner->fitness_validity_from }}</p>
            <p><strong>Fitness Validity To:</strong> {{ $owner->fitness_validity_to }}</p>
            <p><strong>Pollution validity From:</strong> {{ $owner->polution_validity_from }}</p>
            <p><strong>Pollution validity To:</strong> {{ $owner->polution_validity_to }}</p>
            <p><strong>Whether the DOA is within the Policy Period:</strong> {{ $owner->doa_in_policy }}</p>
            <p><strong>Policy is Comprehensive or TP Liability only:</strong> {{ $owner->policy_comper_tp_liability }}</p>
            <p><strong>If goods laden, details of the goods, owner, etc:</strong> {{ $owner->if_goodladen_details }}</p>
            <p><strong>Details of the damages caused to the goods & how those were further transported, etc:</strong> {{ $owner->damage_goods_transport }}</p>
            <p><strong>Details of the damages caused to the Vehicle:</strong> {{ $owner->details_damage_vehicle }}</p>
            <p><strong>Details of the Damages Caused to the Vehicle:</strong> {{ $owner->details_damage_vehicle }}</p>
            <p><strong>All Liability Covered:</strong> {{ $owner->all_liability_covered }}</p>
            <p><strong>Is Close Proximity:</strong> {{ $owner->is_close_proximity ? 'Yes' : 'No' }}</p>
            <p><strong>RC Owner Insurer Same:</strong> {{ $owner->rc_owner_insurer_same ? 'Yes' : 'No' }}</p>
            <p><strong>Victim is Employee:</strong> {{ $owner->victim_is_employee ? 'Yes' : 'No' }}</p>
            <p><strong>Registration Valid DOA:</strong> {{ $owner->reg_valid_doa ? 'Yes' : 'No' }}</p>
            <p><strong>Fitness Valid DOA:</strong> {{ $owner->fitness_valid_doa ? 'Yes' : 'No' }}</p>
            <p><strong>Valid Permit DOA:</strong> {{ $owner->valid_permit_doa ? 'Yes' : 'No' }}</p>
            <p><strong>Any Violation Route Permit:</strong> {{ $owner->any_violation_route_permit ? 'Yes' : 'No' }}</p>
            <p><strong>Opposite Party Insurer Name:</strong> {{ $owner->opp_party_insurer_name }}</p>
            <p><strong>Opposite Party Policy No:</strong> {{ $owner->opp_party_policy_no }}</p>
            <p><strong>Is Opposite Party Liable:</strong> {{ $owner->is_opp_party_liabile ? 'Yes' : 'No' }}</p>
            <p><strong>Opposite Party Period Insurance:</strong> {{ $owner->opp_party_period_insurance }}</p>
            <p><strong>Opposite Party Class Vehicle:</strong> {{ $owner->opp_part_class_vehicle }}</p>
            <p><strong>Opposite Party RC Owner:</strong> {{ $owner->opp_party_rc_owner }}</p>
            <p><strong>Opposite Party Engine No:</strong> {{ $owner->opp_engine_no }}</p>
            <p><strong>Opposite Party Chassis No:</strong> {{ $owner->opp_chassis_no }}</p>
            <p><strong>CC Vehicle:</strong> {{ $owner->cc_vehicle }}</p>
            <p><strong>Opposite Party CC Vehicle:</strong> {{ $owner->opp_party_cc_vehicle }}</p>
            <p><strong>Address/Mobile Accused:</strong> {{ $owner->address_mobile_accused }}</p>
            <p><strong>Accused DL Valid DOA:</strong> {{ $owner->accused_dl_vaild_doa ? 'Yes' : 'No' }}</p>
            <p><strong>Rel Accused Insured:</strong> {{ $owner->rel_accused_insured }}</p>
            <p><strong>Opposite Driver DL Valid:</strong> {{ $owner->opp_driver_dl_valid ? 'Yes' : 'No' }}</p>
            <p><strong>Accused Accident Before:</strong> {{ $owner->accused_accident_before ? 'Yes' : 'No' }}</p>
            <p><strong>DL No (Owner):</strong> {{ $owner->dl_no_owner }}</p>
            <p><strong>DL No (Opposite Vehicle):</strong> {{ $owner->dl_no_opp_vehicle }}</p>
            <p><strong>Opposite Party Class Vehicle Authorized Driver:</strong> {{ $owner->opp_party_class_vehicle_auth_drive }}</p>
            <p><strong>Accused First Issue NT:</strong> {{ $owner->accused_first_issue_nt }}</p>
            <p><strong>Opposite Party First Issue NT:</strong> {{ $owner->opp_party_first_issue_nt }}</p>
            <p><strong>NT Validity (Accused):</strong> {{ $owner->nt_validity__accused }}</p>
            <p><strong>NT Validity (Opposite Party):</strong> {{ $owner->nt_validity_opp_party }}</p>
            <p><strong>Badge No (Accused):</strong> {{ $owner->badge_no_accused }}</p>
            <p><strong>Badge No (Opposite Party):</strong> {{ $owner->badge_no_opp_party }}</p>
            <p><strong>Accused TV First Issue:</strong> {{ $owner->accused_tv_first_issue }}</p>
            <p><strong>Opposite Party TV First Issue:</strong> {{ $owner->opp_party_tv_first_issue }}</p>
            <p><strong>Accused Transport Validity:</strong> {{ $owner->accused_transport_validity }}</p>
            <p><strong>Opposite Party Transport Validity:</strong> {{ $owner->opp_party_transport_validity }}</p>
            <p><strong>Interact No:</strong> {{ $owner->interact_no }}</p>
            <p><strong>Accident Happened on National Holiday:</strong> {{ $owner->accident_hapn_nat_hoilday }}</p>
            <p><strong>Date of Claim Intimated:</strong> {{ $owner->date_claim_intimated }}</p>
            <p><strong>Reason for Late Intimation:</strong> {{ $owner->reason_late_intimation }}</p>
            <p><strong>Insured Profession:</strong> {{ $owner->insured_profession }}</p>
            <p><strong>Is Negative Area Address:</strong> {{ $owner->is_neg_area_address ? 'Yes' : 'No' }}</p>
            <p><strong>Pan Number:</strong> {{ $owner->pan_no_owner }}</p>
            <p><strong>Aadhar Number:</strong> {{ $owner->aadhar_no_owner }}</p>
            <p><strong>Hypothecation Details:</strong> {{ $owner->hyp_details }}</p>
            <p><strong>Vehicle Damages:</strong> {{ $owner->vehicle_damages }}</p>
            <p><strong>Commercial Vehicle Details:</strong> {{ $owner->comercl_vehicle_details }}</p>
            <p><strong>Material Loss in Accident:</strong> {{ $owner->material_loss_accidnt }}</p>
            <p><strong>Material Quantity:</strong> {{ $owner->material_quantity }}</p>
            <p><strong>Is Load Receipt Available:</strong> {{ $owner->is_load_recept_available ? 'Yes' : 'No' }}</p>
            <p><strong>Fitness Details:</strong> {{ $owner->fitness_details }}</p>
            <p><strong>Area Covered:</strong> {{ $owner->area_covered }}</p>
            <p><strong>Previous Insurer:</strong> {{ $owner->prev_insurer }}</p>
            <p><strong>Previous Policy Number:</strong> {{ $owner->prev_policy_no }}</p>
            <p><strong>Policy Details:</strong> {{ $owner->policy_detl }}</p>
            <p><strong>Any Claim in Previous Policy:</strong> {{ $owner->any_clain_in_prev_policy ? 'Yes' : 'No' }}</p>
            <p><strong>Previous Claim Photo Available:</strong> {{ $owner->is_prev_claim_photo_available ? 'Yes' : 'No' }}</p>
            <p><strong>Social Status:</strong> {{ $owner->social_status }}</p>

            <h5>Download Data Section</h5>


            @if(isset($owner->owner_downloads) )

            @foreach(json_decode($owner->owner_downloads) as $file)
            @php

            $extension = pathinfo($file, PATHINFO_EXTENSION);

            $label = '';
            switch (strtolower($extension)) {
            case 'jpg':
            case 'jpeg':
            case 'png':
            case 'gif':
            case 'svg':
            $label = 'owner image';
            break;
            case 'pdf':
            case 'zip':
            $label = 'owner report';
            break;
            case 'aac':
            case 'mp3':
            case 'wav':
            $label = 'owner voice';
            break;
            case 'mp4':
            case 'avi':
            case 'mov':
            case 'wmv':
            case 'mkv':
            $label = 'owner video file';
            break;
            default:
            $label = 'unknown file';
            }
            @endphp

            <ul>

                <li>
                    <a href="{{ asset('storage/' . $file) }}" target="_blank" download>{{ $label }}</a>
                </li>

            </ul>

            @endforeach
            @else
            <p>No files available for download.</p>
            @endif

            <div id="edit-form-owner{{$owner->id}}" style="display:none;">
                <h3 class="text-center">Update Owner Data</h3>
                <form method="POST" action="{{ route('owner.update.owner_data', $owner->id) }}" class="p-4 border rounded bg-light">
                    @csrf
                    @method('PUT')

                    <!-- Insured Person's Version of Accident -->
                    <div class="mb-3">
                        <label for="insured_version_acc" class="form-label">Insured Person's Version of Accident:</label>
                        <textarea name="insured_version_acc" id="insured_version_acc" class="form-control">{{ old('insured_version_acc', $owner->insured_version_acc) }}</textarea>
                    </div>

                    <!-- Insured Occupation -->
                    <div class="mb-3">
                        <label for="insured_occupation" class="form-label">Insured Occupation:</label>
                        <input type="text" name="insured_occupation" id="insured_occupation" class="form-control" value="{{ old('insured_occupation', $owner->insured_occupation) }}">
                    </div>

                    <!-- RC Owner Name -->
                    <div class="mb-3">
                        <label for="rc_owner_name" class="form-label">RC Owner Name:</label>
                        <input type="text" name="rc_owner_name" id="rc_owner_name" class="form-control" value="{{ old('rc_owner_name', $owner->rc_owner_name) }}">
                    </div>

                    <!-- Vehicle No -->
                    <div class="mb-3">
                        <label for="vehicle_no" class="form-label">Vehicle No:</label>
                        <input type="text" name="vehicle_no" id="vehicle_no" class="form-control" value="{{ old('vehicle_no', $owner->vehicle_no) }}">
                    </div>

                    <!-- Vehicle Make and Model -->
                    <div class="mb-3">
                        <label for="made_make" class="form-label">Vehicle Make and Model:</label>
                        <input type="text" name="made_make" id="made_make" class="form-control" value="{{ old('made_make', $owner->made_make) }}">
                    </div>

                    <!-- Engine No -->
                    <div class="mb-3">
                        <label for="engine_no" class="form-label">Engine No:</label>
                        <input type="text" name="engine_no" id="engine_no" class="form-control" value="{{ old('engine_no', $owner->engine_no) }}">
                    </div>

                    <!-- Vehicle Chassis No -->
                    <div class="mb-3">
                        <label for="chass_no" class="form-label">Vehicle Chassis No:</label>
                        <input type="text" name="chass_no" id="chass_no" class="form-control" value="{{ old('chass_no', $owner->chass_no) }}">
                    </div>

                    <!-- Class of Vehicle -->
                    <div class="mb-3">
                        <label for="class_of_vehicle" class="form-label">Class of Vehicle:</label>
                        <input type="text" name="class_of_vehicle" id="class_of_vehicle" class="form-control" value="{{ old('class_of_vehicle', $owner->class_of_vehicle) }}">
                    </div>

                    <!-- Details of Opposite Vehicle -->
                    <div class="mb-3">
                        <label for="details_opp_vehicle" class="form-label">Details of Opposite Vehicle:</label>
                        <textarea name="details_opp_vehicle" id="details_opp_vehicle" class="form-control">{{ old('details_opp_vehicle', $owner->details_opp_vehicle) }}</textarea>
                    </div>

                    <!-- Vehicle Permit Details -->
                    <div class="mb-3">
                        <label for="permit_details" class="form-label">Vehicle Permit Details:</label>
                        <input type="text" name="permit_details" id="permit_details" class="form-control" value="{{ old('permit_details', $owner->permit_details) }}">
                    </div>

                    <!-- Owner of Opposite Vehicle -->
                    <div class="mb-3">
                        <label for="owner_opp_vehicle" class="form-label">Owner of Opposite Vehicle:</label>
                        <input type="text" name="owner_opp_vehicle" id="owner_opp_vehicle" class="form-control" value="{{ old('owner_opp_vehicle', $owner->owner_opp_vehicle) }}">
                    </div>

                    <div class="mb-3">
                        <label for="owner_opp_vehicle" class="form-label">Fitness Validity : From:</label>
                        <input type="date" name="fitness_validity_from" id="owner_opp_vehicle" class="form-control" value="{{ old('owner_opp_vehicle', $owner->owner_opp_vehicle) }}">
                    </div>
                    <div class="mb-3">
                        <label for="owner_opp_vehicle" class="form-label">Fitness Validity : To:</label>
                        <input type="date" name="fitness_validity_to" id="owner_opp_vehicle" class="form-control" value="{{ old('owner_opp_vehicle', $owner->owner_opp_vehicle) }}">
                    </div>
                    <div class="mb-3">
                        <label for="owner_opp_vehicle" class="form-label">Pollution validity: From</label>
                        <input type="date" name="polution_validity_from" id="owner_opp_vehicle" class="form-control" value="{{ old('owner_opp_vehicle', $owner->owner_opp_vehicle) }}">
                    </div>
                    <div class="mb-3">
                        <label for="owner_opp_vehicle" class="form-label">Pollution validity: To</label>
                        <input type="date" name="polution_validity_to" id="owner_opp_vehicle" class="form-control" value="{{ old('owner_opp_vehicle', $owner->owner_opp_vehicle) }}">
                    </div>
                    <div class="mb-3">
                        <label for="owner_opp_vehicle" class="form-label">Whether the DOA is within the Policy Period:</label>
                        <select name="doa_in_policy" id="owner_opp_vehicle" class="form-control">
                            <option value='yes' @if($owner->owner_opp_vehicle=='yes') select @else @endif >Yes</option>
                            <option value='no' @if($owner->owner_opp_vehicle=='no') select @else @endif >No</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="owner_opp_vehicle" class="form-label">Policy is Comprehensive or TP Liability only:</label>
                        <select name="policy_comper_tp_liability" id="owner_opp_vehicle" class="form-control">
                            <option value='yes' @if($owner->policy_comper_tp_liability=='yes') select @else @endif >Yes</option>
                            <option value='no' @if($owner->policy_comper_tp_liability=='no') select @else @endif >No</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="owner_opp_vehicle" class="form-label">If goods laden, details of the goods, owner, etc:</label>
                        <input type="text" name="if_goodladen_details" id="owner_opp_vehicle" class="form-control" value="{{ old('if_goodladen_details', $owner->if_goodladen_details) }}">
                    </div>

                    <div class="mb-3">
                        <label for="owner_opp_vehicle" class="form-label">Details of the damages caused to the goods
                            &how those were further transported, etc:</label>
                        <input type="text" name="damage_goods_transport" id="owner_opp_vehicle" class="form-control" value="{{ old('damage_goods_transport', $owner->damage_goods_transport) }}">
                    </div>

                    <div class="mb-3">
                        <label for="owner_opp_vehicle" class="form-label">Details of the damages caused to the Vehicle:</label>
                        <input type="text" name="details_damage_vehicle" id="owner_opp_vehicle" class="form-control" value="{{ old('details_damage_vehicle', $owner->details_damage_vehicle) }}">
                    </div>

                    <div class="mb-3">
                        <label for="insured_advocate" class="form-label">Insured Advocate:</label>
                        <input type="text" name="insured_advocate" id="insured_advocate" class="form-control" value="{{ old('insured_advocate', $owner->insured_advocate ?? '') }}">
                    </div>

                    <!-- Accused Admitted Offence -->
                    <div class="mb-3">
                        <label for="accused_admited_offence" class="form-label">Accused Admitted Offence:</label>
                        <input type="text" name="accused_admited_offence" id="accused_admited_offence" class="form-control" value="{{ old('accused_admited_offence', $owner->accused_admited_offence ?? '') }}">
                    </div>

                    <!-- Type of License -->
                    <div class="mb-3">
                        <label for="type_license" class="form-label">Type of License:</label>
                        <select name="type_license" id="type_license" class="form-control">
                            <option value="" disabled {{ old('type_license', $owner->type_license ?? '') ? '' : 'selected' }}>Select License Type</option>
                            <option value="Permanent" {{ old('type_license', $owner->type_license ?? '') == 'Permanent' ? 'selected' : '' }}>Permanent</option>
                            <option value="Temp" {{ old('type_license', $owner->type_license ?? '') == 'Temp' ? 'selected' : '' }}>Temporary</option>
                        </select>
                    </div>

                    <!-- Vehicle Authorization to Drive -->
                    <div class="mb-3">
                        <label for="vehicle_auth_drive" class="form-label">Vehicle Authorization to Drive:</label>
                        <input type="text" name="vehicle_auth_drive" id="vehicle_auth_drive" class="form-control" value="{{ old('vehicle_auth_drive', $owner->vehicle_auth_drive ?? '') }}">
                    </div>

                    <!-- Mismatch Vehicle in DL -->
                    <div class="mb-3">
                        <label for="mismatch_vehicle_in_dl" class="form-label">Mismatch Vehicle in DL:</label>
                        <input type="text" name="mismatch_vehicle_in_dl" id="mismatch_vehicle_in_dl" class="form-control" value="{{ old('mismatch_vehicle_in_dl', $owner->mismatch_vehicle_in_dl ?? '') }}">
                    </div>

                    <!-- Insured Advocate Number -->
                    <div class="mb-3">
                        <label for="insured_advocate_num" class="form-label">Insured Advocate Number:</label>
                        <input type="text" name="insured_advocate_num" id="insured_advocate_num" class="form-control" value="{{ old('insured_advocate_num', $owner->insured_advocate_num ?? '') }}">
                    </div>

                    <!-- Boolean Fields as Select Boxes -->
                    <div class="mb-3">
                        <label for="is_close_proximity" class="form-label">Is Close Proximity:</label>
                        <select name="is_close_proximity" id="is_close_proximity" class="form-control">
                            <option value="1" {{ $owner->is_close_proximity ? 'selected' : '' }}>Yes</option>
                            <option value="0" {{ !$owner->is_close_proximity ? 'selected' : '' }}>No</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="rc_owner_insurer_same" class="form-label">RC Owner Insurer Same:</label>
                        <select name="rc_owner_insurer_same" id="rc_owner_insurer_same" class="form-control">
                            <option value="1" {{ $owner->rc_owner_insurer_same ? 'selected' : '' }}>Yes</option>
                            <option value="0" {{ !$owner->rc_owner_insurer_same ? 'selected' : '' }}>No</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="victim_is_employee" class="form-label">Victim is Employee:</label>
                        <select name="victim_is_employee" id="victim_is_employee" class="form-control">
                            <option value="1" {{ $owner->victim_is_employee ? 'selected' : '' }}>Yes</option>
                            <option value="0" {{ !$owner->victim_is_employee ? 'selected' : '' }}>No</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="reg_valid_doa" class="form-label">Registration Valid DOA:</label>
                        <select name="reg_valid_doa" id="reg_valid_doa" class="form-control">
                            <option value="1" {{ $owner->reg_valid_doa ? 'selected' : '' }}>Yes</option>
                            <option value="0" {{ !$owner->reg_valid_doa ? 'selected' : '' }}>No</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="fitness_valid_doa" class="form-label">Fitness Valid DOA:</label>
                        <select name="fitness_valid_doa" id="fitness_valid_doa" class="form-control">
                            <option value="1" {{ $owner->fitness_valid_doa ? 'selected' : '' }}>Yes</option>
                            <option value="0" {{ !$owner->fitness_valid_doa ? 'selected' : '' }}>No</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="valid_permit_doa" class="form-label">Valid Permit DOA:</label>
                        <select name="valid_permit_doa" id="valid_permit_doa" class="form-control">
                            <option value="1" {{ $owner->valid_permit_doa ? 'selected' : '' }}>Yes</option>
                            <option value="0" {{ !$owner->valid_permit_doa ? 'selected' : '' }}>No</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="any_violation_route_permit" class="form-label">Any Violation Route Permit:</label>
                        <select name="any_violation_route_permit" id="any_violation_route_permit" class="form-control">
                            <option value="1" {{ $owner->any_violation_route_permit ? 'selected' : '' }}>Yes</option>
                            <option value="0" {{ !$owner->any_violation_route_permit ? 'selected' : '' }}>No</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="is_opp_party_liabile" class="form-label">Is Opposite Party Liable:</label>
                        <select name="is_opp_party_liabile" id="is_opp_party_liabile" class="form-control">
                            <option value="1" {{ $owner->is_opp_party_liabile ? 'selected' : '' }}>Yes</option>
                            <option value="0" {{ !$owner->is_opp_party_liabile ? 'selected' : '' }}>No</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="opp_driver_dl_valid" class="form-label">Opposite Driver DL Valid:</label>
                        <select name="opp_driver_dl_valid" id="opp_driver_dl_valid" class="form-control">
                            <option value="1" {{ $owner->opp_driver_dl_valid ? 'selected' : '' }}>Yes</option>
                            <option value="0" {{ !$owner->opp_driver_dl_valid ? 'selected' : '' }}>No</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="accused_accident_before" class="form-label">Accused Accident Before:</label>
                        <select name="accused_accident_before" id="accused_accident_before" class="form-control">
                            <option value="1" {{ $owner->accused_accident_before ? 'selected' : '' }}>Yes</option>
                            <option value="0" {{ !$owner->accused_accident_before ? 'selected' : '' }}>No</option>
                        </select>
                    </div>

                    <!-- Other Fields as Text Areas -->
                    <div class="mb-3">
                        <label for="all_liability_covered" class="form-label">All Liability Covered:</label>
                        <input type="text" name="all_liability_covered" id="all_liability_covered" class="form-control" value="{{ old('all_liability_covered', $owner->all_liability_covered ?? '') }}">
                    </div>

                    <div class="mb-3">
                        <label for="opp_party_insurer_name" class="form-label">Opposite Party Insurer Name:</label>
                        <input type="text" name="opp_party_insurer_name" id="opp_party_insurer_name" class="form-control" value="{{ old('opp_party_insurer_name', $owner->opp_party_insurer_name ?? '') }}">
                    </div>

                    <div class="mb-3">
                        <label for="opp_party_policy_no" class="form-label">Opposite Party Policy No:</label>
                        <input type="text" name="opp_party_policy_no" id="opp_party_policy_no" class="form-control" value="{{ old('opp_party_policy_no', $owner->opp_party_policy_no ?? '') }}">
                    </div>

                    <div class="mb-3">
                        <label for="opp_party_period_insurance" class="form-label">Opposite Party Period Insurance:</label>
                        <input type="date" name="opp_party_period_insurance" id="opp_party_period_insurance" class="form-control" value="{{ old('opp_party_period_insurance', $owner->opp_party_period_insurance ?? '') }}">
                    </div>



                    <div class="mb-3">
                        <label for="rel_accused_insured" class="form-label">Relation of Accused with Insured:</label>
                        <textarea name="rel_accused_insured" id="rel_accused_insured" class="form-control">{{ old('rel_accused_insured', $owner->rel_accused_insured ?? '') }}</textarea>
                    </div>

                    <div class="mb-3">
                        <label for="badge_no_accused" class="form-label">Badge No of Accused:</label>
                        <input type="text" name="badge_no_accused" id="badge_no_accused" class="form-control" value="{{ old('badge_no_accused', $owner->badge_no_accused ?? '') }}">
                    </div>

                    <div class="mb-3">
                        <label for="badge_no_opp_party" class="form-label">Badge No of Opposite Party:</label>
                        <input type="text" name="badge_no_opp_party" id="badge_no_opp_party" class="form-control" value="{{ old('badge_no_opp_party', $owner->badge_no_opp_party ?? '') }}">
                    </div>


                    <div class="mb-3">
                        <label for="accused_tv_first_issue" class="form-label">Accused TV First Issue:</label>
                        <input type="date" name="accused_tv_first_issue" id="accused_tv_first_issue" class="form-control" value="{{ old('accused_tv_first_issue', $owner->accused_tv_first_issue ?? '') }}">
                    </div>

                    <div class="mb-3">
                        <label for="opp_party_tv_first_issue" class="form-label">Opposite Party TV First Issue:</label>
                        <input type="date" name="opp_party_tv_first_issue" id="opp_party_tv_first_issue" class="form-control" value="{{ old('opp_party_tv_first_issue', $owner->opp_party_tv_first_issue ?? '') }}">
                    </div>


                    <div class="mb-3">
                        <label for="accused_transport_validity" class="form-label">Accused Transport Validity:</label>
                        <input type="date" name="accused_transport_validity" id="accused_transport_validity" class="form-control" value="{{ old('accused_transport_validity', $owner->accused_transport_validity ?? '') }}">
                    </div>

                    <div class="mb-3">
                        <label for="opp_party_transport_validity" class="form-label">Opposite Party Transport Validity:</label>
                        <input type="date" name="opp_party_transport_validity" id="opp_party_transport_validity" class="form-control" value="{{ old('opp_party_transport_validity', $owner->opp_party_transport_validity ?? '') }}">
                    </div>

                    <!-- Opposite Party Class Vehicle -->
                    <div class="mb-3">
                        <label for="opposite_party_class_vehicle" class="form-label">Opposite Party Class Vehicle:</label>
                        <input type="text" name="opp_party_class_vehicle_auth_drive" id="opp_party_class_vehicle" class="form-control" value="{{ old('opp_party_class_vehicle_auth_drive', $owner->opp_party_class_vehicle_auth_drive) }}">
                    </div>

                    <!-- Opposite Party RC Owner -->
                    <div class="mb-3">
                        <label for="opposite_party_rc_owner" class="form-label">Opposite Party RC Owner:</label>
                        <input type="text" name="opp_party_rc_owner" id="opp_party_rc_owner" class="form-control" value="{{ old('opp_party_rc_owner', $owner->opp_party_rc_owner) }}">
                    </div>

                    <!-- Opposite Party Engine No -->
                    <div class="mb-3">
                        <label for="opposite_party_engine_no" class="form-label">Opposite Party Engine No:</label>
                        <input type="text" name="opp_engine_no" id="opp_engine_no" class="form-control" value="{{ old('opp_engine_no', $owner->opp_engine_no) }}">
                    </div>

                    <!-- Opposite Party Chassis No -->
                    <div class="mb-3">
                        <label for="opposite_party_chassis_no" class="form-label">Opposite Party Chassis No:</label>
                        <input type="text" name="opp_chassis_no" id="opp_chassis_no" class="form-control" value="{{ old('opp_chassis_no', $owner->opp_chassis_no) }}">
                    </div>

                    <!-- CC Vehicle -->
                    <div class="mb-3">
                        <label for="cc_vehicle" class="form-label">CC Vehicle:</label>
                        <input type="text" name="cc_vehicle" id="cc_vehicle" class="form-control" value="{{ old('cc_vehicle', $owner->cc_vehicle) }}">
                    </div>

                    <!-- Opposite Party CC Vehicle -->
                    <div class="mb-3">
                        <label for="opposite_party_cc_vehicle" class="form-label">Opposite Party CC Vehicle:</label>
                        <input type="text" name="opp_party_cc_vehicle" id="opp_party_cc_vehicle" class="form-control" value="{{ old('opp_party_cc_vehicle', $owner->opp_party_cc_vehicle) }}">
                    </div>

                    <!-- Address/Mobile Accused -->
                    <div class="mb-3">
                        <label for="address_mobile_accused" class="form-label">Address/Mobile Accused:</label>
                        <input type="text" name="address_mobile_accused" id="address_mobile_accused" class="form-control" value="{{ old('address_mobile_accused', $owner->address_mobile_accused) }}">
                    </div>

                    <!-- NT Validity Accused -->
                    <div class="mb-3">
                        <label for="nt_validity_accused" class="form-label">NT Validity Accused:</label>
                        <input type="date" name="nt_validity__accused" id="nt_validity__accused" class="form-control" value="{{ old('nt_validity__accused', $owner->nt_validity__accused) }}">
                    </div>

                    <!-- NT Validity Opposite Party -->
                    <div class="mb-3">
                        <label for="nt_validity_opp_party" class="form-label">NT Validity Opposite Party:</label>
                        <input type="date" name="nt_validity_opp_party" id="nt_validity_opp_party" class="form-control" value="{{ old('nt_validity_opp_party', $owner->nt_validity_opp_party) }}">
                    </div>

                    <!-- Accused First Issue NT -->
                    <div class="mb-3">
                        <label for="accused_first_issue_nt" class="form-label">Accused First Issue NT:</label>
                        <input type="date" name="accused_first_issue_nt" id="accused_first_issue_nt" class="form-control" value="{{ old('accused_first_issue_nt', $owner->accused_first_issue_nt) }}">
                    </div>

                    <!-- Opposite Party First Issue NT -->
                    <div class="mb-3">
                        <label for="opp_party_first_issue_nt" class="form-label">Opposite Party First Issue NT:</label>
                        <input type="date" name="opp_party_first_issue_nt" id="opp_party_first_issue_nt" class="form-control" value="{{ old('opp_party_first_issue_nt', $owner->opp_party_first_issue_nt) }}">
                    </div>

                    <!-- DL No Owner -->
                    <div class="mb-3">
                        <label for="dl_no_owner" class="form-label">DL No Owner:</label>
                        <input type="text" name="dl_no_owner" id="dl_no_owner" class="form-control" value="{{ old('dl_no_owner', $owner->dl_no_owner) }}">
                    </div>

                    <!-- DL No Opposite Vehicle -->
                    <div class="mb-3">
                        <label for="dl_no_opp_vehicle" class="form-label">DL No Opposite Vehicle:</label>
                        <input type="text" name="dl_no_opp_vehicle" id="dl_no_opp_vehicle" class="form-control" value="{{ old('dl_no_opp_vehicle', $owner->dl_no_opp_vehicle) }}">
                    </div>

                    <div class="mb-3">
                        <label for="owner_interact_no" class="form-label">Interact No:</label>
                        <input type="text" name="interact_no" id="owner_interact_no" class="form-control" value="{{ old('interact_no', $owner->interact_no) }}">
                    </div>

                    <!-- Accident Happened on National Holiday -->
                    <div class="mb-3">
                        <label for="owner_accident_hapn_nat_hoilday" class="form-label">Accident Happened on National Holiday:</label>
                        <input type="text" name="accident_hapn_nat_hoilday" id="owner_accident_hapn_nat_hoilday" class="form-control" value="{{ old('accident_hapn_nat_hoilday', $owner->accident_hapn_nat_hoilday) }}">
                    </div>

                    <!-- Date Claim Intimated -->
                    <div class="mb-3">
                        <label for="owner_date_claim_intimated" class="form-label">Date of Claim Intimated:</label>
                        <input type="date" name="date_claim_intimated" id="owner_date_claim_intimated" class="form-control" value="{{ old('date_claim_intimated', $owner->date_claim_intimated) }}">
                    </div>

                    <!-- Reason for Late Intimation -->
                    <div class="mb-3">
                        <label for="owner_reason_late_intimation" class="form-label">Reason for Late Intimation:</label>
                        <input type="text" name="reason_late_intimation" id="owner_reason_late_intimation" class="form-control" value="{{ old('reason_late_intimation', $owner->reason_late_intimation) }}">
                    </div>

                    <!-- Insured Profession -->
                    <div class="mb-3">
                        <label for="owner_insured_profession" class="form-label">Insured Profession:</label>
                        <input type="text" name="insured_profession" id="owner_insured_profession" class="form-control" value="{{ old('insured_profession', $owner->insured_profession) }}">
                    </div>

                    <!-- Is Negative Area Address -->
                    <div class="mb-3">
                        <label for="owner_is_neg_area_address" class="form-label">Is Negative Area Address:</label>
                        <select name="is_neg_area_address" id="owner_is_neg_area_address" class="form-control">
                            <option value="1" @if($owner->is_neg_area_address == 'yes') selected @endif>Yes</option>
                            <option value="0" @if($owner->is_neg_area_address == 'no') selected @endif>No</option>
                        </select>
                    </div>

                    <!-- Pan Number Owner -->
                    <div class="mb-3">
                        <label for="owner_pan_no_owner" class="form-label">Pan Number of Owner:</label>
                        <input type="text" name="pan_no_owner" id="owner_pan_no_owner" class="form-control" value="{{ old('pan_no_owner', $owner->pan_no_owner) }}">
                    </div>

                    <!-- Aadhar Number Owner -->
                    <div class="mb-3">
                        <label for="owner_aadhar_no_owner" class="form-label">Aadhar Number of Owner:</label>
                        <input type="text" name="aadhar_no_owner" id="owner_aadhar_no_owner" class="form-control" value="{{ old('aadhar_no_owner', $owner->aadhar_no_owner) }}">
                    </div>

                    <!-- Hypothecation Details -->
                    <div class="mb-3">
                        <label for="owner_hyp_details" class="form-label">HYP Details:</label>
                        <input type="text" name="hyp_details" id="owner_hyp_details" class="form-control" value="{{ old('hyp_details', $owner->hyp_details) }}">
                    </div>

                    <!-- Vehicle Damages -->
                    <div class="mb-3">
                        <label for="owner_vehicle_damages" class="form-label">Vehicle Damages:</label>
                        <input type="text" name="vehicle_damages" id="owner_vehicle_damages" class="form-control" value="{{ old('vehicle_damages', $owner->vehicle_damages) }}">
                    </div>

                    <!-- Commercial Vehicle Details -->
                    <div class="mb-3">
                        <label for="owner_comercl_vehicle_details" class="form-label">Commercial Vehicle Details:</label>
                        <input type="text" name="comercl_vehicle_details" id="owner_comercl_vehicle_details" class="form-control" value="{{ old('comercl_vehicle_details', $owner->comercl_vehicle_details) }}">
                    </div>

                    <!-- Material Loss in Accident -->
                    <div class="mb-3">
                        <label for="owner_material_loss_accidnt" class="form-label">Material Loss in Accident:</label>
                        <input type="text" name="material_loss_accidnt" id="owner_material_loss_accidnt" class="form-control" value="{{ old('material_loss_accidnt', $owner->material_loss_accidnt) }}">
                    </div>

                    <!-- Material Quantity -->
                    <div class="mb-3">
                        <label for="owner_material_quantity" class="form-label">Material Quantity:</label>
                        <input type="number" name="material_quantity" id="owner_material_quantity" class="form-control" value="{{ old('material_quantity', $owner->material_quantity) }}">
                    </div>

                    <!-- Is Load Receipt Available -->
                    <div class="mb-3">
                        <label for="owner_is_load_recept_available" class="form-label">Is Load Receipt Available:</label>
                        <select name="is_load_recept_available" id="owner_is_load_recept_available" class="form-control">
                            <option value="1" @if($owner->is_load_recept_available == 'yes') selected @endif>Yes</option>
                            <option value="0" @if($owner->is_load_recept_available == 'no') selected @endif>No</option>
                        </select>
                    </div>

                    <!-- Fitness Details -->
                    <div class="mb-3">
                        <label for="owner_fitness_details" class="form-label">Fitness Details:</label>
                        <input type="text" name="fitness_details" id="owner_fitness_details" class="form-control" value="{{ old('fitness_details', $owner->fitness_details) }}">
                    </div>

                    <!-- Area Covered -->
                    <div class="mb-3">
                        <label for="owner_area_covered" class="form-label">Area Covered:</label>
                        <input type="text" name="area_covered" id="owner_area_covered" class="form-control" value="{{ old('area_covered', $owner->area_covered) }}">
                    </div>

                    <!-- Previous Insurer -->
                    <div class="mb-3">
                        <label for="owner_prev_insurer" class="form-label">Previous Insurer:</label>
                        <input type="text" name="prev_insurer" id="owner_prev_insurer" class="form-control" value="{{ old('prev_insurer', $owner->prev_insurer) }}">
                    </div>

                    <!-- Previous Policy Number -->
                    <div class="mb-3">
                        <label for="owner_prev_policy_no" class="form-label">Previous Policy Number:</label>
                        <input type="text" name="prev_policy_no" id="owner_prev_policy_no" class="form-control" value="{{ old('prev_policy_no', $owner->prev_policy_no) }}">
                    </div>

                    <!-- Policy Details -->
                    <div class="mb-3">
                        <label for="owner_policy_detl" class="form-label">Policy Details:</label>
                        <input type="text" name="policy_detl" id="owner_policy_detl" class="form-control" value="{{ old('policy_detl', $owner->policy_detl) }}">
                    </div>

                    <!-- Any Claim in Previous Policy -->
                    <div class="mb-3">
                        <label for="owner_any_clain_in_prev_policy" class="form-label">Any Claim in Previous Policy:</label>
                        <select name="any_clain_in_prev_policy" id="owner_any_clain_in_prev_policy" class="form-control">
                            <option value="1" @if($owner->any_clain_in_prev_policy == 'yes') selected @endif>Yes</option>
                            <option value="0" @if($owner->any_clain_in_prev_policy == 'no') selected @endif>No</option>
                        </select>
                    </div>

                    <!-- Previous Claim Photo Available -->
                    <div class="mb-3">
                        <label for="owner_is_prev_claim_photo_available" class="form-label">Previous Claim Photo Available:</label>
                        <select name="is_prev_claim_photo_available" id="owner_is_prev_claim_photo_available" class="form-control">
                            <option value="1" @if($owner->is_prev_claim_photo_available == 'yes') selected @endif>Yes</option>
                            <option value="0" @if($owner->is_prev_claim_photo_available == 'no') selected @endif>No</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="owner_social_status" class="form-label">Owner Social Status</label>
                        <input type="text" name="social_status" id="social_status" class="form-control" value="{{ old('social_status', $owner->social_status) }}">
                    </div>

                    <!-- Submit Button -->
                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary">Save Changes</button>
                    </div>
                </form>
            </div>


            @if ($owner->owner_written_statment_images)
            <h6>Owner Written Statement Images:</h6>
            <div>
                @foreach (json_decode($owner->owner_written_statment_images) as $image)
                <input type="checkbox" id="ownerStatmentImage-{{ $owner->id }}-{{ $loop->index }}-{{$report->case_id}}" name="ownerStatmentImages[]" value="{{ asset('storage/' . $image) }}">
                <a href="{{ asset('storage/' . $image) }}" data-lightbox="owner-statement" data-title="Owner Written Statement Image">
                    <img src="{{ asset('storage/' . $image) }}" alt="Owner Written Statement Image" class="img-fluid mb-2" style="max-width: 200px;">
                </a>
                @endforeach
            </div>
            @endif

            <form id="update-form" action="{{ route('owner.update.written_statment_images', $owner->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <label for="files">Update Owner Written Statment Images :</label>
                <input type="file" name="owner_written_statment_images[]" multiple>
                @error('owner_written_statment_images.*')
                <div class="alert alert-danger">{{ $message }}</div>
                @enderror
                <button type="submit" class="btn btn-success mt-3">Submit</button>
            </form>

            @if ($owner->owner_aadhaar_card_images)
            <h6>Owner Aadhaar Card Images:</h6>
            <div>
                @foreach (json_decode($owner->owner_aadhaar_card_images) as $image)
                <input type="checkbox" id="ownerAadharImage-{{ $owner->id }}-{{ $loop->index }}-{{$report->case_id}}" name="ownerAadharImages[]" value="{{ asset('storage/' . $image) }}">
                <a href="{{ asset('storage/' . $image) }}" data-lightbox="owner-aadhaar" data-title="Owner Aadhaar Card Image">
                    <img src="{{ asset('storage/' . $image) }}" alt="Owner Aadhaar Card Image" class="img-fluid mb-2" style="max-width: 200px;">
                </a>
                @endforeach
            </div>
            @endif

            <form id="update-form" action="{{ route('owner.update.aadhaar_card_images', $owner->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <label for="files">Update Owner Aadhar Card Images :</label>
                <input type="file" name="owner_aadhaar_card_images[]" multiple>
                @error('owner_aadhaar_card_images.*')
                <div class="alert alert-danger">{{ $message }}</div>
                @enderror
                <button type="submit" class="btn btn-success mt-3">Submit</button>
            </form>

            @if ($owner->ration_card)
            <h6>Owner Ration Card:</h6>
            <div>
                @foreach (json_decode($owner->ration_card) as $image)
                <input type="checkbox" id="ownerRationImage-{{ $owner->id }}-{{ $loop->index }}-{{$report->case_id}}" name="ownerRationImages[]" value="{{ asset('storage/' . $image) }}">
                <a href="{{ asset('storage/' . $image) }}" data-lightbox="owner-ration-card" data-title="owner-ration-card">
                    <img src="{{ asset('storage/' . $image) }}" alt="owner-ration-card" class="img-fluid mb-2" style="max-width: 200px;">
                </a>
                @endforeach
            </div>
            @endif

            <form id="update-form" action="{{ route('owner.update.ration_card_images', $owner->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <label for="files">Update Owner Ration Card Images :</label>
                <input type="file" name="owner_ration_card[]" multiple>
                @error('owner_ration_card.*')
                <div class="alert alert-danger">{{ $message }}</div>
                @enderror
                <button type="submit" class="btn btn-success mt-3">Submit</button>
            </form>

            @if ($owner->driving_lic)
            <h6>Owner Ration Card:</h6>
            <div>
                @foreach (json_decode($owner->driving_lic) as $image)
                <input type="checkbox" id="ownerDlImage-{{ $owner->id }}-{{ $loop->index }}-{{$report->case_id}}" name="ownerDlImages[]" value="{{ asset('storage/' . $image) }}">
                <a href="{{ asset('storage/' . $image) }}" data-lightbox="owner-driving_lic" data-title="owner-driving_lic">
                    <img src="{{ asset('storage/' . $image) }}" alt="owner-ration-card" class="img-fluid mb-2" style="max-width: 200px;">
                </a>
                @endforeach
            </div>
            @endif

            <form id="update-form" action="{{ route('owner.update.driving_lic_images', $owner->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <label for="files">Update Owner Driving Licence Images :</label>
                <input type="file" name="owner_driving_lic[]" multiple>
                @error('owner_driving_lic.*')
                <div class="alert alert-danger">{{ $message }}</div>
                @enderror
                <button type="submit" class="btn btn-success mt-3">Submit</button>
            </form>

            @if ($owner->owner_voice)
            <h6>Owner Voice:</h6>
            @foreach (json_decode($owner->owner_voice) as $voice)
            <audio controls>
                <source src="{{ asset('storage/' . $voice) }}" type="audio/mpeg">
                Your browser does not support the audio tag.
            </audio>
            @endforeach
            @endif

            <form id="update-form" action="{{ route('owner.update.owner_voice', $owner->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <label for="files">Update Owner Voice :</label>
                <input type="file" name="owner_voice[]" multiple>
                @error('owner_voice.*')
                <div class="alert alert-danger">{{ $message }}</div>
                @enderror
                <button type="submit" class="btn btn-success mt-3">Submit</button>
            </form>
        </div>
    </div>
    @endforeach

    <hr>

    <h5>Accident Person Data</h5>
    @if ($report->accident_person_reassign_status == 1)
    <form action="{{ route('accident.person.re.assign') }}" method="POST" class="ajax-form">
        @csrf
        <input type="hidden" name="id" required value="{{ $report->id }}">
        <button type="submit" class="btn btn-danger">Re-Assign</button>
    </form>
    @endif
    @if ($report->accident_person_reassign_status == 0)
    <span class="badge badge-danger">Pending</span>
    @endif
    @foreach ($accidentPersonData as $index => $accident)
    <div class="card mb-3">
        <div class="card-body">


            <div class='row'>
                <div class="col-md-8"></div>
                <div class="col-md-2">
                    <a class='btn btn-danger' href="{{url('/accident/'.$accident->id)}}">Authorize Upload data</a>
                </div>
                <div class="col-md-2">
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#accidentModal{{ $accident->id }}">Edit Accident Data</button></p>
                </div>
            </div>

            <div class="form-check">
                <input class="form-check-input" type="radio" id="selectAccident{{ $accident->id }}" value="{{ $accident->id }}-{{$report->case_id}}">
                <label class="form-check-label" for="selectAccident{{ $accident->id }}">
                    Select this accident data
                </label>
            </div>


            <h6>Entry {{ $index + 1 }}</h6>
            <p><strong>Executive Name:</strong> {{ $accident->executive_name }}</p>
            <p><strong>FIR Version of Accident:</strong> {{ $accident->fir_vers_accdnt }}</p>
            <p><strong>Claiment Version of Accident:</strong> {{ $accident->claiment_vers_aaccdnt }}</p>
            <p><strong>Chargesheet Conclusion:</strong> {{ $accident->chrgesheet_con }}</p>
            <p><strong>Relationship of insured and Claiment:</strong> {{ $accident->relation_insured_and_claiment }}</p>
            <p><strong>Age of Injured Person:</strong> {{ $accident->age_injured_person }}</p>
            <p><strong>Profession of Injured Person:</strong> {{ $accident->profession_injured }}</p>
            <p><strong>Difference in Rider Version and Injured:</strong> {{ $accident->diff_in_rider_ver_injured }}</p>
            <p><strong>Name and Address of Hospital:</strong> {{ $accident->name_address_hospital }}</p>
            <p><strong>Distance Between Accident site to Hospital:</strong> {{ $accident->distance_from_site_hospital }}</p>
            <p><strong>Details of Big Hospital Near Accident site:</strong> {{ $accident->details_big_hospital_near_site }}</p>
            <p><strong>Accident Happen on Zebra Crossing or Not:</strong> @if($accident->zebra_cross_on_site==1 ) Yes @else NO @endif</p>
            <p><strong>Injury Record Details:</strong> {{ $accident->injury_record_details }}</p>
            <p><strong>Name of Witness:</strong> {{ $accident->name_witness }}</p>
            <p><strong>Name of Informer:</strong> {{ $accident->name_informer }}</p>
            <p><strong>Vechicle No in FIR:</strong> {{ $accident->vehicle_no_fir }}</p>
            <p><strong>Name Of Accused:</strong> {{ $accident->name_accused }}</p>
            <p><strong>Name Of injured:</strong> {{ $accident->injured_name }}</p>
            <p><strong>Phone No of Injured:</strong> {{ $accident->injured_phone }}</p>
            <p><strong>Address of Injured:</strong> {{ $accident->injured_address }}</p>
            <p><strong>Injured Pedstrian or Traveller :</strong> {{$accident->injured_pedstrian_or_traveller}}</p>
            <p><strong>Injured Person Addmited Hospital Report:</strong> {{ $accident->injured_hospital_report }}</p>
            <p><strong>Injured Admited Hospital details:</strong> {{ $accident->injured_admited_hospital_details}}</p>
            <p><strong>GD No:</strong> {{ $accident->gd_no}}</p>
            <p><strong>Police Station:</strong> {{ $accident->police_station}}</p>
            <p><strong>GD Date:</strong> {{ $accident->gd_date}}</p>
            <p><strong>Accident Date-Time:</strong> {{ $accident->accident_date_time}}</p>
            <p><strong>Accident Discription:</strong> {{ $accident->accident_description}}</p>
            <p><strong>Number of Occupants:</strong> {{ $accident->no_occupants }}</p>
            <p><strong>Complainant Relation to Injured:</strong> {{ $accident->complnt_rel_injured }}</p>
            <p><strong>Description of Accused:</strong> {{ $accident->descrition_accused }}</p>
            <p><strong>How did accused identified:</strong> {{ $accident->accused_identifed }}</p>
            <p><strong>Name of Complainant:</strong> {{ $accident->name_complainant }}</p>
            <p><strong>District:</strong> {{ $accident->district }}</p>
            <p><strong>State:</strong> {{ $accident->state }}</p>
            <p><strong>FIR Number:</strong> {{ $accident->fir_no }}</p>
            <p><strong>Section:</strong> {{ $accident->section }}</p>
            <p><strong>Date and Time of FIR:</strong> {{ $accident->date_time_fir }}</p>
            <p><strong>Delay, if any, in lodging FIR & Reasons thereof:</strong> {{ $accident->delay_in_fir }}</p>
            <p><strong>Gist of FIR:</strong> {{ $accident->gist_fir }}</p>
            <p><strong>Court where Charge-sheet/final report filed & CC No.:</strong> {{ $accident->court_where_chargesheet_filled }}</p>
            <p><strong>Date of Charge-Sheet/Final report:</strong> {{ $accident->date_chargesheet }}</p>
            <p><strong>Sections in the Charge-sheet:</strong> {{ $accident->section_chargesheet }}</p>
            <p><strong>Who all are charged with (e.g., Our Driver & Other Driver) :</strong> {{ $accident->who_charged_with }}</p>
            <p><strong>Gist of Chargesheet:</strong> {{ $accident->gist_chargesheet }}</p>
            <p><strong>Any Petty case charged, if so, details.:</strong> {{ $accident->petty_case_charged }}</p>
            <p><strong>Present status of the Criminal case:</strong> {{ $accident->status_criminal_case }}</p>
            <p><strong>Whether the Accident Genuine or Not as per the evidence gathered.:</strong> {{ $accident->accident_genuine }}</p>
            <p><strong>Opinion on Income of Injured/Deceased:</strong> {{ $accident->opnion_disablement }}</p>
            <p><strong>Opinion on what capacity the Injured/deceased travelled in the Vehicle.:</strong> {{ $accident->opnion_injured }}</p>
            <p><strong>Opinion on Contribution of our vehicle & Other Vehicle, if more than one Vehicle is involved.:</strong> {{ $accident->accident_cause_more_vehicle }}</p>
            <p><strong>Opinion on DL effectiveness/validity as on Date of Accident.:</strong> {{ $accident->opnion_dl_validity }}</p>
            <p><strong>Whether DL extract obtained, if not,reasons thereof.:</strong> {{ $accident->dl_extract_obtained }}</p>
            <p><strong>Whether involvement of the Injured/Deceased established from the FIR, if not, clarify.:</strong> {{ $accident->injured_established_fir }}</p>
            <p><strong>Whether involvement of Insured Vehicle is established from the FIR, if not, clarify:</strong> {{ $accident->insured_vehicle_establ_fir }}</p>
            <p><strong>Comments on defence on the evidence available :</strong> {{ $accident->comment_defence_evidence }}</p>
            <p><strong>Wound Certificate:</strong> {{ $accident->wound_certificate }}</p>
            <p><strong>WEARING OF PROTECTIVE HEAD WEAR/HELMET IN CASE OF HEAD INJURY
                    CAUSED TO TWO-WHEELER RIDER OR PILLION RIDER AS CLAIMANT:</strong> {{ $accident->helment_details }}</p>
            <p><strong>Result on verification of Medical Records:</strong> {{ $accident->verification_medical_report }}</p>
            <p><strong>Result of discussion with the Injured/deceased
                    family & neighbours :</strong> {{ $accident->discussion_family_neighbour }}</p>
            <p><strong>Discuss on what capacity the Injured/deceased
                    was travelling in the Vehicle:</strong> {{ $accident->role_injured }}</p>
            <p><strong>Result of discussion with the insured:</strong> {{ $accident->discussion_injured }}</p>
            <p><strong>Result of discussion with the employer for confirming the occupation & income of the injured/ deceased:</strong> {{ $accident->discussion_employeer_injured }}</p>
            <p><strong> Result of discussion with the rider of the IV:</strong> {{ $accident->discussion_rider }}</p>
            <p><strong>Discussion on the Financial Investigation on the Insured with regard to his employment,employer, salary, movable & immovable property, total monthly income, etc:</strong> {{ $accident->discussion_financial }}</p>
            <p><strong>Fact Findings:</strong> {{ $accident->fact_findings }}</p>
            <p><strong>Conclusion:</strong> {{ $accident->conclusion }}</p>
            <p><strong>Name, Address, and Employer:</strong> {{ $accident->name_add_num_employeer }}</p>
            <p><strong>Education of Injured:</strong> {{ $accident->edu_injured }}</p>
            <p><strong>Gap Sequential Event:</strong> {{ $accident->gap_sequencial_event }}</p>
            <p><strong>Date and Time of Hospital Admission:</strong> {{ $accident->date_time_hospital_addmission }}</p>
            <p><strong>Doctor's Name and First Aid:</strong> {{ $accident->doc_name_and_first_aid }}</p>
            <p><strong>Record Found at First Aid Hospital:</strong> {{ $accident->record_found_first_aid_hospital }}</p>
            <p><strong>FIR Named or Not:</strong> {{ $accident->fir_named_or_not }}</p>
            <p><strong>Absence Charge Sheet:</strong> {{ $accident->absence_chargesheet }}</p>
            <p><strong>Damage per OD Claim:</strong> {{ $accident->damage_per_od_claim }}</p>
            <p><strong>Date of Discharge:</strong> {{ $accident->date_discharge }}</p>
            <p><strong>Period of Absence from Work:</strong> {{ $accident->period_absence_from_work }}</p>
            <p><strong>Permanent Disability:</strong> {{ $accident->permanent_disablity }}</p>
            <p><strong>Present Condition of Injury:</strong> {{ $accident->present_condition_injury }}</p>
            <p><strong>Amount of Medical Expenses:</strong> {{ $accident->amount_medical_expenceses }}</p>
            <p><strong>Status of Medical Expense Reimbursement by Employer:</strong> {{ $accident->status_rembur_med_expense_employeer }}</p>
            <p><strong>Other Information:</strong> {{ $accident->other_information }}</p>
            <p><strong>Date of Panchnama:</strong> {{ $accident->date_panachanama }}</p>
            <p><strong>Opinion from Panchayath:</strong> {{ $accident->opnion_from_panchayath }}</p>
            <p><strong>Date of Death (as per Post Mortem):</strong> {{ $accident->date_of_death_date_pm }}</p>
            <p><strong>Cause of Death (as per PMR):</strong> {{ $accident->cause_death_per_pmr }}</p>
            <p><strong>Co-Injured Name and Address:</strong> {{ $accident->co_injured_name_address }}</p>
            <p><strong>Co-Injured Income:</strong> {{ $accident->co_injured_income }}</p>
            <p><strong>Co-Injured Age:</strong> {{ $accident->co_injured_age }}</p>
            <p><strong>Co-Injured Occupation:</strong> {{ $accident->co_injured_occupation }}</p>
            <p><strong>Co-Injured Relation to Injured:</strong> {{ $accident->co_injured_relation_injured }}</p>
            <p><strong>Co-Injured Dependent or Not:</strong> {{ $accident->co_injured_depend_or_not }}</p>
            <p><strong>Hit and Run Police Reach Conclusion:</strong> {{ $accident->hit_run_police_reach_conclusion }}</p>
            <p><strong>Gaps in Police Investigation:</strong> {{ $accident->gaps_police_investigation }}</p>
            <p><strong>Case Suspect Nexus:</strong> {{ $accident->case_suspect_nexus }}</p>
            <p><strong>TP Property Damage:</strong> {{ $accident->tp_property_damage }}</p>
            <p><strong>Policy Details:</strong> {{ $accident->policy_details }}</p>
            <p><strong>Insured Vehicle Paper Validity at Accident Time:</strong> {{ $accident->insured_vehicle_paper_valid_accident_time }}</p>
            <p><strong>Criminal Court Status of the Case:</strong> {{ $accident->criminal_court_status_case }}</p>
            <p><strong>Case Diary Findings:</strong> {{ $accident->case_diary_findings }}</p>
            <p><strong>About the Accident:</strong> {{ $accident->about_accident }}</p>
            <p><strong>Owner Verification:</strong> {{ $accident->owner_verification }}</p>
            <p><strong>Rider Verification:</strong> {{ $accident->rider_verification }}</p>
            <p><strong>Details of DL Validity:</strong> {{ $accident->details_dl_validity }}</p>
            <p><strong>TPV Details of DL Validity:</strong> {{ $accident->tpv_details_dl_validity }}</p>
            <p><strong>Nature of Claim:</strong> {{ $accident->nature_of_claim }}</p>
            <p><strong>Concern Matters:</strong> {{ $accident->concern_matters }}</p>
            <p><strong>Distance from Police:</strong> {{ $accident->dist_from_police }}</p>
            <p><strong>Relation of the Informer with the Claimant:</strong> {{ $accident->rel_informr_claimant }}</p>
            <p><strong>Address is Different ?:</strong> {{ $accident->address_different ? 'Yes' : 'No' }}</p>
            <p><strong>Annual Income of Injured:</strong> {{ number_format($accident->annual_incom_injured, 2) }}</p>
            <p><strong>In case the deceased was Gove. ----Employee/Semi Govt./PSU etc. check for his ESI coverage of reimbursement of medical expenses:</strong> {{ $accident->esi_coverage_details }}</p>
            <p><strong>Status of Injuries/Deceased:</strong> {{ $accident->status_injures_decesed }}</p>
            <p><strong>Compansation:</strong> {{ $accident->compass }}</p>
            <p><strong>Employment on compassionate ground if any OR pension details:</strong> {{ $accident->pension_details }}</p>
            <p><strong>In the case of death,does the deceased succumbed to injuries on the spot:</strong> {{ $accident->injured_death_on_spot ? 'Yes' : 'No' }}</p>
            <p><strong>PMR Number:</strong> {{ $accident->pmr_no }}</p>
            <p><strong>PMR Date:</strong> {{ $accident->pmr_date && \Carbon\Carbon::parse($accident->pmr_date)->isValid() ? \Carbon\Carbon::parse($accident->pmr_date)->format('d-m-Y') : 'N/A' }}</p>
            <p><strong>Name of the Hospital where PMR carried:</strong> {{ $accident->hospital_name_pmr_carried }}</p>
            <p><strong>Weather fit for compromise:</strong> {{ $accident->injured_compromised ? 'Yes' : 'No' }}</p>
            <p><strong>What is the defense if not fit for compromise:</strong> {{ $accident->defense_fit_compromize ? 'Yes' : 'No' }}</p>

            <p><strong>Wound Certificate Comment:</strong> {{ $accident->wound_certi_coment ?? 'N/A' }}</p>

            <p><strong>Number of Injured:</strong> {{ $accident->no_injured ?? 'N/A' }}</p>

            <p><strong>Number of Dead:</strong> {{ $accident->no_dead ?? 'N/A' }}</p>

            <p><strong>Number of TPPD:</strong> {{ $accident->no_tppd ?? 'N/A' }}</p>

            <p><strong>Comprehensive:</strong> {{ $accident->comperhensive ? 'Yes' : 'No' }}</p>

            <p><strong>Date of Birth of Injured:</strong> {{ $accident->dob_injured ?? 'N/A' }}</p>

            <p><strong>Aadhar Number of Injured:</strong> {{ $accident->aadhar_no_injured ?? 'N/A' }}</p>

            <p><strong>PAN Number:</strong> {{ $accident->pan_no ?? 'N/A' }}</p>

            <p><strong>Period of Leave Avail:</strong> {{ $accident->period_leave_avail ?? 'N/A' }}</p>

            <p><strong>Source of Income:</strong> {{ $accident->source_income ?? 'N/A' }}</p>

            <p><strong>Monthly Income:</strong> {{ $accident->month_incom ?? 'N/A' }}</p>

            <p><strong>Wound Certificate Short Description:</strong> {{ $accident->wound_certificate_short_disc ?? 'N/A' }}</p>

            <p><strong>Injured Examination Date on Wound Certificate:</strong> {{ $accident->injured_exami_date_on_woundcerti ?? 'N/A' }}</p>

            <p><strong>Injury Place on Wound Certificate:</strong> {{ $accident->injury_place_on_wound_certi ?? 'N/A' }}</p>

            <p><strong>Person Who Brought Injured:</strong> {{ $accident->person_name_who_brought_injured ?? 'N/A' }}</p>

            <p><strong>Hospitalized From Date:</strong> {{ $accident->hospitalised_from_date ?? 'N/A' }}</p>

            <p><strong>Hospitalized To Date:</strong> {{ $accident->hospitalised_to_date ?? 'N/A' }}</p>

            <p><strong>IP Days:</strong> {{ $accident->ip_days ?? 'N/A' }}</p>

            <p><strong>Verified Treatment Records:</strong> {{ $accident->verified_treament_records ?? 'N/A' }}</p>

            <p><strong>Postmortem Report Details:</strong> {{ $accident->details_postmortam_report ?? 'N/A' }}</p>

            <p><strong>Legal Heir's Name:</strong> {{ $accident->legal_heirs_name ?? 'N/A' }}</p>

            <p><strong>Heir's Age:</strong> {{ $accident->heirs_age ?? 'N/A' }}</p>

            <p><strong>Relationship to Deceased:</strong> {{ $accident->heirs_rel_deseased ?? 'N/A' }}</p>

            <p><strong>Heir's Marital Status:</strong> {{ $accident->marital_status_heirs ?? 'N/A' }}</p>

            <p><strong>Heir's Residing Address:</strong> {{ $accident->heirs_residing_address ?? 'N/A' }}</p>

            <p><strong>Heir's Occupation:</strong> {{ $accident->heirs_occupation ?? 'N/A' }}</p>

            <p><strong>Heir's Employer Address:</strong> {{ $accident->heirs_employer_address ?? 'N/A' }}</p>


            <h5>Download Data Section</h5>


            @if(isset($accident->accident_downloads) )

            @foreach(json_decode($accident->accident_downloads) as $file)
            @php

            $extension = pathinfo($file, PATHINFO_EXTENSION);

            $label = '';
            switch (strtolower($extension)) {
            case 'jpg':
            case 'jpeg':
            case 'png':
            case 'gif':
            case 'svg':
            $label = 'accident image';
            break;
            case 'pdf':
            case 'zip':
            $label = 'accident medical report';
            break;
            case 'aac':
            case 'mp3':
            case 'wav':
            $label = 'accident voice';
            break;
            case 'mp4':
            case 'avi':
            case 'mov':
            case 'wmv':
            case 'mkv':
            $label = 'accident video file';
            break;
            default:
            $label = 'unknown file';
            }
            @endphp

            <ul>

                <li>
                    <a href="{{ asset('storage/' . $file) }}" target="_blank" download>{{ $label }}</a>
                </li>

            </ul>

            @endforeach
            @else
            <p>No files available for download.</p>
            @endif


            <!-- Bootstrap Modal -->
            <div class="modal fade" id="accidentModal{{$accident->id}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-xl">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Accident Person's Data</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div id="edit-form-accident{{$accident->id}}">
                                <h3 class="text-center">Update Accident Data</h3>
                                <form method="POST" action="{{ route('accident.update.accident_data', $accident->id) }}" class="p-4 border rounded bg-light">
                                    @csrf
                                    @method('PUT')

                                    <!-- Executive Name -->
                                    <div class="mb-3">
                                        <label for="executive_name" class="form-label">Executive Name:</label>
                                        <input type="text" name="executive_name" id="executive_name" class="form-control" value="{{ old('executive_name', $accident->executive_name) }}">
                                    </div>

                                    <!-- FIR Version of Accident -->
                                    <div class="mb-3">
                                        <label for="fir_vers_accdnt" class="form-label">FIR Version of Accident:</label>
                                        <textarea name="fir_vers_accdnt" id="fir_vers_accdnt" class="form-control">{{ old('fir_vers_accdnt', $accident->fir_vers_accdnt) }}</textarea>
                                    </div>

                                    <!-- Claiment Version of Accident -->
                                    <div class="mb-3">
                                        <label for="claiment_vers_aaccdnt" class="form-label">Claiment Version of Accident:</label>
                                        <textarea name="claiment_vers_aaccdnt" id="claiment_vers_aaccdnt" class="form-control">{{ old('claiment_vers_aaccdnt', $accident->claiment_vers_aaccdnt) }}</textarea>
                                    </div>

                                    <!-- Chargesheet Conclusion -->
                                    <div class="mb-3">
                                        <label for="chrgesheet_con" class="form-label">Chargesheet Conclusion:</label>
                                        <textarea name="chrgesheet_con" id="chrgesheet_con" class="form-control">{{ old('chrgesheet_con', $accident->chrgesheet_con) }}</textarea>
                                    </div>

                                    <!-- Relationship of insured and Claiment -->
                                    <div class="mb-3">
                                        <label for="relation_insured_and_claiment" class="form-label">Relationship of Insured and Claiment:</label>
                                        <input type="text" name="relation_insured_and_claiment" id="relation_insured_and_claiment" class="form-control" value="{{ old('relation_insured_and_claiment', $accident->relation_insured_and_claiment) }}">
                                    </div>

                                    <!-- Age of Injured Person -->
                                    <div class="mb-3">
                                        <label for="age_injured_person" class="form-label">Age of Injured Person:</label>
                                        <input type="number" name="age_injured_person" id="age_injured_person" class="form-control" value="{{ old('age_injured_person', $accident->age_injured_person) }}">
                                    </div>

                                    <!-- Profession of Injured Person -->
                                    <div class="mb-3">
                                        <label for="profession_injured" class="form-label">Profession of Injured Person:</label>
                                        <input type="text" name="profession_injured" id="profession_injured" class="form-control" value="{{ old('profession_injured', $accident->profession_injured) }}">
                                    </div>

                                    <!-- Difference in Rider Version and Injured -->
                                    <div class="mb-3">
                                        <label for="diff_in_rider_ver_injured" class="form-label">Difference in Rider Version and Injured:</label>
                                        <textarea name="diff_in_rider_ver_injured" id="diff_in_rider_ver_injured" class="form-control">{{ old('diff_in_rider_ver_injured', $accident->diff_in_rider_ver_injured) }}</textarea>
                                    </div>

                                    <!-- Name and Address of Hospital -->
                                    <div class="mb-3">
                                        <label for="name_address_hospital" class="form-label">Name and Address of Hospital:</label>
                                        <textarea name="name_address_hospital" id="name_address_hospital" class="form-control">{{ old('name_address_hospital', $accident->name_address_hospital) }}</textarea>
                                    </div>

                                    <!-- Distance Between Accident site to Hospital -->
                                    <div class="mb-3">
                                        <label for="distance_from_site_hospital" class="form-label">Distance Between Accident Site to Hospital:</label>
                                        <input type="text" name="distance_from_site_hospital" id="distance_from_site_hospital" class="form-control" value="{{ old('distance_from_site_hospital', $accident->distance_from_site_hospital) }}">
                                    </div>

                                    <!-- Details of Big Hospital Near Accident Site -->
                                    <div class="mb-3">
                                        <label for="details_big_hospital_near_site" class="form-label">Details of Big Hospital Near Accident Site:</label>
                                        <textarea name="details_big_hospital_near_site" id="details_big_hospital_near_site" class="form-control">{{ old('details_big_hospital_near_site', $accident->details_big_hospital_near_site) }}</textarea>
                                    </div>

                                    <!-- Accident Happen on Zebra Crossing or Not -->
                                    <div class="mb-3">
                                        <label for="zebra_cross_on_site" class="form-label">Accident Happen on Zebra Crossing or Not:</label>
                                        <select name="zebra_cross_on_site" id="zebra_cross_on_site" class="form-control">
                                            <option value="1" {{ old('zebra_cross_on_site', $accident->zebra_cross_on_site) == 1 ? 'selected' : '' }}>Yes</option>
                                            <option value="0" {{ old('zebra_cross_on_site', $accident->zebra_cross_on_site) == 0 ? 'selected' : '' }}>No</option>
                                        </select>
                                    </div>

                                    <!-- Injury Record Details -->
                                    <div class="mb-3">
                                        <label for="injury_record_details" class="form-label">Injury Record Details:</label>
                                        <textarea name="injury_record_details" id="injury_record_details" class="form-control">{{ old('injury_record_details', $accident->injury_record_details) }}</textarea>
                                    </div>

                                    <!-- Name of Witness -->
                                    <div class="mb-3">
                                        <label for="name_witness" class="form-label">Name of Witness:</label>
                                        <input type="text" name="name_witness" id="name_witness" class="form-control" value="{{ old('name_witness', $accident->name_witness) }}">
                                    </div>

                                    <!-- Name of Informer -->
                                    <div class="mb-3">
                                        <label for="name_informer" class="form-label">Name of Informer:</label>
                                        <input type="text" name="name_informer" id="name_informer" class="form-control" value="{{ old('name_informer', $accident->name_informer) }}">
                                    </div>

                                    <div class="mb-3">
                                        <label for="name_informer" class="form-label">Name of Complainant:</label>
                                        <input type="text" name="name_complainant" id="name_complainant" class="form-control" value="{{ old('name_complainant', $accident->name_complainant) }}">
                                    </div>

                                    <!-- Vehicle No in FIR -->
                                    <div class="mb-3">
                                        <label for="vehicle_no_fir" class="form-label">Vehicle No in FIR:</label>
                                        <input type="text" name="vehicle_no_fir" id="vehicle_no_fir" class="form-control" value="{{ old('vehicle_no_fir', $accident->vehicle_no_fir) }}">
                                    </div>

                                    <!-- Name Of Accused -->
                                    <div class="mb-3">
                                        <label for="name_accused" class="form-label">Name of Accused:</label>
                                        <input type="text" name="name_accused" id="name_accused" class="form-control" value="{{ old('name_accused', $accident->name_accused) }}">
                                    </div>

                                    <!-- Name of Injured -->
                                    <div class="mb-3">
                                        <label for="injured_name" class="form-label">Name of Injured:</label>
                                        <input type="text" name="injured_name" id="injured_name" class="form-control" value="{{ old('injured_name', $accident->injured_name) }}">
                                    </div>

                                    <!-- Phone No of Injured -->
                                    <div class="mb-3">
                                        <label for="injured_phone" class="form-label">Phone No of Injured:</label>
                                        <input type="text" name="injured_phone" id="injured_phone" class="form-control" value="{{ old('injured_phone', $accident->injured_phone) }}">
                                    </div>

                                    <!-- Address of Injured -->
                                    <div class="mb-3">
                                        <label for="injured_address" class="form-label">Address of Injured:</label>
                                        <input type="text" name="injured_address" id="injured_address" class="form-control" value="{{ old('injured_address', $accident->injured_address) }}">
                                    </div>

                                    <!-- Injured Pedestrian or Traveller -->
                                    <div class="mb-3">
                                        <label for="injured_pedstrian_or_traveller" class="form-label">Injured Pedestrian or Traveller:</label>
                                        <select name="injured_pedstrian_or_traveller" id="injured_pedstrian_or_traveller" class="form-control">
                                            <option value="pedstrian" {{ old('injured_pedstrian_or_traveller', $accident->injured_pedstrian_or_traveller) == 'pedstrian' ? 'selected' : '' }}>Pedstrian</option>
                                            <option value="passenger" {{ old('injured_pedstrian_or_traveller', $accident->injured_pedstrian_or_traveller) == 'passenger' ? 'selected' : '' }}>Passenger</option>
                                            <option value="driver" {{ old('injured_pedstrian_or_traveller', $accident->injured_pedstrian_or_traveller) == 'driver' ? 'selected' : '' }}>Driver</option>
                                            <option value="rider" {{ old('injured_pedstrian_or_traveller', $accident->injured_pedstrian_or_traveller) == 'rider' ? 'selected' : '' }}>Rider</option>
                                            <option value="tillion" {{ old('injured_pedstrian_or_traveller', $accident->injured_pedstrian_or_traveller) == 'tillion' ? 'selected' : '' }}>Tillion</option>
                                        </select>
                                        </select>
                                    </div>

                                    <!-- Injured Person Admitted Hospital Report -->
                                    <div class="mb-3">
                                        <label for="injured_hospital_report" class="form-label">Injured Person Admitted Hospital Report:</label>
                                        <textarea name="injured_hospital_report" id="injured_hospital_report" class="form-control">{{ old('injured_hospital_report', $accident->injured_hospital_report) }}</textarea>
                                    </div>

                                    <!-- Injured Admitted Hospital Details -->
                                    <div class="mb-3">
                                        <label for="injured_admited_hospital_details" class="form-label">Injured Admitted Hospital Details:</label>
                                        <textarea name="injured_admited_hospital_details" id="injured_admited_hospital_details" class="form-control">{{ old('injured_admited_hospital_details', $accident->injured_admited_hospital_details) }}</textarea>
                                    </div>
                                    <!-- GD NO -->
                                    <div class="mb-3">
                                        <label for="gd_no" class="form-label">GD No:</label>
                                        <textarea name="gd_no" id="gd_no" class="form-control">{{ old('gd_no', $accident->gd_no) }}</textarea>
                                    </div>
                                    <!-- Police Station -->
                                    <div class="mb-3">
                                        <label for="police_station" class="form-label">Police Station:</label>
                                        <textarea name="police_station" id="police_station" class="form-control">{{ old('police_station', $accident->police_station) }}</textarea>
                                    </div>

                                    <!-- Distance Between Accident site to Hospital -->
                                    <div class="mb-3">
                                        <label for="gd_date" class="form-label">GD Date:</label>
                                        <input type="date" name="gd_date" id="gd_date" class="form-control" value="{{ old('gd_date', $accident->gd_date) }}">
                                    </div>

                                    <div class="mb-3">
                                        <label for="accident_date_time" class="form-label">Accident Date Time:</label>
                                        <input type="datetime-local" name="accident_date_time" id="gd_date" class="form-control" value="{{ old('accident_date_time', $accident->accident_date_time) }}">
                                    </div>

                                    <div class="mb-3">
                                        <label for="gd_date" class="form-label">Accident Discription:</label>
                                        <textarea type="text" name="accident_description" id="gd_date" class="form-control" value="">{{ old('accident_description', $accident->accident_description) }}</textarea>
                                    </div>

                                    <div class="mb-3">
                                        <label for="no_occupants" class="form-label">No. of occupants at the time of the Accident:</label>
                                        <input type="number" name="no_occupants" id="no_occupants" class="form-control" value="{{ old('no_occupants', $accident->no_occupants) }}">
                                    </div>

                                    <div class="mb-3">
                                        <label for="complnt_rel_injured" class="form-label">Complainant’s relationship with the
                                            Injured/ Deceased:</label>
                                        <input type="text" name="complnt_rel_injured" id="complnt_rel_injured" class="form-control" value="{{ old('complnt_rel_injured', $accident->complnt_rel_injured) }}">
                                    </div>

                                    <div class="mb-3">
                                        <label for="descrition_accused" class="form-label">Description of the Accused:</label>
                                        <textarea name="descrition_accused" id="descrition_accused" class="form-control">{{ old('descrition_accused', $accident->descrition_accused) }}</textarea>
                                    </div>

                                    <div class="mb-3">
                                        <label for="accused_identifed" class="form-label">How did accused identified:</label>
                                        <input type="text" name="accused_identifed" id="accused_identifed" class="form-control" value="{{ old('accused_identifed', $accident->accused_identifed) }}">
                                    </div>

                                    <div class="mb-3">
                                        <label for="district" class="form-label">District:</label>
                                        <input type="text" name="district" id="district" class="form-control" value="{{ old('district', $accident->district) }}">
                                    </div>

                                    <div class="mb-3">
                                        <label for="state" class="form-label">State:</label>
                                        <input type="text" name="state" id="state" class="form-control" value="{{ old('state', $accident->state) }}">
                                    </div>

                                    <div class="mb-3">
                                        <label for="fir_no" class="form-label">FIR Number:</label>
                                        <input type="text" name="fir_no" id="fir_no" class="form-control" value="{{ old('fir_no', $accident->fir_no) }}">
                                    </div>

                                    <div class="mb-3">
                                        <label for="section" class="form-label">Section:</label>
                                        <input type="text" name="section" id="section" class="form-control" value="{{ old('section', $accident->section) }}">
                                    </div>

                                    <div class="mb-3">
                                        <label for="date_time_fir" class="form-label">Date and Time of FIR:</label>
                                        <input type="datetime-local" name="date_time_fir" id="date_time_fir" class="form-control" value="{{ old('date_time_fir', $accident->date_time_fir) }}">
                                    </div>

                                    <div class="mb-3">
                                        <label for="delay_in_fir" class="form-label">Delay, if any, in lodging FIR & Reasons
                                            thereof:</label>
                                        <input type="text" name="delay_in_fir" id="delay_in_fir" class="form-control" value="{{ old('delay_in_fir', $accident->delay_in_fir) }}">
                                    </div>

                                    <div class="mb-3">
                                        <label for="gist_fir" class="form-label">Gist of FIR:</label>
                                        <textarea name="gist_fir" id="gist_fir" class="form-control">{{ old('gist_fir', $accident->gist_fir) }}</textarea>
                                    </div>

                                    <div class="mb-3">
                                        <label for="court_where_chargesheet_filled" class="form-label">Court where Charge-sheet/final report
                                            filed & CC No.:</label>
                                        <input type="text" name="court_where_chargesheet_filled" id="court_where_chargesheet_filled" class="form-control" value="{{ old('court_where_chargesheet_filled', $accident->court_where_chargesheet_filled) }}">
                                    </div>

                                    <div class="mb-3">
                                        <label for="date_chargesheet" class="form-label">Date of Charge-Sheet/Final report:</label>
                                        <input type="date" name="date_chargesheet" id="date_chargesheet" class="form-control" value="{{ old('date_chargesheet', $accident->date_chargesheet) }}">
                                    </div>

                                    <div class="mb-3">
                                        <label for="section_chargesheet" class="form-label">Sections in the Charge-sheet:</label>
                                        <input type="text" name="section_chargesheet" id="section_chargesheet" class="form-control" value="{{ old('section_chargesheet', $accident->section_chargesheet) }}">
                                    </div>

                                    <div class="mb-3">
                                        <label for="who_charged_with" class="form-label">Who all are charged with (e.g., Our
                                            Driver & Other Driver) :</label>
                                        <input type="text" name="who_charged_with" id="who_charged_with" class="form-control" value="{{ old('who_charged_with', $accident->who_charged_with) }}">
                                    </div>

                                    <div class="mb-3">
                                        <label for="gist_chargesheet" class="form-label">Gist of Chargesheet:</label>
                                        <textarea name="gist_chargesheet" id="gist_chargesheet" class="form-control">{{ old('gist_chargesheet', $accident->gist_chargesheet) }}</textarea>
                                    </div>

                                    <div class="mb-3">
                                        <label for="petty_case_charged" class="form-label">Any Petty case charged, if so, details:</label>
                                        <input type="text" name="petty_case_charged" id="petty_case_charged" class="form-control" value="{{ old('petty_case_charged', $accident->petty_case_charged) }}">
                                    </div>

                                    <div class="mb-3">
                                        <label for="status_criminal_case" class="form-label">Present status of the Criminal case.:</label>
                                        <input type="text" name="status_criminal_case" id="status_criminal_case" class="form-control" value="{{ old('status_criminal_case', $accident->status_criminal_case) }}">
                                    </div>

                                    <div class="mb-3">
                                        <label for="accident_genuine" class="form-label">Whether the Accident Genuine or Not as
                                            per the evidence gathered.:</label>
                                        <input type="text" name="accident_genuine" id="accident_genuine" class="form-control" value="{{ old('accident_genuine', $accident->accident_genuine) }}">
                                    </div>

                                    <div class="mb-3">
                                        <label for="opnion_disablement" class="form-label">Opinion on disablement/dependency:</label>
                                        <input type="text" name="opnion_disablement" id="opnion_disablement" class="form-control" value="{{ old('opnion_disablement', $accident->opnion_disablement) }}">
                                    </div>

                                    <div class="mb-3">
                                        <label for="opnion_injured" class="form-label">Opinion on what capacity the Injured/deceased travelled in the Vehicle.:</label>
                                        <input type="text" name="opnion_injured" id="opnion_injured" class="form-control" value="{{ old('opnion_injured', $accident->opnion_injured) }}">
                                    </div>

                                    <div class="mb-3">
                                        <label for="accident_cause_more_vehicle" class="form-label">Opinion on Contribution of our vehicle & Other Vehicle, if more than one Vehicle is involved.:</label>
                                        <input type="text" name="accident_cause_more_vehicle" id="accident_cause_more_vehicle" class="form-control" value="{{ old('accident_cause_more_vehicle', $accident->accident_cause_more_vehicle) }}">
                                    </div>

                                    <div class="mb-3">
                                        <label for="opnion_dl_validity" class="form-label">Opinion on DL effectiveness/validity as
                                            on Date of Accident.:</label>
                                        <input type="text" name="opnion_dl_validity" id="opnion_dl_validity" class="form-control" value="{{ old('opnion_dl_validity', $accident->opnion_dl_validity) }}">
                                    </div>

                                    <div class="mb-3">
                                        <label for="dl_extract_obtained" class="form-label">Whether DL extract obtained, if not,
                                            reasons thereof:</label>
                                        <input type="text" name="dl_extract_obtained" id="dl_extract_obtained" class="form-control" value="{{ old('dl_extract_obtained', $accident->dl_extract_obtained) }}">
                                    </div>

                                    <div class="mb-3">
                                        <label for="injured_established_fir" class="form-label">Whether involvement of the Injured/Deceased established from the FIR, if not, clarify.:</label>
                                        <input type="text" name="injured_established_fir" id="injured_established_fir" class="form-control" value="{{ old('injured_established_fir', $accident->injured_established_fir) }}">
                                    </div>

                                    <div class="mb-3">
                                        <label for="insured_vehicle_establ_fir" class="form-label">Whether involvement of Insured Vehicle
                                            is established from the FIR, if not, clarify:</label>
                                        <input type="text" name="insured_vehicle_establ_fir" id="insured_vehicle_establ_fir" class="form-control" value="{{ old('insured_vehicle_establ_fir', $accident->insured_vehicle_establ_fir) }}">
                                    </div>

                                    <div class="mb-3">
                                        <label for="comment_defence_evidence" class="form-label">Comments on defence on the evidence
                                            available :</label>
                                        <textarea name="comment_defence_evidence" id="comment_defence_evidence" class="form-control">{{ old('comment_defence_evidence', $accident->comment_defence_evidence) }}</textarea>
                                    </div>

                                    <div class="mb-3">
                                        <label for="wound_certificate" class="form-label">Wound Certificate:</label>
                                        <textarea name="wound_certificate" id="wound_certificate" class="form-control">{{ old('wound_certificate', $accident->wound_certificate) }}</textarea>
                                    </div>

                                    <div class="mb-3">
                                        <label for="helment_details" class="form-label">WEARING OF PROTECTIVE HEAD WEAR/HELMET IN CASE OF HEAD INJURY
                                            CAUSED TO TWO-WHEELER RIDER OR PILLION RIDER AS CLAIMANT:</label>
                                        <input type="text" name="helment_details" id="helment_details" class="form-control" value="{{ old('helment_details', $accident->helment_details) }}">
                                    </div>

                                    <div class="mb-3">
                                        <label for="verification_medical_report" class="form-label">Result on verification of Medical Records:</label>
                                        <textarea name="verification_medical_report" id="verification_medical_report" class="form-control">{{ old('verification_medical_report', $accident->verification_medical_report) }}</textarea>
                                    </div>

                                    <div class="mb-3">
                                        <label for="discussion_family_neighbour" class="form-label">Result of discussion with the Injured/deceased
                                            family & neighbours:</label>
                                        <textarea name="discussion_family_neighbour" id="discussion_family_neighbour" class="form-control">{{ old('discussion_family_neighbour', $accident->discussion_family_neighbour) }}</textarea>
                                    </div>

                                    <div class="mb-3">
                                        <label for="role_injured" class="form-label">Discuss on what capacity the Injured/deceased
                                            was travelling in the Vehicle:</label>
                                        <input type="text" name="role_injured" id="role_injured" class="form-control" value="{{ old('role_injured', $accident->role_injured) }}">
                                    </div>

                                    <div class="mb-3">
                                        <label for="discussion_injured" class="form-label">Result of discussion with the insured:</label>
                                        <textarea name="discussion_injured" id="discussion_injured" class="form-control">{{ old('discussion_injured', $accident->discussion_injured) }}</textarea>
                                    </div>

                                    <div class="mb-3">
                                        <label for="discussion_employeer_injured" class="form-label">Result of discussion with the employer for confirming the occupation & income of the injured/ deceased:</label>
                                        <textarea name="discussion_employeer_injured" id="discussion_employeer_injured" class="form-control">{{ old('discussion_employeer_injured', $accident->discussion_employeer_injured) }}</textarea>
                                    </div>

                                    <div class="mb-3">
                                        <label for="discussion_rider" class="form-label">Result of discussion with the rider of the IV:</label>
                                        <textarea name="discussion_rider" id="discussion_rider" class="form-control">{{ old('discussion_rider', $accident->discussion_rider) }}</textarea>
                                    </div>

                                    <div class="mb-3">
                                        <label for="discussion_financial" class="form-label"> Discussion on the Financial Investigation on the Insured with regard to his employment,employer, salary, movable & immovable property,total monthly income, etc:</label>
                                        <textarea name="discussion_financial" id="discussion_financial" class="form-control">{{ old('discussion_financial', $accident->discussion_financial) }}</textarea>
                                    </div>

                                    <div class="mb-3">
                                        <label for="fact_findings" class="form-label">Fact and Findings:</label>
                                        <textarea name="fact_findings" id="fact_findings" class="form-control">{{ old('fact_findings', $accident->fact_findings) }}</textarea>
                                    </div>

                                    <div class="mb-3">
                                        <label for="conclusion" class="form-label">Conclusion:</label>
                                        <textarea name="conclusion" id="conclusion" class="form-control">{{ old('conclusion', $accident->conclusion) }}</textarea>
                                    </div>

                                    <div class="mb-3">
                                        <label for="name_add_num_employeer" class="form-label">Name, Address, and Number of Employer:</label>
                                        <textarea name="name_add_num_employeer" id="name_add_num_employeer" class="form-control">{{ old('name_add_num_employeer', $accident->name_add_num_employeer) }}</textarea>
                                    </div>

                                    <div class="mb-3">
                                        <label for="edu_injured" class="form-label">Education of Injured:</label>
                                        <input type="text" name="edu_injured" id="edu_injured" class="form-control" value="{{ old('edu_injured', $accident->edu_injured) }}">
                                    </div>

                                    <div class="mb-3">
                                        <label for="gap_sequencial_event" class="form-label">Gap in Sequential Event:</label>
                                        <textarea name="gap_sequencial_event" id="gap_sequencial_event" class="form-control">{{ old('gap_sequencial_event', $accident->gap_sequencial_event) }}</textarea>
                                    </div>

                                    <div class="mb-3">
                                        <label for="date_time_hospital_addmission" class="form-label">Date and Time of Hospital Admission:</label>
                                        <input type="datetime-local" name="date_time_hospital_addmission" id="date_time_hospital_addmission" class="form-control" value="{{ old('date_time_hospital_addmission', $accident->date_time_hospital_addmission) }}">
                                    </div>

                                    <div class="mb-3">
                                        <label for="doc_name_and_first_aid" class="form-label">Doctor's Name and First Aid Provided:</label>
                                        <textarea name="doc_name_and_first_aid" id="doc_name_and_first_aid" class="form-control">{{ old('doc_name_and_first_aid', $accident->doc_name_and_first_aid) }}</textarea>
                                    </div>

                                    <div class="mb-3">
                                        <label for="record_found_first_aid_hospital" class="form-label">Record Found for First Aid at Hospital:</label>
                                        <textarea name="record_found_first_aid_hospital" id="record_found_first_aid_hospital" class="form-control">{{ old('record_found_first_aid_hospital', $accident->record_found_first_aid_hospital) }}</textarea>
                                    </div>

                                    <div class="mb-3">
                                        <label for="fir_named_or_not" class="form-label">FIR Named or Not:</label>
                                        <input type="text" name="fir_named_or_not" id="fir_named_or_not" class="form-control" value="{{ old('fir_named_or_not', $accident->fir_named_or_not) }}">
                                    </div>

                                    <div class="mb-3">
                                        <label for="absence_chargesheet" class="form-label">Absence in Chargesheet:</label>
                                        <textarea name="absence_chargesheet" id="absence_chargesheet" class="form-control">{{ old('absence_chargesheet', $accident->absence_chargesheet) }}</textarea>
                                    </div>

                                    <div class="mb-3">
                                        <label for="damage_per_od_claim" class="form-label">Damage Per OD Claim:</label>
                                        <textarea name="damage_per_od_claim" id="damage_per_od_claim" class="form-control">{{ old('damage_per_od_claim', $accident->damage_per_od_claim) }}</textarea>
                                    </div>

                                    <div class="mb-3">
                                        <label for="date_discharge" class="form-label">Date of Discharge:</label>
                                        <input type="date" name="date_discharge" id="date_discharge" class="form-control" value="{{ old('date_discharge', $accident->date_discharge) }}">
                                    </div>

                                    <div class="mb-3">
                                        <label for="period_absence_from_work" class="form-label">Period of Absence from Work:</label>
                                        <input type="text" name="period_absence_from_work" id="period_absence_from_work" class="form-control" value="{{ old('period_absence_from_work', $accident->period_absence_from_work) }}">
                                    </div>

                                    <div class="mb-3">
                                        <label for="permanent_disablity" class="form-label">Permanent Disability:</label>
                                        <textarea name="permanent_disablity" id="permanent_disablity" class="form-control">{{ old('permanent_disablity', $accident->permanent_disablity) }}</textarea>
                                    </div>

                                    <div class="mb-3">
                                        <label for="present_condition_injury" class="form-label">Present Condition of Injury:</label>
                                        <textarea name="present_condition_injury" id="present_condition_injury" class="form-control">{{ old('present_condition_injury', $accident->present_condition_injury) }}</textarea>
                                    </div>

                                    <div class="mb-3">
                                        <label for="amount_medical_expenceses" class="form-label">Amount of Medical Expenses:</label>
                                        <textarea name="amount_medical_expenceses" id="amount_medical_expenceses" class="form-control">{{ old('amount_medical_expenceses', $accident->amount_medical_expenceses) }}</textarea>
                                    </div>

                                    <div class="mb-3">
                                        <label for="status_rembur_med_expense_employeer" class="form-label">Status of Reimbursement for Medical Expenses (Employer):</label>
                                        <textarea name="status_rembur_med_expense_employeer" id="status_rembur_med_expense_employeer" class="form-control">{{ old('status_rembur_med_expense_employeer', $accident->status_rembur_med_expense_employeer) }}</textarea>
                                    </div>

                                    <div class="mb-3">
                                        <label for="other_information" class="form-label">Other Information:</label>
                                        <textarea name="other_information" id="other_information" class="form-control">{{ old('other_information', $accident->other_information) }}</textarea>
                                    </div>

                                    <div class="mb-3">
                                        <label for="date_panachanama" class="form-label">Date of Panchnama:</label>
                                        <input type="date" name="date_panachanama" id="date_panachanama" class="form-control" value="{{ old('date_panachanama', $accident->date_panachanama) }}">
                                    </div>

                                    <div class="mb-3">
                                        <label for="opnion_from_panchayath" class="form-label">Opinion from Panchayat:</label>
                                        <textarea name="opnion_from_panchayath" id="opnion_from_panchayath" class="form-control">{{ old('opnion_from_panchayath', $accident->opnion_from_panchayath) }}</textarea>
                                    </div>

                                    <div class="mb-3">
                                        <label for="date_of_death_date_pm" class="form-label">Date of Death or PM:</label>
                                        <input type="date" name="date_of_death_date_pm" id="date_of_death_date_pm" class="form-control" value="{{ old('date_of_death_date_pm', $accident->date_of_death_date_pm) }}">
                                    </div>

                                    <div class="mb-3">
                                        <label for="cause_death_per_pmr" class="form-label">Cause of Death per PMR:</label>
                                        <textarea name="cause_death_per_pmr" id="cause_death_per_pmr" class="form-control">{{ old('cause_death_per_pmr', $accident->cause_death_per_pmr) }}</textarea>
                                    </div>

                                    <div class="mb-3">
                                        <label for="co_injured_name_address" class="form-label">Co-Injured Name and Address:</label>
                                        <textarea name="co_injured_name_address" id="co_injured_name_address" class="form-control">{{ old('co_injured_name_address', $accident->co_injured_name_address) }}</textarea>
                                    </div>

                                    <div class="mb-3">
                                        <label for="co_injured_income" class="form-label">Co-Injured Income:</label>
                                        <textarea name="co_injured_income" id="co_injured_income" class="form-control">{{ old('co_injured_income', $accident->co_injured_income) }}</textarea>
                                    </div>

                                    <div class="mb-3">
                                        <label for="co_injured_age" class="form-label">Co-Injured Age:</label>
                                        <input type="text" name="co_injured_age" id="co_injured_age" class="form-control" value="{{ old('co_injured_age', $accident->co_injured_age) }}">
                                    </div>

                                    <div class="mb-3">
                                        <label for="co_injured_occupation" class="form-label">Co-Injured Occupation:</label>
                                        <textarea name="co_injured_occupation" id="co_injured_occupation" class="form-control">{{ old('co_injured_occupation', $accident->co_injured_occupation) }}</textarea>
                                    </div>

                                    <div class="mb-3">
                                        <label for="co_injured_relation_injured" class="form-label">Co-Injured Relation to Injured:</label>
                                        <textarea name="co_injured_relation_injured" id="co_injured_relation_injured" class="form-control">{{ old('co_injured_relation_injured', $accident->co_injured_relation_injured) }}</textarea>
                                    </div>

                                    <div class="mb-3">
                                        <label for="co_injured_depend_or_not" class="form-label">Co-Injured Dependent or Not:</label>
                                        <input type="text" name="co_injured_depend_or_not" id="co_injured_depend_or_not" class="form-control" value="{{ old('co_injured_depend_or_not', $accident->co_injured_depend_or_not) }}">
                                    </div>

                                    <div class="mb-3">
                                        <label for="hit_run_police_reach_conclusion" class="form-label">Hit and Run Police Reach Conclusion:</label>
                                        <textarea name="hit_run_police_reach_conclusion" id="hit_run_police_reach_conclusion" class="form-control">{{ old('hit_run_police_reach_conclusion', $accident->hit_run_police_reach_conclusion) }}</textarea>
                                    </div>

                                    <div class="mb-3">
                                        <label for="gaps_police_investigation" class="form-label">Gaps in Police Investigation:</label>
                                        <textarea name="gaps_police_investigation" id="gaps_police_investigation" class="form-control">{{ old('gaps_police_investigation', $accident->gaps_police_investigation) }}</textarea>
                                    </div>

                                    <div class="mb-3">
                                        <label for="case_suspect_nexus" class="form-label">Case Suspect Nexus:</label>
                                        <textarea name="case_suspect_nexus" id="case_suspect_nexus" class="form-control">{{ old('case_suspect_nexus', $accident->case_suspect_nexus) }}</textarea>
                                    </div>

                                    <div class="mb-3">
                                        <label for="tp_property_damage" class="form-label">TP Property Damage:</label>
                                        <textarea name="tp_property_damage" id="tp_property_damage" class="form-control">{{ old('tp_property_damage', $accident->tp_property_damage) }}</textarea>
                                    </div>

                                    <div class="mb-3">
                                        <label for="policy_details" class="form-label">Policy Details:</label>
                                        <textarea name="policy_details" id="policy_details" class="form-control">{{ old('policy_details', $accident->policy_details) }}</textarea>
                                    </div>

                                    <div class="mb-3">
                                        <label for="insured_vehicle_paper_valid_accident_time" class="form-label">Insured Vehicle Paper Validity at Accident Time:</label>
                                        <textarea name="insured_vehicle_paper_valid_accident_time" id="insured_vehicle_paper_valid_accident_time" class="form-control">{{ old('insured_vehicle_paper_valid_accident_time', $accident->insured_vehicle_paper_valid_accident_time) }}</textarea>
                                    </div>

                                    <div class="mb-3">
                                        <label for="criminal_court_status_case" class="form-label">Criminal Court Status of the Case:</label>
                                        <textarea name="criminal_court_status_case" id="criminal_court_status_case" class="form-control">{{ old('criminal_court_status_case', $accident->criminal_court_status_case) }}</textarea>
                                    </div>

                                    <div class="mb-3">
                                        <label for="case_diary_findings" class="form-label">Case Diary Findings:</label>
                                        <textarea name="case_diary_findings" id="case_diary_findings" class="form-control">{{ old('case_diary_findings', $accident->case_diary_findings) }}</textarea>
                                    </div>

                                    <div class="mb-3">
                                        <label for="about_accident" class="form-label">About the Accident:</label>
                                        <textarea name="about_accident" id="about_accident" class="form-control">{{ old('about_accident', $accident->about_accident) }}</textarea>
                                    </div>

                                    <div class="mb-3">
                                        <label for="owner_verification" class="form-label">Owner Verification:</label>
                                        <textarea name="owner_verification" id="owner_verification" class="form-control">{{ old('owner_verification', $accident->owner_verification) }}</textarea>
                                    </div>

                                    <div class="mb-3">
                                        <label for="rider_verification" class="form-label">Rider Verification:</label>
                                        <textarea name="rider_verification" id="rider_verification" class="form-control">{{ old('rider_verification', $accident->rider_verification) }}</textarea>
                                    </div>

                                    <div class="mb-3">
                                        <label for="details_dl_validity" class="form-label">Details of DL Validity:</label>
                                        <textarea name="details_dl_validity" id="details_dl_validity" class="form-control">{{ old('details_dl_validity', $accident->details_dl_validity) }}</textarea>
                                    </div>

                                    <div class="mb-3">
                                        <label for="tpv_details_dl_validity" class="form-label">TPV Details of DL Validity:</label>
                                        <textarea name="tpv_details_dl_validity" id="tpv_details_dl_validity" class="form-control">{{ old('tpv_details_dl_validity', $accident->tpv_details_dl_validity) }}</textarea>
                                    </div>

                                    <div class="mb-3">
                                        <label for="edu_injured" class="form-label">Claim No:</label>
                                        <input type="text" name="claim_no" id="edu_injured" class="form-control" value="{{ old('claim_no', $accident->claim_no) }}">
                                    </div>

                                    <div class="mb-3">
                                        <label for="edu_injured" class="form-label">Injured Marital Status:</label>
                                        <input type="text" name="injured_marital_status" id="injured_marital_status" class="form-control" value="{{ old('injured_marital_status', $accident->injured_marital_status) }}">
                                    </div>

                                    <div class="mb-3">
                                        <label for="edu_injured" class="form-label">co-Injured Marital Status:</label>
                                        <input type="text" name="co_injured_marital_status" id="injured_marital_status" class="form-control" value="{{ old('co_injured_marital_status', $accident->co_injured_marital_status) }}">
                                    </div>

                                    <div class="mb-3">
                                        <label for="claim_no" class="form-label">Claim No:</label>
                                        <input type="text" name="claim_no" id="claim_no" class="form-control" value="{{ old('claim_no', $accident->claim_no) }}">
                                    </div>

                                    <div class="mb-3">
                                        <label for="nature_of_claim" class="form-label">Nature of Claim:</label>
                                        <input type="text" name="nature_of_claim" id="nature_of_claim" class="form-control" value="{{ old('nature_of_claim', $accident->nature_of_claim) }}">
                                    </div>

                                    <div class="mb-3">
                                        <label for="concern_matters" class="form-label">Concern Matters:</label>
                                        <textarea name="concern_matters" id="concern_matters" class="form-control">{{ old('concern_matters', $accident->concern_matters) }}</textarea>
                                    </div>

                                    <div class="mb-3">
                                        <label for="dist_from_police" class="form-label">Distance from Police:</label>
                                        <input type="text" name="dist_from_police" id="dist_from_police" class="form-control" value="{{ old('dist_from_police', $accident->dist_from_police) }}">
                                    </div>

                                    <div class="mb-3">
                                        <label for="rel_informr_claimant" class="form-label"> Informer Relative of Claimant ?:</label>
                                        <input type="text" name="rel_informr_claimant" id="rel_informr_claimant" class="form-control" value="{{ old('rel_informr_claimant', $accident->rel_informr_claimant) }}">
                                    </div>

                                    <div class="mb-3">
                                        <label for="address_different" class="form-label">Is Address Different?</label>
                                        <select name="address_different" id="address_different" class="form-control">
                                            <option value="1" {{ old('address_different', $accident->address_different) == 1 ? 'selected' : '' }}>Yes</option>
                                            <option value="0" {{ old('address_different', $accident->address_different) == 0 ? 'selected' : '' }}>No</option>
                                        </select>
                                    </div>

                                    <div class="mb-3">
                                        <label for="annual_incom_injured" class="form-label">Annual Income of Injured:</label>
                                        <input type="number" step="0.01" name="annual_incom_injured" id="annual_incom_injured" class="form-control" value="{{ old('annual_incom_injured', $accident->annual_incom_injured) }}">
                                    </div>

                                    <div class="mb-3">
                                        <label for="esi_coverage_details" class="form-label">ESI Coverage Details:</label>
                                        <textarea name="esi_coverage_details" id="esi_coverage_details" class="form-control">{{ old('esi_coverage_details', $accident->esi_coverage_details) }}</textarea>
                                    </div>

                                    <div class="mb-3">
                                        <label for="status_injures_decesed" class="form-label">Status of Injuries/Deceased:</label>
                                        <textarea name="status_injures_decesed" id="status_injures_decesed" class="form-control">{{ old('status_injures_decesed', $accident->status_injures_decesed) }}</textarea>
                                    </div>

                                    <div class="mb-3">
                                        <label for="compass" class="form-label">Compansation:</label>
                                        <textarea name="compass" id="compass" class="form-control">{{ old('compass', $accident->compass) }}</textarea>
                                    </div>

                                    <div class="mb-3">
                                        <label for="pension_details" class="form-label">Pension Details:</label>
                                        <textarea name="pension_details" id="pension_details" class="form-control"> {{ old('pension_details', $accident->pension_details) }} </textarea>
                                    </div>

                                    <div class="mb-3">
                                        <label for="injured_death_on_spot" class="form-label">Injured or Death on Spot:</label>
                                        <select name="injured_death_on_spot" id="injured_death_on_spot" class="form-control">
                                            <option value="1" {{ old('injured_death_on_spot', $accident->injured_death_on_spot) == 1 ? 'selected' : '' }}>Yes</option>
                                            <option value="0" {{ old('injured_death_on_spot', $accident->injured_death_on_spot) == 0 ? 'selected' : '' }}>No</option>
                                        </select>
                                    </div>

                                    <div class="mb-3">
                                        <label for="pmr_no" class="form-label">PMR Number:</label>
                                        <input type="text" name="pmr_no" id="pmr_no" class="form-control" value="{{ old('pmr_no', $accident->pmr_no) }}">
                                    </div>

                                    <div class="mb-3">
                                        <label for="pmr_date" class="form-label">PMR Date:</label>
                                        <input type="date" name="pmr_date" id="pmr_date" class="form-control" value="{{ old('pmr_date', $accident->pmr_date ? $accident->pmr_date : '') }}">
                                    </div>

                                    <div class="mb-3">
                                        <label for="hospital_name_pmr_carried" class="form-label">Hospital Name (PMR Carried):</label>
                                        <input type="text" name="hospital_name_pmr_carried" id="hospital_name_pmr_carried" class="form-control" value="{{ old('hospital_name_pmr_carried', $accident->hospital_name_pmr_carried) }}">
                                    </div>

                                    <div class="mb-3">
                                        <label for="injured_compromised" class="form-label">Injured Compromised:</label>
                                        <select name="injured_compromised" id="injured_compromised" class="form-control">
                                            <option value="1" {{ old('injured_compromised', $accident->injured_compromised) == 1 ? 'selected' : '' }}>Yes</option>
                                            <option value="0" {{ old('injured_compromised', $accident->injured_compromised) == 0 ? 'selected' : '' }}>No</option>
                                        </select>
                                    </div>

                                    <div class="mb-3">
                                        <label for="defense_fit_compromize" class="form-label">Defense Fit Compromise:</label>
                                        <select name="defense_fit_compromize" id="defense_fit_compromize" class="form-control">
                                            <option value="1" {{ old('defense_fit_compromize', $accident->defense_fit_compromize) == 1 ? 'selected' : '' }}>Yes</option>
                                            <option value="0" {{ old('defense_fit_compromize', $accident->defense_fit_compromize) == 0 ? 'selected' : '' }}>No</option>
                                        </select>
                                    </div>

                                    <div class="mb-3">
                                        <label for="wound_certi_coment" class="form-label">Wound Certificate Comment:</label>
                                        <textarea name="wound_certi_coment" id="wound_certi_coment" class="form-control">{{ old('wound_certi_coment', $accident->wound_certi_coment) }}</textarea>
                                    </div>

                                    <div class="mb-3">
                                        <label for="no_injured" class="form-label">Number of Injured:</label>
                                        <input type="number" name="no_injured" id="no_injured" class="form-control" value="{{ old('no_injured', $accident->no_injured) }}">
                                    </div>

                                    <div class="mb-3">
                                        <label for="no_dead" class="form-label">Number of Dead:</label>
                                        <input type="number" name="no_dead" id="no_dead" class="form-control" value="{{ old('no_dead', $accident->no_dead) }}">
                                    </div>

                                    <div class="mb-3">
                                        <label for="no_tppd" class="form-label">Number of TPPD:</label>
                                        <input type="number" name="no_tppd" id="no_tppd" class="form-control" value="{{ old('no_tppd', $accident->no_tppd) }}">
                                    </div>

                                    <div class="mb-3">
                                        <label for="comperhensive" class="form-label">Comprehensive:</label>
                                        <select name="comperhensive" id="comperhensive" class="form-control">
                                            <option value="1" {{ old('comperhensive', $accident->comperhensive) == 1 ? 'selected' : '' }}>Yes</option>
                                            <option value="0" {{ old('comperhensive', $accident->comperhensive) == 0 ? 'selected' : '' }}>No</option>
                                        </select>
                                    </div>

                                    <div class="mb-3">
                                        <label for="dob_injured" class="form-label">Date of Birth of Injured:</label>
                                        <input type="date" name="dob_injured" id="dob_injured" class="form-control" value="{{ old('dob_injured', $accident->dob_injured) }}">
                                    </div>

                                    <div class="mb-3">
                                        <label for="aadhar_no_injured" class="form-label">Aadhar Number of Injured:</label>
                                        <input type="text" name="aadhar_no_injured" id="aadhar_no_injured" class="form-control" value="{{ old('aadhar_no_injured', $accident->aadhar_no_injured) }}">
                                    </div>

                                    <div class="mb-3">
                                        <label for="pan_no" class="form-label">PAN Number of Injured:</label>
                                        <input type="text" name="pan_no" id="pan_no" class="form-control" value="{{ old('pan_no', $accident->pan_no) }}">
                                    </div>

                                    <div class="mb-3">
                                        <label for="period_leave_avail" class="form-label">Period of Leave Avail:</label>
                                        <input type="number" name="period_leave_avail" id="period_leave_avail" class="form-control" value="{{ old('period_leave_avail', $accident->period_leave_avail) }}">
                                    </div>

                                    <div class="mb-3">
                                        <label for="source_income" class="form-label">Source of Income:</label>
                                        <input type="text" name="source_income" id="source_income" class="form-control" value="{{ old('source_income', $accident->source_income) }}">
                                    </div>

                                    <div class="mb-3">
                                        <label for="month_incom" class="form-label">Monthly Income:</label>
                                        <input type="number" step="0.01" name="month_incom" id="month_incom" class="form-control" value="{{ old('month_incom', $accident->month_incom) }}">
                                    </div>

                                    <div class="mb-3">
                                        <label for="wound_certificate_short_disc" class="form-label">Wound Certificate Short Description:</label>
                                        <textarea name="wound_certificate_short_disc" id="wound_certificate_short_disc" class="form-control">{{ old('wound_certificate_short_disc', $accident->wound_certificate_short_disc) }}</textarea>
                                    </div>

                                    <div class="mb-3">
                                        <label for="injured_exami_date_on_woundcerti" class="form-label">Injured Examination Date on Wound Certificate:</label>
                                        <input type="date" name="injured_exami_date_on_woundcerti" id="injured_exami_date_on_woundcerti" class="form-control" value="{{ old('injured_exami_date_on_woundcerti', $accident->injured_exami_date_on_woundcerti) }}">
                                    </div>

                                    <div class="mb-3">
                                        <label for="injury_place_on_wound_certi" class="form-label">Injury Place on Wound Certificate:</label>
                                        <input type="text" name="injury_place_on_wound_certi" id="injury_place_on_wound_certi" class="form-control" value="{{ old('injury_place_on_wound_certi', $accident->injury_place_on_wound_certi) }}">
                                    </div>

                                    <div class="mb-3">
                                        <label for="person_name_who_brought_injured" class="form-label">Person Who Brought Injured:</label>
                                        <input type="text" name="person_name_who_brought_injured" id="person_name_who_brought_injured" class="form-control" value="{{ old('person_name_who_brought_injured', $accident->person_name_who_brought_injured) }}">
                                    </div>

                                    <div class="mb-3">
                                        <label for="hospitalised_from_date" class="form-label">Hospitalized From Date:</label>
                                        <input type="date" name="hospitalised_from_date" id="hospitalised_from_date" class="form-control" value="{{ old('hospitalised_from_date', $accident->hospitalised_from_date) }}">
                                    </div>

                                    <div class="mb-3">
                                        <label for="hospitalised_to_date" class="form-label">Hospitalized To Date:</label>
                                        <input type="date" name="hospitalised_to_date" id="hospitalised_to_date" class="form-control" value="{{ old('hospitalised_to_date', $accident->hospitalised_to_date) }}">
                                    </div>

                                    <div class="mb-3">
                                        <label for="ip_days" class="form-label">IP Days:</label>
                                        <input type="number" name="ip_days" id="ip_days" class="form-control" value="{{ old('ip_days', $accident->ip_days) }}">
                                    </div>

                                    <div class="mb-3">
                                        <label for="verified_treament_records" class="form-label">Verified Treatment Records:</label>
                                        <textarea name="verified_treament_records" id="verified_treament_records" class="form-control">{{ old('verified_treament_records', $accident->verified_treament_records) }}</textarea>
                                    </div>

                                    <div class="mb-3">
                                        <label for="details_postmortam_report" class="form-label">Postmortem Report Details:</label>
                                        <textarea name="details_postmortam_report" id="details_postmortam_report" class="form-control">{{ old('details_postmortam_report', $accident->details_postmortam_report) }}</textarea>
                                    </div>

                                    <div class="mb-3">
                                        <label for="legal_heirs_name" class="form-label">Legal Heir's Name:</label>
                                        <input type="text" name="legal_heirs_name" id="legal_heirs_name" class="form-control" value="{{ old('legal_heirs_name', $accident->legal_heirs_name) }}">
                                    </div>

                                    <div class="mb-3">
                                        <label for="heirs_age" class="form-label">Heir's Age:</label>
                                        <input type="number" name="heirs_age" id="heirs_age" class="form-control" value="{{ old('heirs_age', $accident->heirs_age) }}">
                                    </div>

                                    <div class="mb-3">
                                        <label for="heirs_rel_deseased" class="form-label"> Heir's Relationship to Deceased:</label>
                                        <input type="text" name="heirs_rel_deseased" id="heirs_rel_deseased" class="form-control" value="{{ old('heirs_rel_deseased', $accident->heirs_rel_deseased) }}">
                                    </div>

                                    <div class="mb-3">
                                        <label for="marital_status_heirs" class="form-label">Heir's Marital Status:</label>
                                        <input type="text" name="marital_status_heirs" id="marital_status_heirs" class="form-control" value="{{ old('marital_status_heirs', $accident->marital_status_heirs) }}">
                                    </div>

                                    <div class="mb-3">
                                        <label for="heirs_residing_address" class="form-label">Heir's Residing Address:</label>
                                        <textarea name="heirs_residing_address" id="heirs_residing_address" class="form-control">{{ old('heirs_residing_address', $accident->heirs_residing_address) }}</textarea>
                                    </div>

                                    <div class="mb-3">
                                        <label for="heirs_occupation" class="form-label">Heir's Occupation:</label>
                                        <input type="text" name="heirs_occupation" id="heirs_occupation" class="form-control" value="{{ old('heirs_occupation', $accident->heirs_occupation) }}">
                                    </div>

                                    <div class="mb-3">
                                        <label for="heirs_employer_address" class="form-label">Heir's Employer Address:</label>
                                        <textarea name="heirs_employer_address" id="heirs_employer_address" class="form-control">{{ old('heirs_employer_address', $accident->heirs_employer_address) }}</textarea>
                                    </div>



                                    <!-- Submit Button -->
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-primary">Save Changes</button>
                                </form>
                            </div>
                        </div>
                        <!-- <div class="modal-footer">
                           
                        </div> -->
                    </div>
                </div>
            </div>


            @if ($accident->accident_person_images)
            <h6>Accident Person Images:</h6>
            <div>
                @foreach (json_decode($accident->accident_person_images) as $image)
                <input type="checkbox" id="accidentPersonImage-{{ $accident->id }}-{{ $loop->index }}-{{$report->case_id}}" name="accidentPersonImages[]" value="{{ asset('storage/' . $image) }}">
                <a href="{{ asset('storage/' . $image) }}" data-lightbox="accident-person" data-title="Accident Person Image">
                    <img src="{{ asset('storage/' . $image) }}" alt="Accident Person Image" class="img-fluid mb-2" style="max-width: 200px;">
                </a>
                @endforeach
            </div>
            @endif

            <form id="update-form" action="{{ route('accident.update.person_images', $accident->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <label for="files">Update Accident Person Images :</label>
                <input type="file" name="accident_person_images[]" multiple>
                @error('accident_person_images.*')
                <div class="alert alert-danger">{{ $message }}</div>
                @enderror
                <button type="submit" class="btn btn-success mt-3">Submit</button>
            </form>


            @if ($accident->medical_report)
            <h6>Accident Person Medical Report:</h6>
            <div>
                @foreach (json_decode($accident->medical_report) as $pdf)
                <a href="{{ asset('storage/' . $pdf) }}" target="_blank" class="btn btn-primary mb-2">
                    View Medical Report (PDF)
                </a>
                @endforeach
            </div>
            @endif

            <form id="update-form" action="{{ route('accident.update.medical_report', $accident->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <label for="files">Update Accident Person Medical Report :</label>
                <input type="file" name="accident_medical_report[]" multiple>
                @error('accident_medical_report.*')
                <div class="alert alert-danger">{{ $message }}</div>
                @enderror
                <button type="submit" class="btn btn-success mt-3">Submit</button>
            </form>

            @if ($accident->gd_pdf)
            <h6>General Data Pdf:</h6>
            <div>
                @foreach (json_decode($accident->gd_pdf) as $pdf)
                <a href="{{ asset('storage/' . $pdf) }}" target="_blank" class="btn btn-primary mb-2">
                    View GD Report (PDF)
                </a>
                @endforeach
            </div>
            @endif

            <form id="update-form" action="{{ route('accident.update.gd_pdf', $accident->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <label for="files">Update GD Report :</label>
                <input type="file" name="gd_pdf[]" multiple>
                @error('gd_pdf.*')
                <div class="alert alert-danger">{{ $message }}</div>
                @enderror
                <button type="submit" class="btn btn-success mt-3">Submit</button>
            </form>


            @if ($accident->hospital_report)
            <h6>Accident Person Hospital Report:</h6>
            <div>
                @foreach (json_decode($accident->hospital_report) as $pdf)
                <input type="checkbox" id="accidentHospitalImage-{{$accident->id }}-{{ $loop->index }}-{{$report->case_id}}" name="accidentHospitalImages[]" value="{{ asset('storage/' . $image) }}">
                <a href="{{ asset('storage/' . $pdf) }}" target="_blank" class="btn btn-primary mb-2">
                    View Hospital Report (PDF)
                </a>
                @endforeach
            </div>
            @endif

            <form id="update-form" action="{{ route('accident.update.hospital_report', $accident->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <label for="files">Update Accident Person hospital Report :</label>
                <input type="file" name="accident_hospital_report[]" multiple>
                @error('accident_hospital_report.*')
                <div class="alert alert-danger">{{ $message }}</div>
                @enderror
                <button type="submit" class="btn btn-success mt-3">Submit</button>
            </form>


            @if ($accident->ration_card)
            <h6>Accident Person's Ration Card:</h6>
            <div>
                @foreach (json_decode($accident->ration_card) as $image)
                <input type="checkbox" id="accidentRationImage-{{$accident->id }}-{{ $loop->index }}-{{$report->case_id}}" name="accidentRationImages[]" value="{{ asset('storage/' . $image) }}">
                <a href="{{ asset('storage/' . $image) }}" data-lightbox="accident-person" data-title="Accident Person Image">
                    @if($image) <img src="{{ asset('storage/' . $image) }}" alt="Accident Person" class="img-fluid mb-2" style="max-width: 200px;"> @else @endif
                </a>
                @endforeach
            </div>
            @endif

            <form id="update-form" action="{{ route('accident.update.ration_card', $accident->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <label for="files">Update Accident Person Ration Card :</label>
                <input type="file" name="accident_ration_card[]" multiple>
                @error('accident_ration_card.*')
                <div class="alert alert-danger">{{ $message }}</div>
                @enderror
                <button type="submit" class="btn btn-success mt-3">Submit</button>
            </form>

            @if ($accident->accident_person_aadhaar_card_images)
            <h6>Accident Person Aadhaar Card Images:</h6>
            <div>
                @foreach (json_decode($accident->accident_person_aadhaar_card_images) as $image)
                <input type="checkbox" id="accidentAadharImage-{{$accident->id }}-{{ $loop->index }}-{{$report->case_id}}" name="accidentAadharImages[]" value="{{ asset('storage/' . $image) }}">
                <a href="{{ asset('storage/' . $image) }}" data-lightbox="accident-person-aadhaar" data-title="Accident Person Aadhaar Card Image">
                    <img src="{{ asset('storage/' . $image) }}" alt="Accident Person Aadhaar Card Image" class="img-fluid mb-2" style="max-width: 200px;">
                </a>
                @endforeach
            </div>
            @endif

            <form id="update-form" action="{{ route('accident.update.aadhar_card', $accident->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <label for="files">Update Accident Person Aadhar Card :</label>
                <input type="file" name="accident_person_aadhaar_card_images[]" multiple>
                @error('accident_person_aadhaar_card_images.*')
                <div class="alert alert-danger">{{ $message }}</div>
                @enderror
                <button type="submit" class="btn btn-success mt-3">Submit</button>
            </form>

            @if ($accident->accident_person_written_statment_images)
            <h6>Accident Person Written Statement Images:</h6>
            <div>
                @foreach (json_decode($accident->accident_person_written_statment_images) as $image)
                <input type="checkbox" id="accidentStatmentImage-{{  $accident->id }}-{{ $loop->index }}-{{$report->case_id}}" name="accidentStatmentImages[]" value="{{ asset('storage/' . $image) }}">
                <a href="{{ asset('storage/' . $image) }}" data-lightbox="accident-person-statement" data-title="Accident Person Written Statement Image">
                    <img src="{{ asset('storage/' . $image) }}" alt="Accident Person Written Statement Image" class="img-fluid mb-2" style="max-width: 200px;">
                </a>
                @endforeach
            </div>
            @endif

            <form id="update-form" action="{{ route('accident.update.written_statment_images', $accident->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <label for="files">Update Accident Person Written Statement Images :</label>
                <input type="file" name="accident_person_written_statment_images[]" multiple>
                @error('accident_person_written_statment_images.*')
                <div class="alert alert-danger">{{ $message }}</div>
                @enderror
                <button type="submit" class="btn btn-success mt-3">Submit</button>
            </form>

            @if ($accident->accident_person_voice)
            <h6>Accident Person Voice:</h6>
            @foreach (json_decode($accident->accident_person_voice) as $voice)
            <audio controls>
                <source src="{{ asset('storage/' . $voice) }}" type="audio/mpeg">
                Your browser does not support the audio tag.
            </audio>
            @endforeach
            @endif

            <form id="update-form" action="{{ route('accident.update.accident_person_voice', $accident->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <label for="files">Update Accident Person Voice :</label>
                <input type="file" name="accident_person_voice[]" multiple>
                @error('accident_person_voice.*')
                <div class="alert alert-danger">{{ $message }}</div>
                @enderror
                <button type="submit" class="btn btn-success mt-3">Submit</button>
            </form>
        </div>
    </div>
    @endforeach
</div>



<style>
.form-check-input[type=radio] 
{
    border-color: black;
}
</style>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- Lightbox2 CSS -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.3/css/lightbox.min.css" rel="stylesheet" />
<!-- Lightbox2 JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.3/js/lightbox.min.js"></script>
<script>
    lightbox.option({
        'resizeDuration': 200,
        'wrapAround': true,
        'fadeDuration': 300
    });
</script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const radios = document.querySelectorAll('.toggle-edit');
        const wrappers = document.querySelectorAll('.field-wrapper');
        const submitBtn = document.getElementById('submitButtonWrapper');

        radios.forEach(radio => {
            radio.addEventListener('change', function () {
                // Hide all inputs
                wrappers.forEach(w => w.classList.add('d-none'));

                // Show the matching one
                const targetId = this.getAttribute('data-target');
                const targetWrapper = document.querySelector(targetId);
                if (targetWrapper) {
                    targetWrapper.classList.remove('d-none');
                }

                // Show submit button
                submitBtn.classList.remove('d-none');
            });
        });
    });
</script>


<script>
document.addEventListener('DOMContentLoaded', function () {
    // ===== Gate Entry Radios =====
    const gateRadios = document.querySelectorAll('.toggle-gate');
    const gateInputs = document.querySelectorAll('.garage-input-gate');
    const gateButton = document.getElementById('updateGateButtonWrapper');

    gateRadios.forEach(radio => {
        radio.addEventListener('change', function () {
            gateInputs.forEach(input => input.classList.add('d-none'));
            const input = document.getElementById(this.dataset.inputId);
            if (input) input.classList.remove('d-none');
            if (gateButton) gateButton.classList.remove('d-none');
        });
    });

    // ===== Garage Job Card Radios =====
    const jobRadios = document.querySelectorAll('.toggle-job');
    const jobInputs = document.querySelectorAll('.garage-input-job');
    const jobButton = document.getElementById('updateJobButtonWrapper');

    jobRadios.forEach(radio => {
        radio.addEventListener('change', function () {
            jobInputs.forEach(input => input.classList.add('d-none'));
            const input = document.getElementById(this.dataset.inputId);
            if (input) input.classList.remove('d-none');
            if (jobButton) jobButton.classList.remove('d-none');
        });
    });

    // ===== Fitness Particular Radios =====
    const fitnessRadios = document.querySelectorAll('.toggle-fitness');
    const fitnessSelects = document.querySelectorAll('.garage-select-fitness');
    const fitnessButton = document.getElementById('fitnessButton');

    fitnessRadios.forEach(radio => {
        radio.addEventListener('change', function () {
            fitnessSelects.forEach(select => select.classList.add('d-none'));
            const select = document.getElementById(`fitnessSelect${radio.id.replace('fitnessCheck', '')}`);
            if (select) select.classList.remove('d-none');
            if (fitnessButton) fitnessButton.classList.remove('d-none');
        });
    });

    const permitRadios = document.querySelectorAll('.toggle-permit');
    const permitSelects = document.querySelectorAll('.garage-select-permit');
    const permitButton = document.getElementById('permitButton');

    permitRadios.forEach(radio => {
        radio.addEventListener('change', function () {
            permitSelects.forEach(select => select.classList.add('d-none'));
            const select = document.getElementById(`permitSelect${radio.id.replace('permitCheck', '')}`);
            if (select) select.classList.remove('d-none');
            if (permitButton) permitButton.classList.remove('d-none');
        });
    });

    const particularRadios = document.querySelectorAll('.toggle-particular');
    const particularSelects = document.querySelectorAll('.garage-select-particular');
    const particularButton = document.getElementById('particularButton');

    particularRadios.forEach(radio => {
        radio.addEventListener('change', function () {
            particularSelects.forEach(select => select.classList.add('d-none'));
            const select = document.getElementById(`particularSelect${radio.id.replace('particularCheck', '')}`);
            if (select) select.classList.remove('d-none');
            if (particularButton) particularButton.classList.remove('d-none');
        });
    });


    const motorRadios = document.querySelectorAll('.toggle-motor');
    const motorSelects = document.querySelectorAll('.garage-select-motor');
    const motorButton = document.getElementById('motorButton');

    motorRadios.forEach(radio => {
        radio.addEventListener('change', function () {
            motorSelects.forEach(select => select.classList.add('d-none'));
            const select = document.getElementById(`motorSelect${radio.id.replace('motorCheck', '')}`);
            if (select) select.classList.remove('d-none');
            if (motorButton) motorButton.classList.remove('d-none');
        });
    });


});
</script>


<script>
    $(document).ready(function() {
        $('.ajax-form').on('submit', function(event) {
            event.preventDefault(); // Prevent default form submission
            var form = $(this);
            var url = form.attr('action');
            var formData = form.serialize(); // Serialize the form data

            $.ajax({
                type: 'POST',
                url: url,
                data: formData,
                success: function(response) {
                    // Handle success (show message, update UI, etc.)
                    alert('Re-Assigned successfully!');
                },
                error: function(xhr, status, error) {
                    // Handle error
                    alert('An error occurred: ' + error);
                }
            });
        });
    });
</script>

<script>
    function editFieldDriver(id) {
        const form = document.getElementById('edit-form' + id);
        if (form.style.display === 'block') {
            form.style.display = 'none';
        } else {
            form.style.display = 'block';
        }
    }

    function editFieldGarage(id) {
        const form = document.getElementById('edit-form-garage' + id);
        if (form.style.display === 'block') {
            form.style.display = 'none';
        } else {
            form.style.display = 'block';
        }
    }

    function editFieldSpot(id) {
        const form = document.getElementById('edit-form-spot' + id);
        if (form.style.display === 'block') {
            form.style.display = 'none';
        } else {
            form.style.display = 'block';
        }
    }

    function editFieldOwner(id) {
        const form = document.getElementById('edit-form-owner' + id);
        if (form.style.display === 'block') {
            form.style.display = 'none';
        } else {
            form.style.display = 'block';
        }
    }

    function editFieldAccident(id) {
        const form = document.getElementById('edit-form-accident' + id);
        if (form.style.display === 'block') {
            form.style.display = 'none';
        } else {
            form.style.display = 'block';
        }
    }

    //GARAGE

    $('input[type="radio"][id^="selectGarage"]').on('change', function() {
        var value = $(this).val();
        var parts = value.split('-');
        var garageId = parts[0];
        var caseId = parts[1];

        console.log('Garage ID:', garageId);
        console.log('Case ID:', caseId);

        var isChecked = $(this).prop('checked');


        if (isChecked) {

            postSelectedGarage(garageId, caseId);
        }
    });


    function postSelectedGarage(garageId, caseId) {
        console.log(garageId, caseId);
        $.ajax({
            url: "{{ route('garage.save.selected') }}",
            method: 'POST',
            data: {
                garage_id: garageId,
                case_id: caseId,
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                console.log(response.data.message);
                if (response.data.status === 200) {
                    console.log('Garage ID ' + garageId + ' selected successfully!');
                } else {
                    alert('Failed to select the garage.');
                }
            },
            error: function(xhr, status, error) {

                alert('Error: ' + error);
            }
        });
    }

    $('input[type="checkbox"][id^="gateImage"], input[type="checkbox"][id^="jobCardImage"], input[type="checkbox"][id^="vehicleImage"], input[type="checkbox"][id^="towVehicleReport"]').on('change', function() {

        // var selectedImages = collectSelectedImages();  
        var value = $(this).attr('id');
        var parts = value.split('-');

        var garageId = parts[1];
        var caseId = parts[3];
        var selectedImages = collectSelectedGarageImages(garageId, caseId);

        if (selectedImages.length > 0) {

            sendSelectedGarageImages(selectedImages, garageId, caseId);
        }
    });


    function collectSelectedGarageImages(garageId, caseId) {
        var selectedImages = [];
        $('input[type="checkbox"]:checked').each(function() {

            selectedImages.push($(this).val());

        });

        return selectedImages;
    }


    function sendSelectedGarageImages(selectedImages, garageId, caseId) {
        $.ajax({
            url: '{{ route("garage.save.selected.images") }}',
            method: 'POST',
            data: {
                garage_id: garageId,
                case_id: caseId,
                selected_garage_images: selectedImages,
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {

                if (response.data.status === 200) {
                    console.log('Selected images saved successfully!');
                } else {
                    alert('Failed to save selected images.');
                }
            },
            error: function(xhr, status, error) {

                alert('Warining: ' + 'Please select Garage Section Data First');
            }
        });
    }

    //Driver

    $('input[type="radio"][id^="selectDriver"]').on('change', function() {
        var value = $(this).val();
        var parts = value.split('-');
        var driverId = parts[0];
        var caseId = parts[1];
        var isChecked = $(this).prop('checked');
        console.log('Checkbox clicked for driver ID:', driverId);

        if (isChecked) {
            postSelectedDriver(driverId, caseId);
        }
    });


    function postSelectedDriver(driverId, caseId) {
        $.ajax({
            url: "{{route('driver.save.selected')}}",
            method: 'POST',
            data: {
                driver_id: driverId,
                case_id: caseId,
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                console.log(response.data.message);
                if (response.data.status === 200) {
                    console.log('Driver ID ' + driverId + ' selected successfully!');
                } else {
                    alert('Failed to select the driver.');
                }
            },
            error: function(xhr, status, error) {
                alert('Error: ' + error);
            }
        });
    }


    $('input[type="checkbox"][id^="rationCardImage"], input[type="checkbox"][id^="vehiclePermitImage"], input[type="checkbox"][id^="assetPicImage"], input[type="checkbox"][id^="driverImage"], input[type="checkbox"][id^="driverLicImage"], input[type="checkbox"][id^="driverRcImage"], input[type="checkbox"][id^="driverAadharImage"], input[type="checkbox"][id^="coDriverAadharImage"], input[type="checkbox"][id^="coPassengerDriverAadharImage"]').on('change', function() {

        var value = $(this).attr('id');
        var parts = value.split('-');
        var driverId = parts[1];
        var caseId = parts[3];
        var selectedImages = collectSelectedDriverImages(driverId, caseId);
        if (selectedImages.length > 0) {
            sendSelectedDriverImages(selectedImages, driverId, caseId);
        }
    });


    function collectSelectedDriverImages(driverId, caseId) {
        var selectedImages = [];
        $('input[type="checkbox"]:checked').each(function() {

            selectedImages.push($(this).val());

        });

        return selectedImages;
    }


    function sendSelectedDriverImages(selectedImages, driverId, caseId) {
        $.ajax({
            url: '{{route("driver.save.selected.images")}}',
            method: 'POST',
            data: {
                driver_id: driverId,
                case_id: caseId,
                selected_driver_images: selectedImages,
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                console.log(response.data.message);
                if (response.data.status === 200) {
                    console.log('Selected images saved successfully!');
                } else {
                    alert('Failed to save selected images.');
                }
            },
            error: function(xhr, status, error) {
                alert('Warining: ' + 'Please select Driver Section Data First');
            }
        });
    }

    //SPOT

    $('input[type="radio"][id^="selectSpot"]').on('change', function() {
        var value = $(this).val();
        var parts = value.split('-');
        var spotId = parts[0];
        var caseId = parts[1];
        var isChecked = $(this).prop('checked');
        console.log('Checkbox clicked for driver ID:', spotId);

        if (isChecked) {
            postSelectedSpot(spotId, caseId);
        }
    });


    function postSelectedSpot(spotId, caseId) {
        $.ajax({
            url: "{{route('spot.save.selected')}}",
            method: 'POST',
            data: {
                spot_id: spotId,
                case_id: caseId,
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                console.log(response.data.message);
                if (response.data.status === 200) {
                    console.log('Spot ID ' + spotId + ' selected successfully!');
                } else {
                    alert('Failed to select the Spot.');
                }
            },
            error: function(xhr, status, error) {
                alert('Warining: ' + 'Please select Spot Section Data First');
            }
        });
    }


    $('input[type="checkbox"][id^="spotImage"]').on('change', function() {

        var value = $(this).attr('id');
        var parts = value.split('-');
        var spotId = parts[1];
        var caseId = parts[3];
        var selectedImages = collectSelectedSpotImages(spotId, caseId);
        if (selectedImages.length > 0) {
            sendSelectedSpotImages(selectedImages, spotId, caseId);
        }
    });


    function collectSelectedSpotImages(spotId, caseId) {
        var selectedImages = [];

        $('input[type="checkbox"]:checked').each(function() {

            selectedImages.push($(this).val());

        });

        return selectedImages;
    }


    function sendSelectedSpotImages(selectedImages, spotId, caseId) {
        $.ajax({
            url: '{{route("spot.save.selected.images")}}',
            method: 'POST',
            data: {
                spot_id: spotId,
                case_id: caseId,
                selected_spot_images: selectedImages,
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {

                if (response.data.status === 200) {
                    console.log('Selected images saved successfully!');
                } else {
                    alert('Warining: ' + 'Please select Spot Section Data First');
                }
            },
            error: function(xhr, status, error) {
                alert('Warining: ' + 'Please select Spot Section Data First');
            }
        });
    }


    //OWNER

    $('input[type="radio"][id^="selectOwner"]').on('change', function() {
        var value = $(this).val();
        var parts = value.split('-');
        var ownerId = parts[0];
        var caseId = parts[1];
        var isChecked = $(this).prop('checked');
        console.log('Checkbox clicked for Owner ID:', ownerId);

        if (isChecked) {
            postSelectedOwner(ownerId, caseId);
        }
    });


    function postSelectedOwner(ownerId, caseId) {
        $.ajax({
            url: "{{route('owner.save.selected')}}",
            method: 'POST',
            data: {
                owner_id: ownerId,
                case_id: caseId,
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {

                if (response.data.status === 200) {
                    console.log('Owner ID ' + ownerId + ' selected successfully!');
                } else {
                    alert('Failed to select the owner.');
                }
            },
            error: function(xhr, status, error) {
                alert('Error: ' + error);
            }
        });
    }


    $('input[type="checkbox"][id^="ownerStatmentImage"],input[type="checkbox"][id^="ownerAadharImage"],input[type="checkbox"][id^="ownerRationImage"],input[type="checkbox"][id^="ownerDlImage"]').on('change', function() {

        var value = $(this).attr('id');
        var parts = value.split('-');
        var ownerId = parts[1];
        var caseId = parts[3];
        var selectedImages = collectSelectedOwnerImages();
        if (selectedImages.length > 0) {
            sendSelectedOwnerImages(selectedImages, ownerId, caseId);
        }
    });


    function collectSelectedOwnerImages() {
        var selectedImages = [];

        $('input[type="checkbox"]:checked').each(function() {

            selectedImages.push($(this).val());

        });

        return selectedImages;
    }


    function sendSelectedOwnerImages(selectedImages, ownerId, caseId) {
        $.ajax({
            url: '{{route("owner.save.selected.images")}}',
            method: 'POST',
            data: {
                owner_id: ownerId,
                case_id: caseId,
                selected_owner_images: selectedImages,
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {

                if (response.data.status === 200) {
                    console.log('Selected images saved successfully!');
                } else {
                    alert('Failed to save selected images.');
                }
            },
            error: function(xhr, status, error) {
                alert('Warining: ' + 'Please select Owner Section Data First');
            }
        });
    }


    //ACCIDENT

    $('input[type="radio"][id^="selectAccident"]').on('change', function() {
        var value = $(this).val();
        var parts = value.split('-');
        var accidentId = parts[0];
        var caseId = parts[1];
        var isChecked = $(this).prop('checked');
        console.log('Checkbox clicked for Accident ID:', accidentId);

        if (isChecked) {
            postSelectedAccident(accidentId, caseId);
        }
    });


    function postSelectedAccident(accidentId, caseId) {
        $.ajax({
            url: "{{route('accident.save.selected')}}",
            method: 'POST',
            data: {
                accident_id: accidentId,
                case_id: caseId,
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {

                if (response.data.status === 200) {
                    console.log('Accident ID ' + accidentId + ' selected successfully!');
                } else {
                    alert('Failed to select the Accident.');
                }
            },
            error: function(xhr, status, error) {
                alert('Error: ' + error);
            }
        });
    }


    $('input[type="checkbox"][id^="accidentPersonImage"],input[type="checkbox"][id^="accidentRationImage"],input[type="checkbox"][id^="accidentAadharImage"],input[type="checkbox"][id^="accidentStatmentImage"]').on('change', function() {

        var value = $(this).attr('id');
        var parts = value.split('-');
        var accidentId = parts[1];
        var caseId = parts[3];
        var selectedImages = collectSelectedAccidentImages();
        if (selectedImages.length > 0) {
            sendSelectedAccidentImages(selectedImages, accidentId, caseId);
        }
    });


    function collectSelectedAccidentImages() {
        var selectedImages = [];

        $('input[type="checkbox"]:checked').each(function() {

            selectedImages.push($(this).val());

        });

        return selectedImages;
    }


    function sendSelectedAccidentImages(selectedImages, accidentId, caseId) {
        $.ajax({
            url: '{{route("accident.save.selected.images")}}',
            method: 'POST',
            data: {
                accident_id: accidentId,
                case_id: caseId,
                selected_accident_images: selectedImages,
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {

                if (response.data.status === 200) {
                    console.log('Selected images saved successfully!');
                } else {
                    alert('Failed to save selected images.');
                }
            },
            error: function(xhr, status, error) {
                alert('Warining: ' + 'Please select Accident Section Data First');
            }
        });
    }
</script>



@endsection