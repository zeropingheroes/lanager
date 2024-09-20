@include('components.form.inputs.name', ['value' => $lan->name])
@include('components.form.inputs.start-end', ['start' => $lan->start, 'end' => $lan->end])
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
@include('components.form.inputs.published', ['value' => $lan->published])
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
