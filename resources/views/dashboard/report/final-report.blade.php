<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Final Report</title>
</head>

<body >

        <h1 style="text-align: center; color: #2c3e50; margin-bottom: 20px;">Final Report</h1>

        <div
            style="margin-bottom: 20px; padding: 15px; border: 1px solid #ecf0f1; border-radius: 5px; background: #ecf0f1;">
            <h2 style="color: #2980b9; border-bottom: 2px solid #2980b9; padding-bottom: 10px; margin-top: 20px;">
                Customer Details</h2>
            <p style="line-height: 1.6; color: #34495e;"><strong>Customer Name:</strong> {{ $report->customer_name }}</p>
            <p style="line-height: 1.6; color: #34495e;"><strong>Company Name:</strong> {{ $report->company_name }}</p>
            <p style="line-height: 1.6; color: #34495e;"><strong>Case Type:</strong> {{ $report->type }}</p>
        </div>

        <div
            style="margin-bottom: 20px; padding: 15px; border: 1px solid #ecf0f1; border-radius: 5px; background: #ecf0f1;">
            <h2 style="color: #2980b9; border-bottom: 2px solid #2980b9; padding-bottom: 10px; margin-top: 20px;">Garage
                Details</h2>
            <p style="line-height: 1.6; color: #34495e;"><strong>Gate Entry:</strong> {{ $report->garage_gate_entry }}
            </p>
            <div style="display: flex; flex-wrap: wrap; gap: 10px;">
                @if ($report->garage_gate_entry_images && json_decode($report->garage_gate_entry_images))
                    @foreach (json_decode($report->garage_gate_entry_images) as $image)
                        <img src="{{ public_path('storage/' . $image) }}" alt="Garage Gate Entry Image" class="img-thumbnail"
                            style="max-width: 100px; border: 2px solid #2980b9; border-radius: 5px; transition: transform 0.3s;">
                    @endforeach
                @else
                    <p class="text-muted">No gate entry images available</p>
                @endif
            </div>
            <div style="display: flex; flex-wrap: wrap; gap: 10px;">
                @if ($report->vehicle_images && json_decode($report->vehicle_images))
                    @foreach (json_decode($report->vehicle_images) as $image)
                        <img src="{{ public_path('storage/' . $image) }}" alt="Garage Gate Entry Image" class="img-thumbnail"
                            style="max-width: 100px; border: 2px solid #2980b9; border-radius: 5px; transition: transform 0.3s;">
                    @endforeach
                @else
                    <p class="text-muted">No gate entry images available</p>
                @endif
            </div>
            <p style="line-height: 1.6; color: #34495e;"><strong>Job Card:</strong> {{ $report->garage_job_card }}</p><br>
            <div style="display: flex; flex-wrap: wrap; gap: 10px;">
                @if ($report->garage_job_card_images && json_decode($report->garage_job_card_images))
                    @foreach (json_decode($report->garage_job_card_images) as $image)
                        <img src="{{ public_path('storage/' . $image) }}" alt="Garage Gate Entry Image" class="img-thumbnail"
                            style="max-width: 100px; border: 2px solid #2980b9; border-radius: 5px; transition: transform 0.3s;">
                    @endforeach
                @else
                    <p class="text-muted">No gate entry images available</p>
                @endif
            </div>
        </div>

        <div
            style="margin-bottom: 20px; padding: 15px; border: 1px solid #ecf0f1; border-radius: 5px; background: #ecf0f1;">
            <h2 style="color: #2980b9; border-bottom: 2px solid #2980b9; padding-bottom: 10px; margin-top: 20px;">Driver
                Details</h2>
            <p style="line-height: 1.6; color: #34495e;"><strong>Driver Name:</strong> {{ $report->driver_name}}</p><br>
            <div style="display: flex; flex-wrap: wrap; gap: 10px;">
                @if ($report->driver_image && json_decode($report->driver_image))
                    <div class="gallery">
                        @foreach (json_decode($report->driver_image) as $image)
                        <img src="{{ public_path('storage/' . $image) }}" alt="Driver Image" class="img-thumbnail">
                        @endforeach
                    </div>
                @else
                    <p class="text-muted">No driver images available</p>
                @endif
            </div>
        </div>

        <div
            style="margin-bottom: 20px; padding: 15px; border: 1px solid #ecf0f1; border-radius: 5px; background: #ecf0f1;">
            <h2 style="color: #2980b9; border-bottom: 2px solid #2980b9; padding-bottom: 10px; margin-top: 20px;">Spot
                Details</h2>
            <p style="line-height: 1.6; color: #34495e;"><strong>Spot Address:</strong> [Spot Address]</p>
            <div style="display: flex; flex-wrap: wrap; gap: 10px;">
                <img src="[Spot Image URL]" alt="Spot Image"
                    style="max-width: 100px; border: 2px solid #2980b9; border-radius: 5px; transition: transform 0.3s;">
            </div>
        </div>

        <div
            style="margin-bottom: 20px; padding: 15px; border: 1px solid #ecf0f1; border-radius: 5px; background: #ecf0f1;">
            <h2 style="color: #2980b9; border-bottom: 2px solid #2980b9; padding-bottom: 10px; margin-top: 20px;">Owner
                Details</h2>
            <p style="line-height: 1.6; color: #34495e;"><strong>Written Statement:</strong> [Written Statement]</p>
            <div style="display: flex; flex-wrap: wrap; gap: 10px;">
                <img src="[Aadhaar Card Owner URL]" alt="Owner Aadhaar Card"
                    style="max-width: 100px; border: 2px solid #2980b9; border-radius: 5px; transition: transform 0.3s;">
            </div>
        </div>

        <div
            style="margin-bottom: 20px; padding: 15px; border: 1px solid #ecf0f1; border-radius: 5px; background: #ecf0f1;">
            <h2 style="color: #2980b9; border-bottom: 2px solid #2980b9; padding-bottom: 10px; margin-top: 20px;">
                Accident Person Details</h2>
            <div style="display: flex; flex-wrap: wrap; gap: 10px;">
                <img src="[Accident Person Image URL]" alt="Accident Person Image"
                    style="max-width: 100px; border: 2px solid #2980b9; border-radius: 5px; transition: transform 0.3s;">
                <img src="[Aadhaar Card Accident Person URL]" alt="Accident Person Aadhaar Card"
                    style="max-width: 100px; border: 2px solid #2980b9; border-radius: 5px; transition: transform 0.3s;">
            </div>
            <p style="line-height: 1.6; color: #34495e;"><strong>Written Statement:</strong> [Written Statement]</p>
        </div>
</body>

</html>
