@extends('layouts.app')
@section('content')

    <main>
        <div class="page-header page-header-light bg-white shadow">
            <div class="container-fluid">
                <div class="page-header-content py-3">
                    <h1 class="page-header-title">
                        <div class="page-header-icon"><i class="fa fa-unlock "></i></div>
                        <span>Roles</span>
                    </h1>
                </div>
            </div>
        </div>

        <div class="container-fluid mt-4">

            <div class="card">
                <div class="card-body p-0 p-2">
                    <div class="row">
                        <div class="col-12">
                            @can('role-create')
                                <a class="btn btn-success btn-sm float-right" href="{{ route('roles.create') }}"> Create New Role</a>
                            @endcan
                        </div>
                        <div class="col-12">
                            <hr class="border-dark">
                            @if ($message = Session::get('success'))
                                <div class="alert alert-success">
                                    <span>{{ $message }}</span>
                                </div>
                            @endif
                        </div>

                        <div class="col-12 table-responsive">

                            <table class="table table-bordered">
                                <tr>
                                    <th>No</th>
                                    <th>Name</th>
                                    <th width="280px">Action</th>
                                </tr>
                                @foreach ($roles as $key => $role)
                                    <tr>
                                        <td>{{ ++$i }}</td>
                                        <td>{{ $role->name }}</td>
                                        <td>
                                            <a class="btn btn-info btn-sm" href="{{ route('roles.show',$role->id) }}"><i class="fa fa-eye"></i></a>
{{--                                            @can('role-edit')--}}
                                                <a class="btn btn-primary btn-sm" href="{{ route('roles.edit',$role->id) }}"><i class="fa fa-pencil-alt"></i></a>
{{--                                            @endcan--}}
                                            @can('role-delete')
                                                @if($role->id != 1)
                                                    <form method="POST" action="{{ route('roles.destroy', $role->id) }}" id="delete_form" style="display:inline">
                                                        {{ csrf_field() }}
                                                        {{ method_field('DELETE') }}
                                                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure to delete this role?')"><i class="fa fa-trash"></i></button>
                                                    </form>
                                                @endif
                                            @endcan
                                        </td>
                                    </tr>
                                @endforeach
                            </table>

                        </div>
                    </div>
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

        });
    </script>
@endsection
