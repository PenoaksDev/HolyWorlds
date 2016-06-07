@extends ('master', ['breadcrumb_other' => trans('threads.new_updated')])

@section('subtitle', 'New and updated threads')

@section ('content')
    @if (!$threads->isEmpty())
        <table class="bordered">
            <thead>
                <tr>
                    <th>{{ trans('general.subject') }}</th>
                    <th style="width:5%;" class="center-align">{{ trans('general.replies') }}</th>
                    <th style="width:15%;" class="right-align">{{ trans('posts.last') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($threads as $thread)
                    <tr>
                        <td>
                            <span class="pull-right">
                                @if ($thread->locked)
                                    <span class="label label-danger">{{ trans('threads.locked') }}</span>
                                @endif
                                @if ($thread->pinned)
                                    <span class="label label-info">{{ trans('threads.pinned') }}</span>
                                @endif
                                @if ($thread->userReadStatus)
                                    <span class="label label-primary">{{ trans($thread->userReadStatus) }}</span>
                                @endif
                            </span>
                            <a href="{{ route('forum.thread.show', $thread) }}">{{ $thread->title }}</a>
                            <p>
                                {{ $thread->authorName }}
                                <span class="text-muted">(<em><a href="{{ route('forum.category.show', $thread->category) }}">{{ $thread->category->title }}</a></em>, {{ $thread->posted }})</span>
                            </p>
                        </td>
                        <td class="center-align">
                            {{ $thread->replyCount }}
                        </td>
                        <td class="right-align">
                            {{ $thread->lastPost->authorName }}
                            <p class="text-muted">({{ $thread->lastPost->posted }})</p>
                            <a href="{{ route('forum.thread.show', $thread->lastPost) }}">{{ trans('posts.view') }} &raquo;</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        @can ('markNewThreadsAsRead')
            <div class="text-center">
                <form action="{{ route('forum.mark-new') }}" method="POST" data-confirm>
                    {!! csrf_field() !!}
                    {!! method_field('patch') !!}
                    <button class="waves-effect waves-light btn-large btn-small">{{ trans('general.mark_read') }}</button>
                </form>
            </div>
        @endcan
    @else
        <p class="text-center">
            {{ trans('threads.none_found') }}
        </p>
    @endif
@stop
