@extends('layouts.default')

@section('title')
    @lang('title.log-entry') {{ $log->id }}
@endsection

@section('content')

    <h1>@lang('title.log-entry') {{ $log->id }}</h1>

    <div>
        <strong>@lang('title.time')</strong> @include('components.time-relative', ['datetime' => $log->created_at])
    </div>
    <div>
        <strong>@lang('title.level')</strong> @include('pages.log.partials.level', ['level' => $log->level_name])
    </div>
    @if($log->user)
        <div>
            <strong>@lang('title.user')</strong>
            @include('pages.user.partials.avatar-username', ['user' => $log->user])
        </div>
    @endif

    <h2>@lang('title.message')</h2>
    <div>
        <code>{{ $log->message }}</code>
    </div>

    @if($log->context != "[]")
        <h2>@lang('title.context')</h2>
        <div>
            @foreach(json_decode($log->context) as $key => $value)
                <h4>{{ $key }}</h4>
                @php dump($value) @endphp
            @endforeach
        </div>
    @endif
    {{--@include('pages.log.partials.github', ['log' => $log])--}}

@endsection
