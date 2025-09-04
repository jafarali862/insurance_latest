<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Insurance</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="shortcut icon" href="{{ asset('dashboard/img/icon.png') }}" type="image/x-icon">

    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f8f9fa;
        }

        .navbar {
            background-color: #0056b3;
        }

        .navbar-brand,
        .nav-link {
            color: white !important;
        }

        .hero {
            background: url('{{ asset('page/4.jpg') }}') no-repeat center center;
            background-size: cover;
            color: white;
            padding: 100px 0;
            text-align: center;
        }

        .card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            min-height: 400px;
        }

        .card img {
            border-radius: 10px 10px 0 0;
        }

        .card:hover {
            transform: scale(1.05);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.3);
        }

        .footer {
            background-color: #0056b3;
            color: white;
            padding: 30px 0;
            text-align: center;
        }
    </style>
</head>

<body>

    <!-- Navigation Bar -->
    <nav class="navbar navbar-expand-lg navbar-light">
        <a class="navbar-brand" href="#"></a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav"
            aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item active">
                    <a class="nav-link" href="{{ url('/login') }}">Login</a>
                </li>
                </ul>
        </div>
    </nav>

    <!-- Hero Section -->
    <div class="hero">
        <h1>Secure Your Future</h1>
        <p>Insurance solutions tailored for you!</p>
        <a href="{{ url('/login') }}" class="btn btn-light btn-lg">Login</a>
    </div>

    <!-- Services Section -->
    <div class="container mt-5">
        <h2 class="text-center mb-4">Our Premium Services</h2>
        <div class="row">
            <div class="col-md-4">
                <div class="card mb-4">
                    <img src="{{ asset('page/1.jpg') }}" class="card-img-top" alt="Auto Insurance">
                    <div class="card-body">
                        <h5 class="card-title">Auto Insurance</h5>
                        <p class="card-text">Drive confidently with comprehensive coverage and peace of mind on the
                            road.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card mb-4">
                    <img src="{{ asset('page/2.jpg') }}" class="card-img-top" alt="Home Insurance">
                    <div class="card-body">
                        <h5 class="card-title">Home Insurance</h5>
                        <p class="card-text">Protect your home and everything you cherish with our tailored insurance
                            plans.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card mb-4">
                    <img src="{{ asset('page/3.jpg') }}" class="card-img-top" alt="Life Insurance">
                    <div class="card-body">
                        <h5 class="card-title">Life Insurance</h5>
                        <p class="card-text">Secure your family's future with plans designed to fit your life and
                            budget.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Testimonials Section -->
    <div class="container mt-5">
        <h2 class="text-center mb-4">What Our Clients Say</h2>
        <div class="row">
            <div class="col-md-4">
                <div class="card mb-4">
                    <img src="{{ asset('page/client1.jpg') }}" class="card-img-top" alt="Michael Brown">
                    <div class="card-body">
                        <h5 class="card-title">Michael Brown</h5>
                        <p class="card-text">"I felt so secure knowing my family was protected. The process was smooth
                            and the team was amazing!"</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card mb-4">
                    <img src="{{ asset('page/client2.jpg') }}" class="card-img-top" alt="Sarah Wilson">
                    <div class="card-body">
                        <h5 class="card-title">Sarah Wilson</h5>
                        <p class="card-text">"They helped me choose the perfect coverage for my home. Highly recommend!"
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card mb-4">
                    <img src="{{ asset('page/client3.jpg') }}" class="card-img-top" alt="Emily Davis">
                    <div class="card-body">
                        <h5 class="card-title">Emily Davis</h5>
                        <p class="card-text">"Excellent service and friendly staff. I got the coverage I needed
                            quickly!"</p>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!-- Footer -->
    <div class="footer">
        <p>&copy; 2024 Niveosys Technologies Pvt Ltd. All Rights Reserved.</p>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>
