@if (!is_null($category->parent))
    @include ('partials.breadcrumb-categories', ['category' => $category->parent])
@endif
<a href="{{ Forum::route('category.show', $category) }}" class="breadcrumb">{{ $category->title }}</a>
