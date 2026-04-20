@extends('layouts.default')

@section('title')
    @lang('title.images')
@endsection

@section('content-header')
    <h1>@lang('title.images')</h1>
    {{ Breadcrumbs::render('images.index') }}
@endsection

@section('content')
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
                        <a class="dropdown-item copy-to-clipboard"
                           href="#"
                           data-clipboard-text="![{{ ucwords(str_replace('-', ' ', pathinfo($image['filename'], PATHINFO_FILENAME))) }}]({{$image['url']}})">
                            @lang('title.copy-markdown')
                        </a>
                        <a href="{{ route( 'images.edit', $image['filename']) }}" class="dropdown-item">@lang('title.edit')</a>
                        <form action="{{ route( 'images.destroy', $image['filename']) }}" method="POST">
                            {{ method_field('DELETE') }}
                            {{ csrf_field() }}
                            <a class="dropdown-item" href="#"
                               onclick="submitDeletionForm(event);">@lang('title.delete')</a>
                        </form>
                    @endcomponent
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>

    <form method="POST" action="{{ route('images.store') }}" accept-charset="UTF-8" enctype="multipart/form-data">
        {{ csrf_field() }}
        <div class="input-group mb-3">
            <input type="file" class="form-control" id="images" name="images[]" multiple>
            <button type="submit" class="btn btn-primary">@lang('title.upload')</button>
        </div>
    </form>

@endsection
