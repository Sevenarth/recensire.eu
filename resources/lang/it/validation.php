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

    'accepted'             => 'Il campo :attribute deve essere accettato.',
    'active_url'           => 'Il campo :attribute is not a valid URL.',
    'after'                => 'Il campo :attribute must be a date after :date.',
    'after_or_equal'       => 'Il campo :attribute must be a date after or equal to :date.',
    'alpha'                => 'Il campo :attribute may only contain letters.',
    'alpha_dash'           => 'Il campo :attribute may only contain letters, numbers, and dashes.',
    'alpha_num'            => 'Il campo :attribute may only contain letters and numbers.',
    'array'                => 'Il campo :attribute deve essere una lista.',
    'before'               => 'Il campo :attribute must be a date before :date.',
    'before_or_equal'      => 'Il campo :attribute must be a date before or equal to :date.',
    'between'              => [
        'numeric' => 'Il campo :attribute must be between :min and :max.',
        'file'    => 'Il campo :attribute must be between :min and :max kilobytes.',
        'string'  => 'Il campo :attribute must be between :min and :max characters.',
        'array'   => 'Il campo :attribute must have between :min and :max items.',
    ],
    'boolean'              => 'Il campo :attribute deve essere vero o falso.',
    'confirmed'            => 'Il campo :attribute deve essere uguale alla conferma.',
    'date'                 => 'Il campo :attribute non è una data valida.',
    'date_format'          => 'Il campo :attribute non rispetta il formato :format.',
    'different'            => 'Il campo :attribute e :other devono essere diversi.',
    'digits'               => 'Il campo :attribute must be :digits digits.',
    'digits_between'       => 'Il campo :attribute must be between :min and :max digits.',
    'dimensions'           => 'Il campo :attribute has invalid image dimensions.',
    'distinct'             => 'Il campo :attribute ha un duplicato.',
    'email'                => 'Il campo :attribute deve essere un indirizzo email valido.',
    'exists'               => 'Il campo selezionato :attribute non è valido.',
    'file'                 => 'Il campo :attribute deve essere un file.',
    'filled'               => 'Il campo :attribute deve avere un valore.',
    'image'                => 'Il campo :attribute deve essere una immagine.',
    'in'                   => 'Il campo selezionato :attribute non è valido.',
    'in_array'             => 'Il campo :attribute field does not exist in :other.',
    'integer'              => 'Il campo :attribute deve essere un numero intero.',
    'ip'                   => 'Il campo :attribute must be a valid IP address.',
    'ipv4'                 => 'Il campo :attribute must be a valid IPv4 address.',
    'ipv6'                 => 'Il campo :attribute must be a valid IPv6 address.',
    'json'                 => 'Il campo :attribute must be a valid JSON string.',
    'max'                  => [
        'numeric' => 'Questo campo non può essere maggiore di :max.',
        'file'    => 'Il campo :attribute may not be greater than :max kilobytes.',
        'string'  => 'Questo campo non può avere più di :max caratteri.',
        'array'   => 'Il campo :attribute may not have more than :max items.',
    ],
    'mimes'                => 'Il campo :attribute must be a file of type: :values.',
    'mimetypes'            => 'Il campo :attribute must be a file of type: :values.',
    'min'                  => [
        'numeric' => 'Il campo :attribute deve essere almeno :min.',
        'file'    => 'Il campo :attribute deve essere di almeno :min kilobytes.',
        'string'  => 'Il campo :attribute deve essere di almeno :min caratteri.',
        'array'   => 'Il campo :attribute deve avere almeno :min elementi.',
    ],
    'not_in'               => 'Il campo selezionato :attribute non è valido.',
    'numeric'              => 'Il campo :attribute deve essere un numero.',
    'present'              => 'Il campo :attribute deve essere presente.',
    'regex'                => 'Il campo :attribute ha un formato non valido.',
    'required'             => 'Il campo :attribute è obbligatorio.',
    'required_if'          => 'Il campo :attribute è richiesto quando :other è :value.',
    'required_unless'      => 'Il campo :attribute è richiesto almenoché :other è in :values.',
    'required_with'        => 'Il campo :attribute è richiesto quando :values è prese.',
    'required_with_all'    => 'Il campo :attribute è richiesto quando :values è prese.',
    'required_without'     => 'Il campo :attribute è richiesto quando :values non è presente.',
    'required_without_all' => 'Il campo :attribute è richiesto quando nessun :values è presente.',
    'same'                 => 'Il campo :attribute e :other devono essere uguali.',
    'size'                 => [
        'numeric' => 'Il campo :attribute deve essere :size.',
        'file'    => 'Il campo :attribute deve essere :size kilobytes.',
        'string'  => 'Il campo :attribute deve essere :size caratteri.',
        'array'   => 'Il campo :attribute deve contenere :size elementi.',
    ],
    'string'               => 'Il campo :attribute deve essere una string.',
    'timezone'             => 'Il campo :attribute deve essere una valida zona.',
    'unique'               => 'Il campo :attribute esiste già nel sistema.',
    'uploaded'             => 'Il campo :attribute ha fallito a caricare.',
    'url'                  => 'Il campo :attribute ha un formato invalido.',

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
