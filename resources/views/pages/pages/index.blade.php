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
                        <a href="{{ route('lans.pages.show', ['lan' => $page->lan, 'page' => $page->id, 'slug' => str_slug($page->title) ]) }}">{{ $page->title }}</a>
                    </td>
                    <td>
                        @include('components.time-relative', ['datetime' => $page->updated_at])
                    </td>
                    @if( Gate::allows('update', $page) || Gate::allows('destroy', $page) )
                        <td>
                            @include('components.tick-cross', ['value' => $page->published])
                        </td>
                        <td>
                            @component('components.actions-dropdown')
                                <a class="dropdown-item copy-markdown"
                                   href="#"
                                   data-clipboard-text="[{{ $page->title }}]({{ sprintf('/lans/%s/pages/%s/%s',$page->lan_id, $page->id, str_slug($page->title)) }})">
                                    @lang('title.copy-markdown-link')
                                </a>
                                <a href="{{ route('lans.pages.edit', ['lan' => $page->lan, 'page' => $page->id]) }}" class="dropdown-item">@lang('title.edit')</a>
                                <form action="{{ route('lans.pages.destroy', ['lan' => $page->lan, 'page' => $page->id]) }}" method="POST">
                                    {{ method_field('DELETE') }}
                                    {{ csrf_field() }}
                                    <a class="dropdown-item" href="#" onclick="$(this).closest('form').submit();">@lang('title.delete')</a>
                                </form>

                            @endcomponent
                        </td>
                    @endif
                </tr>
            @endforeach
            </tbody>
        </table>
        @can('create', Zeropingheroes\Lanager\Page::class)
            <a href="{{ route( 'lans.pages.create', $lan->id) }}" class="btn btn-primary">@lang('title.create')</a>
        @endcan

        <script type="text/javascript">
            window.onload = function () {
                // Copy to clipboard button
                var clipboard = new Clipboard('.copy-markdown');
                clipboard.on('error', function(e) {
                    console.error('Action:', e.action);
                    console.error('Trigger:', e.trigger);
                });
            }
        </script>
    @endif
@endsection