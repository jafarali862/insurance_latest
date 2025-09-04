@extends('dashboard.layout.app')
@section('title', 'Salary List')

@section('content')
<div class="container-fluid">
    <div class="text-right">
        <a class="btn btn-primary" href="{{route('salary.create')}}"><i class="fa fa-plus" aria-hidden="true"></i></a>
    </div>
    <h1 class="mb-4">Salary List</h1>

    @if($salaries->isEmpty())
        <div class="alert alert-warning" role="alert">
            No salary records found.
        </div>
    @else
        <table class="table table-bordered text-center">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Executive Name</th>
                    <th>Basic </th>
                    <th>Allowance </th>
                    <th>Bonus</th>
                    <th>Total</th>
                    <th>Month/Year</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                @foreach($salaries as $salary)
                    <tr>
                        <td>{{ $salary->id }}</td>
                        <td>{{ $salary->user->name }}</td> <!-- Updated to fetch from the users table -->
                        <td>{{ number_format($salary->basic, 2) }}</td>
                        <td>{{ number_format($salary->allowance, 2) }}</td>
                        <td>{{ number_format($salary->bonus, 2) }}</td>
                        <td>{{ number_format($salary->total, 2) }}</td>
                        <td>{{ \Carbon\Carbon::parse($salary->month_year)->format('F Y') }}</td>
                        <td>{{ \Carbon\Carbon::parse($salary->date)->format('d-m-Y') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Pagination Links -->
        {{ $salaries->links() }}
    @endif
</div>
@endsection
