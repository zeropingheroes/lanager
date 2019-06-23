<div class="form-group">
    <label for="name">@lang('title.name')</label>
    <input type="text" class="form-control" id="name" name="name" placeholder="@lang('title.name')"
           value="{{ old('name', $lan->name) }}">
</div>
<div class="form-group">
    <label for="description">@lang('title.description')</label>
    <textarea class="form-control" id="description" name="description" rows="10" placeholder="@lang('phrase.markdown-help')"
              aria-describedby="descriptionHelp">{{ old('description', $lan->description) }}</textarea>
    <small id="descriptionHelp" class="form-text text-muted">
        <a href="@lang('phrase.markdown-formatting-help-link-url')" target="_blank">@lang('phrase.markdown-formatting-help-link')</a>
        <br>
        <a href="{{ route('images.index') }}" target="_blank">@lang('title.upload-images')</a>
    </small>
</div>
<div class="form-row">
    <script>
        $(function () {
            var format = 'YYYY-MM-DD HH:mm:00';

            var start = $('#start');
            var end = $('#end');
            var startDate = moment(start.val(), format).toDate();
            var endDate = moment(end.val(), format).toDate();

            start.datetimepicker({
                format: format,
                date: moment(),
                sideBySide: true
            });

            start.datetimepicker('date', startDate);

            end.datetimepicker({
                format: format,
                date: moment(),
                sideBySide: true,
                useCurrent: false
            });

            end.datetimepicker('date', endDate);

            $("#start").on("change.datetimepicker", function (e) {
                $('#end').datetimepicker('minDate', e.date);
            });
            $("#end").on("change.datetimepicker", function (e) {
                $('#start').datetimepicker('maxDate', e.date);
            });
        });
    </script>
    <div class="form-group col-md-6">
        <label for="start">@lang('title.start')</label>
        <input type="text" class="form-control datetimepicker-input" id="start" name="start"
               placeholder="YYYY-MM-DD HH:MM:SS" value="{{ old('start', $lan->start) }}"
               data-toggle="datetimepicker" data-target="#start">
    </div>

    <div class="form-group col-md-6">
        <label for="end">@lang('title.end')</label>
        <input type="text" class="form-control datetimepicker-input" id="end" name="end"
               placeholder="YYYY-MM-DD HH:MM:SS" value="{{ old('end', $lan->end) }}"
               data-toggle="datetimepicker" data-target="#end">
    </div>
</div>
<div class="form-group">
    <label for="venue_id">@lang('title.venue')</label>
    @include('components.form.select', ['name' => 'venue_id', 'item' => $lan, 'items' => $venues, 'labelField' => 'name', 'blank' => true])
</div>

<div class="form-group">
    <label for="achievement_id">@lang('title.lan-achievement')</label>
    @include('components.form.select', ['name' => 'achievement_id', 'item' => $lan, 'items' => $achievements, 'labelField' => 'name', 'blank' => true])
    <small id="achievement_id_help" class="form-text">
        @lang('phrase.lan-achievement-help')
    </small>
</div>
<div class="form-group">
    <div class="custom-control custom-checkbox">
        <input type="checkbox" class="custom-control-input" id="published" name="published"
               value="1" {{ old('published', $lan->published) ? 'checked' : null}}>
        <label class="custom-control-label" for="published">@lang('title.published')</label>
    </div>
</div>
<button type="submit" class="btn btn-primary">@lang('title.submit')</button>