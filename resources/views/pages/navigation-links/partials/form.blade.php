<div class="row mb-3">
    @include('components.form.label', ['for' => 'title', 'text' => __('title.title'), 'required' => true])
    <div class="col-sm-10">
        <input type="text" class="form-control" id="title" name="title" placeholder="@lang('title.title')"
           value="{{ old('title', $navigationLink->title) }}">
    </div>
</div>
<div class="row mb-3">
    @include('components.form.label', ['for' => 'url', 'text' => __('title.url')])
    <div class="col-sm-10">
        <input type="text" class="form-control" id="url" name="url" placeholder="@lang('title.url')"
           value="{{ old('url', $navigationLink->url) }}">
    </div>
</div>
<div class="row mb-3">
    @include('components.form.label', ['for' => 'position', 'text' => __('title.position'), 'required' => true])
    <div class="col-sm-10">
        <input type="text" class="form-control" id="position" name="position" placeholder="@lang('title.position')"
           value="{{ old('position', $navigationLink->position) }}">
    </div>
</div>
<div class="row mb-3">
    @include('components.form.label', ['for' => 'parent_id', 'text' => __('title.parent')])
    <div class="col-sm-10">
        @include('components.form.select', ['name' => 'parent_id', 'item' => $navigationLink, 'items' => $navigationLinks, 'labelField' => 'title', 'blank' => true])
    </div>
</div>
@include('components.form.required-legend')
@include('components.form.inputs.submit')
