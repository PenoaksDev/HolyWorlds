@if ( isset( $breadcrumbs ) && count( $breadcrumbs ) > 0 || array_key_exists('breadcrumbs', View::getSections()))
	<ol class="hidden-xs hidden-sm breadcrumb pull-right" style="margin: 0;">
		<li><a href="{{ URL::route('root') }}">Home</a></li>

		@if (isset($category) && $category)
			@if (!is_null($category->parent))
				@include ('partials.breadcrumb-categories', ['category' => $category->parent])
			@endif
			<a href="{{ URL::route('forum.category.show', $category) }}" class="breadcrumb">{{ $category->title }}</a>
		@endif
		@if (isset($thread) && $thread)
			<a href="{{ URL::route('forum.thread.show', $thread) }}" class="breadcrumb">{{ $thread->title }}</a>
		@endif
		@if (isset($breadcrumb_other) && $breadcrumb_other)
			<span class="breadcrumb">{!! $breadcrumb_other !!}</span>
		@endif

		<?php
			if ( isset( $breadcrumbs ) && count( $breadcrumbs ) > 0 )
			foreach ( $breadcrumbs as $crumb )
			{
				if ( is_string( $crumb ) || count( $crumb ) == 1 )
					echo "<li>{$crumb}</li>";
				else
					echo "<li><a href=\"{$crumb[1]}\">{$crumb[0]}</a></li>";
			}
		?>

		@yield('breadcrumbs')
	</ol>
@endif
