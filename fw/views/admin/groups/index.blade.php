@extends('admin.master')

@section('title', 'User Groups')

@section('breadcrumbs')
@parent
<li>Groups</li>
@append

@section('bottom')
	<script type="text/javascript">
		$(document).ready(function(){
			$('#btnCreateGroup').click(function(){
				$.ajax({
					url: '{{ route('admin.groups.create') }}',
					success: function ( result ){
						$('#modalCreateGroupBody').html( result );
						$('#modalCreateGroup').modal('show');
					},
					error: function (){
						// Redirect on error so user can see full error
						window.location = '{{ route('admin.groups.create') }}';
					}
				});
			});
		});
	</script>
@endsection

@section('content')
	<div class="modal fade" id="modalCreateGroup" tabindex="-1" role="dialog" aria-labelledby="modalCreateGroupLabel">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title" id="modalCreateGroupLabel">Create Group</h4>
				</div>
				<div class="modal-body" id="modalCreateGroupBody"></div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
					<button type="button" class="btn btn-primary">Submit</button>
				</div>
			</div>
		</div>
	</div>

	<div class="row">
		<div class="col-md-12">
			<button type="button" class="btn btn-success" id="btnCreateGroup"><i class="fa fa-plus-circle" aria-hidden="true"></i> Create Group</button>
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
							| <a href="{{ route('admin.groups.inheritance', $group->id) }}"><i class="fa fa-users" aria-hidden="true"></i></a>
							| <a href="{{ route('admin.resource.delete', ['groups', $group->id]) }}"><i class="fa fa-trash" aria-hidden="true"></i></a>
						</td>
					</tr>
					@endforeach
				</tbody>
			</table>
		</div>
	</div>
@stop
