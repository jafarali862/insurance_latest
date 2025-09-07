@extends('dashboard.layout.app')
@section('title', 'List Templates')

@section('content')
<div class="container-fluid">
    <div class="text-right mb-3">
        <button class="btn btn-danger mr-2" onclick="window.history.back()">
            <i class="fa fa-arrow-left" aria-hidden="true"></i>
        </button>
        <button class="btn btn-warning mr-2" onclick="window.location.reload()">
            <i class="fa fa-spinner" aria-hidden="true"></i>
        </button>
        <a href="{{ route('templates.create_templates') }}" class="btn btn-primary">
            <i class="fa fa-plus" aria-hidden="true"></i>
        </a>
    </div>

    <div class="row">
        <div class="col-md-10 offset-md-1">
            <div class="card">
                <div class="card-header">
                    <h4>Templates List</h4>
                </div>
                <div id="successMessage" class="alert alert-success" style="display: none;"></div>
                <div class="card-body">

                    <table class="table table-bordered table-striped" id="questionsTable">
                        <thead class="table-dark">
                            <tr>
                                <th>#</th>
                                <th>Template ID</th>
                                <th>Questions</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($templates as $template)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $template->template_id }}</td>
                                <td>
                                    <ul>
                                        @foreach($template->questions as $question)
                                        <li>
                                            {{ $question->question }}
                                            <small class="text-muted">({{ $question->data_category }})</small>
                                        </li>
                                        @endforeach
                                    </ul>
                                </td>
                                <td>
                                    <button class="btn btn-sm btn-primary preview-btn" data-id="{{ $template->id }}">
                                        <i class="fas fa-eye"></i> Preview
                                    </button>

                                    <a href="{{ route('templates.edit_templates', $template->id) }}" class="btn btn-sm btn-info">
                                        <i class="fas fa-edit"></i> Edit
                                    </a>

                                    <form action="{{ route('templates.destroy_templates', $template->id) }}" 
                                          method="POST" 
                                          style="display:inline;" 
                                          onsubmit="return confirm('Are you sure?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger">
                                            <i class="fas fa-trash-alt"></i> Delete
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted">No templates found</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection


<!-- Preview Modal -->
<div class="modal fade" id="previewModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-dark text-white">
                <h5 class="modal-title">Template Preview</h5>
                  <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="previewContent">
                <!-- Preview content loaded here -->
            </div>
        </div>
    </div>
</div>

{{-- JS & CSS --}}
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet">

<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>

<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

<script>
$(document).on("click", ".preview-btn", function () {
    let templateId = $(this).data("id");
    let url = "{{ route('templates.preview', ':id') }}".replace(":id", templateId);

    $("#previewContent").html('<div class="text-center text-muted">Loading...</div>');

    let modalEl = document.getElementById('previewModal');
    let modal = new bootstrap.Modal(modalEl);
    modal.show();

    $.get(url, function (data) {
        $("#previewContent").html(data);
    }).fail(function () {
        $("#previewContent").html('<div class="text-danger">Error loading preview</div>');
    });
});

$(document).ready(function() {
    $('#questionsTable').DataTable({
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
            searchPlaceholder: "Search questions..."
        },
        columnDefs: [
            { orderable: false, targets: 3 } // Disable ordering on Actions column
        ],
    });
});
</script>
