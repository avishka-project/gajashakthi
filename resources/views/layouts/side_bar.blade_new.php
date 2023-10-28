<nav class="sidenav shadow-right sidenav-light">
    <div class="sidenav-menu">
        <div class="nav accordion" id="accordionSidenav">
            <div class="sidenav-menu-heading py-2 mt-3 text-primary">Core</div>
            {{-- <a class="nav-link p-0 px-3 py-2" id="dashboard_link" href="{{ url('/home') }}">
                <div class="nav-link-icon"><i class="fas fa-desktop"></i></div>
                Dashboards
            </a> --}}
            
            {{-- @if(auth()->user()->can('emptype-list'))
            <a class="nav-link p-0 px-3 py-2" id="emptype_link" href="{{ route('emptypes')}}">
                <div class="nav-link-icon"><i class="fas fa-user"></i></div>
                {{ __('Employee Type') }}
            </a>
            @endif
            @if(auth()->user()->can('Region-list') || auth()->user()->can('Sub-Region-list') )
            <a class="nav-link p-0 px-3 py-2 collapsed" id="regionlist" href="javascript:void(0);"
               data-toggle="collapse" data-target="#regionlistdrop" aria-expanded="false"
               aria-controls="collapseDashboards"
            >
                <div class="nav-link-icon"><i class="fas fa-map-marker-alt"></i></div>
                Region
                <div class="sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div
                >
            </a>
            <div class="collapse" id="regionlistdrop" data-parent="#accordionSidenav">
                <nav class="sidenav-menu-nested nav accordion" id="accordionSidenavPages">
                    @can('Region-list')
                        <a class="nav-link p-0 px-3 py-2" id="region_link"
                           href="{{ route('regions')}}">Region</a>
                    @endcan
                        @can('Sub-Region-list')
                            <a href="{{ route('subregions')}}" id="subregion_link" class="nav-link p-0 px-3 py-2" > Sub Region </a>
                        @endcan
                </nav>
            </div>
        @endif  
        @if(auth()->user()->can('customer-list') || auth()->user()->can('branch-list') || auth()->user()->can('customercategory-list') || auth()->user()->can('subcustomer-list') )
            <a class="nav-link p-0 px-3 py-2 collapsed" id="customerlist" href="javascript:void(0);"
            data-toggle="collapse" data-target="#customerlistdrop" aria-expanded="false"
            aria-controls="collapseDashboards">
                <div class="nav-link-icon"><i class="fas fa-users"></i></div>
                Customer
                <div class="sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div
                >
            </a>
            <div class="collapse" id="customerlistdrop" data-parent="#accordionSidenav">
                <nav class="sidenav-menu-nested nav accordion" id="accordionSidenavPages">
                    @can('customer-list')
                    <a class="nav-link p-0 px-3 py-2" id="customer_link" href="{{ route('customers')}}">Customer</a>
                    @endcan
                    @can('subcustomer-list')
                    <a href="{{ route('subcustomers')}}" id="subcustomer_link" class="nav-link p-0 px-3 py-2"> Sub
                        Customer</a>
                    @endcan
                    @can('branch-list')
                    <a href="{{ route('branchers')}}" id="branch_link" class="nav-link p-0 px-3 py-2"> Customer
                        Branch</a>
                    @endcan
                    @can('customercategory-list')
                    <a href="{{ route('cuscategory')}}" id="category_link" class="nav-link p-0 px-3 py-2"> Customer
                        Category</a>
                    @endcan
                </nav>
            </div>
        @endif  --}}
        @if(auth()->user()->can('CustomerRequest-list'))
        <a class="nav-link p-0 px-3 py-2" id="cusreqest_link" href="{{ route('customerrequest') }}">
            <div class="nav-link-icon"><i class="fas fa-users"></i></div>
            {{ __('Customers Request') }}
        </a> 
        @endif 
        @if(auth()->user()->can('allocation-list'))
        <a class="nav-link p-0 px-3 py-2" id="empallocation_link" href="{{ route('allocation') }}">
            <div class="nav-link-icon"><i class="fas fa-user-plus"></i></div>
            {{ __('Employee Allocation') }}
        </a> 
        @endif 
        @if(auth()->user()->can('CustomerRequest-list'))
        <a class="nav-link p-0 px-3 py-2" id="emppayment_link" href="{{ route('employeepayment') }}">
            <div class="nav-link-icon"><i class="fas fa-users"></i></div>
            {{ __('Employee Payment') }}
        </a> 
        @endif 
        @if(auth()->user()->can('empattendance-list') || auth()->user()->can('empattendance-approvelist') )
        <a class="nav-link p-0 px-3 py-2 collapsed" id="empattendancelist" href="javascript:void(0);"
        data-toggle="collapse" data-target="#empattendancelistdrop" aria-expanded="false"
        aria-controls="collapseDashboards">
            <div class="nav-link-icon"><i class="fas fa-address-book"></i></div>
            Employee Attendance
            <div class="sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div
            >
        </a>
        <div class="collapse" id="empattendancelistdrop" data-parent="#accordionSidenav">
            <nav class="sidenav-menu-nested nav accordion" id="accordionSidenavPages">
                @can('empattendance-list')
                <a class="nav-link p-0 px-3 py-2" id="empattendace_link" href="{{ route('empattendance')}}"> Employee Attendance</a>
                @endcan
                @can('empattendance-approvelist')
                <a href="{{ route('attendanceapprove')}}" id="empattendaceapprove_link" class="nav-link p-0 px-3 py-2"> Attendance Approve</a>
                @endcan
               
            </nav>
        </div>
    @endif 

            {{-- @if(auth()->user()->can('user-list') || auth()->user()->can('role-list'))

                <div class="sidenav-menu-heading py-2 text-primary">Administrator</div>
                <a class="nav-link p-0 px-3 py-2 collapsed" id="administrator_main_nav_link"
                   href="javascript:void(0);" data-toggle="collapse" data-target="#administrator_collapse"
                   aria-expanded="false" aria-controls="collapseLayouts"
                >
                    <div class="nav-link-icon"><i class="fas fa-user"></i></div>
                    Administrator
                    <div class="sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div
                    >
                </a>
                <div class="collapse" id="administrator_collapse" data-parent="#accordionSidenav">
                    <nav class="sidenav-menu-nested nav accordion" id="accordionSidenavLayout">
                        @can('user-list')
                            <a class="nav-link p-0 px-3 py-2" id="users_link"
                               href="{{ route('users.index') }}">Users</a>
                        @endcan
                        @can('role-list')
                            <a class="nav-link p-0 px-3 py-2" id="roles_link"
                               href="{{ route('roles.index') }}">Roles</a>
                        @endcan
                    </nav>
                </div>

            @endif --}}

        </div>
        <div class="sidenav-footer" style="position: fixed;
bottom: 0;
width: 100%;">
            <div class="sidenav-footer-content">
                <div class="sidenav-footer-subtitle">Logged in as:</div>
                <div class="sidenav-footer-title">
                    @isset(Auth::user()->name)
                        {{ Auth::user()->name }}
                    @endisset</div>
            </div>
        </div>
    </div>
</nav>