@extends('layouts.default')

@section('title')
    {{ $lan->name }}
@endsection

@section('content')

    <h1>
        {{ $lan->name }}
        <small class="text-muted">
            @include('pages.lans.partials.dates', ['lan' => $lan])
        </small>
    </h1>
    <h4>
        @include('pages.lans.partials.timespan', ['lan' => $lan])
        <small class="text-muted">
            @include('pages.lans.partials.duration', ['lan' => $lan])
        </small>
    </h4>

    @if( ! $lan->users->isEmpty())
        <h5>{{ $lan->users->count() }} @lang('title.attendees')</h5>

        <table class="table table-striped">
            <thead>
            <tr>
                <th>@lang('title.attendee')</th>
                <th>@lang('title.logged-in')</th>
            </tr>
            </thead>
            <tbody>
            @foreach($lan->users as $user)
                <tr>
                    <td>
                        @include('pages.users.partials.avatar-username', ['user' => $user])
                    </td>
                    <td>
                        @include('components.time-relative', ['datetime' => $user->attendance->created_at])
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    @endif

    @include('components.buttons.edit', ['item' => $lan])
    @include('components.buttons.delete', ['item' => $lan])

@endsection