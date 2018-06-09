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
                </tr>
            @endforeach
            </tbody>
        </table>
    @endif
@endsection