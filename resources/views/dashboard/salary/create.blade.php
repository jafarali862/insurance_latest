@extends('dashboard.layout.app')
@section('title', 'Salary Create')

@section('content')
    <div class="container-fluid">

        <div class="text-right">
            <button class="btn btn-danger mr-2 mb-2" onclick="window.history.back()"><i class="fa fa-arrow-left"
                    aria-hidden="true"></i></button>
            <button class="btn btn-warning mr-2 mb-2" onclick="window.location.reload()"><i class="fa fa-spinner"
                    aria-hidden="true mr-2 mb-2"></i></button>
            <a class="btn btn-primary" href="{{route('salary.index')}}"><i class="fa fa-list-ul" aria-hidden="true"></i></a>
        </div>

        <h2>Create Salary</h2>

        <div id="message" class="alert" style="display:none;"></div> <!-- Message div -->

        <form id="salaryForm" action="{{ route('salary.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="user">Select User:</label>
                <select class="form-control" id="user" name="user" required>
                    <option selected disabled>Select a user</option>
                    @foreach ($users as $user)
                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="basic">Basic Salary:</label>
                <input type="number" class="form-control" id="basic" name="basic_salary"
                    placeholder="Enter Basic Salary" required>
            </div>

            <div class="form-group">
                <label for="allowance">Allowance:</label>
                <input type="number" class="form-control" id="allowance" name="allowance" placeholder="Enter Allowance"
                    required>
            </div>

            <div class="form-group">
                <label for="bonus">Bonus:</label>
                <input type="number" class="form-control" id="bonus" name="bonus" placeholder="Enter Bonus" required>
            </div>

            <div class="form-group">
                <label for="month_year">Month/Year:</label>
                <input type="month" class="form-control" id="month_year" name="month_year" required>
            </div>

            <div class="form-group">
                <label for="total">Total Salary:</label>
                <input type="number" class="form-control" id="total" name="total_salary" placeholder="Total Salary"
                    readonly>
            </div>

            <button type="button" class="btn btn-primary" id="calculateTotal">Calculate Total</button>
            <button type="submit" class="btn btn-success">Submit</button>
        </form>
    </div>

    <style>
        .container-fluid {
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 5px;
        }

        h2 {
            color: #333;
            margin-bottom: 20px;
        }

        .form-group label {
            font-weight: bold;
        }

        .form-control {
            border-radius: 5px;
            border: 1px solid #ced4da;
        }

        .btn {
            margin-top: 10px;
        }

        #message {
            margin-bottom: 20px;
        }
    </style>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#calculateTotal').click(function() {
                const basic = parseFloat($('#basic').val()) || 0;
                const allowance = parseFloat($('#allowance').val()) || 0;
                const bonus = parseFloat($('#bonus').val()) || 0;

                // Calculate total
                const total = basic + allowance + bonus;

                $('#total').val(total);
            });

            $('#salaryForm').submit(function(e) {
                e.preventDefault(); // Prevent default form submission

                $.ajax({
                    url: $(this).attr('action'), // The form's action attribute
                    type: 'POST',
                    data: $(this).serialize(), // Serialize form data
                    success: function(response) {
                        // Show success message
                        $('#message').removeClass('alert-danger').addClass(
                            'alert alert-success');
                        $('#message').text(response.message).show();

                        // Reset form fields
                        $('#salaryForm')[0].reset();
                        $('#total').val(''); // Reset total field
                    },
                    error: function(xhr) {
                        // Handle error response
                        const errors = xhr.responseJSON.errors;
                        let errorMessage = '';

                        for (const key in errors) {
                            if (errors.hasOwnProperty(key)) {
                                errorMessage += errors[key].join(', ') + '\n';
                            }
                        }
                        $('#message').removeClass('alert-success').addClass(
                            'alert alert-danger');
                        $('#message').text('Error: \n' + errorMessage).show();
                    }
                });
            });
        });
    </script>
@endsection
