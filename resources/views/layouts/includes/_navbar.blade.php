<!-- partial:partials/_navbar.html -->
<nav class="navbar col-lg-12 col-12 p-0 fixed-top d-flex flex-row">
    <div class="text-center navbar-brand-wrapper d-flex align-items-center justify-content-center">
    <a class="navbar-brand brand-logo mr-5" href="{{ route('dashboard.index') }}"><img src="{{ asset('public/default/h&s.jpg') }}" class="mr-2" alt="logo"/></a>
    <a class="navbar-brand brand-logo-mini" href="{{ route('dashboard.index') }}"><img src="{{ asset('public/default/h&s.jpg') }}" alt="logo"/></a>
    </div>
    <div class="navbar-menu-wrapper d-flex align-items-center justify-content-end">

        <button style="outline: none; box-shadow:none" class="navbar-toggler navbar-toggler align-self-center" type="button" data-toggle="minimize">
            <span class="icon-menu"></span>
        </button>

        <ul class="navbar-nav navbar-nav-right">
            <li class="nav-item nav-profile dropdown">
            <a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown" id="profileDropdown">
                <img src="{{ asset('public/default/profile.png') }}" alt="profile"/>
            </a>
            <div class="dropdown-menu dropdown-menu-right navbar-dropdown" aria-labelledby="profileDropdown">
                <a class="dropdown-item">
                <i class="ti-settings text-primary"></i>
                Settings
                </a>
                <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <i class="ti-power-off text-primary"></i>
                        Logout
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                        @csrf
                    </form>
                </a>
            </div>
            </li>
        </ul>

        <button style="outline: none; box-shadow:none" class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button" data-toggle="offcanvas">
            <span class="icon-menu"></span>
        </button>
        
    </div>
</nav>
<!-- partial -->