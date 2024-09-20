@include('components.form.inputs.name', ['value' => $venue->name])

<div class="row mb-3">
    <label for="street_address"
           class="col-sm-2 col-form-label"
    >
        @lang('title.street-address')
    </label>
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
@include('components.form.inputs.submit')
