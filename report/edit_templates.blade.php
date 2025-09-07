@extends('dashboard.layout.app')
@section('title', 'Edit Templates')

@section('content')

    <div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800 text-center">Edit Templates</h1>

    <div class="row justify-content-center">
    <div class="col-lg-8 col-md-10">
    <div class="card border-0 shadow-lg rounded-lg">
    <div class="card-header bg-primary text-white border-0 rounded-top">
    <h6 class="m-0 font-weight-bold">Edit Templates</h6>
    </div>
    <div class="card-body">
    <div id="successMessage" class="alert alert-success" style="display: none;"></div>


<h4>Edit Template</h4>

 <form method="POST" action="{{ route('templates.update_templates', $template->id) }}">
        @csrf
      

        <ul class="nav nav-tabs" id="questionTabs" role="tablist">
            @foreach($questions as $category => $group)
                <li class="nav-item">
                    <a class="nav-link {{ $loop->first ? 'active' : '' }}" 
                       id="{{ $category }}-tab" 
                       data-bs-toggle="tab" 
                       href="#{{ $category }}" 
                       role="tab">
                       {{ ucfirst(str_replace('_', ' ', $category)) }}
                    </a>
                </li>
            @endforeach
        </ul>

        <div class="tab-content mt-3">
            @foreach($questions as $category => $group)
                <div class="tab-pane fade {{ $loop->first ? 'show active' : '' }}" 
                     id="{{ $category }}" 
                     role="tabpanel">
                     
                    @foreach($group as $question)
                        <div class="form-check">
                            <input class="form-check-input" 
                                   type="checkbox" 
                                   name="questions[]" 
                                   value="{{ $question->id }}" 
                                   id="q{{ $question->id }}"
                                   {{ $template->questions->contains($question->id) ? 'checked' : '' }}>
                            <label class="form-check-label" for="q{{ $question->id }}">
                                {{ $question->question }}
                            </label>
                        </div>
                    @endforeach

                </div>
            @endforeach
        </div>

        <br/>
        <button type="submit" class="btn btn-primary mb-3">Update Template</button>
        <a href="{{ route('templates.list_templates') }}" class="btn btn-secondary mb-3">Cancel</a>
    </form>

    <!-- <form action="{{ route('templates.destroy_templates', $template->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this template?')" class="mt-2">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-danger">Delete Template</button>
    </form>
 -->


  @endsection

    </div>
    </div>
    </div>
    </div>
    </div>

 <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <script>

    $(document).ready(function() {
    $('#updateCompany').on('submit', function(e) {
    e.preventDefault();

    const form = $(this);
    const actionUrl = form.attr('action'); // Uses route with ID included

    $.ajax({
    url: actionUrl,
    type: 'POST', // or 'PUT' if you're not spoofing it via hidden method
    data: form.serialize(),
    success: function(response) {
    if (response.success) {
    $('#successMessage').text(response.success).show();
    $('.text-danger').text('');
    }
    }
    });
    });
    });

    </script>

