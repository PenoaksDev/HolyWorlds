@foreach ($model->tags as $tag)
    <div class="chip">
        <a href="{{ URL::to("tagged/{$tag->slug}") }}">{{ $tag->name }}</a>
    </div>
@endforeach
