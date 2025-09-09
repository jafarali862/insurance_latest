@php
    use Illuminate\Support\Str;
    $grouped = $template->questions->groupBy('data_category');
@endphp

<h5>Template ID: {{ $template->template_id }}</h5>
<hr>

@foreach($grouped as $category => $questions)
    <h6 class="mt-3">
        {{ Str::title(str_replace('_', ' ', $category)) }}
    </h6>
    <ul class="list-group mb-3">
        @foreach($questions as $question)
            <li class="list-group-item">
                {{ $question->question }}
            </li>
        @endforeach
    </ul>
@endforeach

@if($grouped->isEmpty())
    <div class="text-muted">No questions found</div>
@endif
