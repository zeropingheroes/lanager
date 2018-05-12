<div class="form-group">
    <label for="title">@lang('title.title')</label>
    <input type="text" class="form-control" id="title" name="title" placeholder="@lang('title.title')"
           value="{{ old('title', $navigationLink->title) }}">
</div>
<div class="form-group">
    <label for="title">@lang('title.url')</label>
    <input type="text" class="form-control" id="url" name="url" placeholder="@lang('title.url')"
           value="{{ old('url', $navigationLink->url) }}">
</div>
<div class="form-group">
    <label for="title">@lang('title.position')</label>
    <input type="text" class="form-control" id="position" name="position" placeholder="@lang('title.position')"
           value="{{ old('position', $navigationLink->position) }}">
</div>
<div class="form-group">
    <label for="parent_id">@lang('title.parent')</label>
    @include('components.form.select', ['name' => 'parent_id', 'item' => $navigationLink, 'items' => $navigationLinks, 'labelField' => 'title', 'blank' => true])
</div>

<button type="submit" class="btn btn-primary">@lang('title.submit')</button>