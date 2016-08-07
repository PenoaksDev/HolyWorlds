<div id="chat-post-{{ $id }}" class="row msg-post">
	<div class="col-xs-1">
		@include('user.partials.avatar', ['size' => 40, 'user' => $user, 'class' => 'img-circle'])
	</div>
	<div class="col-xs-11">
		<div class="row">
			<div class="col-xs-12" style="white-space: pre-line;">{!! $text !!}</div>
		</div>
		<div class="row">
			<div class="col-xs-12">
				<smaller class="text-gray">{{ $user->name }} &mdash; {{ $date->diffForHumans() }}</smaller>
			</div>
		</div>
	</div>
</div>
