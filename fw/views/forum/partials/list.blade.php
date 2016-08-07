<tr class="category {{ isset($class) ? $class : '' }}">
	<td {{ $category->threadsEnabled ? '' : 'colspan=5'}}>
		<a href="{{ URL::routeModel('forum.category.show', $category) }}" class="title">
			{{ $category->title }}
		</a>
		<br>
		<smaller class="text-gray">{{ $category->description }}</smaller>
	</td>
	@if ($category->threadsEnabled)
	<td class="center-align hidden-xs hidden-sm">
		@if( $category->threadCount == 0 )
		<small><span class="label label-danger">No Threads</span></small>
		@else
		{{ $category->threadCount }}
		@endif
	</td>
	<td class="right-align hidden-xs hidden-sm">
		@if ($category->newestThread)
		<a href="{{ URL::routeModel('forum.thread.show', $category->newestThread) }}">
			{{ $category->newestThread->title }}<br />
			<small>{{ $category->newestThread->authorName }}</small>
		</a>
		@endif
	</td>
	<td class="right-align hidden-xs hidden-sm">
		@if ($category->latestActiveThread && $category->latestActiveThread->lastPost)
		<a href="{{ URL::routeModel('forum.thread.show', $category->latestActiveThread->lastPost) }}">
			{{ $category->latestActiveThread->title }} <br />
			<small>{{ $category->latestActiveThread->lastPost->authorName }}</small>
		</a>
		@endif
	</td>
	@endif
</tr>
