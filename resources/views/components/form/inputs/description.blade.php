<div class="row mb-3">
    <label for="description"
           class="col-sm-2 col-form-label"
    >
        @lang('title.description')
    </label>
    <div class="col-sm-10">
        <textarea class="form-control"
                  id="description"
                  name="description"
                  rows="10"
                  placeholder="@lang('phrase.markdown-help')"
                  aria-describedby="descriptionHelp"
        >{{ old('description', $value) }}</textarea>
        <small id="descriptionHelp"
               class="form-text text-muted"
        >
            <a href="@lang('phrase.markdown-formatting-help-link-url')"
               target="_blank"
            >
                @lang('phrase.markdown-formatting-help-link')
            </a>
            <br>
            <a href="{{ route('images.index') }}"
               target="_blank"
            >
                @lang('title.upload-images')
            </a>
        </small>
    </div>
</div>
