@extends('dashboard.layout.app')
@section('title', 'Questionnaire List')

@push('styles')
    <!-- DataTables CSS -->
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
        <a href="{{ route('company.create_question') }}" class="btn btn-primary">
            <i class="fas fa-clipboard"></i>
        </a>

        
    </div>


    <div class="card card-primary card-outline shadow-sm">
        <div class="card-header">
            <h4 class="card-title mt-0" style="font-size: 32px;">Questionnaire List</h4>
        </div>

        <div class="card-body table-responsive p-0">
           
          <table id="questionsTable" class="table table-bordered table-hover text-center mb-0" style="width: 100%">
    <thead class="thead-dark">
        <tr>
            <th>ID</th>
            <th>Question</th>
            <th>Input Type</th>
            <th>Data Category</th>
            <th>Templates</th> {{-- ✅ new column --}}
            <th style="width: 120px;">Actions</th>
        </tr>
    </thead>
    <tbody>
        @php $i = 1; @endphp
        @foreach($questions as $q)
            <tr>
                <td>{{ $i++ }}</td>
                <td>{{ $q->question }}</td>
                <td>{{ $q->input_type }}</td>
                <td>{{ $q->data_category }}</td>
                <td>
                    @if($q->templates->isNotEmpty())
                        @foreach($q->templates as $template)
                            <span class="badge bg-primary">{{ $template->template_id }}</span>
                        @endforeach
                    @else
                        <span class="text-muted">Not Assigned</span>
                    @endif
                </td>
                <td>
                    <a href="{{ route('questions.edit_question', $q->id) }}" class="btn btn-sm btn-info" title="Edit">
                        <i class="fas fa-edit"></i>
                    </a>
                    <form action="{{ route('questions.destroy_question', $q->id) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" onclick="return confirm('Are you sure?')" class="btn btn-sm btn-danger" title="Delete">
                            <i class="fas fa-trash-alt"></i>
                        </button>
                    </form>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>

            

            @if($questions->isEmpty())
                <div class="p-3 text-center text-muted">
                    No questions found.
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
@endpush
