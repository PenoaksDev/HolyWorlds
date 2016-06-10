@extends ('forum.master')

@section ('title', 'Forum Index')

@section ('content')
<div class="row">
	<div class="col-md-12">
		@foreach ($categories as $category)
			<table class="table table-striped">
				<thead>
					<th>
						<a href="{{ route('forum.category.show', $category) }}" class="title">
							{{ $category->title }}
						</a>
						<br>
						{{ $category->description }}
					</th>
					<th style="width:10%;">Threads</th>
					<th style="width:10%;">Posts</th>
					<th style="width:20%;">Newest</th>
					<th style="width:20%;">Last Post</th>
				</thead>
				<tbody class="table-striped">
					@if (!$category->children->isEmpty())
						@foreach ($category->children as $subcategory)
							@include ('forum.category.partials.list', ['category' => $subcategory])
						@endforeach
					@endif
				</tbody>
			</table>
		@endforeach
	</div>
</div>
@endsection
