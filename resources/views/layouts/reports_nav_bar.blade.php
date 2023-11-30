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

<div class="row nowrap" style="padding-top: 5px;padding-bottom: 5px">
    @if(in_array('employee-report',$userPermissions)
    || in_array('attendance-report',$userPermissions)
    || in_array('late-attendance-report',$userPermissions)
    || in_array('leave-report',$userPermissions)
    || in_array('employee-bank-report',$userPermissions)
    || in_array('leave-balance-report',$userPermissions)
    || in_array('ot-report',$userPermissions)
    || in_array('no-pay-report',$userPermissions))
    <div class="dropdown">
        <a  role="button" data-toggle="dropdown" class="btn navbtncolor" data-target="#" href="#" id="employeereports">
            Employee Reports <span class="caret"></span></a>
            <ul class="dropdown-menu multi-level dropdownmenucolor" role="menu" aria-labelledby="dropdownMenu">
                @if(in_array('employee-report',$userPermissions))
                <li><a class="dropdown-item" href="{{ route('EmpoloyeeReport')}}">Employees Report</a></li>
                @endif
                @if(in_array('attendance-report',$userPermissions))
                <li><a class="dropdown-item" href="{{ route('attendetreportbyemployee')}}">Attendance Report</a></li>
                @endif
                @if(in_array('late-attendance-report',$userPermissions))
                <li><a class="dropdown-item" href="{{ route('LateAttendance')}}">Late Attendance</a></li>
                @endif
                @if(in_array('leave-report',$userPermissions))
                <li><a class="dropdown-item" href="{{ route('leaveReport')}}">Leave Report</a></li>
                @endif
                @if(in_array('employee-bank-report',$userPermissions))
                <li><a class="dropdown-item" href="{{ route('empBankReport')}}">Employee Banks</a></li>
                @endif
                @if(in_array('leave-balance-report',$userPermissions))
                <li><a class="dropdown-item" href="{{ route('LeaveBalance')}}">Leave Balance</a></li>
                @endif
                @if(in_array('ot-report',$userPermissions))
                <li><a class="dropdown-item" href="{{ route('ot_report')}}">O.T.Report</a></li>
                @endif
                @if(in_array('no-pay-report',$userPermissions))
                <li><a class="dropdown-item" href="{{ route('no_pay_report')}}">No Pay Report</a></li>
                @endif
            </ul>
    </div>
    @endif


    @if(in_array('Branch-Summary-Report',$userPermissions)
    || in_array('EmployeeWise-Attendance-Report',$userPermissions)
    || in_array('EmployeeWise-Attendance-Summary-Report',$userPermissions)
    || in_array('Branch-Visit-Summary-Report',$userPermissions)
    || in_array('Security-Employee-Report',$userPermissions))

<div class="dropdown">
    <a  role="button" data-toggle="dropdown" class="btn navbtncolor" data-target="#" href="#" id="securityreport">
        Security Reports <span class="caret"></span></a>

        <ul class="dropdown-menu multi-level dropdownmenucolor" role="menu" aria-labelledby="dropdownMenu">

            @if(in_array('Branch-Summary-Report',$userPermissions))
            <li><a class="dropdown-item" href="{{ route('branchsummary')}}" id="travelrequest_link">Branch Summary Report</a></li>
            @endif
            @if(in_array('EmployeeWise-Attendance-Report',$userPermissions))
            <li><a class="dropdown-item" href="{{ route('employeewiseattendance')}}" id="boardingfees_link">EmployeeWise Report</a></li>
            @endif
            @if(in_array('EmployeeWise-Attendance-Summary-Report',$userPermissions))
            <li><a class="dropdown-item" href="{{ route('employeewisesummary')}}" id="travelrequest_link">EmployeeWise Summary</a></li>
            @endif
            @if(in_array('Branch-Visit-Summary-Report',$userPermissions))
            <li><a class="dropdown-item" href="{{ route('branchvisitsummary')}}" id="boardingfees_link">Branch Visit Report</a></li>
            @endif
            @if(in_array('Security-Employee-Report',$userPermissions))
            <li><a class="dropdown-item" href="{{ route('rptsecurityemployees')}}" id="travelrequest_link">Security Employee Report</a></li>
            @endif
    
        </ul>
    </div>
    @endif
</div>

@endif