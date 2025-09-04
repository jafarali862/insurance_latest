<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <title>Odometer Data</title>
    <style>
        body {
            background-color: #f8f9fa;
            color: #333;
            padding: 15px;
        }

        .card {
            background-color: #ffffff;
            border: 1px solid #ddd;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            margin-bottom: 15px;
        }

        .card-header {
            background-color: #007bff;
            color: white;
            cursor: pointer;
        }

        .card-body {
            padding: 10px;
        }

        img {
            max-width: 200px;
            min-width: 100px;
            max-height: 150px;
            min-height: 100px;
            height: auto;
            border-radius: 5px;
        }

        .h6 {
            font-size: 0.9rem;
        }

        .alert {
            margin-bottom: 20px;
        }
    </style>
</head>

<body>
    <div class="alert alert-warning" role="alert" id="noConnectionAlert" style="display: none;">
        No data connection available. Please check your internet connection.
    </div>
    <div class="container" id="mainContent">
        
        
        @if ($data->isEmpty())
            <div class="alert alert-warning" role="alert">
                No data available for the current month.
            </div>
        @else
            <div class="alert" role="alert">
                <span class="text-danger">*</span> Current month data only available.
            </div>
        @endif
        
        <div class="accordion mt-3" id="supportAccordion">
            @foreach ($data as $item)
                <div class="card">
                    <div class="card-header" id="heading{{ $loop->index }}" data-toggle="collapse"
                        data-target="#collapse{{ $loop->index }}" aria-expanded="false"
                        aria-controls="collapse{{ $loop->index }}">
                        <h5 class="mb-0">Date: {{ $item->check_in_date }}</h5>
                    </div>
                    <div id="collapse{{ $loop->index }}" class="collapse" aria-labelledby="heading{{ $loop->index }}"
                        data-parent="#supportAccordion">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4">
                                    <p class="card-text"><strong>Check In KM:</strong> <span
                                            class="text-muted">{{ $item->check_in_km }}</span></p>
                                    <p class="card-text"><strong>Check In Time:</strong> <span
                                            class="text-muted">{{ $item->check_in_time }}</span></p>
                                    <p class="card-text"><strong>Check In Image:</strong>
                                        <img src="{{ asset('storage/' . $item->check_in_image) }}" alt="Check In"
                                            class="img-fluid mb-2">
                                    </p>
                                </div>
                                <div class="col-md-4">
                                    <p class="card-text"><strong>Check Out KM:</strong> <span
                                            class="text-muted">{{ $item->check_out_km ?? 'N/A' }}</span></p>
                                    <p class="card-text"><strong>Check Out Time:</strong> <span
                                            class="text-muted">{{ $item->check_out_time ?? 'N/A' }}</span></p>
                                    <p class="card-text"><strong>Check Out Image:</strong>
                                        @if ($item->check_out_image)
                                            <img src="{{ asset('storage/' . $item->check_out_image) }}" alt="Check Out"
                                                class="img-fluid mb-2">
                                        @else
                                            <span class="text-muted">N/A</span>
                                        @endif
                                    </p>
                                </div>
                                <div class="col-md-4">
                                    <p class="card-text"><strong>Total KM:</strong>
                                        @php
                                            $totalKm = $item->check_out_km
                                                ? $item->check_out_km - $item->check_in_km
                                                : null;
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
                                            $checkInTime = \Carbon\Carbon::createFromFormat(
                                                'h:i A',
                                                $item->check_in_time,
                                            );
                                            $duration = null;

                                            if ($item->check_out_time) {
                                                $checkOutTime = \Carbon\Carbon::createFromFormat(
                                                    'h:i A',
                                                    $item->check_out_time,
                                                );
                                                $duration = $checkOutTime->diff($checkInTime);
                                            }
                                        @endphp

                                        @if ($duration)
                                            <span
                                                class="text-muted">{{ $duration->format('%h hours %i minutes') }}</span>
                                        @else
                                            <span class="text-muted">Check out time not recorded</span>
                                        @endif
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        <div class="mt-4">
            {{ $data->links() }}
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        // Check internet connection
        function updateConnectionStatus() {
            const noConnectionAlert = document.getElementById('noConnectionAlert');
            const mainContent = document.getElementById('mainContent');

            if (navigator.onLine) {
                noConnectionAlert.style.display = 'none';
                mainContent.style.display = 'block';
            } else {
                noConnectionAlert.style.display = 'block';
                mainContent.style.display = 'none';
            }
        }

        window.addEventListener('load', () => {
            updateConnectionStatus(); // Check on load
        });
        window.addEventListener('online', updateConnectionStatus); // Check when back online
        window.addEventListener('offline', updateConnectionStatus); // Check when offline
    </script>
</body>

</html>
