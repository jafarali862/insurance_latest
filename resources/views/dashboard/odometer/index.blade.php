@extends('dashboard.layout.app')

@section('title', 'Odometer List')

@section('content')


<div class="container-fluid">
    <div class="d-flex justify-content-end mb-3">
        <button class="btn btn-danger mr-2" onclick="window.history.back()" title="Back">
            <i class="fas fa-arrow-left"></i>
        </button>
        <button class="btn btn-warning mr-2"  onclick="window.location.href='{{ route('odometer.list') }}'" title="Reload">
            <i class="fas fa-sync-alt"></i>
        </button>
     
    </div>

    <div class="card card-primary card-outline shadow-sm">
        <div class="card-header">
    <h3 class="card-title mb-0" style="font-size: 32px;">Odometer List</h3>

    
    <form method="GET" class="form-inline mb-3" id="filterForm" style="float: right;">
    <div class="form-row align-items-end justify-content-end">
    <div class="form-group mx-2">
    <label for="from_date" class="mr-2">From:</label>
    <input type="date" id="from_date" name="from_date" class="form-control"
    value="{{ request()->from_date }}">
    </div>

    <div class="form-group mx-2">
    <label for="to_date" class="mr-2">To:</label>
    <input type="date" id="to_date" name="to_date" class="form-control"
    value="{{ request()->to_date }}">
    </div>

    <!-- <div class="form-group mx-2">
    <button type="submit" class="btn btn-primary">Filter</button>
    </div> -->

    <!-- <div class="form-group mx-2">
    <a href="{{ route('odometer.list') }}" class="btn btn-secondary">Reset</a>
    </div> -->
    </div>
    </form>

     </div>



    <div class="card">
        <div class="card-body">
          
        <table id="odometerTable" class="table table-bordered table-striped table-hover">
                <thead class="thead-dark text-center">
                    <tr>
                        <th>S.No</th>
                        <th>Name</th>
                        <th>Check In Time</th>
                        <th>Check Out Time</th>
                        <th>Date</th>
                        <th>View</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($odometerRecords as  $record)
                        <tr class="text-center">
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $record->user_name }}</td>
                            <td>{{ $record->check_in_time }}</td>
                            <td>
                                @if($record->check_out_time)
                                    {{ $record->check_out_time }}
                                @else
                                    <span class="text-danger font-weight-bold">Exec not closed</span>
                                @endif
                            </td>
                            <td>
                                @if($record->check_out_date)
                                    {{ $record->check_out_date }}
                                @else
                                    <span class="text-danger font-weight-bold">Exec not closed</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('odometer.view', $record->id) }}" class="btn btn-info btn-sm">
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
<!-- DataTables JS & CSS (can be moved to layout) -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap4.min.css">
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap4.min.js"></script>

<script>
   $(document).ready(function () {
    $('#odometerTable').DataTable({
        "paging": true,
        "searching": true,
        "info": true,
        "ordering": true,
        "responsive": true,
        "autoWidth": false,
        "pageLength": 10,
        "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
        "language": {
            "search": "Search all columns:",
            "lengthMenu": "Show _MENU_ records per page",
            "zeroRecords": "No matching records found",
            "info": "Showing page _PAGE_ of _PAGES_",
            "infoEmpty": "No records available",
            "infoFiltered": "(filtered from _MAX_ total records)"
        }
    });
});

</script>

<script>
    document.getElementById('from_date').addEventListener('change', function () {
        document.getElementById('filterForm').submit();
    });

    document.getElementById('to_date').addEventListener('change', function () {
        document.getElementById('filterForm').submit();
    });
</script>


@endsection
