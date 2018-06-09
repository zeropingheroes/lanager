@extends('layouts.default')

@section('title')
    @lang('title.upload-images')
@endsection

@section('content')
    <h1>@lang('title.upload-images')</h1>
    @include('components.alerts.all')
    @include('components.form.create', ['route' => route('images.store'), 'enctype' => 'multipart/form-data'])

    <div class="form-group">
        <label for="folder">@lang('title.folder')</label>
        <select class="custom-select" id="folder" name="folder">
            <option value=""></option>
            @foreach($folders as $folder)
                    <option value="{{ $folder }}">{{ $folder }}</option>
            @endforeach
        </select>
    </div>

    <div class="form-group">
        <div class="custom-file">
            <input type="file" class="custom-file-input" id="images" name="images[]" multiple>
            <label class="custom-file-label" for="images">@lang('phrase.select-files')</label>
        </div>
    </div>

    <button type="submit" class="btn btn-primary">@lang('title.submit')</button>

    @include('components.form.close')
@endsection