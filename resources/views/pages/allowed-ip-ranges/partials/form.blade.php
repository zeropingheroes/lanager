<div class="row mb-3">
    @include('components.form.label', ['for' => 'ip_range', 'text' => __('title.ip-range'), 'required' => true])
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
    @include('components.form.label', ['for' => 'description', 'text' => __('title.description')])
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
@include('components.form.required-legend')
@include('components.form.inputs.submit')
