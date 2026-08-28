<?php
$activeIcon = match ($active) {
    'in-progress' => 'fa-play',
    'recent' => 'fa-clock-rotate-left',
    'owned' => 'fa-wallet',
};
?>
<div class="row align-items-center">
    <div class="col">
        <h1>
        @switch($active)
            @case('in-progress')
                @lang('title.games-in-progress')
                @break
            @case('recent')
                @lang('title.recently-played-games')
                @break
            @case('owned')
                @lang('title.games-owned')
                @break
        @endswitch
        </h1>
    </div>
    <div class="col col-md-2 text-end">
        <div class="dropdown show">
            <a class="btn btn-secondary dropdown-toggle" href="#" role="button" id="gamesDisplayDropdown"
               data-bs-toggle="dropdown"
               aria-haspopup="true" aria-expanded="false">
                <i class="fa-solid {{ $activeIcon }}"></i> @lang('title.'.$active)
            </a>
            <div class="dropdown-menu" aria-labelledby="gamesDisplayDropdown">
                <a class="dropdown-item @if($active == 'in-progress') active @endif" href="{{ route('games.in-progress') }}"><i class="fa-solid fa-play"></i> @lang('title.in-progress')</a>
                <a class="dropdown-item @if($active == 'recent') active @endif" href="{{ route('games.recent') }}"><i class="fa-solid fa-clock-rotate-left"></i> @lang('title.recent')</a>
                <a class="dropdown-item @if($active == 'owned') active @endif" href="{{ route('games.owned') }}"><i class="fa-solid fa-wallet"></i> @lang('title.owned')</a>
            </div>
        </div>
    </div>
</div>
