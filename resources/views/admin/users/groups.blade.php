@extends('admin.master')

@section('title', 'User Assigned Groups')

@section('breadcrumbs')
@parent
<li>{{ $user->id }}</li>
<li>Groups</li>
@append

@section('content')
<div class="row">
	<div class="col-md-3">
		@include('user.partials.avatar', ['user' => $user, 'size' => '254'])
		<ul class="list-group">
			<li class="list-group-item">Username: {{ $user->name }}</li>
			<li class="list-group-item">User # {{ $user->id }}</li>
			<li class="list-group-item">E-Mail: {{ $user->email }}</li>
			<li class="list-group-item">Created: {{ $user->created_at }}</li>
			<li class="list-group-item">Updated: {{ $user->updated_at }}</li>
			<li class="list-group-item">Activated: <span class="glyphicon glyphicon-{{ $user->isActivated() ? "ok" : "remove" }}"></span></li>
		</ul>
		<a class="btn btn-default" href="{{ route('admin.users.edit', $user->id) }}"><i class="fa fa-pencil" aria-hidden="true"></i> Edit</a>
		<a class="btn btn-default" href="{{ route('admin.resource.delete', ['users', $user->id]) }}"><i class="fa fa-trash" aria-hidden="true"></i> Delete</a>
	</div>
	<div class="col-md-9">
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
				@foreach ($user->groups() as $group)
				<tr>
					<td>{{ $group->id }}</td>
					<td>{{ $group->name }}</td>
					<td>{{ $group->description }}</td>
					<td class="right-align">
						<a href="{{ route('admin.groups.show', [$group->id]) }}"><i class="fa fa-users" aria-hidden="true"></i></a>
						| <a href="javascript: void();"><i class="fa fa-chain-broken" aria-hidden="true"></i></a> <!-- TODO AJAX unchain group Inheritance -->
					</td>
				</tr>
				@endforeach
			</tbody>
		</table>
	</div>
</div>
@stop
