@if (!$category->children->isEmpty())
	<table class="table table-striped">
		<thead>
			<tr>
				<th>Category</th>
				<th style="width:10%;">Threads</th>
				<th style="width:20%;">Newest Thread</th>
				<th style="width:20%;">Last Post</th>
			</tr>
		</thead>
		<tbody>
			@foreach ($category->children as $subcategory)
				@include ('forum.partials.list', ['category' => $subcategory])
			@endforeach
		</tbody>
	</table>
@endif
