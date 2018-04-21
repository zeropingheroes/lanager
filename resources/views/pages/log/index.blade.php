@extends('layouts.default')

@section('title')
    @lang('title.logs')
@endsection

@section('content')

    <h1>@lang('title.logs')</h1>
    @if(count($logs))
        <table class="table table-striped">
            <tbody>
            @foreach( $logs as $log )
                <tr>
                    <td>
                        @include('components.time-datetime', ['datetime' => $log->created_at])
                    </td>
                    <td>
                        @include('pages.log.partials.level', ['level' => $log->level_name])
                    </td>
                    <td>
                        {{ $log->message }}
                    </td>
                    <td>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    @else
        <p>@lang('phrase.no-items-found', ['item' => __('title.logs')])</p>
    @endif

@endsection