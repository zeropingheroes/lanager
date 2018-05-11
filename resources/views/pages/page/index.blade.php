@extends('layouts.default')

@section('title')
    @lang('title.pages')
@endsection

@section('content')
    <h1>@lang('title.pages')</h1>
    @include('components.alerts.all')

    @if( empty($pages))
        <p>@lang('phrase.no-items-found', ['item' => __('title.pages')])</p>
    @else
        <table class="table table-striped">
            <thead>
            <tr>
                <th>@lang('title.title')</th>
                <th>@lang('title.updated')</th>
                @if( Gate::allows('update', Zeropingheroes\Lanager\Page::class) ||
                     Gate::allows('destroy', Zeropingheroes\Lanager\Page::class) )
                    <th>
                        @lang('title.published')
                    </th>
                    <th>
                        @lang('title.actions')
                    </th>
                @endif
            </tr>
            </thead>
            <tbody>
            @foreach($pages as $page)
                <tr>
                    <td>
                        <a href="{{ route('pages.show', ['id' => $page->id, 'slug' => str_slug($page->title) ]) }}">{{ $page->title }}</a>
                    </td>
                    <td>
                        @include('components.time-relative', ['datetime' => $page->updated_at])
                    </td>
                    @can('update', Zeropingheroes\Lanager\Page::class)
                        <td>@include('components.tick-cross', ['value' => $page->published])</td>
                    @endcan
                    @if( Gate::allows('update', $page) || Gate::allows('destroy', $page) )
                        <td>
                            @component('components.actions-dropdown')
                                @include('components.actions-dropdown.edit', ['item' => $page])
                                @include('components.actions-dropdown.delete', ['item' => $page])
                            @endcomponent
                        </td>
                    @endif
                </tr>
            @endforeach
            </tbody>
        </table>
        @include('components.buttons.create', ['item' => Zeropingheroes\Lanager\Page::class])
    @endif
@endsection