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
        <!-- Collapsible Dashboards Parent -->
        <li>
            <a href="javascript:;" class="has-arrow">
                <div class="parent-icon"><i class='bx bx-home-circle'></i></div>
                <div class="menu-title">Dashboards</div>
            </a>
            <ul>
                <li><a href="{{ route('admin.dashboard') }}"><i class="bx bx-right-arrow-alt"></i>Executive Overview</a></li>
                @if($featureManager->isEnabled('accounting'))
                    @can('accounting.accounts.view')
                        <li><a href="{{ route('admin.accounting.dashboard') }}"><i class="bx bx-right-arrow-alt"></i>Finance Analytics</a></li>
                    @endcan
                @endif
                @if($featureManager->isEnabled('inventory'))
                    @can('inventory.products.view')
                        <li><a href="{{ route('admin.inventory.dashboard') }}"><i class="bx bx-right-arrow-alt"></i>Inventory Analytics</a></li>
                    @endcan
                @endif
                @if($featureManager->isEnabled('sales'))
                    @can('sales.orders.view')
                        <li><a href="{{ route('admin.sales.dashboard') }}"><i class="bx bx-right-arrow-alt"></i>Sales Analytics</a></li>
                    @endcan
                @endif
                @if($featureManager->isEnabled('procurement'))
                    @can('procurement.orders.view')
                        <li><a href="{{ route('admin.procurement.dashboard') }}"><i class="bx bx-right-arrow-alt"></i>Procurement Analytics</a></li>
                    @endcan
                @endif
                @if($featureManager->isEnabled('hrm'))
                    @can('hrm.employees.view')
                        <li><a href="{{ route('admin.hrm.dashboard') }}"><i class="bx bx-right-arrow-alt"></i>HRM Analytics</a></li>
                    @endcan
                @endif
            </ul>
        </li>

        <!-- Sales & Billing (Flat Section) -->
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
                        <div class="parent-icon"><i class='bx bx-cart'></i></div>
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

        <!-- Inventory & Stock (Flat Section) -->
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

        <!-- Procurement & AP (Flat Section) -->
        @if($featureManager->isEnabled('procurement') && (auth()->user()?->can('procurement.suppliers.view') || auth()->user()?->can('procurement.orders.view') || auth()->user()?->can('procurement.bills.view')))
            <li class="menu-label">Procurement & AP</li>
            @can('procurement.suppliers.view')
                <li>
                    <a href="{{ route('admin.procurement.suppliers.index') }}">
                        <div class="parent-icon"><i class='bx bx-buildings'></i></div>
                        <div class="menu-title">Suppliers</div>
                    </a>
                </li>
            @endcan
            @can('procurement.orders.view')
                <li>
                    <a href="{{ route('admin.procurement.orders.index') }}">
                        <div class="parent-icon"><i class='bx bx-shopping-bag'></i></div>
                        <div class="menu-title">Purchase Orders</div>
                    </a>
                </li>
            @endcan
            @can('procurement.bills.view')
                <li>
                    <a href="{{ route('admin.procurement.bills.index') }}">
                        <div class="parent-icon"><i class='bx bx-file'></i></div>
                        <div class="menu-title">Vendor Bills</div>
                    </a>
                </li>
            @endcan
        @endif

        <!-- Finance & Ledger (Flat Section) -->
        @if($featureManager->isEnabled('accounting') && (auth()->user()?->can('accounting.accounts.view') || auth()->user()?->can('accounting.transactions.view')))
            <li class="menu-label">Finance & Ledger</li>
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
                        <div class="menu-title">Ledger Transactions</div>
                    </a>
                </li>
            @endcan
        @endif

        <!-- Payments & Treasury (Flat Section) -->
        @if($featureManager->isEnabled('payments') && (auth()->user()?->can('payments.transactions.view') || auth()->user()?->can('payments.refunds.view')))
            <li class="menu-label">Payments & Treasury</li>
            @can('payments.transactions.view')
                <li>
                    <a href="{{ route('admin.payments.index') }}">
                        <div class="parent-icon"><i class='bx bx-wallet'></i></div>
                        <div class="menu-title">Payments & Methods</div>
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

        <!-- Organization & HR (Flat Section) -->
        @if(auth()->user()?->can('users.departments.view') || auth()->user()?->can('users.teams.view') || ($featureManager->isEnabled('hrm') && auth()->user()?->can('hrm.employees.view')))
            <li class="menu-label">Organization & HR</li>
            @if($featureManager->isEnabled('hrm'))
                @can('hrm.employees.view')
                    <li>
                        <a href="{{ route('admin.hrm.employees.index') }}">
                            <div class="parent-icon"><i class='bx bx-user-voice'></i></div>
                            <div class="menu-title">Employees</div>
                        </a>
                    </li>
                @endcan
                @can('hrm.attendance.view')
                    <li>
                        <a href="{{ route('admin.hrm.attendance.index') }}">
                            <div class="parent-icon"><i class='bx bx-time-five'></i></div>
                            <div class="menu-title">Attendance</div>
                        </a>
                    </li>
                @endcan
                @can('hrm.leaves.view')
                    <li>
                        <a href="{{ route('admin.hrm.leaves.index') }}">
                            <div class="parent-icon"><i class='bx bx-calendar-check'></i></div>
                            <div class="menu-title">Leave Requests</div>
                        </a>
                    </li>
                @endcan
                @can('hrm.payroll.view')
                    <li>
                        <a href="{{ route('admin.hrm.payroll.index') }}">
                            <div class="parent-icon"><i class='bx bx-money'></i></div>
                            <div class="menu-title">Payroll Runs</div>
                        </a>
                    </li>
                @endcan
            @endif
            @can('users.departments.view')
                <li>
                    <a href="{{ route('admin.organization.staff') }}">
                        <div class="parent-icon"><i class='bx bx-id-card'></i></div>
                        <div class="menu-title">Staff Directory</div>
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.organization.departments') }}">
                        <div class="parent-icon"><i class='bx bx-sitemap'></i></div>
                        <div class="menu-title">Departments</div>
                    </a>
                </li>
            @endcan
            @can('users.teams.view')
                <li>
                    <a href="{{ route('admin.organization.teams') }}">
                        <div class="parent-icon"><i class='bx bx-group'></i></div>
                        <div class="menu-title">Teams & Leads</div>
                    </a>
                </li>
            @endcan
        @endif

        <!-- System & Security (Flat Section) -->
        <li class="menu-label">System & Security</li>
        @can('core.features.view')
            <li>
                <a href="{{ route('admin.features.index') }}">
                    <div class="parent-icon"><i class='bx bx-toggle-right'></i></div>
                    <div class="menu-title">Module Feature Flags</div>
                </a>
            </li>
        @endcan
        @can('users.roles.view')
            <li>
                <a href="{{ route('admin.roles.index') }}">
                    <div class="parent-icon"><i class='bx bx-shield-alt-2'></i></div>
                    <div class="menu-title">Roles & RBAC</div>
                </a>
            </li>
        @endcan
        @can('users.permissions.view')
            <li>
                <a href="{{ route('admin.permissions.index') }}">
                    <div class="parent-icon"><i class='bx bx-key'></i></div>
                    <div class="menu-title">Permissions Matrix</div>
                </a>
            </li>
        @endcan
        @can('users.audit-logs.view')
            <li>
                <a href="{{ route('admin.audit-logs.index') }}">
                    <div class="parent-icon"><i class='bx bx-history'></i></div>
                    <div class="menu-title">Compliance Audit Trail</div>
                </a>
            </li>
        @endcan
    </ul>
</div>
