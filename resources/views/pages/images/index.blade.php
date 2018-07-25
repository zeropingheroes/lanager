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
            var clipboard = new Clipboard('.copy-markdown');
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
    <a href="{{ route( 'images.create') }}" class="btn btn-primary">@lang('title.upload')</a>
@endsection