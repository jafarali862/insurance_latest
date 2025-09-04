@extends('dashboard.layout.app')
@section('title', 'Assigned Case List')
@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between mb-3">
        <div>
            <button class="btn btn-danger mr-2" onclick="window.history.back()">
                <i class="fa fa-arrow-left"></i>
            </button>
            <button class="btn btn-warning mr-2" onclick="window.location.reload()">
                <i class="fa fa-spinner"></i>
            </button>
            <a href="{{ route('insurance.create') }}" class="btn btn-primary mr-2">
                <i class="fa fa-plus"></i>
            </a>
        </div>

        <!-- <form method="GET" action="{{ route('assigned.case') }}" class="form-inline">
            <div class="input-group mr-2">
                <label for="from_date" class="mr-2">From Date</label>
                <input type="date" name="from_date" id="from_date" value="{{ request('from_date') }}" class="form-control">
            </div>
            <div class="input-group mr-2">
                <label for="to_date" class="mr-2">To Date</label>
                <input type="date" name="to_date" id="to_date" value="{{ request('to_date') }}" class="form-control">
            </div>
            <button type="submit" class="btn btn-primary mr-2">Filter</button>
            <a href="{{ route('assigned.case') }}" class="btn btn-secondary">Reset</a>
        </form> -->

        <div class="row mb-3">
        <form id="filterForm" class="form-inline">
            <div class="input-group mr-2">
                <label for="from_date" class="mr-2">From Date</label>
                <input type="date" name="from_date" id="from_date" value="{{ $from ?? '' }}" class="form-control">
            </div>
            <div class="input-group mr-2">
                <label for="to_date" class="mr-2">To Date</label>
                <input type="date" name="to_date" id="to_date" value="{{ $to ?? '' }}" class="form-control">
            </div>
        </form>

         <a href="{{ route('assigned.case') }}" class="btn btn-secondary">Reset</a>
    </div>


    </div>

    <div class="card card-primary card-outline">
        <div class="card-header">
            <h3 class="card-title" style="font-size: 32px;">Assigned Case List</h3>
        </div>
        <div class="card-body">
            <table id="casesTable" class="table table-bordered table-hover table-striped">
                <thead class="thead-dark">
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Company</th>
                        <th>Case Type</th>
                        <th>Crime Number</th>
                        <th>Police Station</th>
                        <th>Investigation Date</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $i = $cases->firstItem(); ?>
                    @foreach ($cases as $case)
                    <tr>
                        <td>{{ $i++ }}</td>
                        <td>{{ $case->customer_name }}<br/>{{ $case->phone }}</td>
                        <td>{{ $case->company_name }}</td>
                        <td>{{ $case->type }}</td>
                        <td>{{ $case->crime_number }}</td>
                        <td>{{ $case->police_station }}</td>
                        <td>{{ \Carbon\Carbon::createFromFormat('Y-m-d', $case->date)->format('d-F-Y') }}</td>
                        <td>
                            @if ($case->case_status == 1)
                            <span class="badge badge-danger">Pending</span>
                            <button type="button" class="btn btn-primary btn-sm ml-2 open-reassign-modal" data-url="{{ route('re.assign.case', $case->id) }}">
                                <i class="fa fa-retweet"></i>
                            </button>
                            <button class="btn btn-info btn-sm open-view-modal ml-2" data-url="{{ route('view.case_assignment', $case->id) }}">
                                <i class="fa fa-eye"></i>
                            </button>
                            @else
                            <span class="badge badge-success">Complete</span>
                            <button class="btn btn-danger btn-sm ml-2" disabled>
                                <i class="fa fa-window-close"></i>
                            </button>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            {{-- Laravel Pagination Removed for DataTables --}}
        </div>
    </div>
</div>

<!-- Reassign Modal -->
<div class="modal fade" id="reassignModal" tabindex="-1" aria-labelledby="reassignModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h5 class="modal-title" id="reassignModalLabel">Re-Assign Case</h5>
              <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
    <span aria-hidden="true">&times;</span>
</button>

            </div>
            <div class="modal-body" id="reassignModalBody">
                <div class="text-center py-5">
                    <div class="spinner-border text-primary" role="status"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- View Modal -->
<div class="modal fade" id="viewModal" tabindex="-1" role="dialog" aria-labelledby="viewModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title" id="viewModalLabel">Case Details</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="viewModalBody">
                <!-- AJAX-loaded content -->
            </div>
        </div>
    </div>
</div>


@push('styles')
<!-- AdminLTE DataTables CSS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap4.min.css" />
@endpush

@push('scripts')
<!-- jQuery (Required by DataTables) -->
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<!-- DataTables JS -->
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap4.min.js"></script>

<script>
$(document).ready(function() {
    // Initialize DataTable
    $('#casesTable').DataTable({
        "order": [[0, "asc"]],
        "paging": true,
        "lengthChange": true,
        "searching": true,
        "info": true,
        "autoWidth": false,
        // You can customize page length options
        "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
        // For responsive
        responsive: true,
    });

    // Reassign Modal open + AJAX load
    $('.open-reassign-modal').on('click', function() {
        const url = $(this).data('url');
        const modal = new bootstrap.Modal(document.getElementById('reassignModal'));
        const modalBody = $('#reassignModalBody');

        modalBody.html(`
            <div class="text-center py-5">
                <div class="spinner-border text-primary" role="status"></div>
            </div>
        `);
        modal.show();

        fetch(url)
            .then(response => {
                if (!response.ok) throw new Error('Failed to load modal content.');
                return response.text();
            })
            .then(html => {
                modalBody.html(html);
                bindReassignFormSubmit();
            })
            .catch(error => {
                modalBody.html('<div class="alert alert-danger">Unable to load content. Try again later.</div>');
                console.error(error);
            });
    });

    function bindReassignFormSubmit() {
        const form = $('#caseUpdate');
        if (!form.length) return;

        form.off('submit').on('submit', function(e) {
            e.preventDefault();

            const formData = new FormData(this);

            fetch('{{ route('re.assign.update') }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                    'Accept': 'application/json'
                },
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    $('#successMessage').text(data.success).show();

                    setTimeout(() => {
                        $('#reassignModal').modal('hide');
                        location.reload();
                    }, 1500);
                }
            })
            .catch(err => {
                console.error('Error submitting form:', err);
            });
        });
    }

    // View Modal open + AJAX load
    $('.open-view-modal').on('click', function() {
        const url = $(this).data('url');
        const modal = new bootstrap.Modal(document.getElementById('viewModal'));
        const modalBody = $('#viewModalBody');

        modalBody.html(`
            <div class="text-center py-5">
                <div class="spinner-border text-info" role="status"></div>
            </div>
        `);
        modal.show();

        fetch(url)
            .then(response => {
                if (!response.ok) throw new Error('Failed to load');
                return response.text();
            })
            .then(html => {
                modalBody.html(html);
            })
            .catch(error => {
                modalBody.html('<div class="alert alert-danger">Error loading details. Try again.</div>');
                console.error('Error loading view modal:', error);
            });
    });

    function autoSubmitFilter() {
        const fromDate = $('#from_date').val();
        const toDate = $('#to_date').val();

        if (fromDate && toDate) {
            const url = "{{ route('assigned.case') }}?from_date=" + fromDate + "&to_date=" + toDate;
            window.location.href = url;
        }
    }

    $('#from_date, #to_date').on('change', autoSubmitFilter);


});
</script>
@endpush
@endsection
