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

            <div class="form-group mb-3">
            <label for="company">Insurance Company:</label>
            <select name="company_id" class="form-control" required>
            @foreach($companies as $company)
            <option value="{{ $company->id }}">{{ $company->name }}</option>
            @endforeach
            </select>
            </div>

            <!-- <div class="form-group mb-3">
            <label for="category">Category:</label>
            <select name="category" class="form-control" required>
            <option value="driver">Driver</option>
            <option value="garage">Garage</option>
            <option value="spot">Spot</option>
            <option value="accident">Accident</option>
            </select>
            </div> -->

            <hr>

            <h4>Fields (Dynamic)</h4>
            <div id="fields-container" class="mb-3">
            <div class="field-row d-flex gap-2 align-items-center mb-2">
            <input type="text" name="fields[0][name]" class="form-control" placeholder="Field name" required>
            <select name="fields[0][type]" class="form-control" required>
            <option value="string">String</option>
            <option value="integer">Integer</option>
            <option value="boolean">Boolean</option>
            <option value="text">Text</option>
            <option value="json">JSON</option>
            </select>
            <button type="button" class="btn btn-danger btn-sm" onclick="removeField(this)" title="Remove field">&times;</button>
            </div>
            </div>

            <button type="button" class="btn btn-secondary mb-3" onclick="addField()">Add Another Field</button>
            <br/>
            <button type="submit" class="btn btn-primary mb-3">Create Template</button>
            </form>

      </div>
    </div>
  </div>
</div>


<script>
  let fieldIndex = 1;

  function addField() {
    const container = document.getElementById('fields-container');
    const row = document.createElement('div');
    row.classList.add('field-row', 'd-flex', 'gap-2', 'align-items-center', 'mb-2');
    row.innerHTML = `
      <input type="text" name="fields[${fieldIndex}][name]" class="form-control" placeholder="Field name" required>
      <select name="fields[${fieldIndex}][type]" class="form-control" required>
        <option value="string">String</option>
        <option value="integer">Integer</option>
        <option value="boolean">Boolean</option>
        <option value="text">Text</option>
        <option value="json">JSON</option>
      </select>
      <button type="button" class="btn btn-danger btn-sm" onclick="removeField(this)" title="Remove field">&times;</button>
    `;
    container.appendChild(row);
    fieldIndex++;
  }

  function removeField(button) {
    // Remove the parent .field-row div of the clicked remove button
    const row = button.closest('.field-row');
    if (row) {
      row.remove();
    }
  }
</script>


    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

   




    <script>
    document.getElementById('input_type').addEventListener('change', function () {
        const fileTypeContainer = document.getElementById('fileTypeContainer');
        if (this.value === 'file') {
            fileTypeContainer.style.display = 'block';
            document.getElementById('file_type').setAttribute('required', 'required');
        } else {
            fileTypeContainer.style.display = 'none';
            document.getElementById('file_type').removeAttribute('required');
        }
    });
</script>

@endsection
