@extends('admin.master')

@section('title', 'Group Inheritance')

@section('breadcrumbs')
@parent
<li><a href="{{ route( 'admin.groups.index' ) }}">Groups</a></li>
<li>{{$group->id}}</li>
@append

@section('head')
	<link rel="stylesheet" href="{{ URL::asset('css/jqtree.css') }}">
@stop

@section('bottom')
	<script language="javascript" type="text/javascript" src="{{ URL::asset("js/jqtree/tree.jquery.js") }}"></script>
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
					<p id="loadingModalMessage">Please wait as we save changes.</p>
				</center>
			</div>
			<div class="modal-footer">
			</div>
		</div>
	</div>
</div>

<div class="row">
	<div class="col-md-8">
			<h5>Showing inheritances for group '<a id="selectRootNode" href="javascript: void(0);">{{ $group->name }}</a>'.</h5>

			<div id="tree" data-url="{{ route('admin.groups.ajax') }}"></div>

			<script type="text/javascript">
				$(document).ready(function(){
					$('#selectRootNode').click(function(){
						$("#node").val( '{{ $group->id }}' );
					});

					$('#btnSave').click(function(){
						$('#loadingModal').modal('show');

						console.log( $('#tree').tree('toJson') );

						$.ajax({
							url: '{{ route("admin.groups.ajax") }}',
							data: {
								save: $('#tree').tree('toJson')
							},
							cache: false,
							success: function(){
								$('#loadingModal').modal('hide');
							},
							error: function (){
								$('#loadingModal').modal('hide');
								showError( "Failed to save changes.<br />See console for exact error message." );
							}
						});
					});

					var t = $("#tree");

					t.tree( {
						@foreach ( $group->groups() as $member )
						data: [{name: '{{ $member->name }}', id: '{{ $group->id . "/" . $member->id }}', load_on_demand: {{ $member->hasGroups() }}}],
						@endforeach
						dragAndDrop: true,
						onCreateLi: function(node, $li) {
							// $li.find('.jqtree-element').append(' <a href="#node-'+ node.id +'" class="unlink" data-node-id="' + node.id + '"><i class="fa fa-chain-broken" aria-hidden="true"></i></a>' );
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
							$("#removeGroup").removeAttr("disabled");
						}
						else
						{
							$("#node").val( {{ $group->id }} );
							$("#removeGroup").attr("disabled", "disabled");
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
				});
			</script>
	</div>
	<div class="col-md-4">
		<form>
			<div class="form-group">
				<label for="newgroup" class="control-label">Select Group</label>
				<select class="form-control" id="newgroup">
					<option value="" disabled="disabled">Select Group</option>
					<?
						foreach ( App\Models\Group::get() as $group )
							echo "<option value=\"" . $group->id . "\">" . $group->id . " | " . $group->name . "</option>";
					?>
				</select>
			</div>

			<div class="form-group">
				<label for="node" class="control-label">Node Path</label>
				<input type="text" class="form-control" id="node" disabled="disabled" placeholder="Node Path">
			</div>

			<div class="form-group">
				 	<a class="btn btn-primary" href="javascript: void(0);" id="addGroup" disabled="disabled"><i class="fa fa-chain" aria-hidden="true"></i> Link Group</a>
					<a class="btn btn-danger" href="{{ route("admin.groups.ajax", ["node" => "", "remove" => ""]) }}" id="removeGroup" disabled="disabled"><i class="fa fa-chain-broken" aria-hidden="true"></i> Unlink Group</a>
					<a class="btn btn-success" href="javascript: void(0);" id="btnSave" disabled="disabled"><i class="fa fa-floppy-o" aria-hidden="true"></i>
 Save</a>
			</div>

			<p id="alert" class="alert alert-danger" style="display: none;"></p>
		</form>
		<script type="text/javascript">
			function showError( msg )
			{
				$("#alert").html( msg ).slideDown(500, function(){
					$(this).delay(2500).slideUp(500);
				});
			}

			function markChangesMade(){
				$('#btnSave').removeAttr('disabled');
			}

			$(document).ready(function () {
				$("#addGroup").click(function(){
					var name = $("#newgroup option:selected").text();
					name = name.substring( name.indexOf( "|" ) + 1, name.length );
					var group = $("#newgroup").val();
					var node = $("#tree").tree("getSelectedNodes");

					if ( node.length == 0 )
					{
						showError("You must select a group.");
						return;
					}

					node = node[0];

					if ( node.id.includes(group) )
					{
						showError("Linking this group will create an illegal recursive leak.");
						return;
					}

					$("#alert").slideUp(100); // Dismiss Errors

					$("#tree").tree("openNode", node);

					for (var i=0; i < node.children.length; i++) {
						var child = node.children[i];

						if ( child.id == node.id + "/" + group )
						{
							showError( "This group is already linked with the selected parent." );
							return;
						}
					}

					$("#tree").tree("appendNode", {
						name: name,
						id: group
					}, node);

					markChangesMade();
				});

				$("#loadingModal").modal('hide');
			});
		</script>
	</div>
</div>
@endif
@stop
