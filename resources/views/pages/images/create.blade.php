@extends('layouts.default')

@section('title')
    @lang('title.upload-images')
@endsection

@section('content')
    <h1>@lang('title.upload-images')</h1>
    @include('components.alerts.all')
    @include('components.form.create', ['route' => route('images.store'), 'enctype' => 'multipart/form-data'])

    <h5>@lang('title.folder')</h5>
    <div class="row">
        <div class="form-group col">
            <label for="existingFolder">@lang('title.existing')</label>
            <div class="input-group">
                <div class="input-group-prepend">
                    <div class="input-group-text">
                        <input type="radio" name="folderType" value="existing" id="useExistingFolder">
                    </div>
                </div>
                <select class="custom-select" id="existingFolder" name="folder">
                    <option value=""></option>
                    @foreach($folders as $folder)
                        <option value="{{ $folder }}">{{ $folder }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="form-group col">
            <label for="newFolder">@lang('title.new')</label>
            <div class="input-group">
                <div class="input-group-prepend">
                    <div class="input-group-text">
                        <input type="radio" name="folderType" value="new" id="createNewFolder">
                    </div>
                </div>
                <input type="text" class="form-control" id="newFolder" name="folder">
            </div>
        </div>
    </div>
    <h5>@lang('title.files')</h5>
    <div class="form-group">
        <div class="custom-file">
            <input type="file" class="custom-file-input" id="images" name="images[]" multiple>
            <label class="custom-file-label" for="images">@lang('phrase.select-files')</label>
        </div>
    </div>

    <button type="submit" class="btn btn-primary">@lang('title.submit')</button>

    @include('components.form.close')
    <script type="text/javascript">
        window.onload = function () {
            $( "select#existingFolder" ).click(function() {
                $( "#useExistingFolder" ).prop( "checked", true );
            });
            $( "input#newFolder" ).click(function() {
                $( "#createNewFolder" ).prop( "checked", true );
            });
        }
    </script>

@endsection