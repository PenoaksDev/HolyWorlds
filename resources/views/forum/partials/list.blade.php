<tr class="category {{ isset($class) ? $class : '' }}">
	<td {{ $category->threadsEnabled ? '' : 'colspan=5'}}>
		<a href="{{ route('forum.category.show', $category) }}" class="title">
			{{ $category->title }}
		</a>
		<br>
		<smaller class="text-gray">{{ $category->description }}</smaller>
	</td>
	@if ($category->threadsEnabled)
	<td class="center-align hide-on-small-only">
		@if( $category->threadCount == 0 )
		<small><span class="label label-danger">No Threads</span></small>
		@else
		{{ $category->threadCount }}
		@endif
	</td>
	<td class="right-align hide-on-small-only">
		@if ($category->newestThread)
		<a href="{{ route('forum.thread.show', $category->newestThread) }}">
			{{ $category->newestThread->title }}<br />
			<small>{{ $category->newestThread->authorName }}</small>
		</a>
		@endif
	</td>
	<td class="right-align">
		@if ($category->latestActiveThread && $category->latestActiveThread->lastPost)
		<a href="{{ route('forum.thread.show', $category->latestActiveThread->lastPost) }}">
			{{ $category->latestActiveThread->title }} <br />
			<small>{{ $category->latestActiveThread->lastPost->authorName }}</small>
		</a>
		@endif
	</td>
	@endif
</tr>
