<div class="row mb-3">
    @include('components.form.label', ['for' => 'title', 'text' => __('title.title'), 'required' => true])
    <div class="col-sm-10">
        <input type="text"
               class="form-control"
               id="title"
               name="title"
               placeholder="@lang('title.name')"
               value="{{ old('name', $guide->title) }}"
        >
    </div>
</div>

<div class="row mb-3">
    @include('components.form.label', ['for' => 'content', 'text' => __('title.content'), 'required' => true])
    <div class="col-sm-10">
        <textarea class="form-control"
                  id="content"
                  name="content"
                  rows="10"
                  placeholder="@lang('phrase.markdown-help')"
                  aria-describedby="contentHelp"
        >{{ old('content', $guide->content) }}</textarea>
        <small id="contentHelp"
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
@include('components.form.inputs.published', ['value' => $guide->published])
@include('components.form.required-legend')
@include('components.form.inputs.submit')
