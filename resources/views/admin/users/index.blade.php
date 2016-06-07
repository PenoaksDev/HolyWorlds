@extends('admin.master')

@section('title', 'Users')

@section('breadcrumbs')
@parent
<li>Users</li>
@append

@section('content')
<div class="right-align">
	<a href="{{ route('admin.users.create') }}" class="btn btn-success">Create User</a>
</div>
<table class="table">
	<thead>
		<tr>
			<th></th>
			<th>User #</th>
			<th>Username</th>
			<th>E-mail</th>
			<th>Created</th>
			<th>Updated</th>
			<th>Act</th>
			<th></th>
		</tr>
	</thead>
	<tbody>
		@foreach ($users as $user)
		<tr>
			<td>@include('user.partials.avatar', ['user' => $user])</td>
			<td>{{ $user->id }}</td>
			<td>{{ $user->name }}</td>
			<td>{{ $user->email }}</td>
			<td>{{ $user->created_at }}</td>
			<td>{{ $user->updated_at }}</td>
			<td><span class="glyphicon glyphicon-{{ $user->isActivated() ? "ok" : "remove" }}"></span></td>
			<td class="right-align">
				<a href="{{ route('admin.users.edit', $user->id) }}"><i class="fa fa-pencil" aria-hidden="true"></i></a>
				| <a href="{{ route('admin.users.groups', [$user->id]) }}"><i class="fa fa-users" aria-hidden="true"></i></a>
				| <a href="{{ route('admin.resource.delete', ['users', $user->id]) }}"><i class="fa fa-trash" aria-hidden="true"></i></a>
			</td>
		</tr>
		@endforeach
	</tbody>
</table>
@stop
