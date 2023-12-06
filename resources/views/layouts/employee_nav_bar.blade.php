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
|| in_array('Deaddonationdetail-list',$userPermissions)
|| in_array('allocation-list',$userPermissions)
|| in_array('Travelrequest-list',$userPermissions)
|| in_array('Boardingfees-list',$userPermissions))
<div class="row nowrap" style="padding-top: 5px;padding-bottom: 5px">

    @if(in_array('bank-list',$userPermissions)|| in_array('skill-list',$userPermissions)
    || in_array('job-title-list',$userPermissions)|| in_array('pay-grade-list',$userPermissions)
    || in_array('job-category-list',$userPermissions)|| in_array('job-employment-status-list',$userPermissions)
    || in_array('shift-list',$userPermissions)|| in_array('work-shift-list',$userPermissions))

    <div class="dropdown">
        <a  role="button" data-toggle="dropdown" class="btn navbtncolor" data-target="#" href="#" id="employeemaster">
            Master Data <span class="caret"></span></a>
            <ul class="dropdown-menu multi-level dropdownmenucolor" role="menu" aria-labelledby="dropdownMenu">
                @if(in_array('bank-list',$userPermissions))
                <li><a class="dropdown-item" href="{{ route('Bank')}}">Bank</a></li>
                @endif
                @if(in_array('skill-list',$userPermissions))
                <li><a class="dropdown-item" href="{{ route('Skill')}}">Skill</a></li>
                @endif
                @if(in_array('job-title-list',$userPermissions))
                <li><a class="dropdown-item" href="{{ route('JobTitle')}}">Job Titles</a></li>
                @endif
                @if(in_array('pay-grade-list',$userPermissions))
                <li><a class="dropdown-item" href="{{ route('PayGrade')}}">Pay Grades</a></li>
                @endif
                @if(in_array('job-category-list',$userPermissions))
                <li><a class="dropdown-item" href="{{ route('JobCategory')}}">Job Categories</a></li>
                @endif
                @if(in_array('job-employment-status-list',$userPermissions))
                <li><a class="dropdown-item" href="{{ route('EmploymentStatus')}}">Job Employment Status</a></li>
                @endif
                @if(in_array('shift-list',$userPermissions))
                <li><a class="dropdown-item" href="{{ route('Shift')}}">Shifts</a></li>
                @endif
                @if(in_array('work-shift-list',$userPermissions))
                <li><a class="dropdown-item" href="{{ route('ShiftType')}}">Work Shifts</a></li>
                @endif
            </ul>
    </div>
    @endif

    @if(in_array('employee-list',$userPermissions))
    <a role="button" class="btn navbtncolor"  href="{{ route('addEmployee') }}" id="employee_add_link">
        Office Staff <span class="caret"></span> </a>

        <a role="button" class="btn navbtncolor"  href="{{ route('addEmployeesecurity') }}" id="securityemployee_add_link">
            Security Staff <span class="caret"></span> </a>
    @endif
    
    @if(in_array('Deaddonation-list',$userPermissions)|| in_array('Deaddonationallocation-list',$userPermissions)
    || in_array('Deaddonationincomplete-list',$userPermissions)|| in_array('Deaddonationlastallocation-list',$userPermissions)
    || in_array('Deaddonationdetail-list',$userPermissions)|| in_array('allocation-list',$userPermissions)
    || in_array('Travelrequest-list',$userPermissions)|| in_array('Boardingfees-list',$userPermissions)
    || in_array('Salaryadvances-list',$userPermissions) || in_array('Employeeloans-list',$userPermissions)
    || in_array('Gratuityrequests-list',$userPermissions))
<div class="dropdown">
<a  role="button" data-toggle="dropdown" class="btn navbtncolor" data-target="#" href="#" id="employeerequest">
    Employee Request <span class="caret"></span></a>

    <ul class="dropdown-menu multi-level dropdownmenucolor" role="menu" aria-labelledby="dropdownMenu">
       
        <li class="dropdown-submenu dropdown-item">
            <a class="dropdown-submenu dropdown-item submenulistpadding" tabindex="-1" href="#">Dead Donation</a>
        <ul class="dropdown-menu dropdownmenucolor">
            @if(in_array('Deaddonation-list',$userPermissions))
            <li><a class="dropdown-item" href="{{ route('deaddonation')}}">Dead Donation</a></li>
            @endif
            @if(in_array('Deaddonationallocation-list',$userPermissions))
            <li><a class="dropdown-item" href="{{ route('assignallocation')}}">First Payment</a></li>
            @endif
            @if(in_array('Deaddonationincomplete-list',$userPermissions))
            <li><a class="dropdown-item" href="{{ route('incomplete')}}">Document Verification</a></li>
            @endif
            @if(in_array('Deaddonationlastallocation-list',$userPermissions))
            <li><a class="dropdown-item" href="{{ route('lastallocation')}}">Second payment</a></li>
            @endif
            @if(in_array('Deaddonationdetail-list',$userPermissions))
            <li><a class="dropdown-item" href="{{ route('deaddonationdetail')}}">Dead Donation Details</a></li>
            @endif
        </ul>
    </li>
        @if(in_array('Travelrequest-list',$userPermissions))
        <li><a class="dropdown-item" href="{{ route('travelrequest')}}" id="travelrequest_link">Travel Request</a></li>
        @endif
        @if(in_array('Boardingfees-list',$userPermissions))
        <li><a class="dropdown-item" href="{{ route('boardingfees')}}" id="boardingfees_link">Boardingfees</a></li>
        @endif
        @if(in_array('Salaryadvances-list',$userPermissions))
        <li><a class="dropdown-item" href="{{ route('salaryadvance')}}" id="Salaryadvances_link">Salary Advances</a></li>
        @endif
        @if(in_array('Employeeloans-list',$userPermissions))
        <li><a class="dropdown-item" href="{{ route('employeeloan')}}" id="Employeeloans_link">Employee Loans</a></li>
        @endif
        @if(in_array('Gratuityrequests-list',$userPermissions))
        <li><a class="dropdown-item" href="{{ route('gratuityrequest')}}" id="Gratuityrequests_link">Gratuity Requests</a></li>
        @endif

    </ul>
</div>
    @endif
</div>
@endif