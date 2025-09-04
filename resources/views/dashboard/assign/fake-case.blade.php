@extends('dashboard.layout.app')
@section('title', 'Fake Case List')

@section('content')
    <div class="container-fluid">
        <div class="text-right">
            <button class="btn btn-danger mr-2 mb-2" onclick="window.history.back()"><i class="fa fa-arrow-left"
                    aria-hidden="true"></i></button>
            <button class="btn btn-warning mr-2 mb-2" onclick="window.location.reload()"><i class="fa fa-spinner"
                    aria-hidden="true"></i></button>
            <!-- <a href="{{ route('insurance.create') }}" class="btn btn-primary mr-2 mb-2">
                <i class="fa fa-plus" aria-hidden="true"></i>
            </a> -->
        </div>
        <div class="card">
            <div class="card-header">
                <h4>Fake Case List</h4>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped text-center" id="casesTable">
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
                                <!-- <th>Re Assign</th> -->
                            </tr>
                        </thead>
                     <?php $i = $cases->firstItem(); ?>


                        
                        <tbody>
                            @foreach ($cases as $case)
                                <tr>
                                    <td><?php echo $i++; ?></td>
                                    <td>{{ $case->customer_name }}<br/>{{ $case->phone }}</td>
                                    <td>{{ $case->company_name }}</td>
                                    <td>{{ $case->type }}</td>

                                    <td>{{ $case->crime_number }}</td>
                                    <td>{{ $case->police_station }}</td>
                                    <td>{{ \Carbon\Carbon::createFromFormat('Y-m-d', $case->date)->format('d-F-Y') }}</td>
                                   

                        <td>
                        @if ($case->case_status == 1)
                        <span class="badge bg-danger">Pending</span>

                        <button type="button"
                        class="btn btn-primary btn-sm ms-2 open-reassign-modal"
                        data-url="{{ route('re.assign.case', $case->id) }}">
                        <i class="fa fa-retweet"></i>
                        </button>


                        <button class="btn btn-info open-view-modal"
                        data-url="{{ route('view.case_assignment', $case->id) }}">
                        <i class="fa fa-eye" aria-hidden="true"></i> 
                        </button>


                        @else
                        <span class="badge bg-success">Complete</span>

                        <button class="btn btn-danger btn-sm ms-2" disabled>
                        <i class="fa fa-window-close" aria-hidden="true"></i>
                        </button>
                        @endif
                        </td>



                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="d-flex justify-content-right">
                        {{ $cases->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!-- Reassign Modal -->
<div class="modal fade" id="reassignModal" tabindex="-1" aria-labelledby="reassignModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="reassignModalLabel">Re-Assign Case</h5>
      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
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
<div class="modal fade" id="viewModal" tabindex="-1" aria-labelledby="viewModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="viewModalLabel">Case Details</h5>
  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
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

        $('#casesTable').DataTable({
      "paging": true,
      "lengthChange": true,
      "searching": true,
      "ordering": true,
      "info": true,
      "autoWidth": false,
      "order": [[0, "asc"]], // Default order by ID ascending
    });
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.open-reassign-modal').forEach(button => {
        button.addEventListener('click', function () {
            const url = this.getAttribute('data-url');
            const modal = new bootstrap.Modal(document.getElementById('reassignModal'));
            const modalBody = document.getElementById('reassignModalBody');

            modalBody.innerHTML = `
                <div class="text-center py-5">
                    <div class="spinner-border text-primary" role="status"></div>
                </div>
            `;

            modal.show();

            fetch(url)
                .then(response => {
                    if (!response.ok) throw new Error('Failed to load modal content.');
                    return response.text();
                })
                .then(html => {
                    modalBody.innerHTML = html;

                    // Rebind submit handler AFTER content is injected
                    bindReassignFormSubmit();
                })
                .catch(error => {
                    modalBody.innerHTML = `<div class="alert alert-danger">Unable to load content. Try again later.</div>`;
                    console.error(error);
                });
        });
    });

    function bindReassignFormSubmit() {
        const form = document.getElementById('caseUpdate');
        if (!form) return;

        form.addEventListener('submit', function (e) {
            e.preventDefault();

            const formData = new FormData(this);

            fetch('{{ route('re.assign.update') }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                },
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const successBox = document.getElementById('successMessage');
                    successBox.textContent = data.success;
                    successBox.style.display = 'block';

                    setTimeout(() => {
                        const modalEl = document.getElementById('reassignModal');
                        const modalInstance = bootstrap.Modal.getInstance(modalEl);
                        modalInstance.hide();
                        location.reload(); // Optional: Refresh the table
                    }, 1500);
                }
            })
            .catch(err => {
                console.error('Error submitting form:', err);
            });
        });
    }
});


 document.addEventListener('DOMContentLoaded', function () 
        {
        document.querySelectorAll('.open-view-modal').forEach(button => {
        button.addEventListener('click', function () {
        const url = this.getAttribute('data-url');
        const modal = new bootstrap.Modal(document.getElementById('viewModal'));
        const modalBody = document.getElementById('viewModalBody');

        modalBody.innerHTML = `
        <div class="text-center py-5">
        <div class="spinner-border text-info" role="status"></div>
        </div>
        `;

        modal.show();

        fetch(url)
        .then(response => {
        if (!response.ok) throw new Error('Failed to load');
        return response.text();
        })
        .then(html => {
        modalBody.innerHTML = html;
        })
        .catch(error => {
        modalBody.innerHTML = `<div class="alert alert-danger">Error loading details. Try again.</div>`;
        console.error('Error loading view modal:', error);
        });
        });
        });
        });
</script>

@endpush
@endsection
