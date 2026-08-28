@php $required = $required ?? false; @endphp
<div class="row mb-3">
    @include('components.form.label', ['for' => 'published', 'text' => __('title.published'), 'required' => $required])
    <div class="col-sm-10">
        <div class="form-check">
            <input type="checkbox"
                   class="form-check-input"
                   id="published"
                   name="published"
                   value="1"
                {{ old('published', $value) ? 'checked' : null}}
            >
        </div>
    </div>
</div>
