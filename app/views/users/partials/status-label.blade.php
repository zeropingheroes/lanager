<?php
if (isset($state)) {
    switch ($state->status) {
        case 1:
            if (is_null($state->application_id)) $labelType = 'info';
            if (!is_null($state->application_id)) $labelType = 'success';
            break;
        case 2:
            $labelType = 'warning';
            break;
        case 3:
            $labelType = 'warning';
            break;
        case 4:
            $labelType = 'warning';
            break;
        case 5:
            $labelType = 'info';
            break;
        case 6:
            $labelType = 'info';
            break;
        case 0:
            $labelType = 'normal';
            break;
        default:
            $labelType = 'normal';
    }

    echo Label::{$labelType}($state->present()->statusText);
}
else {
    echo Label::normal('Status unknown');
}
?>