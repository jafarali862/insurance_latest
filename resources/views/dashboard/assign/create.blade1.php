@extends('dashboard.layout.app')
@section('title', 'Add New Insurance Customer')
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-8 offset-md-2">
                <div class="card mb-5">
                    <div class="card-header">
                        <h4>Assign the executive</h4><br>
                        <p>Customer Name: <strong>{{ $insuranceCustomer->name }}</strong></p>
                        <p>Insurance Company: <strong>{{ $insuranceCompany->name }}</strong></p>
                    </div>
                    <div id="successMessage" class="alert alert-success mt-3" style="display: none;"></div>
                    <div class="card-body">
                        <form action="{{ route('store.case') }}" method="post" id="caseForm">
                            @csrf
                            <input type="hidden" name="case_id" value="{{ $caseId->id }}">
                            <input type="hidden" name="company_id" value="{{ $insuranceCompany->id }}">
                            <input type="hidden" name="customer_id" value="{{ $insuranceCustomer->id }}">
                            <div class="form-group">
                                <div class="row">
                                    <div class="col col-6">
                                        <label for="executive_1">Select Default Executive</label> <span
                                            class="text-danger">*</span>
                                        <select name="Default_Executive" id="executive_1" class="form-select" required>
                                            <option disabled selected>Select the executive</option>
                                            @foreach ($users as $user)
                                                <option value="{{ $user->id }}">{{ $user->name }}
                                                    ({{ $user->place }})
                                                </option>
                                            @endforeach
                                        </select>
                                        <span class="text-danger" id="Default_Executive-error"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="container" id="sub-executive-group" style="display: none;">
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col col-6">
                                            <label for="executive_2">Select Executive (Driver)</label>
                                            <select name="executive_driver" id="executive_2" class="form-select">
                                                <option disabled selected>Select the executive</option>
                                                @foreach ($users as $user)
                                                    <option value="{{ $user->id }}">{{ $user->name }}
                                                        ({{ $user->place }})
                                                    </option>
                                                @endforeach
                                            </select>
                                            <span class="text-danger" id="executive_driver-error"></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col col-6">
                                            <label for="executive_3">Select Executive (Garage)</label>
                                            <select name="executive_garage" id="executive_3" class="form-select">
                                                <option disabled selected>Select the executive</option>
                                                @foreach ($users as $user)
                                                    <option value="{{ $user->id }}">{{ $user->name }}
                                                        ({{ $user->place }})
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col col-6">
                                            <label for="executive_4">Select Executive (Spot)</label>
                                            <select name="executive_spot" id="executive_4" class="form-select">
                                                <option disabled selected>Select the executive</option>
                                                @foreach ($users as $user)
                                                    <option value="{{ $user->id }}">{{ $user->name }}
                                                        ({{ $user->place }})
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col col-6">
                                            <label for="executive_5">Select Executive (Meeting)</label>
                                            <select name="executive_meeting" id="executive_5" class="form-select">
                                                <option disabled selected>Select the executive</option>
                                                @foreach ($users as $user)
                                                    <option value="{{ $user->id }}">{{ $user->name }}
                                                        ({{ $user->place }})
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <!-- New Dropdown for Accident Person -->
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col col-6" style="display: none;" id="accident">
                                            <label for="executive_6">Select Executive (Accident Person)</label>
                                            <select name="executive_accident_person" id="executive_6" class="form-select">
                                                <option disabled selected>Select the executive</option>
                                                @foreach ($users as $user)
                                                    <option value="{{ $user->id }}">{{ $user->name }}
                                                        ({{ $user->place }})
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="row">
                                    <div class="col col-6">
                                        <label for="date">Select Investigation Date</label> <span
                                            class="text-danger">*</span>
                                        <input type="date" name="date" id="date" class="form-control" required>
                                        @error('date')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="col col-6">
                                        <label for="investigation_type">Investigation Type</label> <span
                                            class="text-danger">*</span>
                                        <select name="investigation_type" id="investigation_type" class="form-select"
                                            required>
                                            <option selected disabled>Select the investigation type</option>
                                            <option value="OD">OD</option>
                                            <option value="MAC">MAC</option>
                                        </select>
                                        <span class="text-danger" id="investigation_type-error"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col">
                                        <label for="other">Other Details</label>
                                        <textarea name="other" id="other" class="form-control" cols="30" rows="5"></textarea>
                                        @error('other')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col col-6">
                                        <button type="submit" class="btn btn-primary">Assign</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
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
    </script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#caseForm').on('submit', function(e) {
                e.preventDefault();

                $.ajax({
                    url: '{{ route('store.case') }}',
                    type: 'POST',
                    data: $(this).serialize(),
                    success: function(response) {
                        if (response.success) {
                            $('#successMessage').text(response.success).show();
                            $('#caseForm')[0].reset(); // Reset form fields
                            $('.text-danger').text(''); // Clear previous error messages
                        }
                    },
                    error: function(xhr) {
                        var errors = xhr.responseJSON.errors;
                        $('.text-danger').text(''); // Clear previous error messages
                        $.each(errors, function(key, value) {
                            $('#' + key + '-error').text(value);
                        });
                    }
                });
            });
        });
    </script>
@endsection
