<?php

if (isset($application)) {
    $size = (isset($size) ? $size : '');

    switch ($size) {
        case 'small':
            $logoUrl = $application->present()->smallLogo;
            break;
        case 'medium':
            $logoUrl = $application->present()->mediumLogo;
            break;
        case 'large':
            $logoUrl = $application->present()->largeLogo;
            break;
        default:
            $logoUrl = $application->present()->smallLogo;
            break;
    }

    echo '<a href="'.$application->present()->url.'" title="'.e($application->name).'">';
    echo '<img src="'.$logoUrl.'">';
    echo '</a>';
}