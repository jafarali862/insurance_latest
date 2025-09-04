@extends('dashboard.layout.app')
@section('title', 'Report Request')

@section('content')

<div class="container-fluid">
<div class="d-flex justify-content-end mb-3">
<a href="javascript:history.back()" class="btn btn-danger mr-2">
<i class="fas fa-arrow-left"></i>
</a>
<a href="{{ url()->current() }}" class="btn btn-warning mr-2">
<i class="fas fa-sync-alt"></i>
</a>

</div>

<div class="card card-primary card-outline shadow-sm">
<div class="card-header">
<h3 class="card-title" style="font-size: 32px;">Final Report</h3>
</div>


    <div class="card">
        <div class="card-body">


             <form id="filterForm" class="form-inline mb-3">
            <div class="input-group mr-2">
                <label for="from_date" class="mr-2">From Date</label>
                <input type="date" name="from_date" id="from_date" value="{{ $from ?? '' }}" class="form-control">
            </div>
            <div class="input-group mr-2">
                <label for="to_date" class="mr-2">To Date</label>
                <input type="date" name="to_date" id="to_date" value="{{ $to ?? '' }}" class="form-control">
            </div>
        </form>


            <div class="table-responsive">
                <table id="reportTable" class="table table-bordered table-striped text-center">
                    <thead class="thead-dark">
                        <tr>
                            <th>ID</th>
                            <th>Customer Name</th>
                            <th>Company Name</th>
                            <th>Investigation Date</th>
                            <th>Type</th>
                            <th>Download PDF</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($reports as $index => $report)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $report->customer_name }}</td>
                            <td>{{ $report->company_name }}</td>
                            <td>{{ \Carbon\Carbon::parse($report->date)->format('d-m-Y') }}</td>
                            <td>{{ $report->type }}</td>
                            <td>
                                <a href="javascript:void(0);" onclick="downloadPdf({{ $report->report_id }})"
                                    class="btn btn-success btn-sm">
                                    <i class="fa fa-download"></i> Download Report
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
<!-- DataTables -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap4.min.css">
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap4.min.js"></script>

<script>
    $(document).ready(function () {
        $('#reportTable').DataTable({
            responsive: true,
            autoWidth: false,
            pageLength: 10,
            ordering: true,
            language: {
                search: "Search:",
                lengthMenu: "Show _MENU_ entries",
                zeroRecords: "No matching reports found",
                info: "Showing _START_ to _END_ of _TOTAL_ reports",
                infoEmpty: "No reports available",
                infoFiltered: "(filtered from _MAX_ total entries)"
            }
        });
    });

    function downloadPdf(id) {
        if (id != null) {
            $.ajax({
                url: '{{ route("final.report.download") }}',
                type: 'POST',
                data: {
                    report_id: id,
                    _token: '{{ csrf_token() }}',
                },
                xhrFields: {
                    responseType: 'blob'
                },
                success: function (response) {
                    if (response instanceof Blob) {
                        var link = document.createElement('a');
                        link.href = URL.createObjectURL(response);
                        link.download = 'report_' + id + '.pdf';
                        document.body.appendChild(link);
                        link.click();
                        document.body.removeChild(link);
                    } else {
                        alert("Report could not be downloaded.");
                    }
                },
                error: function (xhr, status, error) {
                    alert('Error: ' + error + '\n' + xhr.responseText);
                }
            });
        }
    }


      function autoSubmitFilter() {
        const from = document.getElementById('from_date').value;
        const to = document.getElementById('to_date').value;

        if (from && to) {
            const queryParams = new URLSearchParams(window.location.search);
            queryParams.set('from_date', from);
            queryParams.set('to_date', to);
            window.location.href = window.location.pathname + '?' + queryParams.toString();
        }
    }

    document.getElementById('from_date').addEventListener('change', autoSubmitFilter);
    document.getElementById('to_date').addEventListener('change', autoSubmitFilter);

    
</script>
@endsection
