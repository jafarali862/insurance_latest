<!-- @extends('dashboard.layout.app')
@section('title', 'Re Assign Case')

@section('content') -->
    <div class="container-fluid">
        <h1 class="h3 mb-4 text-gray-800 text-center">Re Assign Case</h1>

        <div class="row justify-content-center">
            <div class="col-lg-8 col-md-10">
                <div class="card border-0 shadow-lg rounded-lg">
                    <div class="card-header bg-primary text-white border-0 rounded-top">
                        <h4 class="m-0 font-weight-bold">Re Assign Case</h4>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('re.assign.update') }}" method="POST" id="caseUpdate">
                            @csrf
                            <input type="hidden" name="id" value="{{$cases->id}}" required>
                            <div class="mb-4">
                                <p>Customer Name: <strong>{{$customer->name}}</strong></p>
                            </div>
                            <div class="mb-4">
                                <p>Vompany Name: <strong>{{$company->name}}</strong></p>
                            </div>
                            <div class="mb-4">
                                <p>Case Type: <strong>{{$cases->type}}</strong></p>
                            </div>
                            <hr><br>
                            <div id="successMessage" class="alert alert-success" style="display: none;"></div>
                            <div class="mb-4">
                                <label for="driver" class="form-label font-weight-bold">Select Executive (Driver)</label>
                                <select id="driver" name="driver" class="form-select border-0 rounded-pill shadow-sm" required>
                                    <option disabled>Select the executive</option>
                                    @foreach ($executives as $user)
                                        <option value="{{$user->id}}" {{ $user->id == $cases->executive_driver ? 'selected' : '' }}>
                                            {{$user->name}} ({{$user->place}})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-4">
                                <label for="Garage" class="form-label font-weight-bold">Select Executive (Garage)</label>
                                <select id="Garage" name="garage" class="form-select border-0 rounded-pill shadow-sm" required>
                                    <option disabled>Select the executive</option>
                                    @foreach ($executives as $user)
                                        <option value="{{$user->id}}" {{ $user->id == $cases->executive_garage ? 'selected' : '' }}>
                                            {{$user->name}} ({{$user->place}})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-4">
                                <label for="Spot" class="form-label font-weight-bold">Select Executive (Spot)</label>
                                <select id="Spot" name="spot" class="form-select border-0 rounded-pill shadow-sm" required>
                                    <option disabled>Select the executive</option>
                                    @foreach ($executives as $user)
                                        <option value="{{$user->id}}" {{ $user->id == $cases->executive_spot ? 'selected' : '' }}>
                                            {{$user->name}} ({{$user->place}})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-4">
                                <label for="Meeting" class="form-label font-weight-bold">Select Executive (Meeting)</label>
                                <select id="Meeting" name="meeting" class="form-select border-0 rounded-pill shadow-sm" required>
                                    <option disabled>Select the executive</option>
                                    @foreach ($executives as $user)
                                        <option value="{{$user->id}}" {{ $user->id == $cases->executive_meeting ? 'selected' : '' }}>
                                            {{$user->name}} ({{$user->place}})
                                        </option>   
                                    @endforeach
                                </select>
                            </div>
                            @if($cases->type=='MAC')
                            <div class="mb-4">
                                <label for="accident_person" class="form-label font-weight-bold">Select Executive (Accident Person)</label>
                                <select id="accident_person" name="accident_person" class="form-select border-0 rounded-pill shadow-sm" required>
                                    <option disabled>Select the executive</option>
                                    @foreach ($executives as $user)
                                        <option value="{{$user->id}}" {{ $user->id == $cases->executive_accident_person ? 'selected' : '' }}>
                                            {{$user->name}} ({{$user->place}})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            @endif
                            <div class="mb-4">
                                <label for="date" class="form-label font-weight-bold">Investigation Date</label>
                                <input type="date" name="date" id="date" class="form-control" value="{{$cases->date}}">
                            </div>
                            <div class="mb-4">
                                <button type="submit" class="btn btn-primary">Update</button>
                            </div>
                        </form>
                        
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

    <script>
        $(document).ready(function() {
            $('#caseUpdate').on('submit', function(e) {
                e.preventDefault();

                $.ajax({
                    url: '{{ route('re.assign.update') }}',
                    type: 'POST',
                    data: $(this).serialize(),
                    success: function(response) {
                        if (response.success) {
                            $('#successMessage').text(response.success).show();
                            $('#caseUpdate')[0].reset(); // Reset form fields
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
<!-- @endsection -->


