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


<h4>Edit Template for {{ $template->company->name }}</h4>

<form action="{{ route('templates.update_templates', $template->id) }}" method="POST">
    @csrf

    <div id="fields-container">
        @foreach ($fields as $index => $field)
        <div class="field-row d-flex gap-2 align-items-center mb-2">
            <input type="text" name="fields[{{ $index }}][name]" value="{{ $field['name'] }}" class="form-control" placeholder="Field name" required>
            <select name="fields[{{ $index }}][type]" class="form-control" required>
                <option value="string" {{ $field['type'] == 'string' ? 'selected' : '' }}>String</option>
                <option value="integer" {{ $field['type'] == 'integer' ? 'selected' : '' }}>Integer</option>
                <option value="boolean" {{ $field['type'] == 'boolean' ? 'selected' : '' }}>Boolean</option>
                <option value="text" {{ $field['type'] == 'text' ? 'selected' : '' }}>Text</option>
                <option value="json" {{ $field['type'] == 'json' ? 'selected' : '' }}>JSON</option>
            </select>
            <button type="button" class="btn btn-danger btn-sm" onclick="removeField(this)" title="Remove field">&times;</button>
        </div>
        @endforeach
    </div>

    <button type="button" class="btn btn-secondary mb-3" onclick="addField()">Add Another Field</button>
    <br/>
    <button type="submit" class="btn btn-primary mb-3">Update Template</button>
</form>

<script>
function addField() {
    const container = document.getElementById('fields-container');
    const index = container.children.length;

    const div = document.createElement('div');
    div.className = 'field-row d-flex gap-2 align-items-center mb-2';
    div.innerHTML = `
        <input type="text" name="fields[${index}][name]" class="form-control" placeholder="Field name" required>
        <select name="fields[${index}][type]" class="form-control" required>
            <option value="string">String</option>
            <option value="integer">Integer</option>
            <option value="boolean">Boolean</option>
            <option value="text">Text</option>
            <option value="json">JSON</option>
        </select>
        <button type="button" class="btn btn-danger btn-sm" onclick="removeField(this)" title="Remove field">&times;</button>
    `;

    container.appendChild(div);
}

function removeField(button) {
    button.parentElement.remove();
}
</script>
@endsection

    </div>
    </div>
    </div>
    </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

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

