@extends('sidebar_left')

<?php
$breadcrumbs[] = 'Group';
$breadcrumbs[] = $group->id;
?>

@section('title', $group->name)

@section('left')
	<div style="text-align: center;">
		<h4>{{ $group->name }}</h4>
	</div>
@endsection

@section('content')
	<h5>Group Membership</h5>
	@if ( count( $group->groups ) == 0 )
		<i>This group is not a member of any groups</i>
	@endif
	@foreach ( $group->groups as $group2 )
		<a href="{!! URL::routeModel( 'group.show', $group2 ) !!}" class="badge {!! $group2->class !!}">{{ $group2->name }}</a>
	@endforeach

	<h5>Users</h5>
	@foreach ( $group->childUsers as $child )
		<a href="{!! URL::routeModel( 'user.show', $child ) !!}" class="badge {!! $child->class !!}">{{ $child->name }}</a>
	@endforeach

	<h5>Groups</h5>
	@foreach ( $group->childGroups as $child )
		<a href="{!! URL::routeModel( 'group.show', $child ) !!}" class="badge {!! $child->class !!}">{{ $child->name }}</a>
	@endforeach
@endsection
