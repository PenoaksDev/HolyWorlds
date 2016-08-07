@extends('wrapper')

@section('title', 'Sign in')

@section('content')
<div class="row">
	<div class="col-md-9">
		<form class="form-signin form-horizontal" action="{{ URL::route( 'login' ) }}" method="post" role="form">
			{!! Session::csrfField() !!}
			<input name="target" type="hidden" value="">
			<div class="form-group">
				<label for="username" class="col-sm-2 control-label">Email</label>
				<div class="col-sm-10">
					<input autocomplete="off" name="username" id="username" type="email" value="{{ Request::old('name_or_email') }}" class="form-control" placeholder="E-mail" required="" autofocus="">
				</div>
			</div>
			<div class="form-group">
				<label for="password" class="col-sm-2 control-label">Password</label>
				<div class="col-sm-10">
					<input autocomplete="off" name="password" id="password" type="password" class="form-control" placeholder="Password">
				</div>
			</div>
			<div class="form-group">
				<div class="col-sm-offset-2 col-sm-10">
					<div class="checkbox">
						<label>
							<input name="remember" type="checkbox" value="true"> Remember me
						</label>
					</div>
				</div>
			</div>
			<div class="form-group">
				<div class="col-sm-offset-2 col-sm-10">
					<a href="{{ URL::route( 'register' )  }}" class="btn btn-default"><span class="fa fa-pencil-square-o"></span> Registration</a>
					<a class="btn btn-default" href="{{ url('auth/password/reset') }}">Reset Password</a>
					<button class="btn btn-success pull-right" type="submit"><span class="fa fa-sign-in"></span> Sign In</button>
				</div>
			</div>
		</form>
	</div>
	<div class="col-md-3">
		<div class="sidebar-module">
			<h5>Alternatively, log in via...</h5>
			@foreach ( Config::get('auth.login_providers') as $key => $provider )
				<p><a href="{{ url( "auth/{$key}" ) }}" class="btn btn-default btn-large brand-{{ $key }}">{{ $provider }} :D</a></p>
			@endforeach
		</div>
	</div>
</div>
@endsection
