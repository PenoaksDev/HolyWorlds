<?php
$breadcrumbs = ["<a href=\"" . route('forum.thread.show', $thread) . "\">" . $thread->title . "</a>"];
$cat = App\Models\Forum\Category::find( $thread->category_id );
while ( $cat && !is_null($cat->parent) )
{
	array_unshift($breadcrumbs, "<a href=\"" . route('forum.category.show', $cat) . "\">" . $cat->title . "</a>");
	$cat = $cat->parent;
}
?>
@extends ('forum.master', compact('breadcrumbs'))

@section('subtitle')
@if ($thread->trashed())
	[{{ trans('general.deleted') }}]
@endif
@if ($thread->locked)
	[{{ trans('threads.locked') }}]
@endif
@if ($thread->pinned)
	[{{ trans('threads.pinned') }}]
@endif
{{ $thread->title }}
@stop

@section ('content')
	<div id="thread">
		@if ($thread->postCount)
			@can ('deletePosts', $thread)
				<form action="{{ route('forum.bulk.post.update') }}" method="POST" data-actions-form>
					{!! csrf_field() !!}
					{!! method_field('delete') !!}
			@endcan
		@endif

		@can ('reply', $thread)
			<div class="row">
				<div class="col s12 l6">
					<div class="btn-group" role="group">
						<a href="{{ route('forum.post.create', $thread) }}" class="btn btn-large">{{ trans('general.new_reply') }}</a>
						<a href="#quick-reply" class="btn btn-large">{{ trans('general.quick_reply') }}</a>
					</div>
				</div>
				<div class="col s12 l6 right-align">
					@include('partials.pagination', ['paginator' => $thread->postsPaginated])
				</div>
			</div>
		@endcan

		<table class="bordered {{ $thread->trashed() ? 'deleted' : '' }}">
			<thead>
				<tr>
					<th colspan="2" class="right-align">
						@if ($thread->postCount)
							@can ('deletePosts', $thread)
								<input type="checkbox" id="toggle-all" data-toggle-all>
								<label for="toggle-all">Select all posts</label>
							@endcan
						@endif
					</th>
				</tr>
			</thead>
			<tbody>
				@foreach ($thread->postsPaginated as $post)
					@include ('forum.post.partials.list', compact('post'))
				@endforeach
			</tbody>
		</table>

		@if ($thread->postCount)
			@can ('deletePosts', $thread)
					@include ('forum.thread.partials.post-actions')
				</form>
			@endcan
		@endif

		@include('forum.partials.pagination', ['paginator' => $thread->postsPaginated])

		@can ('reply', $thread)
			<h3>{{ trans('general.quick_reply') }}</h3>
			<div id="quick-reply">
				<form method="POST" action="{{ route('forum.post.store', $thread) }}">
					{!! csrf_field() !!}

					<div class="form-group">
						<textarea name="content" class="form-control">{{ old('content') }}</textarea>
					</div>

					<div class="right-align">
						<button type="submit" class="btn btn-large">{{ trans('general.reply') }}</button>
					</div>
				</form>
			</div>
		@endcan
	</div>
@stop

@section('after_content')
@can ('manageThreads', $category)
	<hr>
	<form action="{{ route('forum.thread.update', $thread) }}" method="POST" data-actions-form>
		{!! csrf_field() !!}
		{!! method_field('patch') !!}

		@include ('thread.partials.actions')
	</form>
@endcan
@stop

@section ('footer')
	<script>
	$('tr input[type=checkbox]').change(function () {
		var postRow = $(this).closest('tr').prev('tr');
		$(this).is(':checked') ? postRow.addClass('active') : postRow.removeClass('active');
	});
	</script>
@stop
