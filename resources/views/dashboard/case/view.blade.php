<div class="container-fluid">
    <div class="row">
        <!-- Left Column -->
        <div class="col-md-6">
            <h5>Customer Name:</h5>
            <p>{{ $customers->name }} ({{ $customers->phone }})</p>

            <h5>Company:</h5>
            <p>{{ $customers->custname }}</p>

            <h5>Crime Number:</h5>
            <p>{{ $customers->crime_number }}</p>

            <h5>Police Station:</h5>
            <p>{{ $customers->police_station }}</p>

              <h5>Executive Name:</h5>
            <p><b>Driver</b>: {{  $customers->driver_name }} </p>
            <p><b>Garage</b>: {{  $customers->garage_name }} </p>
            <p><b>Spot</b>: {{  $customers->spot_name }} </p>
            <p><b>Meeting</b>: {{  $customers->meeting_name }} </p>
            <p><b>Accident</b>: {{  $customers->accident_name }} </p>
        </div>

        <!-- Right Column -->
        <div class="col-md-6">
            <h5>Assign Date:</h5>
            <p>{{ \Carbon\Carbon::parse($customers->created_at)->format('d-m-Y') }}</p>

            <h5>Status:</h5>
            <p>
                @if ($customers->case_status == 1)
                    <span class="badge bg-danger">Pending</span>
                @elseif ($customers->case_status == 0)
                    <span class="badge bg-success">Complete</span>
                @elseif ($customers->case_status == 2)
                    <span class="badge bg-warning">Assigned</span>
                @else
                    <span class="badge bg-secondary">Unknown</span>
                @endif
            </p>

             <h5>Investigation Date:</h5>
            <p>{{ \Carbon\Carbon::parse($customers->case_date)->format('d-m-Y') }}</p>


             <h5>Case Type:</h5>
            <p>{{  $customers->insurance_type }} </p>


           


            {{-- Add more details if needed --}}
        </div>
    </div>
</div>
