<div class="row mb-3">
    <label class="col-sm-2 col-form-label" for="title">@lang('title.title')</label>
    <div class="col-sm-10">
        <input type="text" class="form-control" id="title" name="title" placeholder="@lang('title.title')"
           value="{{ old('title', $navigationLink->title) }}">
    </div>
</div>
<div class="row mb-3">
    <label class="col-sm-2 col-form-label" for="url">@lang('title.url')</label>
    <div class="col-sm-10">
        <input type="text" class="form-control" id="url" name="url" placeholder="@lang('title.url')"
           value="{{ old('url', $navigationLink->url) }}">
    </div>
</div>
<div class="row mb-3">
    <label class="col-sm-2 col-form-label" for="position">@lang('title.position')</label>
    <div class="col-sm-10">
        <input type="text" class="form-control" id="position" name="position" placeholder="@lang('title.position')"
           value="{{ old('position', $navigationLink->position) }}">
    </div>
</div>
<div class="row mb-3">
    <label class="col-sm-2 col-form-label" for="parent_id">@lang('title.parent')</label>
    <div class="col-sm-10">
        @include('components.form.select', ['name' => 'parent_id', 'item' => $navigationLink, 'items' => $navigationLinks, 'labelField' => 'title', 'blank' => true])
    </div>
</div>
@include('components.form.inputs.submit')
