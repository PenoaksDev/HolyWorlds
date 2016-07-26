@extends('app')

@section('title', 'Chat Room')

@section('content')

<div id="chat-room">
	@include('messages.chat')
</div>

@stop