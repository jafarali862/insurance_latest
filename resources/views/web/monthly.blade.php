<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Monthly Records</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        body {
            background-color: #f8f9fa; /* Light background */
        }
        .record {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            border-radius: 15px; /* Rounded corners */
            padding: 2rem;
            color: white;
            text-align: center;
            transition: transform 0.3s; /* Smooth hover effect */
            margin-bottom: 1.5rem;
        }
        .garage { background-color: #007bff; }
        .driver { background-color: #28a745; }
        .accident { background-color: #dc3545; }
        .owner { background-color: #ffc107; }
        .spot { background-color: #6f42c1; }
        .count {
            font-size: 3rem; /* Larger count font */
            font-weight: bold;
        }
        .total {
            background-color: #343a40; /* Dark color for total section */
            color: white;
            padding: 2rem;
            border-radius: 15px;
            text-align: center;
            margin-top: 2rem;
        }
        h1 {
            color: #343a40; /* Dark color for header */
        }
    </style>
</head>
<body>
    <div class="container my-5">
        <button class="btn btn-outline-primary mb-3" onclick="history.back()">
            <i class="fas fa-arrow-left"></i> Back
        </button>
        <h1 class="text-center mb-4">Monthly Records</h1>

        <div class="row">
            <div class="col-md-6 col-lg-4">
                <div class="record garage">
                    <h4>Garage Data</h4>
                    <p class="count">{{ $garageCount }}</p>
                </div>
            </div>
            <div class="col-md-6 col-lg-4">
                <div class="record driver">
                    <h4>Driver Data</h4>
                    <p class="count">{{ $driverCount }}</p>
                </div>
            </div>
            <div class="col-md-6 col-lg-4">
                <div class="record accident">
                    <h4>Accident Person Data</h4>
                    <p class="count">{{ $accidentPersonCount }}</p>
                </div>
            </div>
            <div class="col-md-6 col-lg-4">
                <div class="record owner">
                    <h4>Owner Data</h4>
                    <p class="count">{{ $ownerCount }}</p>
                </div>
            </div>
            <div class="col-md-6 col-lg-4">
                <div class="record spot">
                    <h4>Spot Data</h4>
                    <p class="count">{{ $spotCount }}</p>
                </div>
            </div>
        </div>

        <div class="total">
            <h2>Total Count</h2>
            <p class="count">{{ $garageCount + $driverCount + $accidentPersonCount + $ownerCount + $spotCount }}</p>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
