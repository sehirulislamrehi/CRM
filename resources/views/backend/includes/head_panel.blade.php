<nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
        </li>
    </ul>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
        <li class="nav-item px-3 d-flex justify-content-center align-items-center">
            <div class="custom-control custom-switch">
                <input type="checkbox" class="custom-control-input" id="modeSwitch">
                <label class="custom-control-label" id="modelabel" for="modeSwitch">Dark Mode</label>
            </div>
        </li>

        <li class="nav-item px-1 d-flex justify-content-center align-items-center">
            <div class="dropdown">
                <a class="btn btn-sm btn-outline-dark dropdown-toggle" href="#" role="button" data-toggle="dropdown"
                   aria-expanded="false">
                    @if (auth('super_admin')->check())
                        <i class="fas fa-user pr-1"></i>{{ auth('super_admin')->user()->name }}
                    @else
                        <i class="fas fa-user pr-1"></i>{{ auth('web')->user()->fullname }}
                    @endif
                </a>
                <div class="dropdown-menu dark">
                    <form action="{{ route('admin.logout') }}" class="" method="post">
                        @csrf
                        <button class="btn btn-sm" type="submit"><i class="fas fa-sign-out-alt pr-3"></i>Logout</button>
                    </form>
                </div>
            </div>
        </li>
    </ul>
</nav>
