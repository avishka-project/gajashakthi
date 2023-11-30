@extends('layouts.app')

@section('content')

<main>
    <div class="page-header page-header-light bg-white shadow">
        <div class="container-fluid">
            <div class="page-header-content py-3">
                <h1 class="page-header-title">
                    <div class="page-header-icon"><i class="fas fa-user"></i></div>
                    <span>Salary Detail</span>
                </h1>
            </div>
        </div>
    </div>
    <div class="container-fluid mt-3">
        <div class="card">
            <div class="card-body p-0 p-2">
                <div class="row">
                    <div class="col-9">
                        <table class="table table-striped table-bordered table-sm small" id="dataTable">
                            <thead>
                                <tr>
                                    <th>Salary Component</th>
                                    <th>Pay Frequency</th>
                                    <th>Currency</th>
                                    <th>Amount</th>
                                    <th>Show Direct Deposit Details </th>
                                    <th>Action</th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach($employees as $employee)
                                <tr>
                                    <td>{{$employee->emp_sal_grade}}</td>
                                    <td>{{$employee->emp_sal_transaction_type}}</td>
                                    <td>{{$employee->emp_sal_currency}}</td>
                                    <td>{{$employee->emp_sal_basic_salary}}</td>
                                    <td>{{$employee->emp_sal_account}}</td>
                                    <td>
                                        @if(in_array('employee-edit',$userPermissions))
                                            <button class="btn btn-outline-primary btn-sm"><i class="fas fa-pencil-alt"></i></button>
                                            <button class="btn btn-outline-danger btn-sm"><i class="far fa-trash-alt"></i></button>
                                            @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <hr class="border-dark">
                        <form>
                            <div class="form-row">
                                <div class="col">
                                    <label class="small font-weight-bold text-dark">Select File</label>
                                    <input type="file" class="custom-file-input" id="validatedCustomFile" required>
                                </div>
                                <div class="col">
                                    <label class="small font-weight-bold text-dark">Comment</label>
                                    <textarea class="form-control" id="exampleFormControlTextarea1" rows="3"></textarea>
                                </div>
                            </div>
                            <div class="form-group mt-3">
                                @if(in_array('employee-edit',$userPermissions))
                                    <button type="submit" class="btn btn-outline-primary btn-sm fa-pull-right px-4"><i class="fas fa-save"></i>&nbsp;Save</button>
                                    @endif
                            </div>
                        </form>
                    </div>
                    @include('layouts.employeeRightBar')
                </div>
            </div>
        </div>        
    </div>        
</main>
@endsection

@section('script')
<script>
    $('#employee_main_nav_link').prop('aria-expanded', 'true').removeClass('collapsed');
    $('#employee_collapse').addClass('show');
    $('#employee_add_link').addClass('active');

    $('#dataTable').DataTable();
</script>
@endsection
