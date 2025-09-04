@extends('dashboard.layout.app')
@section('title', 'Home')
@section('content')

@php
    $user = Auth::user();
@endphp

<div class="container-fluid">
    @if ($user->role == 1)
        <div class="row">

            <!-- Insurance Companies -->
            <div class="col-lg-3 col-6">
                <div class="small-box bg-success">
                    <div class="inner">
                        <h3>{{ $totalCompany }}</h3>
                        <p>Insurance Companies</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-building"></i>
                    </div>
                    <a href="{{ route('company.list') }}" class="small-box-footer">
                        More info <i class="fas fa-arrow-circle-right"></i>
                    </a>
                </div>
            </div>

            <!-- Total Employees -->
            <div class="col-lg-3 col-6">
                <div class="small-box bg-warning">
                    <div class="inner">
                        <h3>{{ $totalEmployee }}</h3>
                        <p>Total Employees</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-users"></i>
                    </div>
                    <a href="{{ route('user.list') }}" class="small-box-footer text-dark">
                        More info <i class="fas fa-arrow-circle-right"></i>
                    </a>
                </div>
            </div>

            <!-- Total Cases -->
            <div class="col-lg-3 col-6">
                <div class="small-box bg-primary">
                    <div class="inner">
                        <h3>{{ $totalcaseCount }}</h3>
                        <p>Total Cases</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-clipboard-list"></i>
                    </div>
                    <a href="{{ route('case.index') }}" class="small-box-footer">
                        More info <i class="fas fa-arrow-circle-right"></i>
                    </a>
                </div>
            </div>

            <!-- Pending Investigations -->
            <div class="col-lg-3 col-6">
                <div class="small-box bg-danger">
                    <div class="inner">
                        <h3>{{ $totalcaseCount }}</h3>
                        <p>Pending Investigations</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-clock"></i>
                    </div>
                    <a href="{{ route('case.index') }}" class="small-box-footer">
                        More info <i class="fas fa-arrow-circle-right"></i>
                    </a>
                </div>
            </div>

        </div>
</div>

<div class="container-fluid">
    <div class="row">

        <!-- Complete Investigations -->
        <div class="col-lg-3 col-6">
            <div class="small-box bg-info">
                <div class="inner">
                    <h3>{{ $completeCase }}</h3>
                    <p>Complete Investigations</p>
                </div>
                <div class="icon">
                    <i class="fas fa-check-circle"></i>
                </div>
                <a href="#" class="small-box-footer">
                    More info <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>

        <!-- Assigned Cases -->
        <div class="col-lg-3 col-6">
            <div class="small-box bg-secondary">
                <div class="inner">
                    <h3>{{ $assignedCase }}</h3>
                    <p>Assigned Cases</p>
                </div>
                <div class="icon">
                    <i class="fas fa-clipboard-check"></i>
                </div>
                <a href="{{ route('assigned.case') }}" class="small-box-footer">
                    More info <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>

        <!-- Submitted Cases -->
        <div class="col-lg-3 col-6">
            <div class="small-box bg-warning">
                <div class="inner">
                    <h3>{{ $totalSubmittedCase }}</h3>
                    <p>Submitted Cases</p>
                </div>
                <div class="icon">
                    <i class="fas fa-paper-plane"></i>
                </div>
                <a href="{{ route('assigned.case') }}" class="small-box-footer text-dark">
                    More info <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>

        <!-- Fake Cases -->
        <div class="col-lg-3 col-6">
            <div class="small-box bg-danger">
                <div class="inner">
                    <h3>{{ $fakeCase }}</h3>
                    <p>Fake Cases</p>
                </div>
                <div class="icon">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>
                <a href="{{ route('fake.cases') }}" class="small-box-footer">
                    More info <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>

    </div>
</div>

        <!-- Today Submitted Cases -->
      <div class="col-lg-3 col-6">
    <!-- small box -->
    <div class="small-box bg-danger">
        <div class="inner">
            <h3>{{ $todaySubmittedCase }}</h3>
            <p>Today Submitted Cases</p>
        </div>
        <div class="icon">
            <i class="fas fa-calendar-day"></i>
        </div>
        <a href="{{ route('case.today') }}" class="small-box-footer">
            More info <i class="fas fa-arrow-circle-right"></i>
        </a>
    </div>
</div>


 <div class="row">
    <!-- Area Chart -->
    <div class="col-md-8">
        <div class="card card-primary card-outline" style="padding-bottom: 40px;">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-chart-line"></i> Total Cases for {{ $currentYear }}
                </h3>
            </div>
            <div class="card-body">
                <div class="chart">
                    <canvas id="myAreaChart" style="min-height: 300px; height: 300px; max-height: 300px;"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Pie Chart -->
    <div class="col-md-4">
        <div class="card card-success card-outline">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-chart-pie"></i> Total Case Status
                </h3>
            </div>
            <div class="card-body">
                <div class="chart">
                    <canvas id="PieChart" style="min-height: 300px; height: 300px; max-height: 300px;"></canvas>
                </div>

                <div class="text-center mt-3">
                    <span class="badge bg-primary text-white">
                        <i class="fas fa-circle me-1"></i>  Complete: <strong>{{ $completeCase }}</strong>
                    </span>
                    <span class="badge bg-danger  text-white ms-2">
                        <i class="fas fa-circle me-1"></i> Pending: <strong>{{ $pendingCase }}</strong>
                    </span>
                </div>
                
            </div>
        </div>
    </div>
</div>



@endif

    <!-- /.container-fluid -->

   @push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    // Area Chart
  const ctxArea = document.getElementById('myAreaChart').getContext('2d');

// Create vertical gradient for the area fill
const gradient = ctxArea.createLinearGradient(0, 0, 0, 400);
gradient.addColorStop(0, 'rgba(60,141,188,0.5)');
gradient.addColorStop(1, 'rgba(60,141,188,0)');

const areaChart = new Chart(ctxArea, {
    type: 'line',
    data: {
        labels: ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'],
        datasets: [{
            label: 'Total Cases',
            data: @json($casesCount),
            backgroundColor: gradient,
            borderColor: 'rgba(60,141,188,1)',
            pointBackgroundColor: 'rgba(60,141,188,1)',
            borderWidth: 3,
            pointRadius: 5,
            pointHoverRadius: 7,
            tension: 0.3,
            fill: true,
            hoverBorderWidth: 4,
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                labels: {
                    font: {
                        size: 14,
                        family: "'Segoe UI', Tahoma, Geneva, Verdana, sans-serif",
                        weight: '600',
                    },
                    color: '#e0e0e0'  // lighter legend text
                }
            },
            tooltip: {
                mode: 'index',
                intersect: false,
                bodyColor: '#222',
                titleColor: '#222',
                backgroundColor: 'rgba(255,255,255,0.9)',
                borderColor: 'rgba(60,141,188,0.8)',
                borderWidth: 1,
                padding: 8,
                cornerRadius: 6,
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    stepSize: 10,
                    font: {
                        size: 13,
                        family: "'Segoe UI', Tahoma, Geneva, Verdana, sans-serif",
                    },
                    color: '#ccc',
                },
                grid: {
                    color: 'rgba(255, 255, 255, 0.15)',
                    borderDash: [6, 4],
                }
            },
            x: {
                ticks: {
                    font: {
                        size: 13,
                        family: "'Segoe UI', Tahoma, Geneva, Verdana, sans-serif",
                    },
                    color: '#ccc',
                },
                grid: {
                    color: 'rgba(255, 255, 255, 0.1)',
                    borderDash: [4, 3],
                }
            }
        }
    }
});


    

    // Pie Chart
    const complete = {{ $completeCase }};
    const pending = {{ $pendingCase }};
    const ctxPie = document.getElementById('PieChart').getContext('2d');
    const pieChart = new Chart(ctxPie, {
        type: 'doughnut',
        data: {
            labels: ['Complete', 'Pending'],
            datasets: [{
                data: [complete, pending],
                backgroundColor: ['#007bff', '#dc3545'],
                borderColor: '#fff',
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        boxWidth: 15,
                        font: {
                            size: 12,
                        },
                        color: '#fff'  // <-- Legend labels white
                    }
                },
                tooltip: {
                    bodyColor: '#fff',    // tooltip text color
                    titleColor: '#fff',   // tooltip title color
                    backgroundColor: 'rgba(0,0,0,0.7)' // dark tooltip background
                }
            }
        }
    });
});

</script>
@endpush

@endsection
