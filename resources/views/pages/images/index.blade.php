@extends('layouts.default')

@section('title')
    @lang('title.images')
@endsection

@section('content')
    <h1>@lang('title.images')</h1>
    @include('components.alerts.all')

    <table class="table table-striped">
        <thead>
        <tr>
            <th>@lang('title.name')</th>
            <th>@lang('title.actions')</th>
        </tr>
        </thead>
        <tbody>
        @foreach($images as $image)
            <tr>
                <td>
                    <a href="{{ $image['url'] }}" target="_blank">{{ $image['filename'] }}</a>
                </td>
                <td>
                    @component('components.actions-dropdown')
                        <a class="dropdown-item copy-markdown"
                           href="#"
                           data-clipboard-text="![{{ ucwords(str_replace('-', ' ', $image['filename'])) }}]({{$image['url']}})">
                            @lang('title.copy')
                        </a>
                        <form action="{{ route( 'images.destroy', $image['filename']) }}" method="POST">
                            {{ method_field('DELETE') }}
                            {{ csrf_field() }}
                            <a class="dropdown-item" href="#" onclick="$(this).closest('form').submit();">@lang('title.delete')</a>
                        </form>
                    @endcomponent
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
    <script type="text/javascript">
        window.onload = function () {
            // Copy to clipboard button
            var clipboard = new Clipboard('.copy-markdown');
            clipboard.on('error', function(e) {
                console.error('Action:', e.action);
                console.error('Trigger:', e.trigger);
            });

            // Show selected files in file input label
            $("input[type=file]").change(function () {
                var files = $(this).prop("files");
                var fieldVal = $.map(files, function(val) { return ' ' + val.name; });
                if (fieldVal != undefined || fieldVal != "") {
                    $(this).next(".custom-file-label").text(fieldVal);
                }
            });
        }
    </script>

    <form method="POST" action="{{ route('images.store') }}" accept-charset="UTF-8" enctype="multipart/form-data">
        {{ csrf_field() }}
        <div class="row">
            <div class="col">
                <div class="input-group">
                    <div class="custom-file mr-2">
                        <input type="file" class="custom-file-input" id="images" name="images[]" multiple>
                        <label class="custom-file-label" for="images">@lang('phrase.select-files')</label>
                    </div>
                    <button type="submit" class="btn btn-primary">@lang('title.upload')</button>
                </div>
            </div>
        </div>

    </form>

@endsection