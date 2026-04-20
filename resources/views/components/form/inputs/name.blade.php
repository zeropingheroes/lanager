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
               value="{{ old('name', $value) }}"
        >
    </div>
</div>
