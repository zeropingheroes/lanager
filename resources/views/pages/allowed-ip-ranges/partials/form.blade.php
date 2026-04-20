<div class="row mb-3">
    <label for="ip_range"
           class="col-sm-2 col-form-label"
    >
        @lang('title.ip-range')
    </label>
    <div class="col-sm-10">
        <input type="text"
               class="form-control"
               id="ip_range"
               name="ip_range"
               placeholder="@lang('title.ip-range')"
               value="{{ old('ip_range', $allowedIpRange->ip_range) }}"
        >
    </div>
</div>

<div class="row mb-3">
    <label
        for="description"
        class="col-sm-2 col-form-label"
    >
        @lang('title.description')
    </label>
    <div class="col-sm-10">
        <input
            type="text"
            class="form-control"
            id="description"
            name="description"
            placeholder="@lang('title.description')"
            value="{{ old('description', $allowedIpRange->description) }}"
        >
    </div>
</div>
@include('components.form.inputs.submit')
