@extends('layouts.default')

@section('title')
    @lang('title.login')
@endsection

@section('content')

    <div class="text-center">
      <h1 class="h3 mt-3 mb-3 font-weight-normal">@lang('title.login')</h1>

      <p class="mb-3">@lang('phrase.please-sign-in')</p>

      <a href="{{ url('/auth/steam') }}">
        <img src="{{ asset('img/sits_small.png') }}" alt="@lang('title.sign-in-steam')" class="img-fluid mx-auto">
      </a>

      <p class="mt-4">
        @lang('phrase.no-steam-account') <a href="https://store.steampowered.com/join/">@lang('phrase.create-steam-account')</a>
      </p>

      <a href="{{ url('/auth/discord') }}">
        @lang('title.sign-in-discord')
      </a>
    </div>
@endsection
