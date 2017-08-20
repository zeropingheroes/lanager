<?php
// default to small size
$size = empty($size) ? 'small' : $size;

switch ($size) {
    case 'small':
        $url = $user->present()->avatarSmall;
        break;
    case 'medium':
        $url = $user->present()->avatarMedium;
        break;
    case 'large':
        $url = $user->present()->avatarLarge;
        break;
    default:
        $url = $user->present()->avatarSmall;
}

if (isset($classes) && !is_array($classes)) $classes = [$classes];
$classes[] = 'avatar';
$classes[] = 'avatar-'.$size;

$state = $user->state;

if ($state) {
    if (isset($state->application_id)) {
        $classes[] = 'avatar-in-game';
        $title = 'In-Game: '.$state->application->name;
    }
    elseif ($state->status) {
        $classes[] = 'avatar-online';
        $title = $state->present()->statusText;
    }
    else {
        $classes[] = 'avatar-offline';
        $title = $state->present()->statusText;
    }
}
else {
    $classes[] = 'avatar-offline';
    $title = 'Status unknown';
}
?>
<img class="{{{ implode(' ', $classes) }}}" src="{{ $url }}" alt="Avatar for {{{ $user->username }}}" title="{{{ $title }}}">
