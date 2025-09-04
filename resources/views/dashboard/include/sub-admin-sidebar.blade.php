<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="{{ route('dashboard') }}" class="brand-link">
        <i class="fas fa-shield-alt ml-3 text-light"></i>
        <span class="brand-text font-weight-light ml-2">Insurance</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user panel (optional) -->
        @php $user = Auth::user(); @endphp
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                <img src="{{ asset('dashboard/img/undraw_profile.svg') }}" class="img-circle elevation-2" alt="User Image">
            </div>
            <div class="info">
                <a href="#" class="d-block">{{ $user->name }}</a>
            </div>
        </div>

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">

                <!-- Dashboard -->
                <li class="nav-item">
                    <a href="{{ route('dashboard') }}" class="nav-link {{ request()->is('home') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>Dashboard</p>
                    </a>
                </li>

                <!-- Case Management -->
                <li class="nav-item has-treeview {{ request()->is('insurance*') || request()->is('assigned*') ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-briefcase"></i>
                        <p>
                            Case Management
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('case.index') }}" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Case List</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('insurance.create') }}" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Add Case</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('assigned.case') }}" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Assigned Case</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Track Case</p>
                            </a>
                        </li>
                    </ul>
                </li>

                <!-- Report Management -->
                <li class="nav-item has-treeview">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-file-alt"></i>
                        <p>
                            Report Management
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('request.report') }}" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Report Requests</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Report List</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Add Report</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>All Final Report</p>
                            </a>
                        </li>
                    </ul>
                </li>

                <!-- Approval Request -->
                <li class="nav-item">
                    <a href="{{ route('approval.request') }}" class="nav-link">
                        <i class="nav-icon fas fa-paper-plane"></i>
                        <p>Approval Request</p>
                    </a>
                </li>

            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>
