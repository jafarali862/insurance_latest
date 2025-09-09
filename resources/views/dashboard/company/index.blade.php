@extends('dashboard.layout.app')
@section('title', 'Company List')

@push('styles')
    <!-- DataTables CSS for Bootstrap4 (AdminLTE compatible) -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap4.min.css" />
@endpush

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-end mb-3">
        <button class="btn btn-danger mr-2" onclick="window.history.back()">
            <i class="fas fa-arrow-left"></i>
        </button>
        <button class="btn btn-warning mr-2" onclick="window.location.reload()">
            <i class="fas fa-sync-alt"></i>
        </button>
       <a href="{{ route('company.create') }}" class="btn btn-success" title="Create New Company">
    <i class="fas fa-building plus"></i>
</a>

    </div>

    <!-- Card -->
    <div class="card card-primary card-outline shadow-sm">
        <div class="card-header">
            <h3 class="card-title  mt-0" style="font-size: 32px;">Company List</h3>
        </div>

        <div class="card-body table-responsive p-0">
           
            <table id="companyTable" class="table table-bordered table-hover text-center mb-0" style="width:100%">
                <thead class="thead-dark">
                    <tr>
                        <th style="width: 50px;">#</th>
                        <th>Company Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Template</th>
                        <th>Status</th>
                        <th style="width: 100px;">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($companies as $index => $company)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $company->name }}</td>
                            <td>{{ $company->email }}</td>
                            <td>{{ $company->phone }}</td>
                            <td>{{ $company->template ? "Template {$company->template}" : 'Default Template' }}</td>
                            <td>
                                @if ($company->status == 1)
                                    <span class="badge badge-success">Active</span>
                                @else
                                    <span class="badge badge-danger">Inactive</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('companies.edit', $company->id) }}" class="btn btn-sm btn-info" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>

                            <form action="{{ route('companies.destroy', $company->id) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('Are you sure you want to delete this company?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger" title="Delete">
                            <i class="fas fa-trash-alt"></i>
                            </button>
                            </form>

                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            @if($companies->isEmpty())
                <div class="p-3 text-center text-muted">
                    No companies found.
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('scripts')
    <!-- jQuery and DataTables JS -->
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap4.min.js"></script>

    <script>
        $(document).ready(function() {
            $('#companyTable').DataTable({
                paging: true,
                lengthChange: true,
                searching: true,
                ordering: true,
                info: true,
                autoWidth: false,
                responsive: true,
                pageLength: 10,
                language: {
                    search: "_INPUT_",
                    searchPlaceholder: "Search companies..."
                },
                columnDefs: [
                    { orderable: false, targets: 6 } // Disable ordering on the Action column
                ],
            });
        });
    </script>
@endpush
