<script>
    $(function () {
        var start = $('#start');
        var end = $('#end');

        $("#start").click(function(){
            start.datetimepicker({
                format: 'YYYY-MM-DD HH:mm:00',
                sideBySide: true,
                useCurrent: false
            });
        });
        $("#end").click(function(){
            end.datetimepicker({
                format: 'YYYY-MM-DD HH:mm:00',
                sideBySide: true,
                useCurrent: false
            });
        });
    });
</script>
<div class="form-group">
    <label for="name">@lang('title.name')</label>
    <input type="text" class="form-control" id="name" name="name" placeholder="@lang('title.name')"
           value="{{ old('name', $slide->name) }}">
</div>
<div class="form-group">
    <label for="content">@lang('title.content')</label>
    <textarea class="form-control" id="content" name="content" rows="10" placeholder="@lang('phrase.slides-content-placeholder')"
              aria-describedby="contentHelp">{{ old('content', $slide->content) }}</textarea>
    <small id="contentHelp" class="form-text text-muted">
        @lang('phrase.slides-content-help')
        <br>
        <a href="@lang('phrase.markdown-formatting-help-link-url')" target="_blank">@lang('phrase.markdown-formatting-help-link')</a>
        <br>
        <a href="{{ route('images.index') }}" target="_blank">@lang('title.upload-images')</a>
    </small>
</div>
<div class="form-group">
    <label for="position">@lang('title.position')</label>
    <input type="text" class="form-control" id="position" name="position" placeholder="@lang('title.position')"
           value="{{ old('position', $slide->position) }}">
</div>
<div class="form-group">
    <label for="duration">@lang('title.duration')</label>
    <input type="text" class="form-control" id="duration" name="duration" placeholder="@lang('title.duration')"
           value="{{ old('duration', $slide->duration) }}">
</div>
<div class="form-row">
    <div class="form-group col-md-6">
        <label for="start">@lang('title.start')</label>
        <input type="text" class="form-control datetimepicker-input" id="start" name="start"
               placeholder="YYYY-MM-DD HH:MM:SS" value="{{ old('start', $slide->start) }}"
               data-toggle="datetimepicker" data-target="#start" autocomplete="off">
        <span class='help-block'>Optionally set when the slide should be displayed</span>
    </div>

    <div class="form-group col-md-6">
        <label for="end">@lang('title.end')</label>
        <input type="text" class="form-control datetimepicker-input" id="end" name="end"
               placeholder="YYYY-MM-DD HH:MM:SS" value="{{ old('end', $slide->end) }}"
               data-toggle="datetimepicker" data-target="#end" autocomplete="off">
    </div>
</div>

<div class="form-group">
    <div class="custom-control custom-checkbox">
        <input type="checkbox" class="custom-control-input" id="published" name="published"
               value="1" {{ old('published', $slide->published) ? 'checked' : null}}>
        <label class="custom-control-label" for="published">@lang('title.published')</label>
    </div>
</div>
<button type="submit" class="btn btn-primary">@lang('title.submit')</button>