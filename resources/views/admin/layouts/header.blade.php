@inject('featureManager', 'Modules\Core\Services\FeatureManager')
@php
    $isArabic = app()->getLocale() === 'ar';
@endphp
<header>
    <div class="topbar d-flex align-items-center">
        <nav class="navbar navbar-expand">
            <div class="mobile-toggle-menu"><i class='bx bx-menu'></i></div>

            <div class="search-bar flex-grow-1">
                <div class="d-flex align-items-center gap-2">
                    <span class="badge rounded-pill bg-light-primary text-primary px-3 py-2 font-13">
                        <i class="bx bx-buildings me-1 align-middle"></i>
                        {{ app(\Modules\Core\Services\TenantContext::class)->get()?->name ?? __('Enterprise ERP') }}
                    </span>
                    <span class="badge rounded-pill bg-light-success text-success px-2 py-1 font-12 d-none d-md-inline-block">
                        <i class="bx bxs-circle me-1" style="font-size:8px;"></i>{{ __('Active Workspace') }}
                    </span>
                </div>
            </div>

            <div class="top-menu ms-auto">
                <ul class="navbar-nav align-items-center">
                    <!-- Quick ERP Module Launcher -->
                    <li class="nav-item dropdown dropdown-large">
                        <a class="nav-link dropdown-toggle dropdown-toggle-nocaret" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false" title="{{ __('Quick Module Launcher') }}">
                            <i class='bx bx-category'></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-end">
                            <div class="row row-cols-3 g-3 p-3">
                                <div class="col text-center">
                                    <a href="{{ route('admin.dashboard') }}" class="text-decoration-none">
                                        <div class="app-box mx-auto bg-gradient-cosmic text-white"><i class='bx bx-line-chart'></i></div>
                                        <div class="app-title text-dark">{{ __('Executive') }}</div>
                                    </a>
                                </div>
                                @if($featureManager->isEnabled('sales'))
                                <div class="col text-center">
                                    <a href="{{ route('admin.orders.index') }}" class="text-decoration-none">
                                        <div class="app-box mx-auto bg-gradient-burning text-white"><i class='bx bx-cart'></i></div>
                                        <div class="app-title text-dark">{{ __('Sales') }}</div>
                                    </a>
                                </div>
                                @endif
                                @if($featureManager->isEnabled('inventory'))
                                <div class="col text-center">
                                    <a href="{{ route('admin.products.index') }}" class="text-decoration-none">
                                        <div class="app-box mx-auto bg-gradient-lush text-white"><i class='bx bx-package'></i></div>
                                        <div class="app-title text-dark">{{ __('Inventory') }}</div>
                                    </a>
                                </div>
                                @endif
                                @if($featureManager->isEnabled('accounting'))
                                <div class="col text-center">
                                    <a href="{{ route('admin.accounting.transactions') }}" class="text-decoration-none">
                                        <div class="app-box mx-auto bg-gradient-kyoto text-dark"><i class='bx bx-calculator'></i></div>
                                        <div class="app-title text-dark">{{ __('Finance') }}</div>
                                    </a>
                                </div>
                                @endif
                                @if($featureManager->isEnabled('procurement'))
                                <div class="col text-center">
                                    <a href="{{ route('admin.procurement.orders.index') }}" class="text-decoration-none">
                                        <div class="app-box mx-auto bg-gradient-blues text-dark"><i class='bx bx-shopping-bag'></i></div>
                                        <div class="app-title text-dark">{{ __('Purchasing') }}</div>
                                    </a>
                                </div>
                                @endif
                                <div class="col text-center">
                                    <a href="{{ route('admin.organization.departments') }}" class="text-decoration-none">
                                        <div class="app-box mx-auto bg-gradient-moonlit text-white"><i class='bx bx-group'></i></div>
                                        <div class="app-title text-dark">{{ __('HR & Teams') }}</div>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </li>

                    <!-- Language Switcher (EN <-> AR) -->
                    <li class="nav-item me-1">
                        <a class="btn btn-sm btn-outline-primary rounded-pill px-3 fw-semibold"
                           href="{{ route('locale.switch', $isArabic ? 'en' : 'ar') }}"
                           title="{{ __('Switch Language') }}">
                            <i class="bx bx-globe me-1 align-middle"></i>{{ $isArabic ? 'EN' : 'العربية' }}
                        </a>
                    </li>

                    <!-- Quick Dark/Light Toggle -->
                    <li class="nav-item">
                        <a class="nav-link" href="javascript:;" id="quickDarkToggle" title="{{ __('Toggle Dark / Light Mode') }}">
                            <i class='bx bx-moon'></i>
                        </a>
                    </li>
                </ul>
            </div>

            <div class="user-box dropdown">
                <a class="d-flex align-items-center nav-link dropdown-toggle dropdown-toggle-nocaret" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <img src="{{ asset('admin/assets/images/avatars/avatar-2.png') }}" class="user-img" alt="user avatar">
                    <div class="user-info ps-3">
                        <p class="user-name mb-0">{{ auth()->user()?->name ?? __('Administrator') }}</p>
                        <p class="designattion mb-0">{{ auth()->user()?->email ?? '' }}</p>
                    </div>
                </a>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li>
                        <a class="dropdown-item" href="{{ route('admin.dashboard') }}">
                            <i class='bx bx-home-circle'></i><span>{{ __('Executive Dashboard') }}</span>
                        </a>
                    </li>
                    @can('core.features.view')
                    <li>
                        <a class="dropdown-item" href="{{ route('admin.features.index') }}">
                            <i class='bx bx-slider-alt'></i><span>{{ __('Module Feature Flags') }}</span>
                        </a>
                    </li>
                    @endcan
                    @can('users.audit-logs.view')
                    <li>
                        <a class="dropdown-item" href="{{ route('admin.audit-logs.index') }}">
                            <i class='bx bx-history'></i><span>{{ __('Compliance Audit Trail') }}</span>
                        </a>
                    </li>
                    @endcan
                    <li><div class="dropdown-divider mb-0"></div></li>
                    <li>
                        <form method="POST" action="{{ route('admin.logout') }}">
                            @csrf
                            <button type="submit" class="dropdown-item text-danger">
                                <i class="bx bx-log-out-circle"></i><span>{{ __('Sign Out') }}</span>
                            </button>
                        </form>
                    </li>
                </ul>
            </div>
        </nav>
    </div>
</header>
<script>
document.addEventListener('DOMContentLoaded', function() {
    var btn = document.getElementById('quickDarkToggle');
    if (btn) {
        btn.addEventListener('click', function() {
            var html = document.documentElement;
            if (html.classList.contains('dark-theme')) {
                html.classList.remove('dark-theme');
                html.classList.add('light-theme');
            } else {
                html.classList.remove('light-theme', 'semi-dark');
                html.classList.add('dark-theme');
            }
            localStorage.setItem('erp_theme_class', html.className);
        });
    }
});
</script>
