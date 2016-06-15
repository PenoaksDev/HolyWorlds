<div class="row post-row {{ $post->trashed() ? 'deleted' : '' }}" id="post-{{ $post->sequenceNumber }}">
	<div class="col-sm-2 post-author">
		@if ( $post->author != null )
			<center>
				<a href="{{ $post->author->profile->url }}" class="author-name">
					<p class="author-name">{{ $post->author->displayName }}</p>
					@include('user.partials.avatar', ['size' => 125, 'user' => $post->author, 'class' => 'img-circle'])
				</a>
				@include ('user.partials.rank-list', ['user' => $post->author])
			</center>
		@else
			<p class="alert alert-danger">We had a problem!</p>
		@endif
	</div>
	<div class="col-sm-10 post-body {{ !empty($post->author->profile->signature) ? 'with-signature' : '' }}">
		@if ( !is_null($post->author) )
			<span class="grey-text hide-on-med-and-up" style="display: none;">
				{{ $post->posted }}
				<a href="{{ $post->author->profile->url }}" class="author-name">
					<strong>
						@include('user.partials.avatar', ['user' => $post->author, 'class' => 'tiny circular'])
						{!! $post->author->displayName !!}
					</strong>
				</a>
				said…
			</span>
		@endif
		@if (!is_null($post->parent))
			<p>
				<strong>
					{{ trans('general.response_to', ['item' => $post->parent->authorName]) }}
					(<a href="{{ route('forum.post.show', $post->parent) }}">View Post</a>):
				</strong>
			</p>
			<blockquote>
				{!! str_limit(Forum::render($post->parent->content)) !!}
			</blockquote>
		@endif

		@if ($post->trashed())
			<span class="label label-danger">{{ trans('general.deleted') }}</span>
		@else
			@if ( $post->bbcode )
				<?php
				$bbcode = new App\BBCodeParser;
				echo $bbcode->parse( $post->content );
				?>
			@else
				{!! Markdown::convertToHtml($post->content) !!}
			@endif

			@if ($post->hasBeenUpdated())
				<p class="grey-text hide-on-med-and-up">
					<em>Edited {{ $post->updated }}</em>
				</p>
			@endif

			@if (!empty($post->author->profile->signature))
				<blockquote class="signature">
					{{ $post->author->profile->signature }}
				</blockquote>
			@endif
		@endif
	</div>
</div>
<div class="row post-footer">
	<div class="col-sm-2">
		@if (!$post->trashed())
			@can ('edit', $post)
				<a href="{{ route('forum.post.edit', $post) }}">Edit</a>
			@endcan
		@endif
	</div>
	<div class="col-sm-10">
		<span class="hide-on-small-only">
			Posted {{ $post->posted }}@if ($post->hasBeenUpdated()), updated {{ $post->updated }}@endif
		</span>
		<span class="hide-on-med-and-up">
			@if (!$post->trashed())
				@can ('edit', $post)
					<a href="{{ route('forum.post.edit', $post) }}">Edit</a>
				@endcan
			@endif
		</span>
		<span class="pull-right">
			<a href="{{ route('forum.thread.show', $post) }}">#{{ $post->sequenceNumber }}</a>
			@if (!$post->trashed())
				@can ('reply', $post->thread)
					- <a href="{{ route('forum.post.create', $post) }}">Reply</a>
				@endcan
			@endif
			@if (Request::fullUrl() != route('forum.post.show', $post))
				- <a href="{{ route('forum.post.show', $post) }}">View Post</a>
			@endif
			@if (isset($thread))
				@can ('deletePosts', $thread)
					@if (!$post->isFirst)
						<input type="checkbox" name="items[]" id="select-post-{{ $post->id }}" value="{{ $post->id }}">
						<label for="select-post-{{ $post->id }}"></label>
					@endif
				@endcan
			@endif
		</span>
	</div>
</div>
