<div class="form-group">
    <label for="name">@lang('title.name')</label>
    <input type="text" class="form-control" id="name" name="name" placeholder="@lang('title.name')"
           value="{{ old('name', $event->name) }}">
</div>
<div class="form-group">
    <label for="description">@lang('title.description')</label>
    <textarea class="form-control" id="description" name="description" rows="10" placeholder="@lang('phrase.markdown-help')"
              aria-describedby="descriptionHelp">{{ old('description', $event->description) }}</textarea>
    <small id="descriptionHelp" class="form-text text-muted">
        <a href="@lang('phrase.markdown-formatting-help-link-url')" target="_blank">@lang('phrase.markdown-formatting-help-link')</a>
        <br>
        <a href="{{ route('images.index') }}" target="_blank">@lang('title.upload-images')</a>
    </small>
</div>
<div class="form-row">
    <script>
        $(function () {
            var format = 'YYYY-MM-DD HH:mm:ss';

            var start = $('#start');
            var end = $('#end');
            var startDate = moment(start.val(), format).toDate();
            var endDate = moment(end.val(), format).toDate();
            var lanStart = moment('{{ $lan->start }}');
            var lanEnd = moment('{{ $lan->end }}');

            start.datetimepicker({
                format: format,
                date: lanStart,
                minDate: lanStart,
                maxDate: lanEnd,
                sideBySide: true,
                useCurrent: false
            });

            start.datetimepicker('date', startDate);

            end.datetimepicker({
                format: format,
                date: lanStart.add(1, 'hours'),
                minDate: lanStart,
                maxDate: lanEnd,
                sideBySide: true,
                useCurrent: false
            });

            end.datetimepicker('date', endDate);

            $("#start").on("change.datetimepicker", function (e) {
                $('#end').datetimepicker('minDate', e.date);
            });
        });
    </script>
    <div class="form-group col-md-6">
        <label for="start">@lang('title.start')</label>
        <input type="text" class="form-control datetimepicker-input" id="start" name="start"
               placeholder="YYYY-MM-DD HH:MM:SS" value="{{ old('start', $event->start) }}"
               data-toggle="datetimepicker" data-target="#start">
    </div>

    <div class="form-group col-md-6">
        <label for="end">@lang('title.end')</label>
        <input type="text" class="form-control datetimepicker-input" id="end" name="end"
               placeholder="YYYY-MM-DD HH:MM:SS" value="{{ old('end', $event->end) }}"
               data-toggle="datetimepicker" data-target="#end">
    </div>
</div>
<div class="form-group">
    <label for="event_type_id">@lang('title.event-type')</label>
    @include('components.form.select', ['name' => 'event_type_id', 'item' => $event, 'items' => $eventTypes, 'labelField' => 'name'])
    <small id="descriptionHelp" class="form-text text-muted">
        <a href="{{ route('event-types.index') }}" target="_blank">@lang('title.manage') @lang('title.event-types')</a>
    </small>
</div>
<div class="form-group">
    <div class="custom-control custom-checkbox">
        <input type="checkbox" class="custom-control-input" id="published" name="published"
               value="1" {{ old('published', $event->published) ? 'checked' : null}}>
        <label class="custom-control-label" for="published">@lang('title.published')</label>
    </div>
</div>
<button type="submit" class="btn btn-primary">@lang('title.submit')</button>