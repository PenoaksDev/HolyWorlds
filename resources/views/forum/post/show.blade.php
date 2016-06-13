@extends ('wrapper')

@section ('title', 'Viewing Thread')

@section ('content')
	<h2>{{ trans('posts.view') }} ({{ $thread->title }})</h2>
	<a href="{{ route('forum.thread.show', $thread) }}" class="btn btn-default">&laquo; {{ trans('threads.view') }}</a>

	<div class="row">
		<div class="col-md-2">
			Author
		</div>
		<div class="col-sm-10">
			Post
		</div>
	</div>
	@include ('post.partials.list', compact('post'))
@stop
