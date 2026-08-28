@include('components.form.inputs.name', ['value' => $venue->name, 'required' => true])

<div class="row mb-3">
    @include('components.form.label', ['for' => 'street_address', 'text' => __('title.street-address'), 'required' => true])
    <div class="col-sm-10">
        <input type="text"
               class="form-control"
               id="street_address"
               name="street_address"
               placeholder="@lang('title.street-address')"
               value="{{ old('street_address', $venue->street_address) }}"
        >
    </div>
</div>
@include('components.form.required-legend')
@include('components.form.inputs.submit')
