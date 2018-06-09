@extends('layouts.default')

@section('title')
    @lang('title.images')
@endsection

@section('content')
    <h1>@lang('title.images')</h1>
    @include('components.alerts.all')

    @if( empty($images))
        <p>@lang('phrase.no-items-found', ['item' => __('title.images')])</p>
    @else
        <table class="table table-striped">
            <thead>
            <tr>
                <th>@lang('title.title')</th>
                <th>@lang('title.folder')</th>
                <th>@lang('title.markdown')</th>
            </tr>
            </thead>
            <tbody>
            @foreach($images as $image)
                <tr>
                    <td>
                        <a href="{{ $image['url'] }}" target="_blank">{{ $image['filename'] }}</a>
                    </td>
                    <td>
                        {{ $image['folder'] }}
                    </td>
                    <td>
                        <button class="btn btn-secondary btn-sm btn-copy-markdown"
                                type="button"
                                data-clipboard-text="[!Image description]({{$image['url']}})">
                            Copy
                        </button>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
        <script type="text/javascript">
            window.onload = function () {
                var clipboard = new Clipboard('.btn-copy-markdown');
                clipboard.on('success', function(e) {
                    e.trigger.innerHTML = 'Copied';
                    setTimeout(function () {
                        e.trigger.innerHTML = 'Copy';
                    }, 3000);
                });
                clipboard.on('error', function(e) {
                    console.error('Action:', e.action);
                    console.error('Trigger:', e.trigger);
                    e.trigger.innerHTML = 'Copy Error';
                });
            }
        </script>
    @endif
@endsection