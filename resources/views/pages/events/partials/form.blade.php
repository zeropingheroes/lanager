@vite('resources/js/pages/event-form.js')
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
               placeholder="YYYY-MM-DD HH:MM"
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
               placeholder="YYYY-MM-DD HH:MM"
               value="{{ old('signups_close', $event->signups_close) }}"
               data-toggle="datetimepicker"
               data-target="#signups_close"
        >
    </div>
</div>

@include('components.form.inputs.published', ['value' => $event->published])
@include('components.form.inputs.submit')
