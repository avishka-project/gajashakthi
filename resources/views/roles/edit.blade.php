@extends('layouts.app')
@section('content')
    <main>
        <div class="page-header page-header-light bg-white shadow">
            <div class="container-fluid">
                <div class="page-header-content py-3">
                    <h1 class="page-header-title">
                        <div class="page-header-icon"><i class="fa fa-unlock "></i></div>
                        <span>Edit Role</span>
                    </h1>
                </div>
            </div>
        </div>

        <div class="container-fluid mt-4">

            <div class="card">
                <div class="card-body p-0 p-2">

                    @if (count($errors) > 0)
                        <div class="alert alert-danger">
                            <strong>Whoops!</strong> There were some problems with your input.<br><br>
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    {!! Form::model($role, ['method' => 'PATCH','route' => ['roles.update', $role->id]]) !!}
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <div class="form-group">
                                <strong>Name:</strong>
                                {!! Form::text('name', null, array('placeholder' => 'Name','class' => 'form-control form-control-sm')) !!}
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <div class="form-group">
                                <strong>Permission:</strong>
                                <br/>
                                <label>
                                    <input type="checkbox" name="selectAll" id="selectAllDomainList" /> Check All
                                </label>
                                <div class="row">
                                    @foreach($perms_with_modules as $pm)
                                        <div class="col-md-4">
                                            <div class="card shadow mb-4">
                                                <div class="card-header">
                                                    <strong>{{$pm->module}}</strong>
                                                </div>
                                                <div class="card-body">
                                                    @foreach($permission as $value)
                                                        @if($pm->module === $value->module)
                                                            <label>{{ Form::checkbox('permission[]', $value->id, in_array($value->id, $rolePermissions) ? true : false, array('class' => 'name')) }}
                                                                {{ $value->name }}</label>
                                                            <br/>
                                                        @endif
                                                    @endforeach
                                                </div>
                                            </div>

                                        </div>
                                    @endforeach
                                </div>

                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-12 text-center">
                            <button type="submit" class="btn btn-primary btn-sm">Submit</button>
                        </div>
                    </div>
                    {!! Form::close() !!}

                </div>
            </div>
        </div>
    </main>






@endsection

@section('script')
    <script>
        $(document).ready(function(){

            $('#administrator_main_nav_link').prop('aria-expanded', 'true').removeClass('collapsed');
            $('#administrator_collapse').addClass('show');
            $('#roles_link').addClass('active');

            $(':checkbox[name=selectAll]').click(function () {
                $(':checkbox').prop('checked', this.checked);
            });

        });
    </script>
@endsection
