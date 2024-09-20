<div class="row mb-3">
    <label for="name"
           class="col-sm-2 col-form-label"
    >
        @lang('title.name')
    </label>
    <div class="col-sm-10">
        <input type="text"
               class="form-control"
               id="name"
               name="name"
               placeholder="@lang('title.name')"
               value="{{ old('name', $event->name) }}"
        >
    </div>
</div>
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
        >{{ old('description', $event->description) }}</textarea>
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
<div class="row mb-3">
    <label for="start"
           class="col-sm-2 col-form-label"
    >
        @lang('title.start')
    </label>
    <div class="col-sm-10">
        <input type="text"
               class="form-control datetimepicker-input"
               id="start"
               name="start"
               placeholder="YYYY-MM-DD HH:MM:SS"
               value="{{ old('start', $event->start) }}"
               data-toggle="datetimepicker"
               data-target="#start"
        >
    </div>
</div>
<div class="row mb-3">
    <label for="end"
           class="col-sm-2 col-form-label"
    >
        @lang('title.end')
    </label>
    <div class="col-sm-10">
        <input type="text"
               class="form-control datetimepicker-input"
               id="end"
               name="end"
               placeholder="YYYY-MM-DD HH:MM:SS"
               value="{{ old('end', $event->end) }}"
               data-toggle="datetimepicker"
               data-target="#end"
        >
    </div>
</div>

<div class="row mb-3">
    <label for="signups_open"
           class="col-sm-2 col-form-label"
    >
        @lang('title.signups-open')
    </label>
    <div class="col-sm-10">
        <input type="text"
               class="form-control datetimepicker-input"
               id="signups_open"
               name="signups_open"
               placeholder="YYYY-MM-DD HH:MM:SS"
               value="{{ old('signups_open', $event->signups_open) }}"
               data-toggle="datetimepicker"
               data-target="#signups_open"
        >
    </div>
</div>
<div class="row mb-3">
    <label for="signups_close"
           class="col-sm-2 col-form-label"
    >
        @lang('title.signups-close')
    </label>
    <div class="col-sm-10">
        <input type="text"
               class="form-control datetimepicker-input"
               id="signups_close"
               name="signups_close"
               placeholder="YYYY-MM-DD HH:MM:SS"
               value="{{ old('signups_close', $event->signups_close) }}"
               data-toggle="datetimepicker"
               data-target="#signups_close"
        >
    </div>
</div>

<div class="row mb-3">
    <div class="col-sm-10 offset-sm-2">
        <div class="form-check">
            <input type="checkbox"
                   class="form-check-input"
                   id="published"
                   name="published"
                   value="1"
                {{ old('published', $event->published) ? 'checked' : null}}
            >
            <label class="form-check-label"
                   for="published"
            >
                @lang('title.published')
            </label>
        </div>
    </div>
</div>
<button type="submit" class="btn btn-primary">@lang('title.submit')</button>
<script>
    document.addEventListener("DOMContentLoaded", function () {

        const startInput = document.getElementById('start');
        const start = new TempusDominus(startInput, {
            localization: {
                format: 'yyyy-MM-dd HH:mm',
                dayViewHeaderFormat: {month: 'long', year: 'numeric'},
            },
            display: {
                sideBySide: true,
                theme: "dark",
                buttons: {
                    today: true,
                    clear: true,
                },
            },
        });
        if (startInput.value) {
            start.dates.setValue(start.dates.parseInput(startInput.value));
        }

        const endInput = document.getElementById('end');
        const end = new TempusDominus(endInput, {
            localization: {
                format: 'yyyy-MM-dd HH:mm',
                dayViewHeaderFormat: {month: 'long', year: 'numeric'},
            },
            display: {
                sideBySide: true,
                theme: "dark",
                buttons: {
                    today: true,
                    clear: true,
                },
            },
            useCurrent: false,
        });
        if (endInput.value) {
            end.dates.setValue(end.dates.parseInput(endInput.value));
        }

        const signupsOpenInput = document.getElementById('signups_open');
        const signupsOpen = new TempusDominus(signupsOpenInput, {
            localization: {
                format: 'yyyy-MM-dd HH:mm',
                dayViewHeaderFormat: {month: 'long', year: 'numeric'},
            },
            display: {
                sideBySide: true,
                theme: "dark",
                buttons: {
                    today: true,
                    clear: true,
                },
            },
        });
        if (signupsOpenInput.value) {
            signupsOpen.dates.setValue(signupsOpen.dates.parseInput(signupsOpenInput.value));
        }

        const signupsCloseInput = document.getElementById('signups_close');
        const signupsClose = new TempusDominus(signupsCloseInput, {
            localization: {
                format: 'yyyy-MM-dd HH:mm',
                dayViewHeaderFormat: {month: 'long', year: 'numeric'},
            },
            display: {
                sideBySide: true,
                theme: "dark",
                buttons: {
                    today: true,
                    clear: true,
                },
            },
            useCurrent: false,
        });
        if (signupsCloseInput.value) {
            signupsClose.dates.setValue(signupsClose.dates.parseInput(signupsCloseInput.value));
        }

        startInput.addEventListener(Namespace.events.change, (e) => {
            end.updateOptions({
                restrictions: {
                    minDate: e.detail.date,
                },
            });
            signupsOpen.updateOptions({
                restrictions: {
                    maxDate: e.detail.date,
                },
            });
        });

        signupsOpenInput.addEventListener(Namespace.events.change, (e) => {
            signupsClose.updateOptions({
                restrictions: {
                    minDate: e.detail.date,
                },
            });
        });
    });
</script>
