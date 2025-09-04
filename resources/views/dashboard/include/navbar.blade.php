@php
    $user = Auth::user();
@endphp

<nav class="main-header navbar navbar-expand navbar-light border-bottom bg-white">
    <!-- Sidebar Toggle (for push menu) -->
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#" role="button">
                <i class="fas fa-bars"></i>
            </a>
        </li>
    </ul>

    <!-- Greeting & Time (Center or Left-Aligned) -->
    <ul class="navbar-nav ml-3">
        <li class="nav-item d-flex align-items-center">
            <span class="text-primary font-weight-bold" id="greetingTime" style="font-family: Arial Black;"></span>
        </li>
    </ul>

    <!-- Right Navbar -->
    <ul class="navbar-nav ml-auto">

        <!-- User Dropdown Menu -->
        <li class="nav-item dropdown">
            <a class="nav-link d-flex align-items-center" data-toggle="dropdown" href="#" role="button">
                <span class="mr-2 d-none d-md-inline text-dark font-weight-bold" style="color:#343a40 !important;">{{ $user->name }}</span>
                <img src="{{ asset('dashboard/img/undraw_profile.svg') }}" alt="User Avatar"
                    class="img-size-32 img-circle elevation-2">
            </a>
            <div class="dropdown-menu dropdown-menu-right">
                <!-- Optional Profile Links -->
                <!--
                <a href="#" class="dropdown-item">
                    <i class="fas fa-user mr-2"></i> Profile
                </a>
                <div class="dropdown-divider"></div>
                -->

                <!-- Logout -->
                <a href="#" class="dropdown-item text-danger" data-toggle="modal" data-target="#logoutModal">
                    <i class="fas fa-sign-out-alt mr-2"></i> Logout
                </a>
            </div>
        </li>
    </ul>
</nav>

<script>
    function updateTime() {
        const now = new Date();
        const hours = now.getHours();
        const minutes = now.getMinutes();
        const seconds = now.getSeconds();

        const period = hours >= 12 ? 'PM' : 'AM';
        const formattedHours = hours % 12 || 12;
        const formattedMinutes = String(minutes).padStart(2, '0');
        const formattedSeconds = String(seconds).padStart(2, '0');
        const currentTime = `${formattedHours}:${formattedMinutes}:${formattedSeconds} ${period}`;

        let greeting;
        if (hours < 12) {
            greeting = 'Good Morning';
        } else if (hours < 15) {
            greeting = 'Good Afternoon';
        } else {
            greeting = 'Good Evening';
        }

        document.getElementById('greetingTime').textContent = `${greeting} - ${currentTime}`;
    }

    setInterval(updateTime, 1000);
    updateTime();
</script>
