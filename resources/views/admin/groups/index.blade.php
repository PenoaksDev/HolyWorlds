@extends('admin.master')

@section('title', 'Administer Groups')

@section('breadcrumbs')
@parent
<li>Groups</li>
@append

@section('content')
<div class="row">
	<div class="col-md-12">
		<a class="btn btn-success"> Create Group</a>
		<table class="table">
			<thead>
				<tr>
					<th>Group #</th>
					<th>Name</th>
					<th>Description</th>
					<th></th>
				</tr>
			</thead>
			<tbody>
				@foreach ($groups as $group)
				<tr>
					<td>{{ $group->id }}</td>
					<td>{{ $group->name }}</td>
					<td>{{ $group->description }}</td>
					<td class="right-align"><?php
						$groups = 0;
						$users = 0;
						foreach ( $group->children as $child )
							if ( $child["type"] == 0 )
								$groups++;
							else
								$users++;
						?>
						<span class="badge">{{ $users }} <i class="fa fa-user" aria-hidden="true"></i></span>
						<span class="badge">{{ $groups }} <i class="fa fa-users" aria-hidden="true"></i></span>

						| <a href="{{ route('admin.groups.edit', $group->id) }}"><i class="fa fa-pencil" aria-hidden="true"></i></a>
						| <a href="{{ route('admin.groups.show', [$group->id]) }}"><i class="fa fa-users" aria-hidden="true"></i></a>
						| <a href="{{ route('admin.resource.delete', ['groups', $group->id]) }}"><i class="fa fa-trash" aria-hidden="true"></i></a>
					</td>
				</tr>
				@endforeach
			</tbody>
		</table>
	</div>
</div>
@stop
