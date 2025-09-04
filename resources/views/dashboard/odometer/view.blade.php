@extends('dashboard.layout.app')

@section('title', 'Odometer View')

@section('content')
    <div class="container-fluid">

        <div class="text-right">
            <button class="btn btn-danger mr-2 mb-2" onclick="window.history.back()"><i class="fa fa-arrow-left"
                    aria-hidden="true"></i></button>
            <button class="btn btn-warning mr-2 mb-2" onclick="window.location.reload()"><i class="fa fa-spinner"
                    aria-hidden="true"></i></button>
            <a href="{{ route('odometer.list') }}" class="btn btn-primary mr-2 mb-2">
                <i class="fa fa-list" aria-hidden="true"></i>
            </a>
        </div>
        <h1 class="h3 mb-4 text-gray-800">Odometer Reading Details</h1>

        {{-- <div class="text-right">
            <a href="{{ route('odometer.list') }}" class="btn btn-primary mr-5"><i class="fa fa-backward"
                    aria-hidden="true"></i> Back</a>
        </div> --}}

        <div class="card shadow-lg border-0 rounded">
            <div class="card-body">
                <h5 class="card-title">Executive Name: <span class="text-primary">{{ $record->user_name }}</span></h5>
                <hr class="my-4">
                <div class="row mb-3">
                    <div class="col-md-4">
                        <p class="card-text"><strong>Check In KM:</strong> <span
                                class="text-muted">{{ $record->check_in_km }}</span></p>
                        <p class="card-text"><strong>Check In Time:</strong> <span
                                class="text-muted">{{ $record->check_in_time }}</span></p>
                        <p class="card-text"><strong>Check In Date:</strong> <span
                                class="text-muted">{{ $record->check_in_date }}</span></p>
                        <p class="card-text"><strong>Check In Location:</strong> <span
                                class="text-muted">{{ $record->check_in_latitude_and_longitude ?? 'N/A' }}</span></p>
                    </div>
                    <div class="col-md-4">
                        <p class="card-text"><strong>Check Out KM:</strong> <span
                                class="text-muted">{{ $record->check_out_km ?? 'N/A' }}</span></p>
                        <p class="card-text"><strong>Check Out Time:</strong> <span
                                class="text-muted">{{ $record->check_out_time ?? 'N/A' }}</span></p>
                        <p class="card-text"><strong>Check Out Date:</strong> <span
                                class="text-muted">{{ $record->check_out_date ?? 'N/A' }}</span></p>
                        <p class="card-text"><strong>Check Out Location:</strong> <span
                                class="text-muted">{{ $record->check_out_latitude_and_longitude ?? 'N/A' }}</span></p>
                    </div>
                    <div class="col-md-4">
                        <p class="card-text"><strong>Total KM:</strong>
                            @php
                                $totalKm = $record->check_out_km ? $record->check_out_km - $record->check_in_km : null;
                            @endphp
                            <span class="text-muted {{ $totalKm < 0 ? 'text-danger' : '' }}">
                                {{ $totalKm !== null ? $totalKm : 'Check Out KM not recorded' }}
                            </span>
                            @if ($totalKm < 0)
                                <span class="badge badge-danger ml-2">Wrong</span>
                            @endif
                        </p>
                        <p class="card-text"><strong>Work Duration:</strong>
                            @php
                                $checkInTime = \Carbon\Carbon::createFromFormat('h:i A', $record->check_in_time);

                                if ($record->check_out_time) {
                                    $checkOutTime = \Carbon\Carbon::createFromFormat('h:i A', $record->check_out_time);
                                    $duration = $checkOutTime->diff($checkInTime);
                                } else {
                                    $duration = null;
                                }
                            @endphp

                            @if ($duration)
                                <span class="text-muted">{{ $duration->format('%h hours %i minutes') }}</span>
                            @else
                                <span class="text-muted">Check out time not recorded</span>
                            @endif

                        </p>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-4">
                        <p class="card-text"><strong>Check In Image:</strong></p>
                        @if ($record->check_in_image)
                            <a href="{{ asset('storage/' . $record->check_in_image) }}" data-lightbox="checkin"
                                data-title="Check In Image">
                                <img src="{{ asset('storage/' . $record->check_in_image) }}" alt="Check In Image"
                                    class="img-fluid rounded shadow-sm"
                                    style="max-width: 300px; max-height: 200px; width: auto; height: auto;" />
                            </a>
                        @else
                            <span class="text-warning">N/A</span>
                        @endif
                    </div>
                    <div class="col-md-4">
                        <p class="card-text"><strong>Check Out Image:</strong></p>
                        @if ($record->check_out_image)
                            <a href="{{ asset('storage/' . $record->check_out_image) }}" data-lightbox="checkout"
                                data-title="Check Out Image">
                                <img src="{{ asset('storage/' . $record->check_out_image) }}" alt="Check Out Image"
                                    class="img-fluid rounded shadow-sm"
                                    style="max-width: 300px; max-height: 200px; width: auto; height: auto;" />
                            </a>
                        @else
                            <span class="text-warning">N/A</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
