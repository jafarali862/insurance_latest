@extends('dashboard.layout.app')
@section('title', 'Password Reset List')

@section('content')

     <div class="container-fluid">
    <div class="d-flex justify-content-end mb-3">
    <button class="btn btn-danger mr-2" onclick="window.history.back()" title="Back">
    <i class="fas fa-arrow-left"></i>
    </button>
    <button class="btn btn-warning mr-2" onclick="window.location.reload()" title="Reload">
    <i class="fas fa-sync-alt"></i>
    </button>
    <a href="{{ route('all.password.reset.request') }}" class="btn btn-primary">
    Show All Requests <i class="fas fa-list-ul"></i>
    </a>
    </div>

    <div class="card card-primary card-outline shadow-sm">
    <div class="card-header">
    <h3 class="card-title" style="font-size: 32px;">Pending Password Reset List</h3>
    </div>


    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <div class="card">
        <div class="card-body">
            <table id="passwordResetTable" class="table table-bordered table-striped text-center">
                <thead class="thead-dark">
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Request Date</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($requests as $index => $request)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $request->user_name }}</td>
                        <td>{{ $request->request_date }}</td>
                        <td>
                            <a href="{{ route('password.reset.edit', $request->id) }}" class="btn btn-primary btn-sm">
                                <i class="fas fa-pencil-alt"></i>
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
<!-- DataTables CSS/JS (if not already included in layout) -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap4.min.css">
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap4.min.js"></script>

<script>
    $(document).ready(function () {
        $('#passwordResetTable').DataTable({
            "responsive": true,
            "autoWidth": false,
            "ordering": true,
            "pageLength": 10,
            "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
            "language": {
                "search": "Search:",
                "lengthMenu": "Show _MENU_ entries",
                "zeroRecords": "No matching requests found",
                "info": "Showing _START_ to _END_ of _TOTAL_ requests",
                "infoEmpty": "No requests available",
                "infoFiltered": "(filtered from _MAX_ total entries)"
            }
        });
    });
</script>
@endsection
