@extends('layouts.default')

@section('title')
    @lang('title.logs')
@endsection

@section('content')

    <h1>@lang('title.logs')</h1>
    @if(count($logs))

        @include('pages.log.partials.filter')

        <table class="table table-striped">
            <thead>
                <tr>
                    <th>@lang('title.time')</th>
                    <th>@lang('title.level')</th>
                    <th>@lang('title.message')</th>
                    <th>@lang('title.user')</th>
                </tr>
            </thead>
            <tbody>
            @foreach( $logs as $log )
                <tr>
                    <td>
                        @include('components.time-relative', ['datetime' => $log->created_at])
                    </td>
                    <td>
                        @include('pages.log.partials.level', ['level' => $log->level_name])
                    </td>
                    <td>
                        <a href="{{ route('logs.show', $log->id) }}">
                        {{ str_limit($log->message, 96) }}
                        </a>
                    </td>
                    <td>
                        @if($log->user)
                            @include('pages.user.partials.avatar-username', ['user' => $log->user])
                        @endif
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>

        {{ $logs->links() }}
    @else
        <p>@lang('phrase.no-items-found', ['item' => __('title.logs')])</p>
    @endif

@endsection