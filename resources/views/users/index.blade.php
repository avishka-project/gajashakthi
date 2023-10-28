@extends('layouts.app')
@section('content')

    <main>
        <div class="page-header page-header-light bg-white shadow">
            <div class="container-fluid">
                <div class="page-header-content py-3">
                    <h1 class="page-header-title">
                        <div class="page-header-icon"><i class="fa fa-users"></i></div>
                        <span>Users</span>
                    </h1>
                </div>
            </div>
        </div>

        <div class="container-fluid mt-4">

            <div class="card">
                <div class="card-body p-0 p-2">
                    <div class="row">
                        <div class="col-12">
                            <a class="btn btn-success btn-sm float-right" href="{{ route('users.create') }}"> Create New User</a>
                        </div>
                        <div class="col-12">
                            <hr class="border-dark">
                            @if ($message = Session::get('success'))
                                <div class="alert alert-success">
                                    <span>{{ $message }}</span>
                                </div>
                            @endif
                        </div>

                        <div class="col-12">
                            <table class="table table-striped table-sm" id="users_table">
                                <tr>
                                    <th>No</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Roles</th>
                                    <th width="280px">Action</th>
                                </tr>
                                @foreach ($data as $key => $user)
                                    <tr>
                                        <td>{{ $user->id }}</td>
                                        <td>{{ $user->name }}</td>
                                        <td>{{ $user->email }}</td>
                                        <td>
                                            @foreach($user->roles as $role)
                                                <label class="badge badge-success">{{ $role->name }}</label>
                                            @endforeach
                                        </td>
                                        <td>
                                            <a class="btn btn-info btn-sm" href="{{ route('users.show',$user->id) }}"> <i class="fa fa-eye"></i></a>
                                            <a class="btn btn-primary btn-sm" href="{{ route('users.edit',$user->id) }}"><i class="fa fa-pencil-alt"></i></a>
                                            @if($user->id != Auth::user()->id)
                                                <form method="POST" action="{{ route('users.destroy', $user->id) }}" id="delete_form" style="display:inline">
                                                    {{ csrf_field() }}
                                                    {{ method_field('DELETE') }}
                                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure to delete this user?')"><i class="fa fa-trash"></i></button>
                                                </form>
                                            @endif
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
        $('#users_link').addClass('active');

        //delete_form submit
        // $('#delete_form').submit(function(e) {
        //     e.preventDefault();
        //     if (confirm('Are you sure you want to delete this user?')) {
        //         this.submit();
        //     }
        // });

    });
</script>
@endsection
