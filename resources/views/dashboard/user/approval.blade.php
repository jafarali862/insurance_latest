@extends('dashboard.layout.app')
@section('title', 'Approval Request')

@section('content')
<!-- <div class="container-fluid">
<div class="d-flex justify-content-between align-items-center mb-3">
<h3 class="fw-bold text-primary mb-0">Mobile Approval Request</h3>
<div>
<a href="javascript:history.back()" class="btn btn-danger mr-2">
<i class="fas fa-arrow-left"></i>
</a>
<a href="{{ url()->current() }}" class="btn btn-warning">
<i class="fas fa-sync"></i>
</a>
</div>
</div> -->

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
    <h3 class="card-title" style="font-size: 32px;">Mobile Approval Request</h3>
    </div>


    <div class="card">
        <div class="card-body">
            <table id="approvalTable" class="table table-bordered table-striped text-center">
                <thead class="thead-dark">
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Place</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($users as $user)
                        <tr id="user-row-{{ $user->id }}">
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>{{ $user->place }}</td>
                            <td>
                                <form action="{{ route('approval.request.update', $user->id) }}" method="POST"
                                    class="approveForm d-inline" data-user-id="{{ $user->id }}">
                                    @csrf
                                    <button type="button" class="btn btn-success btn-sm approveBtn">
                                        <i class="fas fa-check"></i> Approve
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>


<!-- Approval Confirmation Modal -->
<div class="modal fade" id="confirmationModal" tabindex="-1" role="dialog" aria-labelledby="confirmationModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="confirmationModalLabel">Confirm Approval</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true" style="color:white;">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                Are you sure you want to approve this user?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="confirmApproveBtn">Approve</button>
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
        // Initialize DataTable
        $('#approvalTable').DataTable({
            "responsive": true,
            "autoWidth": false,
            "ordering": true,
            "pageLength": 10,
            "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
            "language": {
                "search": "Search:",
                "zeroRecords": "No matching records found",
                "info": "Showing _START_ to _END_ of _TOTAL_ users",
                "infoEmpty": "No users available",
                "infoFiltered": "(filtered from _MAX_ total records)"
            }
        });

        // Handle approve button logic
        let currentForm;
        $('.approveBtn').on('click', function () {
            currentForm = $(this).closest('form');
            $('#confirmationModal').modal('show');
        });

        $('#confirmApproveBtn').on('click', function () {
            $.ajax({
                url: currentForm.attr('action'),
                type: 'POST',
                data: currentForm.serialize(),
                success: function (response) {
                    $('#user-row-' + currentForm.data('user-id')).fadeOut('slow', function () {
                        $(this).remove();
                    });
                    $('#confirmationModal').modal('hide');
                },
                error: function (xhr) {
                    alert('Error: ' + xhr.responseText);
                }
            });
        });
    });
</script>
@endsection
