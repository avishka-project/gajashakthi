@if(in_array('EmployeePayment-list',$userPermissions))

<div class="row nowrap" style="padding-top: 5px;padding-bottom: 5px">

@if(in_array('EmployeePayment-list',$userPermissions))
<a role="button" class="btn navbtncolor"  href="{{ route('employeepayment') }}" id="emppayment_link">
    Security Payment <span class="caret"></span> </a>
@endif


</div>
@endif