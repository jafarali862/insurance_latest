<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="{{ route('dashboard') }}" class="brand-link text-center">
        @if($companyLogo && $companyLogo->logo)
            <img src="{{ asset('storage/' . $companyLogo->logo) }}" alt="Logo" class="brand-image img-circle elevation-3" style="opacity: .8; width: 40px;">
        @else
            <img src="{{ asset('dashboard/img/icon.png') }}" alt="Logo" class="brand-image img-circle elevation-3" style="opacity: .8; width: 40px;">
        @endif
        <span class="brand-text font-weight-light">Insurance</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">

                <!-- Dashboard -->
                <li class="nav-item">
                    <a href="{{ route('dashboard') }}" class="nav-link {{ request()->is('dashboard') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>Dashboard</p>
                    </a>
                </li>

                <!-- User Management -->
                <li class="nav-item has-treeview {{ request()->is('user*') ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-users-cog"></i>
                        <p>
                            User Management
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('user.create') }}" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Add User</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('user.list') }}" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>User List</p>
                            </a>
                        </li>
                    </ul>
                </li>

                <!-- Insurance Company -->
                <li class="nav-item has-treeview">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-building"></i>
                        <p>
                            Insurance Company
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('company.create') }}" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Add Company</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('company.list') }}" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Company List</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('questions.index_question') }}" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Questionnaire List</p>
                            </a>
                        </li>

                         <!-- <li class="nav-item">
                            <a href="{{ route('category.index') }}" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Category of Data</p>
                            </a>
                        </li> -->

                    </ul>
                </li>

                <!-- Case Management -->
                <li class="nav-item has-treeview">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-briefcase"></i>
                        <p>
                            Case Management
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('insurance.create') }}" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Add Case</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('case.index') }}" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Case List</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('assigned.case') }}" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Assigned Case</p>
                            </a>
                        </li>
                    </ul>
                </li>

                <!-- Report Management -->
                <li class="nav-item">
                    <a href="{{ route('request.report') }}" class="nav-link">
                        <i class="nav-icon fas fa-file-alt"></i>
                        <p>Report Management</p>
                    </a>
                </li>

                <!-- Odometer Reading -->
                <li class="nav-item">
                    <a href="{{ route('odometer.list') }}" class="nav-link">
                        <i class="nav-icon fas fa-bicycle"></i>
                        <p>Odometer Reading</p>
                    </a>
                </li>

                <!-- Approval Request -->
                <li class="nav-item">
                    <a href="{{ route('approval.request') }}" class="nav-link">
                        <i class="nav-icon fas fa-paper-plane"></i>
                        <p>Approval Request</p>
                    </a>
                </li>

                <!-- Password Reset -->
                <li class="nav-item">
                    <a href="{{ route('password.reset.request') }}" class="nav-link">
                        <i class="nav-icon fa fa-lock"></i>
                        <p>Password Reset Request</p>
                    </a>
                </li>

                <!-- Company Management -->
                <li class="nav-item">
                    <a href="{{ route('logos') }}" class="nav-link">
                        <i class="nav-icon fab fa-pied-piper"></i>
                        <p>Company Management</p>
                    </a>
                </li>

                <!-- Final Report -->
                <li class="nav-item">
                    <a href="{{ route('final.report.create') }}" class="nav-link">
                        <i class="nav-icon fa fa-file"></i>
                        <p>Make Final Report</p>
                    </a>
                </li>

            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>
