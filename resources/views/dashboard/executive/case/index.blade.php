@extends('dashboard.layout.app')
@section('title', 'Case List')

@section('content')
    <div class="container-fluid">
        <div class="card">
            <div class="card-header">
                <h4>Case List</h4>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped text-center">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Company Name</th>
                                <th>Customer Name</th>
                                <th>Executive Driver</th>
                                <th>Executive Garage</th>
                                <th>Executive Spot</th>
                                <th>Executive Meeting</th>
                                <th>Date</th>
                                <th>Actton</th>
                            </tr>
                        </thead>
                        <?php $i = 1; ?>
                        <tbody>
                            @foreach ($cases as $case)
                            <tr>
                                <td><?php echo $i++ ?></td>
                                <td>{{ $case->company_name }}</td>
                                <td>{{ $case->customer_name }}</td>
                                <td>{{ $case->driver_name }}</td>
                                <td>{{ $case->garage_name }}</td>
                                <td>{{ $case->spot_name }}</td>
                                <td>{{ $case->meeting_name }}</td>
                                <td>{{ \Carbon\Carbon::createFromFormat('Y-m-d', $case->date)->format('d-F-Y') }}</td>
                                <td>
                                    <a href="" class="btn btn-primary"><i class="fa fa-eye" aria-hidden="true"></i></a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="d-flex justify-content-right">
                        {{$cases->links()}}
                    </div>
                </div>                
            </div>
        </div>
    </div>
@endsection
