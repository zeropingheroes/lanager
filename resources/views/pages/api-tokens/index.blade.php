@extends('layouts.default')

@section('title')
    @lang('title.api-tokens')
@endsection

@section('content-header')
    <div class="row align-items-center">
        <div class="col">
            <h1>@lang('title.api-tokens')</h1>
        </div>
    </div>
    {{ Breadcrumbs::render('api-tokens.index') }}
@endsection

@section('content')
    @if(session('new_api_token'))
        <div class="alert alert-warning">
            <p class="mb-1">@lang('phrase.copy-api-token-now')</p>
            <div class="d-flex align-items-center gap-2">
                <code class="text-break">{{ session('new_api_token') }}</code>
                <button type="button"
                        class="btn btn-sm btn-outline-secondary copy-to-clipboard flex-shrink-0"
                        data-clipboard-text="{{ session('new_api_token') }}"
                        title="@lang('title.copy')"
                >
                    <i class="fa-solid fa-copy"></i>
                </button>
            </div>
        </div>
    @endif

    <p>@lang('phrase.api-tokens-allow-external-access')</p>

    @include('pages.api-tokens.partials.list', ['tokens' => $tokens])
    @include('pages.api-tokens.partials.form')
@endsection
