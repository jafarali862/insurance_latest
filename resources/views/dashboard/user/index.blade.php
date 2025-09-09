@extends('dashboard.layout.app')
@section('title', 'User List')

@push('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap4.min.css">
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
    <a href="{{ route('user.create') }}" class="btn btn-primary">
        <i class="fas fa-user-plus"></i>
    </a>
</div>

    </div>

    <div class="card card-primary card-outline shadow-sm">
        <div class="card-header">
            <div class="row align-items-center justify-content-between">
                <div class="col-md-4">
            <h4 class="card-title m-0" style="font-size: 32px;">User List</h4>
                </div>
                <div class="col-md-5">
                    <form id="statusForm" method="GET" class="form-inline justify-content-end gap-3">
                        <div class="form-check form-check-inline">
                            <input type="radio" name="status" id="triggerSubmit1" value="1" class="form-check-input" {{ ($status == 1) ? 'checked' : '' }}>
                            <label for="triggerSubmit1" class="form-check-label">Active</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input type="radio" name="status" id="triggerSubmit2" value="0" class="form-check-input" {{ ($status === 0) ? 'checked' : '' }}>
                            <label for="triggerSubmit2" class="form-check-label">Inactive</label>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="card-body table-responsive p-0">
            @if($users->count() > 0)

            <table class="table table-bordered table-hover text-center mb-0" id="userTable">
                <thead class="thead-dark">
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>IMEI</th>
                        <th>Status</th>
                        <th style="width: 100px;">Action</th>
                    </tr>
                </thead>
                <tbody>
                   
                @forelse ($users as $index => $user)
                <tr>
                <td>{{ $users->firstItem() + $index }}</td> <!-- 1 -->
                <td>{{ $user->name }}</td> <!-- 2 -->
                <td>{{ $user->email }}</td> <!-- 3 -->
                <td>
                @if($user->role == 2)
                Sub Admin
                @elseif($user->role == 3)
                Executive
                @else
                N/A
                @endif
                </td> <!-- 4 -->
                <td>{{ $user->imei ?? '-' }}</td> <!-- 5 -->
                <td>
                @if ($user->status == 1)
                <span class="badge badge-success">Active</span>
                @else
                <span class="badge badge-danger">Inactive</span>
                @endif
                </td> <!-- 6 -->
                <td>
                <a href="{{ route('user.edit', $user->id) }}" class="btn btn-sm btn-primary" title="Edit User">
                <i class="fas fa-edit"></i>
                </a>
                </td> <!-- 7 -->
                </tr>
                @empty
                <tr>
                <td colspan="7">No users found.</td>
                </tr>
                @endforelse

                </tbody>
            </table>
            @endif
        </div>

        {{-- If you're still using Laravel pagination --}}
        {{-- Optional if DataTables handles pagination entirely --}}
        {{-- <div class="card-footer d-flex justify-content-center">
            {{ $users->withQueryString()->links() }}
        </div> --}}
    </div>
</div>
@endsection




@push('scripts')
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap4.min.js"></script>

<script>
   $(document).ready(function () {
    const table = $('#userTable');
    if (table.length) {
        table.DataTable({
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
                searchPlaceholder: "Search users..."
            },
            columnDefs: [
                { orderable: false, targets: [6] }
            ]
        });
    }

    // Status filter radio buttons
    $('#triggerSubmit1, #triggerSubmit2').on('change', function () {
        $('#statusForm').submit();
    });
});

</script>
@endpush
