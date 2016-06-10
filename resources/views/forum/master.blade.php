@extends('wrapper')

@section('breadcrumbs')
	<li><a href="{{ url('forum') }}">Forum</a></li>
	@if ( isset( $breadcrumbs ) )
		@foreach ( $breadcrumbs as $crumb )
			{!! "<li>$crumb</li>" !!}
		@endforeach
	@endif
@stop
