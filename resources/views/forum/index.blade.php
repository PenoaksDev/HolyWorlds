@extends ('forum.master')

@section ('title', 'Forum Index')

@section ('content')
<div class="row">
	<div class="col-md-12">
		@foreach ($categories as $category)
			<a href="{{ Helper::route('forum.category.show', $category) }}" class="title">
				<h3 style="margin-bottom: 0;">{{ $category->title }}</h3>
			</a>
			<p>{{ $category->description }}</p>
			@include('forum.partials.categories', compact('category'))
			<hr />
		@endforeach
	</div>
</div>
@endsection
