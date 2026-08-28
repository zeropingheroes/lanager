@php $required = $required ?? false; @endphp
<div class="row mb-3">
    @include('components.form.label', ['for' => 'name', 'text' => __('title.name'), 'required' => $required])
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
