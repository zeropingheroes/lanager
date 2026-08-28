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
        @include('components.form.label', ['for' => 'filename', 'text' => __('title.filename'), 'required' => true])
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
    @include('components.form.required-legend')
    @include('components.form.inputs.submit')
    @include('components.form.close')
@endsection
