@extends('messages.master')

@section('chat')
<div id="console"></div>
<input type="text" name="message" id="message" />
<button class="btn" type="submit">Send</button>
@endsection

@section('after_content')
	<script type="text/javascript">
		$("#compose").submit(function(event){
			event.preventDefault();
			var msg = $("#message").val();
			pushMessage("chatPublic", msg);
			$("#message").val("");
		});

		$(document).on( "push:received", function(e) {
			chatin(e.msg);
		});

		$(document).on( "push:connected", function() {
			chatin("<i>Connected to Holy Worlds Push Service</i>");
		});

		var d = new Date();

		function chatin(msg)
		{
			$("#console").html( $("#console").html() + "<br />" + d.getTime() + ": " + msg );
		}
	</script>
@endsection
