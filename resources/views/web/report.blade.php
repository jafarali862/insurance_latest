<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <title>Greeting Message</title>
    <style>
        body {
            background-color: #e9ecef;
            color: #343a40;
            font-family: 'Arial', sans-serif;
        }

        .container {
            margin-top: 70px;
        }

        .card {
            border-radius: 15px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        }

        .btn-icon {
            display: flex;
            align-items: center;
            margin: 10px;
            width: 120px;
            border-radius: 10px;
        }

        h5 {
            font-weight: bold;
        }

        h6 {
            color: #000407;
            font-family: cursive;
        }

        .alert {
            font-size: 14px;
            font-weight: bold;
            color: #A30DA8;
        }

        .btn {
            font-family: cursive;
        }
    </style>
</head>

<body class="text-center">
    <div class="container">
        <div class="card p-4">
            <h6></h6>
            <h6 class="text-left">Hey {{ $user->name }} 👋</h6>
            <h6 class="text-left">Welcome Back!</h6>
            <div class="alert alert-danger text-magenta h6 text-bold" id="greetingTime"></div>

            <div class="row justify-content-center mb-5 mt-3">
                <div class="col-12 mb-3">
                    <a href="{{ route('salary.report', $user->id) }}" class="btn btn-lg btn-primary btn-block">
                        <i class="fas fa-dollar-sign px-2"></i> Salary
                    </a>
                </div>
                <div class="col-12 mb-3">
                    <a href="{{ route('today.record', $user->id) }}" class="btn btn-lg btn-secondary btn-block">
                        <i class="fas fa-calendar-day px-2"></i> Today
                    </a>
                </div>
                <div class="col-12 ">
                    <a href="{{ route('monthly.record', $user->id) }}" class="btn btn-lg btn-success btn-block">
                        <i class="fas fa-calendar-alt px-2"></i> Monthly
                    </a>
                </div>
            </div>

        </div>
    </div>

    <script>
        function updateTime() {
            const now = new Date();
            const hours = now.getHours();
            const minutes = now.getMinutes();
            const seconds = now.getSeconds();

            // Convert to 12-hour format
            const period = hours >= 12 ? 'PM' : 'AM';
            const formattedHours = hours % 12 || 12; // Converts 0 to 12
            const formattedMinutes = String(minutes).padStart(2, '0');
            const formattedSeconds = String(seconds).padStart(2, '0');

            // Determine greeting
            let greeting;
            if (hours >= 0 && hours < 12) {
                greeting = 'Good Morning';
            } else if (hours >= 12 && hours < 15) {
                greeting = 'Good Afternoon';
            } else {
                greeting = 'Good Evening';
            }

            // Update the greeting message
            document.getElementById('greetingTime').textContent =
                `${greeting} - ${formattedHours}:${formattedMinutes}:${formattedSeconds} ${period}`;
        }

        // Update time every second
        setInterval(updateTime, 1000);
        updateTime(); // Initial call
    </script>

    <!-- Font Awesome for icons -->
    <script src="https://kit.fontawesome.com/a076d05399.js"></script>

</body>

</html>
