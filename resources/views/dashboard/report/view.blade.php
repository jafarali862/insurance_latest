@extends('dashboard.layout.app')
@section('title', 'Report Request Detail')
@section('content')
<div class="container-fluid">
    <div class="row align-items-center mb-3">
        <!-- Heading -->
        <div class="col-md-6">
<h3 class="mb-0">
    <strong>
        <span style="color:#007bff;">Report Details for</span>
        <span style="color:#28a745;">{{ $report->customer_name }}</span>
    </strong>
</h3>
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

      <!-- Action Buttons -->
        <div class="d-flex justify-content-end mb-3">
        <button class="btn btn-danger mr-2" onclick="window.history.back()" title="Back">
        <i class="fas fa-arrow-left"></i>
        </button>
        <button class="btn btn-warning mr-2" onclick="window.location.reload()" title="Reload">
        <i class="fas fa-sync-alt"></i>
        </button>

        </div>


        <div class="card">
        <div class="card-header">
        <h5><strong>General Information</strong></h5>
        </div>
        <div class="card-body">
        <p><strong>Company Name:</strong> {{ $report->company_name }}</p>
        <p><strong>Investigation Date:</strong> {{ \Carbon\Carbon::parse($report->date)->format('d-m-Y') }}</p>
        <p><strong>Type:</strong> {{ $report->type }}</p>
        </div>
        </div>

        <hr>


        @if ($report->is_fake == 1)

        <span class="badge bg-danger">Fake</span>

       
        @else
        <button type="button" class="btn btn-secondary" style="margin-bottom: 10px;float:right;" onclick="confirmFakeData()">Fake Data</button>

        <form id="fakeDataForm" method="POST" action="{{ route('insurance.fake.data') }}">
        @csrf
        <input type="hidden" name="case_id" value="{{ $report->case_id }}">

        </form>
        @endif



        @if (session('success'))
        <div class="alert alert-success">
        {{ session('success') }}
        </div>
        <script>
        // Reload after 5 seconds
        setTimeout(function () {
            location.reload();
        }, 5000); // 5000 milliseconds = 5 seconds
        </script>
        @endif


        @if (session('error'))
        <div class="alert alert-danger">
        {{ session('error') }}
        </div>
        @endif


<!-- Nav Tabs -->
<ul class="nav nav-tabs mt-4" id="dataTabs" role="tablist">
    <li class="nav-item">
        <a class="nav-link active" id="garage-tab" data-toggle="tab" href="#garageData" role="tab" aria-controls="garageData" aria-selected="true">Garage Data</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" id="driver-tab" data-toggle="tab" href="#driverData" role="tab" aria-controls="driverData" aria-selected="false">Driver Data</a>
    </li>

     <li class="nav-item">
        <a class="nav-link" id="spot-tab" data-toggle="tab" href="#spotData" role="tab" aria-controls="spotData" aria-selected="false">Spot Data</a>
    </li>

     <li class="nav-item">
        <a class="nav-link" id="owner-tab" data-toggle="tab" href="#ownerData" role="tab" aria-controls="ownerData" aria-selected="false">Owner Data</a>
    </li>

      <li class="nav-item">
        <a class="nav-link" id="accident-tab" data-toggle="tab" href="#accidentData" role="tab" aria-controls="accidentData" aria-selected="false">Accident Person Data</a>
    </li>


</ul>

<!-- Tab Content Wrapper -->
<div class="tab-content" id="dataTabsContent">

    <!--  Garage Data Tab -->
    <div class="tab-pane fade show active" id="garageData" role="tabpanel" aria-labelledby="garage-tab">
        <h5 class="mt-4">Garage Data</h5>

        @if ($report->garage_reassign_status == 1)
    
        <!-- Re-Assign Button -->
                <button type="button" style="margin-bottom:10px;" class="btn btn-danger" data-bs-toggle="modal"  data-bs-target="#reassignModal"  data-report-id="{{ $report->id }}">
                Re-Assign
                </button>

                <!-- Re-Assign Modal -->
                <div class="modal fade" id="reassignModal" tabindex="-1" aria-labelledby="reassignModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                
                <form action="{{ route('garage.re.assign') }}" method="POST" id="reassignForm">
                @csrf
                <input type="hidden" name="id" id="modalReportId">

                <div class="modal-content">
                <div class="modal-header">
                <h5 class="modal-title" id="reassignModalLabel">Re-Assign Executive</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                <div class="mb-3">
                <label for="executive_id" class="form-label">Select Executive</label>
                <select class="form-select" name="executive_id" required>
                <option disabled selected>Select the executive</option>
                @foreach ($users as $user)
                <option value="{{ $user->id }}">
                {{ $user->name }} ({{ $user->place }})
                </option>
                @endforeach
                </select>
                </div>
                </div>

                <div class="modal-footer">
                <button type="submit" class="btn btn-primary">Confirm Re-Assign</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                </div>
                </div>
                </form>

                </div>
                </div>


        @endif

        @if ($report->garage_reassign_status == 0)

        <span class="badge bg-warning text-dark">Pending</span>

        @endif

          <!-- @if ($report->is_fake == 1)

        <span class="badge bg-danger">Fake</span>

        @endif -->


      


        <div class="card p-3 mb-4">

            <form method="POST" action="{{ route('garage.text.update_new') }}" enctype="multipart/form-data">
                @csrf
                @foreach ($garageQuestions as $qIndex => $question)
                    @php
                        $columnName = $question->column_name;
                        $inputType = $question->input_type;
                    @endphp

                    <div class="mb-4 border-bottom pb-2">
                        <strong class="d-block mb-2">{{ $question->question }}</strong>

                        <div class="row">
                            @foreach ($garageData as $garage)
                                @php
                                    $value = $garage->$columnName ?? 'N/A';
                                    $radioId = "garage_radio_{$columnName}_{$garage->id}";
                                    $wrapperId = "garage_fieldWrapper_{$columnName}_{$garage->id}";
                                @endphp

            <div class="col-md-4 mb-2">

            <!-- @if (!empty($value) && strtolower(trim($value)) !== 'n/a')
            <div class="form-check mb-1">
            <input type="checkbox" class="form-check-input select-answer-checkbox-garage" name="selected_field[{{ $columnName }}]"
            value="{{ $garage->id }}" data-column="{{ $columnName }}" data-value="{{ $value }}"  data-case="{{ $report->case_id }}" id="{{ $radioId }}">
    
            Select this garage: 
         
            </div>
            @endif -->


             @if (!empty($value) && strtolower(trim($value)) !== 'n/a')
            <div class="form-check mb-1">
            <input type="checkbox" class="form-check-input select-answer-checkbox-garage"
            name="selected_field[{{ $columnName }}]"
            value="{{ $garage->id }}"
            data-column="{{ $columnName }}"
            data-value="{{ $value }}"
            data-case="{{ $report->case_id }}"
            id="{{ $radioId }}"  style="border: 2px solid #5a10ff; width: 18px; height: 18px; accent-color: #5a10ff;">
            Select this garage  
            </div>
            @endif


            <div class="form-check mb-1">
            <input type="radio" class="form-check-input toggle-edit"
            name="selected_field"
            value="{{ $columnName }}:{{ $garage->id }}"
            id="{{ $radioId }}"
            data-target="#{{ $wrapperId }}">
                                     
            <label class="form-check-label" for="{{ $radioId }}">

            @php
            $imageExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'bmp'];
            $audioExtensions = ['mp3', 'aac', 'wav', 'flac', 'ogg', 'm4a', 'wma', 'aiff', 'alac', 'opus'];
            $extension = strtolower(pathinfo($value, PATHINFO_EXTENSION));
            $filePath = 'storage/' . $value;
            @endphp

            @if (in_array($extension, $imageExtensions) && file_exists(public_path($filePath)))
            <img src="{{ asset($filePath) }}" alt="Image" style="max-width: 300px; height: auto; border: 1px solid #ccc; margin-bottom: 5px;">

            @elseif (in_array($extension, $audioExtensions) && file_exists(public_path($filePath)))
            <audio controls style="display: block; margin-bottom: 5px;">
            <source src="{{ asset($filePath) }}" type="audio/{{ $extension }}">
            Your browser does not support the audio element.
            </audio>





            @else
            <p>
            @if ($inputType === 'select')
            @if ($value == 1)
            Yes
            @elseif ($value == 0)
            No
            @else
            {{ $value }}
            @endif
            @else
            {{ $value }}
            @endif
            </p>
            @endif


            <br>

            <small>
            @if (!empty($value) && strtolower(trim($value)) !== 'n/a')
            <span style="color: #007bff; font-weight: bold;">
            <i class="fas fa-user-tie me-1"></i> {{-- Font Awesome icon --}}
            {{ $garage->executive_name }}
            </span>
            @endif
            </small><br/>
         {{ \Carbon\Carbon::parse($garage->updated_at)->format('d-m-Y h:i:s A') }}



            </label>




        </div>

        <div class="d-none field-wrapper mt-2" id="{{ $wrapperId }}">
        @if ($inputType === 'textarea')
        <textarea name="field_value[{{ $garage->id }}][{{ $columnName }}]" class="form-control">{{ $value }}</textarea>
        @elseif ($inputType === 'file')
        @if ($value)
            <!-- <p><img src="{{ asset('storage/' . $value) }}" alt="Garage" style="max-width: 300px; height: auto; border: 1px solid #ccc; margin-bottom: 5px;"></p> -->
        @endif
        <input type="file" name="field_value[{{ $garage->id }}][{{ $columnName }}]" class="form-control">
        @elseif ($inputType === 'select')
    @php
        $otherSelected = $value === 'Other';
    @endphp

    <select name="field_value[{{ $garage->id }}][{{ $columnName }}]"
            class="form-control select-trigger"
            data-id="{{ $garage->id }}"
            data-column="{{ $columnName }}">
        <option value="Yes" {{ $value == 'Yes' ? 'selected' : '' }}>Yes</option>
        <option value="No" {{ $value == 'No' ? 'selected' : '' }}>No</option>
        <option value="Other" {{ $value == 'Other' ? 'selected' : '' }}>Other</option>
    </select>

       <input type="text" 
       name="other_value[{{ $garage->id }}][{{ $columnName }}]"
       class="form-control mt-2 other-input"
       id="other_input_{{ $garage->id }}_{{ $columnName }}"
       placeholder="Please specify"
       value="{{ old("other_value.$garage->id.$columnName") }}"
       style="{{ $value == 'Other' ? '' : 'display:none;' }}; width:850px;">


        @else
        <input type="{{ $inputType }}" name="field_value[{{ $garage->id }}][{{ $columnName }}]" value="{{ $value }}" class="form-control">
        @endif

          
        <button type="submit" class="btn btn-sm btn-primary mt-2">Update</button>
        </div>
        </div>
        @endforeach
        </div>
        </div>
        @endforeach
        </form>
        </div>
        </div>


    <!-- 🧍 Driver Data Tab -->
        <div class="tab-pane fade" id="driverData" role="tabpanel" aria-labelledby="driver-tab">
        <h5 class="mt-4">Driver Data</h5>

        @if ($report->driver_reassign_status == 1)
    
         <button type="button" class="btn btn-danger" style="margin-bottom:10px;" data-bs-toggle="modal"  data-bs-target="#reassignModal"  data-report-id="{{ $report->id }}">
                Re-Assign
                </button>

                <!-- Re-Assign Modal -->
                <div class="modal fade" id="reassignModal" tabindex="-1" aria-labelledby="reassignModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                <form action="{{ route('driver.re.assign') }}" method="POST" id="reassignForm">
                @csrf
                <input type="hidden" name="id" id="modalReportId">

                <div class="modal-content">
                <div class="modal-header">
                <h5 class="modal-title" id="reassignModalLabel">Re-Assign Executive</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                <div class="mb-3">
                <label for="executive_id" class="form-label">Select Executive</label>
                <select class="form-select" name="executive_id" required>
                <option disabled selected>Select the executive</option>
                @foreach ($users as $user)
                <option value="{{ $user->id }}">
                {{ $user->name }} ({{ $user->place }})
                </option>
                @endforeach
                </select>
                </div>
                </div>

                <div class="modal-footer">
                <button type="submit" class="btn btn-primary">Confirm Re-Assign</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                </div>
                </div>
                </form>
                </div>
                </div>

        @endif

        @if ($report->driver_reassign_status == 0)
        <span class="badge bg-warning text-dark">Pending</span>
        @endif

        <div class="card p-3 mb-4">



          <form method="POST" action="{{ route('driver.text.update_new') }}" enctype="multipart/form-data">
                @csrf
                @foreach ($driverQuestions as $qIndex => $question)
                    @php
                        $columnName = $question->column_name;
                        $inputType = $question->input_type;
                    @endphp

                    <div class="mb-4 border-bottom pb-2">
                        <strong class="d-block mb-2">{{ $question->question }}</strong>

                        <div class="row">
                            @foreach ($driverData as $driver)
                                @php
                                    $value = $driver->$columnName ?? 'N/A';
                                    $radioId = "driver_radio_{$columnName}_{$driver->id}";
                                    $wrapperId = "driver_fieldWrapper_{$columnName}_{$driver->id}";
                                @endphp

            <div class="col-md-4 mb-2">

            @if (!empty($value) && strtolower(trim($value)) !== 'n/a')
            <div class="form-check mb-1">
           
            <input type="checkbox" class="form-check-input select-answer-checkbox-driver"
            name="selected_field[{{ $columnName }}]"
            value="{{ $driver->id }}"
            data-column="{{ $columnName }}"
            data-value="{{ $value }}"
            data-case="{{ $report->case_id }}"
            id="{{ $radioId }}"  style="border: 2px solid #5a10ff; width: 18px; height: 18px; accent-color: #5a10ff;">
            Select this driver: 

            </div>
            @endif


            <div class="form-check mb-1">
            <input type="radio" class="form-check-input toggle-edit"
            name="selected_field"
            value="{{ $columnName }}:{{ $driver->id }}"
            id="{{ $radioId }}"
            data-target="#{{ $wrapperId }}">
                                     
            <label class="form-check-label" for="{{ $radioId }}">

            @php
            $imageExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'bmp'];
            $audioExtensions = ['mp3', 'aac', 'wav', 'flac', 'ogg', 'm4a', 'wma', 'aiff', 'alac', 'opus'];
            $extension = strtolower(pathinfo($value, PATHINFO_EXTENSION));
            $filePath = 'storage/' . $value;
            @endphp

            @if (in_array($extension, $imageExtensions) && file_exists(public_path($filePath)))
            <img src="{{ asset($filePath) }}" alt="Image" style="max-width: 300px; height: auto; border: 1px solid #ccc; margin-bottom: 5px;">

            @elseif (in_array($extension, $audioExtensions) && file_exists(public_path($filePath)))
            <audio controls style="display: block; margin-bottom: 5px;">
            <source src="{{ asset($filePath) }}" type="audio/{{ $extension }}">
            Your browser does not support the audio element.
            </audio>

            @else
            {{ $value }}
            @endif

            <br><small>
              @if (!empty($value) && strtolower(trim($value)) !== 'n/a')    
            [{{ $driver->executive_name }}]
            @endif
           </small>
            </label>



        </div>

        <div class="d-none field-wrapper mt-2" id="{{ $wrapperId }}">
        @if ($inputType === 'textarea')
        <textarea name="field_value[{{ $driver->id }}][{{ $columnName }}]" class="form-control">{{ $value }}</textarea>
        @elseif ($inputType === 'file')
        @if ($value)
            <!-- <p><img src="{{ asset('storage/' . $value) }}" alt="Garage" style="max-width: 300px; height: auto; border: 1px solid #ccc; margin-bottom: 5px;"></p> -->
        @endif
        <input type="file" name="field_value[{{ $driver->id }}][{{ $columnName }}]" class="form-control">
        @elseif ($inputType === 'select')
    @php
        $otherSelected = $value === 'Other';
    @endphp

    <select name="field_value[{{ $driver->id }}][{{ $columnName }}]"
            class="form-control select-trigger"
            data-id="{{ $driver->id }}"
            data-column="{{ $columnName }}">
        <option value="Yes" {{ $value == 'Yes' ? 'selected' : '' }}>Yes</option>
        <option value="No" {{ $value == 'No' ? 'selected' : '' }}>No</option>
        <option value="Other" {{ $value == 'Other' ? 'selected' : '' }}>Other</option>
    </select>

       <input type="text" 
       name="other_value[{{ $driver->id }}][{{ $columnName }}]"
       class="form-control mt-2 other-input"
       id="other_input_{{ $driver->id }}_{{ $columnName }}"
       placeholder="Please specify"
       value="{{ old("other_value.$driver->id.$columnName") }}"
       style="{{ $value == 'Other' ? '' : 'display:none;' }}; width:850px;">


        @else
        <input type="{{ $inputType }}" name="field_value[{{ $driver->id }}][{{ $columnName }}]" value="{{ $value }}" class="form-control">
        @endif

          
        <button type="submit" class="btn btn-sm btn-primary mt-2">Update</button>
        </div>
        </div>
        @endforeach
        </div>
        </div>
        @endforeach
        </form>


        </div>
        </div>




         <!-- 🧍 Spot Data Tab -->
        <div class="tab-pane fade" id="spotData" role="tabpanel" aria-labelledby="spot-tab">
        <h5 class="mt-4">Spot Data</h5>

        @if ($report->spot_reassign_status == 1)


        <!-- <form action="{{ route('spot.re.assign') }}" method="POST" class="ajax-form">
        @csrf
        <input type="hidden" name="id" value="{{ $report->id }}">
        <button type="submit" class="btn btn-danger">Re-Assign</button>
        </form> -->

          <button type="button" class="btn btn-danger" style="margin-bottom:10px;" data-bs-toggle="modal"  data-bs-target="#reassignModal"  data-report-id="{{ $report->id }}">
                Re-Assign
                </button>

                <!-- Re-Assign Modal -->
                <div class="modal fade" id="reassignModal" tabindex="-1" aria-labelledby="reassignModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                <form action="{{ route('spot.re.assign') }}" method="POST" id="reassignForm">
                @csrf
                <input type="hidden" name="id" id="modalReportId">

                <div class="modal-content">
                <div class="modal-header">
                <h5 class="modal-title" id="reassignModalLabel">Re-Assign Executive</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                <div class="mb-3">
                <label for="executive_id" class="form-label">Select Executive</label>
                <select class="form-select" name="executive_id" required>
                <option disabled selected>Select the executive</option>
                @foreach ($users as $user)
                <option value="{{ $user->id }}">
                {{ $user->name }} ({{ $user->place }})
                </option>
                @endforeach
                </select>
                </div>
                </div>

                <div class="modal-footer">
                <button type="submit" class="btn btn-primary">Confirm Re-Assign</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                </div>
                </div>
                </form>
                </div>
                </div>



        @endif

        @if ($report->spot_reassign_status == 0)
        <span class="badge bg-warning text-dark">Pending</span>
        @endif

        <div class="card p-3 mb-4">


           <form method="POST" action="{{ route('spot.text.update_new') }}" enctype="multipart/form-data">
                @csrf
                @foreach ($spotQuestions as $qIndex => $question)
                    @php
                        $columnName = $question->column_name;
                        $inputType = $question->input_type;
                    @endphp

                    <div class="mb-4 border-bottom pb-2">
                        <strong class="d-block mb-2">{{ $question->question }}</strong>

                        <div class="row">
                            @foreach ($spotData as $spot)
                                @php
                                    $value = $spot->$columnName ?? 'N/A';
                                    $radioId = "spot_radio_{$columnName}_{$spot->id}";
                                    $wrapperId = "spot_fieldWrapper_{$columnName}_{$spot->id}";
                                @endphp

            <div class="col-md-4 mb-2">

          @if (!empty($value) && strtolower(trim($value)) !== 'n/a')


            <div class="form-check mb-1">
           
            <input type="checkbox" class="form-check-input select-answer-checkbox-spot"
            name="selected_field[{{ $columnName }}]"
            value="{{ $spot->id }}"
            data-column="{{ $columnName }}"
            data-value="{{ $value }}"
            data-case="{{ $report->case_id }}"
            id="{{ $radioId }}"  style="border: 2px solid #5a10ff; width: 18px; height: 18px; accent-color: #5a10ff;">
            
            Select this spot: 
            </div>
            @endif


            <div class="form-check mb-1">
            <input type="radio" class="form-check-input toggle-edit"
            name="selected_field"
            value="{{ $columnName }}:{{ $spot->id }}"
            id="{{ $radioId }}"
            data-target="#{{ $wrapperId }}">
                                     
            <label class="form-check-label" for="{{ $radioId }}">

            @php
            $imageExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'bmp'];
            $audioExtensions = ['mp3', 'aac', 'wav', 'flac', 'ogg', 'm4a', 'wma', 'aiff', 'alac', 'opus'];
            $extension = strtolower(pathinfo($value, PATHINFO_EXTENSION));
            $filePath = 'storage/' . $value;
            @endphp

            @if (in_array($extension, $imageExtensions) && file_exists(public_path($filePath)))
            <img src="{{ asset($filePath) }}" alt="Image" style="max-width: 300px; height: auto; border: 1px solid #ccc; margin-bottom: 5px;">

            @elseif (in_array($extension, $audioExtensions) && file_exists(public_path($filePath)))
            <audio controls style="display: block; margin-bottom: 5px;">
            <source src="{{ asset($filePath) }}" type="audio/{{ $extension }}">
            Your browser does not support the audio element.
            </audio>




            @else
            <p>
            @if ($inputType === 'select')
            @if ($value == 1)
            Yes
            @elseif ($value == 0)
            No
            @else
            {{ $value }}
            @endif
            @else
            {{ $value }}
            @endif
            </p>
            @endif



            <br><small>
            @if (!empty($value) && strtolower(trim($value)) !== 'n/a')
            [{{ $spot->executive_name }}]
             @endif
           </small>
            </label>



        </div>

        <div class="d-none field-wrapper mt-2" id="{{ $wrapperId }}">
        @if ($inputType === 'textarea')
        <textarea name="field_value[{{ $spot->id }}][{{ $columnName }}]" class="form-control">{{ $value }}</textarea>
        @elseif ($inputType === 'file')
        @if ($value)
            <!-- <p><img src="{{ asset('storage/' . $value) }}" alt="Garage" style="max-width: 300px; height: auto; border: 1px solid #ccc; margin-bottom: 5px;"></p> -->
        @endif
        <input type="file" name="field_value[{{ $spot->id }}][{{ $columnName }}]" class="form-control">
        @elseif ($inputType === 'select')
    @php
        $otherSelected = $value === 'Other';
    @endphp

    <select name="field_value[{{ $spot->id }}][{{ $columnName }}]"
            class="form-control select-trigger"
            data-id="{{ $spot->id }}"
            data-column="{{ $columnName }}">
        <option value="Yes" {{ $value == 'Yes' ? 'selected' : '' }}>Yes</option>
        <option value="No" {{ $value == 'No' ? 'selected' : '' }}>No</option>
        <option value="Other" {{ $value == 'Other' ? 'selected' : '' }}>Other</option>
    </select>

       <input type="text" 
       name="other_value[{{ $spot->id }}][{{ $columnName }}]"
       class="form-control mt-2 other-input"
       id="other_input_{{ $spot->id }}_{{ $columnName }}"
       placeholder="Please specify"
       value="{{ old("other_value.$spot->id.$columnName") }}"
       style="{{ $value == 'Other' ? '' : 'display:none;' }}; width:850px;">

        @else
        <input type="{{ $inputType }}" name="field_value[{{ $spot->id }}][{{ $columnName }}]" value="{{ $value }}" class="form-control">
        @endif

          
        <button type="submit" class="btn btn-sm btn-primary mt-2">Update</button>
        </div>
        </div>
        @endforeach
        </div>
        </div>
        @endforeach
        </form>

        
        </div>
        </div>



        <!-- 🧍 Owner Data Tab -->
        <div class="tab-pane fade" id="ownerData" role="tabpanel" aria-labelledby="owner-tab">
        <h5 class="mt-4">Owner Data</h5>

        @if ($report->owner_reassign_status == 1)

        <!-- <form action="{{ route('owner.re.assign') }}" method="POST" class="ajax-form">
        @csrf
        <input type="hidden" name="id" value="{{ $report->id }}">
        <button type="submit" class="btn btn-danger">Re-Assign</button>
        </form> -->

                <button type="button" class="btn btn-danger" style="margin-bottom:10px;" data-bs-toggle="modal"  data-bs-target="#reassignModal"  data-report-id="{{ $report->id }}">
                Re-Assign
                </button>

                <!-- Re-Assign Modal -->
                <div class="modal fade" id="reassignModal" tabindex="-1" aria-labelledby="reassignModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                <form action="{{ route('owner.re.assign') }}" method="POST" id="reassignForm">
                @csrf
                <input type="hidden" name="id" id="modalReportId">

                <div class="modal-content">
                <div class="modal-header">
                <h5 class="modal-title" id="reassignModalLabel">Re-Assign Executive</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                <div class="mb-3">
                <label for="executive_id" class="form-label">Select Executive</label>
                <select class="form-select" name="executive_id" required>
                <option disabled selected>Select the executive</option>
                @foreach ($users as $user)
                <option value="{{ $user->id }}">
                {{ $user->name }} ({{ $user->place }})
                </option>
                @endforeach
                </select>
                </div>
                </div>

                <div class="modal-footer">
                <button type="submit" class="btn btn-primary">Confirm Re-Assign</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                </div>
                </div>
                </form>
                </div>
                </div>

        @endif

        @if ($report->owner_reassign_status == 0)
        <span class="badge bg-warning text-dark">Pending</span>
        @endif

        <div class="card p-3 mb-4">



           <form method="POST" action="{{route('owner.text.update_new')}}" enctype="multipart/form-data">
                @csrf
                @foreach ($ownerQuestions as $qIndex => $question)
                    @php
                        $columnName = $question->column_name;
                        $inputType = $question->input_type;
                    @endphp

                    <div class="mb-4 border-bottom pb-2">
                        <strong class="d-block mb-2">{{ $question->question }}</strong>

                        <div class="row">
                            @foreach ($ownerData as $owner)
                                @php
                                    $value = $owner->$columnName ?? 'N/A';
                                    $radioId = "owner_radio_{$columnName}_{$owner->id}";
                                    $wrapperId = "owner_fieldWrapper_{$columnName}_{$owner->id}";
                                @endphp

            <div class="col-md-4 mb-2">
              @if (!empty($value) && strtolower(trim($value)) !== 'n/a')

            <div class="form-check mb-1">
          
            <input type="checkbox" class="form-check-input select-answer-checkbox-owner"
            name="selected_field[{{ $columnName }}]"
            value="{{ $owner->id }}"
            data-column="{{ $columnName }}"
            data-value="{{ $value }}"
            data-case="{{ $report->case_id }}"
            id="{{ $radioId }}"  style="border: 2px solid #5a10ff; width: 18px; height: 18px; accent-color: #5a10ff;">
            
            Select this owner: 
        
            </div>
            @endif


            <div class="form-check mb-1">
            <input type="radio" class="form-check-input toggle-edit"
            name="selected_field"
            value="{{ $columnName }}:{{ $spot->id }}"
            id="{{ $radioId }}"
            data-target="#{{ $wrapperId }}">
                                     
            <label class="form-check-label" for="{{ $radioId }}">

            @php
            $imageExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'bmp'];
            $audioExtensions = ['mp3', 'aac', 'wav', 'flac', 'ogg', 'm4a', 'wma', 'aiff', 'alac', 'opus'];
            $extension = strtolower(pathinfo($value, PATHINFO_EXTENSION));
            $filePath = 'storage/' . $value;
            @endphp

            @if (in_array($extension, $imageExtensions) && file_exists(public_path($filePath)))
            <img src="{{ asset($filePath) }}" alt="Image" style="max-width: 300px; height: auto; border: 1px solid #ccc; margin-bottom: 5px;">

            @elseif (in_array($extension, $audioExtensions) && file_exists(public_path($filePath)))
            <audio controls style="display: block; margin-bottom: 5px;">
            <source src="{{ asset($filePath) }}" type="audio/{{ $extension }}">
            Your browser does not support the audio element.
            </audio>

           @else
    <p>
        @if ($inputType === 'select')
            @if ($value == 1)
                Yes
            @elseif ($value == 0)
                No
            @else
                {{ $value }}
            @endif
        @else
            {{ $value }}
        @endif
    </p>
@endif


            </p>

            <br><small>
               @if (!empty($value) && strtolower(trim($value)) !== 'n/a')    
            [{{ $owner->executive_name }}]
            @endif
           </small>
            </label>



        </div>

      

<div class="field-wrapper mt-2" id="{{ $wrapperId }}" style="display: none;">

        @if ($inputType === 'textarea')
        <textarea name="field_value[{{ $owner->id }}][{{ $columnName }}]" class="form-control">{{ $value }}</textarea>
        @elseif ($inputType === 'file')

        
        @if ($value)
            <!-- <p><img src="{{ asset('storage/' . $value) }}" alt="Garage" style="max-width: 300px; height: auto; border: 1px solid #ccc; margin-bottom: 5px;"></p> -->
        @endif
        <input type="file" name="field_value[{{ $owner->id }}][{{ $columnName }}]" class="form-control fa">
        @elseif ($inputType === 'select')
    @php
        $otherSelected = $value === 'Other';
    @endphp

    <select name="field_value[{{ $owner->id }}][{{ $columnName }}]"
            class="form-control select-trigger"
            data-id="{{ $owner->id }}"
            data-column="{{ $columnName }}">
        <option value="Yes" {{ $value == 'Yes' ? 'selected' : '' }}>Yes</option>
        <option value="No" {{ $value == 'No' ? 'selected' : '' }}>No</option>
        <option value="Other" {{ $value == 'Other' ? 'selected' : '' }}>Other</option>
    </select>

       <input type="text" 
       name="other_value[{{ $owner->id }}][{{ $columnName }}]"
       class="form-control mt-2 other-input"
       id="other_input_{{ $owner->id }}_{{ $columnName }}"
       placeholder="Please specify"
       value="{{ old("other_value.$owner->id.$columnName") }}"
       style="{{ $value == 'Other' ? '' : 'display:none;' }}; width:850px;">

        @else
        <input type="{{ $inputType }}" name="field_value[{{ $owner->id }}][{{ $columnName }}]" value="{{ $value }}" class="form-control">
        @endif

          
        <button type="submit" class="btn btn-sm btn-primary mt-2">Update</button>
        </div>
        </div>
        @endforeach
        </div>
        </div>
        @endforeach
        </form>

        </div>
        </div>






         <!-- 🧍 Accident Person Data Tab -->
        <div class="tab-pane fade" id="accidentData" role="tabpanel" aria-labelledby="accident-tab">
        <h5 class="mt-4">Accident Person Data</h5>

        @if ($report->accident_person_reassign_status == 1)


        <!-- <form action="{{ route('accident.person.re.assign') }}" method="POST" class="ajax-form">
        @csrf
        <input type="hidden" name="id" value="{{ $report->id }}">
        <button type="submit" class="btn btn-danger">Re-Assign</button>
        </form> -->

           <button type="button" class="btn btn-danger" style="margin-bottom:10px;" data-bs-toggle="modal"  data-bs-target="#reassignModal"  data-report-id="{{ $report->id }}">
                Re-Assign
                </button>

                <!-- Re-Assign Modal -->
                <div class="modal fade" id="reassignModal" tabindex="-1" aria-labelledby="reassignModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                <form action="{{ route('accident.person.re.assign') }}" method="POST" id="reassignForm">
                @csrf
                <input type="hidden" name="id" id="modalReportId">

                <div class="modal-content">
                <div class="modal-header">
                <h5 class="modal-title" id="reassignModalLabel">Re-Assign Executive</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                <div class="mb-3">
                <label for="executive_id" class="form-label">Select Executive</label>
                <select class="form-select" name="executive_id" required>
                <option disabled selected>Select the executive</option>
                @foreach ($users as $user)
                <option value="{{ $user->id }}">
                {{ $user->name }} ({{ $user->place }})
                </option>
                @endforeach
                </select>
                </div>
                </div>

                <div class="modal-footer">
                <button type="submit" class="btn btn-primary">Confirm Re-Assign</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                </div>
                </div>
                </form>
                </div>
                </div>

        @endif

        @if ($report->accident_person_reassign_status == 0)
        <span class="badge bg-warning text-dark">Pending</span>
        @endif

        <div class="card p-3 mb-4">




          <form method="POST" action="{{ route('accident.text.update_new') }}" enctype="multipart/form-data">
                @csrf
                @foreach ($accidentQuestions as $qIndex => $question)
                    @php
                        $columnName = $question->column_name;
                        $inputType = $question->input_type;
                    @endphp

                    <div class="mb-4 border-bottom pb-2">
                        <strong class="d-block mb-2">{{ $question->question }}</strong>

                        <div class="row">
                            @foreach ($accidentPersonData as $accident)
                                @php
                                    $value = $accident->$columnName ?? 'N/A';
                                    $radioId = "accident_radio_{$columnName}_{$accident->id}";
                                    $wrapperId = "accident_fieldWrapper_{$columnName}_{$accident->id}";
                                @endphp

            <div class="col-md-4 mb-2">

                  @if (!empty($value) && strtolower(trim($value)) !== 'n/a')
                <div class="form-check mb-1">
           
            <!-- <input type="checkbox" class="form-check-input select-answer-checkbox-accident" name="selected_field[{{ $columnName }}]"
            value="{{ $accident->id }}" data-column="{{ $columnName }}" data-value="{{ $value }}"  data-case="{{ $report->case_id }}" id="{{ $radioId }}" style="border: 2px solid #5a10ff; width: 18px; height: 18px; accent-color: #5a10ff;"> -->
            
            <input type="checkbox" class="form-check-input select-answer-checkbox-accident"
            name="selected_field[{{ $columnName }}]"
            value="{{ $accident->id }}"
            data-column="{{ $columnName }}"
            data-value="{{ $value }}"
            data-case="{{ $report->case_id }}"
            id="{{ $radioId }}"  style="border: 2px solid #5a10ff; width: 18px; height: 18px; accent-color: #5a10ff;">
      
            Select this accident: 
    
            </div>
            @endif

            <div class="form-check mb-1">
            <input type="radio" class="form-check-input toggle-edit"
            name="selected_field"
            value="{{ $columnName }}:{{ $spot->id }}"
            id="{{ $radioId }}"
            data-target="#{{ $wrapperId }}">
                                     
            <label class="form-check-label" for="{{ $radioId }}">

            @php
            $imageExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'bmp'];
            $audioExtensions = ['mp3', 'aac', 'wav', 'flac', 'ogg', 'm4a', 'wma', 'aiff', 'alac', 'opus'];
            $extension = strtolower(pathinfo($value, PATHINFO_EXTENSION));
            $filePath = 'storage/' . $value;
            @endphp

            @if (in_array($extension, $imageExtensions) && file_exists(public_path($filePath)))
            <img src="{{ asset($filePath) }}" alt="Image" style="max-width: 300px; height: auto; border: 1px solid #ccc; margin-bottom: 5px;">

            @elseif (in_array($extension, $audioExtensions) && file_exists(public_path($filePath)))
            <audio controls style="display: block; margin-bottom: 5px;">
            <source src="{{ asset($filePath) }}" type="audio/{{ $extension }}">
            Your browser does not support the audio element.
            </audio>

            @else
            <p>
            @if ($inputType === 'select')
            @if ($value == 1)
            Yes
            @elseif ($value == 0)
            No
            @else
            {{ $value }}
            @endif
            @else
            {{ $value }}
            @endif
            </p>
            @endif

            <br><small>
               @if (!empty($value) && strtolower(trim($value)) !== 'n/a')    
             [{{ $accident->executive_name }}]
              @endif
            </small>
            </label>



        </div>

        <div class="d-none field-wrapper mt-2" id="{{ $wrapperId }}">
        @if ($inputType === 'textarea')
        <textarea name="field_value[{{ $accident->id }}][{{ $columnName }}]" class="form-control">{{ $value }}</textarea>
        @elseif ($inputType === 'file')
        @if ($value)
            <!-- <p><img src="{{ asset('storage/' . $value) }}" alt="Garage" style="max-width: 300px; height: auto; border: 1px solid #ccc; margin-bottom: 5px;"></p> -->
        @endif
        <input type="file" name="field_value[{{ $accident->id }}][{{ $columnName }}]" class="form-control">
        @elseif ($inputType === 'select')
    @php
        $otherSelected = $value === 'Other';
    @endphp

    <select name="field_value[{{ $accident->id }}][{{ $columnName }}]"
            class="form-control select-trigger"
            data-id="{{ $accident->id }}"
            data-column="{{ $columnName }}">
        <option value="Yes" {{ $value == 'Yes' ? 'selected' : '' }}>Yes</option>
        <option value="No" {{ $value == 'No' ? 'selected' : '' }}>No</option>
        <option value="Other" {{ $value == 'Other' ? 'selected' : '' }}>Other</option>
    </select>

       <input type="text" 
       name="other_value[{{ $accident->id }}][{{ $columnName }}]"
       class="form-control mt-2 other-input"
       id="other_input_{{ $accident->id }}_{{ $columnName }}"
       placeholder="Please specify"
       value="{{ old("other_value.$accident->id.$columnName") }}"
       style="{{ $value == 'Other' ? '' : 'display:none;' }}; width:850px;">

        @else
        <input type="{{ $inputType }}" name="field_value[{{ $accident->id }}][{{ $columnName }}]" value="{{ $value }}" class="form-control">
        @endif

          
        <button type="submit" class="btn btn-sm btn-primary mt-2">Update</button>
        </div>
        </div>
        @endforeach
        </div>
        </div>
        @endforeach
        </form>

        </div>
        </div>


</div>


    <hr>

    </div>
    </div>

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
<!-- Bootstrap 5 dependencies (NO jQuery needed) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">


    <script>
        lightbox.option({
            'resizeDuration': 200,
            'wrapAround': true,
            'fadeDuration': 300
        });
    </script>


<script>

document.querySelectorAll('.toggle-edit').forEach(radio => {
  radio.addEventListener('change', () => {
    const target = document.querySelector(radio.dataset.target);
    if (target) target.style.display = 'block';
  });
});


    function confirmFakeData() {
        if (confirm("Are you sure  want to mark this section as fake data?")) {
            document.getElementById('fakeDataForm').submit();
        }
    }



    document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.select-trigger').forEach(function (select) {
    select.addEventListener('change', function () {
    const garageId = this.dataset.id;
    const column = this.dataset.column;
    const input = document.getElementById(`other_input_${garageId}_${column}`);

    if (this.value === 'Other') {
        input.style.display = 'block';
    } else {
        input.style.display = 'none';
        input.value = ''; // Optional: clear text if not Other
    }
    });
    });
    });

 
    document.addEventListener('DOMContentLoaded', function () {
        const reassignModal = document.getElementById('reassignModal');
        reassignModal.addEventListener('show.bs.modal', function (event) {
            const button = event.relatedTarget;
            const reportId = button.getAttribute('data-report-id');
            document.getElementById('modalReportId').value = reportId;
        });
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




    $('input.select-answer-checkbox-garage').on('change', function () {
    const columnName = $(this).data('column');
    const value = $(this).data('value');
    const caseId = $(this).data('case');

    // Highlight checkbox when checked
    if ($(this).is(':checked')) {
    $(this).closest('.form-check').addClass('bg-success text-white'); // green background
    } else {
    $(this).closest('.form-check').removeClass('bg-success text-white');
    }

    // AJAX call to update database
    $.ajax({
    url: "{{ route('save.selected') }}",
    method: 'POST',
    data: {
    column_name: columnName,
    value: value,
    case_id: caseId,
    _token: '{{ csrf_token() }}'
    },
    success: function (response) {
    console.log('Answer updated:', response.data.message);
    },
    error: function (xhr, status, error) {
    console.error('Error updating answer:', error);
    }
    });
    });

   

    $('input.select-answer-checkbox-driver').on('change', function () {
    const columnName = $(this).data('column');
    const value = $(this).data('value');
    const caseId = $(this).data('case');

    // Highlight checkbox when checked
    if ($(this).is(':checked')) {
    $(this).closest('.form-check').addClass('bg-success text-white'); // green background
    } else {
    $(this).closest('.form-check').removeClass('bg-success text-white');
    }

    // AJAX call to update database
    $.ajax({
    url: "{{ route('save.selected') }}",
    method: 'POST',
    data: {
    column_name: columnName,
    value: value,
    case_id: caseId,
    _token: '{{ csrf_token() }}'
    },
    success: function (response) {
    console.log('Answer updated:', response.data.message);
    },
    error: function (xhr, status, error) {
    console.error('Error updating answer:', error);
    }
    });
    });


    $('input.select-answer-checkbox-spot').on('change', function () {
    const columnName = $(this).data('column');
    const value = $(this).data('value');
    const caseId = $(this).data('case');

    // Highlight checkbox when checked
    if ($(this).is(':checked')) {
    $(this).closest('.form-check').addClass('bg-success text-white'); // green background
    } else {
    $(this).closest('.form-check').removeClass('bg-success text-white');
    }

    // AJAX call to update database
    $.ajax({
    url: "{{ route('save.selected') }}",
    method: 'POST',
    data: {
    column_name: columnName,
    value: value,
    case_id: caseId,
    _token: '{{ csrf_token() }}'
    },
    success: function (response) {
    console.log('Answer updated:', response.data.message);
    },
    error: function (xhr, status, error) {
    console.error('Error updating answer:', error);
    }
    });
    });


    $('input.select-answer-checkbox-owner').on('change', function () {
    const columnName = $(this).data('column');
    const value = $(this).data('value');
    const caseId = $(this).data('case');

    // Highlight checkbox when checked
    if ($(this).is(':checked')) {
    $(this).closest('.form-check').addClass('bg-success text-white'); // green background
    } else {
    $(this).closest('.form-check').removeClass('bg-success text-white');
    }

    // AJAX call to update database
    $.ajax({
    url: "{{ route('save.selected') }}",
    method: 'POST',
    data: {
    column_name: columnName,
    value: value,
    case_id: caseId,
    _token: '{{ csrf_token() }}'
    },
    success: function (response) {
    console.log('Answer updated:', response.data.message);
    },
    error: function (xhr, status, error) {
    console.error('Error updating answer:', error);
    }
    });
    });


     $('input.select-answer-checkbox-accident').on('change', function () {
    const columnName = $(this).data('column');
    const value = $(this).data('value');
    const caseId = $(this).data('case');

    // Highlight checkbox when checked
    if ($(this).is(':checked')) {
    $(this).closest('.form-check').addClass('bg-success text-white'); // green background
    } else {
    $(this).closest('.form-check').removeClass('bg-success text-white');
    }

    // AJAX call to update database
    $.ajax({
    url: "{{ route('save.selected') }}",
    method: 'POST',
    data: {
    column_name: columnName,
    value: value,
    case_id: caseId,
    _token: '{{ csrf_token() }}'
    },
    success: function (response) {
    console.log('Answer updated:', response.data.message);
    },
    error: function (xhr, status, error) {
    console.error('Error updating answer:', error);
    }
    });
    });


</script>
@endsection