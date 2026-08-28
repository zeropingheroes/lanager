{{csrf_field()}}

@include('components.form.inputs.name', ['value' => $achievement->name, 'required' => true])
@include('components.form.inputs.description', ['value' => $achievement->description])
<div class="row mb-3">
    @include('components.form.label', ['for' => 'image', 'text' => __('title.achievement-image')])
    <div class="col-sm-10">
        <input type="file" class="form-control" id="image" name="image">
        <small>@lang('phrase.achievement-image-help')</small>
    </div>
</div>
@include('components.form.required-legend')
@include('components.form.inputs.submit')
