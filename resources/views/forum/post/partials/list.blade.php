<div class="row" id="post-{{ $post->sequenceNumber }}" class="post {{ $post->trashed() ? 'deleted' : '' }}">
	<div class="col-sm-2 author-info center-align hide-on-small-only">
		@if ( $post->author != null )
			<p class="center-align">
				<a href="{{ $post->author->profile->url }}" class="author-name">
					{!! $post->author->displayName !!}
				</a>
				<a href="{{ $post->author->profile->url }}">
					@include('user.partials.avatar', ['user' => $post->author, 'class' => 'circular'])
				</a>
			</p>
			@include ('user.partials.rank-list', ['user' => $post->author])
			@if (!is_null($post->author->mainCharacter))
				<p class="grey-text center-align">
					Main character:
					<br>
					<a href="{{ $post->author->mainCharacter->url }}">{{ $post->author->mainCharacter->name }}</a>
				</p>
			@endif
		@endif
	</div>
	<div class="col-sm-10 body {{ !empty($post->author->profile->signature) ? 'with-signature' : '' }}">
		@if ( !is_null($post->author) )
			<span class="grey-text hide-on-med-and-up">
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
					(<a href="{{ route('forum.post.show', $post->parent) }}">{{ trans('posts.view') }}</a>):
				</strong>
			</p>
			<blockquote>
				{!! str_limit(Forum::render($post->parent->content)) !!}
			</blockquote>
		@endif

		@if ($post->trashed())
			<span class="label label-danger">{{ trans('general.deleted') }}</span>
		@else
			{!! Markdown::convertToHtml($post->content) !!}

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
<div class="row">
	<div class="col-sm-2">
		@if (!$post->trashed())
			@can ('edit', $post)
				<a href="{{ route('forum.post.edit', $post) }}">{{ trans('general.edit') }}</a>
			@endcan
		@endif
	</div>
	<div class="col-sm-10">
		<span class="hide-on-small-only">
			{{ trans('general.posted') }} {{ $post->posted }}@if ($post->hasBeenUpdated()), updated {{ $post->updated }}@endif
		</span>
		<span class="hide-on-med-and-up">
			@if (!$post->trashed())
				@can ('edit', $post)
					<a href="{{ route('forum.post.edit', $post) }}">{{ trans('general.edit') }}</a>
				@endcan
			@endif
		</span>
		<span class="pull-right">
			<a href="{{ route('forum.thread.show', $post) }}">#{{ $post->sequenceNumber }}</a>
			@if (!$post->trashed())
				@can ('reply', $post->thread)
					- <a href="{{ route('forum.post.create', $post) }}">{{ trans('general.reply') }}</a>
				@endcan
			@endif
			@if (Request::fullUrl() != route('forum.post.show', $post))
				- <a href="{{ route('forum.post.show', $post) }}">{{ trans('posts.view') }}</a>
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
