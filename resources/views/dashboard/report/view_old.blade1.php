@extends('dashboard.layout.app')
@section('title', 'Report Request Detail')

@section('content')
    <div class="container-fluid">
        <h3>Report Details for {{ $report->customer_name }}</h3>

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
                    <h6>Entry {{ $index + 1 }}</h6>
                    <p><strong>Garage Gate Entry:</strong> {{ $garage->garage_gate_entry }}</p>
                    <p><strong>Garage Job Card:</strong> {{ $garage->garage_job_card }}</p>
                    <p><strong>Executive Name:</strong> {{ $garage->executive_name }}</p>

                    <h6>Images:</h6>
                    @if ($garage->garage_gate_entry_images)
                        <div>
                            @foreach (json_decode($garage->garage_gate_entry_images) as $image)
                                <a href="{{ asset('storage/' . $image) }}" data-lightbox="garage-gate-entry" data-title="Garage Gate Entry Image">
                                    <img src="{{ asset('storage/' . $image) }}" alt="Garage Gate Entry Image" class="img-fluid mb-2" style="max-width: 200px;">
                                </a>
                            @endforeach
                        </div>
                    @endif

                    @if ($garage->garage_job_card_images)
                        <div>
                            @foreach (json_decode($garage->garage_job_card_images) as $image)
                                <a href="{{ asset('storage/' . $image) }}" data-lightbox="garage-job-card" data-title="Garage Job Card Image">
                                    <img src="{{ asset('storage/' . $image) }}" alt="Garage Job Card Image" class="img-fluid mb-2" style="max-width: 200px;">
                                </a>
                            @endforeach
                        </div>
                    @endif

                    @if ($garage->vehicle_images)
                        <h6>Vehicle Images:</h6>
                        <div>
                            @foreach (json_decode($garage->vehicle_images) as $image)
                                <a href="{{ asset('storage/' . $image) }}" data-lightbox="vehicle" data-title="Vehicle Image">
                                    <img src="{{ asset('storage/' . $image) }}" alt="Vehicle Image" class="img-fluid mb-2" style="max-width: 200px;">
                                </a>
                            @endforeach
                        </div>
                    @endif

                    @if ($garage->garage_voice)
                        <h6>Garage Voice:</h6>
                        @foreach (json_decode($garage->garage_voice) as $voice)
                            <audio controls>
                                <source src="{{ asset('storage/' . $voice) }}" type="audio/mpeg">
                                Your browser does not support the audio tag.
                            </audio>
                        @endforeach
                    @endif
                </div>
            </div>
        @endforeach

        <hr>

        <h5>Driver Data</h5>
        @if ($report->driver_reassign_status == 1)
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
                    <h6>Entry {{ $index + 1 }}</h6>
                    <p><strong>Driver Name:</strong> {{ $driver->driver_name }}</p>
                    <p><strong>Executive Name:</strong> {{ $driver->executive_name }}</p>

                    <h6>Driver Images</h6>
                    @if ($driver->driver_image)
                        <div>
                            @foreach (json_decode($driver->driver_image) as $image)
                                <a href="{{ asset('storage/' . $image) }}" data-lightbox="driver" data-title="Driver Image">
                                    <img src="{{ asset('storage/' . $image) }}" alt="Driver Image" class="img-fluid mb-2" style="max-width: 200px;">
                                </a>
                            @endforeach
                        </div>
                    @endif

                    <h6>Images:</h6>
                    @if ($driver->driving_licence_images)
                        <div>
                            @foreach (json_decode($driver->driving_licence_images) as $image)
                                <a href="{{ asset('storage/' . $image) }}" data-lightbox="driving-licence" data-title="Driving Licence Image">
                                    <img src="{{ asset('storage/' . $image) }}" alt="Driving Licence Image" class="img-fluid mb-2" style="max-width: 200px;">
                                </a>
                            @endforeach
                        </div>
                    @endif

                    @if ($driver->rc_images)
                        <div>
                            @foreach (json_decode($driver->rc_images) as $image)
                                <a href="{{ asset('storage/' . $image) }}" data-lightbox="rc" data-title="RC Image">
                                    <img src="{{ asset('storage/' . $image) }}" alt="RC Image" class="img-fluid mb-2" style="max-width: 200px;">
                                </a>
                            @endforeach
                        </div>
                    @endif

                    @if ($driver->driver_aadhaar_card_images)
                        <div>
                            @foreach (json_decode($driver->driver_aadhaar_card_images) as $image)
                                <a href="{{ asset('storage/' . $image) }}" data-lightbox="aadhaar-card" data-title="Driver Aadhaar Card Image">
                                    <img src="{{ asset('storage/' . $image) }}" alt="Driver Aadhaar Card Image" class="img-fluid mb-2" style="max-width: 200px;">
                                </a>
                            @endforeach
                        </div>
                    @endif

                    @if ($driver->driver_voice)
                        <h6>Driver Voice:</h6>
                        @foreach (json_decode($driver->driver_voice) as $voice)
                            <audio controls>
                                <source src="{{ asset('storage/' . $voice) }}" type="audio/mpeg">
                                Your browser does not support the audio tag.
                            </audio>
                        @endforeach
                    @endif
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
                    <h6>Entry {{ $index + 1 }}</h6>
                    <p><strong>Spot Address:</strong> {{ $spot->spot_address }}</p>
                    <p><strong>Executive Name:</strong> {{ $spot->executive_name }}</p>

                    <h6>Images:</h6>
                    @if ($spot->spot_images)
                        <div>
                            @foreach (json_decode($spot->spot_images) as $image)
                                <a href="{{ asset('storage/' . $image) }}" data-lightbox="spot" data-title="Spot Image">
                                    <img src="{{ asset('storage/' . $image) }}" alt="Spot Image" class="img-fluid mb-2" style="max-width: 200px;">
                                </a>
                            @endforeach
                        </div>
                    @endif

                    @if ($spot->spot_voice)
                        <h6>Spot Voice:</h6>
                        @foreach (json_decode($spot->spot_voice) as $voice)
                            <audio controls>
                                <source src="{{ asset('storage/' . $voice) }}" type="audio/mpeg">
                                Your browser does not support the audio tag.
                            </audio>
                        @endforeach
                    @endif
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
                    <h6>Entry {{ $index + 1 }}</h6>
                    <p><strong>Executive Name:</strong> {{ $owner->executive_name }}</p>
                    @if ($owner->owner_written_statment_images)
                        <h6>Owner Written Statement Images:</h6>
                        <div>
                            @foreach (json_decode($owner->owner_written_statment_images) as $image)
                                <a href="{{ asset('storage/' . $image) }}" data-lightbox="owner-statement" data-title="Owner Written Statement Image">
                                    <img src="{{ asset('storage/' . $image) }}" alt="Owner Written Statement Image" class="img-fluid mb-2" style="max-width: 200px;">
                                </a>
                            @endforeach
                        </div>
                    @endif

                    @if ($owner->owner_aadhaar_card_images)
                        <h6>Owner Aadhaar Card Images:</h6>
                        <div>
                            @foreach (json_decode($owner->owner_aadhaar_card_images) as $image)
                                <a href="{{ asset('storage/' . $image) }}" data-lightbox="owner-aadhaar" data-title="Owner Aadhaar Card Image">
                                    <img src="{{ asset('storage/' . $image) }}" alt="Owner Aadhaar Card Image" class="img-fluid mb-2" style="max-width: 200px;">
                                </a>
                            @endforeach
                        </div>
                    @endif

                    @if ($owner->owner_voice)
                        <h6>Owner Voice:</h6>
                        @foreach (json_decode($owner->owner_voice) as $voice)
                            <audio controls>
                                <source src="{{ asset('storage/' . $voice) }}" type="audio/mpeg">
                                Your browser does not support the audio tag.
                            </audio>
                        @endforeach
                    @endif
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
                    <h6>Entry {{ $index + 1 }}</h6>
                    <p><strong>Executive Name:</strong> {{ $accident->executive_name }}</p>
                    @if ($accident->accident_person_images)
                        <h6>Accident Person Images:</h6>
                        <div>
                            @foreach (json_decode($accident->accident_person_images) as $image)
                                <a href="{{ asset('storage/' . $image) }}" data-lightbox="accident-person" data-title="Accident Person Image">
                                    <img src="{{ asset('storage/' . $image) }}" alt="Accident Person Image" class="img-fluid mb-2" style="max-width: 200px;">
                                </a>
                            @endforeach
                        </div>
                    @endif

                    @if ($accident->accident_person_aadhaar_card_images)
                        <h6>Accident Person Aadhaar Card Images:</h6>
                        <div>
                            @foreach (json_decode($accident->accident_person_aadhaar_card_images) as $image)
                                <a href="{{ asset('storage/' . $image) }}" data-lightbox="accident-person-aadhaar" data-title="Accident Person Aadhaar Card Image">
                                    <img src="{{ asset('storage/' . $image) }}" alt="Accident Person Aadhaar Card Image" class="img-fluid mb-2" style="max-width: 200px;">
                                </a>
                            @endforeach
                        </div>
                    @endif

                    @if ($accident->accident_person_written_statment_images)
                        <h6>Accident Person Written Statement Images:</h6>
                        <div>
                            @foreach (json_decode($accident->accident_person_written_statment_images) as $image)
                                <a href="{{ asset('storage/' . $image) }}" data-lightbox="accident-person-statement" data-title="Accident Person Written Statement Image">
                                    <img src="{{ asset('storage/' . $image) }}" alt="Accident Person Written Statement Image" class="img-fluid mb-2" style="max-width: 200px;">
                                </a>
                            @endforeach
                        </div>
                    @endif

                    @if ($accident->accident_person_voice)
                        <h6>Accident Person Voice:</h6>
                        @foreach (json_decode($accident->accident_person_voice) as $voice)
                            <audio controls>
                                <source src="{{ asset('storage/' . $voice) }}" type="audio/mpeg">
                                Your browser does not support the audio tag.
                            </audio>
                        @endforeach
                    @endif
                </div>
            </div>
        @endforeach
    </div>

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
    
@endsection
