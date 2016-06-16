@extends('wrapper')

@section('title', "Messages")
@section('subtitle')

@stop

@section('breadcrumbs')
	<span class="breadcrumb">Messages</span>
@stop

@section('content')
	<div class="row">
		<div class="col s10 m10 l10 offset-l1">
			<div id="console"></div>
		</div>
	</div>
	<div class="row">
		<form id="compose">
			<div class="col s12 m12 l8">
				<input type="text" name="message" id="message" />
			</div>
			<div class="col s12 m12 l4">
				<button class="btn" type="submit">Send</button>
			</div>
		</form>
	</div>
@stop

@section('after_content')
	<script type="text/javascript">
		$("#compose").submit(function(event){
			event.preventDefault();
			var msg = $("#message").val();
			pushMessage("chatPublic", msg);
			$("#message").val("");
		});

		$(document).on( "push:received", function(e) {
			console(e.msg);
		});

		$(document).on( "push:connected", function() {
			console("<i>Connected to Holy Worlds Push Service</i>");
		});

		var d = new Date();

		function console(msg)
		{
			$("#console").html( $("#console").html() + "<br />" + d.getTime() + ": " + msg );
		}
	</script>
@stop
