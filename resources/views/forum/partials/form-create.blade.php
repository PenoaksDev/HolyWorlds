<form action="{{ route('forum.category.store') }}" method="POST">
    {!! csrf_field() !!}

    <div class="panel panel-default">
        <div class="panel-heading">
            <span class="glyphicon glyphicon-plus"></span>
            <a href="#" data-toggle="collapse" data-target=".collapse.create-category">{{ trans('categories.create') }}</a>
        </div>
        <div class="collapse create-category">
            <div class="panel-body">
                <div class="form-group">
                    <label for="title">{{ trans('general.title') }}</label>
                    <input type="text" name="title" value="{{ old('title') }}" class="form-control">
                </div>
                <div class="form-group">
                    <label for="description">{{ trans('general.description') }}</label>
                    <input type="text" name="description" value="{{ old('description') }}" class="form-control">
                </div>
                <div class="form-group">
                    <label for="category-id">{{ trans_choice('categories.category', 1) }}</label>
                    <select name="category_id" id="category-id" class="form-control">
                        <option value="0">({{ trans('general.none') }})</option>
                        @include ('category.partials.options')
                    </select>
                </div>
                <div class="form-group">
                    <label for="weight">{{ trans('general.weight') }}</label>
                    <input type="number" id="weight" name="weight" value="{{ !empty(old('weight')) ? old('weight') : 0 }}" class="form-control">
                </div>
                <div class="form-group">
                    <label>
                        <input type="hidden" name="enable_threads" value="0">
                        <input type="checkbox" name="enable_threads" value="1" checked>
                        {{ trans('categories.enable_threads') }}
                    </label>
                </div>
                <div class="form-group">
                    <label>
                        <input type="hidden" name="private" value="0">
                        <input type="checkbox" name="private" value="1">
                        {{ trans('categories.make_private') }}
                    </label>
                </div>
            </div>
            <div class="panel-footer clearfix">
                <button type="submit" class="btn-large pull-right">
                    {{ trans('general.create') }}
                </button>
            </div>
        </div>
    </div>
</form>
