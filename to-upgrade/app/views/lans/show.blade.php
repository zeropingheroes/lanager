@extends('layouts.default')
@section('content')

    <h1>
        {{{ $lan->name }}}
        <small>
            {{ $lan->present()->monthYear }}
            @include('permalink', ['url' => URL::route('lans.show', $lan->id)])
        </small>
    </h1>

    @include('layouts.default.alerts')

    <h3>
        {{ $lan->present()->timespan }}
        <small>{{ $lan->present()->duration }}</small>
    </h3>

    <h4>Achievements Awarded</h4>
    @include('user-achievements.partials.list', ['userAchievements' => $lan->userAchievements])

    <hr>

    @include('buttons.edit', ['resource' => 'lans', 'item' => $lan, 'size' => 'extraSmall'])
    @include('buttons.destroy', ['resource' => 'lans', 'item' => $lan, 'size' => 'extraSmall'])

@endsection				
