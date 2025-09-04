@extends('dashboard.layout.app')
@section('title', 'User List')

@section('content')

    <div class="container-fluid">
    <div class="d-flex justify-content-end mb-3">
    <a href="javascript:history.back()" class="btn btn-danger mr-2">
    <i class="fas fa-arrow-left"></i>
    </a>
    <a href="{{ url()->current() }}" class="btn btn-warning mr-2">
    <i class="fas fa-sync-alt"></i>
    </a>
    @if(count($logos) == 0)
    <a href="{{ route('logo') }}" class="btn btn-primary">
    <i class="fas fa-user-plus"></i> Add Company Info
    </a>
    @endif

    </div>

    <div class="card card-primary card-outline shadow-sm">
    <div class="card-header">
    <h3 class="card-title" style="font-size: 32px;">Company Info List</h3>
    </div>

    @if(session('success'))
    <div class="alert alert-success">
    {{ session('success') }}
    </div>
    @endif

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table id="companyInfoTable" class="table table-bordered table-striped text-center">
                    <thead class="thead-dark">
                        <tr>
                            <th>ID</th>
                            <th>Company Name</th>
                            <th>Email</th>
                            <th>Contact</th>
                            <th>Place</th>
                            <th>Logo</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($logos as $logo)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $logo->name }}</td>
                            <td>{{ $logo->email }}</td>
                            <td>{{ $logo->phone }}</td>
                            <td>{{ $logo->place }}</td>
                            <td>
                                <img src="{{ asset('storage/' . $logo->logo) }}" alt="Company Logo"
                                    class="img-fluid" style="max-width: 100px;">
                            </td>
                            <td>
                                <a href="{{ route('logo.edit', $logo->id) }}" class="btn btn-sm btn-primary">
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
</div>
@endsection

@section('scripts')
<!-- DataTables CSS & JS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap4.min.css">
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap4.min.js"></script>

<script>
    $(document).ready(function () {
        $('#companyInfoTable').DataTable({
            "responsive": true,
            "autoWidth": false,
            "ordering": true,
            "pageLength": 10,
            "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
            "language": {
                "search": "Search:",
                "zeroRecords": "No matching records found",
                "info": "Showing _START_ to _END_ of _TOTAL_ entries",
                "infoEmpty": "No records available",
                "infoFiltered": "(filtered from _MAX_ total records)"
            }
        });
    });
</script>
@endsection
