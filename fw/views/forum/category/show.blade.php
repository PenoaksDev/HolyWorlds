<?php
$cat = $category;
do
{
	\Milky\Helpers\Breadcrumbs::prepend( ['url' => URL::routeModel('forum.category.show', $cat), 'title' => $cat->title] );
	$cat = $cat->parent;
}
while ( $cat );
?>

@extends ('forum.wrapper', compact('breadcrumbs'))
@section ('pagetitle', $category->title)

@section ('content')
	<div class="row">
		<div class="col-md-12">
			<p>{{ $category->description }}</p>
			@include('forum.partials.categories', compact('category'))
		</div>
	</div>
	<div class="row">
		<div class="col-md-12">
			<center>
				@include('forum.partials.pagination', ['paginator' => $category->threadsPaginated])
			</center>
		</div>
	</div>
	<div class="row">
		<div class="col-md-12">
			@if ($category->threadsEnabled)
				@can ('createThreads', $category)
				<a href="{{ URL::routeModel('forum.thread.create', $category) }}" class="btn btn-default">New Thread</a>
				@endcan
			@endif
		</div>
	</div>
	<div class="row">
		<div class="col-md-12">
			@if (!$category->threadsPaginated->isEmpty())
				@can ('manageThreads', $category)
					<form action="{{ URL::routeModel('forum.bulk.thread.update') }}" method="POST" data-actions-form>
					{!! csrf_field() !!}
					{!! method_field('delete') !!}
				@endcan
			@endif

			@if ($category->threadsEnabled)
				<table class="table table-striped">
					<thead>
						<tr>
							<th>Subject</th>
							<th class="col-md-2 right-align hidden-xs hidden-sm">Posts</th>
							<th class="col-md-2 right-align">Last Post</th>
							@can ( 'manageThreads', $category )
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
										<a href="{{ URL::routeModel('forum.thread.show', $thread) }}">
											<strong>{{ $thread->title }}</strong>
										</a>
										<br>
										<p>
											<a href="{{ $thread->author->profileUrl }}">
												{{ $thread->author->getDisplayName() }}
											</a>
											<span class="grey-text">({{ $thread->posted }})</span>
										</p>
									</td>
									@if ($thread->trashed())
										<td colspan="2">&nbsp;</td>
									@else
										<td class="right-align hidden-xs hidden-sm">
											{{ $thread->postCount }}
										</td>
										<td class="right-align">
											@if( $thread->lastPost == null )
												<small><span class="label label-danger">No Posts</span></small>
											@else
												@if ( $thread->lastPost->author != null )
													<a href="{{ $thread->lastPost->author->profile->url }}">
														<strong>{{ $thread->lastPost->authorName }}</strong>
													</a>
												@endif
												<br>
												<span class="grey-text">({{ $thread->lastPost->posted }})</span>
												<br>
												<a href="{{ URL::routeModel('forum.thread.show', $thread->lastPost) }}">View Post</a>
											@endif
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
										<a href="{{ URL::routeModel('forum.thread.create', $category) }}">Be the first to post!</a>
									@endcan
								</td>
							</tr>
						@endif
					</tbody>
				</table>
			@endif

			@if (!$category->threadsPaginated->isEmpty())
				@can ('manageThreads', $category)
					@include ('forum.partials.thread-actions')
					</form>
				@endcan
			@endif
			</div>
		</div>

		<div class="row">
			<div class="col-md-12">
				@if ($category->threadsEnabled)
					@has ('org.holyworlds.forum.thread.create.' . $category->id)
						<a href="{{ URL::routeModel('forum.thread.create', $category) }}" class="btn btn-default">New Thread</a>
					@endhas
				@endif
			</div>
		</div>
		<div row="row">
			<div class="col-md-12">
				<center>
					@include('forum.partials.pagination', ['paginator' => $category->threadsPaginated])
				</center>
			</div>
		</div>

		@if ($category->threadsEnabled)
			@can ('markNewThreadsAsRead')
				<hr>
				<div class="center-align">
					<form action="{{ URL::routeModel('forum.mark-new') }}" method="POST" data-confirm>
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
@endsection

@section('after_content')
	@can ('manageCategories')
		<hr>
		<form action="{{ URL::routeModel('forum.category.update', $category) }}" method="POST" data-actions-form>
			{!! csrf_field() !!}
			{!! method_field('patch') !!}

			@include ('forum.partials.actions')
		</form>
	@endcan
@endsection
