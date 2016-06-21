@extends('wrapper')

@section('title', "Messages")

@section('breadcrumbs')
	<li><a href="{{ route('messages.index') }}">Messages</a></li>
@endsection

@section('content')
	<div class="row">
		<div class="col-sm-9">
			@yield('chat')
		</div>
		<div class="col-sm-3">
			<h3>Chat Channels</h3>
			<div class="list-group">
				@foreach ( \App\Models\MsgChannel::get() as $channel )
					@if ( empty( $channel->perm ) || Auth::user()->hasPermission( $channel->perm ) )
						<a href="{{ route( 'messages.channel.show', $channel->id ) }}" class="list-group-item<?php if ( $id == $channel->id ) echo ' active'; ?>">
							<h4 class="list-group-item-heading">{{ $channel->title }}</h4>
							<p class="list-group-item-text">...</p>
						</a>
					@endif
				@endforeach
			</div>
			<h3>Private Messages</h3>
			<div class="list-group">
				@foreach ( \App\Models\User::orderByRaw('RAND()')->limit(6)->get() as $user )
				<a href="#" class="list-group-item">
					<h4 class="list-group-item-heading">{{ $user->name }}</h4>
					<p class="list-group-item-text">...</p>
				</a>
				@endforeach
			</div>
		</div>
	</div>
@endsection
