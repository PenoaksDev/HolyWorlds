@extends('admin.master')

@section('title', 'Group Inheritance')

@section('breadcrumbs')
	@parent
	<li><a href="{{ route( 'admin.groups.index' ) }}">Groups</a></li>
	<li><a href="{{ route( 'admin.groups.show', $group->id ) }}">{{ $group->id }}</a></li>
	<li>Inheritance</li>
@append

@section('head')
	<link rel="stylesheet" href="{{ URL::asset('css/jqtree.css') }}">
@stop

@section('bottom')
	<script language="javascript" type="text/javascript" src="{{ URL::asset("js/jqtree/tree.jquery.js") }}"></script>
	<script type="text/javascript">
		function showError( msg )
		{
			$("#alert").removeClass('alert-success').addClass('alert-danger').html( msg ).slideDown(500, function(){
				$(this).delay(2500).slideUp(500);
			});
		}

		function showSuccess( msg )
		{
			$("#alert").removeClass('alert-danger').addClass('alert-success').html( msg ).slideDown(500, function(){
				$(this).delay(2500).slideUp(500);
			});
		}

		function markChangesMade(){
			$('#btnSave').removeAttr('disabled');
		}

		function resetSelection()
		{
			$("#node").val( '{{ $group->id }}' );
			$("#tree").tree('setState', {selected_node: []});
			$("#btnRemoveGroup").attr("disabled", "disabled");
		}

		$( document ).ready(function () {
			t = $("#tree");

			t.tree( {
				data: <?php
						$data = array();
						foreach( $group->groups() as $member )
							$data[] = ['name' => $member->name, 'id' => $group->id . "/" . $member->id, 'load_on_demand' => $member->hasGroups()];
						echo json_encode( $data );
					?>,
				dataUrl: '{{ route('admin.groups.ajax') }}',
				dragAndDrop: true,
				onLoading: function(is_loading, node, el){
					if ( is_loading )
						$('#loadingModal').modal('show');
					else
						$('#loadingModal').modal('hide');
				}
			});

			t.bind('tree.move', function(e){
				markChangesMade();
			});

			t.bind('tree.select', function(e){
				if ( e.node )
				{
					t.tree( 'openNode', e.node );
					$("#node").val( e.node.id );
					$("#btnRemoveGroup").removeAttr("disabled");
				}
				else
				{
					$("#node").val( '{{ $group->id }}' );
					$("#btnRemoveGroup").attr("disabled", "disabled");
				}
			});

			t.on(
				'click', '.unlink',
				function(e) {
					var node_id = $(e.target).data('node-id');
					var node = $tree.tree('getNodeById', node_id);
					if (node)
					alert(node.name);
				}
			);

			$('#selectRootNode').click(function(){
				$( '#node' ).val( '{{ $group->id }}' );
				t.tree( 'setState' , {selected_node: []});
			});

			$('#btnSave').click(function(){
				if ( this.hasAttribute('disabled') )
					return;

				resetSelection();
				$('#loadingModal').modal('show');
				$.ajax({
					url: '{{ route("admin.groups.ajax") }}',
					data: {
						save: t.tree('toJson')
					},
					cache: false,
					success: function(){
						$('#loadingModal').modal('hide');
						$('#btnSave').attr('disabled', 'disabled');
						showSuccess( "Successfully saved group inheritances." );
					},
					error: function (){
						$('#loadingModal').modal('hide');
						showError( "Failed to save changes.<br />See console for exact error message." );
					}
				});
			});

			$('#btnRemoveGroup').click(function(){
				if ( this.hasAttribute('disabled') )
					return;

				var nodes = $('#tree').tree('getSelectedNodes');

				if ( nodes.length == 0 )
				{
					showError( "You must select a group." );
					return;
				}

				$('#modalConfirm').modal('show');
			});

			$('#btnRemoveGroupConfirm').click(function(){
				var nodes = $('#tree').tree('getSelectedNodes');
				$('#modalConfirm').modal('hide');

				if ( nodes.length == 0 )
				{
					showError( "You must select a group." );
					return;
				}

				t.tree('removeNode', nodes[0]);
				markChangesMade();
				resetSelection();
			});

			$("#btnAddGroup").click(function(){
				if ( this.hasAttribute('disabled') )
					return;

				var name = $("#newgroup option:selected").text();
				name = name.substring( name.indexOf( "|" ) + 1, name.length );
				var group = $("#newgroup").val();
				var nodePath = $('#node').val();
				var node = $("#tree").tree("getSelectedNodes");
				node = node.length == 0 ? t.tree('getTree') : node[0];

				$("#alert").slideUp(100); // Dismiss Errors



				if ( nodePath.includes(group) )
				{
					showError("Linking this group will create an illegal recursive leak.");
					return;
				}

				for (var i=0; i < node.children.length; i++) {
					var child = node.children[i];

					if ( child.id == group || child.id == node.id + "/" + group )
					{
						showError( "This group is already linked with the selected parent." );
						return;
					}
				}

				$("#tree").tree("appendNode", {
					name: name,
					id: nodePath + "/" + group
				}, node);

				$("#tree").tree("openNode", node);

				markChangesMade();
			});

			$("#loadingModal").modal('hide');
		});
	</script>
@endsection

@section('content')
	@if ( $group == null )
		<p class="alert alert-error">That group does not exist!</p>
	@else
		<div class="modal fade" id="loadingModal" tabindex="-1" role="dialog" aria-labelledby="loadingModalLabel" data-keyboard="false" data-backdrop="static" data-show="true">
			<div class="modal-dialog modal-sm">
				<div class="modal-content">
					<div class="modal-header">
						<h4 class="modal-title" id="loadingModalLabel">Contacting Server</h4>
					</div>
					<div class="modal-body">
						<center>
							<img src="{{ URL::asset('images/loading_coffee.gif') }}" /><br />
							<p id="loadingModalMessage">Please wait as we save/load the tree.</p>
						</center>
					</div>
					<div class="modal-footer">
					</div>
				</div>
			</div>
		</div>

		<div class="modal fade" tabindex="-1" role="dialog" id="modalConfirm" aria-labelledby="modalConfirmLabel">
			<div class="modal-dialog" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						<h4 class="modal-title" id="modalConfirmLabel">Confirm</h4>
					</div>
					<div class="modal-body">
						<p>Are you sure you wish to remove this inheritance?</p>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-default" data-dismiss="modal">Sorry, my mistake</button>
						<button type="button" class="btn btn-primary" id="btnRemoveGroupConfirm">Indeed!</button>
					</div>
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-md-8">
				<h5>Showing inheritances for group '<a id="selectRootNode" href="javascript: void(0);">{{ $group->name }}</a>'.</h5>
				<div id="tree"></div>
				<hr />
			</div>
			<div class="col-md-4">
				<form>
					<div class="form-group">
						<label for="newgroup" class="control-label">Select Group</label>
						<select class="form-control" id="newgroup">
							<?
							foreach ( App\Models\Group::get() as $g )
							echo "<option value=\"" . $g->id . "\">" . $g->id . " | " . $g->name . "</option>";
							?>
						</select>
					</div>

					<div class="form-group">
						<label for="node" class="control-label">Node Path</label>
						<input type="text" class="form-control" id="node" disabled="disabled" placeholder="Node Path" value="{{ $group->id }}">
					</div>

					<div class="form-group">
						<a class="btn btn-primary" href="javascript: void(0);" id="btnAddGroup"><i class="fa fa-chain" aria-hidden="true"></i> Link Group</a>
						<a class="btn btn-danger" href="javascript: void(0);" id="btnRemoveGroup" disabled="disabled"><i class="fa fa-chain-broken" aria-hidden="true"></i> Unlink Group</a>
						<a class="btn btn-success" href="javascript: void(0);" id="btnSave" disabled="disabled"><i class="fa fa-floppy-o" aria-hidden="true"></i> Save</a>
					</div>

					<p id="alert" class="alert alert-danger" style="display: none;"></p>
				</form>
			</div>
		</div>
	@endif
@stop
