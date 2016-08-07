@extends('wrapper')

@section('title', 'Community of Christ-centered Creativity')
@section('pagetitle', 'Welcome!')

@section('content')
	@if ( !\Milky\Framework::environment( 'production' ) )
	<div class="row">
		<div class="col-sm-12">
			<div class="alert alert-danger" role="alert">
				<h1 style="margin: 0;"><i class="fa fa-exclamation-triangle" aria-hidden="true"></i> Development Site Notice</h1>
				<p>
					This website is currently under active development. Nothing is final and things should be expected to break at any moment.
					Changes are deployed to production every Monday at 2am, with the exception of manual deployments when patching of critical bugs is required.
					If your interested in contributing to our codebase or would like to file a bug report, please see our GitHub repository at <a target="_blank" href="https://github.com/PenoaksDev/HolyWorlds">https://github.com/PenoaksDev/HolyWorlds</a>
				</p>
			</div>
		</div>
	</div>
	@endif
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
		{{-- <div class="col-md-8">
			@foreach ($articles as $article)
				@include('article.partials.list')
			@endforeach
			@include('forum.partials.pagination', ['paginator' => $articles])
		</div> --}}
		<div class="col-md-4">
			<h4>Latest forum threads</h4>
			<ul class="collection">
				@foreach ($newThreads as $thread)
					<li class="collection-item grey-text">
						<a href="{{ URL::route('forum.thread.show', $thread) }}">
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
		</div>
		<div class="col-md-4">
			<h4>Latest forum replies</h4>
			<ul class="collection">
				@foreach ($newPosts as $post)
					<li class="collection-item grey-text">
						Re: <a href="{{ URL::route('forum.thread.show', $post) }}">
							{{ $post->thread->title }}
						</a>
						by
						@if ( $post->author )
							<a href="{{ $post->author->profile->url }}">
								{{ $post->author->name }}
							</a>
						@endif
						<br />
						{{ $post->created_at->diffForHumans() }}
					</li>
				@endforeach
			</ul>
		</div>
	</div>
@endsection
