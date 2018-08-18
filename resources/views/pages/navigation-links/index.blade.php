@extends('layouts.default')

@section('title')
    @lang('title.navigation-links')
@endsection

@section('content-header')
    <h1>@lang('title.navigation-links')</h1>
    {{ Breadcrumbs::render('navigation-links.index') }}
@endsection

@section('content')
    @if( empty($navigationLinks))
        <p>@lang('phrase.no-items-found', ['item' => __('title.navigation-links')])</p>
    @else
        <table class="table table-striped">
            <thead>
            <tr>
                <th>@lang('title.title')</th>
                <th>@lang('title.url')</th>
                <th>@lang('title.position')</th>
                <th>@lang('title.actions')</th>
            </tr>
            </thead>
            <tbody>
            @foreach($navigationLinks as $navigationLink)
                @include('pages.navigation-links.partials.row', ['navigationLink' => $navigationLink])

                @if($navigationLink->children()->count())
                    @foreach($navigationLink->children()->orderBy('position')->get() as $childNavigationLink)
                        @include('pages.navigation-links.partials.row', ['navigationLink' => $childNavigationLink])
                    @endforeach
                @endif
            @endforeach
            </tbody>
        </table>
        @include('components.buttons.create', ['item' => Zeropingheroes\Lanager\NavigationLink::class])
    @endif

@endsection