@extends('dashboard.layout.app')
@section('title', 'Report Request')

@section('content')

    <div class="container-fluid">
    <div class="d-flex justify-content-end mb-3">
    <button class="btn btn-danger mr-2" onclick="window.history.back()" title="Back">
    <i class="fas fa-arrow-left"></i>
    </button>
    <button class="btn btn-warning mr-2" onclick="window.location.reload()" title="Reload">
    <i class="fas fa-sync-alt"></i>
    </button>

    </div>

    <div class="card card-primary card-outline shadow-sm">
    <div class="card-header">
    <h3 class="card-title mb-0" style="font-size: 32px;">Report Request List</h3>
    </div>



        <div class="card-body">
            <table id="reportTable" class="table table-bordered table-striped">
                <thead class="thead-dark">
                    <tr>
                        <th>ID</th>
                        <th>Customer Name</th>
                        <th>Company Name</th>
                        <th>Investigation Date</th>
                        <th>Type</th>
                         <th>Crime Number</th>
                        <th>Police Station</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($reports as $i => $report)
                        <tr>
                            <td>{{ $i + 1 }}</td>
                            <td>{{ $report->customer_name }}</td>
                            <td>{{ $report->company_name }}</td>
                            <td>{{ \Carbon\Carbon::parse($report->date)->format('d-m-Y') }}</td>
                            <td>{{ $report->type }}</td>
                            <td>{{ $report->crime_number }}</td>
                            <td>{{ $report->police_station }}</td>
                            <td>
                                <a href="{{ route('request.report.view', $report->report_id) }}" class="btn btn-info btn-sm">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<!-- DataTables JS & CSS (make sure included in layout or add here) -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap4.min.css">
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap4.min.js"></script>

<script>
    $(document).ready(function () {
        $('#reportTable').DataTable({
            "ordering": true,
            "responsive": true,
            "autoWidth": false,
            "pageLength": 10,
            "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
        });
    });
</script>
@endsection
