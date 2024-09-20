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
               value="{{ old('name', $lan->name) }}"
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
               class="form-control datetimepicker-input"
               id="start"
               name="start"
               placeholder="YYYY-MM-DD HH:MM:SS"
               value="{{ old('start', $lan->start) }}"
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
               class="form-control datetimepicker-input"
               id="end"
               name="end"
               placeholder="YYYY-MM-DD HH:MM:SS"
               value="{{ old('end', $lan->end) }}"
               data-toggle="datetimepicker"
               data-target="#end"
        >
    </div>
</div>

<div class="row mb-3">
    <label for="venue_id"
           class="col-sm-2 col-form-label"
    >
        @lang('title.venue')
    </label>
    <div class="col-sm-10">
        @include('components.form.select', [
            'name' => 'venue_id',
            'item' => $lan,
            'items' => $venues,
            'labelField' => 'name',
            'blank' => true,
            ])
    </div>
</div>
<div class="row mb-3">
    <label for="achievement_id"
           class="col-sm-2 col-form-label"
    >
        @lang('title.lan-achievement')
    </label>
    <div class="col-sm-10">
        @include('components.form.select',[
            'name' => 'achievement_id',
            'item' => $lan,
            'items' => $achievements,
            'labelField' => 'name',
            'blank' => true,
        ])
        <small id="achievement_id_help"
               class="form-text"
        >
            @lang('phrase.lan-achievement-help')
        </small>
    </div>
</div>
<div class="row mb-3">
    <label for="published"
           class="col-sm-2 col-form-label"
    >
        @lang('title.published')
    </label>
    <div class="col-sm-10">
        <div class="form-check">
            <input type="checkbox"
                   class="form-check-input"
                   id="published"
                   name="published"
                   value="1"
                {{ old('published', $lan->published) ? 'checked' : null}}
            >
        </div>
    </div>
</div>

<div class="row mb-3">
    <div class="offset-sm-2 d-grid col-sm-10 gap-2">
        <button type="submit" class="btn btn-primary">@lang('title.submit')</button>
    </div>
</div>
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
