@extends('dashboard.layout.app')
@section('title', 'List Templates')

@section('content')
    <div class="container-fluid">
        <div class="text-right">
            <button class="btn btn-danger mr-2 mb-2" onclick="window.history.back()"><i class="fa fa-arrow-left"
                    aria-hidden="true"></i></button>
            <button class="btn btn-warning mr-2 mb-2" onclick="window.location.reload()"><i class="fa fa-spinner"
                    aria-hidden="true"></i></button>
                   
            <a href="{{ route('templates.create_templates') }}" class="btn btn-primary mr-2 mb-2">
                <i class="fa fa-plus" aria-hidden="true"></i>
            </a>
        </div>
       <div class="row">
  <div class="col-md-8 offset-md-2">
    <div class="card">
      <div class="card-header">
        <h4>List Templates</h4>
      </div>
      <div id="successMessage" class="alert alert-success" style="display: none;"></div>
      <div class="card-body">

    

        <table class="table">
        <thead>
            <tr>
                <th>Company</th>
                <th>Table Name</th>
                <th>Fields</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
           
          @foreach ($templates as $template)
          <tr>
          <td>{{ $template->company->name }}</td>
          <td>{{ $template->table_name }}</td>
          <td>

          @foreach (is_string($template->fields) ? json_decode($template->fields, true) : $template->fields as $field)
          <strong>{{ $field['name'] }}</strong> ({{ $field['type'] }})<br>
          @endforeach

          </td>
          <td>
          <a href="{{ route('templates.edit_templates', $template->id) }}" class="btn btn-sm btn-primary">Edit</a>
          </td>
          </tr>
          @endforeach
          

        </tbody>
        </table>
        @endsection


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


