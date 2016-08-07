@if (Auth::check())
	<div id="chat_header">
		<span id="chat_header_title">Chat</span>
		<span id="chat_header_icon" class="fa fa-comments fa-3x"></span>
	</div>
	
	<div id="chat_box">
		<div id="chat_box_content"></div>
		<input type="text" id="chat_input" placeholder="chat" />
	</div>
@endif