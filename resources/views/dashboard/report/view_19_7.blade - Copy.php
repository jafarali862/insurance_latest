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
        </label><br/>
        [{{ $garage->executive_name}}]
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

        

            <!-- <form method="POST" action="{{ route('garage.text.update_new') }}">
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
            </form> -->


            <!-- <form method="POST" action="{{ route('garage.text.update_new') }}">
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
            </form> -->


            <!-- <form method="POST" action="{{ route('garage.text.update_new') }}">
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

            <div class="mt-3 d-none" id="fitnessButton">
            <button type="submit" class="btn btn-success">Update Garage</button>
            </div>
            </form> -->




            <!-- <p><strong>Is Permit Particular Collected:</strong> {{ $garage->is_permit_particular_collected ? 'Yes' : 'No' }}</p> -->


            <!-- <form method="POST" action="{{ route('garage.text.update_new') }}">
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

            <div class="mt-3 d-none" id="permitButton">
            <button type="submit" class="btn btn-success">Update Garage</button>
            </div>
            </form> -->



            <!-- <form method="POST" action="{{ route('garage.text.update_new') }}">
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

            <div class="mt-3 d-none" id="particularButton">
            <button type="submit" class="btn btn-success">Update Garage</button>
            </div>
            </form> -->


            <!-- <p><strong>Is DL Particular Collected:</strong> {{ $garage->is_dl_particular_collected ? 'Yes' : 'No' }}</p> -->
            <!-- <p><strong>Is Any Motor Occurrence:</strong> {{ $garage->is_any_motor_occurance ? 'Yes' : 'No' }}</p> -->

            <!-- <form method="POST" action="{{ route('garage.text.update_new') }}">
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

            <div class="mt-3 d-none" id="motorButton">
            <button type="submit" class="btn btn-success">Update Garage</button>
            </div>
            </form> -->


            
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
        </div>
    </div>
    @endforeach

    <hr>








    <h5>Garage Data</h5>
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


        </div>
    </div>
    @endforeach


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


        </div>
    </div>
    @endforeach







    <!-- Include Bootstrap (if not already in your layout) -->


<!-- Tabs Navigation -->
<ul class="nav nav-tabs mt-4" id="dataTabs" role="tablist">
    <li class="nav-item">
        <a class="nav-link active" id="garage-tab" data-toggle="tab" href="#garageData" role="tab" aria-controls="garageData" aria-selected="true">Garage Data</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" id="owner-tab" data-toggle="tab" href="#ownerData" role="tab" aria-controls="ownerData" aria-selected="false">Owner Data</a>
    </li>

    <li class="nav-item">
        <a class="nav-link" id="driver-tab" data-toggle="tab" href="#driverData" role="tab" aria-controls="driverData" aria-selected="false">Driver Data</a>
    </li>

</ul>

<!-- Tabs Content -->
<div class="tab-content" id="dataTabsContent">

    <!-- Garage Data Tab -->
    <div class="tab-pane fade show active" id="garageData" role="tabpanel" aria-labelledby="garage-tab">
        <h5 class="mt-4">Garage Data</h5>

        @if ($report->owner_reassign_status == 1)
        <form action="{{ route('owner.re.assign') }}" method="POST" class="ajax-form mb-3">
            @csrf
            <input type="hidden" name="id" required value="{{ $report->id }}">
            <button type="submit" class="btn btn-danger">Re-Assign</button>
        </form>
        @endif

        @if ($report->owner_reassign_status == 0)
        <span class="badge badge-danger mb-3">Pending</span>
        @endif

        @foreach ($ownerData as $index => $owner)
        <div class="card mb-3">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-8"></div>
                    <div class="col-md-2">
                        <a class='btn btn-danger' href="{{ url('/owner/'.$owner->id) }}">Authorize Upload data</a>
                    </div>
                    <div class="col-md-2">
                        <button class="btn btn-primary" onclick="editFieldOwner({{$owner->id}})">Edit Owner Data</button>
                    </div>
                </div>

                <div class="form-check mt-2">
                    <input class="form-check-input" type="radio" id="selectOwner{{ $owner->id }}" value="{{ $owner->id }}-{{$report->case_id}}">
                    <label class="form-check-label" for="selectOwner{{ $owner->id }}">
                        Select this owner data
                    </label>
                </div>

                <h6 class="mt-3">Entry {{ $index + 1 }}</h6>
                <p><strong>Executive Name:</strong> {{ $owner->executive_name }}</p>
                <p><strong>Insured person's Version of Accident:</strong> {{ $owner->insured_version_acc }}</p>

                <h5>Download Data Section</h5>
                @if(isset($owner->owner_downloads))
                    @foreach(json_decode($owner->owner_downloads) as $file)
                        @php
                        $extension = pathinfo($file, PATHINFO_EXTENSION);
                        $label = match(strtolower($extension)) {
                            'jpg', 'jpeg', 'png', 'gif', 'svg' => 'owner image',
                            'pdf', 'zip' => 'owner report',
                            'aac', 'mp3', 'wav' => 'owner voice',
                            'mp4', 'avi', 'mov', 'wmv', 'mkv' => 'owner video file',
                            default => 'unknown file',
                        };
                        @endphp
                        <ul>
                            <li><a href="{{ asset('storage/' . $file) }}" target="_blank" download>{{ $label }}</a></li>
                        </ul>
                    @endforeach
                @else
                    <p>No files available for download.</p>
                @endif
            </div>
        </div>
        @endforeach
    </div>

    <!-- Owner Data Tab -->
    <div class="tab-pane fade" id="ownerData" role="tabpanel" aria-labelledby="owner-tab">
        <h5 class="mt-4">Owner Data</h5>

        @if ($report->owner_reassign_status == 1)
        <form action="{{ route('owner.re.assign') }}" method="POST" class="ajax-form mb-3">
            @csrf
            <input type="hidden" name="id" required value="{{ $report->id }}">
            <button type="submit" class="btn btn-danger">Re-Assign</button>
        </form>
        @endif

        @if ($report->owner_reassign_status == 0)
        <span class="badge badge-danger mb-3">Pending</span>
        @endif

        @foreach ($ownerData as $index => $owner)
        <div class="card mb-3">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-8"></div>
                    <div class="col-md-2">
                        <a class='btn btn-danger' href="{{ url('/owner/'.$owner->id) }}">Authorize Upload data</a>
                    </div>
                    <div class="col-md-2">
                        <button class="btn btn-primary" onclick="editFieldOwner({{$owner->id}})">Edit Owner Data</button>
                    </div>
                </div>

                <div class="form-check mt-2">
                    <input class="form-check-input" type="radio" id="selectOwnerTab2{{ $owner->id }}" value="{{ $owner->id }}-{{$report->case_id}}">
                    <label class="form-check-label" for="selectOwnerTab2{{ $owner->id }}">
                        Select this owner data
                    </label>
                </div>

                <h6 class="mt-3">Entry {{ $index + 1 }}</h6>
                <p><strong>Executive Name:</strong> {{ $owner->executive_name }}</p>
                <p><strong>Insured person's Version of Accident:</strong> {{ $owner->insured_version_acc }}</p>

                <h5>Download Data Section</h5>
                @if(isset($owner->owner_downloads))
                    @foreach(json_decode($owner->owner_downloads) as $file)
                        @php
                        $extension = pathinfo($file, PATHINFO_EXTENSION);
                        $label = match(strtolower($extension)) {
                            'jpg', 'jpeg', 'png', 'gif', 'svg' => 'owner image',
                            'pdf', 'zip' => 'owner report',
                            'aac', 'mp3', 'wav' => 'owner voice',
                            'mp4', 'avi', 'mov', 'wmv', 'mkv' => 'owner video file',
                            default => 'unknown file',
                        };
                        @endphp
                        <ul>
                            <li><a href="{{ asset('storage/' . $file) }}" target="_blank" download>{{ $label }}</a></li>
                        </ul>
                    @endforeach
                @else
                    <p>No files available for download.</p>
                @endif
            </div>
        </div>
        @endforeach
    </div>






     <div class="tab-pane fade" id="driverData" role="tabpanel" aria-labelledby="driver-tab">
        <h5 class="mt-4">Driver Data</h5>


        @foreach ($driverData as $index => $driver)

            <p><strong>Executive Name:</strong> {{ $driver->executive_name }}</p>
        <p><strong>Driver Name:</strong> {{ $driver->driver_name }}</p>
        @endforeach

        @if ($report->driver_reassign_status == 1)
        <form action="{{ route('owner.re.assign') }}" method="POST" class="ajax-form mb-3">
            @csrf
            <input type="hidden" name="id" required value="{{ $report->id }}">
            <button type="submit" class="btn btn-danger">Re-Assign</button>
        </form>
        @endif

        @if ($report->owner_reassign_status == 0)
        <span class="badge badge-danger mb-3">Pending</span>
        @endif

        @foreach ($driverData as $index => $driver)
        <div class="card mb-3">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-8"></div>
                    <div class="col-md-2">
                        <a class='btn btn-danger' href="{{ url('/driver/'.$driver->id) }}">Authorize Upload data</a>
                    </div>
                    <div class="col-md-2">
                        <button class="btn btn-primary" onclick="editFieldOwner({{$driver->id}})">Edit Driver Data</button>
                    </div>
                </div>

                <div class="form-check mt-2">
                    <input class="form-check-input" type="radio" id="selectOwnerTab2{{ $driver->id }}" value="{{ $driver->id }}-{{$report->case_id}}">
                    <label class="form-check-label" for="selectDriverTab2{{ $driver->id }}">
                        Select this driver data
                    </label>
                </div>

                <h6 class="mt-3">Entry {{ $index + 1 }}</h6>
                <p><strong>Executive Name:</strong> {{ $driver->driver_name }}</p>
            

                <h5>Download Data Section</h5>
                @if(isset($driver->driver_downloads))
                    @foreach(json_decode($driver->driver_downloads) as $file)
                        @php
                        $extension = pathinfo($file, PATHINFO_EXTENSION);
                        $label = match(strtolower($extension)) {
                            'jpg', 'jpeg', 'png', 'gif', 'svg' => 'owner image',
                            'pdf', 'zip' => 'owner report',
                            'aac', 'mp3', 'wav' => 'owner voice',
                            'mp4', 'avi', 'mov', 'wmv', 'mkv' => 'owner video file',
                            default => 'unknown file',
                        };
                        @endphp
                        <ul>
                            <li><a href="{{ asset('storage/' . $file) }}" target="_blank" download>{{ $label }}</a></li>
                        </ul>
                    @endforeach
                @else
                    <p>No files available for download.</p>
                @endif
            </div>
        </div>
        @endforeach
    </div>



</div>





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