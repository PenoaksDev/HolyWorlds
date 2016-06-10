<?php
$breadcrumbs = ["<a href=\"" . route('forum.category.show', $category) . "\">" . $category->title . "</a>"];
$cat = $category;
while ( !is_null($cat->parent) )
{
    $cat = $cat->parent;
    array_unshift($breadcrumbs, "<a href=\"" . route('forum.category.show', $cat) . "\">" . $cat->title . "</a>");
}
?>
@extends ('forum.master', compact('breadcrumbs'))

@section ('pagetitle', $category->title)

@section ('content')
	<div class="row">
		<div class="col-md-12">
			@if (!$category->children->isEmpty())
				<table class="table table-striped">
					<thead>
						<tr>
							<th>Category</th>
							<th style="width:10%;">Threads</th>
							<th style="width:10%;">Posts</th>
							<th style="width:20%;">Newest</th>
							<th style="width:20%;">Last Post</th>
						</tr>
					</thead>
					<tbody>
						@foreach ($category->children as $subcategory)
							@include ('forum.category.partials.list', ['category' => $subcategory])
						@endforeach
					</tbody>
				</table>
			@endif

			<div class="row">
				<div class="col-sm-4">
					@if ($category->threadsEnabled)
						@can ('createThreads', $category)
							<a href="{{ route('forum.thread.create', $category) }}" class="btn btn-default">New Thread</a>
						@endcan
					@endif
				</div>
				<div class="col-sm-8 right-align">
					@include('partials.pagination', ['paginator' => $category->threadsPaginated])
				</div>
			</div>

			@if (!$category->threadsPaginated->isEmpty())
				@can ('manageThreads', $category)
					<form action="{{ route('forum.bulk.thread.update') }}" method="POST" data-actions-form>
						{!! csrf_field() !!}
						{!! method_field('delete') !!}
				@endcan
			@endif

			@if ($category->threadsEnabled)
				<table class="table table-striped">
					<thead>
						<tr>
							<th>Subject</th>
							<th class="col-md-2 right-align hide-on-small-only">Replies</th>
							<th class="col-md-2 right-align">Last Post</th>
							@can ('manageThreads', $category)
								<th class="col-md-1 right-align"><input type="checkbox" data-toggle-all></th>
							@endcan
						</tr>
					</thead>
					<tbody>
						@if (!$category->threadsPaginated->isEmpty())
							@foreach ($category->threadsPaginated as $thread)
								<tr class="{{ $thread->trashed() ? "deleted" : "" }}">
									<td>
										<span class="pull-right">
											@if ($thread->locked)
												<span class="label label-warning">Locked</span>
											@endif
											@if ($thread->pinned)
												<span class="label label-info">Pinned</span>
											@endif
											@if ($thread->userReadStatus && !$thread->trashed())
												<span class="label label-primary">{{ $thread->userReadStatus }}</span>
											@endif
											@if ($thread->trashed())
												<span class="label label-danger">Deleted</span>
											@endif
										</span>
										<a href="{{ route('forum.thread.show', $thread) }}">
											<strong>{{ $thread->title }}</strong>
										</a>
										<br>
										<p>
											<a href="{{ $thread->author->profile->url }}">
												{{ $thread->authorName }}
											</a>
											<span class="grey-text">({{ $thread->posted }})</span>
										</p>
									</td>
									@if ($thread->trashed())
										<td colspan="2">&nbsp;</td>
									@else
										<td class="right-align hide-on-small-only">
											{{ $thread->replyCount }}
										</td>
										<td class="right-align">
											<a href="{{ $thread->lastPost->author->profile->url }}">
												<strong>{{ $thread->lastPost->authorName }}</strong>
											</a>
											<br>
											<span class="grey-text">({{ $thread->lastPost->posted }})</span>
											<br>
											<a href="{{ route('forum.thread.show', $thread->lastPost) }}">View Post</a>
										</td>
									@endif
									@can ('manageThreads', $category)
										<td class="right-align">
											<input type="checkbox" name="items[]" id="select-thread-{{ $thread->id }}" value="{{ $thread->id }}">
											<label for="select-thread-{{ $thread->id }}"></label>
										</td>
									@endcan
								</tr>
							@endforeach
						@else
							<tr>
								<td>No threads found</td>
								<td class="right-align" colspan="3">
									@can ('createThreads', $category)
										<a href="{{ route('forum.thread.create', $category) }}">Be the first to post!</a>
									@endcan
								</td>
							</tr>
						@endif
					</tbody>
				</table>
			@endif

			@if (!$category->threadsPaginated->isEmpty())
				@can ('manageThreads', $category)
						@include ('category.partials.thread-actions')
					</form>
				@endcan
			@endif

			<div class="row">
				<div class="col s4">
					@if ($category->threadsEnabled)
						@can ('createThreads', $category)
							<a href="{{ route('forum.thread.create', $category) }}" class="btn btn-default">New Thread</a>
						@endcan
					@endif
				</div>
				<div class="col s8 right-align">
					@include('partials.pagination', ['paginator' => $category->threadsPaginated])
				</div>
			</div>

			@if ($category->threadsEnabled)
				@can ('markNewThreadsAsRead')
					<hr>
					<div class="center-align">
						<form action="{{ route('forum.mark-new') }}" method="POST" data-confirm>
							{!! csrf_field() !!}
							{!! method_field('patch') !!}
							<input type="hidden" name="category_id" value="{{ $category->id }}">
							<button class="btn btn-default">Mark These Threads as Read</button>
						</form>
					</div>
				@endcan
			@endif
		</div>
	</div>
@stop

@section('after_content')
@can ('manageCategories')
	<hr>
	<form action="{{ route('forum.category.update', $category) }}" method="POST" data-actions-form>
		{!! csrf_field() !!}
		{!! method_field('patch') !!}

		@include ('category.partials.actions')
	</form>
@endcan
@stop
