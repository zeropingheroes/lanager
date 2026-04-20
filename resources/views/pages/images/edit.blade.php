@extends('layouts.default')

@section('title')
    @lang('title.edit-item', ['item' => __('title.image')])
@endsection

@section('content-header')
    <h1>@lang('title.edit-item', ['item' => __('title.image')])</h1>
    {{ Breadcrumbs::render('images.edit', $image['filename']) }}
@endsection

@section('content')
    @include('components.form.edit', ['route' => route('images.update', $image['filename'])])
    <div class="row mb-3">
        <label for="filename"
               class="col-sm-2 col-form-label"
        >
            @lang('title.filename')
        </label>
        <div class="col-sm-10">
            <input name="filename"
                   type="text"
                   class="form-control"
                   placeholder="@lang('title.filename')"
                   aria-describedby="extension"
                   value="{{ old('filename', $image['filename']) }}"
            >
        </div>
    </div>
    @include('components.form.inputs.submit')
    @include('components.form.close')
@endsection
