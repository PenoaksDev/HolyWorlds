@extends('admin.master')

@section('title', 'User Assigned Groups')

@section('breadcrumbs')
@parent
<span class="breadcrumb">Users</span>
<span class="breadcrumb">Groups</span>
@append

@section('content')
<div class="right-align">
	<a href="{{ route('admin.users.create') }}" class="waves-effect waves-light btn-large">
		Add Group
	</a>
</div>
<table class="bordered">
	<thead>
		<tr>
			<th>Group #</th>
			<th>Group Name</th>
			<th></th>
		</tr>
	</thead>
	<tbody>
		{{ var_dump( $user ) }}
		foreach ($user->groups() as $group)
		<tr>
			<td> $group->id }}</td>
			<td> $group->displayName }}</td>
			<td class="right-align">
				<a href=" route('admin.users.edit', $user->id) }}">Edit</a>
				| <a href=" route('admin.groups.list', [$user->id]) }}">Groups</a>
				| <a href=" route('admin.resource.delete', ['users', $user->id]) }}">Delete</a>
			</td>
		</tr>
		endforeach
	</tbody>
</table>
@stop
