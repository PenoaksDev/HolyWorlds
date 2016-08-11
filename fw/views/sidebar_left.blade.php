@extends('wrapper')

@section('content_override')
	<div class="row">
		<div class="col-sm-3">
			@yield('left')
		</div>
		<div class="col-sm-9">
			@yield('content')
		</div>
	</div>
@endsection
