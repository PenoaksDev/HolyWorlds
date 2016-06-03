@extends ('master', ['breadcrumb_other' => trans('posts.view')])

@section ('content')
    <div id="post">
        <h2>{{ trans('posts.view') }} ({{ $thread->title }})</h2>

        <a href="{{ Forum::route('thread.show', $thread) }}" class="btn btn-default">&laquo; {{ trans('threads.view') }}</a>

        <table class="bordered">
            <thead>
                <tr>
                    <th class="col-md-2">
                        {{ trans('general.author') }}
                    </th>
                    <th>
                        {{ trans_choice('posts.post', 1) }}
                    </th>
                </tr>
            </thead>
            <tbody>
                @include ('post.partials.list', compact('post'))
            </tbody>
        </table>
    </div>
@stop
