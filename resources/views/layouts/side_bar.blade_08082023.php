<nav class="sidenav shadow-right sidenav-light">
    <div class="sidenav-menu">
        <div class="nav accordion" id="accordionSidenav">
            <div class="sidenav-menu-heading py-2 mt-3 text-primary">Core</div>
            <a class="nav-link p-0 px-3 py-2" id="dashboard_link" href="{{ url('/home') }}">
                <div class="nav-link-icon"><i class="fas fa-desktop"></i></div>
                Dashboards
            </a>

            @if(auth()->user()->can('location-list') || auth()->user()->can('company-list') || auth()->user()->can('bank-list') )
                <a class="nav-link p-0 px-3 py-2 collapsed" id="organization_main_nav_link" href="javascript:void(0);"
                   data-toggle="collapse" data-target="#organization_collapse" aria-expanded="false"
                   aria-controls="collapseDashboards"
                >
                    <div class="nav-link-icon"><i class="fas fa-sitemap"></i></div>
                    Organization
                    <div class="sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div
                    >
                </a>
                <div class="collapse" id="organization_collapse" data-parent="#accordionSidenav">
                    <nav class="sidenav-menu-nested nav accordion" id="accordionSidenavPages">
                        @can('location-list')
                            <a class="nav-link p-0 px-3 py-2" id="branch_link" href="{{ route('Branch') }}">Location</a>
                        @endcan
                        @can('company-list')
                            <a class="nav-link p-0 px-3 py-2" id="company_link"
                               href="{{ route('Company') }}">Company</a>
                        @endcan
                        @can('bank-list')
                            <a class="nav-link p-0 px-3 py-2" id="bank_link" href="{{ route('Bank') }}">Bank</a>
                        @endcan
                    </nav>
                </div>
            @endif

            @if(auth()->user()->can('skill-list') )
                <a class="nav-link p-0 px-3 py-2 collapsed" id="qualification_main_nav_link" href="javascript:void(0);"
                   data-toggle="collapse" data-target="#qualification_collapse" aria-expanded="false"
                   aria-controls="collapseDashboards"
                >
                    <div class="nav-link-icon"><i class="fas fa-user-graduate"></i></div>
                    Qualifications
                    <div class="sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div
                    >
                </a>
                <div class="collapse" id="qualification_collapse" data-parent="#accordionSidenav">
                    <nav class="sidenav-menu-nested nav accordion" id="accordionSidenavPages">
                        @can('skill-list')
                            <a class="nav-link p-0 px-3 py-2" id="skill_link" href="{{ route('Skill') }}">Skill</a>
                        @endcan
                    </nav>
                </div>
            @endif

            @if(auth()->user()->can('job-title-list')
                || auth()->user()->can('pay-grade-list')
                || auth()->user()->can('job-category-list')
                || auth()->user()->can('job-employment-status-list')
                 )
                <a class="nav-link p-0 px-3 py-2 collapsed" id="job_main_nav_link" href="javascript:void(0);"
                   data-toggle="collapse" data-target="#job_collapse" aria-expanded="false"
                   aria-controls="collapseLayouts"
                >
                    <div class="nav-link-icon"><i class="fas fa-user-tag"></i></div>
                    Job
                    <div class="sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div
                    >
                </a>
                <div class="collapse" id="job_collapse" data-parent="#accordionSidenav">
                    <nav class="sidenav-menu-nested nav accordion" id="accordionSidenavLayout">
                        @can('job-title-list')
                            <a class="nav-link p-0 px-3 py-2" id="job_title_link" href="{{ route('JobTitle') }}">Job
                                Titles</a>
                        @endcan
                        @can('pay-grade-list')
                            <a class="nav-link p-0 px-3 py-2" id="pay_grade_link" href="{{ route('PayGrade') }}">Pay
                                Grades</a>
                        @endcan
                        @can('job-category-list')
                            <a class="nav-link p-0 px-3 py-2" id="job_category_link" href="{{ route('JobCategory') }}">
                                Job
                                Categories</a>
                        @endcan
                        @can('job-employment-status-list')
                            <a class="nav-link p-0 px-3 py-2" id="employment_link"
                               href="{{ route('EmploymentStatus') }}">Job
                                Employment Status</a>
                        @endcan
                    </nav>
                </div>
            @endif

            @if(auth()->user()->can('attendance-sync')
                    || auth()->user()->can('attendance-incomplete-data-list')
                    || auth()->user()->can('attendance-list')
                    || auth()->user()->can('attendance-create')
                    || auth()->user()->can('attendance-edit')
                    || auth()->user()->can('attendance-delete')
                    || auth()->user()->can('attendance-approve')
                    || auth()->user()->can('late-attendance-create')
                    || auth()->user()->can('late-attendance-approve')
                    || auth()->user()->can('late-attendance-list')
                    || auth()->user()->can('attendance-incomplete-data-list')
                    || auth()->user()->can('ot-approve')
                    || auth()->user()->can('ot-list')
                    || auth()->user()->can('finger-print-device-list')
                    || auth()->user()->can('finger-print-user-list')
                    )

                <a class="nav-link p-0 px-3 py-2 collapsed" id="attendance_main_nav_link" href="javascript:void(0);"
                   data-toggle="collapse" data-target="#attendance_collapse" aria-expanded="false"
                   aria-controls="collapseLayouts"
                >
                    <div class="nav-link-icon"><i class="far fa-calendar-plus"></i></div>
                    Attendant Details
                    <div class="sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div
                    >
                </a>
                <div class="collapse" id="attendance_collapse" data-parent="#accordionSidenav">
                    <nav class="sidenav-menu-nested nav accordion" id="accordionSidenavLayout">
                        @can('attendance-sync')
                            <a class="nav-link p-0 px-3 py-2" id="attendance_sync_link"
                               href="{{ route('Attendance') }}">Attendance
                                Sync</a>
                        @endcan
                        @can('attendance-create')
                            <a class="nav-link p-0 px-3 py-2" id="attendance_add_link"
                               href="{{ route('AttendanceEdit') }}">
                                Attendance Add</a>
                        @endcan
                        @can('attendance-edit')
                            <a class="nav-link p-0 px-3 py-2" id="attendance_edit_link"
                               href="{{ route('AttendanceEditBulk') }}"> Attendance Edit</a>
                        @endcan
                        @can('attendance-approve')
                            <a class="nav-link p-0 px-3 py-2" id="attendance_approve_link"
                               href="{{ route('AttendanceApprovel') }}">Attendance Approval</a>
                        @endcan

                        @can('late-attendance-create')
                            <a class="nav-link p-0 px-3 py-2" id="late_attendance_mark_link"
                               href="{{ route('late_attendance_by_time') }}">Late Attendance Mark</a>
                        @endcan
                        @can('late-attendance-approve')
                            <a class="nav-link p-0 px-3 py-2" id="late_attendance_approve_link"
                               href="{{ route('late_attendance_by_time_approve') }}">Late Attendance Approve</a>
                        @endcan
                        @can('late-attendance-list')
                            <a class="nav-link p-0 px-3 py-2" id="late_attendance_link"
                               href="{{ route('late_attendances_all') }}">Late Attendances</a>
                        @endcan

                        @can('attendance-incomplete-data-list')
                            <a class="nav-link p-0 px-3 py-2" id="incomplete_attendance_link"
                               href="{{ route('incomplete_attendances') }}">Incomplete Attendances</a>
                        @endcan
                        @can('ot-approve')
                            <a class="nav-link p-0 px-3 py-2" id="ot_approve_link" href="{{ route('ot_approve') }}">OT
                                Approve</a>
                        @endcan
                        @can('ot-list')
                            <a class="nav-link p-0 px-3 py-2" id="approved_ot_link" href="{{ route('ot_approved') }}">Approved
                                OT</a>
                        @endcan
                        @can('finger-print-device-list')
                            <a class="nav-link p-0 px-3 py-2" id="finger_print_device_link"
                               href="{{ route('FingerprintDevice') }}">Fingerprint Device</a>
                        @endcan
                        @can('finger-print-user-list')
                            <a class="nav-link p-0 px-3 py-2" id="finger_print_user_link"
                               href="{{ route('FingerprintUser') }}">Fingerprint User</a>
                        @endcan
                    </nav>
                </div>
            @endif

            @if(auth()->user()->can('leave-list')
                    || auth()->user()->can('leave-type-list')
                    || auth()->user()->can('leave-approve')
                    || auth()->user()->can('holiday-list')
                    )

                <a class="nav-link p-0 px-3 py-2 collapsed" id="leaves_main_nav_link" href="javascript:void(0);"
                   data-toggle="collapse" data-target="#leave_collapse" aria-expanded="false"
                   aria-controls="collapseLayouts"
                >
                    <div class="nav-link-icon"><i class="far fa-calendar-times"></i></div>
                    Leave Management
                    <div class="sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div
                    >
                </a>
                <div class="collapse" id="leave_collapse" data-parent="#accordionSidenav">
                    <nav class="sidenav-menu-nested nav accordion" id="accordionSidenavLayout">
                        @can('leave-list')
                            <a class="nav-link p-0 px-3 py-2" id="leave_apply_link" href="{{ route('LeaveApply') }}">Leave
                                Apply</a>
                        @endcan
                        @can('leave-type-list')
                            <a class="nav-link p-0 px-3 py-2" id="leave_type_link" href="{{ route('LeaveType') }}">Leave
                                Type</a>
                        @endcan
                        @can('leave-approve')
                            <a class="nav-link p-0 px-3 py-2" id="leave_approvals_link"
                               href="{{ route('LeaveApprovel') }}">Leave
                                Approvals</a>
                        @endcan
                        @can('holiday-list')
                            <a class="nav-link p-0 px-3 py-2" id="holiday_link"
                               href="{{ route('Holiday') }}">Holiday</a>
                        @endcan
                    </nav>
                </div>
            @endif

            @if(auth()->user()->can('shift-list')
                    || auth()->user()->can('work-shift-list')
                    )

                <a class="nav-link p-0 px-3 py-2 collapsed" id="shift_main_nav_link" href="javascript:void(0);"
                   data-toggle="collapse" data-target="#shift_collapse" aria-expanded="false"
                   aria-controls="collapseLayouts"
                >
                    <div class="nav-link-icon"><i class="fas fa-business-time"></i></div>
                    Shift Management
                    <div class="sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div
                    >
                </a>
                <div class="collapse" id="shift_collapse" data-parent="#accordionSidenav">
                    <nav class="sidenav-menu-nested nav accordion" id="accordionSidenavLayout">
                        @can('shift-list')
                            <a class="nav-link p-0 px-3 py-2" id="shift_link" href="{{ route('Shift') }}">Shifts</a>
                        @endcan
                        @can('work-shift-list')
                            <a class="nav-link p-0 px-3 py-2" id="work_shift_link" href="{{ route('ShiftType') }}">Work
                                Shifts</a>
                        @endcan
                    </nav>
                </div>
            @endif
                    @if(auth()->user()->can('emptype-list'))
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
            @endif 
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
                        <a class="nav-link p-0 px-3 py-2" id="empattendace_link" href="{{ route('customers')}}"> Employee Attendance</a>
                        @endcan
                        @can('empattendance-approvelist')
                        <a href="{{ route('subcustomers')}}" id="empattendaceapprove_link" class="nav-link p-0 px-3 py-2"> Attendance Approve</a>
                        @endcan
                    
                    </nav>
                </div>
              @endif        

            @if(auth()->user()->can('employee-list')
                    )
                <div class="sidenav-menu-heading py-2 text-primary">Information</div>
                <a class="nav-link p-0 px-3 py-2 collapsed" id="employee_main_nav_link" href="javascript:void(0);"
                   data-toggle="collapse" data-target="#employee_collapse" aria-expanded="false"
                   aria-controls="collapseLayouts"
                >
                    <div class="nav-link-icon"><i class="fas fa-id-card-alt"></i></div>
                    Employee Information
                    <div class="sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div
                    >
                </a>
                <div class="collapse" id="employee_collapse" data-parent="#accordionSidenav">
                    <nav class="sidenav-menu-nested nav accordion" id="accordionSidenavLayout">
                        <a class="nav-link p-0 px-3 py-2" id="employee_add_link" href="{{ route('addEmployee') }}">Employee
                            Details</a>
                    </nav>
                </div>
            @endif

            @if(auth()->user()->can('employee-report')
                    || auth()->user()->can('attendance-report')
                    || auth()->user()->can('late-attendance-report')
                    || auth()->user()->can('leave-report')
                    || auth()->user()->can('employee-bank-report')
                    || auth()->user()->can('leave-balance-report')
                    || auth()->user()->can('ot-report')
                    || auth()->user()->can('no-pay-report')
                    )
                <div class="sidenav-menu-heading py-2 text-primary">Reports</div>
                <a class="nav-link p-0 px-3 py-2 collapsed" id="report_main_nav_link" href="javascript:void(0);"
                   data-toggle="collapse" data-target="#report_collapse" aria-expanded="false"
                   aria-controls="collapseLayouts"
                >
                    <div class="nav-link-icon"><i class="fas fa-file"></i></div>
                    Employee Reports
                    <div class="sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div
                    >
                </a>
                <div class="collapse" id="report_collapse" data-parent="#accordionSidenav">
                    <nav class="sidenav-menu-nested nav accordion" id="accordionSidenavLayout">
                        @can('employee-report')
                            <a class="nav-link p-0 px-3 py-2" id="employees_report_link"
                               href="{{ route('EmpoloyeeReport') }}">Employees Report</a>
                        @endcan
                        @can('attendance-report')
                            <a class="nav-link p-0 px-3 py-2" id="attendance_report_link"
                               href="{{ route('attendetreportbyemployee') }}">Attendance Report</a>
                        @endcan
                        @can('late-attendance-report')
                            <a class="nav-link p-0 px-3 py-2" id="late_attendance_report_link"
                               href="{{ route('LateAttendance') }}">Late Attendance</a>
                        @endcan
                        @can('leave-report')
                            <a class="nav-link p-0 px-3 py-2" id="leave_report_link" href="{{ route('leaveReport') }}">Leave
                                Report</a>
                        @endcan
                        @can('employee-bank-report')
                            <a class="nav-link p-0 px-3 py-2" id="employee_bank_report_link"
                               href="{{ route('empBankReport') }}">Employee Banks</a>
                        @endcan
                        @can('leave-balance-report')
                            <a class="nav-link p-0 px-3 py-2" id="leave_balance_report_link"
                               href="{{ route('LeaveBalance') }}">Leave Balance</a>
                        @endcan
                        @can('ot-report')
                            <a class="nav-link p-0 px-3 py-2" id="ot_report_link" href="{{ route('ot_report') }}">O.T.
                                Report</a>
                        @endcan
                        @can('no-pay-report')
                            <a class="nav-link p-0 px-3 py-2" id="no_pay_report_link"
                               href="{{ route('no_pay_report') }}">No
                                Pay Report</a>
                        @endcan
                    </nav>
                </div>
            @endif

            @if(auth()->user()->can('user-list') || auth()->user()->can('role-list'))

                <div class="sidenav-menu-heading py-2 text-primary">Administrator</div>
                <a class="nav-link p-0 px-3 py-2 collapsed" id="administrator_main_nav_link"
                   href="javascript:void(0);" data-toggle="collapse" data-target="#administrator_collapse"
                   aria-expanded="false" aria-controls="collapseLayouts"
                >
                    <div class="nav-link-icon"><i class="fas fa-user-lock"></i></div>
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

            @endif

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