<?php $curve = App\Models\Setting::findOrNew('forum_post_curve')->value(15000); ?>
<div class="row post-row {{ $post->trashed() ? 'deleted' : '' }}" id="post-{{ $post->sequenceNumber }}">
	<div class="col-md-2 hidden-xs hidden-sm post-author">
		@if ( $post->author != null )
			<center>
				<a href="{{ $post->author->profile->url }}" class="author-name">
					<p class="author-name">{{ $post->author->displayName }}</p>
					@include('user.partials.avatar', ['size' => 125, 'user' => $post->author, 'class' => 'img-circle'])
				</a>
				@include ('user.partials.rank-list', ['user' => $post->author])
				<div class="smaller text-gray">
					<p>Posts: {{ $post->author->profile->post_count }}</p>
					<div class="post-rank">
						<?php
							for( $i = 0; $i < (10 * round( $post->author->profile->post_count / $curve, 1 )); $i++ )
								if ( $i % 2 == 0 )
									echo "<i class=\"fa fa-book\" aria-hidden=\"true\"></i>";
							if ( $i % 2 == 1 )
								echo "<i class=\"fa fa-pencil\" aria-hidden=\"true\"></i>";
						?>
					</div>
				</div>
			</center>
		@else
			<p class="alert alert-danger">We had a problem!</p>
		@endif
	</div>
	<div class="col-md-10 post-body">
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
				{!! App\BBCodeParser::parse( $post->content ) !!}
			@else
				{!! Markdown::convertToHtml( $post->content ) !!}
			@endif

			@if ($post->hasBeenUpdated())
				<p class="grey-text hide-on-med-and-up">
					<em>Edited {{ $post->updated }}</em>
				</p>
			@endif

			@if (!empty($post->author->profile->signature))
				<div class="post-signature" id="post-signature-{{ $post->id }}">
					<div class="post-signature-body" style="max-height: 125px;">
						@if ( $post->author->profile->signature_bbcode == true )
							{!! App\BBCodeParser::parse( $post->author->profile->signature ) !!}
						@else
							{!! Markdown::convertToHtml( $post->author->profile->signature ) !!}
						@endif
					</div>
					<div class="post-signature-footer">
						<a href="javascript: toggle('post-signature-{{ $post->id }}');"><i class="fa fa-angle-double-down" aria-hidden="true"></i></a>
					</div>
				</div>
			@endif
		@endif
	</div>
</div>
<div class="row">
	<div class="col-sm-12 post-footer">
		<div class="post-footer-inner">
				Posted {{ $post->posted }}@if ($post->hasBeenUpdated()), updated {{ $post->updated }}@endif
			</span>
			<span class="pull-right">
				<a href="{{ route('forum.thread.show', $post) }}">#{{ $post->sequenceNumber }}</a>
				@if (!$post->trashed())
					@can ('reply', $post->thread)
						- <a href="{{ route('forum.post.create', $post) }}">Reply</a>
					@endcan
				@endif
				@if (Request::fullUrl() != route('forum.post.show', $post))
					| <a href="{{ route('forum.post.show', $post) }}">View Post</a>
				@endif
				@if (!$post->trashed())
					@can ('edit', $post)
					| <a href="{{ route('forum.post.edit', $post) }}">Edit</a>
					@endcan
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
</div>
