@extends('layouts.app')
@section('style')
    <style>
        .help-block{
            color: red;
        }
    </style>
@endsection
@section('content')
    <main>

        <div class="container-fluid mt-3">
            <div class="row">
                <div class="col-lg-9">
                    <div id="default">
                        <div class="card mb-4">
                            <div class="card-header">Add Passport Details</div>
                            <div class="card-body">
                                @if(Session::has('message'))
                                    <p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('message') }}</p>
                                @endif

                                <form id="PdetailsForm" class="form-horizontal" method="POST"
                                      action="{{ route('passportInsert') }}">
                                    {{ csrf_field() }}
                                    <div class="form-group col-lg-8">
                                        <label for="exampleFormControlInput1">
                                            Employee Id</label>
                                        <input class="form-control" id="emp_id" name="emp_id" type="text"
                                               value="{{$id}}" readonly>
                                    </div>


                                    <div class="form-group col-lg-8">
                                        <label for="exampleFormControlInput1">
                                            Issued Date</label>
                                        <input class="form-control" id="issue_date" name="issue_date"
                                               type="date">
                                        @if ($errors->has('issue_date'))
                                            <span class="help-block">
                                                                <strong>{{ $errors->first('issue_date') }}</strong>
                                                            </span>
                                        @endif
                                    </div>
                                    <div class="form-group col-lg-8">
                                        <label for="exampleFormControlInput1">
                                            Expire Date</label>
                                        <input class="form-control" id="expire_date" name="expire_date"
                                               type="date">
                                        @if ($errors->has('expire_date'))
                                            <span class="help-block">
                                                                <strong>{{ $errors->first('expire_date') }}</strong>
                                                            </span>
                                        @endif
                                    </div>
                                    <div class="form-group col-lg-8">
                                        <label for="exampleFormControlInput1">
                                            Comments</label>
                                        <input class="form-control" id="pass_comments" name="pass_comments"
                                               type="text">
                                        @if ($errors->has('pass_comments'))
                                            <span class="help-block">
                                                                <strong>{{ $errors->first('pass_comments') }}</strong>
                                                            </span>
                                        @endif
                                    </div>
                                    <div class="form-group col-lg-8">
                                        <label for="exampleFormControlInput1">
                                            Passport Type</label>
                                        <input class="form-control" id="pass_type" name="pass_type" type="text">
                                        @if ($errors->has('pass_type'))
                                            <span class="help-block">
                                                                <strong>{{ $errors->first('pass_type') }}</strong>
                                                            </span>
                                        @endif
                                    </div>
                                    <div class="form-group col-lg-8">
                                        <label for="exampleFormControlInput1">
                                            Passport Status</label>
                                        <input class="form-control" id="pass_status" name="pass_status"
                                               type="text">
                                        @if ($errors->has('pass_status'))
                                            <span class="help-block">
                                                                <strong>{{ $errors->first('pass_status') }}</strong>
                                                            </span>
                                        @endif
                                    </div>
                                    <div class="form-group col-lg-8">
                                        <label for="exampleFormControlInput1">
                                            Passport Review </label>
                                        <input class="form-control" id="pass_review" name="pass_review"
                                               type="text">
                                        @if ($errors->has('pass_review'))
                                            <span class="help-block">
                                                                <strong>{{ $errors->first('pass_review') }}</strong>
                                                            </span>
                                        @endif
                                    </div>

                                    <div class="form-group col-lg-8">
                                        <label for="exampleFormControlInput1">
                                            EPF # </label>
                                        <input class="form-control" id="epf_no" name="epf_no"
                                               type="text">
                                        @if ($errors->has('epf_no'))
                                            <span class="help-block">
                                                                <strong>{{ $errors->first('epf_no') }}</strong>
                                                            </span>
                                        @endif
                                    </div>

                                    <div class="form-group col-lg-8">
                                        @can('employee-edit')
                                            <button type="submit" class="btn btn-primary btn-sm">Save</button>
                                            <button type="reset" class="btn btn-success btn-sm">Clear</button>
                                        @endcan
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>


                </div>
                @include('layouts.employeeRightBar')

            </div>
        </div>
        <div class="container-fluid mt-3">
            <div class="card mb-4">

                <div class="card-body">
                    <div class="datatable table-responsive">
                        <table class="table table-bordered table-hover" id="dataTable" width="100%" cellspacing="0">
                            <thead>
                            <tr>
                                <th>Issued Date</th>
                                <th>Expire Date</th>
                                <th>Comments</th>
                                <th>Passport Type</th>
                                <th>Passport Status</th>
                                <th>Review</th>
                                <th>EPF #</th>
                                <th>Action</th>
                            </tr>
                            </thead>

                            <tbody>
                            @foreach($passport as $passports)
                                <tr>
                                    <td>{{$passports->emp_pass_issue_date}}</td>
                                    <td>{{$passports->emp_pass_expire_date}}</td>
                                    <td>{{$passports->emp_pass_comments}}</td>
                                    <td>{{$passports->emp_pass_type}}</td>
                                    <td>{{$passports->emp_pass_status}}</td>
                                    <td>{{$passports->emp_pass_review}}</td>
                                    <td>{{$passports->epf_no}}</td>

                                    <td>
                                        @can('employee-edit')
                                            <a href="{{route('passportEdit',$passports->emp_pass_id)}}" class="btn btn-sm btn-primary mr-2"><i class="fa fa-pencil-alt"></i></a>
                                            <a href="{{route('passportDestroy',$passports->emp_pass_id)}}" class="btn btn-sm btn-danger "><i class="fa fa-trash"></i></a>
                                        @endcan
                                    </td>
                                </tr>

                            @endforeach


                            </tbody>
                        </table>
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

        $('#issue_date').datepicker({
            format: "yyyy/mm/dd",
            autoclose: true
        });
        $('#expire_date').datepicker({
            format: "yyyy/mm/dd",
            autoclose: true
        });
        $('#review_date').datepicker({
            format: "yyyy/mm/dd",
            autoclose: true
        });
    </script>
@endsection
