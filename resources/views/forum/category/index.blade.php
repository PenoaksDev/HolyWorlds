@extends ('master', ['category' => null])

@section ('pagetitle', 'Forum Index')

@section ('content')
<div id="index">
    @foreach ($categories as $category)
        <table class="table">
            <thead>
                <th>
                    <a href="{{ route('forum.category.show', $category) }}" class="title">
                        {{ $category->title }}
                    </a>
                    <br>
                    {{ $category->description }}
                </th>
                <th class="center-align hide-on-small-only" style="width:10%;">{{ trans_choice('threads.thread', 2) }}</th>
                <th class="center-align hide-on-small-only" style="width:10%;">{{ trans_choice('posts.post', 2) }}</th>
                <th class="right-align hide-on-small-only" style="width:20%;">{{ trans('threads.newest') }}</th>
                <th class="right-align" style="width:20%;">{{ trans('posts.last') }}</th>
            </thead>
            <tbody class="table-striped">
                @if (!$category->children->isEmpty())
                    @foreach ($category->children as $subcategory)
                        @include ('forum.category.partials.list', ['category' => $subcategory])
                    @endforeach
                @endif
            </tbody>
        </table>
    @endforeach
</div>
@stop
