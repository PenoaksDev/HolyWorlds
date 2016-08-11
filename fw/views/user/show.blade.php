@extends('sidebar_left')

<?php
$breadcrumbs[] = 'Profile';
$breadcrumbs[] = $user->id;
?>

@section('title', $user->name)

@section('left')
	<div style="text-align: center;">
		@include('user.partials.avatar', ['class' => 'img-thumbnail', 'size' => 200])
		<h4>{{ $user->getDisplayName() }}</h4>
		<strong>Joined:</strong> {{ $user->created_at->diffForHumans() }}
	</div>
@endsection

@section('content')
	@if ($user->profile->about)
		<h3>About</h3>

		{{-- Markdown::convertToHtml($user->profile->about) --}}
	@endif

	<h5>Group Membership</h5>
	@if ( count( $user->groups ) == 0 )
		<i>This user is not a member of any groups</i>
	@endif
	@foreach ( $user->groups as $group )
		<a href="{!! URL::routeModel( 'group.show', $group ) !!}" class="badge {!! $group->class !!}">{{ $group->name }}</a>
	@endforeach

	@if ($user->profile->signature)
		<hr>
		{!! $user->profile->signature_bbcode ? \HolyWorlds\Support\BBCodeParser::parse( $user->profile->signature ) :  Markdown::convertToHtml( $user->profile->signature ) !!}
	@endif

	<hr>

	@include('comment.partials.add', ['model' => 'UserProfile', 'id' => $user->profile->id])
	@include('comment.partials.list', ['noComments' => "{$user->name} has no comments yet. :&#40;"])
@endsection
