@extends('app')

@section('body_class', 'home')

@section('title', 'Community of Christ-centered Creativity')
@section('pagetitle', 'Home')
@section('subtitle', "<em>{$quote}</em>")

@section('content')
<div class="row">
	<div class="col-md-4">
		<h4>Newest Users</h4>
		<ul class="list-group">
			@foreach ($newUsers as $user)
			<li class="list-group-item">
				@include('user.partials.avatar', ['user' => $user])
				<a href="{{ $user->profile->url }}">
					{{ $user->name }}
				</a>
				<span>joined {{ $user->created_at->diffForHumans() }}</span>
			</li>
			@endforeach
		</ul>

		@if (!$onlineUsers->isEmpty())
		<h4>Online Users</h4>
		<ul class="list-group">
			@foreach ($onlineUsers as $session)
			<li class="list-group-item">
				@include('user.partials.avatar', ['user' => $session->user])
				<a href="{{ $session->user->profile->url }}">
					{{ $session->user->name }}
				</a>
				<span>{{ $session->last_activity->diffForHumans() }}</span>
			</li>
			@endforeach
		</ul>
		@endif
	</div>
	<div class="col-md-8">
		@foreach ($articles as $article)
			@include('article.partials.list')
		@endforeach
		@include('partials.pagination', ['paginator' => $articles])
	</div>
	<div class="col s12 m12 l3">
		<h4>Latest forum threads</h4>
		<ul class="collection">
			@foreach ($newThreads as $thread)
			<li class="collection-item grey-text">
				<a href="{{ route('forum.thread.show', $thread) }}">
					{{ $thread->title }}
				</a>
				by
				<a href="{{ $thread->author->profile->url }}">
					{{ $thread->author->name }}
				</a>
				<br>
				{{ $thread->created_at->diffForHumans() }}
			</li>
			@endforeach
		</ul>
		<hr>
		<h4>Latest forum replies</h4>
		<ul class="collection">
			@foreach ($newPosts as $post)
			<li class="collection-item grey-text">
				Re: <a href="{{ route('forum.thread.show', $post) }}">
				{{ $post->thread->title }}
			</a>
			by
			<a href="{{ $post->author->profile->url }}">
				{{ $post->author->name }}
			</a>
			<br>
			{{ $post->created_at->diffForHumans() }}
		</li>
		@endforeach
	</ul>
</div>
</div>
@endsection
