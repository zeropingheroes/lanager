@include('components.form.create', ['route' => route('api-tokens.store')])

<div class="row g-2 align-items-center">
    <div class="col">
        <input type="text"
               id="name"
               name="name"
               class="form-control"
               placeholder="@lang('title.name')"
               value="{{ old('name') }}"
        >
    </div>

    <div class="col-auto">
        <button type="submit" class="btn btn-primary">
            <i class="fa-solid fa-plus"></i> @lang('title.create-item', ['item' => trans('title.api-token')])
        </button>
    </div>
</div>

@include('components.form.close')
