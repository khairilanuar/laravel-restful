<?php

return [
    'allow_public_registration' => true,
    //'default_registered_user_role' => null,

    'password_strength' => [
        'min_length'                  => 8,
        'require_lowercase_character' => true,
        'require_uppercase_character' => true,
        'require_numeric_character'   => true,
        'require_special_character'   => true,
    ],
];
