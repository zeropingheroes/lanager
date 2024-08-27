<div class="form-group mb-3">
    <label for="name">@lang('title.name')</label>
    <input type="text" class="form-control" id="name" name="name" placeholder="@lang('title.name')"
           value="{{ old('name', $lan->name) }}">
</div>
<div class="form-row mb-3">
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
            });
            if (endInput.value) {
                end.dates.setValue(end.dates.parseInput(endInput.value));
            }

            startInput.addEventListener(Namespace.events.change, (e) => {
                end.updateOptions({
                    restrictions: {
                        minDate: e.detail.date,
                    },
                });
            });
        });
    </script>
    <div class="form-group col-md-6 mb-3">
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
<div class="form-group mb-3">
    <label for="venue_id">@lang('title.venue')</label>
    @include('components.form.select', ['name' => 'venue_id', 'item' => $lan, 'items' => $venues, 'labelField' => 'name', 'blank' => true])
</div>

<div class="form-group mb-3">
    <label for="achievement_id">@lang('title.lan-achievement')</label>
    @include('components.form.select', ['name' => 'achievement_id', 'item' => $lan, 'items' => $achievements, 'labelField' => 'name', 'blank' => true])
    <small id="achievement_id_help" class="form-text">
        @lang('phrase.lan-achievement-help')
    </small>
</div>
<div class="form-group mb-3">
    <div class="custom-control custom-checkbox">
        <input type="checkbox" class="custom-control-input" id="published" name="published"
               value="1" {{ old('published', $lan->published) ? 'checked' : null}}>
        <label class="custom-control-label" for="published">@lang('title.published')</label>
    </div>
</div>
<button type="submit" class="btn btn-primary">@lang('title.submit')</button>
