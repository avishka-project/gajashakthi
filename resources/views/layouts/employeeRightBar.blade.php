<div class="col-lg-3">
    <div class="card shadow-none">
        @isset($employee_picture)
        <a href="../public/images/{{ $employee_picture->emp_pic_filename }}">
            <img class="card-img-top" src="../public/images/{{ $employee_picture->emp_pic_filename }}">
        </a>
        @endisset
        <ul class="list-group list-group-flush">
            <li class="list-group-item py-1 px-2"><a href="{{ url('/viewEmployee/') }}/{{$id}}" class="text-decoration-none text-dark"><i class="fas fa-paper-plane mr-2"></i>Personal Details</a></li>
            <li class="list-group-item py-1 px-2"><a href="{{ url('/viewEmergencyContacts/') }}/{{$id}}" class="text-decoration-none text-dark"><i class="fas fa-paper-plane mr-2"></i>Emergency Contacts</a></li>
            <li class="list-group-item py-1 px-2"><a href="{{ url('/viewDependents/') }}/{{$id}}" class="text-decoration-none text-dark"><i class="fas fa-paper-plane mr-2"></i>Dependents</a></li>
            <li class="list-group-item py-1 px-2"><a href="{{ url('/viewImmigration/') }}/{{$id}}" class="text-decoration-none text-dark"><i class="fas fa-paper-plane mr-2"></i>Immigration</a></li>
            <li class="list-group-item py-1 px-2"><a href="{{ url('/viewSalaryDetails/') }}/{{$id}}" class="text-decoration-none text-dark"><i class="fas fa-paper-plane mr-2"></i>Salary</a></li>
            <li class="list-group-item py-1 px-2"><a href="{{ url('/viewQualifications/') }}/{{$id}}" class="text-decoration-none text-dark"><i class="fas fa-paper-plane mr-2"></i>Qualifications</a></li>
            <li class="list-group-item py-1 px-2"><a href="{{ url('/viewPassport/') }}/{{$id}}" class="text-decoration-none text-dark"><i class="fas fa-paper-plane mr-2"></i>Passport</a></li>
            <li class="list-group-item py-1 px-2"><a href="{{ url('/viewbankDetails/') }}/{{$id}}" class="text-decoration-none text-dark"><i class="fas fa-paper-plane mr-2"></i>Bank Details</a></li>
        </ul>
    </div>
</div>