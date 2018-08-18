@extends('layouts.default')

@section('title')
    @lang('title.log') #{{ $log->id }}
@endsection

@section('content-header')
    <h1>@lang('title.log') #{{ $log->id }} @include('pages.logs.partials.level', ['level' => $log->level_name])</h1>
@endsection

@section('content')
    <ul class="nav nav-tabs" id="myTab" role="tablist">
        <li class="nav-item">
            <a class="nav-link active" id="home-tab" data-toggle="tab" href="#html" role="tab" aria-controls="html" aria-selected="true">@lang('title.html')</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="profile-tab" data-toggle="tab" href="#markdown" role="tab" aria-controls="markdown" aria-selected="false">@lang('title.markdown')</a>
        </li>
    </ul>
    <div class="tab-content" id="myTabContent">
        <div class="tab-pane fade show active" id="html" role="tabpanel" aria-labelledby="html-tab">
            <h2>@lang('title.message')</h2>
            <code>{{ $log->message }}</code>

            <h2>@lang('title.time')</h2>
            <code>@include('components.time-datetime', ['datetime' => $log->created_at])</code>

            <h2>@lang('title.data')</h2>
            @php
                $data = [
                'level' => $log->level_name,
                'message' => $log->message,
                'time' => (string) $log->created_at,
                'user' => $log->user->username,
                'context' => json_decode($log->context,true)
            ];
            @endphp
            <code>
                <pre>{{ var_export_short($data) }}</pre>
            </code>
        </div>
        <div class="tab-pane fade" id="markdown" role="tabpanel" aria-labelledby="markdown-tab">
            <p class="mt-2">
                <a href="https://github.com/zeropingheroes/lanager/issues/new" target="_blank">@lang('phrase.paste-below-into-github-issue')</a>
            </p>
            <pre>
## @lang('title.message')


```
{{ $log->message }}
```


## @lang('title.data')


```php
{{ var_export_short($data) }}
```

</pre>
        </div>
    </div>

@endsection