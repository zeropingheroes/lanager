@extends('layouts.default')

@section('title')
    @lang('title.edit-item', ['item' => __('title.game')])
@endsection

@section('content-header')
    <h1>@lang('title.edit-item', ['item' => __('title.game')])</h1>
@endsection

@section('content')
    <form method="POST" action="{{ route('lan-games.update', $lanGame) }}" accept-charset="UTF-8">
        {{ method_field('PUT') }}
        {{ csrf_field() }}
        <div class="row no-gutters">
            <div class="col">
                <input class="form-control"
                       type="search"
                       id="game_name"
                       name="game_name"
                       value="{{ old('game_name', $lanGame->game_name) }}"
                >
            </div>
            <div class="col-auto">
                <button class="btn btn-primary" type="submit">
                    @lang('title.submit')
                </button>
            </div>
        </div>
    </form>
@endsection
