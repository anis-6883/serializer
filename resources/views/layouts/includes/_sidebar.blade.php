<!-- partial:partials/_sidebar.html -->
<nav class="sidebar sidebar-offcanvas" id="sidebar">
    <ul class="nav">
        <li class="nav-item">
            <a class="nav-link" href="{{ route('dashboard.index') }}">
                <i class='menu-icon fas fa-home'></i> 
                <span class="menu-title">Dashboard</span>
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link" href="{{ url('apps/') }}">
                <i class='menu-icon fab fa-app-store-ios'></i> 
                <span class="menu-title">App</span>
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link" href="{{ url('serials/') }}">
                <i class='menu-icon fas fa-film'></i> 
                <span class="menu-title">Serial</span>
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link" href="{{ url('episodes/') }}">
                <i class='menu-icon fas fa-file-video'></i> 
                <span class="menu-title">Episode</span>
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link" href="{{ url('notifications/') }}">
                <i class='menu-icon fas fa-bell'></i> 
                <span class="menu-title">Notification</span>
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link" data-toggle="collapse" href="#ui-basic" aria-expanded="false" aria-controls="ui-basic">
                <i class='menu-icon fas fa-cogs'></i> 
                <span class="menu-title">Administration</span>
                <i class="menu-arrow"></i>
            </a>
            <div class="collapse" id="ui-basic">
                <ul class="nav flex-column sub-menu">
                <li class="nav-item"> <a class="nav-link" href="{{ url('/general_settings') }}">General Settings</a></li>
                <li class="nav-item"> <a class="nav-link" href="{{ url('/database_backup') }}">Database Backup</a></li>
                <li class="nav-item"> <a class="nav-link" href="{{ url('/cache') }}">Cache Clean</a></li>
                </ul>
            </div>
        </li>

    </ul>
</nav>  
<!-- partial -->