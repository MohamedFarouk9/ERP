<header>
    <div class="topbar d-flex align-items-center">
        <nav class="navbar navbar-expand">
            <div class="mobile-toggle-menu"><i class='bx bx-menu'></i></div>
            <div class="search-bar flex-grow-1">
                <div class="position-relative search-bar-box">
                    <span class="badge bg-light-primary text-primary px-3 py-2 fs-6">
                        <i class="bx bx-buildings me-1"></i>
                        {{ app(\Modules\Core\Services\TenantContext::class)->get()?->name ?? 'Enterprise ERP' }}
                    </span>
                </div>
            </div>
            <div class="top-menu ms-auto">
                <ul class="navbar-nav align-items-center">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('admin.dashboard') }}" title="Dashboard">
                            <i class='bx bx-home-circle'></i>
                        </a>
                    </li>
                </ul>
            </div>
            <div class="user-box dropdown">
                <a class="d-flex align-items-center nav-link dropdown-toggle dropdown-toggle-nocaret" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <img src="{{ asset('admin/assets/images/avatars/avatar-2.png') }}" class="user-img" alt="user avatar">
                    <div class="user-info ps-3">
                        <p class="user-name mb-0">{{ auth()->user()?->name ?? 'User' }}</p>
                        <p class="designattion mb-0">{{ auth()->user()?->email ?? '' }}</p>
                    </div>
                </a>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li>
                        <a class="dropdown-item" href="{{ route('admin.dashboard') }}">
                            <i class='bx bx-home-circle'></i><span>Dashboard</span>
                        </a>
                    </li>
                    @can('core.features.view')
                    <li>
                        <a class="dropdown-item" href="{{ route('admin.features.index') }}">
                            <i class='bx bx-slider-alt'></i><span>Module Flags</span>
                        </a>
                    </li>
                    @endcan
                    <li><div class="dropdown-divider mb-0"></div></li>
                    <li>
                        <form method="POST" action="{{ route('admin.logout') }}">
                            @csrf
                            <button type="submit" class="dropdown-item text-danger">
                                <i class="bx bx-log-out-circle"></i><span>Logout</span>
                            </button>
                        </form>
                    </li>
                </ul>
            </div>
        </nav>
    </div>
</header>
