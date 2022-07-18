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
            var format = 'YYYY-MM-DD HH:mm';

            var start = $('#start');
            var end = $('#end');
            var signups_open = $('#signups_open');
            var signups_close = $('#signups_close');
            var startDate = moment(start.val(), format).toDate();
            var endDate = moment(end.val(), format).toDate();
            var lanStart = moment('{{ $lan->start }}');

            if(signups_open.val()) {
                var signups_open_old = moment(signups_open.val(), format).toDate();
            }

            if(signups_close.val()) {
                var signups_close_old = moment(signups_close.val(), format).toDate();
            }

            start.datetimepicker({
                format: format,
                date: lanStart,
                sideBySide: true,
                useCurrent: false
            });

            start.datetimepicker('date', startDate);

            end.datetimepicker({
                format: format,
                date: lanStart.add(1, 'hours'),
                sideBySide: true,
                useCurrent: false
            });

            end.datetimepicker('date', endDate);

            $("#start").on("change.datetimepicker", function (e) {
                $('#end').datetimepicker('minDate', e.date);
            });

            signups_open.datetimepicker({
                format: format,
                sideBySide: true,
                useCurrent: false
            });

            if(signups_open_old) {
                signups_open.datetimepicker('date', signups_open_old);
            }

            signups_close.datetimepicker({
                format: format,
                sideBySide: true,
                useCurrent: false
            });

            if(signups_close_old) {
                signups_close.datetimepicker('date', signups_close_old);
            }

            $("#signups_open").on("change.datetimepicker", function (e) {
                $('#signups_close').datetimepicker('minDate', e.date);
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

<div class="form-row">
    <div class="form-group col-md-6">
        <label for="signups_open">@lang('title.signups-open')</label>
        <input type="text" class="form-control datetimepicker-input" id="signups_open" name="signups_open"
               placeholder="YYYY-MM-DD HH:MM:SS" value="{{ old('signups_open', $event->signups_open) }}"
               data-toggle="datetimepicker" data-target="#signups_open">
    </div>

    <div class="form-group col-md-6">
        <label for="signups_close">@lang('title.signups-close')</label>
        <input type="text" class="form-control datetimepicker-input" id="signups_close" name="signups_close"
               placeholder="YYYY-MM-DD HH:MM:SS" value="{{ old('signups_close', $event->signups_close) }}"
               data-toggle="datetimepicker" data-target="#signups_close">
    </div>
</div>

<div class="form-group">
    <div class="custom-control custom-checkbox">
        <input type="checkbox" class="custom-control-input" id="published" name="published"
               value="1" {{ old('published', $event->published) ? 'checked' : null}}>
        <label class="custom-control-label" for="published">@lang('title.published')</label>
    </div>
</div>
<button type="submit" class="btn btn-primary">@lang('title.submit')</button>
