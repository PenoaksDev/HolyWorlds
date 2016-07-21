@extends ('master', ['breadcrumb_other' => trans('threads.new_thread')])

@section('title', 'Create new thread')
@section('subtitle', "Category: {$category->title}")

@section ('content')
    <form method="POST" action="{{ route('forum.thread.store', $category) }}">
        {!! csrf_field() !!}
        {!! method_field('post') !!}

        <div class="row">
            <div class="input-field col s12">
                <input type="text" name="title" value="{{ old('title') }}">
                <label for="title">{{ trans('general.title') }}</label>
            </div>
        </div>

        <textarea name="content">{{ old('content') }}</textarea>

        <button type="submit" class="btn-large pull-right">{{ trans('general.create') }}</button>
        <a href="{{ URL::previous() }}" class="btn-large blue-grey lighten-1">{{ trans('general.cancel') }}</a>
    </form>
@stop
