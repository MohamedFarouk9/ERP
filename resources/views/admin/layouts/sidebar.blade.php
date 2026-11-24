@inject('featureManager', 'Modules\Core\Services\FeatureManager')

<div class="sidebar-wrapper" data-simplebar="true">
    <div class="sidebar-header">
        <div>
            <img src="{{ asset('admin/assets/images/logo-icon.png') }}" class="logo-icon" alt="logo icon">
        </div>
        <div>
            <h4 class="logo-text">Rukada ERP</h4>
        </div>
        <div class="toggle-icon ms-auto"><i class='bx bx-arrow-to-left'></i></div>
    </div>

    <!--navigation-->
    <ul class="metismenu" id="menu">
        <!-- 1. ANALYTICS DASHBOARDS DROPDOWN -->
        <li class="{{ request()->is('admin/dashboard') || request()->is('admin/*/dashboard') ? 'mm-active' : '' }}">
            <a href="javascript:;" class="has-arrow">
                <div class="parent-icon"><i class='bx bx-home-circle'></i></div>
                <div class="menu-title">Dashboards</div>
            </a>
            <ul>
                <li class="{{ request()->routeIs('admin.dashboard') ? 'mm-active' : '' }}">
                    <a href="{{ route('admin.dashboard') }}"><i class="bx bx-right-arrow-alt"></i>Executive Overview</a>
                </li>
                @if($featureManager->isEnabled('accounting'))
                    @can('accounting.accounts.view')
                        <li class="{{ request()->routeIs('admin.accounting.dashboard') ? 'mm-active' : '' }}">
                            <a href="{{ route('admin.accounting.dashboard') }}"><i class="bx bx-right-arrow-alt"></i>Finance Analytics</a>
                        </li>
                    @endcan
                @endif
                @if($featureManager->isEnabled('inventory'))
                    @can('inventory.products.view')
                        <li class="{{ request()->routeIs('admin.inventory.dashboard') ? 'mm-active' : '' }}">
                            <a href="{{ route('admin.inventory.dashboard') }}"><i class="bx bx-right-arrow-alt"></i>Inventory Analytics</a>
                        </li>
                    @endcan
                @endif
                @if($featureManager->isEnabled('sales'))
                    @can('sales.orders.view')
                        <li class="{{ request()->routeIs('admin.sales.dashboard') ? 'mm-active' : '' }}">
                            <a href="{{ route('admin.sales.dashboard') }}"><i class="bx bx-right-arrow-alt"></i>Sales Analytics</a>
                        </li>
                    @endcan
                @endif
                @if($featureManager->isEnabled('procurement'))
                    @can('procurement.orders.view')
                        <li class="{{ request()->routeIs('admin.procurement.dashboard') ? 'mm-active' : '' }}">
                            <a href="{{ route('admin.procurement.dashboard') }}"><i class="bx bx-right-arrow-alt"></i>Procurement Analytics</a>
                        </li>
                    @endcan
                @endif
            </ul>
        </li>

        <li class="menu-label">Core ERP Modules</li>

        <!-- 2. SALES & COMMERCIAL DROPDOWN -->
        @if($featureManager->isEnabled('sales') && (auth()->user()?->can('sales.customers.view') || auth()->user()?->can('sales.orders.view') || auth()->user()?->can('sales.invoices.view')))
            <li class="{{ request()->is('admin/customers*') || request()->is('admin/orders*') || request()->is('admin/invoices*') ? 'mm-active' : '' }}">
                <a href="javascript:;" class="has-arrow">
                    <div class="parent-icon"><i class='bx bx-cart'></i></div>
                    <div class="menu-title">Sales & Billing</div>
                </a>
                <ul>
                    @can('sales.customers.view')
                        <li class="{{ request()->routeIs('admin.customers.*') ? 'mm-active' : '' }}">
                            <a href="{{ route('admin.customers.index') }}"><i class="bx bx-right-arrow-alt"></i>Customers</a>
                        </li>
                    @endcan
                    @can('sales.orders.view')
                        <li class="{{ request()->routeIs('admin.orders.*') ? 'mm-active' : '' }}">
                            <a href="{{ route('admin.orders.index') }}"><i class="bx bx-right-arrow-alt"></i>Sales Orders</a>
                        </li>
                    @endcan
                    @can('sales.invoices.view')
                        <li class="{{ request()->routeIs('admin.invoices.*') ? 'mm-active' : '' }}">
                            <a href="{{ route('admin.invoices.index') }}"><i class="bx bx-right-arrow-alt"></i>Invoices</a>
                        </li>
                    @endcan
                </ul>
            </li>
        @endif

        <!-- 3. INVENTORY & WAREHOUSING DROPDOWN -->
        @if($featureManager->isEnabled('inventory') && (auth()->user()?->can('inventory.products.view') || auth()->user()?->can('inventory.categories.view') || auth()->user()?->can('inventory.warehouses.view')))
            <li class="{{ request()->is('admin/products*') || request()->is('admin/categories*') || request()->is('admin/warehouses*') ? 'mm-active' : '' }}">
                <a href="javascript:;" class="has-arrow">
                    <div class="parent-icon"><i class='bx bx-package'></i></div>
                    <div class="menu-title">Inventory & Stock</div>
                </a>
                <ul>
                    @can('inventory.products.view')
                        <li class="{{ request()->routeIs('admin.products.*') ? 'mm-active' : '' }}">
                            <a href="{{ route('admin.products.index') }}"><i class="bx bx-right-arrow-alt"></i>Products Catalog</a>
                        </li>
                    @endcan
                    @can('inventory.categories.view')
                        <li class="{{ request()->routeIs('admin.categories.*') ? 'mm-active' : '' }}">
                            <a href="{{ route('admin.categories.index') }}"><i class="bx bx-right-arrow-alt"></i>Categories</a>
                        </li>
                    @endcan
                    @can('inventory.warehouses.view')
                        <li class="{{ request()->routeIs('admin.warehouses.*') ? 'mm-active' : '' }}">
                            <a href="{{ route('admin.warehouses.index') }}"><i class="bx bx-right-arrow-alt"></i>Warehouses</a>
                        </li>
                    @endcan
                </ul>
            </li>
        @endif

        <!-- 4. PROCUREMENT & PURCHASING DROPDOWN -->
        @if($featureManager->isEnabled('procurement') && (auth()->user()?->can('procurement.suppliers.view') || auth()->user()?->can('procurement.orders.view') || auth()->user()?->can('procurement.bills.view')))
            <li class="{{ request()->is('admin/procurement/suppliers*') || request()->is('admin/procurement/purchase-orders*') || request()->is('admin/procurement/vendor-bills*') ? 'mm-active' : '' }}">
                <a href="javascript:;" class="has-arrow">
                    <div class="parent-icon"><i class='bx bx-shopping-bag'></i></div>
                    <div class="menu-title">Procurement & AP</div>
                </a>
                <ul>
                    @can('procurement.suppliers.view')
                        <li class="{{ request()->routeIs('admin.procurement.suppliers.*') ? 'mm-active' : '' }}">
                            <a href="{{ route('admin.procurement.suppliers.index') }}"><i class="bx bx-right-arrow-alt"></i>Suppliers</a>
                        </li>
                    @endcan
                    @can('procurement.orders.view')
                        <li class="{{ request()->routeIs('admin.procurement.orders.*') ? 'mm-active' : '' }}">
                            <a href="{{ route('admin.procurement.orders.index') }}"><i class="bx bx-right-arrow-alt"></i>Purchase Orders</a>
                        </li>
                    @endcan
                    @can('procurement.bills.view')
                        <li class="{{ request()->routeIs('admin.procurement.bills.*') ? 'mm-active' : '' }}">
                            <a href="{{ route('admin.procurement.bills.index') }}"><i class="bx bx-right-arrow-alt"></i>Vendor Bills</a>
                        </li>
                    @endcan
                </ul>
            </li>
        @endif

        <!-- 5. FINANCE & GENERAL LEDGER DROPDOWN -->
        @if($featureManager->isEnabled('accounting') && (auth()->user()?->can('accounting.accounts.view') || auth()->user()?->can('accounting.transactions.view')))
            <li class="{{ request()->is('admin/accounting/accounts*') || request()->is('admin/accounting/transactions*') ? 'mm-active' : '' }}">
                <a href="javascript:;" class="has-arrow">
                    <div class="parent-icon"><i class='bx bx-calculator'></i></div>
                    <div class="menu-title">Finance & Ledger</div>
                </a>
                <ul>
                    @can('accounting.accounts.view')
                        <li class="{{ request()->routeIs('admin.accounting.accounts*') ? 'mm-active' : '' }}">
                            <a href="{{ route('admin.accounting.accounts') }}"><i class="bx bx-right-arrow-alt"></i>Chart of Accounts</a>
                        </li>
                    @endcan
                    @can('accounting.transactions.view')
                        <li class="{{ request()->routeIs('admin.accounting.transactions*') ? 'mm-active' : '' }}">
                            <a href="{{ route('admin.accounting.transactions') }}"><i class="bx bx-right-arrow-alt"></i>Ledger Transactions</a>
                        </li>
                    @endcan
                </ul>
            </li>
        @endif

        <!-- 6. PAYMENTS & TREASURY DROPDOWN -->
        @if($featureManager->isEnabled('payments') && (auth()->user()?->can('payments.transactions.view') || auth()->user()?->can('payments.refunds.view')))
            <li class="{{ request()->is('admin/payments*') ? 'mm-active' : '' }}">
                <a href="javascript:;" class="has-arrow">
                    <div class="parent-icon"><i class='bx bx-wallet'></i></div>
                    <div class="menu-title">Payments & Treasury</div>
                </a>
                <ul>
                    @can('payments.transactions.view')
                        <li class="{{ request()->routeIs('admin.payments.index') ? 'mm-active' : '' }}">
                            <a href="{{ route('admin.payments.index') }}"><i class="bx bx-right-arrow-alt"></i>Payments & Methods</a>
                        </li>
                    @endcan
                    @can('payments.refunds.view')
                        <li class="{{ request()->routeIs('admin.payments.refunds*') ? 'mm-active' : '' }}">
                            <a href="{{ route('admin.payments.refunds') }}"><i class="bx bx-right-arrow-alt"></i>Refunds</a>
                        </li>
                    @endcan
                </ul>
            </li>
        @endif

        <li class="menu-label">People & Governance</li>

        <!-- 7. ORGANIZATION & HR DROPDOWN -->
        @if(auth()->user()?->can('users.departments.view') || auth()->user()?->can('users.teams.view'))
            <li class="{{ request()->is('admin/organization*') ? 'mm-active' : '' }}">
                <a href="javascript:;" class="has-arrow">
                    <div class="parent-icon"><i class='bx bx-group'></i></div>
                    <div class="menu-title">Organization & HR</div>
                </a>
                <ul>
                    @can('users.departments.view')
                        <li class="{{ request()->routeIs('admin.organization.staff*') ? 'mm-active' : '' }}">
                            <a href="{{ route('admin.organization.staff') }}"><i class="bx bx-right-arrow-alt"></i>Staff Directory</a>
                        </li>
                        <li class="{{ request()->routeIs('admin.organization.departments*') ? 'mm-active' : '' }}">
                            <a href="{{ route('admin.organization.departments') }}"><i class="bx bx-right-arrow-alt"></i>Departments</a>
                        </li>
                    @endcan
                    @can('users.teams.view')
                        <li class="{{ request()->routeIs('admin.organization.teams*') ? 'mm-active' : '' }}">
                            <a href="{{ route('admin.organization.teams') }}"><i class="bx bx-right-arrow-alt"></i>Teams & Leads</a>
                        </li>
                    @endcan
                </ul>
            </li>
        @endif

        <!-- 8. SYSTEM & SECURITY DROPDOWN -->
        <li class="{{ request()->is('admin/features*') || request()->is('admin/roles*') || request()->is('admin/permissions*') || request()->is('admin/audit-logs*') ? 'mm-active' : '' }}">
            <a href="javascript:;" class="has-arrow">
                <div class="parent-icon"><i class='bx bx-shield-quarter'></i></div>
                <div class="menu-title">System & Security</div>
            </a>
            <ul>
                @can('core.features.view')
                    <li class="{{ request()->routeIs('admin.features.*') ? 'mm-active' : '' }}">
                        <a href="{{ route('admin.features.index') }}"><i class="bx bx-right-arrow-alt"></i>Module Feature Flags</a>
                    </li>
                @endcan
                @can('users.roles.view')
                    <li class="{{ request()->routeIs('admin.roles.*') ? 'mm-active' : '' }}">
                        <a href="{{ route('admin.roles.index') }}"><i class="bx bx-right-arrow-alt"></i>Roles & RBAC</a>
                    </li>
                @endcan
                @can('users.permissions.view')
                    <li class="{{ request()->routeIs('admin.permissions.*') ? 'mm-active' : '' }}">
                        <a href="{{ route('admin.permissions.index') }}"><i class="bx bx-right-arrow-alt"></i>Permissions Matrix</a>
                    </li>
                @endcan
                @can('users.audit-logs.view')
                    <li class="{{ request()->routeIs('admin.audit-logs.*') ? 'mm-active' : '' }}">
                        <a href="{{ route('admin.audit-logs.index') }}"><i class="bx bx-right-arrow-alt"></i>Compliance Audit Trail</a>
                    </li>
                @endcan
            </ul>
        </li>
    </ul>
</div>
