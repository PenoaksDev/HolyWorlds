@if (!$category->children->isEmpty())
	<table class="table table-striped">
		<thead>
			<tr>
				<th>Category</th>
				<th style="width:10%;" class="hidden-xs hidden-sm">Threads</th>
				<th style="width:20%;" class="hidden-xs hidden-sm">Newest Thread</th>
				<th style="width:20%;" class="hidden-xs hidden-sm">Last Post</th>
			</tr>
		</thead>
		<tbody>
			@foreach ($category->children as $subcategory)
				@include ('forum.partials.list', ['category' => $subcategory])
			@endforeach
		</tbody>
	</table>
@endif
