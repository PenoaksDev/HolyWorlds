@extends('admin.master')

@section('title', 'Users')

@section('breadcrumbs')
@parent
<span class="breadcrumb">Users</span>
@append

@section('content')
<div class="right-align">
	<a href="{{ route('admin.users.create') }}" class="waves-effect waves-light btn-large">
		Create user
	</a>
</div>
<table class="bordered">
	<thead>
		<tr>
			<th>User #</th>
			<th>Username</th>
			<th>E-mail</th>
			<th>Created</th>
			<th>Updated</th>
			<th></th>
		</tr>
	</thead>
	<tbody>
		@foreach ($users as $user)
		<?php
		if ( empty( $user->userId ) )
		{
			$user->userId = strtolower( substr($user->name, 0, 2) . $user->id . substr( $user->name, -1 ) );
			$user->save();
		}
		?>
		<tr>
			<td>{{ $user->userId }}</td>
			<td>{{ $user->name }}</td>
			<td>{{ $user->email }}</td>
			<td>{{ $user->created_at }}</td>
			<td>{{ $user->updated_at }}</td>
			<td class="right-align">
				<a href="{{ route('admin.users.edit', $user->userId) }}">Edit</a>
				| <a href="{{ route('admin.groups.list', [$user->userId]) }}">Groups</a>
				| <a href="{{ route('admin.resource.delete', ['users', $user->userId]) }}">Delete</a>
			</td>
		</tr>
		@endforeach
	</tbody>
</table>
@stop
