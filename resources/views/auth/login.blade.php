@extends('layouts.default')

@section('content')
<main>

<div class="container">
                        <div class="row justify-content-center">
                            <div class="col-lg-4">
                                <div class="card shadow-lg border-0 rounded-lg mt-5">
                                    <div class="card-header justify-content-center"><img src="{{url('/images/logogaja.png')}}" class="img-fluid" alt=""></div>
                                    <div class="card-body">
                                        <form class="form-horizontal" method="POST" action="{{ route('login') }}" autocomplete="off">
                                            {{ csrf_field() }}
                                            <div class="form-group mb-1 {{ $errors->has('email') ? ' has-error' : '' }}"><label class="small mb-2" for="inputEmailAddress">Email</label><input class="form-control form-control-sm" name="email" id="email" type="email" placeholder="Enter email address"  value="{{ old('email') }}" required autofocus/></div>
                                            @if ($errors->has('email'))
                                                <span class="help-block">
                                                    <strong>{{ $errors->first('email') }}</strong>
                                                </span>
                                            @endif
                                            <div class="form-group mb-1 {{ $errors->has('password') ? ' has-error' : '' }}" ><label class="small mb-2" for="inputPassword">Password</label><input class="form-control form-control-sm" id="password" name="password" type="password" placeholder="Enter password" required/></div>
                                            @if ($errors->has('password'))
                                                <span class="help-block">
                                                    <strong>{{ $errors->first('password') }}</strong>
                                                </span>
                                            @endif
                                            <div class="form-group">
                                                <div class="custom-control custom-checkbox"> <input class="custom-control-input" type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}> <label class="custom-control-label" for="rememberPasswordCheck">Remember password</label></div>
                                            </div>
                                            <div class="form-group text-right mt-4 mb-0">
                                                <button type="submit" class="btn btn-primary btn-sm px-3">Login</button>
                                            </div>
                                        </form>
                                    </div>
                                    <div class="card-footer bg-laugfs">
                                        <div class="row">
                                            <div class="col text-center"><img src="{{url('/images/hrm.png')}}" class="img-fluid" alt=""></div>
                                            <div class="col-md-12 small text-center">Copyright &copy; ERav Technology <?php echo date('Y') ?></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
</main>
@endsection
