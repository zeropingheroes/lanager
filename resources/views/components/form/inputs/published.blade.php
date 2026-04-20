<div class="row mb-3">
    <label for="published"
           class="col-sm-2 col-form-label"
    >
        @lang('title.published')
    </label>
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
