<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error messages used by
    | the validator class. Some of these rules have multiple versions such
    | as the size rules. Feel free to tweak each of these messages here.
    |
    */

    'accepted'             => ':attribute musí být akceptován.',
    'active_url'           => ':attribute není validní URL.',
    'after'                => ':attribute musí být datum po :date.',
    'after_or_equal'       => ':attribute musí být datum po nebo rovno :date.',
    'alpha'                => ':attribute smí obsahovt pouze písmena.',
    'alpha_dash'           => ':attribute smí pouze obsahovat písmena, čísla a pomlčky.',
    'alpha_num'            => ':attribute smí pouze obsahovat písmena a čísla.',
    'array'                => ':attribute musí být polem.',
    'before'               => ':attribute musí být datum před :date.',
    'before_or_equal'      => ':attribute musí být datum před nebo rovno :date.',
    'between'              => [
        'numeric' => ':attribute musí být mezi :min a :max.',
        'file'    => ':attribute musí být mezi :min a :max kilobytes.',
        'string'  => ':attribute musí být mezi :min a :max characters.',
        'array'   => ':attribute musí mít mezi :min a :max items.',
    ],
    'boolean'              => ':attribute musí být pravda nebo nepravda (true/false).',
    'confirmed'            => ':attribute potvrzení nesouhlasí.',
    'date'                 => ':attribute není validní datum.',
    'date_format'          => ':attribute neodpovídá formátu :format.',
    'different'            => ':attribute a :other musí být rozdílné.',
    'digits'               => ':attribute musí mít :digits cifer.',
    'digits_between'       => ':attribute musí být mezi :min a :max digits.',
    'dimensions'           => ':attribute nemá validní rozměry obrázku.',
    'distinct'             => ':attribute pole má duplicitní hodnotu.',
    'email'                => ':attribute musí být validní emailová adresa.',
    'exists'               => 'Zvolený :attribute není validní.',
    'file'                 => ':attribute musí být soubor.',
    'filled'               => ':attribute pole musí mít hodnotu.',
    'image'                => ':attribute musí být obrázek.',
    'in'                   => 'Zvolený :attribute není validní.',
    'in_array'             => ':attribute pole neexistuje v :other.',
    'integer'              => ':attribute musí být integer.',
    'ip'                   => ':attribute musí být validní IP adresa.',
    'ipv4'                 => ':attribute musí být validní IPv4 adresa.',
    'ipv6'                 => ':attribute musí být validní IPv6 adresa.',
    'json'                 => ':attribute musí být validní JSON řetězec.',
    'max'                  => [
        'numeric' => ':attribute nesmí být vyšší než :max.',
        'file'    => ':attribute nesmí být vyšší než :max kilobytes.',
        'string'  => ':attribute nesmí být vyšší než :max characters.',
        'array'   => ':attribute nesmí mít více než :max položek.',
    ],
    'mimes'                => ':attribute musí být soubor typu: :values.',
    'mimetypes'            => ':attribute musí být soubor typu: :values.',
    'min'                  => [
        'numeric' => ':attribute musí být alespoň :min.',
        'file'    => ':attribute musí být alespoň :min kilobytes.',
        'string'  => ':attribute musí být alespoň :min characters.',
        'array'   => ':attribute musí mít alespoň :min položek.',
    ],
    'not_in'               => 'Zvolený :attribute není validní.',
    'not_regex'            => ':attribute formát není validní.',
    'numeric'              => ':attribute musí být číslo.',
    'present'              => ':attribute pole musí být přítomno.',
    'regex'                => ':attribute formát není validní.',
    'required'             => ':attribute vyplnění pole není vyžadováno.',
    'required_if'          => ':attribute vyplnění pole je vyžadováno když :other je :value.',
    'required_unless'      => ':attribute vyplnění pole je vyžadováno ledaže :other je v :values.',
    'required_with'        => ':attribute vyplnění pole je vyžadováno když :values je přítomný.',
    'required_with_all'    => ':attribute vyplnění pole je vyžadováno když :values je přítomný.',
    'required_without'     => ':attribute vyplnění pole je vyžadováno když :values není přítomný.',
    'required_without_all' => ':attribute vyplnění pole je vyžadováno když žádné z :values nejsou přítomny.',
    'same'                 => ':attribute a :other se musí shodovat.',
    'size'                 => [
        'numeric' => ':attribute musí být :size.',
        'file'    => ':attribute musí být :size kilobajtů.',
        'string'  => ':attribute musí být :size znaků.',
        'array'   => ':attribute must contain :size položek.',
    ],
    'string'               => ':attribute musí být a string.',
    'timezone'             => ':attribute musí být validní časové pásmo.',
    'unique'               => ':attribute už bylo použito.',
    'uploaded'             => ':attribute se nepodařilo nahrát.',
    'url'                  => ':attribute formát není validní.',

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Here you may specify custom validation messages for attributes using the
    | convention "attribute.rule" to name the lines. This makes it quick to
    | specify a specific custom language line for a given attribute rule.
    |
    */

    'custom' => [
        'attribute-name' => [
            'rule-name' => 'custom-message',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap attribute place-holders
    | with something more reader friendly such as E-Mail Address instead
    | of "email". This simply helps us make messages a little cleaner.
    |
    */

    'attributes' => [],

];
