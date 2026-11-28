@inject('featureManager', 'Modules\Core\Services\FeatureManager')
@php
    $subArrow = app()->getLocale() === 'ar' ? 'bx-left-arrow-alt' : 'bx-right-arrow-alt';
@endphp

<div class="sidebar-wrapper" data-simplebar="true">
    <div class="sidebar-header">
        <div>
            <img src="{{ asset('admin/assets/images/logo-icon.png') }}" class="logo-icon" alt="logo icon">
        </div>
        <div>
            <h4 class="logo-text">{{ __('Rukada ERP') }}</h4>
        </div>
        <div class="toggle-icon ms-auto"><i class='bx bx-arrow-to-left'></i></div>
    </div>

    <!--navigation-->
    <ul class="metismenu" id="menu">
        <!-- Collapsible Dashboards Parent -->
        <li>
            <a href="javascript:;" class="has-arrow">
                <div class="parent-icon"><i class='bx bx-home-circle'></i></div>
                <div class="menu-title">{{ __('Dashboards') }}</div>
            </a>
            <ul>
                <li><a href="{{ route('admin.dashboard') }}"><i class="bx {{ $subArrow }}"></i>{{ __('Executive Overview') }}</a></li>
                @if($featureManager->isEnabled('accounting'))
                    @can('accounting.accounts.view')
                        <li><a href="{{ route('admin.accounting.dashboard') }}"><i class="bx {{ $subArrow }}"></i>{{ __('Finance Analytics') }}</a></li>
                    @endcan
                @endif
                @if($featureManager->isEnabled('inventory'))
                    @can('inventory.products.view')
                        <li><a href="{{ route('admin.inventory.dashboard') }}"><i class="bx {{ $subArrow }}"></i>{{ __('Inventory Analytics') }}</a></li>
                    @endcan
                @endif
                @if($featureManager->isEnabled('sales'))
                    @can('sales.orders.view')
                        <li><a href="{{ route('admin.sales.dashboard') }}"><i class="bx {{ $subArrow }}"></i>{{ __('Sales Analytics') }}</a></li>
                    @endcan
                @endif
                @if($featureManager->isEnabled('procurement'))
                    @can('procurement.orders.view')
                        <li><a href="{{ route('admin.procurement.dashboard') }}"><i class="bx {{ $subArrow }}"></i>{{ __('Procurement Analytics') }}</a></li>
                    @endcan
                @endif
                @if($featureManager->isEnabled('hrm'))
                    @can('hrm.employees.view')
                        <li><a href="{{ route('admin.hrm.dashboard') }}"><i class="bx {{ $subArrow }}"></i>{{ __('HRM Analytics') }}</a></li>
                    @endcan
                @endif
                @if($featureManager->isEnabled('pos'))
                    @can('pos.terminal.view')
                        <li><a href="{{ route('admin.pos.dashboard') }}"><i class="bx {{ $subArrow }}"></i>{{ __('POS Analytics') }}</a></li>
                    @endcan
                @endif
                @if($featureManager->isEnabled('crm'))
                    @can('crm.leads.view')
                        <li><a href="{{ route('admin.crm.dashboard') }}"><i class="bx {{ $subArrow }}"></i>{{ __('CRM Analytics') }}</a></li>
                    @endcan
                @endif
            </ul>
        </li>

        <!-- Sales & Billing (Flat Section) -->
        @if($featureManager->isEnabled('sales') && (auth()->user()?->can('sales.customers.view') || auth()->user()?->can('sales.orders.view') || auth()->user()?->can('sales.invoices.view')))
            <li class="menu-label">{{ __('Sales & Billing') }}</li>
            @can('sales.customers.view')
                <li>
                    <a href="{{ route('admin.customers.index') }}">
                        <div class="parent-icon"><i class='bx bx-user-pin'></i></div>
                        <div class="menu-title">{{ __('Customers') }}</div>
                    </a>
                </li>
            @endcan
            @can('sales.orders.view')
                <li>
                    <a href="{{ route('admin.orders.index') }}">
                        <div class="parent-icon"><i class='bx bx-cart'></i></div>
                        <div class="menu-title">{{ __('Sales Orders') }}</div>
                    </a>
                </li>
            @endcan
            @can('sales.invoices.view')
                <li>
                    <a href="{{ route('admin.invoices.index') }}">
                        <div class="parent-icon"><i class='bx bx-receipt'></i></div>
                        <div class="menu-title">{{ __('Invoices') }}</div>
                    </a>
                </li>
            @endcan
        @endif

        @if($featureManager->isEnabled('pos'))
            <!-- Point of Sale (Flat Section) -->
            <li class="menu-label">{{ __('Point of Sale') }}</li>
            @can('pos.terminal.view')
                <li>
                    <a href="{{ route('admin.pos.terminal') }}">
                        <div class="parent-icon"><i class='bx bx-desktop'></i></div>
                        <div class="menu-title">{{ __('POS Terminal') }}</div>
                    </a>
                </li>
            @endcan
            @can('pos.shifts.view')
                <li>
                    <a href="{{ route('admin.pos.shifts.index') }}">
                        <div class="parent-icon"><i class='bx bx-time-five'></i></div>
                        <div class="menu-title">{{ __('Shifts & Sales') }}</div>
                    </a>
                </li>
            @endcan
        @endif

        @if($featureManager->isEnabled('crm'))
            <!-- CRM (Flat Section) -->
            <li class="menu-label">{{ __('CRM') }}</li>
            @can('crm.leads.view')
                <li>
                    <a href="{{ route('admin.crm.leads.index') }}">
                        <div class="parent-icon"><i class='bx bx-user-voice'></i></div>
                        <div class="menu-title">{{ __('Leads') }}</div>
                    </a>
                </li>
            @endcan
            @can('crm.deals.view')
                <li>
                    <a href="{{ route('admin.crm.deals.index') }}">
                        <div class="parent-icon"><i class='bx bx-briefcase'></i></div>
                        <div class="menu-title">{{ __('Deals Pipeline') }}</div>
                    </a>
                </li>
            @endcan
            @can('crm.activities.view')
                <li>
                    <a href="{{ route('admin.crm.activities.index') }}">
                        <div class="parent-icon"><i class='bx bx-calendar-check'></i></div>
                        <div class="menu-title">{{ __('Activities') }}</div>
                    </a>
                </li>
            @endcan
        @endif

        <!-- Inventory & Stock (Flat Section) -->
        @if($featureManager->isEnabled('inventory') && (auth()->user()?->can('inventory.products.view') || auth()->user()?->can('inventory.categories.view') || auth()->user()?->can('inventory.warehouses.view')))
            <li class="menu-label">{{ __('Inventory & Stock') }}</li>
            @can('inventory.products.view')
                <li>
                    <a href="{{ route('admin.products.index') }}">
                        <div class="parent-icon"><i class='bx bx-package'></i></div>
                        <div class="menu-title">{{ __('Products') }}</div>
                    </a>
                </li>
            @endcan
            @can('inventory.categories.view')
                <li>
                    <a href="{{ route('admin.categories.index') }}">
                        <div class="parent-icon"><i class='bx bx-list-ul'></i></div>
                        <div class="menu-title">{{ __('Categories') }}</div>
                    </a>
                </li>
            @endcan
            @can('inventory.warehouses.view')
                <li>
                    <a href="{{ route('admin.warehouses.index') }}">
                        <div class="parent-icon"><i class='bx bx-store'></i></div>
                        <div class="menu-title">{{ __('Warehouses') }}</div>
                    </a>
                </li>
            @endcan
        @endif

        <!-- Procurement & AP (Flat Section) -->
        @if($featureManager->isEnabled('procurement') && (auth()->user()?->can('procurement.suppliers.view') || auth()->user()?->can('procurement.orders.view') || auth()->user()?->can('procurement.bills.view')))
            <li class="menu-label">{{ __('Procurement & AP') }}</li>
            @can('procurement.suppliers.view')
                <li>
                    <a href="{{ route('admin.procurement.suppliers.index') }}">
                        <div class="parent-icon"><i class='bx bx-buildings'></i></div>
                        <div class="menu-title">{{ __('Suppliers') }}</div>
                    </a>
                </li>
            @endcan
            @can('procurement.orders.view')
                <li>
                    <a href="{{ route('admin.procurement.orders.index') }}">
                        <div class="parent-icon"><i class='bx bx-shopping-bag'></i></div>
                        <div class="menu-title">{{ __('Purchase Orders') }}</div>
                    </a>
                </li>
            @endcan
            @can('procurement.bills.view')
                <li>
                    <a href="{{ route('admin.procurement.bills.index') }}">
                        <div class="parent-icon"><i class='bx bx-file'></i></div>
                        <div class="menu-title">{{ __('Vendor Bills') }}</div>
                    </a>
                </li>
            @endcan
        @endif

        <!-- Finance (Flat Section) -->
        @if($featureManager->isEnabled('finance') && (auth()->user()?->can('finance.expenses.view') || auth()->user()?->can('finance.budgets.view') || auth()->user()?->can('finance.reports.view')))
            <li class="menu-label">{{ __('Finance') }}</li>
            @can('finance.expenses.view')
                <li>
                    <a href="{{ route('admin.finance.expenses.index') }}">
                        <div class="parent-icon"><i class='bx bx-receipt'></i></div>
                        <div class="menu-title">{{ __('Expenses') }}</div>
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.finance.expense-categories.index') }}">
                        <div class="parent-icon"><i class='bx bx-category-alt'></i></div>
                        <div class="menu-title">{{ __('Expense Category') }}</div>
                    </a>
                </li>
            @endcan
            @can('finance.reports.view')
                <li>
                    <a href="{{ route('admin.finance.cashflow') }}">
                        <div class="parent-icon"><i class='bx bx-trending-up'></i></div>
                        <div class="menu-title">{{ __('Cashflow') }}</div>
                    </a>
                </li>
            @endcan
            @can('finance.budgets.view')
                <li>
                    <a href="{{ route('admin.finance.budgets.index') }}">
                        <div class="parent-icon"><i class='bx bx-bar-chart-square'></i></div>
                        <div class="menu-title">{{ __('Budgeting') }}</div>
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.finance.taxes.index') }}">
                        <div class="parent-icon"><i class='bx bx-purchase-tag-alt'></i></div>
                        <div class="menu-title">{{ __('Taxes') }}</div>
                    </a>
                </li>
            @endcan
            @can('finance.reports.view')
                <li>
                    <a href="javascript:;" class="has-arrow">
                        <div class="parent-icon"><i class='bx bx-pie-chart-alt-2'></i></div>
                        <div class="menu-title">{{ __('Reports') }}</div>
                    </a>
                    <ul>
                        <li><a href="{{ route('admin.finance.reports.expense-summary') }}"><i class="bx {{ $subArrow }}"></i>{{ __('Expense Summary') }}</a></li>
                        <li><a href="{{ route('admin.finance.reports.income-summary') }}"><i class="bx {{ $subArrow }}"></i>{{ __('Income Summary') }}</a></li>
                        <li><a href="{{ route('admin.finance.reports.profit-loss') }}"><i class="bx {{ $subArrow }}"></i>{{ __('Profit & Loss') }}</a></li>
                        <li><a href="{{ route('admin.finance.reports.income-vs-expense') }}"><i class="bx {{ $subArrow }}"></i>{{ __('Income vs Expense') }}</a></li>
                        <li><a href="{{ route('admin.finance.reports.tax-summary') }}"><i class="bx {{ $subArrow }}"></i>{{ __('Tax Summary') }}</a></li>
                    </ul>
                </li>
            @endcan
        @endif

        <!-- Finance & Ledger (Flat Section) -->
        @if($featureManager->isEnabled('accounting') && (auth()->user()?->can('accounting.accounts.view') || auth()->user()?->can('accounting.transactions.view')))
            <li class="menu-label">{{ __('Finance & Ledger') }}</li>
            @can('accounting.accounts.view')
                <li>
                    <a href="{{ route('admin.accounting.accounts') }}">
                        <div class="parent-icon"><i class='bx bx-book-open'></i></div>
                        <div class="menu-title">{{ __('Chart of Accounts') }}</div>
                    </a>
                </li>
            @endcan
            @can('accounting.transactions.view')
                <li>
                    <a href="{{ route('admin.accounting.transactions') }}">
                        <div class="parent-icon"><i class='bx bx-transfer'></i></div>
                        <div class="menu-title">{{ __('Ledger Transactions') }}</div>
                    </a>
                </li>
            @endcan
        @endif

        <!-- Payments & Treasury (Flat Section) -->
        @if($featureManager->isEnabled('payments') && (auth()->user()?->can('payments.transactions.view') || auth()->user()?->can('payments.refunds.view')))
            <li class="menu-label">{{ __('Payments & Treasury') }}</li>
            @can('payments.transactions.view')
                <li>
                    <a href="{{ route('admin.payments.index') }}">
                        <div class="parent-icon"><i class='bx bx-wallet'></i></div>
                        <div class="menu-title">{{ __('Payments & Methods') }}</div>
                    </a>
                </li>
            @endcan
            @can('payments.refunds.view')
                <li>
                    <a href="{{ route('admin.payments.refunds') }}">
                        <div class="parent-icon"><i class='bx bx-revision'></i></div>
                        <div class="menu-title">{{ __('Refunds') }}</div>
                    </a>
                </li>
            @endcan
        @endif

        <!-- Organization & HR (Flat Section) -->
        @if(auth()->user()?->can('users.departments.view') || auth()->user()?->can('users.teams.view') || ($featureManager->isEnabled('hrm') && auth()->user()?->can('hrm.employees.view')))
            <li class="menu-label">{{ __('Organization & HR') }}</li>
            @if($featureManager->isEnabled('hrm'))
                @can('hrm.employees.view')
                    <li>
                        <a href="{{ route('admin.hrm.employees.index') }}">
                            <div class="parent-icon"><i class='bx bx-user-voice'></i></div>
                            <div class="menu-title">{{ __('Employees') }}</div>
                        </a>
                    </li>
                @endcan
                @can('hrm.attendance.view')
                    <li>
                        <a href="{{ route('admin.hrm.attendance.index') }}">
                            <div class="parent-icon"><i class='bx bx-time-five'></i></div>
                            <div class="menu-title">{{ __('Attendance') }}</div>
                        </a>
                    </li>
                @endcan
                @can('hrm.leaves.view')
                    <li>
                        <a href="{{ route('admin.hrm.leaves.index') }}">
                            <div class="parent-icon"><i class='bx bx-calendar-check'></i></div>
                            <div class="menu-title">{{ __('Leave Requests') }}</div>
                        </a>
                    </li>
                @endcan
                @can('hrm.payroll.view')
                    <li>
                        <a href="{{ route('admin.hrm.payroll.index') }}">
                            <div class="parent-icon"><i class='bx bx-money'></i></div>
                            <div class="menu-title">{{ __('Payroll Runs') }}</div>
                        </a>
                    </li>
                @endcan
            @endif
            @can('users.departments.view')
                <li>
                    <a href="{{ route('admin.organization.staff') }}">
                        <div class="parent-icon"><i class='bx bx-id-card'></i></div>
                        <div class="menu-title">{{ __('Staff Directory') }}</div>
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.organization.departments') }}">
                        <div class="parent-icon"><i class='bx bx-sitemap'></i></div>
                        <div class="menu-title">{{ __('Departments') }}</div>
                    </a>
                </li>
            @endcan
            @can('users.teams.view')
                <li>
                    <a href="{{ route('admin.organization.teams') }}">
                        <div class="parent-icon"><i class='bx bx-group'></i></div>
                        <div class="menu-title">{{ __('Teams & Leads') }}</div>
                    </a>
                </li>
            @endcan
        @endif

        <!-- System & Security (Flat Section) -->
        <li class="menu-label">{{ __('System & Security') }}</li>
        @can('core.features.view')
            <li>
                <a href="{{ route('admin.features.index') }}">
                    <div class="parent-icon"><i class='bx bx-toggle-right'></i></div>
                    <div class="menu-title">{{ __('Module Feature Flags') }}</div>
                </a>
            </li>
        @endcan
        @can('users.roles.view')
            <li>
                <a href="{{ route('admin.roles.index') }}">
                    <div class="parent-icon"><i class='bx bx-shield-alt-2'></i></div>
                    <div class="menu-title">{{ __('Roles & RBAC') }}</div>
                </a>
            </li>
        @endcan
        @can('users.permissions.view')
            <li>
                <a href="{{ route('admin.permissions.index') }}">
                    <div class="parent-icon"><i class='bx bx-key'></i></div>
                    <div class="menu-title">{{ __('Permissions Matrix') }}</div>
                </a>
            </li>
        @endcan
        @can('users.audit-logs.view')
            <li>
                <a href="{{ route('admin.audit-logs.index') }}">
                    <div class="parent-icon"><i class='bx bx-history'></i></div>
                    <div class="menu-title">{{ __('Compliance Audit Trail') }}</div>
                </a>
            </li>
        @endcan
    </ul>
</div>
