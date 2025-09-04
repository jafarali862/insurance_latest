@extends('dashboard.layout.app')
@section('title', 'Case List')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-end mb-3">
        <button class="btn btn-danger mr-2" onclick="window.history.back()" title="Back">
            <i class="fas fa-arrow-left"></i>
        </button>
        <button class="btn btn-warning mr-2" onclick="window.location.reload()" title="Reload">
            <i class="fas fa-sync-alt"></i>
        </button>
        <a href="{{ route('insurance.create') }}" class="btn btn-primary" title="Add New Case">
            <i class="fas fa-plus"></i>
        </a>
    </div>

    <div class="card card-primary card-outline shadow-sm">
        <div class="card-header">
            <h3 class="card-title" style="font-size: 32px;">Case List</h3>
        </div>

        <div class="card-body table-responsive">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif


          <div class="mb-3">
    <label for="statusFilter"><strong>Filter by Status:</strong></label>
    <select id="statusFilter" class="form-control" style="width:200px; display:inline-block;">
        <option value="">All</option>
        <option value="Pending">Pending</option>
        <option value="Complete">Complete</option>
        <option value="Assigned">Assigned</option>
        <!-- <option value="Unknown">Unknown</option> -->
    </select>
</div>



            @php use Carbon\Carbon; @endphp
            <table id="casesTable" class="table table-bordered table-hover text-center">
                <thead class="thead-dark">
                    <tr>
                        <th>ID</th>
                        <th>Name / Phone</th>
                        <th>Company</th>
                        <th>Case Type</th>
                        <th>Crime Number</th>
                        <th>Police Station</th>
                        <th>Assign Date</th>
                        <th>Status & Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @php $i = 1; @endphp
                    @foreach ($customers as $customer)
                        <tr>
                            <td>{{ $i++ }}</td>
                            <td>
                                {{ $customer->name }}<br>
                                <small class="text-muted">{{ $customer->phone }}</small><br/>

                        <!-- @if ($customer->case_status == 1)
                        <span class="badge badge-danger">Pending</span>
                        @elseif ($customer->case_status == 0)
                        <span class="badge badge-success">Complete</span>
                        @elseif ($customer->case_status == 2)
                        <span class="badge badge-warning">Assigned</span>
                        @else
                        <span class="badge badge-secondary">Unknown</span>
                        @endif -->



                        @php
                        $statusText = 'Unknown';
                        if ($customer->case_status == 1) $statusText = 'Pending';
                        elseif ($customer->case_status == 0) $statusText = 'Complete';
                        elseif ($customer->case_status == 2) $statusText = 'Assigned';
                        @endphp

                        <span class="badge 
                        @if ($statusText == 'Pending') badge-danger 
                        @elseif ($statusText == 'Complete') badge-success 
                        @elseif ($statusText == 'Assigned') badge-warning 
                        @else badge-secondary @endif
                        ">
                        {{ $statusText }}
                        </span>

                        <!-- Hidden plain-text for filtering -->
                        <span class="d-none status-text">{{ $statusText }}</span>

                            </td>
                            <td>{{ $customer->custname }}</td>
                            <td>{{ $customer->insurance_type }}</td>
                            <td>{{ $customer->crime_number }}</td>
                            <td>{{ $customer->police_station }}</td>
                            <td>{{ Carbon::parse($customer->created_at)->format('d-m-Y') }}</td>
                            <td>
                                <button class="btn btn-primary btn-sm open-assign-modal mb-1" data-url="{{ route('assign.case', $customer->id) }}" title="Assign Executive">
                                    <i class="fas fa-user-plus"></i>
                                </button>

                                <a href="{{ route('case.edit', $customer->id) }}" class="btn btn-info btn-sm ml-1" title="Edit Case">
                                    <i class="fas fa-edit"></i>
                                </a>

                                <button class="btn btn-secondary btn-sm open-view-modal ml-1" data-url="{{ route('view.case', $customer->id) }}" title="View Details">
                                    <i class="fas fa-eye"></i>
                                </button>

                                @if ($customer->intimation_report)
                                    <a href="{{ asset('storage/' . $customer->intimation_report) }}" class="btn btn-success btn-sm ml-1" download title="Download Intimation Report">
                                        <i class="fas fa-download"></i>
                                    </a>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            {{-- No manual pagination here since DataTables handles it --}}
        </div>
    </div>
</div>

{{-- Assign Modal --}}
<div class="modal fade" id="assignModal" tabindex="-1" role="dialog" aria-labelledby="assignModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h5 class="modal-title" id="assignModalLabel">Assign Executive</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="assignModalBody">
                <div class="text-center py-5">
                    <div class="spinner-border text-primary" role="status"></div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- View Modal --}}
<div class="modal fade" id="viewModal" tabindex="-1" role="dialog" aria-labelledby="viewModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title" id="viewModalLabel">Case Details</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="viewModalBody">
                <!-- AJAX loaded content -->
            </div>
        </div>
    </div>
</div>

@push('styles')
<!-- AdminLTE DataTables CSS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap4.min.css" />
@endpush
@push('scripts')

<script>
$(document).ready(function() {

//    $('#casesTable').DataTable({
//         paging: true,
//         lengthChange: true,
//         searching: true,
//         ordering: true,
//         info: true,
//         autoWidth: false,
//         pageLength: 10,
//         lengthMenu: [5, 10, 25, 50],
//         columnDefs: [
//             { orderable: false, targets: 7 }
//         ]
//     });

    var table = $('#casesTable').DataTable({
        paging: true,
        lengthChange: true,
        searching: true,
        ordering: true,
        info: true,
        autoWidth: false,
        pageLength: 10,
        lengthMenu: [5, 10, 25, 50],
        columnDefs: [
            { orderable: false, targets: 7 }
        ]
    });

    // Status filter
    $('#statusFilter').on('change', function () {
        var selected = $(this).val();

        if (selected) {
            table.column(1).search(selected, true, false).draw();  
            // 👆 true,false → exact word match, not regex partial
        } else {
            table.column(1).search('').draw(); // reset filter
        }
    });

    $('#statusFilter').on('change', function () {
        var selectedStatus = $(this).val().toLowerCase();

        table.rows().every(function () {
            var statusCell = $(this.node()).find('.status-text').text().toLowerCase();
            if (selectedStatus === "" || statusCell === selectedStatus) {
                $(this.node()).show();
            } else {
                $(this.node()).hide();
            }
        });
    });

    

    // Delegate click for assign modal buttons
    $('#casesTable').on('click', '.open-assign-modal', function() {
        var url = $(this).data('url');
        var $modal = $('#assignModal');
        var $modalBody = $('#assignModalBody');

        $modalBody.html(`
            <div class="text-center py-5">
                <div class="spinner-border text-primary" role="status"></div>
            </div>
        `);

        $modal.modal('show');

        fetch(url)
            .then(response => {
                if (!response.ok) throw new Error('Network response was not OK');
                return response.text();
            })
            .then(html => {
                $modalBody.html(html);
                // Execute scripts in loaded content
                $modalBody.find('script').each(function() {
                    $.globalEval(this.text || this.textContent || this.innerHTML || '');
                });
            })
            .catch(() => {
                $modalBody.html('<div class="alert alert-danger">Failed to load form. Please try again later.</div>');
            });
    });

    // Delegate click for view modal buttons
    $('#casesTable').on('click', '.open-view-modal', function() {
        var url = $(this).data('url');
        var $modal = $('#viewModal');
        var $modalBody = $('#viewModalBody');

        $modalBody.html(`
            <div class="text-center py-5">
                <div class="spinner-border text-info" role="status"></div>
            </div>
        `);

        $modal.modal('show');

        fetch(url)
            .then(response => {
                if (!response.ok) throw new Error('Failed to load');
                return response.text();
            })
            .then(html => {
                $modalBody.html(html);
            })
            .catch(() => {
                $modalBody.html('<div class="alert alert-danger">Error loading details. Try again.</div>');
            });
    });
});

// Your exe1 function (optional, depends on your use case)
function exe1() {
    const insPerson = window.insuranceCustomer || {};
    const exe1Val = $('#executive_1').val();
    const exe2 = $('#executive_2');
    const exe3 = $('#executive_3');
    const exe4 = $('#executive_4');
    const exe5 = $('#executive_5');
    const exe6 = $('#executive_6');
    const accident = $('#accident');

    if (exe1Val !== '') {
        $('#sub-executive-group').show();
        exe2.val(exe1Val);
        exe3.val(exe1Val);
        exe4.val(exe1Val);
        exe5.val(exe1Val);

        if (insPerson.insurance_type === 'MACT') {
            accident.show();
            exe6.val(exe1Val);
        } else {
            accident.hide();
            exe6.val('');
        }
    } else {
        $('#sub-executive-group').hide();
    }
}
</script>
@endpush
@endsection

