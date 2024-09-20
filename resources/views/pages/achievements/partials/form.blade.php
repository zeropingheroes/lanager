{{csrf_field()}}

@include('components.form.inputs.name', ['value' => $achievement->name])
@include('components.form.inputs.description', ['value' => $achievement->description])
<div class="row mb-3">
    <label for="image"
           class="col-sm-2 col-form-label">
        @lang('title.achievement-image')
    </label>
    <div class="col-sm-10">
        <input type="file" class="form-control" id="image" name="image">
        <small>@lang('phrase.achievement-image-help')</small>
    </div>
</div>
@include('components.form.inputs.submit')
