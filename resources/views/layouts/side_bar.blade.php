
<nav class="sidenav shadow-right sidenavbarcolor ">
    <div class="sidenav-menu">
        <div class="nav accordion" id="accordionSidenav">
            <div class=" py-1 mt-3  sidebar-text-color">Core</div>
            <a class="nav-link p-0 px-3 py-1 sidebar-text-color" id="dashboard_link" href="{{ url('/home') }}">
                <div class="nav-link-icon"><i class="fas fa-desktop"></i></div>
                Dashboards
            </a>
            
            @if(in_array('company-list',$userPermissions)
            || in_array('Newbusinessproposal-list',$userPermissions)
            || in_array('Vehicletype-list',$userPermissions)
            || in_array('Vehicle-list',$userPermissions)
            || in_array('Vehicle-Allocate-list',$userPermissions)
            || in_array('Vehicleserviceandrepair-list',$userPermissions)
            || in_array('Supplier-list',$userPermissions)
            || in_array('Porder-list',$userPermissions)
            || in_array('Grn-list',$userPermissions)
            || in_array('StoreType-list',$userPermissions)
            || in_array('StoreList-list',$userPermissions)
            || in_array('InventoryType-list',$userPermissions)
            || in_array('InventoryList-list',$userPermissions)
            || in_array('Issue-list',$userPermissions)
            || in_array('Return-list',$userPermissions)
            || in_array('ApproveReturn-list',$userPermissions)
            || in_array('Stock-list',$userPermissions)
            || in_array('Pettycashcategory-list',$userPermissions)
            || in_array('Pettycash-list',$userPermissions)
            || in_array('Vat-list',$userPermissions)
            || in_array('Mobilebillpayment-list',$userPermissions))

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
                           @if(in_array('company-list',$userPermissions))
                            <a class="nav-link p-0 px-3 py-1 sidebar-text-color " id="company_link"
                                href="{{ route('Company') }}">Company</a>
                           @endif
                        </nav>
                    </div>

                    @if(in_array('Vat-list',$userPermissions))
                    <a class="nav-link p-0 px-3 py-1 sidebar-text-color" id="vat_link"
                        href="{{ route('vat') }}">
                        <div class="nav-link-icon"></div>
                        {{ __('Vat') }}
                    </a>
                    @endif

                    <!-- Vehicle Information -->
                    <a class="nav-link p-0 px-3 py-1 collapsed sidebar-text-color" id="vehiclelist"
                        href="javascript:void(0);" data-toggle="collapse" data-target="#vehiclelistlistdrop"
                        aria-expanded="false" aria-controls="collapseDashboards">
                        <div class="nav-link-icon"></div> Vehicle Fleet Management
                        <div class="sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                    </a>
                    <div class="collapse" id="vehiclelistlistdrop">
                        <nav class="sidenav-menu-nested nav accordion" id="accordionSidenavPages">
                            @if(in_array('Vehicletype-list',$userPermissions))
                            <a class="nav-link p-0 px-3 py-1 sidebar-text-color" id="vehicletype_link"
                                href="{{ route('vehicletype')}}"> Vehicle Type</a>
                             @endif
                            @if(in_array('Vehicle-list',$userPermissions))
                            <a class="nav-link p-0 px-3 py-1 sidebar-text-color" id="vehicle_link"
                                href="{{ route('vehicle')}}"> Vehicle</a>
                             @endif
                            @if(in_array('Vehicle-Allocate-list',$userPermissions))
                            <a href="{{ route('vehicleallocate')}}" id="vehicleassign_link"
                                class="nav-link p-0 px-3 py-1 sidebar-text-color"> Vehicle Allocation</a>
                             @endif
                            @if(in_array('Vehicleserviceandrepair-list',$userPermissions))
                            <a class="nav-link p-0 px-3 py-1 sidebar-text-color" id="vehicleserviceandrepair_link"
                                href="{{ route('vehicleserviceandrepair') }}"> Vehicle Service & Repair
                            </a>
                             @endif
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
                                    @if(in_array('StoreType-list',$userPermissions))
                                    <a class="nav-link p-0 px-3 py-1 sidebar-text-color" id="store_type_link"
                                        href="{{ route('storetype')}}"> Store Type</a>
                                     @endif
                                    @if(in_array('StoreList-list',$userPermissions))
                                    <a href="{{ route('storelist')}}" id="store_list_link"
                                        class="nav-link p-0 px-3 py-1 sidebar-text-color"> Store List</a>
                                     @endif
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
                                @if(in_array('InventoryType-list',$userPermissions))
                                <a class="nav-link p-0 px-3 py-1 sidebar-text-color" id="inventory_type_link"
                                    href="{{ route('inventorytype')}}"> Inventory Type</a>
                                 @endif
                                @if(in_array('InventoryList-list',$userPermissions))
                                <a href="{{ route('inventorylist')}}" id="inventory_list_link"
                                    class="nav-link p-0 px-3 py-1 sidebar-text-color"> Inventory List</a>
                                 @endif
                            </nav>
                        </div>
                            @if(in_array('Supplier-list',$userPermissions))
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
                                @if(in_array('Porder-list',$userPermissions))
                                <a class="nav-link p-0 px-3 py-1 sidebar-text-color" id="item_category_link"
                                    href="{{ route('porder')}}"> Purchase Order</a>
                                 @endif
                                @if(in_array('Grn-list',$userPermissions))
                                <a href="{{ route('grn')}}" id="item_link"
                                    class="nav-link p-0 px-3 py-1 sidebar-text-color"> GRN</a>
                                 @endif
                            </nav>
                        </div>
                            @if(in_array('Issue-list',$userPermissions))
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
                                @if(in_array('Return-list',$userPermissions))
                                <a class="nav-link p-0 px-3 py-1 sidebar-text-color" id="return_link"
                                    href="{{ route('return')}}"> Add Return</a>
                                 @endif
                                @if(in_array('ApproveReturn-list',$userPermissions))
                                <a href="{{ route('approvereturn')}}" id="approvereturn_link"
                                    class="nav-link p-0 px-3 py-1 sidebar-text-color"> Approve Return</a>
                                 @endif
                            </nav>
                        </div>

                        @if(in_array('Stock-list',$userPermissions))
                            <a class="nav-link p-0 px-3 py-1 sidebar-text-color" id="stock_link"
                                href="{{ route('stock') }}">
                                <div class="nav-link-icon"></div>
                                {{ __('Stock') }}
                            </a>
                            @endif
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
                            @if(in_array('Mobilebillpayment-list',$userPermissions))
                            <a class="nav-link p-0 px-3 py-1 sidebar-text-color" id="mobilebillpayment_link"
                                href="{{ route('mobilebillpayment')}}"> Mobile Bill Payment</a>
                             @endif
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
                            @if(in_array('Pettycashcategory-list',$userPermissions))
                            <a class="nav-link p-0 px-3 py-1 sidebar-text-color" id="pettycashcategory_link"
                                href="{{ route('pettycashcategory')}}"> Petty Cash Category</a>
                             @endif
                            @if(in_array('Pettycash-list',$userPermissions))
                            <a class="nav-link p-0 px-3 py-1 sidebar-text-color" id="pettycash_link"
                                href="{{ route('pettycash')}}"> Petty Cash</a>
                             @endif
                        </nav>
                    </div>
                </nav>
            </div>
            @endif

            @if(in_array('employee-list',$userPermissions)
            || in_array('bank-list',$userPermissions)
            || in_array('skill-list',$userPermissions)
            || in_array('job-title-list',$userPermissions)
            || in_array('pay-grade-list',$userPermissions)
            || in_array('job-category-list',$userPermissions)
            || in_array('job-employment-status-list',$userPermissions)
            || in_array('shift-list',$userPermissions)
            || in_array('work-shift-list',$userPermissions)
            || in_array('Deaddonation-list',$userPermissions)
            || in_array('Deaddonationallocation-list',$userPermissions)
            || in_array('Deaddonationincomplete-list',$userPermissions)
            || in_array('Deaddonationlastallocation-list',$userPermissions)
            || in_array('Deaddonationdetail-view',$userPermissions)
            || in_array('allocation-list',$userPermissions)
            || in_array('Travelrequest-list',$userPermissions)
            || in_array('Boardingfees-list',$userPermissions))

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
                                    @if(in_array('bank-list',$userPermissions))
                                    <a class="nav-link p-0 px-3 py-1 sidebar-text-color" id="bank_link"
                                        href="{{ route('Bank') }}">Bank</a>
                                    @endif
                                    @if(in_array('skill-list',$userPermissions))
                                    <a class="nav-link p-0 px-3 py-1 sidebar-text-color" id="skill_link"
                                        href="{{ route('Skill') }}">Skill</a>
                                    @endif
                                    @if(in_array('job-title-list',$userPermissions))
                                    <a class="nav-link p-0 px-3 py-1 sidebar-text-color" id="job_title_link"
                                        href="{{ route('JobTitle') }}">Job
                                        Titles</a>
                                    @endif
                                    @if(in_array('pay-grade-list',$userPermissions))
                                    <a class="nav-link p-0 px-3 py-1 sidebar-text-color" id="pay_grade_link"
                                        href="{{ route('PayGrade') }}">Pay
                                        Grades</a>
                                    @endif
                                    @if(in_array('job-category-list',$userPermissions))
                                    <a class="nav-link p-0 px-3 py-1 sidebar-text-color" id="job_category_link"
                                        href="{{ route('JobCategory') }}">
                                        Job
                                        Categories</a>
                                    @endif
                                    @if(in_array('job-employment-status-list',$userPermissions))
                                    <a class="nav-link p-0 px-3 py-1 sidebar-text-color" id="employment_link"
                                        href="{{ route('EmploymentStatus') }}">Job
                                        Employment Status</a>
                                    @endif
                                    @if(in_array('shift-list',$userPermissions))
                                    <a class="nav-link p-0 px-3 py-1 sidebar-text-color" id="shift_link"
                                        href="{{ route('Shift') }}">Shifts</a>
                                    @endif
                                    @if(in_array('work-shift-list',$userPermissions))
                                    <a class="nav-link p-0 px-3 py-1 sidebar-text-color" id="work_shift_link"
                                        href="{{ route('ShiftType') }}">Work
                                        Shifts</a>
                                    @endif
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
                                    @if(in_array('Deaddonation-list',$userPermissions))
                                    <a class="nav-link p-0 px-3 py-1 sidebar-text-color" id="deaddonation_link"
                                        href="{{ route('deaddonation')}}"> Dead Donation</a>
                                    @endif
                                    @if(in_array('Deaddonationallocation-list',$userPermissions))
                                    <a href="{{ route('assignallocation')}}" id="assignallocation_link"
                                        class="nav-link p-0 px-3 py-1 sidebar-text-color"> First Payment</a>
                                    @endif
                                    @if(in_array('Deaddonationincomplete-list',$userPermissions))
                                    <a href="{{ route('incomplete')}}" id="incomplete_link"
                                        class="nav-link p-0 px-3 py-1 sidebar-text-color"> Document Verification</a>
                                    @endif
                                    @if(in_array('Deaddonationlastallocation-list',$userPermissions))
                                    <a href="{{ route('lastallocation')}}" id="lastallocation_link"
                                        class="nav-link p-0 px-3 py-1 sidebar-text-color"> Second payment</a>
                                    @endif
                                    @if(in_array('Deaddonationdetail-view',$userPermissions))
                                    <a href="{{ route('deaddonationdetail')}}" id="deaddonationdetail_link"
                                        class="nav-link p-0 px-3 py-1 sidebar-text-color"> Dead
                                        Donation Details</a>
                                    @endif

                                </nav>
                            </div>
                            @if(in_array('Travelrequest-list',$userPermissions))
                            <a class="nav-link p-0 px-3 py-2 sidebar-text-color" id="travelrequest_link"
                                href="{{ route('travelrequest') }}">
                                <div class="nav-link-icon"></div>
                                {{ __('Travel Request') }}
                            </a>
                            @endif
                            @if(in_array('Boardingfees-list',$userPermissions))
                            <a class="nav-link p-0 px-3 py-2 sidebar-text-color" id="Boardingfees_link"
                                href="{{ route('boardingfees') }}">
                                <div class="nav-link-icon"></div>
                                {{ __('Boardingfees') }}
                            </a>
                            @endif
                            @if(in_array('Boardingfees-list',$userPermissions))
                            <a class="nav-link p-0 px-3 py-2 sidebar-text-color" id="Accommodationfees_link"
                                href="{{ route('accommodationfees') }}">
                                <div class="nav-link-icon"></div>
                                {{ __('Accommodation Fee') }}
                            </a>
                            @endif
                        </nav>
                    </div>

                </nav>
            </div>
            @endif

            @if(in_array('customer-list',$userPermissions)
            || in_array('branch-list',$userPermissions)
            || in_array('customercategory-list',$userPermissions)
            || in_array('subcustomer-list',$userPermissions)
            || in_array('Region-list',$userPermissions)
            || in_array('Sub-Region-list',$userPermissions)
            || in_array('CustomerRequest-list',$userPermissions))

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
                            @if(in_array('Region-list',$userPermissions))
                            <a class="nav-link p-0 px-3 py-1 sidebar-text-color" id="region_link"
                                href="{{ route('regions')}}">Region</a>
                             @endif
                            @if(in_array('Sub-Region-list',$userPermissions))
                            <a href="{{ route('subregions')}}" id="subregion_link"
                                class="nav-link p-0 px-3 py-1 sidebar-text-color"> Sub Region </a>
                             @endif
                            @if(in_array('customer-list',$userPermissions))
                            <a class="nav-link p-0 px-3 py-1 sidebar-text-color" id="customer_link"
                                href="{{ route('customers')}}">Client</a>
                             @endif
                            @if(in_array('subcustomer-list',$userPermissions))
                            <a href="{{ route('subcustomers')}}" id="subcustomer_link"
                                class="nav-link p-0 px-3 py-1 sidebar-text-color"> Sub
                                Client</a>
                             @endif
                            @if(in_array('branch-list',$userPermissions))
                            <a href="{{ route('branchers')}}" id="cusbranch_link"
                                class="nav-link p-0 px-3 py-1 sidebar-text-color">
                                Client
                                Branch</a>
                             @endif
                            @if(in_array('customercategory-list',$userPermissions))
                            <a href="{{ route('cuscategory')}}" id="category_link"
                                class="nav-link p-0 px-3 py-1 sidebar-text-color"> Client
                                Category</a>
                             @endif
                        </nav>
                    </div>
                    @if(in_array('CustomerRequest-list',$userPermissions))
                    <a class="nav-link p-0 px-3 py-1 sidebar-text-color" id="cusreqest_link"
                        href="{{ route('customerrequest') }}">
                        <div class="nav-link-icon"></div>
                        {{ __('Authorized Cadre') }}
                    </a>
                    @endif
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


            @if(in_array('attendance-sync',$userPermissions)
            || in_array('attendance-incomplete-data-list',$userPermissions)
            || in_array('attendance-list',$userPermissions)
            || in_array('attendance-create',$userPermissions)
            || in_array('attendance-edit',$userPermissions)
            || in_array('attendance-delete',$userPermissions)
            || in_array('attendance-approve',$userPermissions)
            || in_array('late-attendance-create',$userPermissions)
            || in_array('late-attendance-approve',$userPermissions)
            || in_array('late-attendance-list',$userPermissions)
            || in_array('ot-approve',$userPermissions)
            || in_array('ot-list',$userPermissions)
            || in_array('finger-print-device-list',$userPermissions)
            || in_array('finger-print-user-list',$userPermissions)
            || in_array('leave-list',$userPermissions)
            || in_array('leave-type-list',$userPermissions)
            || in_array('leave-approve',$userPermissions)
            || in_array('holiday-list',$userPermissions)
            || in_array('Employee-Transfer-list',$userPermissions)
            || in_array('specialrequest-approvelist',$userPermissions)
            || in_array('empattendance-list',$userPermissions)
            || in_array('empattendance-approvelist',$userPermissions))

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
                                    @if(in_array('attendance-sync',$userPermissions))
                                    <a class="nav-link p-0 px-3 py-1 sidebar-text-color" id="attendance_sync_link"
                                        href="{{ route('Attendance') }}">Attendance
                                        Sync</a>
                                    @endif
                                    @if(in_array('attendance-create',$userPermissions))
                                    <a class="nav-link p-0 px-3 py-1 sidebar-text-color" id="attendance_add_link"
                                        href="{{ route('AttendanceEdit') }}">
                                        Attendance Add</a>
                                    @endif
                                    @if(in_array('attendance-edit',$userPermissions))
                                    <a class="nav-link p-0 px-3 py-1 sidebar-text-color" id="attendance_edit_link"
                                        href="{{ route('AttendanceEditBulk') }}"> Attendance Edit</a>
                                    @endif
                                    @if(in_array('attendance-approve',$userPermissions))
                                    <a class="nav-link p-0 px-3 py-1 sidebar-text-color" id="attendance_approve_link"
                                        href="{{ route('AttendanceApprovel') }}">Attendance Approval</a>
                                    @endif

                                    @if(in_array('late-attendance-create',$userPermissions))
                                    <a class="nav-link p-0 px-3 py-1 sidebar-text-color" id="late_attendance_mark_link"
                                        href="{{ route('late_attendance_by_time') }}">Late Attendance Mark</a>
                                    @endif
                                    @if(in_array('late-attendance-approve',$userPermissions))
                                    <a class="nav-link p-0 px-3 py-1 sidebar-text-color"
                                        id="late_attendance_approve_link"
                                        href="{{ route('late_attendance_by_time_approve') }}">Late Attendance
                                        Approve</a>
                                    @endif
                                    @if(in_array('late-attendance-list',$userPermissions))
                                    <a class="nav-link p-0 px-3 py-1 sidebar-text-color" id="late_attendance_link"
                                        href="{{ route('late_attendances_all') }}">Late Attendances</a>
                                    @endif

                                    @if(in_array('attendance-incomplete-data-list',$userPermissions))
                                    <a class="nav-link p-0 px-3 py-1 sidebar-text-color" id="incomplete_attendance_link"
                                        href="{{ route('incomplete_attendances') }}">Incomplete Attendances</a>
                                    @endif
                                    @if(in_array('ot-approve',$userPermissions))
                                    <a class="nav-link p-0 px-3 py-1 sidebar-text-color" id="ot_approve_link"
                                        href="{{ route('ot_approve') }}">OT
                                        Approve</a>
                                    @endif
                                    @if(in_array('ot-list',$userPermissions))
                                    <a class="nav-link p-0 px-3 py-1 sidebar-text-color" id="approved_ot_link"
                                        href="{{ route('ot_approved') }}">Approved
                                        OT</a>
                                    @endif
                                    @if(in_array('finger-print-device-list',$userPermissions))
                                    <a class="nav-link p-0 px-3 py-1 sidebar-text-color" id="finger_print_device_link"
                                        href="{{ route('FingerprintDevice') }}">Fingerprint Device</a>
                                    @endif
                                    @if(in_array('finger-print-user-list',$userPermissions))
                                    <a class="nav-link p-0 px-3 py-1 sidebar-text-color" id="finger_print_user_link"
                                        href="{{ route('FingerprintUser') }}">Fingerprint User</a>
                                    @endif
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
                                    @if(in_array('Employee-Transfer-list',$userPermissions))
                                    <a class="nav-link p-0 px-3 py-1 sidebar-text-color" id="empallocation_link"
                                        href="{{ route('allocation')}}">Employee Transfer</a>
                                    @endif
                                    <!-- @can('specialrequest-approvelist')
                                    <a href="{{ route('specialrequests')}}" id="specialrequestapprove_link"
                                        class="nav-link p-0 px-3 py-1 sidebar-text-color"> Special Request</a>
                                    @endcan -->
                                    @if(in_array('empattendance-list',$userPermissions))
                                    <a class="nav-link p-0 px-3 py-1 sidebar-text-color" id="empattendace_link"
                                        href="{{ route('empattendance')}}"> Security Attendance</a>
                                    @endif
                                    @if(in_array('empattendance-approvelist',$userPermissions))
                                    <a href="{{ route('attendanceapprove')}}" id="empattendaceapprove_link"
                                        class="nav-link p-0 px-3 py-1 sidebar-text-color"> Attendance Approve</a>
                                    @endif
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
                                    @if(in_array('leave-type-list',$userPermissions))
                                    <a class="nav-link p-0 px-3 py-1 sidebar-text-color" id="leave_type_link"
                                        href="{{ route('LeaveType') }}">Leave
                                        Type</a>
                                    @endif
                                    @if(in_array('holiday-list',$userPermissions))
                                    <a class="nav-link p-0 px-3 py-1 sidebar-text-color" id="holiday_link"
                                        href="{{ route('Holiday') }}">Holiday</a>
                                    @endif
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
                                    @if(in_array('leave-list',$userPermissions))
                                    <a class="nav-link p-0 px-3 py-1 sidebar-text-color" id="leave_apply_link"
                                        href="{{ route('LeaveApply') }}">Leave
                                        Apply</a>
                                    @endif
                                    @if(in_array('leave-approve',$userPermissions))
                                    <a class="nav-link p-0 px-3 py-1 sidebar-text-color" id="leave_approvals_link"
                                        href="{{ route('LeaveApprovel') }}">Leave
                                        Approvals</a>
                                    @endif
                                </nav>
                            </div>
                        </nav>
                    </div>
                </nav>
            </div>
            @endif


            @if(in_array('employee-report',$userPermissions)
            || in_array('attendance-report',$userPermissions)
            || in_array('late-attendance-report',$userPermissions)
            || in_array('leave-report',$userPermissions)
            || in_array('employee-bank-report',$userPermissions)
            || in_array('leave-balance-report',$userPermissions)
            || in_array('ot-report',$userPermissions)
            || in_array('no-pay-report',$userPermissions)
            || in_array('Branch-Summary-Report',$userPermissions)
            || in_array('EmployeeWise-Attendance-Report',$userPermissions)
            || in_array('EmployeeWise-Attendance-Summary-Report',$userPermissions)
            || in_array('Branch-Visit-Summary-Report',$userPermissions)
            || in_array('Security-Employee-Report',$userPermissions))

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
                            @if(in_array('employee-report',$userPermissions))
                            <a class="nav-link p-0 px-3 py-1 sidebar-text-color" id="employees_report_link"
                                href="{{ route('EmpoloyeeReport') }}">Employees Report</a>
                              @endif
                            @if(in_array('attendance-report',$userPermissions))
                            <a class="nav-link p-0 px-3 py-1 sidebar-text-color" id="attendance_report_link"
                                href="{{ route('attendetreportbyemployee') }}">Attendance Report</a>
                              @endif
                           @if(in_array('late-attendance-report',$userPermissions))
                            <a class="nav-link p-0 px-3 py-1 sidebar-text-color" id="late_attendance_report_link"
                                href="{{ route('LateAttendance') }}">Late
                                Attendance</a>
                              @endif
                            @if(in_array('leave-report',$userPermissions))
                            <a class="nav-link p-0 px-3 py-1 sidebar-text-color" id="leave_report_link"
                                href="{{ route('leaveReport') }}">Leave
                                Report</a>
                              @endif
                            @if(in_array('employee-bank-report',$userPermissions))
                            <a class="nav-link p-0 px-3 py-1 sidebar-text-color" id="employee_bank_report_link"
                                href="{{ route('empBankReport') }}">Employee Banks</a>
                              @endif
                            @if(in_array('leave-balance-report',$userPermissions))
                            <a class="nav-link p-0 px-3 py-1 sidebar-text-color" id="leave_balance_report_link"
                                href="{{ route('LeaveBalance') }}">Leave Balance</a>
                              @endif
                            @if(in_array('ot-report',$userPermissions))
                            <a class="nav-link p-0 px-3 py-1 sidebar-text-color" id="ot_report_link"
                                href="{{ route('ot_report') }}">O.T.
                                Report</a>
                              @endif
                            @if(in_array('no-pay-report',$userPermissions))
                            <a class="nav-link p-0 px-3 py-1 sidebar-text-color" id="no_pay_report_link"
                                href="{{ route('no_pay_report') }}">No
                                Pay Report</a>
                              @endif
                        </nav>
                    </div>
                    <a class="nav-link p-0 px-3 py-1 collapsed sidebar-text-color" id="report_main_nav_link"
                    href="javascript:void(0);" data-toggle="collapse" data-target="#securityreport_collapse"
                    aria-expanded="false" aria-controls="collapseLayouts">
                        <div class="nav-link-icon"><i class="fas fa-file"></i></div>
                        Security Reports
                        <div class="sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                    </a>
                    <div class="collapse" id="securityreport_collapse" data-parent="#accordionSidenavPages">
                        <nav class="sidenav-menu-nested nav accordion" id="allocation_submenu">
                            @if(in_array('Branch-Summary-Report',$userPermissions))
                            <a class="nav-link p-0 px-3 py-1 sidebar-text-color" id="branch_report_link"
                                href="{{ route('branchsummary') }}">Branch Summary Report</a>
                              @endif
                            @if(in_array('EmployeeWise-Attendance-Report',$userPermissions))
                            <a class="nav-link p-0 px-3 py-1 sidebar-text-color" id="empwise_report_link"
                                href="{{ route('employeewiseattendance') }}">EmployeeWise Report</a>
                              @endif
                            @if(in_array('EmployeeWise-Attendance-Summary-Report',$userPermissions))
                            <a class="nav-link p-0 px-3 py-1 sidebar-text-color" id="empwise_summary_link"
                                href="{{ route('employeewisesummary') }}">EmployeeWise Summary</a>
                              @endif
                            @if(in_array('Branch-Visit-Summary-Report',$userPermissions))
                            <a class="nav-link p-0 px-3 py-1 sidebar-text-color" id="branch_visit_link"
                                href="{{ route('branchvisitsummary') }}">Branch Visit Report</a>
                              @endif
                            @if(in_array('Security-Employee-Report',$userPermissions))
                            <a class="nav-link p-0 px-3 py-1 sidebar-text-color" id="rptsecurity_employee_link"
                                href="{{ route('rptsecurityemployees') }}">Security Employee Report</a>
                              @endif
                        </nav>
                    </div>
                </nav>
            </div>
            @endif


            @if(in_array('EmployeePayment-list',$userPermissions))

            <a class="nav-link p-0 px-3 py-2 collapsed sidebar-text-color" href="javascript:void(0);"
                data-toggle="collapse" data-target="#collappayroll" aria-expanded="false" aria-controls="collappayroll">
                <div class="nav-link-icon"><i class="fas fa-money-bill-wave-alt"></i></div>
                Pay Roll
                <div class="sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
            </a>
            <div class="collapse" id="collappayroll" data-parent="#accordionSidenav">
                <nav class="sidenav-menu-nested nav accordion" id="accordionSidenavPages">
                    @if(in_array('EmployeePayment-list',$userPermissions))
                    <a class="nav-link p-0 px-3 py-1 sidebar-text-color" id="emppayment_link"
                        href="{{ route('employeepayment') }}">
                        <div class="nav-link-icon"></div>
                        {{ __('Security Payment') }}
                    </a>
                    @endif
                </nav>
            </div>
            @endif


            @if(in_array('user-list',$userPermissions)
            || in_array('role-list',$userPermissions))

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
                            @if(in_array('user-list',$userPermissions))
                            <a class="nav-link p-0 px-3 py-1 sidebar-text-color" id="users_link"
                                href="{{ route('users.index') }}">Users</a>
                            @endif
                            @if(in_array('role-list',$userPermissions))
                            <a class="nav-link p-0 px-3 py-1 sidebar-text-color" id="roles_link"
                                href="{{ route('roles.index') }}">Roles</a>
                            @endif
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