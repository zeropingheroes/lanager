@vite('resources/js/pages/slide-form.js')
@include('components.form.inputs.name', ['value' => $slide->name])


<div class="row mb-3">
    <label for="content"
           class="col-sm-2 col-form-label"
    >
        @lang('title.content')
    </label>
    <div class="col-sm-10">
        <textarea class="form-control"
                  id="content"
                  name="content"
                  rows="10"
                  placeholder="@lang('phrase.slides-content-placeholder')"
                  aria-describedby="contentHelp"
        >{{ old('content', $slide->content) }}</textarea>
        <small id="contentHelp"
               class="form-text text-muted"
        >
            @lang('phrase.slides-content-help')
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

<div class="row mb-3">
    <label for="position"
           class="col-sm-2 col-form-label"
    >
        @lang('title.position')
    </label>
    <div class="col-sm-10">
        <input type="text"
               class="form-control"
               id="position"
               name="position"
               placeholder="@lang('title.position')"
               value="{{ old('position', $slide->position) }}"
        >
    </div>
</div>
<div class="row mb-3">
    <label for="duration"
           class="col-sm-2 col-form-label"
    >
        @lang('title.duration')
    </label>
    <div class="col-sm-10">
        <input type="text"
               class="form-control"
               id="duration"
               name="duration"
               placeholder="@lang('title.duration')"
               value="{{ old('duration', $slide->duration) }}"
        >
    </div>
</div>
<div class="row mb-3">
    <label for="start"
           class="col-sm-2 col-form-label"
    >
        @lang('title.start')
    </label>
    <div class="col-sm-4">
        <input type="text"
               class="form-control
               datetimepicker-input"
               id="start"
               name="start"
               placeholder="YYYY-MM-DD HH:MM:SS"
               value="{{ old('start', $slide->start) }}"
               data-toggle="datetimepicker"
               data-target="#start"
        >
    </div>
    <label for="end"
           class="col-sm-2 col-form-label"
    >
        @lang('title.end')
    </label>
    <div class="col-sm-4">
        <input type="text"
               class="form-control
               datetimepicker-input"
               id="end"
               name="end"
               placeholder="YYYY-MM-DD HH:MM"
               value="{{ old('end', $slide->end) }}"
               data-toggle="datetimepicker"
               data-target="#end"
        >
    </div>
</div>
<div class="row mb-3">
    <div class="col-sm-10 offset-sm-2">
        <small id="contentHelp"
               class="form-text text-muted"
        >
            @lang('phrase.slides-start-end-help')
        </small>
    </div>
</div>

@include('components.form.inputs.published', ['value' => $slide->published])
@include('components.form.inputs.submit')
