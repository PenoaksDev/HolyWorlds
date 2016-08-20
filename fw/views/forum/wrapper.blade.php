@extends('wrapper')

<?php

?>

@section('breadcrumbs')
	<li><a href="{{ URL::route( 'forum.index' ) }}">Forum</a></li>
	@if ( isset( $breadcrumbs ) )
		@foreach ( $breadcrumbs as $crumb )
			{!! "<li>$crumb</li>" !!}
		@endforeach
	@endif
@endsection
