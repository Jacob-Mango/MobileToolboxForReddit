<?php

// Make a development.php and production.php file using this template.

return [
    'twig' => [
        'debug' => true // true or false
    ],
    'csrf' => [
        'session' => 'csrf_token' // some token here
    ],
    'reddit' => [
        'client_id' => '', // obtain from reddit
        'secret' => '', // obtain from reddit
        'user_agent' => '' // make this a proper user agent, follow the protocol set out by the API agreements please
    ]
];