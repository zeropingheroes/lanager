@extends('layouts.default')

@section('title')
    @lang('title.edit-item', ['item' => __('title.image')])
@endsection

@section('content')
    <h1>@lang('title.edit-item', ['item' => __('title.image')])</h1>
    @include('components.alerts.all')

    @include('components.form.edit', ['route' => route('images.update', $image['filename'])])
    <div class="form-group">
        <label for="filename">@lang('title.filename')</label>
        <input name="filename" type="text" class="form-control" placeholder="@lang('title.filename')" aria-describedby="extension"
               value="{{ old('filename', $image['filename']) }}">
    </div>
    <button type="submit" class="btn btn-primary">@lang('title.submit')</button>
    @include('components.form.close')

@endsection