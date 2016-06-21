@extends('messages.master', ['id' => $channel->id])

@section('chat')
<div class="well">
	<div id="channel-public" class="msg-window">
		@foreach( \App\Models\MsgPost::get() as $post )
			@include('messages.post', ['id' => $post->id, 'user' => \App\Models\User::find( $post->user ), 'date' => $post->created_at, 'text' => $post->text])
		@endforeach
	</div>
	<form id="compose" class="form-horizontal">
		<div class="form-group" style="margin: 0;">
			<textarea class="form-control" id="message" name="message" placeholder="Send a message"></textarea>
		</div>
		<div class="form-group" style="margin: 0;">
			<small class="text-gray">Press <kbd><kbd>Enter</kbd></kbd> to send or <kbd><kbd>Shift</kbd> + <kbd>Enter</kbd></kbd> for a line return.</small>
			<button class="btn btn-xs btn-primary pull-right" type="submit">Send</button>
		</div>
	</form>
</div>
@endsection

@section('after_content')
	<script type="text/javascript">
		$(document).ready(function(){
			var channel = '{{ $channel->id }}';

			$(document).on( "push:received", function(e) {
				chatin(e.msg);
			});

			$(document).on( "push:connected", function() {
				// chatin("<i>Connected to Holy Worlds Push Service</i>");
			});

			function chatin(msg)
			{
				$("#channel-public").html( $("#channel-public").html() + msg );
				$("#channel-public").animate({ scrollTop: $("#channel-public").prop('scrollHeight') }, 1000);
			}

			$('#message').keydown(function (event){
				if ( event.keyCode == 13 && !event.shiftKey )
				{
					event.preventDefault();
					$('#compose').submit();
				}
			});

			$("#compose").submit(function(event){
				event.preventDefault();
				var msg = $("#message").val();
				pushMessage("chatPublic", msg);
				$("#message").val("");
			});
		});
	</script>
@endsection
