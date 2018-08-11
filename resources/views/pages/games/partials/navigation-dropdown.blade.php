<div class="row align-items-center">
    <div class="col">
        <h1>@lang('title.'.$active)</h1>
    </div>
    <div class="col text-right">
        <div class="dropdown show">
            <a class="btn btn-secondary dropdown-toggle" href="#" role="button" id="gamesDisplayDropdown" data-toggle="dropdown"
               aria-haspopup="true" aria-expanded="false">
                @lang('title.'.$active)
            </a>
            <div class="dropdown-menu" aria-labelledby="gamesDisplayDropdown">
                <a class="dropdown-item @if($active == 'in-progress') active @endif" href="{{ route('games.in-progress') }}">@lang('title.in-progress')</a>
                <a class="dropdown-item @if($active == 'recent') active @endif" href="{{ route('games.recent') }}">@lang('title.recent')</a>
                <a class="dropdown-item @if($active == 'owned') active @endif" href="{{ route('games.owned') }}">@lang('title.owned')</a>
            </div>
        </div>
    </div>
</div>
