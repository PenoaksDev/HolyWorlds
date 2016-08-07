@extends ('master', ['breadcrumb_other' => trans('general.new_reply')])

@section('title', 'Replying to thread')
@section('subtitle', $thread->title)

@section ('content')
    @if (!is_null($post) && !$post->trashed())
        <h3>{{ trans('general.replying_to', ['item' => $post->authorName]) }}...</h3>

        @include ('post.partials.excerpt')
    @endif

    <form method="POST" action="{{ route('forum.post.store', $thread) }}">
        {!! csrf_field() !!}
        @if (!is_null($post))
            <input type="hidden" name="post" value="{{ $post->id }}">
        @endif

        <div class="form-group">
            <textarea name="content" class="form-control">{{ old('content') }}</textarea>
        </div>

        <button type="submit" class="waves-effect waves-light btn-large pull-right">
            {{ trans('general.reply') }}
        </button>
        <a href="{{ URL::previous() }}" class="waves-effect waves-light btn-large blue-grey lighten-1">
            {{ trans('general.cancel') }}
        </a>
    </form>
@stop
