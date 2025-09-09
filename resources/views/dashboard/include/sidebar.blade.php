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
                    <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>Dashboard</p>
                    </a>
                </li>

                <!-- User Management -->
            @php
            $userRoutes = ['user.create', 'user.list', 'user.edit', 'user.show']; // add all related routes here
            $userMenuOpen = collect($userRoutes)->contains(function ($route) {
            return request()->routeIs($route);
            });
            @endphp

            <li class="nav-item has-treeview {{ $userMenuOpen ? 'menu-open' : '' }}">
            <a href="#" class="nav-link {{ $userMenuOpen ? 'active' : '' }}">
            <i class="nav-icon fas fa-users-cog"></i>
            <p>
            User Management
            <i class="right fas fa-angle-left"></i>
            </p>
            </a>

            <ul class="nav nav-treeview">
            <li class="nav-item">
            <a href="{{ route('user.create') }}" class="nav-link {{ request()->routeIs('user.create') ? 'active' : '' }}">
            <i class="far fa-circle nav-icon"></i>
            <p>Add User</p>
            </a>
            </li>
            <li class="nav-item">
            <a href="{{ route('user.list') }}" class="nav-link {{ request()->routeIs('user.list') ? 'active' : '' }}">
            <i class="far fa-circle nav-icon"></i>
            <p>User List</p>
            </a>
            </li>
            </ul>
            </li>


                <!-- Insurance Company -->
              @php
    $insuranceRoutes = [
        'company.create',
        'company.list',
        'questions.index_question',
        'templates.list_templates',
        // add more routes if needed
    ];

    $isInsuranceActive = collect($insuranceRoutes)->contains(function ($route) {
        return request()->routeIs($route);
    });
@endphp

<li class="nav-item has-treeview {{ $isInsuranceActive ? 'menu-open' : '' }}">
    <a href="#" class="nav-link {{ $isInsuranceActive ? 'active' : '' }}">
        <i class="nav-icon fas fa-building"></i>
        <p>
            Insurance Company
            <i class="right fas fa-angle-left"></i>
        </p>
    </a>
    <ul class="nav nav-treeview">
        <li class="nav-item">
            <a href="{{ route('company.create') }}" class="nav-link {{ request()->routeIs('company.create') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon"></i>
                <p>Add Company</p>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('company.list') }}" class="nav-link {{ request()->routeIs('company.list') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon"></i>
                <p>Company List</p>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('questions.index_question') }}" class="nav-link {{ request()->routeIs('questions.index_question') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon"></i>
                <p>Questionnaire List</p>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('templates.list_templates') }}" class="nav-link {{ request()->routeIs('templates.list_templates') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon"></i>
                <p>Templates</p>
            </a>
        </li>
    </ul>
</li>


          
                @php
                $caseRoutes = [
                'insurance.create',
                'case.index',
                'assigned.case',
                ];

                $isCaseActive = collect($caseRoutes)->contains(function ($route) {
                return request()->routeIs($route);
                });
                @endphp

                <li class="nav-item has-treeview {{ $isCaseActive ? 'menu-open' : '' }}">
                <a href="#" class="nav-link {{ $isCaseActive ? 'active' : '' }}">
                <i class="nav-icon fas fa-briefcase"></i>
                <p>
                Case Management
                <i class="right fas fa-angle-left"></i>
                </p>
                </a>
                <ul class="nav nav-treeview">
                <li class="nav-item">
                <a href="{{ route('insurance.create') }}" class="nav-link {{ request()->routeIs('insurance.create') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon"></i>
                <p>Add Case</p>
                </a>
                </li>
                <li class="nav-item">
                <a href="{{ route('case.index') }}" class="nav-link {{ request()->routeIs('case.index') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon"></i>
                <p>Case List</p>
                </a>
                </li>
                <li class="nav-item">
                <a href="{{ route('assigned.case') }}" class="nav-link {{ request()->routeIs('assigned.case') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon"></i>
                <p>Assigned Case</p>
                </a>
                </li>
                </ul>
                </li>




                <!-- Report Management -->
                <li class="nav-item">
                    <a href="{{ route('request.report') }}" class="nav-link {{ request()->routeIs('request.report') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-file-alt"></i>
                        <p>Report Management</p>
                    </a>
                </li>

                <!-- Odometer Reading -->
                <li class="nav-item">
                    <a href="{{ route('odometer.list') }}" class="nav-link {{ request()->routeIs('odometer.list') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-bicycle"></i>
                        <p>Odometer Reading</p>
                    </a>
                </li>

                <!-- Approval Request -->
                <li class="nav-item">
                    <a href="{{ route('approval.request') }}" class="nav-link {{ request()->routeIs('approval.request') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-paper-plane"></i>
                        <p>Approval Request</p>
                    </a>
                </li>

                <!-- Password Reset -->
                <li class="nav-item">
                    <a href="{{ route('password.reset.request') }}" class="nav-link {{ request()->routeIs('password.reset.request') ? 'active' : '' }}">
                        <i class="nav-icon fa fa-lock"></i>
                        <!-- <p>Password Reset Request</p> -->
                        <p>Password Reset</p>  
                    </a>
                </li>

                <!-- Company Management -->
                <li class="nav-item">
                    <a href="{{ route('logos') }}" class="nav-link {{ request()->routeIs('logos') ? 'active' : '' }}">
                        <i class="nav-icon fab fa-pied-piper"></i>
                        <p>Company Management</p>
                    </a>
                </li>

                <!-- Final Report -->
                <li class="nav-item">
                    <a href="{{ route('final.report.create') }}" class="nav-link {{ request()->routeIs('final.report.create') ? 'active' : '' }}">
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

<style>

    .sidebar-dark-primary .nav-sidebar>.nav-item>.nav-link.active, .sidebar-light-primary .nav-sidebar>.nav-item>.nav-link.active
    {
        background-color: #3f6791;
    }
</style>