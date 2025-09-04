<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="{{ url('/home') }}" class="brand-link">
        <i class="fas fa-shield-alt ml-3 text-light"></i>
        <span class="brand-text font-weight-light ml-2">Insurance</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">

                <!-- Dashboard -->
                <li class="nav-item">
                    <a href="{{ url('/home') }}" class="nav-link {{ request()->is('home') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>Dashboard</p>
                    </a>
                </li>

                <!-- Case Management -->
                <li class="nav-item has-treeview {{ request()->is('executive/case/*') ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-briefcase"></i>
                        <p>
                            Case Management
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('executive.case.list') }}" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Case List</p>
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

                <!-- Message -->
                <li class="nav-item">
                    <a href="{{ url('tables.html') }}" class="nav-link">
                        <i class="nav-icon fas fa-table"></i>
                        <p>Message</p>
                    </a>
                </li>

            </ul>
        </nav>
        <!-- /.sidebar-menu -->

    </div>
    <!-- /.sidebar -->
</aside>
