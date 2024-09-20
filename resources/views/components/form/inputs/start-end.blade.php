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
               value="{{ old('start', $start) }}"
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
               value="{{ old('end', $end) }}"
               data-toggle="datetimepicker"
               data-target="#end"
        >
    </div>
</div>
