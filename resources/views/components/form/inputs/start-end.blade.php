@php $required = $required ?? false; @endphp
<div class="row mb-3">
    @include('components.form.label', ['for' => 'start', 'text' => __('title.start'), 'required' => $required])
    <div class="col-sm-4">
        <input type="text"
               class="form-control datetimepicker-input"
               id="start"
               name="start"
               placeholder="YYYY-MM-DD HH:MM"
               value="{{ old('start', $start) }}"
               data-toggle="datetimepicker"
               data-target="#start"
        >
    </div>
    @include('components.form.label', ['for' => 'end', 'text' => __('title.end'), 'required' => $required])
    <div class="col-sm-4">
        <input type="text"
               class="form-control datetimepicker-input"
               id="end"
               name="end"
               placeholder="YYYY-MM-DD HH:MM"
               value="{{ old('end', $end) }}"
               data-toggle="datetimepicker"
               data-target="#end"
        >
    </div>
</div>
