@include('components.form.inputs.name', ['value' => $event->name])
@include('components.form.inputs.description', ['value' => $event->description])
@include('components.form.inputs.start-end', ['start' => $event->start, 'end' => $event->end])

<div class="row mb-3">
    <label for="signups_open"
           class="col-sm-2 col-form-label"
    >
        @lang('title.signups-open')
    </label>
    <div class="col-sm-4">
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
    <label for="signups_close"
           class="col-sm-2 col-form-label"
    >
        @lang('title.signups-close')
    </label>
    <div class="col-sm-4">
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

@include('components.form.inputs.published', ['value' => $event->published])
@include('components.form.inputs.submit')

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
