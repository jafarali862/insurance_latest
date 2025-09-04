<div class="container-fluid">
  <div class="row justify-content-center">
    <div class="col-md-8">
      <div class="card card-primary">
        <div class="card-header" style="color:unset;background-color:unset;">
          <h3 class="card-title" style="font-weight:800;">Assign the executive</h3><br>
          <p>Customer Name: <strong>{{ $insuranceCustomer->name }}</strong></p>
          <p>Insurance Company: <strong>{{ $insuranceCompany->name }}</strong></p>
        </div>
        <div id="successMessage" class="alert alert-success mt-3" style="display:none;"></div>
        <div class="card-body">
          <form action="{{ route('store.case') }}" method="POST" id="caseForm">
            @csrf
            <input type="hidden" name="case_id" value="{{ $caseId->id }}">
            <input type="hidden" name="company_id" value="{{ $insuranceCompany->id }}">
            <input type="hidden" name="customer_id" value="{{ $insuranceCustomer->id }}">
            <input type="hidden" name="investigation_type" value="{{ $insuranceCustomer->insurance_type ?? $caseAssignment?->type }}">

            <div class="form-group">
              <label for="executive_1">Select Default Executive <span class="text-danger">*</span></label>
              <select name="Default_Executive" id="executive_1" onChange="exe1()" class="form-control select2" required>
                <option disabled selected>Select the executive</option>
                @foreach ($users as $user)
                  <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->place }})</option>
                @endforeach
              </select>
              <span class="text-danger" id="Default_Executive-error"></span>
            </div>

            <div id="sub-executive-group">
              <div class="form-row">
                <div class="form-group col-md-6">
                  <label for="executive_2">Select Executive (Driver)</label>
                  <select name="executive_driver" id="executive_2" class="form-control select2">
                    <option disabled selected>Select the executive</option>
                    @foreach ($users as $user)
                      <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->place }})</option>
                    @endforeach
                  </select>
                  <span class="text-danger" id="executive_driver-error"></span>
                </div>
                <div class="form-group col-md-6">
                  <label for="executive_3">Select Executive (Garage)</label>
                  <select name="executive_garage" id="executive_3" class="form-control select2">
                    <option disabled selected>Select the executive</option>
                    @foreach ($users as $user)
                      <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->place }})</option>
                    @endforeach
                  </select>
                </div>
              </div>

              <div class="form-row">
                <div class="form-group col-md-6">
                  <label for="executive_4">Select Executive (Spot)</label>
                  <select name="executive_spot" id="executive_4" class="form-control select2">
                    <option disabled selected>Select the executive</option>
                    @foreach ($users as $user)
                      <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->place }})</option>
                    @endforeach
                  </select>
                </div>
                <div class="form-group col-md-6">
                  <label for="executive_5">Select Executive (Meeting)</label>
                  <select name="executive_meeting" id="executive_5" class="form-control select2">
                    <option disabled selected>Select the executive</option>
                    @foreach ($users as $user)
                      <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->place }})</option>
                    @endforeach
                  </select>
                </div>
              </div>

              <div class="form-row">
                <div class="form-group col-md-6">
                  <label for="executive_6">Select Executive (Accident Person)</label>
                  <select name="executive_accident_person" id="executive_6" class="form-control select2">
                    <option disabled selected>Select the executive</option>
                    @foreach ($users as $user)
                      <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->place }})</option>
                    @endforeach
                  </select>
                </div>

                <div class="form-group col-md-6">
                  <label for="date">Select Investigation Date <span class="text-danger">*</span></label>
                  <input type="date" name="date" id="date" class="form-control" required>
                  @error('date')
                    <span class="text-danger">{{ $message }}</span>
                  @enderror
                </div>
              </div>
            </div>

            <div class="form-group">
              <label for="other">Other Details</label>
              <textarea name="other" id="other" class="form-control" rows="5"></textarea>
              @error('other')
                <span class="text-danger">{{ $message }}</span>
              @enderror
            </div>

            <button type="submit" class="btn btn-primary">Assign</button>

          </form>
        </div> <!-- /.card-body -->
      </div> <!-- /.card -->
    </div> <!-- /.col-md-8 -->
  </div> <!-- /.row -->
</div> <!-- /.container-fluid -->

<!-- Make sure to include Select2 CSS and JS for enhanced selects -->
@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" />
@endpush


    <!-- <script>
        document.addEventListener('DOMContentLoaded', function() {
            const mainExecutiveSelect = document.getElementById('executive_1');
            const subExecutiveGroup = document.getElementById('sub-executive-group');
            const subExecutiveSelects = [
                document.getElementById('executive_2'),
                document.getElementById('executive_3'),
                document.getElementById('executive_4'),
                document.getElementById('executive_5')
            ];
            const accidentPersonSelect = document.getElementById('executive_6');

            mainExecutiveSelect.addEventListener('change', function() {
                if (mainExecutiveSelect.value) {
                    subExecutiveGroup.style.display = 'block';
                    // Set all sub executive selects to the main executive's value
                    subExecutiveSelects.forEach(select => {
                        select.value = mainExecutiveSelect.value;
                    });
                    // Set the accident person select to the main executive's value
                    accidentPersonSelect.value = mainExecutiveSelect.value;
                } else {
                    subExecutiveGroup.style.display = 'none';
                    subExecutiveSelects.forEach(select => {
                        select.value =
                            ''; // Reset the sub executive selects if main is not selected
                    });
                    accidentPersonSelect.value = ''; // Reset accident person select
                }
            });

            const investigationTypeSelect = document.getElementById('investigation_type');
            const accident = document.getElementById('accident');

            investigationTypeSelect.addEventListener('change', function() {
                if (this.value === 'MAC') {
                    accident.style.display = 'block';
                } else {
                    accident.style.display = 'none';
                    accidentPersonSelect.value = ''; // Reset the select value
                }
            });
        });
    </script> -->

  
    <script>
        // $(document).ready(function() {
        //     $('#caseForm').on('submit', function(e) {
        //         e.preventDefault();

        //         $.ajax({
        //             url: '{{ route('store.case') }}',
        //             type: 'POST',
        //             data: $(this).serialize(),
        //             success: function(response) {
        //                 if (response.success) {
        //                     $('#successMessage').text(response.success).show();
        //                     $('#caseForm')[0].reset(); // Reset form fields
        //                     $('.text-danger').text(''); // Clear previous error messages
        //                 }
        //             },
        //             error: function(xhr) {
        //                 var errors = xhr.responseJSON.errors;
        //                 $('.text-danger').text(''); // Clear previous error messages
        //                 $.each(errors, function(key, value) {
        //                     $('#' + key + '-error').text(value);
        //                 });
        //             }
        //         });
        //     });
        // });
     
        $(document).ready(function () {
    $('#caseForm').on('submit', function (e) {
        e.preventDefault();

        $.ajax({
            url: '{{ route('store.case') }}',
            type: 'POST',
            data: $(this).serialize(),
            success: function (response) {
                if (response.success) {
                    // Show success message
                    $('#successMessage').text(response.success).fadeIn();

                    // Reset form
                    $('#caseForm')[0].reset();
                    $('.text-danger').text('');

                    // Hide success message after 8 seconds and reload page
                    setTimeout(function () {
                        $('#successMessage').fadeOut();
                        location.reload(); // Reload the entire page
                    }, 8000); // 8000 ms = 8 seconds
                }
            },
            error: function (xhr) {
                var errors = xhr.responseJSON.errors;
                $('.text-danger').text('');
                $.each(errors, function (key, value) {
                    $('#' + key + '-error').text(value);
                });
            }
        });
    });
});

     var accident = $('#accident');
     var insPerson = <?php echo json_encode($insuranceCustomer); ?>;
        if (insPerson.insurance_type === 'MAC') 
        {
            accident.show();
        } else {
            accident.hide();
        }
        $('#sub-executive-group').hide();
 
  function exe1(){
    var insPerson = <?php echo json_encode($insuranceCustomer); ?>;
    var exe1=$('#executive_1').val();
    var exe2=$('#executive_2');
    var exe3=$('#executive_3');
    var exe4=$('#executive_4');
    var exe5=$('#executive_5');
    var exe6=$('#executive_6');
    var accident=$('#accident');
    console.log(exe1);
    if(exe1!=''){
       $('#sub-executive-group').show();
       exe2.val(exe1);
       exe3.val(exe1);
       exe4.val(exe1);
       exe5.val(exe1);
       if (insPerson.insurance_type === 'MAC') 
        {
            accident.show();
            exe6.val(exe1);
        } else {
            accident.hide();
            exe6.val('');
        }

    }else{
        $('#sub-executive-group').hide(); 
    }
  }
      
</script>

