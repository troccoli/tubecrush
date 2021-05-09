<?php

return [
    'consent' => [
        'cookie_name' => 'tubecrush_cookie_consent',

        'consent_value' => 'yes',
        'refuse_value' => 'no',

        'consent_cookie_lifetime' => 60 * 24 * 365,
        'refuse_cookie_lifetime' => 60 * 24 * 30,
    ],
    'voting' => [
        'cookie_name' => 'tubecrush_cookie_voting',
        'cookie_lifetime' => 60 * 24 * 365,
    ]
];
