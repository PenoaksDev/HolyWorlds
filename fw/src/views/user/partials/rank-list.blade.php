@foreach ($user->groups() as $group)
	<strong class="{!! $group->class !!}">{{ $group->name }}</strong><br>
@endforeach
