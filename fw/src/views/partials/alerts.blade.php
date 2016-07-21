@if (Session::has('alerts'))
    @foreach (Session::get('alerts') as $alert)
        @include ('partials.alert', $alert)
    @endforeach
@endif
