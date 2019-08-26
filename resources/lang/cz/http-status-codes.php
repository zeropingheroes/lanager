<?php

return [

    /*
    |--------------------------------------------------------------------------
    | HTTP Status Codes
    |--------------------------------------------------------------------------
    |
    | The following language lines are used by the error pages to display
    | the correct error text corresponding to the status code.
    |
    | All descriptions have been taken from RFC 7231:
    | https://tools.ietf.org/html/rfc7231
    */

    '400-title' => 'Špatný požadavek',
    '400-description' => 'Požadavek nemůže být vyřízen, poněvadž byl syntakticky nesprávně zapsán.',

    '401-title' => 'Přístup odepřen',
    '401-description' => 'Jestliže byl původní požadavek klienta anonymní, musí být nyní autentizován. Pokud už požadavek byl autentizován, pak byl přistup odepřen.',

    '403-title' => 'Zakázáno',
    '403-description' => 'Server nemůže požadavku vyhovět, autorizace nebyla úspěšná.',

    '404-title' => 'Nenalezeno',
    '404-description' => 'Server nenašel zadanou adresu URL.',

    '405-title' => 'Metoda není přípustná',
    '405-description' => 'Použitá metoda není přípustná pro dosažení požadovaného objektu.',

    '422-title' => 'Nezpracovatelná entita',
    '422-description' => 'Požadavek byl správně naformátovaný, ale odpověď nebyla možná kvůli sémantické chybě.',

    '500-title' => 'Interní chyba na serveru',
    '500-description' => 'Na serveru došlo k neočekávané chybě.',

];
