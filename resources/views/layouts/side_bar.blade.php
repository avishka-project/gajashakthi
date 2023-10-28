<nav class="sidenav shadow-right sidenavbarcolor ">
    <div class="sidenav-menu">
        <div class="nav accordion" id="accordionSidenav">
            <div class=" py-1 mt-3  sidebar-text-color">Core</div>
            <a class="nav-link p-0 px-3 py-1 sidebar-text-color" id="dashboard_link" href="{{ url('/home') }}">
                <div class="nav-link-icon"><i class="fas fa-desktop"></i></div>
                Dashboards
            </a>

            @if(auth()->user()->can('location-list')
            || auth()->user()->can('company-list')
            || auth()->user()->can('Newbusinessproposal-list')
            || auth()->user()->can('Vehicle-list')
            || auth()->user()->can('Vehicle-Allocate-list')
            || auth()->user()->can('Vehicleserviceandrepair-list')
            || auth()->user()->can('ItemCategory-list')
            || auth()->user()->can('Item-list')
            || auth()->user()->can('Supplier-list')
            || auth()->user()->can('Issue-list')
            || auth()->user()->can('Porder-list')
            || auth()->user()->can('Grn-list')
            || auth()->user()->can('StoreType-list')
            || auth()->user()->can('StoreList-list')
            || auth()->user()->can('Pettycash-list')
            || auth()->user()->can('InventoryType-list')
            || auth()->user()->can('InventoryList-list')
            || auth()->user()->can('Return-list')
            || auth()->user()->can('ApproveReturn-list')
            || auth()->user()->can('Pettycashcategory-list'))

            <a class="nav-link p-0 px-3 py-2 collapsed sidebar-text-color" href="javascript:void(0);"
                data-toggle="collapse" data-target="#collapseCorporation" aria-expanded="false"
                aria-controls="collapseCorporation">
                <div class="nav-link-icon"><i class="fas fa-sitemap"></i></div>
                Corporate
                <div class="sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
            </a>
            <div class="collapse" id="collapseCorporation" data-parent="#accordionSidenav">
                <nav class="sidenav-menu-nested nav accordion" id="accordionSidenavPages">

                    <!-- Company Information -->
                    <a class="nav-link p-0 px-3 py-1 collapsed sidebar-text-color" id="organization_main_nav_link"
                        href="javascript:void(0);" data-toggle="collapse" data-target="#organization_collapse"
                        aria-expanded="false" aria-controls="collapseDashboards">
                        <div class="nav-link-icon sidebar-text-color"></div>
                        Company Information
                        <div class="sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                    </a>
                    <div class="collapse" id="organization_collapse" data-parent="#organization_main_nav_link">
                        <nav class="sidenav-menu-nested nav accordion" id="accordionSidenavPages">
                            {{-- @can('location-list')
                            <a class="nav-link p-0 px-3 py-1 sidebar-text-color" id="branch_link"
                                href="{{ route('Branch') }}">Location</a>
                            @endcan --}}
                            @can('company-list')
                            <a class="nav-link p-0 px-3 py-1 sidebar-text-color " id="company_link"
                                href="{{ route('Company') }}">Company</a>
                            @endcan
                        </nav>
                    </div>

                    <!-- Vehicle Information -->
                    <a class="nav-link p-0 px-3 py-1 collapsed sidebar-text-color" id="vehiclelist"
                        href="javascript:void(0);" data-toggle="collapse" data-target="#vehiclelistlistdrop"
                        aria-expanded="false" aria-controls="collapseDashboards">
                        <div class="nav-link-icon"></div> Vehicle Fleet Management
                        <div class="sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                    </a>
                    <div class="collapse" id="vehiclelistlistdrop">
                        <nav class="sidenav-menu-nested nav accordion" id="accordionSidenavPages">
                            @can('Vehicle-list')
                            <a class="nav-link p-0 px-3 py-1 sidebar-text-color" id="vehicletype_link"
                                href="{{ route('vehicletype')}}"> Vehicle Type</a>
                            @endcan
                            @can('Vehicle-list')
                            <a class="nav-link p-0 px-3 py-1 sidebar-text-color" id="vehicle_link"
                                href="{{ route('vehicle')}}"> Vehicle</a>
                            @endcan
                            @can('Vehicle-Allocate-list')
                            <a href="{{ route('vehicleallocate')}}" id="vehicleassign_link"
                                class="nav-link p-0 px-3 py-1 sidebar-text-color"> Vehicle Allocation</a>
                            @endcan
                            @can('Vehicleserviceandrepair-list')
                            <a class="nav-link p-0 px-3 py-1 sidebar-text-color" id="vehicleserviceandrepair_link"
                                href="{{ route('vehicleserviceandrepair') }}"> Vehicle Service & Repair
                            </a>
                            @endcan
                        </nav>
                    </div>

                    <a class="nav-link p-0 px-3 py-1 collapsed sidebar-text-color" href="javascript:void(0);"
                        data-toggle="collapse" data-target="#collapsgrninfo" aria-expanded="false"
                        aria-controls="collapsgrninfo">
                        <div class="nav-link-icon"></div>
                        Inventory Management
                        <div class="sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                    </a>
                    <div class="collapse" id="collapsgrninfo">
                        <nav class="sidenav-menu-nested nav accordion" id="accordionSidenavPages">
                            <a class="nav-link p-0 px-3 py-1 collapsed sidebar-text-color" id="grninfo" href="#"
                                data-toggle="collapse" data-target="#storedrop" aria-expanded="false"
                                aria-controls="collapseDashboards">
                                <div class="nav-link-icon"></div>
                                Store
                                <div class="sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                            </a>
                            <div class="collapse" id="storedrop" data-parent="#accordionSidenavPages">
                                <nav class="sidenav-menu-nested nav accordion" id="grn_submenu">
                                    @can('StoreType-list')
                                    <a class="nav-link p-0 px-3 py-1 sidebar-text-color" id="store_type_link"
                                        href="{{ route('storetype')}}"> Store Type</a>
                                    @endcan
                                    @can('StoreList-list')
                                    <a href="{{ route('storelist')}}" id="store_list_link"
                                        class="nav-link p-0 px-3 py-1 sidebar-text-color"> Store List</a>
                                    @endcan
                                </nav>
                            </div>
                            <a class="nav-link p-0 px-3 py-1 collapsed sidebar-text-color" id="grninfo" href="#"
                            data-toggle="collapse" data-target="#inventorydrop" aria-expanded="false"
                            aria-controls="collapseDashboards">
                            <div class="nav-link-icon"></div>
                            Inventory
                            <div class="sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                        </a>
                        <div class="collapse" id="inventorydrop" data-parent="#accordionSidenavPages">
                            <nav class="sidenav-menu-nested nav accordion" id="grn_submenu">
                                @can('InventoryType-list')
                                <a class="nav-link p-0 px-3 py-1 sidebar-text-color" id="inventory_type_link"
                                    href="{{ route('inventorytype')}}"> Inventory Type</a>
                                @endcan
                                @can('InventoryList-list')
                                <a href="{{ route('inventorylist')}}" id="inventory_list_link"
                                    class="nav-link p-0 px-3 py-1 sidebar-text-color"> Inventory List</a>
                                @endcan
                            </nav>
                        </div>
                            {{-- <a class="nav-link p-0 px-3 py-1 collapsed sidebar-text-color" id="grninfo" href="#"
                                data-toggle="collapse" data-target="#grninfodrop" aria-expanded="false"
                                aria-controls="collapseDashboards">
                                <div class="nav-link-icon"></div>
                                Master Data
                                <div class="sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                            </a>
                            <div class="collapse" id="grninfodrop" data-parent="#accordionSidenavPages">
                                <nav class="sidenav-menu-nested nav accordion" id="grn_submenu">
                                    @can('ItemCategory-list')
                                    <a class="nav-link p-0 px-3 py-1 sidebar-text-color" id="item_category_link"
                                        href="{{ route('itemcategory')}}"> Item Category</a>
                                    @endcan
                                    @can('Item-list')
                                    <a href="{{ route('item')}}" id="item_link"
                                        class="nav-link p-0 px-3 py-1 sidebar-text-color"> Item</a>
                                    @endcan
                                </nav>
                            </div> --}}
                            @if(auth()->user()->can('Supplier-list'))
                            <a class="nav-link p-0 px-3 py-1 sidebar-text-color" id="supplier_link"
                                href="{{ route('supplier') }}">
                                <div class="nav-link-icon"></div>
                                {{ __('Supplier') }}
                            </a>
                            @endif
                            <a class="nav-link p-0 px-3 py-1 collapsed sidebar-text-color" id="grninfo" href="#"
                            data-toggle="collapse" data-target="#grn_pruchaseinfodrop" aria-expanded="false"
                            aria-controls="collapseDashboards">
                            <div class="nav-link-icon"></div>
                            Purchase & GRN
                            <div class="sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                        </a>
                        <div class="collapse" id="grn_pruchaseinfodrop" data-parent="#accordionSidenavPages">
                            <nav class="sidenav-menu-nested nav accordion" id="grn_submenu">
                                @can('Porder-list')
                                <a class="nav-link p-0 px-3 py-1 sidebar-text-color" id="item_category_link"
                                    href="{{ route('porder')}}"> Purchase Order</a>
                                @endcan
                                @can('Grn-list')
                                <a href="{{ route('grn')}}" id="item_link"
                                    class="nav-link p-0 px-3 py-1 sidebar-text-color"> GRN</a>
                                @endcan
                            </nav>
                        </div>
                            @if(auth()->user()->can('Issue-list'))
                            <a class="nav-link p-0 px-3 py-1 sidebar-text-color" id="issue_link"
                                href="{{ route('issue') }}">
                                <div class="nav-link-icon"></div>
                                {{ __('Issue') }}
                            </a>
                            @endif
                            <a class="nav-link p-0 px-3 py-1 collapsed sidebar-text-color" id="grninfo" href="#"
                            data-toggle="collapse" data-target="#returndrop" aria-expanded="false"
                            aria-controls="collapseDashboards">
                            <div class="nav-link-icon"></div>
                            Return
                            <div class="sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                        </a>
                        <div class="collapse" id="returndrop" data-parent="#accordionSidenavPages">
                            <nav class="sidenav-menu-nested nav accordion" id="grn_submenu">
                                @can('Return-list')
                                <a class="nav-link p-0 px-3 py-1 sidebar-text-color" id="return_link"
                                    href="{{ route('return')}}"> Add Return</a>
                                @endcan
                                @can('ApproveReturn-list')
                                <a href="{{ route('approvereturn')}}" id="approvereturn_link"
                                    class="nav-link p-0 px-3 py-1 sidebar-text-color"> Approve Return</a>
                                @endcan
                            </nav>
                        </div>
                        </nav>
                    </div>
                    <a class="nav-link p-0 px-3 py-2 sidebar-text-color" id="newbusinessproposal_link"
                        href="{{ route('newbusinessproposal') }}">
                        <div class="nav-link-icon"></div>
                        {{ __('New Business') }}
                    </a>

                    <a class="nav-link p-0 px-3 py-1 collapsed sidebar-text-color" href="javascript:void(0);"
                        data-toggle="collapse" data-target="#collapfixassets" aria-expanded="false"
                        aria-controls="collapfixassets">
                        <div class="nav-link-icon"></div> Fixed Asset Management
                        <div class="sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                    </a>
                    <div class="collapse" id="collapfixassets">
                        <nav class="sidenav-menu-nested nav accordion" id="accordionSidenavPages">
                        </nav>
                    </div>

                    <a class="nav-link p-0 px-3 py-1 collapsed sidebar-text-color" href="javascript:void(0);"
                        data-toggle="collapse" data-target="#collapserviceman" aria-expanded="false"
                        aria-controls="collapserviceman">
                        <div class="nav-link-icon"></div> Service Management
                        <div class="sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                    </a>
                    <div class="collapse" id="collapserviceman">
                        <nav class="sidenav-menu-nested nav accordion" id="accordionSidenavPages">
                        </nav>
                    </div>

                    <a class="nav-link p-0 px-3 py-1 collapsed sidebar-text-color" href="javascript:void(0);"
                        data-toggle="collapse" data-target="#collapsimman" aria-expanded="false"
                        aria-controls="collapsimman">
                        <div class="nav-link-icon"></div> SIM Management
                        <div class="sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                    </a>
                    <div class="collapse" id="collapsimman">
                        <nav class="sidenav-menu-nested nav accordion" id="accordionSidenavPages">
                            @can('Mobilebillpayment-list')
                            <a class="nav-link p-0 px-3 py-1 sidebar-text-color" id="mobilebillpayment_link"
                                href="{{ route('mobilebillpayment')}}"> Mobile Bill Payment</a>
                            @endcan
                        </nav>
                    </div>

                    <a class="nav-link p-0 px-3 py-1 collapsed sidebar-text-color" href="javascript:void(0);"
                        data-toggle="collapse" data-target="#collapexpenses" aria-expanded="false"
                        aria-controls="collapexpenses">
                        <div class="nav-link-icon"></div> Expenses Management
                        <div class="sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                    </a>
                    <div class="collapse" id="collapexpenses">
                        <nav class="sidenav-menu-nested nav accordion" id="accordionSidenavPages">
                            @can('Pettycashcategory-list')
                            <a class="nav-link p-0 px-3 py-1 sidebar-text-color" id="pettycashcategory_link"
                                href="{{ route('pettycashcategory')}}"> Petty Cash Category</a>
                            @endcan
                            @can('Pettycash-list')
                            <a class="nav-link p-0 px-3 py-1 sidebar-text-color" id="pettycash_link"
                                href="{{ route('pettycash')}}"> Petty Cash</a>
                            @endcan
                        </nav>
                    </div>
                </nav>
            </div>
            @endif

            @if(auth()->user()->can('employee-list') || auth()->user()->can('bank-list') ||
            auth()->user()->can('skill-list') || auth()->user()->can('job-title-list')
            || auth()->user()->can('pay-grade-list') || auth()->user()->can('job-category-list') ||
            auth()->user()->can('job-employment-status-list') || auth()->user()->can('shift-list')
            || auth()->user()->can('work-shift-list')||auth()->user()->can('Deaddonation-list') ||
            auth()->user()->can('Deaddonationallocation-list') || auth()->user()->can('Deaddonationincomplete-list')
            || auth()->user()->can('Deaddonationlastallocation-list') || auth()->user()->can('Deaddonationdetail-view')
            || auth()->user()->can('allocation-list')
            || auth()->user()->can('CustomerRequest-list'))

            <a class="nav-link p-0 px-3 py-2 collapsed sidebar-text-color" href="javascript:void(0);"
                data-toggle="collapse" data-target="#collapseemployee" aria-expanded="false"
                aria-controls="collapseemployee">
                <div class="nav-link-icon"><i class="fas fa-users"></i></div>
                Employee Management
                <div class="sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
            </a>
            <div class="collapse" id="collapseemployee" data-parent="#accordionSidenav">
                <nav class="sidenav-menu-nested nav accordion" id="accordionSidenavPages">

                    <a class="nav-link p-0 px-3 py-1 collapsed sidebar-text-color" id="empmaster_main_nav_link"
                                href="#" data-toggle="collapse" data-target="#empmaster_collapse" aria-expanded="false"
                                aria-controls="collapseDashboards">
                                <div class="nav-link-icon"></div>
                                Master Data
                                <div class="sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                            </a>
                            <div class="collapse" id="empmaster_collapse" data-parent="#accordionSidenavPages">
                                <nav class="sidenav-menu-nested nav accordion" id="empmaster_submenu">
                                    @can('bank-list')
                                    <a class="nav-link p-0 px-3 py-1 sidebar-text-color" id="bank_link"
                                        href="{{ route('Bank') }}">Bank</a>
                                    @endcan
                                    @can('skill-list')
                                    <a class="nav-link p-0 px-3 py-1 sidebar-text-color" id="skill_link"
                                        href="{{ route('Skill') }}">Skill</a>
                                    @endcan
                                    @can('job-title-list')
                                    <a class="nav-link p-0 px-3 py-1 sidebar-text-color" id="job_title_link"
                                        href="{{ route('JobTitle') }}">Job
                                        Titles</a>
                                    @endcan
                                    @can('pay-grade-list')
                                    <a class="nav-link p-0 px-3 py-1 sidebar-text-color" id="pay_grade_link"
                                        href="{{ route('PayGrade') }}">Pay
                                        Grades</a>
                                    @endcan
                                    @can('job-category-list')
                                    <a class="nav-link p-0 px-3 py-1 sidebar-text-color" id="job_category_link"
                                        href="{{ route('JobCategory') }}">
                                        Job
                                        Categories</a>
                                    @endcan
                                    @can('job-employment-status-list')
                                    <a class="nav-link p-0 px-3 py-1 sidebar-text-color" id="employment_link"
                                        href="{{ route('EmploymentStatus') }}">Job
                                        Employment Status</a>
                                    @endcan
                                    @can('shift-list')
                                    <a class="nav-link p-0 px-3 py-1 sidebar-text-color" id="shift_link"
                                        href="{{ route('Shift') }}">Shifts</a>
                                    @endcan
                                    @can('work-shift-list')
                                    <a class="nav-link p-0 px-3 py-1 sidebar-text-color" id="work_shift_link"
                                        href="{{ route('ShiftType') }}">Work
                                        Shifts</a>
                                    @endcan
                                </nav>
                            </div>

                            <a class="nav-link p-0 px-3 py-2 sidebar-text-color" id="employee_add_link"
                            href="{{ route('addEmployee') }}">
                            <div class="nav-link-icon"></div>
                            {{ __('Office Staff') }}
                        </a>

                        <a class="nav-link p-0 px-3 py-2 sidebar-text-color" id="securityemployee_add_link"
                        href="{{ route('addEmployeesecurity') }}">
                        <div class="nav-link-icon"></div>
                        {{ __('Security Staff') }}
                    </a>


                    <a class="nav-link p-0 px-3 py-1 collapsed sidebar-text-color" id="employee_request_main_nav_link"
                        href="#" data-toggle="collapse" data-target="#employee_request_collapse" aria-expanded="false"
                        aria-controls="collapseDashboards">
                        <div class="nav-link-icon"></div>
                        Employee Request
                        <div class="sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                    </a>
                    <div class="collapse" id="employee_request_collapse" data-parent="#accordionSidenavPages">
                        <nav class="sidenav-menu-nested nav accordion" id="employee_request_submenu">

                            <a class="nav-link p-0 px-3 py-1 collapsed sidebar-text-color" id="deaddonationlist"
                                href="#" data-toggle="collapse" data-target="#deaddonationlistdrop"
                                aria-expanded="false" aria-controls="collapseDashboards">
                                <div class="nav-link-icon"></div>
                                Dead Donation
                                <div class="sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                            </a>
                            <div class="collapse" id="deaddonationlistdrop" data-parent="#accordionSidenavPages">
                                <nav class="sidenav-menu-nested nav accordion" id="donation_submenu">
                                    @can('Deaddonation-list')
                                    <a class="nav-link p-0 px-3 py-1 sidebar-text-color" id="deaddonation_link"
                                        href="{{ route('deaddonation')}}"> Dead Donation</a>
                                    @endcan
                                    @can('Deaddonationallocation-list')
                                    <a href="{{ route('assignallocation')}}" id="assignallocation_link"
                                        class="nav-link p-0 px-3 py-1 sidebar-text-color"> Assign
                                        Allocation</a>
                                    @endcan
                                    @can('Deaddonationincomplete-list')
                                    <a href="{{ route('incomplete')}}" id="incomplete_link"
                                        class="nav-link p-0 px-3 py-1 sidebar-text-color"> Incomplete</a>
                                    @endcan
                                    @can('Deaddonationlastallocation-list')
                                    <a href="{{ route('lastallocation')}}" id="lastallocation_link"
                                        class="nav-link p-0 px-3 py-1 sidebar-text-color"> last
                                        Allocation</a>
                                    @endcan
                                    @can('Deaddonationdetail-view')
                                    <a href="{{ route('deaddonationdetail')}}" id="deaddonationdetail_link"
                                        class="nav-link p-0 px-3 py-1 sidebar-text-color"> Dead
                                        Donation Details</a>
                                    @endcan

                                </nav>
                            </div>
                            <a class="nav-link p-0 px-3 py-2 sidebar-text-color" id="travelrequest_link"
                                href="{{ route('travelrequest') }}">
                                <div class="nav-link-icon"></div>
                                {{ __('Travel Request') }}
                            </a>

                            <a class="nav-link p-0 px-3 py-2 sidebar-text-color" id="Boardingfees_link"
                                href="{{ route('boardingfees') }}">
                                <div class="nav-link-icon"></div>
                                {{ __('Boardingfees') }}
                            </a>
                        </nav>
                    </div>

                </nav>
            </div>
            @endif

            @if(auth()->user()->can('customer-list') || auth()->user()->can('branch-list')
            || auth()->user()->can('customercategory-list') || auth()->user()->can('subcustomer-list')||
            auth()->user()->can('Region-list') ||
            auth()->user()->can('Sub-Region-list')||auth()->user()->can('CustomerRequest-list'))

            <a class="nav-link p-0 px-3 py-2 collapsed sidebar-text-color" href="javascript:void(0);"
                data-toggle="collapse" data-target="#collapscustomer" aria-expanded="false"
                aria-controls="collapscustomer">
                <div class="nav-link-icon"><i class="fas fa-user-tie"></i></div>
                Client Management
                <div class="sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
            </a>
            <div class="collapse" id="collapscustomer" data-parent="#accordionSidenav">
                <nav class="sidenav-menu-nested nav accordion" id="accordionSidenavPages">
                    <a class="nav-link p-0 px-3 py-1 collapsed sidebar-text-color" id="customerlist" href="#"
                        data-toggle="collapse" data-target="#customerlistdrop" aria-expanded="false"
                        aria-controls="collapseDashboards">
                        <div class="nav-link-icon"></div>
                        Master Data
                        <div class="sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                    </a>
                    <div class="collapse" id="customerlistdrop" data-parent="#accordionSidenavPages">
                        <nav class="sidenav-menu-nested nav accordion" id="customer_submenu">
                            @can('Region-list')
                            <a class="nav-link p-0 px-3 py-1 sidebar-text-color" id="region_link"
                                href="{{ route('regions')}}">Region</a>
                            @endcan
                            @can('Sub-Region-list')
                            <a href="{{ route('subregions')}}" id="subregion_link"
                                class="nav-link p-0 px-3 py-1 sidebar-text-color"> Sub Region </a>
                            @endcan
                            @can('customer-list')
                            <a class="nav-link p-0 px-3 py-1 sidebar-text-color" id="customer_link"
                                href="{{ route('customers')}}">Client</a>
                            @endcan
                            @can('subcustomer-list')
                            <a href="{{ route('subcustomers')}}" id="subcustomer_link"
                                class="nav-link p-0 px-3 py-1 sidebar-text-color"> Sub
                                Client</a>
                            @endcan
                            @can('branch-list')
                            <a href="{{ route('branchers')}}" id="cusbranch_link"
                                class="nav-link p-0 px-3 py-1 sidebar-text-color">
                                Client
                                Branch</a>
                            @endcan
                            @can('customercategory-list')
                            <a href="{{ route('cuscategory')}}" id="category_link"
                                class="nav-link p-0 px-3 py-1 sidebar-text-color"> Client
                                Category</a>
                            @endcan
                        </nav>
                    </div>

                    <a class="nav-link p-0 px-3 py-1 sidebar-text-color" id="cusreqest_link"
                        href="{{ route('customerrequest') }}">
                        <div class="nav-link-icon"></div>
                        {{ __('Authorized Cadre') }}
                    </a>
                    <a class="nav-link p-0 px-3 py-1 collapsed sidebar-text-color" href="javascript:void(0);"
                        data-toggle="collapse" data-target="#collapinvoice" aria-expanded="false"
                        aria-controls="collapinvoice">
                        <div class="nav-link-icon"></div> Invoice Management
                        <div class="sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                    </a>
                    <div class="collapse" id="collapinvoice">
                        <nav class="sidenav-menu-nested nav accordion" id="accordionSidenavPages">
                        </nav>
                    </div>
                </nav>
            </div>
            @endif

            @if(auth()->user()->can('attendance-sync') || auth()->user()->can('attendance-incomplete-data-list') ||
            auth()->user()->can('attendance-list')
            || auth()->user()->can('attendance-create') || auth()->user()->can('attendance-edit') ||
            auth()->user()->can('attendance-delete')
            || auth()->user()->can('attendance-approve') || auth()->user()->can('late-attendance-create') ||
            auth()->user()->can('late-attendance-approve')
            || auth()->user()->can('late-attendance-list') || auth()->user()->can('attendance-incomplete-data-list') ||
            auth()->user()->can('ot-approve')
            || auth()->user()->can('ot-list') || auth()->user()->can('finger-print-device-list') ||
            auth()->user()->can('finger-print-user-list')
            || auth()->user()->can('leave-list') || auth()->user()->can('leave-type-list') ||
            auth()->user()->can('leave-approve')
            || auth()->user()->can('holiday-list')||auth()->user()->can('Employee-Transfer-list')
            || auth()->user()->can('specialrequest-approvelist')|| auth()->user()->can('empattendance-list')
            || auth()->user()->can('empattendance-approvelist'))

            <a class="nav-link p-0 px-3 py-2 collapsed sidebar-text-color" href="javascript:void(0);"
                data-toggle="collapse" data-target="#collapse_employee_info" aria-expanded="false"
                aria-controls="collapse_employee_info">
                <div class="nav-link-icon"><i class="far fa-calendar-plus"></i></div>
                Attendance & Leave
                <div class="sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
            </a>
            <div class="collapse" id="collapse_employee_info" data-parent="#accordionSidenavPages">
                <nav class="sidenav-menu-nested nav accordion" id="accordionSidenavPages">
                    <a class="nav-link p-0 px-3 py-2 collapsed sidebar-text-color" href="javascript:void(0);"
                        data-toggle="collapse" data-target="#collapsattendance" aria-expanded="false"
                        aria-controls="collapsattendance">
                        <div class="nav-link-icon"></div>
                        Attendance
                        <div class="sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                    </a>
                    <div class="collapse" id="collapsattendance" data-parent="#accordionSidenav">
                        <nav class="sidenav-menu-nested nav accordion" id="accordionSidenavPages">
                            <a class="nav-link p-0 px-3 py-1 collapsed sidebar-text-color" id="attendance_main_nav_link"
                                href="javascript:void(0);" data-toggle="collapse" data-target="#attendance_collapse"
                                aria-expanded="false" aria-controls="collapseLayouts">
                                <div class="nav-link-icon"></div>
                                Office Staff
                                <div class="sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                            </a>
                            <div class="collapse" id="attendance_collapse">
                                <nav class="sidenav-menu-nested nav accordion" id="attendance_submenu">
                                    @can('attendance-sync')
                                    <a class="nav-link p-0 px-3 py-1 sidebar-text-color" id="attendance_sync_link"
                                        href="{{ route('Attendance') }}">Attendance
                                        Sync</a>
                                    @endcan
                                    @can('attendance-create')
                                    <a class="nav-link p-0 px-3 py-1 sidebar-text-color" id="attendance_add_link"
                                        href="{{ route('AttendanceEdit') }}">
                                        Attendance Add</a>
                                    @endcan
                                    @can('attendance-edit')
                                    <a class="nav-link p-0 px-3 py-1 sidebar-text-color" id="attendance_edit_link"
                                        href="{{ route('AttendanceEditBulk') }}"> Attendance Edit</a>
                                    @endcan
                                    @can('attendance-approve')
                                    <a class="nav-link p-0 px-3 py-1 sidebar-text-color" id="attendance_approve_link"
                                        href="{{ route('AttendanceApprovel') }}">Attendance Approval</a>
                                    @endcan

                                    @can('late-attendance-create')
                                    <a class="nav-link p-0 px-3 py-1 sidebar-text-color" id="late_attendance_mark_link"
                                        href="{{ route('late_attendance_by_time') }}">Late Attendance Mark</a>
                                    @endcan
                                    @can('late-attendance-approve')
                                    <a class="nav-link p-0 px-3 py-1 sidebar-text-color"
                                        id="late_attendance_approve_link"
                                        href="{{ route('late_attendance_by_time_approve') }}">Late Attendance
                                        Approve</a>
                                    @endcan
                                    @can('late-attendance-list')
                                    <a class="nav-link p-0 px-3 py-1 sidebar-text-color" id="late_attendance_link"
                                        href="{{ route('late_attendances_all') }}">Late Attendances</a>
                                    @endcan

                                    @can('attendance-incomplete-data-list')
                                    <a class="nav-link p-0 px-3 py-1 sidebar-text-color" id="incomplete_attendance_link"
                                        href="{{ route('incomplete_attendances') }}">Incomplete Attendances</a>
                                    @endcan
                                    @can('ot-approve')
                                    <a class="nav-link p-0 px-3 py-1 sidebar-text-color" id="ot_approve_link"
                                        href="{{ route('ot_approve') }}">OT
                                        Approve</a>
                                    @endcan
                                    @can('ot-list')
                                    <a class="nav-link p-0 px-3 py-1 sidebar-text-color" id="approved_ot_link"
                                        href="{{ route('ot_approved') }}">Approved
                                        OT</a>
                                    @endcan
                                    @can('finger-print-device-list')
                                    <a class="nav-link p-0 px-3 py-1 sidebar-text-color" id="finger_print_device_link"
                                        href="{{ route('FingerprintDevice') }}">Fingerprint Device</a>
                                    @endcan
                                    @can('finger-print-user-list')
                                    <a class="nav-link p-0 px-3 py-1 sidebar-text-color" id="finger_print_user_link"
                                        href="{{ route('FingerprintUser') }}">Fingerprint User</a>
                                    @endcan
                                </nav>
                            </div>

                            <a class="nav-link p-0 px-3 py-1 collapsed sidebar-text-color" id="empallocationlist"
                                href="javascript:void(0);" data-toggle="collapse" data-target="#empallocationlistdrop"
                                aria-expanded="false" aria-controls="collapseDashboards">
                                <div class="nav-link-icon"></div>
                                Security Staff
                                <div class="sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                            </a>
                            <div class="collapse" id="empallocationlistdrop" data-parent="#accordionSidenavPages">
                                <nav class="sidenav-menu-nested nav accordion" id="allocation_submenu">
                                    <!-- @can('allocation-list')
                                    <a class="nav-link p-0 px-3 py-1 sidebar-text-color" id="empallocation_link"
                                        href="{{ route('allocation')}}"> Security Allocation</a>
                                    @endcan -->
                                    @can('Employee-Transfer-list')
                                    <a class="nav-link p-0 px-3 py-1 sidebar-text-color" id="empallocation_link"
                                        href="{{ route('allocation')}}">Employee Transfer</a>
                                    @endcan
                                    <!-- @can('specialrequest-approvelist')
                                    <a href="{{ route('specialrequests')}}" id="specialrequestapprove_link"
                                        class="nav-link p-0 px-3 py-1 sidebar-text-color"> Special Request</a>
                                    @endcan -->
                                    @can('empattendance-list')
                                    <a class="nav-link p-0 px-3 py-1 sidebar-text-color" id="empattendace_link"
                                        href="{{ route('empattendance')}}"> Security Attendance</a>
                                    @endcan
                                    @can('empattendance-approvelist')
                                    <a href="{{ route('attendanceapprove')}}" id="empattendaceapprove_link"
                                        class="nav-link p-0 px-3 py-1 sidebar-text-color"> Attendance Approve</a>
                                    @endcan
                                </nav>
                            </div>
                        </nav>
                    </div>

                    <a class="nav-link p-0 px-3 py-2 collapsed sidebar-text-color" href="javascript:void(0);"
                        data-toggle="collapse" data-target="#collapsleave" aria-expanded="false"
                        aria-controls="collapsleave">
                        <div class="nav-link-icon"></div>
                        Leave
                        <div class="sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                    </a>
                    <div class="collapse" id="collapsleave" data-parent="#accordionSidenav">
                        <nav class="sidenav-menu-nested nav accordion" id="accordionSidenavPages">
                            <a class="nav-link p-0 px-3 py-1 collapsed sidebar-text-color"
                                id="leavemasters_main_nav_link" href="javascript:void(0);" data-toggle="collapse"
                                data-target="#leavemaster_collapse" aria-expanded="false"
                                aria-controls="collapseLayouts">
                                <div class="nav-link-icon"></div>
                                Master Data
                                <div class="sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                            </a>
                            <div class="collapse" id="leavemaster_collapse" data-parent="#accordionSidenavPages">
                                <nav class="sidenav-menu-nested nav accordion" id="leave_submenu">
                                    @can('leave-type-list')
                                    <a class="nav-link p-0 px-3 py-1 sidebar-text-color" id="leave_type_link"
                                        href="{{ route('LeaveType') }}">Leave
                                        Type</a>
                                    @endcan
                                    @can('holiday-list')
                                    <a class="nav-link p-0 px-3 py-1 sidebar-text-color" id="holiday_link"
                                        href="{{ route('Holiday') }}">Holiday</a>
                                    @endcan
                                </nav>
                            </div>
                            <a class="nav-link p-0 px-3 py-1 collapsed sidebar-text-color" id="leaves_main_nav_link"
                                href="javascript:void(0);" data-toggle="collapse" data-target="#leave_collapse"
                                aria-expanded="false" aria-controls="collapseLayouts">
                                <div class="nav-link-icon"></div>
                                Leave Management
                                <div class="sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                            </a>
                            <div class="collapse" id="leave_collapse" data-parent="#accordionSidenavPages">
                                <nav class="sidenav-menu-nested nav accordion" id="accordionSidenavLayout">
                                    @can('leave-list')
                                    <a class="nav-link p-0 px-3 py-1 sidebar-text-color" id="leave_apply_link"
                                        href="{{ route('LeaveApply') }}">Leave
                                        Apply</a>
                                    @endcan
                                    @can('leave-approve')
                                    <a class="nav-link p-0 px-3 py-1 sidebar-text-color" id="leave_approvals_link"
                                        href="{{ route('LeaveApprovel') }}">Leave
                                        Approvals</a>
                                    @endcan
                                </nav>
                            </div>
                        </nav>
                    </div>
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
            || auth()->user()->can('no-pay-report'))

            <a class="nav-link p-0 px-3 py-2 collapsed sidebar-text-color" href="javascript:void(0);"
                data-toggle="collapse" data-target="#collapreports" aria-expanded="false" aria-controls="collapreports">
                <div class="nav-link-icon"><i class="fas fa-file"></i></div>
                Reports
                <div class="sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
            </a>
            <div class="collapse" id="collapreports" data-parent="#accordionSidenav">
                <nav class="sidenav-menu-nested nav accordion" id="accordionSidenavPages">
                    <a class="nav-link p-0 px-3 py-1 collapsed sidebar-text-color" id="report_main_nav_link"
                        href="javascript:void(0);" data-toggle="collapse" data-target="#report_collapse"
                        aria-expanded="false" aria-controls="collapseLayouts">
                        <div class="nav-link-icon"><i class="fas fa-file"></i></div>
                        Employee Reports
                        <div class="sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                    </a>
                    <div class="collapse" id="report_collapse" data-parent="#accordionSidenavPages">
                        <nav class="sidenav-menu-nested nav accordion" id="allocation_submenu">
                            @can('employee-report')
                            <a class="nav-link p-0 px-3 py-1 sidebar-text-color" id="employees_report_link"
                                href="{{ route('EmpoloyeeReport') }}">Employees Report</a>
                            @endcan
                            @can('attendance-report')
                            <a class="nav-link p-0 px-3 py-1 sidebar-text-color" id="attendance_report_link"
                                href="{{ route('attendetreportbyemployee') }}">Attendance Report</a>
                            @endcan
                            @can('late-attendance-report')
                            <a class="nav-link p-0 px-3 py-1 sidebar-text-color" id="late_attendance_report_link"
                                href="{{ route('LateAttendance') }}">Late
                                Attendance</a>
                            @endcan
                            @can('leave-report')
                            <a class="nav-link p-0 px-3 py-1 sidebar-text-color" id="leave_report_link"
                                href="{{ route('leaveReport') }}">Leave
                                Report</a>
                            @endcan
                            @can('employee-bank-report')
                            <a class="nav-link p-0 px-3 py-1 sidebar-text-color" id="employee_bank_report_link"
                                href="{{ route('empBankReport') }}">Employee Banks</a>
                            @endcan
                            @can('leave-balance-report')
                            <a class="nav-link p-0 px-3 py-1 sidebar-text-color" id="leave_balance_report_link"
                                href="{{ route('LeaveBalance') }}">Leave Balance</a>
                            @endcan
                            @can('ot-report')
                            <a class="nav-link p-0 px-3 py-1 sidebar-text-color" id="ot_report_link"
                                href="{{ route('ot_report') }}">O.T.
                                Report</a>
                            @endcan
                            @can('no-pay-report')
                            <a class="nav-link p-0 px-3 py-1 sidebar-text-color" id="no_pay_report_link"
                                href="{{ route('no_pay_report') }}">No
                                Pay Report</a>
                            @endcan
                        </nav>
                    </div>

                </nav>
            </div>
            @endif

            @if(auth()->user()->can('EmployeePayment-list'))

            <a class="nav-link p-0 px-3 py-2 collapsed sidebar-text-color" href="javascript:void(0);"
                data-toggle="collapse" data-target="#collappayroll" aria-expanded="false" aria-controls="collappayroll">
                <div class="nav-link-icon"><i class="fas fa-money-bill-wave-alt"></i></div>
                Pay Roll
                <div class="sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
            </a>
            <div class="collapse" id="collappayroll" data-parent="#accordionSidenav">
                <nav class="sidenav-menu-nested nav accordion" id="accordionSidenavPages">
                    @can('EmployeePayment-list')
                    <a class="nav-link p-0 px-3 py-1 sidebar-text-color" id="emppayment_link"
                        href="{{ route('employeepayment') }}">
                        <div class="nav-link-icon"></div>
                        {{ __('Security Payment') }}
                    </a>
                    @endcan
                </nav>
            </div>
            @endif

            @if(auth()->user()->can('user-list') || auth()->user()->can('role-list'))

            <a class="nav-link p-0 px-3 py-2 collapsed sidebar-text-color" href="javascript:void(0);"
                data-toggle="collapse" data-target="#collapadministrator" aria-expanded="false"
                aria-controls="collapadministrator">
                <div class="nav-link-icon"><i class="fas fa-user-lock"></i></div>
                Administrator
                <div class="sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
            </a>
            <div class="collapse" id="collapadministrator" data-parent="#accordionSidenav">
                <nav class="sidenav-menu-nested nav accordion" id="accordionSidenavPages">
                    <a class="nav-link p-0 px-3 py-1 collapsed sidebar-text-color" id="administrator_main_nav_link"
                        href="javascript:void(0);" data-toggle="collapse" data-target="#administrator_collapse"
                        aria-expanded="false" aria-controls="collapseLayouts">
                        <div class="nav-link-icon"><i class="fas fa-user-lock"></i></div>
                        Administrator
                        <div class="sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                    </a>
                    <div class="collapse" id="administrator_collapse" data-parent="#accordionSidenavPages">
                        <nav class="sidenav-menu-nested nav accordion" id="admin_submenu">
                            @can('user-list')
                            <a class="nav-link p-0 px-3 py-1 sidebar-text-color" id="users_link"
                                href="{{ route('users.index') }}">Users</a>
                            @endcan
                            @can('role-list')
                            <a class="nav-link p-0 px-3 py-1 sidebar-text-color" id="roles_link"
                                href="{{ route('roles.index') }}">Roles</a>
                            @endcan
                        </nav>
                    </div>

                </nav>
            </div>
            @endif
        </div>
        <div class="sidenav-footer" style="position: fixed;
            bottom: 0;
            width: 100%;">
            <div class="sidenav-footer-content">
                <div class="sidenav-footer-subtitle sidebar-text-color">Logged in as:</div>
                <div class="sidenav-footer-title sidebar-text-color">
                    @isset(Auth::user()->name)
                    {{ Auth::user()->name }}
                    @endisset</div>
            </div>
        </div>
    </div>
</nav>