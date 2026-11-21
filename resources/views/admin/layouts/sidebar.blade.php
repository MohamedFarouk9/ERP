@inject('featureManager', 'Modules\Core\Services\FeatureManager')

<div class="sidebar-wrapper" data-simplebar="true">
    <div class="sidebar-header">
        <div>
            <img src="{{ asset('admin/assets/images/logo-icon.png') }}" class="logo-icon" alt="logo icon">
        </div>
        <div>
            <h4 class="logo-text">ERP Core</h4>
        </div>
        <div class="toggle-icon ms-auto"><i class='bx bx-arrow-to-left'></i></div>
    </div>

    <ul class="metismenu" id="menu">
        <!-- DASHBOARDS -->
        <li>
            <a href="javascript:;" class="has-arrow">
                <div class="parent-icon"><i class='bx bx-home-circle'></i></div>
                <div class="menu-title">Dashboards</div>
            </a>
            <ul>
                <li><a href="{{ route('admin.dashboard') }}"><i class="bx bx-right-arrow-alt"></i>Executive Overview</a></li>
                @if($featureManager->isEnabled('accounting'))
                    @can('accounting.accounts.view')
                        <li><a href="{{ route('admin.accounting.dashboard') }}"><i class="bx bx-right-arrow-alt"></i>Finance Dashboard</a></li>
                    @endcan
                @endif
                @if($featureManager->isEnabled('inventory'))
                    @can('inventory.products.view')
                        <li><a href="{{ route('admin.inventory.dashboard') }}"><i class="bx bx-right-arrow-alt"></i>Inventory Dashboard</a></li>
                    @endcan
                @endif
                @if($featureManager->isEnabled('sales'))
                    @can('sales.orders.view')
                        <li><a href="{{ route('admin.sales.dashboard') }}"><i class="bx bx-right-arrow-alt"></i>Sales Dashboard</a></li>
                    @endcan
                @endif
            </ul>
        </li>

        <!-- ACCOUNTING MODULE -->
        @if($featureManager->isEnabled('accounting') && (auth()->user()?->can('accounting.accounts.view') || auth()->user()?->can('accounting.transactions.view')))
            <li class="menu-label">Accounting & Finance</li>
            @can('accounting.accounts.view')
                <li>
                    <a href="{{ route('admin.accounting.accounts') }}">
                        <div class="parent-icon"><i class='bx bx-book-open'></i></div>
                        <div class="menu-title">Chart of Accounts</div>
                    </a>
                </li>
            @endcan
            @can('accounting.transactions.view')
                <li>
                    <a href="{{ route('admin.accounting.transactions') }}">
                        <div class="parent-icon"><i class='bx bx-transfer'></i></div>
                        <div class="menu-title">Transactions</div>
                    </a>
                </li>
            @endcan
        @endif

        <!-- INVENTORY MODULE -->
        @if($featureManager->isEnabled('inventory') && (auth()->user()?->can('inventory.products.view') || auth()->user()?->can('inventory.categories.view') || auth()->user()?->can('inventory.warehouses.view')))
            <li class="menu-label">Inventory & Stock</li>
            @can('inventory.products.view')
                <li>
                    <a href="{{ route('admin.products.index') }}">
                        <div class="parent-icon"><i class='bx bx-package'></i></div>
                        <div class="menu-title">Products</div>
                    </a>
                </li>
            @endcan
            @can('inventory.categories.view')
                <li>
                    <a href="{{ route('admin.categories.index') }}">
                        <div class="parent-icon"><i class='bx bx-list-ul'></i></div>
                        <div class="menu-title">Categories</div>
                    </a>
                </li>
            @endcan
            @can('inventory.warehouses.view')
                <li>
                    <a href="{{ route('admin.warehouses.index') }}">
                        <div class="parent-icon"><i class='bx bx-store'></i></div>
                        <div class="menu-title">Warehouses</div>
                    </a>
                </li>
            @endcan
        @endif

        <!-- SALES MODULE -->
        @if($featureManager->isEnabled('sales') && (auth()->user()?->can('sales.customers.view') || auth()->user()?->can('sales.orders.view') || auth()->user()?->can('sales.invoices.view')))
            <li class="menu-label">Sales & Billing</li>
            @can('sales.customers.view')
                <li>
                    <a href="{{ route('admin.customers.index') }}">
                        <div class="parent-icon"><i class='bx bx-user-pin'></i></div>
                        <div class="menu-title">Customers</div>
                    </a>
                </li>
            @endcan
            @can('sales.orders.view')
                <li>
                    <a href="{{ route('admin.orders.index') }}">
                        <div class="parent-icon"><i class='bx bx-shopping-bag'></i></div>
                        <div class="menu-title">Sales Orders</div>
                    </a>
                </li>
            @endcan
            @can('sales.invoices.view')
                <li>
                    <a href="{{ route('admin.invoices.index') }}">
                        <div class="parent-icon"><i class='bx bx-receipt'></i></div>
                        <div class="menu-title">Invoices</div>
                    </a>
                </li>
            @endcan
        @endif

        <!-- PAYMENTS MODULE -->
        @if($featureManager->isEnabled('payments') && (auth()->user()?->can('payments.transactions.view') || auth()->user()?->can('payments.refunds.view')))
            <li class="menu-label">Payments</li>
            @can('payments.transactions.view')
                <li>
                    <a href="{{ route('admin.payments.index') }}">
                        <div class="parent-icon"><i class='bx bx-credit-card'></i></div>
                        <div class="menu-title">Payments</div>
                    </a>
                </li>
            @endcan
            @can('payments.refunds.view')
                <li>
                    <a href="{{ route('admin.payments.refunds') }}">
                        <div class="parent-icon"><i class='bx bx-revision'></i></div>
                        <div class="menu-title">Refunds</div>
                    </a>
                </li>
            @endcan
        @endif

        <!-- ADMIN & SYSTEM SETTINGS -->
        <li class="menu-label">System & Security</li>
        @can('core.features.view')
            <li>
                <a href="{{ route('admin.features.index') }}">
                    <div class="parent-icon"><i class='bx bx-toggle-right'></i></div>
                    <div class="menu-title">Module Flags</div>
                </a>
            </li>
        @endcan
        @if(auth()->user()?->can('users.roles.view') || auth()->user()?->can('users.permissions.view'))
            <li>
                <a href="javascript:;" class="has-arrow">
                    <div class="parent-icon"><i class='bx bx-shield-alt-2'></i></div>
                    <div class="menu-title">Access Control</div>
                </a>
                <ul>
                    @can('users.roles.view')
                        <li><a href="{{ route('admin.roles.index') }}"><i class="bx bx-right-arrow-alt"></i>Roles</a></li>
                    @endcan
                    @can('users.permissions.view')
                        <li><a href="{{ route('admin.permissions.index') }}"><i class="bx bx-right-arrow-alt"></i>Permissions</a></li>
                    @endcan
                </ul>
            </li>
        @endif
        @can('users.audit-logs.view')
            <li>
                <a href="{{ route('admin.audit-logs.index') }}">
                    <div class="parent-icon"><i class='bx bx-history'></i></div>
                    <div class="menu-title">Audit Logs</div>
                </a>
            </li>
        @endcan
    </ul>
</div>
