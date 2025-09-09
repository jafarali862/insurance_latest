@extends('dashboard.layout.app')
@section('title', 'Create Template')

@section('content')
    <div class="container-fluid">
        <div class="text-right">
            <button class="btn btn-danger mr-2 mb-2" onclick="window.history.back()"><i class="fa fa-arrow-left"
                    aria-hidden="true"></i></button>
            <button class="btn btn-warning mr-2 mb-2" onclick="window.location.reload()"><i class="fa fa-spinner"
                    aria-hidden="true"></i></button>
            <a href="{{ route('templates.list_templates') }}" class="btn btn-primary mr-2 mb-2">
                <i class="fa fa-list" aria-hidden="true"></i>
            </a>
        </div>
       <div class="row">
  <div class="col-md-8 offset-md-2">
    <div class="card">
      <div class="card-header">
        <h4>Add Templates</h4>
      </div>
      <div id="successMessage" class="alert alert-success" style="display: none;"></div>
      <div class="card-body">

        <!-- Template creation form -->
          <form method="POST" action="{{ route('templates.store_templates') }}">
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
    id="q{{ $question->id }}">
    <label class="form-check-label" for="q{{ $question->id }}">
    {{ $question->question }}
    </label>
    </div>
    @endforeach

    </div>
    @endforeach
    </div>

    <br/>
    <button type="submit" class="btn btn-primary mb-3">Create Template</button>
    </form>


    </div>
    </div>
    </div>
    </div>



<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

@endsection
