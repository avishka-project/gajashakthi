@if(in_array('user-list',$userPermissions) || in_array('role-list',$userPermissions))

<div class="row nowrap" style="padding-top: 5px;padding-bottom: 5px">

    @if(in_array('user-list',$userPermissions))
    <a role="button" class="btn navbtncolor" href="{{ route('users.index') }}" id="users_link">
        Users<span class="caret"></span> </a>
    @endif

    @if(in_array('role-list',$userPermissions))
    <a role="button" class="btn navbtncolor" href="{{ route('roles.index') }}" id="roles_link">
        Roles<span class="caret"></span> </a>
    @endif

</div>
@endif