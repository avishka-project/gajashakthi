@extends('layouts.app')

@section('content')
<main>
    <div class="page-header page-header-light bg-white shadow">
        <div class="container-fluid">
            <div class="page-header-content py-3">
                <h1 class="page-header-title">
                    <div class="page-header-icon"><i class="fas fa-users"></i></div>
                    <span>Employee Details - Office Staff</span>
                </h1>
            </div>
        </div>
    </div>
    <div class="container-fluid mt-4">
        <div class="card mb-2">
            <div class="card-body">
                <form class="form-horizontal" id="formFilter">
                    <div class="form-row mb-1">
                        
                        <div class="col-md-2">
                            <label class="small font-weight-bold text-dark">Department</label>
                            <select name="department" id="department_f" class="form-control form-control-sm">
                                <option value="">Select Department</option>
                                @foreach($departments as $dept)
                                    <option value="{{$dept->id}}">{{$dept->name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="small font-weight-bold text-dark">Emp Search Option</label>
                            <select name="search_option" id="search_option" class="form-control form-control-sm">
                                <option value="serviceno">Service No</option>
                                <option value="employee_name">Employee Name</option>
                                <option value="employee_nic">Employee NIC</option>
                            </select>
                        </div>
                        <div class="col-md-3" id="serviceno_div">
                            <label class="small font-weight-bold text-dark">Service No</label>
                            <select name="serviceno" id="serviceno" class="form-control form-control-sm">
                                <!-- Options for Service No -->
                                <option value="">Service No</option>
                            </select>
                        </div>
                        
                        <div class="col-md-3" id="employee_name_div">
                            <label class="small font-weight-bold text-dark">Employee Name</label>
                            <select name="employee_name" id="employee_name" class="form-control form-control-sm">
                                <!-- Options for Employee Name -->
                                <option value="">Employee Name</option>
                            </select>
                        </div>
                        
                        <div class="col-md-3" id="employee_nic_div">
                            <label class="small font-weight-bold text-dark">Employee NIC</label>
                            <select name="employee_nic" id="employee_nic" class="form-control form-control-sm">
                                <!-- Options for Employee NIC -->
                                <option value="">Employee NIC</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="small font-weight-bold text-dark">Date : From - To</label>
                            <div class="input-group input-group-sm mb-3">
                                <input type="date" id="from_date" name="from_date" class="form-control form-control-sm border-right-0" placeholder="yyyy-mm-dd">

                                <input type="date" id="to_date" name="to_date" class="form-control" placeholder="yyyy-mm-dd">
                            </div>
                        </div>
                        <div class="col-md-11">
                            <button type="submit" class="btn btn-primary btn-sm filter-btn float-right" id="btn-filter"> Filter</button>
                        </div>
                        <div class="col-md-1">
                            <button style="margin-top: 5px;width: 100px;" type="button" class="btn btn-secondary btn-sm reset-btn float-left" id="btn-reset"> Reset</button>
                        </div>
                    </div>

                </form>
            </div>
        </div>
        <div class="card">
            <div class="card-body p-0 p-2">
                <div class="row">
                    <div class="col-12">
                        @can('employee-create')
                            <button type="button" class="btn btn-outline-primary btn-sm fa-pull-right" name="create_record" id="create_record"><i class="fas fa-plus mr-2"></i>Add Employee</button>
                        @endcan
                    </div>
                    <div class="col-12">
                        <hr class="border-dark">
                    </div>
                    <div class="col-12">
                        <div class="center-block fix-width scroll-inner">
                        <table class="table table-striped table-bordered table-sm small nowrap display" style="width: 100%" id="emptable">
                            <thead>
                                <tr>
                                    <th>Emp ID</th>
                                    <th>Name</th>
                                    <th>Service No</th>
                                    <th>Office</th>
                                    <th>Department</th>
                                    <th>Start date</th>
                                    <th>Position</th>
                                    <th class="text-center">Status</th>
                                    <th class="text-right">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Area Start -->
    <div class="modal fade" id="empModal" data-backdrop="static" data-keyboard="false" tabindex="-1"
    aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header p-2">
                    <h5 class="modal-title" id="staticBackdropLabel">Add Employee Record</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col">
                            <span id="form_result"></span>
                            <form method="post" id="formemployee" class="form-horizontal">
                                {{ csrf_field() }}	
                                <div class="form-row mb-1">
                                    <div class="col">
                                        <label class="small font-weight-bold text-dark">EPF No</label>
                                        <input type="text" name="etfno" id="etfno" class="form-control form-control-sm" />
                                        @if ($errors->has('etfno'))
                                            <span class="help-block">
                                            <strong>{{ $errors->first('etfno') }}</strong>
                                        </span>
                                        @endif
                                        <span id="checketf"></span>
                                    </div>
                                    <div class="col">
                                        <label class="small font-weight-bold text-dark">Employee ID</label>
                                        <input type="text" name="emp_id" id="emp_id" class="form-control form-control-sm {{ $errors->has('emp_id') ? ' has-error' : '' }}" />
                                         @if ($errors->has('emp_id'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('emp_id') }}</strong>
                                        </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-row mb-1">
                                    <div class="col">
                                        <label class="small font-weight-bold text-dark">First Name</label>
                                        <input type="text" name="firstname" id="firstname" class="form-control form-control-sm {{ $errors->has('firstname') ? ' has-error' : '' }}" />
                                        @if ($errors->has('firstname'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('firstname') }}</strong>
                                        </span>
                                        @endif
                                    </div>
                                    <div class="col">
                                        <label class="small font-weight-bold text-dark">Middle Name</label>
                                        <input type="text" name="middlename" id="middlename" class="form-control form-control-sm {{ $errors->has('middlename') ? ' has-error' : '' }}" />
                                        @if ($errors->has('middlename'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('middlename') }}</strong>
                                        </span>
                                        @endif
                                    </div>
                                    <div class="col">
                                        <label class="small font-weight-bold text-dark">Last Name</label>
                                        <input type="text" name="lastname" id="lastname" class="form-control form-control-sm {{ $errors->has('lastname') ? ' has-error' : '' }}" />
                                        @if ($errors->has('lastname'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('lastname') }}</strong>
                                        </span>
                                        @endif
                                    </div>                                    
                                </div>
                                <div class="form-row mb-1">
                                    <div class="col">
                                        <label class="small font-weight-bold text-dark">Full Name</label>
                                        <input type="text" name="emp_fullname" id="emp_fullname" class="form-control form-control-sm  {{ $errors->has('emp_fullname') ? ' has-error' : '' }}" />
                                        @if ($errors->has('emp_fullname'))
                                            <span class="help-block">
                                            <strong>{{ $errors->first('emp_fullname') }}</strong>
                                        </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-row mb-1">
                                    <div class="col">
                                        <label class="small font-weight-bold text-dark">Name with Initial</label>
                                        <input type="text" name="emp_name_with_initial" id="emp_name_with_initial" class="form-control form-control-sm  {{ $errors->has('emp_name_with_initial') ? ' has-error' : '' }}" />
                                        @if ($errors->has('emp_name_with_initial'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('emp_name_with_initial') }}</strong>
                                        </span>
                                        @endif
                                    </div>
                                    <div class="col">
                                        <label class="small font-weight-bold text-dark">Calling Name</label>
                                        <input type="text" name="calling_name" id="calling_name" class="form-control form-control-sm {{ $errors->has('calling_name') ? ' has-error' : '' }}" />
                                        @if ($errors->has('calling_name'))
                                            <span class="help-block">
                                            <strong>{{ $errors->first('calling_name') }}</strong>
                                        </span>
                                        @endif
                                    </div>

                                </div>
                                <div class="form-row mb-1">
                                    <div class="col">
                                        <label class="small font-weight-bold text-dark">Identity Card No</label>
                                        <input type="text" name="emp_id_card" id="emp_id_card" class="form-control form-control-sm {{ $errors->has('emp_id_card') ? ' has-error' : '' }}" />
                                        @if ($errors->has('emp_id_card'))
                                            <span class="help-block">
                                            <strong>{{ $errors->first('emp_id_card') }}</strong>
                                        </span>
                                        @endif
                                        <span id="checknic"></span>
                                    </div>
                                </div>
                                <div class="form-row mb-1">
                                    <div class="col">
                                        <label class="small font-weight-bold text-dark">Telephone</label>
                                        <input type="text" name="telephone" id="telephone" class="form-control form-control-sm {{ $errors->has('telephone') ? ' has-error' : '' }}" />
                                        @if ($errors->has('telephone'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('telephone') }}</strong>
                                        </span>
                                        @endif
                                    </div>
                                    <div class="col">
                                        <label class="small font-weight-bold text-dark">Mobile No</label>
                                        <input type="text" name="emp_mobile" id="emp_mobile" class="form-control form-control-sm {{ $errors->has('emp_mobile') ? ' has-error' : '' }}" />
                                        @if ($errors->has('emp_mobile'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('emp_mobile') }}</strong>
                                        </span>
                                        @endif
                                    </div>
                                    <div class="col">
                                        <label class="small font-weight-bold text-dark">Office Telephone</label>
                                        <input type="text" name="emp_work_telephone" id="emp_work_telephone" class="form-control form-control-sm {{ $errors->has('emp_work_telephone') ? ' has-error' : '' }}" />
                                        @if ($errors->has('emp_work_telephone'))
                                            <span class="help-block">
                                            <strong>{{ $errors->first('emp_work_telephone') }}</strong>
                                        </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-row mb-1">
                                    <div class="col">
                                        <label class="small font-weight-bold text-dark">Photograph</label>
                                        <input type="file" data-preview="#preview" class="form-control form-control-sm {{ $errors->has('photograph') ? ' has-error' : '' }}" name="photograph" id="photograph">
                                        <img class="" id="preview" src="">
                                        @if ($errors->has('photograph'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('photograph') }}</strong>
                                        </span>
                                        @endif
                                    </div>                                   
                                </div>
                                <div class="form-row mb-1">
                                    <div class="col">
                                        <label class="small font-weight-bold text-dark">Employee Status</label>
                                        <select name="status" id="status" class="form-control form-control-sm shipClass {{ $errors->has('status') ? ' has-error' : '' }}">
                                            <option value="">Select</option>
                                            @foreach($employmentstatus as $employmentstatu)
                                            <option value="{{$employmentstatu->id}}">{{$employmentstatu->emp_status}}
                                            </option>
                                            @endforeach
                                        </select>
                                        @if ($errors->has('status'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('status') }}</strong>
                                        </span>
                                        @endif
                                    </div>
                                    <div class="col">
                                        <label class="small font-weight-bold text-dark">Work Location</label>
                                        <select name="location" class="form-control form-control-sm shipClass {{ $errors->has('location') ? ' has-error' : '' }}">
                                            <option value="">Please Select</option>
                                            @foreach($branch as $branches)
                                            <option value="{{$branches->id}}">{{$branches->location}}</option>
                                            @endforeach
                                        </select>
                                        @if ($errors->has('location'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('location') }}</strong>
                                        </span>
                                        @endif
                                    </div>                                    
                                </div>
                                <div class="form-row mb-1">
                                    <div class="col">
                                        <label class="small font-weight-bold text-dark">Employee Job</label>
                                        <select name="employeejob" class="form-control form-control-sm shipClass {{ $errors->has('employeejob') ? ' has-error' : '' }}">
                                            <option value="">Select</option>
                                            @foreach($title as $titles)
                                            <option value="{{$titles->id}}">{{$titles->title}}</option>
                                            @endforeach
                                        </select>
                                        @if ($errors->has('employeejob'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('employeejob') }}</strong>
                                        </span>
                                        @endif
                                    </div>
                                    <div class="col">
                                        <label class="small font-weight-bold text-dark">Work Shift</label>
                                        <select name="shift" class="form-control form-control-sm shipClass {{ $errors->has('shift') ? ' has-error' : '' }}">
                                            <option value="">Select</option>
                                            @foreach($shift_type as $shift_types)
                                                <option value="{{$shift_types->id}}" @if($shift_types->id == 2) selected @endif>{{$shift_types->shift_name}}</option>
                                            @endforeach
                                        </select>
                                        @if ($errors->has('shift'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('shift') }}</strong>
                                        </span>
                                        @endif
                                    </div>                                    
                                </div>
                                <div class="form-row mb-1">
                                    <div class="col">
                                        <label class="small font-weight-bold text-dark">Company</label>
                                        <select name="employeecompany" id="company" class="form-control form-control-sm shipClass {{ $errors->has('employeecompany') ? ' has-error' : '' }}">
                                            <option value="">Select</option>
                                            @foreach($company as $companies)
                                                <option value="{{$companies->id}}" @if($companies->id == 1) selected @endif>{{$companies->name}}</option>
                                            @endforeach
                                        </select>
                                        @if ($errors->has('employeecompany'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('employeejob') }}</strong>
                                        </span>
                                        @endif
                                    </div>
                                    <div class="col">
                                        <label class="small font-weight-bold text-dark">Department</label>
                                        <select name="department" id="department" class="form-control form-control-sm shipClass {{ $errors->has('department') ? ' has-error' : '' }}">
                                            <option value="">Select</option>
                                            @foreach($departments as $dept)
                                                <option value="{{$dept->id}}">{{$dept->name}}</option>
                                            @endforeach
                                        </select>
                                        @if ($errors->has('department'))
                                            <span class="help-block">
                                            <strong>{{ $errors->first('department') }}</strong>
                                        </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-row mb-1">
                                    <div class="col">
                                        <label class="small font-weight-bold text-dark">Employee Category</label>
                                        <select name="employeecategory" id="empcat" class="form-control form-control-sm shipClass {{ $errors->has('employeecategory') ? ' has-error' : '' }}">
                                            <option value="">Select</option>
                                            @foreach($employeecat as $empcategory)
                                                <option value="{{$empcategory->id}}" @if($empcategory->id == 1) selected @endif>{{$empcategory->emp_category}}</option>
                                            @endforeach
                                        </select>
                                        @if ($errors->has('employeecategory'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('employeecategory') }}</strong>
                                        </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-row mb-1 no_of_leaves" hidden>
                                    <div class="col">
                                        <label class="small font-weight-bold text-dark">No of Casual Leaves</label>
                                        <input type="number" name="no_of_casual_leaves" id="no_of_casual_leaves" class="form-control form-control-sm {{ $errors->has('no_of_casual_leaves') ? ' has-error' : '' }}" />
                                        @if ($errors->has('no_of_casual_leaves'))
                                            <span class="help-block">
                                            <strong>{{ $errors->first('no_of_casual_leaves') }}</strong>
                                        </span>
                                        @endif
                                    </div>
                                    <div class="col">
                                        <label class="small font-weight-bold text-dark">No of Annual Leaves</label>
                                        <input type="number" name="no_of_annual_leaves" id="no_of_annual_leaves" class="form-control form-control-sm {{ $errors->has('no_of_annual_leaves') ? ' has-error' : '' }}" />
                                        @if ($errors->has('no_of_annual_leaves'))
                                            <span class="help-block">
                                            <strong>{{ $errors->first('no_of_annual_leaves') }}</strong>
                                        </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group mt-3">
                                    <button type="submit" name="action_button" id="action_button" class="btn btn-outline-primary btn-sm fa-pull-right px-4"><i class="fas fa-plus"></i>&nbsp;Add</button>
                                </div>
                                <input type="hidden" name="action" id="action" value="Add" />
                                <input type="hidden" name="hidden_id" id="hidden_id" />
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="userlogModal" data-backdrop="static" data-keyboard="false" tabindex="-1"
    aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-sm">
            <div class="modal-content">
                <div class="modal-header p-2">
                    <h5 class="modal-title" id="exampleModalLabel">Add Employee User Login</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col">
                            <span id="userlogform_result"></span>

                            <form id="userlogform" method="post">
                                {{ csrf_field() }}
                                <div class="form-group mb-1">
                                    <label class="small font-weight-bold text-dark">E-mail</label>
                                    <input type="text" class="form-control form-control-sm {{ $errors->has('email') ? ' has-error' : '' }} shipClass" id="email" name="email" placeholder="Type Employee Email">
                                    @if ($errors->has('email'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                    @endif
                                </div>
                                <div class="form-group mb-1">
                                    <label class="small font-weight-bold text-dark">Password</label>
                                    <input type="password" class="form-control form-control-sm {{ $errors->has('password') ? ' has-error' : '' }} shipClass" id="inputEmail4" name="password">
                                    @if ($errors->has('password'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                    @endif
                                </div>
                                <div class="form-group mb-1">
                                    <label class="small font-weight-bold text-dark">Confirm Password</label>
                                    <input type="password" class="form-control form-control-sm {{ $errors->has('comfirmpassword') ? ' has-error' : '' }} shipClass" id="password-confirm" name="password_confirmation">
                                    @if ($errors->has('comfirmpassword'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('comfirmpassword') }}</strong>
                                    </span>
                                    @endif
                                </div>
                                <div class="form-group mt-3">
                                    <button type="submit" name="action_button" id="action_button" class="btn btn-outline-primary btn-sm fa-pull-right px-4"><i class="fas fa-plus"></i>&nbsp;Add</button>
                                </div>
                                <input type="hidden" id="userid" name="userid">
                                <input type="hidden" id="name" name="name">
                                <input type="hidden" name="action" id="action" />
                                <input type="hidden" name="hidden_id" id="hidden_id" />
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="confirmModal" data-backdrop="static" data-keyboard="false" tabindex="-1"
    aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-sm">
            <div class="modal-content">
                <div class="modal-header p-2">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col text-center">
                            <h4 class="font-weight-normal">Are you sure you want to remove this data?</h4>
                        </div>
                    </div>
                </div>
                <div class="modal-footer p-2">
                    <button type="button" name="ok_button" id="ok_button" class="btn btn-danger px-3 btn-sm">OK</button>
                    <button type="button" class="btn btn-dark px-3 btn-sm" data-dismiss="modal">Cancel</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="fpModal" data-backdrop="static" data-keyboard="false" tabindex="-1"
    aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-sm">
            <div class="modal-content">
                <div class="modal-header p-2">
                    <h5 class="modal-title" id="exampleModalLabel">Add Employee User Login</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col">
                            <div id="fpform_result"></div>
                            <form id="fpform" method="post">
                                {{ csrf_field() }}
                                <div class="form-group mb-1">
                                    <label class="small font-weight-bold text-dark">ID</label>
                                    <input type="text" name="id" id="id" class="form-control form-control-sm" readonly />
                                </div>
                                <div class="form-group mb-1">
                                    <label class="small font-weight-bold text-dark">Emp Id: </label>
                                    <input type="text" name="userid" id="userid" class="form-control form-control-sm" readonly />
                                </div>
                                <div class="form-group mb-1">
                                    <label class="small font-weight-bold text-dark">name: </label>
                                    <input type="text" name="name" id="name" class="form-control form-control-sm" />
                                </div>
                                <div class="form-group mb-1">
                                    <label class="small font-weight-bold text-dark">cardno: </label>
                                    <input type="text" name="cardno" id="cardno" class="form-control form-control-sm" />
                                </div>
                                <div class="form-group mb-1">
                                    <label class="small font-weight-bold text-dark">role: </label>
                                    <select name="role" class="form-control form-control-sm">
                                        <option value="">Select</option>
                                        <option value="0">User</option>
                                        <option value="4">Admin</option>
                                    </select>
                                </div>
                                <div class="form-group mb-1">
                                    <label class="small font-weight-bold text-dark">password: </label>
                                    <input type="text" name="password" id="password" class="form-control form-control-sm" />
                                </div>
                                <div class="form-group mb-1">
                                    <label class="small font-weight-bold text-dark">FP Location: </label>
                                    <select name="devices" class="form-control form-control-sm shipClass">
                                        <option value="">Select</option>
                                        @foreach($device as $devices)
                                        <option value="{{$devices->ip}}">{{$devices->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group mt-3">
                                    <button type="submit" name="action_button" id="action_button" class="btn btn-outline-primary btn-sm fa-pull-right px-4"><i class="fas fa-plus"></i>&nbsp;Add</button>
                                </div>
                                <input type="hidden" name="action" id="action" />
                                <input type="hidden" name="hidden_id" id="hidden_id" />
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal Area End -->
</main>
        
              
@endsection
@section('script')
<script>
$(document).ready(function () {
    var listcheck = {{ $listpermission }};
    var editcheck = {{ $editpermission }};
    var fingercheck = {{ $fingerprintpermission }};
    var deletecheck = {{ $deletepermission }};

    $('#empmanagementlist').prop('aria-expanded', 'true').removeClass('collapsed');
    $('#collapseemployee').addClass('show');
    $('#office_staff_main_nav_link').prop('aria-expanded', 'true').removeClass('collapsed');
    $('#office_staff_collapse').addClass('show');
    $('#employee_main_nav_link').prop('aria-expanded', 'true').removeClass('collapsed');
    $('#employee_collapse').addClass('show');
    $('#employee_add_link').addClass('active');

    


    $("#etfno").focusout(function(){
        let val = $(this).val();
        $('#emp_id').val(val);
    });

    // let company_f = $('#company_f');
    // let department_f = $('#department_f');
    // let employee_f = $('#employee_f');
    // let location_f = $('#location_f');

    // company_f.select2({
    //     placeholder: 'Select...',
    //     width: '100%',
    //     allowClear: true,
    //     ajax: {
    //         url: '{{url("company_list_sel2")}}',
    //         dataType: 'json',
    //         data: function(params) {
    //             return {
    //                 term: params.term || '',
    //                 page: params.page || 1
    //             }
    //         },
    //         cache: true
    //     }
    // });

    // department_f.select2({
    //     placeholder: 'Select...',
    //     width: '100%',
    //     allowClear: true,
    //     ajax: {
    //         url: '{{url("department_list_sel2")}}',
    //         dataType: 'json',
    //         data: function(params) {
    //             return {
    //                 term: params.term || '',
    //                 page: params.page || 1,
    //                 company: company_f.val()
    //             }
    //         },
    //         cache: true
    //     }
    // });

    // employee_f.select2({
    //     placeholder: 'Select...',
    //     width: '100%',
    //     allowClear: true,
    //     ajax: {
    //         url: '{{url("employee_list_sel2")}}',
    //         dataType: 'json',
    //         data: function(params) {
    //             return {
    //                 term: params.term || '',
    //                 page: params.page || 1
    //             }
    //         },
    //         cache: true
    //     }
    // });

    // location_f.select2({
    //     placeholder: 'Select...',
    //     width: '100%',
    //     allowClear: true,
    //     ajax: {
    //         url: '{{url("location_list_sel2")}}',
    //         dataType: 'json',
    //         data: function(params) {
    //             return {
    //                 term: params.term || '',
    //                 page: params.page || 1
    //             }
    //         },
    //         cache: true
    //     }
    // });

    function load_dt(department, employee, from_date, to_date){
        // $('#emptable').DataTable({
        //     dom: 'lBfrtip',
        //     buttons: [
        //         {
        //             extend: 'excelHtml5',
        //             text: 'Excel',
        //             className: 'btn btn-default',
        //             exportOptions: {
        //                 columns: 'th:not(:last-child)'
        //             }
        //         },
        //         {
        //             extend: 'pdfHtml5',
        //             text: 'Print',
        //             className: 'btn btn-default',
        //             exportOptions: {
        //                 columns: 'th:not(:last-child)'
        //             }
        //         }
        //     ],
        //     processing: true,
        //     serverSide: true,
        //     ajax: {
        //         "url": "{!! route('employee_list_dt') !!}",
        //         "data": {'department':department, 'employee':employee, 'location': location, 'from_date': from_date, 'to_date': to_date},
        //     },
        //     columns: [
        //         { data: 'emp_id_link', name: 'emp_id_link' },
        //         { data: 'emp_name_link', name: 'emp_name_link' },
        //         { data: 'location', name: 'location' },
        //         { data: 'dep_name', name: 'dep_name' },
        //         { data: 'emp_join_date', name: 'emp_join_date' },
        //         { data: 'title', name: 'title' },
        //         { data: 'emp_status_label', name: 'emp_status_label' },
        //         { data: 'action', name: 'action', orderable: false, searchable: false},
        //     ],
        //     "bDestroy": true,
        //     "order": [
        //         [5, "desc"]
        //     ]
        // });

        
        $('#emptable').DataTable({
                "destroy": true,
                "processing": true,
                "serverSide": true,
                ajax: {
                    url: scripturl + '/employeelist.php',

                    type: "POST", // you can use GET
                    data: {'department':department, 
                           'employee':employee, 
                           'from_date': from_date, 
                           'to_date': to_date
                        },
                    
                },
                dom: "<'row'<'col-sm-5'B><'col-sm-2'l><'col-sm-5'f>>" + "<'row'<'col-sm-12'tr>>" + "<'row'<'col-sm-5'i><'col-sm-7'p>>",
            responsive: true,
            lengthMenu: [
                [10, 25, 50, -1],
                [10, 25, 50, 'All'],
            ],
            "buttons": [
                { extend: 'csv', className: 'btn btn-success btn-sm', title: 'Employee Details', text: '<i class="fas fa-file-csv mr-2"></i> CSV', },
                { 
                    extend: 'print', 
                    title: 'Employee Details',
                    className: 'btn btn-primary btn-sm', 
                    text: '<i class="fas fa-print mr-2"></i> Print',
                    customize: function ( win ) {
                        $(win.document.body).find( 'table' )
                            .addClass( 'compact' )
                            .css( 'font-size', 'inherit' );
                    }, 
                },
            ],
                "order": [[ 5, "desc" ]],
                "columns": [
                    {
                        "targets": -1,
                        "data": null,
                        "render": function(data, type, full) {

                            var link ='';
                            link+='<a href="viewEmployee/' + full['id'] + '" class="text-decoration-none text-dark" >' + full['emp_id'] +'</a>';
                            return link;
                        }
                    },
                    {
                        "targets": -1,
                        "data": null,
                        "render": function(data, type, full) {

                            var link ='';
                            link+='<a href="viewEmployee/' + full['id'] + '" class="text-decoration-none text-dark" >' + full['emp_name_with_initial'] +'</a>';
                            return link;
                        }
                    },
                    {
                        "data": "service_no",
                        "className": 'text-dark'
                    },
                    {
                        "data": "location",
                        "className": 'text-dark'
                    },
                    
                    {
                        "data": "dep_name",
                        "className": 'text-dark'
                    },
                    {
                        "data": "emp_join_date",
                        "className": 'text-dark'
                    },
                    {
                        "data": "title",
                        "className": 'text-dark'
                    },
                    {
                        "targets": -1,
                        "data": null,
                        "render": function(data, type, full) {

                            var text ='';
                            text+='<span class="text-success">'+ full['emp_status'] + '</span>';
                            return text;
                        }

                    },
                    {
                        "targets": -1,
                        "className": 'text-right',
                        "data": null,
                        "render": function(data, type, full) {

                            var button='';

                            button+=' <a style="margin:1px;" data-toggle="tooltip" data-placement="bottom" title="View Employee Details" class="btn btn-outline-dark btn-sm ';if(listcheck==0){button+='d-none';}button+='" href="viewEmployee/' + full['id'] + '"><i class="far fa-clipboard"></i></a> ';
                            
                            button+='<button style="margin:1px;" data-toggle="tooltip" data-placement="bottom" title="Add Employee Fingerprint Details" class="btn btn-outline-primary btn-sm addfp';if(fingercheck==0){button+='d-none';}button+='" id="' + full['emp_id'] +'" name="'+ full['emp_name_with_initial'] +'"><i class="fas fa-sign-in-alt"></i></button>';
                         
                            button+='<button style="margin:1px;" data-toggle="tooltip" data-placement="bottom" title="Add Employee User Login Details" class="btn btn-outline-secondary btn-sm adduserlog';if(editcheck==0){button+='d-none';}button+='"  id="' + full['emp_id'] +'" name="'+ full['emp_name_with_initial'] +'"><i class="fas fa-user"></i></button>';
                           
                            button+='<button style="margin:1px;" data-toggle="tooltip" data-placement="bottom" title="Delete Employee Details" class="btn btn-outline-danger btn-sm delete';if(deletecheck==0){button+='d-none';}button+='" id="'+ full['id'] +'"><i class="far fa-trash-alt"></i></button>';
                            
                            return button;
                        }
                    }
                ],
                drawCallback: function(settings) {
                    $('[data-toggle="tooltip"]').tooltip();
                }
            });
    }

    load_dt('', '', '', '', '');

    $('#formFilter').on('submit',function(e) {
        e.preventDefault();
        let department = $('#department_f').val();
        var employee;
        let from_date = $('#from_date').val();
        let to_date = $('#to_date').val();

        var selecttype= $('#search_option').val();
        if(selecttype=='serviceno'){
            employee = $('#serviceno').val();
}
else if(selecttype=='employee_name'){
            employee = $('#employee_name').val();
}
else if(selecttype=='employee_nic'){
            employee = $('#employee_nic').val();
}

        load_dt(department, employee, from_date, to_date);
    });

});
$(document).ready(function () {

    let company = $('#company');
    let department = $('#department');
    department.select2({
        placeholder: 'Select...',
        width: '100%',
        allowClear: true,
        ajax: {
            url: '{{url("department_list_sel2")}}',
            dataType: 'json',
            data: function(params) {
                return {
                    term: params.term || '',
                    page: params.page || 1,
                    company: company.val()
                }
            },
            cache: true
        }
    });


    $('#create_record').click(function () {

        $('#action_button').val('Add');
        $('#action').val('Add');
        $('#form_result').html('');

        $('#empModal').modal('show');
        $('.modal-title').text('Add Employee Record');
    });

    $('#formemployee').on('submit', function (event) {
        event.preventDefault();
        var action_url = '';
        var formData = new FormData(this);
        //alert(formData);

        if ($('#action').val() == 'Add') {
            action_url = "{{ route('empoyeeRegister') }}";
        }

        var formData = new FormData($(this)[0]);

        $.ajax({
            url: action_url,
            method: "POST",
            //data:$(this).serialize(),
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
            dataType: "json",
            success: function (data) {

                var html = '';
                if (data.errors) {

                    html = '<div class="alert alert-danger">';
                    for (var count = 0; count < data.errors.length; count++) {
                        html += '<p>' + data.errors[count] + '</p>';
                    }
                    html += '</div>';
                }
                if (data.success) {
                    html = '<div class="alert alert-success">' + data.success + '</div>';
                    $('#formemployee')[0].reset();
                    setTimeout(function() {
                        location.reload();
                    }, 3000);

                    $('#formemployee').modal('hide');
                }
                $('#form_result').html(html);
            }
        });
    });

    /*

 $(document).on('click', '.edit', function(){
  var id = $(this).attr('id');
  $('#form_result').html('');
  $.ajax({
   url :"/EmploymentStatus/"+id+"/edit",
   dataType:"json",
   success:function(data)
   {
    $('#ip').val(data.result.ip);
    $('#name').val(data.result.name);
    $('#location').val(data.result.location);
    $('#hidden_id').val(id);
    $('.modal-title').text('Edit Fingerprint Device');
    $('#action_button').val('Edit');
    $('#action').val('Edit');
    $('#empModal').modal('show');
   }
  })
 });
*/
    var user_id;

    $(document).on('click', '.delete', function () {
        user_id = $(this).attr('id');
        $('#confirmModal').modal('show');
    });

    $('#ok_button').click(function () {
        $.ajax({
            url: "EmployeeDestroy/destroy/" + user_id,
            beforeSend: function () {
                $('#ok_button').text('Deleting...');
            },
            success: function (data) {
                setTimeout(function () {
                    $('#confirmModal').modal('hide');
                    $('#user_table').DataTable().ajax.reload();
                    alert('Data Deleted');
                }, 2000);
                location.reload();
            }
        })
    });


    //userlog 

    $(document).on('click', '.adduserlog', function () {
        $('.modal-title').text('Add Employee User Login');
        $('#action_button').val('Add');
        $('#userlogform #id').val($(this).attr('data-id'));
        var id = $(this).attr('id');
        var name = $(this).attr('name');
        $('#userid').val(id);
        $('#name').val(name);
        $('#action').val('Add');
        $('#userlogform_result').html('');

        $('#userlogModal').modal('show');
    });

    $('#userlogform').on('submit', function (event) {
        event.preventDefault();
        var action_url = '';

        if ($('#action').val() == 'Add') {
            action_url = "{{ route('addUserLogin') }}";
        }


        $.ajax({
            url: action_url,
            method: "POST",
            data: $(this).serialize(),
            dataType: "json",
            success: function (data) {

                var html = '';
                if (data.errors) {
                    html = '<div class="alert alert-danger">';
                    for (var count = 0; count < data.errors.length; count++) {
                        html += '<p>' + data.errors[count] + '</p>';
                    }
                    html += '</div>';
                }
                if (data.success) {
                    html = '<div class="alert alert-success">' + data.success + '</div>';
                    // $('#fpform')[0].close();
                    //$('#emptable').DataTable().ajax.reload();
                    location.reload();
                }
                $('#userlogform_result').html(html);
            }
        });
    });

    //userlog

    //fingerprint 

    $(document).on('click', '.addfp', function () {
        $('.modal-title').text('Add Employee to Fingerprint');
        emp_id = $(this).attr('id');
        name = $(this).attr('name');

        $('#action_button').val('Add');
        $('#fpform #id').val(emp_id);
        $('#fpform  #userid').val(emp_id);
        $('#fpform  #name').val(name);

        $('#action').val('Add');
        $('#fpform_result').html('');

        $('#fpModal').modal('show');
    });

    $('#fpform').on('submit', function (event) {
        event.preventDefault();
        var action_url = '';

        if ($('#action').val() == 'Add') {
            action_url = "{{ route('addFingerprintUser') }}";
        }


        $.ajax({
            url: action_url,
            method: "POST",
            data: $(this).serialize(),
            dataType: "json",
            success: function (data) {

                var html = '';
                if (data.errors) {
                    html = '<div class="alert alert-danger">';
                    for (var count = 0; count < data.errors.length; count++) {
                        html += '<p>' + data.errors[count] + '</p>';
                    }
                    html += '</div>';
                }
                if (data.success) {
                    html = '<div class="alert alert-success">' + data.success + '</div>';
                    $('#fpform_result').html(html);
                    $('#fpform')[0].close();
                    //$('#emptable').DataTable().ajax.reload();

                    location.reload();
                }

            }
        });
    });

    //fingerprint



});
</script>
<script>
    var emp_category='1';
    $(document).ready(function () {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $('#serviceno').select2({
            width: '100%',
            minimumInputLength: 1,
            ajax: {
                url: '{!! route("addEmployeegetserviceno") !!}',
                type: 'POST',
                dataType: 'json',
                data: function (params) {
                    return {
                        search: params.term,
                        emp_category: emp_category
                    };
                },
                processResults: function (data) {
                    return {
                        results: $.map(data, function (item) {
                            return {
                                text: item.service_no + ' - ' + item.emp_name_with_initial +
                                    ' - ' + item.emp_national_id,
                                id: item.id
                            };
                        })
                    };
                }
            }
        });

        $("#employee_name_div, #employee_nic_div").hide();

        // Add change event listener to the search option select
        $("#search_option").change(function () {
            // Hide all divs
            $("#serviceno_div, #employee_name_div, #employee_nic_div").hide();
            var selectedOption = $(this).val();
            $("#" + selectedOption + "_div").show();


if(selectedOption=='serviceno'){
 $('#serviceno').select2({
            width: '100%',
            minimumInputLength: 1,
            ajax: {
                url: '{!! route("addEmployeegetserviceno") !!}',
                type: 'POST',
                dataType: 'json',
                data: function (params) {
                    return {
                        search: params.term,
                        emp_category: emp_category
                    };
                },
                processResults: function (data) {
                    return {
                        results: $.map(data, function (item) {
                            return {
                                text: item.service_no + ' - ' + item.emp_name_with_initial +
                                    ' - ' + item.emp_national_id,
                                id: item.id
                            };
                           
                        })
                    };
                }
            }
        });
}
else if(selectedOption=='employee_name'){
    $('#employee_name').select2({
            width: '100%',
            minimumInputLength: 1,
            ajax: {
                url: '{!! route("addEmployeegetempname") !!}',
                type: 'POST',
                dataType: 'json',
                data: function (params) {
                    return {
                        search: params.term,
                        emp_category: emp_category
                    };
                },
                processResults: function (data) {
                    return {
                        results: $.map(data, function (item) {
                            return {
                                text: item.service_no + ' - ' + item.emp_name_with_initial +
                                    ' - ' + item.emp_national_id,
                                id: item.id
                            };
                        })
                    };
                }
            }
        });
}
else if(selectedOption=='employee_nic'){
    $('#employee_nic').select2({
            width: '100%',
            minimumInputLength: 1,
            ajax: {
                url: '{!! route("addEmployeegetempnic") !!}',
                type: 'POST',
                dataType: 'json',
                data: function (params) {
                    return {
                        search: params.term,
                        emp_category: emp_category
                    };
                },
                processResults: function (data) {
                    return {
                        results: $.map(data, function (item) {
                            return {
                                text: item.service_no + ' - ' + item.emp_name_with_initial +
                                    ' - ' + item.emp_national_id,
                                id: item.id
                            };
                        })
                    };
                }
            }
        });
}
        });


        // Store the initial/default values of the select elements
        var initialEmployeeNameValue ='';
        var initialEmployeeNicValue = '';
        var initialServiceNoValue ='';

        // Add a click event listener to the Reset button
        $("#btn-reset").click(function () {

            // Reset the Select2 element
            $("#serviceno").val(initialServiceNoValue).trigger("change");
            $("#employee_name").val(initialEmployeeNameValue).trigger("change");
            $("#employee_nic").val(initialEmployeeNicValue).trigger("change");
            
            // Clear other form fields if needed
            $("#department_f").val("");
            $("#from_date").val("");
            $("#to_date").val("");
        });
    });
</script>

<script>
    // epf no check
    $('#etfno').keyup(function () {
        var emp_etfno = $(this).val();

        if(emp_etfno){
            $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        })
        $.ajax({
            url: '{!! route("employeecheckemp_etfno") !!}',
            type: 'POST',
            dataType: "json",
            data: {
                emp_etfno: emp_etfno
            },
            success: function (data) {
                var checketf = $('#checketf');
        if (data.success) {
            checketf.text(data.success).css('color', 'red');
        } else if (data.error) {
            checketf.text(data.error).css('color', 'green');
        }
            }

        })
        }
        else{
            var checketf = $('#checketf');
            checketf.text('');
        }
        
    });

    // nic no check
    $('#emp_id_card').keyup(function () {
        var nicnumber = $(this).val();

        if(nicnumber){
            $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        })
        $.ajax({
            url: '{!! route("employeechecknicnumber") !!}',
            type: 'POST',
            dataType: "json",
            data: {
                nicnumber: nicnumber
            },
            success: function (data) {
                var checknic = $('#checknic');
        if (data.success) {
            checknic.text(data.success).css('color', 'red');
        } else if (data.error) {
            checknic.text(data.error).css('color', 'green');
        }
            }

        })
        }
        else{
            var checknic = $('#checknic');
            checknic.text('');
        }
        
    });
</script>

@endsection